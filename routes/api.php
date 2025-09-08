<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LogAttendanceController;
use App\Http\Controllers\Api\LogGpsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('auth')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
    });
});

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post('/logout', 'logout');
        });
    });

});

Route::prefix('log/gps')->group(function () {
    Route::controller(LogGpsController::class)->group(function () {
        Route::post('/store', 'store');
    });
});


Route::prefix('log/attendance')->group(function () {
    Route::controller(LogAttendanceController::class)->group(function () {
        Route::post('store', 'store');
        Route::get('get-lastest-time/{param}', 'getLastTimeByMachine');
    });
});
