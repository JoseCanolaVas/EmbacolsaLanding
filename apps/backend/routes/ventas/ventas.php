<?php

use App\Http\Modules\Ventas\Controllers\VentaController;
use Illuminate\Support\Facades\Route;

Route::prefix('ventas')->middleware('auth:api')->group(function () {
    Route::controller(VentaController::class)->group(function () {
        Route::post('/listar', 'listarVentas')->middleware('can:ventas.ver');
        Route::post('/resumen', 'resumenVentas')->middleware('can:ventas.ver');
        Route::get('/obtener/{id}', 'obtenerVenta')->middleware('can:ventas.ver');
        Route::post('/registrar', 'registrarVenta')->middleware('can:ventas.crear');
    });
});
