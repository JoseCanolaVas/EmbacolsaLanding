<?php

namespace App\Http\Modules\Usuarios\Services;

use App\Http\Modules\Usuarios\Repositories\UsuarioRepository;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UsuarioService
{
    public function __construct(
        protected UsuarioRepository $usuarioRepository
    ) {}

    public function crearUsuario(array $data)
    {
        $validator = Validator::make($data, [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'es_super_admin' => ['nullable', 'boolean'],
            'rol' => ['nullable', 'string', 'max:80'],
            'permisos' => ['nullable', 'array'],
            'permisos.*' => ['string', 'max:120'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $datosValidados = $validator->validated();
        $datosValidados['password'] = Hash::make($datosValidados['password']);
        $datosValidados['es_super_admin'] = $datosValidados['es_super_admin'] ?? false;
        $datosValidados['rol'] = $datosValidados['rol'] ?? ($datosValidados['es_super_admin'] ? 'super_admin' : 'editor');
        $datosValidados['permisos'] = $datosValidados['es_super_admin'] ? [] : ($datosValidados['permisos'] ?? []);

        return User::create($datosValidados);
    }

    public function actualizarUsuario(int $id, array $data)
    {
        $usuario = $this->usuarioRepository->buscarUsuario($id);

        if (! $usuario) {
            throw new Exception('Usuario no encontrado');
        }

        $validator = Validator::make($data, [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'password' => ['nullable', 'string', 'min:6'],
            'es_super_admin' => ['nullable', 'boolean'],
            'rol' => ['nullable', 'string', 'max:80'],
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
        $datosValidados['rol'] = $datosValidados['rol'] ?? ($datosValidados['es_super_admin'] ? 'super_admin' : 'editor');
        $datosValidados['permisos'] = $datosValidados['es_super_admin'] ? [] : ($datosValidados['permisos'] ?? []);

        $usuario->update($datosValidados);

        return $usuario;
    }
}
