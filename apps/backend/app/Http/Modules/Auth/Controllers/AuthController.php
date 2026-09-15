<?php

namespace App\Http\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Modules\Auth\Services\AuthService;
use Illuminate\Http\Request;
use App\Http\Modules\Auth\Requests\LoginRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    /**
     * 
     */
    public function login(LoginRequest $request)
    {
        try {
            $login = $this->authService->login($request->validated());
            return response()->json($login, Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ha ocurrido un error al iniciar sesión. ' . $th->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => array_merge(
                $request->user()->toArray(),
                ['permisos' => $request->user()->permisosDisponibles()]
            ),
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
