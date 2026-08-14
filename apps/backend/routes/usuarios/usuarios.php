<?php

use Illuminate\Support\Facades\Route;
use App\Http\Modules\Usuarios\Controllers\UsuarioController;

Route::prefix('usuarios')->group(function () {
    Route::controller(UsuarioController::class)->group(function () {
        Route::get('/listar', 'listarUsuarios')->middleware(['auth:api', 'can:usuarios.ver']);
        Route::post('/crear', 'crearUsuario')->middleware(['auth:api', 'can:usuarios.crear']);
        Route::post('/actualizar/{id}', 'actualizarUsuario')->middleware(['auth:api', 'can:usuarios.editar']);
    });
});
