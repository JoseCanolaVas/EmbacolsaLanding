<?php

use App\Http\Modules\SitioPublico\Controllers\SiteConfigController;
use Illuminate\Support\Facades\Route;

Route::prefix('sitio-publico')->group(function () {
    Route::controller(SiteConfigController::class)->group(function () {
        Route::get('/configuracion', 'mostrar');
        Route::post('/configuracion', 'actualizar')->middleware(['auth:api', 'can:administrar-sitio']);
    });
});
