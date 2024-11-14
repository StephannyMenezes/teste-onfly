<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TravelOrderController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('travel-orders')->group(function () {
    Route::post('/', [TravelOrderController::class, 'store']);
    Route::put('/{id}/status', [TravelOrderController::class, 'updateStatus']);
    Route::get('/{id}', [TravelOrderController::class, 'show']);
    Route::get('/', [TravelOrderController::class, 'index']);
    Route::post('/{id}/notify', [TravelOrderController::class, 'notify']);
});

