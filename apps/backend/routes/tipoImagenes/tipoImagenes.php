<?php

use Illuminate\Support\Facades\Route;
use App\Http\Modules\TipoImagen\Controllers\TipoImagenController;

Route::prefix('tipo-imagenes')->group(function () {
    Route::controller(TipoImagenController::class)->group(function () {
        Route::get('/listar', 'listarTipoImagenes');
        Route::post('/crear', 'crearTipoImagen');
        Route::put('/actualizar/{id}', 'actualizarTipoImagen');
    });
});
