<?php

namespace App\Http\Modules\Usuarios\Services;

use App\Http\Modules\Usuarios\Repositories\UsuarioRepository;
use App\Models\User;
use Exception;

class UsuarioService
{
    public function __construct(
        protected UsuarioRepository $usuarioRepository
    ) {}

    public function crearUsuario(array $data)
    {
        // if(){
        //     validar usuario
        // }

        return User::create($data);
    }

    public function actualizarUsuario(int $id, array $data)
    {
        $usuario = $this->usuarioRepository->buscarUsuario($id);

        if (!$usuario) {
            throw new Exception('Usuario no encontrado');
        }

        $usuario->update($data);

        return $usuario;
    }
}
