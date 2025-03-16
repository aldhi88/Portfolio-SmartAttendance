<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserRoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/user-roles', [UserRoleController::class, 'index']);
});

// Route::get('/user-roles', [UserRoleController::class, 'index']);
Route::post('/auth/login', [AuthController::class, 'login']);

