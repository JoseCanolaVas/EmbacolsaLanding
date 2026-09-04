<?php

use App\Http\Modules\Bodegas\Controllers\BodegaController;
use Illuminate\Support\Facades\Route;

Route::prefix('bodegas')->group(function () {
    Route::controller(BodegaController::class)->group(function () {
        Route::get('/listar', 'listarBodegas');
        Route::post('/crear', 'crearBodega')->middleware(['auth:api', 'can:bodegas.crear']);
        Route::put('/actualizar/{id}', 'actualizarBodega')->middleware(['auth:api', 'can:bodegas.editar']);
    });
});
