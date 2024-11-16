<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelOrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');
});

Route::prefix('travel-orders')->group(function () {
    Route::post('/', [TravelOrderController::class, 'store'])->middleware('auth:api');
    Route::put('/{id}/status', [TravelOrderController::class, 'updateStatus'])->middleware('auth:api');
    Route::get('/{id}', [TravelOrderController::class, 'show']);
    Route::get('/', [TravelOrderController::class, 'index']);
    Route::post('/{id}/notify', [TravelOrderController::class, 'notify']);
});

