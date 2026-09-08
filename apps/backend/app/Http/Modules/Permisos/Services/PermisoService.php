<?php

namespace App\Http\Modules\Permisos\Services;

use App\Support\PermissionCatalog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermisoService
{
    public function listar(array $filtros = []): array
    {
        $query = Permission::query()
            ->select(['id', 'name', 'guard_name', 'label', 'grupo', 'descripcion', 'estado'])
            ->orderBy('grupo')
            ->orderBy('label')
            ->orderBy('name');

        if (! empty($filtros['buscar'])) {
            $buscar = $filtros['buscar'];
            $query->where(function ($q) use ($buscar) {
                $q->where('name', 'ILIKE', "%{$buscar}%")
                    ->orWhere('label', 'ILIKE', "%{$buscar}%")
                    ->orWhere('grupo', 'ILIKE', "%{$buscar}%");
            });
        }

        return $query->get()->map(fn (Permission $permiso) => $this->mapear($permiso))->values()->all();
    }

    public function crear(array $data): array
    {
        $datos = $this->validar($data);

        $permiso = Permission::create([
            'name' => $datos['name'],
            'guard_name' => PermissionCatalog::GUARD,
        ]);

        $permiso->forceFill([
            'label' => $datos['label'] ?: $this->etiqueta($datos['name']),
            'grupo' => $datos['grupo'] ?: 'Otros',
            'descripcion' => $datos['descripcion'] ?? null,
            'estado' => $datos['estado'] ?? true,
        ])->save();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $this->mapear($permiso->fresh());
    }

    public function actualizar(int $id, array $data): array
    {
        $permiso = Permission::findOrFail($id);
        $datos = $this->validar($data, $id);

        $permiso->forceFill([
            'name' => $datos['name'],
            'guard_name' => PermissionCatalog::GUARD,
            'label' => $datos['label'] ?: $this->etiqueta($datos['name']),
            'grupo' => $datos['grupo'] ?: 'Otros',
            'descripcion' => $datos['descripcion'] ?? null,
            'estado' => $datos['estado'] ?? true,
        ])->save();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $this->mapear($permiso->fresh());
    }

    private function validar(array $data, ?int $id = null): array
    {
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:160', Rule::unique('permissions', 'name')->where('guard_name', PermissionCatalog::GUARD)->ignore($id)],
            'label' => ['nullable', 'string', 'max:160'],
            'grupo' => ['nullable', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'estado' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    private function mapear(Permission $permiso): array
    {
        return [
            'id' => $permiso->id,
            'name' => $permiso->name,
            'value' => $permiso->name,
            'label' => $permiso->label ?: $this->etiqueta($permiso->name),
            'grupo' => $permiso->grupo ?: 'Otros',
            'descripcion' => $permiso->descripcion,
            'estado' => (bool) $permiso->estado,
            'guard_name' => $permiso->guard_name,
        ];
    }

    private function etiqueta(string $name): string
    {
        return ucfirst(str_replace(['.', '-'], ' ', $name));
    }
}
