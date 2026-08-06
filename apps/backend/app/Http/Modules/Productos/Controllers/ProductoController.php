<?php

namespace App\Http\Modules\Productos\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Modules\Productos\Services\ProductoServices;
use App\Http\Modules\Productos\Repositories\ProductoRepository;

class ProductoController extends Controller
{
    public function __construct(
        protected ProductoServices $productoService, // Servicio de Productos
        protected ProductoRepository $productoRepository // Repositorio de Productos
    ) {}

    /**
     * Listar productos
     * @param Request $request
     * @return JsonResponse
     * @author jose vasquez
     */
    public function listarProductos(Request $request): JsonResponse
    {
        try {
            $productos = $this->productoRepository->listarProductos($request->all());
            return response()->json([
                'data' => $productos
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Error al listar los productos'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
