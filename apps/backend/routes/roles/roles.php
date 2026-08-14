<?php

use App\Http\Modules\Roles\Controllers\RolController;
use Illuminate\Support\Facades\Route;

Route::prefix('roles')->middleware('auth:api')->group(function () {
    Route::controller(RolController::class)->group(function () {
        Route::get('/listar', 'listar')->middleware('can:roles.ver');
        Route::post('/crear', 'crear')->middleware('can:roles.crear');
        Route::put('/actualizar/{id}', 'actualizar')->middleware('can:roles.editar');
    });
});
