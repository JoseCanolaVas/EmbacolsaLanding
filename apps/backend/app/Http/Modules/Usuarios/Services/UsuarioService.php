<?php

namespace App\Http\Modules\Usuarios\Services;

use App\Http\Modules\Usuarios\Repositories\UsuarioRepository;
use App\Models\User;
use App\Support\PermissionCatalog;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsuarioService
{
    public function __construct(
        protected UsuarioRepository $usuarioRepository
    ) {}

    /**
     * Crear un nuevo usuario
     * @param array $data
     * @return User
     * @author jose vasquez
     */
    public function crearUsuario(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $data['es_super_admin'] = $data['es_super_admin'] ?? false;
        $roles = $this->resolverRoles($data);
        $permisos = $this->resolverPermisos($data['permisos'] ?? []);
        $data['rol'] = $roles[0] ?? ($data['es_super_admin'] ? 'super_admin' : 'editor_catalogo');
        $data['permisos'] = $data['es_super_admin'] ? [] : $permisos;
        unset($data['roles']);

        $usuario = User::create($data);
        $usuario->syncRoles($roles);
        $usuario->syncPermissions($data['es_super_admin'] ? [] : $permisos);

        return $usuario->fresh(['roles', 'permissions']);
    }

    public function actualizarUsuario(int $id, array $data)
    {
        $usuario = $this->usuarioRepository->buscarUsuario($id);

        if (! $usuario) {
            throw new Exception('Usuario no encontrado', 422);
        }


        $validator = Validator::make($data, [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'password' => ['nullable', 'string', 'min:6'],
            'es_super_admin' => ['nullable', 'boolean'],
            'rol' => ['nullable', 'string', 'max:80'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'max:120'],
            'permisos' => ['nullable', 'array'],
            'permisos.*' => ['string', 'max:120'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $datosValidados = $validator->validated();

        if (! empty($datosValidados['password'])) {
            $datosValidados['password'] = Hash::make($datosValidados['password']);
        } else {
            unset($datosValidados['password']);
        }

        $datosValidados['es_super_admin'] = $datosValidados['es_super_admin'] ?? false;
        $roles = $this->resolverRoles($datosValidados);
        $permisos = $this->resolverPermisos($datosValidados['permisos'] ?? []);
        $datosValidados['rol'] = $roles[0] ?? ($datosValidados['es_super_admin'] ? 'super_admin' : 'editor_catalogo');
        $datosValidados['permisos'] = $datosValidados['es_super_admin'] ? [] : $permisos;
        unset($datosValidados['roles']);

        $usuario->update($datosValidados);
        $usuario->syncRoles($roles);
        $usuario->syncPermissions($datosValidados['es_super_admin'] ? [] : $permisos);

        return $usuario->fresh(['roles', 'permissions']);
    }

    private function resolverRoles(array $datos): array
    {
        $roles = $datos['roles'] ?? [];

        if (! empty($datos['rol'])) {
            $roles[] = $datos['rol'];
        }

        if (! empty($datos['es_super_admin'])) {
            $roles[] = 'super_admin';
        }

        $roles = array_values(array_unique(array_filter($roles)));

        return Role::where('guard_name', PermissionCatalog::GUARD)
            ->whereIn('name', $roles)
            ->pluck('name')
            ->all();
    }

    private function resolverPermisos(array $permisos): array
    {
        return Permission::where('guard_name', PermissionCatalog::GUARD)
            ->whereIn('name', array_values(array_unique($permisos)))
            ->pluck('name')
            ->all();
    }
}
