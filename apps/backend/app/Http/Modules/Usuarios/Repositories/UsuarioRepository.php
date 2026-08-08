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
        return User::get();
    }

    /**
     * Buscar un usuario por su ID.
     * @param int $userId
     * @return User|null
     * @author jose vasquez
     */
    public function buscarUsuario(int $userId): User|null
    {
        return User::where('id', $userId)->first();
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
