<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Campos que nunca se guardarán en la sesión
     * cuando ocurra un error de validación.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Registrar el manejo de excepciones.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $exception) {
            //
        });

        /*
        |--------------------------------------------------------------------------
        | Errores de validación
        |--------------------------------------------------------------------------
        */
        $this->renderable(function (
            ValidationException $exception,
            $request
        ) {
            if (!$this->esPeticionApi($request)) {
                return null;
            }

            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados no son válidos.',
                'errors' => $exception->errors(),
            ], 422);
        });

        /*
        |--------------------------------------------------------------------------
        | Modelo no encontrado
        |--------------------------------------------------------------------------
        */
        $this->renderable(function (
            ModelNotFoundException $exception,
            $request
        ) {
            if (!$this->esPeticionApi($request)) {
                return null;
            }

            return response()->json([
                'success' => false,
                'message' => 'El registro solicitado no existe.',
            ], 404);
        });

        /*
        |--------------------------------------------------------------------------
        | Errores de base de datos
        |--------------------------------------------------------------------------
        */
        $this->renderable(function (
            QueryException $exception,
            $request
        ) {
            if (!$this->esPeticionApi($request)) {
                return null;
            }

            $respuesta = [
                'success' => false,
                'message' => 'Ocurrió un error al consultar la base de datos.',
            ];

            if (config('app.debug')) {
                $respuesta['error'] = $exception->getMessage();
                $respuesta['file'] = $exception->getFile();
                $respuesta['line'] = $exception->getLine();
            }

            return response()->json($respuesta, 500);
        });

        /*
        |--------------------------------------------------------------------------
        | Cualquier otra excepción
        |--------------------------------------------------------------------------
        */
        $this->renderable(function (
            Throwable $exception,
            $request
        ) {
            if (!$this->esPeticionApi($request)) {
                return null;
            }

            $statusCode = $exception instanceof HttpExceptionInterface
                ? $exception->getStatusCode()
                : 500;

            $mensajes = [
                400 => 'La solicitud no es válida.',
                403 => 'No tienes permisos para realizar esta acción.',
                404 => 'La ruta solicitada no existe.',
                405 => 'El método HTTP no está permitido.',
                419 => 'La sesión ha expirado.',
                429 => 'Se realizaron demasiadas solicitudes.',
                500 => $exception->getMessage() ?: 'Ocurrió un error en el servidor.',
                503 => 'El servicio no está disponible.',
            ];

            $respuesta = [
                'success' => false,
                'message' => $mensajes[$statusCode]
                    ?? 'Ocurrió un error procesando la solicitud.',
            ];

            if (config('app.debug')) {
                $respuesta['exception'] = get_class($exception);
                $respuesta['error'] = $exception->getMessage();
                $respuesta['file'] = $exception->getFile();
                $respuesta['line'] = $exception->getLine();
            }

            return response()->json($respuesta, $statusCode);
        });
    }

    /**
     * Respuesta cuando el usuario no está autenticado.
     */
    protected function unauthenticated(
        $request,
        AuthenticationException $exception
    ) {
        if ($this->esPeticionApi($request)) {
            return response()->json([
                'success' => false,
                'message' => 'No estás autenticado.',
            ], 401);
        }

        return parent::unauthenticated($request, $exception);
    }

    /**
     * Determina si la petición viene de la API o espera JSON.
     */
    private function esPeticionApi($request): bool
    {
        return $request->expectsJson()
            || $request->is('api/*');
    }
}