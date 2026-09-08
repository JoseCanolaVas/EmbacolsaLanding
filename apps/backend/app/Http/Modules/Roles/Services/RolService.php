<?php

namespace App\Http\Modules\Roles\Services;

use App\Support\PermissionCatalog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolService
{
    public function listar(): array
    {
        return Role::with('permissions')
            ->where('guard_name', PermissionCatalog::GUARD)
            ->orderBy('display_name')
            ->orderBy('name')
            ->get()
            ->map(fn (Role $rol) => $this->mapearRol($rol))
            ->values()
            ->all();
    }

    public function crear(array $data): array
    {
        $datos = $this->validar($data);
        $slug = $datos['slug'] ?: Str::slug($datos['nombre'], '_');

        $rol = Role::create([
            'name' => $slug,
            'guard_name' => PermissionCatalog::GUARD,
        ]);

        $rol->forceFill([
            'display_name' => $datos['nombre'],
            'descripcion' => $datos['descripcion'] ?? null,
            'estado' => $datos['estado'] ?? true,
        ])->save();

        $rol->syncPermissions($datos['permisos'] ?? []);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $this->mapearRol($rol->fresh('permissions'));
    }

    public function actualizar(int $id, array $data): array
    {
        $rol = Role::where('guard_name', PermissionCatalog::GUARD)->findOrFail($id);
        $datos = $this->validar($data, $id);
        $slug = $datos['slug'] ?: Str::slug($datos['nombre'], '_');

        $rol->forceFill([
            'name' => $slug,
            'guard_name' => PermissionCatalog::GUARD,
            'display_name' => $datos['nombre'],
            'descripcion' => $datos['descripcion'] ?? null,
            'estado' => $datos['estado'] ?? true,
        ])->save();

        $rol->syncPermissions($datos['permisos'] ?? []);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $this->mapearRol($rol->fresh('permissions'));
    }

    public function agregarPermisos(int $id, array $permisos): array
    {
        $rol = Role::where('guard_name', PermissionCatalog::GUARD)->findOrFail($id);
        $rol->givePermissionTo($this->permisosValidos($permisos));
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $this->mapearRol($rol->fresh('permissions'));
    }

    public function removerPermisos(int $id, array $permisos): array
    {
        $rol = Role::where('guard_name', PermissionCatalog::GUARD)->findOrFail($id);
        $rol->revokePermissionTo($this->permisosValidos($permisos));
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $this->mapearRol($rol->fresh('permissions'));
    }

    public function permisosDisponibles(): array
    {
        $permisosDb = Permission::query()
            ->where('guard_name', PermissionCatalog::GUARD)
            ->orderBy('grupo')
            ->orderBy('label')
            ->get()
            ->map(fn (Permission $permiso) => [
                'id' => $permiso->id,
                'value' => $permiso->name,
                'name' => $permiso->name,
                'label' => $permiso->label ?: $this->etiquetaPermiso($permiso->name),
                'grupo' => $permiso->grupo ?: 'Otros',
                'descripcion' => $permiso->descripcion,
                'estado' => (bool) $permiso->estado,
            ])
            ->values()
            ->all();

        return count($permisosDb) ? $permisosDb : PermissionCatalog::permisos();
    }

    private function validar(array $data, ?int $id = null): array
    {
        $validator = Validator::make($data, [
            'nombre' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:120', Rule::unique('roles', 'name')->where('guard_name', PermissionCatalog::GUARD)->ignore($id)],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'permisos' => ['nullable', 'array'],
            'permisos.*' => ['string', Rule::exists('permissions', 'name')->where('guard_name', PermissionCatalog::GUARD)],
            'estado' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $datos = $validator->validated();
        $datos['permisos'] = array_values(array_unique($datos['permisos'] ?? []));
        $datos['estado'] = $datos['estado'] ?? true;
        $datos['slug'] = $datos['slug'] ?? null;

        return $datos;
    }

    private function mapearRol(Role $rol): array
    {
        return [
            'id' => $rol->id,
            'nombre' => $rol->display_name ?: $rol->name,
            'slug' => $rol->name,
            'descripcion' => $rol->descripcion,
            'estado' => (bool) $rol->estado,
            'guard_name' => $rol->guard_name,
            'permisos' => $rol->permissions->pluck('name')->values()->all(),
        ];
    }

    private function permisosValidos(array $permisos): array
    {
        return Permission::where('guard_name', PermissionCatalog::GUARD)
            ->whereIn('name', $permisos)
            ->pluck('name')
            ->all();
    }

    private function etiquetaPermiso(string $permiso): string
    {
        return ucfirst(str_replace(['.', '-'], [' ', ' '], $permiso));
    }
}
