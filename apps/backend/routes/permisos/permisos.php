<?php

use App\Http\Modules\Permisos\Controllers\PermisoController;
use Illuminate\Support\Facades\Route;

Route::prefix('permisos')->middleware('auth:api')->group(function () {
    Route::controller(PermisoController::class)->group(function () {
        Route::post('/listar', 'listar')->middleware('can:permisos.ver');
        Route::post('/crear', 'crear')->middleware('can:permisos.crear');
        Route::put('/actualizar/{id}', 'actualizar')->middleware('can:permisos.editar');
    });
});
