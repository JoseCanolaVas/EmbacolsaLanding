<?php

namespace App\Http\Modules\Roles\Services;

use App\Http\Modules\Roles\Models\Rol;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RolService
{
    public const PERMISOS = [
        'panel.ver',
        'productos.ver',
        'productos.crear',
        'productos.editar',
        'categorias.ver',
        'categorias.crear',
        'categorias.editar',
        'marcas.ver',
        'marcas.crear',
        'marcas.editar',
        'imagenes.ver',
        'imagenes.crear',
        'imagenes.editar',
        'imagenes.eliminar',
        'usuarios.ver',
        'usuarios.crear',
        'usuarios.editar',
        'roles.ver',
        'roles.crear',
        'roles.editar',
        'administrar-sitio',
    ];

    public function listar()
    {
        return Rol::orderBy('nombre')->get();
    }

    public function crear(array $data): Rol
    {
        $datos = $this->validar($data);
        $datos['slug'] = $datos['slug'] ?: Str::slug($datos['nombre']);

        return Rol::create($datos);
    }

    public function actualizar(int $id, array $data): Rol
    {
        $rol = Rol::findOrFail($id);
        $datos = $this->validar($data, $id);
        $datos['slug'] = $datos['slug'] ?: Str::slug($datos['nombre']);

        $rol->update($datos);

        return $rol->fresh();
    }

    public function permisosDisponibles(): array
    {
        return collect(self::PERMISOS)
            ->map(fn ($permiso) => [
                'value' => $permiso,
                'label' => $this->etiquetaPermiso($permiso),
            ])
            ->values()
            ->all();
    }

    private function validar(array $data, ?int $id = null): array
    {
        $validator = Validator::make($data, [
            'nombre' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:120', Rule::unique('roles', 'slug')->ignore($id)],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'permisos' => ['nullable', 'array'],
            'permisos.*' => ['string', Rule::in(self::PERMISOS)],
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

    private function etiquetaPermiso(string $permiso): string
    {
        return ucfirst(str_replace(['.', '-'], [' ', ' '], $permiso));
    }
}
