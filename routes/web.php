<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
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


});