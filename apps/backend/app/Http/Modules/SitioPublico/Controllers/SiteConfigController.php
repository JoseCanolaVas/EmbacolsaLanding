<?php

namespace App\Http\Modules\SitioPublico\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Modules\SitioPublico\Services\SiteConfigService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class SiteConfigController extends Controller
{
    public function __construct(
        protected SiteConfigService $siteConfigService
    ) {}

    public function mostrar(): JsonResponse
    {
        return response()->json($this->siteConfigService->obtenerPrincipal(), Response::HTTP_OK);
    }

    public function actualizar(Request $request): JsonResponse
    {
        try {
            return response()->json(
                $this->siteConfigService->actualizarPrincipal($request->all()),
                Response::HTTP_OK
            );
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Datos inválidos para actualizar el sitio público',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
