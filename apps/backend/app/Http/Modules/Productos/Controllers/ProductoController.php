<?php

namespace App\Http\Modules\Productos\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Modules\Productos\Services\ProductoService;
use App\Http\Modules\Productos\Repositories\ProductoRepository;
use App\Http\Modules\Productos\Models\Productos;
use Illuminate\Validation\ValidationException;

class ProductoController extends Controller
{
    public function __construct(
        protected ProductoService $productoService, // Servicio de Productos
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
            return response()->json($productos, Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Error al listar los productos',
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function crearProducto(Request $request): JsonResponse
    {
        try {
            $producto = $this->productoService->crearProducto($request->all());
            return response()->json($producto, Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Datos invalidos para crear el producto',
                'message' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Error al crear el producto',
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Actualizar producto
     * @param Request $request
     * @param Productos $producto // Modelo de Producto por Id ingresado
     * @return JsonResponse
     * @throws \Throwable $th
     * @author jose vasquez
     */
    public function actualizarProducto(Request $request, Productos $producto): JsonResponse
    {
        try {
            $producto = $this->productoService->actualizarProducto($request->all(), $producto);
            return response()->json($producto, Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'mensaje' => 'Ha ocurrido un error al actualizar el producto',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
