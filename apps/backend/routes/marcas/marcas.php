<?php

use App\Http\Modules\Marcas\Controllers\MarcaController;
use Illuminate\Support\Facades\Route;

Route::prefix('marcas')->group(function () {
    Route::controller(MarcaController::class)->group(function () {
        Route::get('/listar', 'listarMarcas');
        Route::post('/crear', 'crearMarca');
        Route::put('/actualizar/{id}', 'actualizarMarca');
    });
});
