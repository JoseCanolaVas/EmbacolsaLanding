<?php

namespace App\Http\Modules\Categorias\Controllers;

use App\Http\Modules\Categorias\Services\CategoriaService;
use App\Http\Modules\Categorias\Repositories\CategoriaRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class CategoriaController extends Controller
{
    public function __construct(
        protected CategoriaService $categoriaService,
        protected CategoriaRepository $categoriaRepository
    ) {}

    /**
     * Crear una nueva categoría.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     * @author jose vasquez
     */
    public function crearCategoria(Request $request): JsonResponse
    {
        try {
            $categoria = $this->categoriaService->crearCategoria($request->all());
            return response()->json($categoria, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error al crear la categoría',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Listar todas las categorías.
     * @return JsonResponse
     * @throws Exception
     * @author jose vasquez
     */
    public function listarCategorias(): JsonResponse
    {
        try {
            $categorias = $this->categoriaRepository->listarCategorias();
            return response()->json($categorias, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error al listar las categorías',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Mostrar una categoría específica.
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     * @author jose vasquez
     */
    public function actualizarCategoria(Request $request, $id): JsonResponse
    {
        try {
            $categoria = $this->categoriaService->actualizarCategoria($request->all(), $id);
            return response()->json($categoria, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error al actualizar la categoría',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
