<?php

namespace App\Http\Modules\Ventas\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Modules\Ventas\Repositories\VentaRepository;
use App\Http\Modules\Ventas\Services\VentaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class VentaController extends Controller
{
    public function __construct(
        protected VentaService $ventaService,
        protected VentaRepository $ventaRepository
    ) {}

    public function listarVentas(Request $request): JsonResponse
    {
        try {
            return response()->json($this->ventaRepository->listarVentas($request->all()), Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ha ocurrido un error al listar las ventas',
                'error' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function obtenerVenta(int $id): JsonResponse
    {
        try {
            $venta = $this->ventaRepository->obtenerVentaPorId($id);

            if (! $venta) {
                return response()->json([
                    'message' => 'Venta no encontrada',
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json($venta, Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ha ocurrido un error al obtener la venta',
                'error' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function resumenVentas(Request $request): JsonResponse
    {
        try {
            return response()->json($this->ventaRepository->resumenVentas($request->all()), Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ha ocurrido un error al consultar el resumen de ventas',
                'error' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function registrarVenta(Request $request): JsonResponse
    {
        try {
            $venta = $this->ventaService->registrarVenta($request->all(), optional($request->user())->id);

            return response()->json($venta, Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Datos invalidos para registrar la venta',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ha ocurrido un error al registrar la venta',
                'error' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
