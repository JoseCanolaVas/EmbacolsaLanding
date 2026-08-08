<?php

use App\Http\Modules\Productos\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

Route::prefix('productos')->group(function () {
    Route::controller(ProductoController::class)->group(function () {
        Route::post('/listar', 'listarProductos');
        Route::post('/crear', 'crearProducto');
        Route::get('/obtener/{id}', 'obtenerProducto');
        Route::put('/actualizar/{id}', 'actualizarProducto');
    });
});
