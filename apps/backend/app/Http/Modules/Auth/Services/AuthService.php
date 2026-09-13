<?php

namespace App\Http\Modules\Auth\Services;

use Exception;
use Illuminate\Support\Facades\Auth;

class AuthService
{

    public function login(array $credenciales)
    {
        /*
         * Autentica solamente durante esta petición.
         * Es más apropiado para routes/api.php porque no usa sesión.
         */
        if (!Auth::guard('web')->once($credenciales)) {
            throw new Exception('Correo o contraseña incorrectos.', 401);
        }

        $user = Auth::guard('web')->user();

        $tokenResult = $user->createToken(
            'TokenSoftNova2026'
        );

        $token = $tokenResult->token;

        $token->expires_at = now()->addDays(30);
        $token->save();

        return [
            'success' => true,
            'message' => 'Inicio de sesión exitoso.',
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->expires_at,
            'user' => [
                'id' => $user->id,
                'nombre' => $user->nombre,
                'apellido' => $user->apellido,
                'email' => $user->email,
                'telefono' => $user->telefono,
                'es_super_admin' => $user->es_super_admin,
                'rol' => $user->rol,
                'permisos' => $user->permisosDisponibles(),
            ],
        ];
    }
}
