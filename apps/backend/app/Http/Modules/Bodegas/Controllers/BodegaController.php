<?php

namespace App\Http\Modules\Bodegas\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Modules\Bodegas\Repositories\BodegaRepository;
use App\Http\Modules\Bodegas\Services\BodegaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class BodegaController extends Controller
{
    public function __construct(
        protected BodegaService $bodegaService,
        protected BodegaRepository $bodegaRepository
    ) {}

    public function listarBodegas(): JsonResponse
    {
        try {
            return response()->json($this->bodegaRepository->listarBodegas(), Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ha ocurrido un error al listar las bodegas',
                'error' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function crearBodega(Request $request): JsonResponse
    {
        try {
            return response()->json($this->bodegaService->crearBodega($request->all()), Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Datos invalidos para crear la bodega',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ha ocurrido un error al crear la bodega',
                'error' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function actualizarBodega(Request $request, int $id): JsonResponse
    {
        try {
            return response()->json($this->bodegaService->actualizarBodega($id, $request->all()), Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Datos invalidos para actualizar la bodega',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ha ocurrido un error al actualizar la bodega',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
