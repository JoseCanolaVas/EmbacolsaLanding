<?php

namespace App\Http\Modules\Permisos\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Modules\Permisos\Services\PermisoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class PermisoController extends Controller
{
    public function __construct(
        protected PermisoService $permisoService
    ) {}

    public function listar(Request $request): JsonResponse
    {
        return response()->json(['permisos' => $this->permisoService->listar($request->all()),], Response::HTTP_OK);
    }

    public function crear(Request $request): JsonResponse
    {
        try {
            return response()->json($this->permisoService->crear($request->all()), Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Datos inválidos para crear el permiso',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function actualizar(Request $request, int $id): JsonResponse
    {
        try {
            return response()->json($this->permisoService->actualizar($id, $request->all()), Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Datos inválidos para actualizar el permiso',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
