<?php

namespace App\Http\Modules\Roles\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Modules\Roles\Services\RolService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class RolController extends Controller
{
    public function __construct(
        protected RolService $rolService
    ) {}

    public function listar(): JsonResponse
    {
        return response()->json([
            'roles' => $this->rolService->listar(),
            'permisos' => $this->rolService->permisosDisponibles(),
        ], Response::HTTP_OK);
    }

    public function crear(Request $request): JsonResponse
    {
        try {
            return response()->json($this->rolService->crear($request->all()), Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Datos inválidos para crear el rol',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function actualizar(Request $request, int $id): JsonResponse
    {
        try {
            return response()->json($this->rolService->actualizar($id, $request->all()), Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Datos inválidos para actualizar el rol',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
