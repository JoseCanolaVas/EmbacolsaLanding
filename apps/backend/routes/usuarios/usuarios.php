<?php

use Illuminate\Support\Facades\Route;
use App\Http\Modules\Usuarios\Controllers\UsuarioController;

Route::prefix('usuarios')->group(function () {
    Route::controller(UsuarioController::class)->group(function () {
        Route::get('/listar', 'listarUsuarios');
        Route::post('/crear', 'crearUsuario');
        Route::post('/actualizar/{id}', 'actualizarUsuario');
    });
});
