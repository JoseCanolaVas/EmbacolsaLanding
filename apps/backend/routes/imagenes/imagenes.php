<?php

use Illuminate\Support\Facades\Route;
use App\Http\Modules\Imagenes\Controllers\ImagenController;

Route::prefix('imagenes')->group(function () {
    Route::controller(ImagenController::class)->group(function () {
        Route::get('/listar', 'listarImagenes');
        Route::post('/crear', 'crearImagen')->middleware(['auth:api', 'can:imagenes.crear']);
        Route::post('/actualizar/{imagen}', 'actualizarImagen')->middleware(['auth:api', 'can:imagenes.editar']);
        Route::delete('/eliminar/{imagen}', 'eliminarImagen')->middleware(['auth:api', 'can:imagenes.eliminar']);
    });
});
