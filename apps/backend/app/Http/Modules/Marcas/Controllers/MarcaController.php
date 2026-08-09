<?php

namespace App\Http\Modules\Marcas\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Modules\Marcas\Repositories\MarcaRepository;
use App\Http\Modules\Marcas\Services\MarcaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class MarcaController extends Controller
{
    public function __construct(
        protected MarcaService $marcaService,
        protected MarcaRepository $marcaRepository
    ) {}

    public function listarMarcas(): JsonResponse
    {
        try {
            return response()->json($this->marcaRepository->listarMarcas(), Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ha ocurrido un error al listar las marcas',
                'error' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function crearMarca(Request $request): JsonResponse
    {
        try {
            return response()->json($this->marcaService->crearMarca($request->all()), Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Datos invalidos para crear la marca',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ha ocurrido un error al crear la marca',
                'error' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function actualizarMarca(Request $request, int $id): JsonResponse
    {
        try {
            return response()->json($this->marcaService->actualizarMarca($id, $request->all()), Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Datos invalidos para actualizar la marca',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ha ocurrido un error al actualizar la marca',
                'error' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
