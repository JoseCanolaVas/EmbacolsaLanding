<?php

namespace App\Http\Modules\Imagenes\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Modules\Imagenes\Repositories\ImagenRepository;
use App\Http\Modules\Imagenes\Services\ImagenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ImagenController extends Controller
{
    public function __construct(
        protected ImagenService $imagenService,
        protected ImagenRepository $imagenRepository
    ) {}

    public function listarImagenes(): JsonResponse
    {
        try {
            return response()->json(
                $this->imagenRepository->listarImagenes(),
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Error al listar las imagenes',
                'message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function crearImagen(Request $request): JsonResponse
    {
        try {
            $imagen = $this->imagenService->crearImagen($request->all());

            return response()->json($imagen, Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Datos invalidos para crear la imagen',
                'message' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Error al crear la imagen',
                'message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
