<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('register', [\App\User\Controllers\AuthController::class, 'register']);
    Route::post('login', [\App\User\Controllers\AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('self', [\App\User\Controllers\UserController::class, 'self']);
    });

    Route::prefix('cars')->group(function () {
        Route::get('list', [\App\Car\Controllers\CarController::class, 'list']);
    });
});

