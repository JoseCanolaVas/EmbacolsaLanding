<?php

namespace App\Http\Modules\Usuarios\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Modules\Usuarios\Services\UsuarioService;
use App\Http\Modules\Usuarios\Repositories\UsuarioRepository;
use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Controlador para manejar las operaciones relacionadas con los usuarios.
 * @package App\Http\Modules\Usuarios\Controllers
 * @author jose vasquez
 */
class UsuarioController extends Controller
{

    /**
     * Constructor del controlador.
     * @param UsuarioService $usuarioService
     * @param UsuarioRepository $usuarioRepository
     */
    public function __construct(
        protected UsuarioService $usuarioService,
        protected UsuarioRepository $usuarioRepository
    ) {}

    /**
     * Listar todos los usuarios.
     * @param Request $request
     * @return JsonResponse
     * @author jose vasquez
     */
    public function listarUsuarios(Request $request): JsonResponse
    {
        try {
            $usuarios = $this->usuarioRepository->listarUsuarios();
            return response()->json($usuarios, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un Error al listar los usuarios',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Crear un nuevo usuario.
     * @param Request $request
     * @return JsonResponse
     * @author jose vasquez
     */
    public function crearUsuario(Request $request): JsonResponse
    {
        try {
            $usuario = $this->usuarioService->crearUsuario($request->all());
            return response()->json($usuario, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un Error al crear el usuario',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Actualizar un usuario existente.
     * @param Request $request  
     * @param int $id
     * @return JsonResponse
     * @author jose vasquez
     */
    public function actualizarUsuario(Request $request, int $id): JsonResponse
    {
        try {
            $usuario = $this->usuarioService->actualizarUsuario($id, $request->all());
            return response()->json($usuario, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un Error al actualizar el usuario',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
