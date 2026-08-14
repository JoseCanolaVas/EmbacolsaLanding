<?php

use App\Http\Modules\Auth\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::prefix('auth')->group(function () {
    Route::controller(AuthController::class)->group(function () {
  

        Route::post('/login', 'login');


        Route::get('/me', 'me')->middleware('auth:api');
        Route::post('/logout', 'logout')->middleware('auth:api');
    });
});
