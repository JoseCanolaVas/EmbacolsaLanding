<?php

use Illuminate\Support\Facades\Route;
use App\Http\Modules\Imagenes\Controllers\ImagenController;

Route::prefix('imagenes')->group(function () {
    Route::controller(ImagenController::class)->group(function () {
        Route::get('/listar', 'listarImagenes');
        Route::post('/crear', 'crearImagen');
        Route::post('/actualizar/{imagen}', 'actualizarImagen');
        Route::delete('/eliminar/{imagen}', 'eliminarImagen');
    });
});
