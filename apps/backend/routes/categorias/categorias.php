<?php

use Illuminate\Support\Facades\Route;
use App\Http\Modules\Categorias\Controllers\CategoriaController;

Route::prefix('categorias')->group(function () {
    Route::controller(CategoriaController::class)->group(function () {
        Route::get('/listar', 'listarCategorias');
        Route::post('/crear', 'crearCategoria')->middleware(['auth:api', 'can:categorias.crear']);
        Route::put('/actualizar/{id}', 'actualizarCategoria')->middleware(['auth:api', 'can:categorias.editar']);
    });
});
