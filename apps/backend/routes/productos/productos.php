<?php

use App\Http\Modules\Productos\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

Route::prefix('productos')->group(function () {
    Route::controller(ProductoController::class)->group(function () {
        Route::post('/listar', 'listarProductos');
        Route::post('/crear', 'crearProducto')->middleware(['auth:api', 'can:productos.crear']);
        Route::get('/obtener/{id}', 'obtenerProducto');
        Route::post('/{producto}', 'actualizarProducto')->middleware(['auth:api', 'can:productos.editar']);
    });
});
