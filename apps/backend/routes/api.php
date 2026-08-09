<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

require __DIR__ . '/usuarios/usuarios.php';
require __DIR__ . '/auth/auth.php';
require __DIR__ . '/categorias/categorias.php';
require __DIR__ . '/productos/productos.php';
require __DIR__ . '/tipoImagenes/tipoImagenes.php';
require __DIR__ . '/imagenes/imagenes.php';
require __DIR__ . '/marcas/marcas.php';
