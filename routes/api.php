<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\IzinController;
use App\Http\Controllers\Api\LemburController;
use App\Http\Controllers\Api\LogAttendanceController;
use App\Http\Controllers\Api\LogGpsController;
use App\Http\Controllers\Api\LovController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Middleware\CheckKeyApiRequest;
use Illuminate\Support\Facades\Route;



Route::prefix('auth')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/refresh', 'refresh')->withoutMiddleware(CheckKeyApiRequest::class);
        Route::post('/logout', 'logout');
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('log/gps')->group(function () {
        Route::controller(LogGpsController::class)->group(function () {
            Route::post('/store', 'store');
        });
    });

    Route::prefix('schedule')->group(function () {
        Route::controller(ScheduleController::class)->group(function () {
            Route::get('/getById', 'getById');
        });
    });

    Route::prefix('lov')->group(function () {
        Route::controller(LovController::class)->group(function () {
            Route::get('/getByKey', 'getByKey');
        });
    });

    Route::prefix('izin')->group(function () {
        Route::controller(IzinController::class)->group(function () {
            Route::post('/store', 'store');
            Route::post('/update/{id}', 'update');
            Route::post('/delete/{id}', 'delete');
        });
    });
    Route::prefix('lembur')->group(function () {
        Route::controller(LemburController::class)->group(function () {
            Route::post('/store', 'store');
            Route::post('/update/{id}', 'update');
            Route::post('/delete/{id}', 'delete');
        });
    });
});

Route::prefix('log/attendance')->group(function () {
    Route::controller(LogAttendanceController::class)->group(function () {
        Route::post('store', 'store');
        Route::get('get-lastest-time/{param}', 'getLastTimeByMachine');
    });
});
