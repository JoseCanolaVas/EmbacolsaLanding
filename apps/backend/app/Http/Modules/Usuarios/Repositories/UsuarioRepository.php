<?php

namespace App\Http\Modules\Usuarios\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UsuarioRepository
{
    /**
     * Listar todos los usuarios.
     * @return Collection
     * @author jose vasquez
     */
    public function listarUsuarios(): Collection
    {
        return User::with(['roles.permissions', 'permissions'])
            ->orderBy('nombre')
            ->get()
            ->map(function (User $usuario) {
                $usuario->setAttribute('roles_asignados', $usuario->roles->map(fn ($rol) => [
                    'id' => $rol->id,
                    'nombre' => $rol->display_name ?: $rol->name,
                    'slug' => $rol->name,
                ])->values());
                $usuario->setAttribute('permisos', $usuario->permisosDisponibles());

                return $usuario;
            });
    }

    /**
     * Buscar un usuario por su ID.
     * @param int $userId
     * @return User|null
     * @author jose vasquez
     */
    public function buscarUsuario(int $userId): User|null
    {
        return User::with(['roles', 'permissions'])->where('id', $userId)->first();
    }

    /**
     * Buscar usuarios por coincidencias en el email.
     * @param string $email
     * @return Collection
     * @author jose vasquez
     */
    public function buscarCoincidenciasPorEmail(string $email): Collection
    {
        return User::where('email', 'like', "%$email%")->get();
    }
}
