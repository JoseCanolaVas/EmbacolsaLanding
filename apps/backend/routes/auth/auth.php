<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::controller()->group(function () {
        Route::post('/login', 'login');
        Route::get('/me', 'me');
        Route::post('/logout', 'logout');
    });
});
