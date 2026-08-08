<?php

namespace App\Http\Modules\TipoImagen\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Modules\TipoImagen\Repositories\TipoImagenRepository;
use App\Http\Modules\TipoImagen\Services\TipoImagenService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TipoImagenController extends Controller
{
    public function __construct(
        protected TipoImagenService $tipoImagenService,
        protected TipoImagenRepository $tipoImagenRepository
    ) {}

    public function listarTipoImagenes()
    {
        try {
            $tipoImagenes = $this->tipoImagenRepository->listarTipoImagenes();
            return response()->json($tipoImagenes, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error al listar los tipos de imagen',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function crearTipoImagen(Request $request)
    {
        try {
            $tipoImagen = $this->tipoImagenService->crearTipoImagen($request->all());
            return response()->json($tipoImagen, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error al crear el tipo de imagen',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function actualizarTipoImagen(Request $request,int $id)
    {
        try {
            $tipoImagen = $this->tipoImagenService->actualizarTipoImagen($id, $request->all());
            return response()->json($tipoImagen, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error al actualizar el tipo de imagen',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
