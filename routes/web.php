<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\UserController;
use App\Livewire\Jabatan\JabatanDT;
use App\Livewire\Location\LocationDT;
use App\Livewire\Organization\OrganizationDT;
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
            Route::controller(OrganizationController::class)->group(function () {
                Route::get('index', 'index')->name('index');
                Route::get('index/dt', OrganizationDT::class)->name('indexDT');
                Route::get('create', 'create')->name('create');
                Route::get('edit', 'edit')->name('edit');
            });
        });
    });

    Route::prefix('jabatan')->group(function () {
        Route::name('jabatan.')->group(function () {
            Route::controller(JabatanController::class)->group(function () {
                Route::get('index', 'index')->name('index');
                Route::get('index/dt', JabatanDT::class)->name('indexDT');
            });
        });
    });

    Route::prefix('location')->group(function () {
        Route::name('location.')->group(function () {
            Route::controller(LocationController::class)->group(function () {
                Route::get('index', 'index')->name('index');
                Route::get('index/dt', LocationDT::class)->name('indexDT');
            });
        });
    });




});
