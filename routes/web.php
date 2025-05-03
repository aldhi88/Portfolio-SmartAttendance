<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MasterFunctionController;
use App\Http\Controllers\MasterLocationController;
use App\Http\Controllers\MasterOrganizationController;
use App\Http\Controllers\MasterPositionController;
use App\Http\Controllers\MasterScheduleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    if(Auth::check()){
        return redirect()->route('dashboard.index');
    }
    return redirect()->route('auth.formLogin');
})->name('anchor');


Route::middleware('guest')->group(function(){

    Route::prefix('auth')->group(function () {
        Route::name('auth.')->group(function () {
            Route::controller(AuthController::class)->group(function () {
                Route::get('login', 'formLogin')->name('formLogin');
            });
        });
    });

});

Route::middleware('auth:web')->group(function(){

    Route::prefix('dashboard')->group(function () {
        Route::name('dashboard.')->group(function () {
            Route::controller(DashboardController::class)->group(function () {
                Route::get('index', 'index')->name('index');
            });
        });
    });

    Route::prefix('laporan')->group(function () {
        Route::name('laporan.')->group(function () {
            Route::controller(LaporanController::class)->group(function () {
                Route::get('log-absen', 'indexLogAbsen')->name('indexLogAbsen');
                Route::get('log-absen/dt', 'indexLogAbsenDt')->name('indexLogAbsenDt');
            });
        });
    });

    Route::prefix('auth')->group(function () {
        Route::name('auth.')->group(function () {
            Route::controller(AuthController::class)->group(function () {
                Route::get('logout', 'logout')->name('logout');
            });
        });
    });

    Route::prefix('user')->group(function () {
        Route::name('user.')->group(function () {
            Route::controller(UserController::class)->group(function () {
                Route::get('index', 'index')->name('index');
            });
        });
    });

    Route::prefix('perusahaan')->group(function () {
        Route::name('perusahaan.')->group(function () {
            Route::controller(MasterOrganizationController::class)->group(function () {
                Route::get('index', 'index')->name('index');
                Route::get('indexDT', 'indexDT')->name('indexDT');
            });
        });
    });

    Route::prefix('jabatan')->group(function () {
        Route::name('jabatan.')->group(function () {
            Route::controller(MasterPositionController::class)->group(function () {
                Route::get('index', 'index')->name('index');
                Route::get('indexDT', 'indexDT')->name('indexDT');
            });
        });
    });

    Route::prefix('lokasi')->group(function () {
        Route::name('lokasi.')->group(function () {
            Route::controller(MasterLocationController::class)->group(function () {
                Route::get('index', 'index')->name('index');
                Route::get('indexDT', 'indexDT')->name('indexDT');
            });
        });
    });
    Route::prefix('fungsi')->group(function () {
        Route::name('fungsi.')->group(function () {
            Route::controller(MasterFunctionController::class)->group(function () {
                Route::get('index', 'index')->name('index');
                Route::get('indexDT', 'indexDT')->name('indexDT');
            });
        });
    });
    Route::prefix('jadwal-kerja')->group(function () {
        Route::name('jadwal-kerja.')->group(function () {
            Route::controller(MasterScheduleController::class)->group(function () {
                Route::get('index', 'index')->name('index');
                Route::get('create/{type}', 'create')->name('create');
                Route::get('indexDT', 'indexDT')->name('indexDT');
            });
        });
    });




});
