<?php

namespace App\Http\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                'string',
            ],
        ]);

        /*
         * Autentica solamente durante esta petición.
         * Es más apropiado para routes/api.php porque no usa sesión.
         */
        if (!Auth::guard('web')->once($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Correo o contraseña incorrectos.',
            ], 401);
        }

        $user = Auth::guard('web')->user();

        $tokenResult = $user->createToken(
            'TokenSoftNova2026'
        );

        /*
         * Este es el modelo Laravel\Passport\Token.
         * Sirve para modificar expiración, scopes, revocación, etc.
         */
        $token = $tokenResult->token;

        $token->expires_at = now()->addDays(30);
        $token->save();

        return response()->json([
            'success' => true,
            'message' => 'Inicio de sesión exitoso.',

            // Este es el token que debe guardar Nuxt.
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
            ],
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }
}