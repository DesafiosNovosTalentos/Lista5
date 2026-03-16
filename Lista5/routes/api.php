<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationLogController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('/orders')->group(function () {
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{id}', [OrderController::class, 'show']);
        Route::put('/{id}', [OrderController::class, 'update']);
        Route::delete('/{id}', [OrderController::class, 'delete']);
    });

    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::get('/users/{userId}/notifications', [NotificationLogController::class, 'index']);
});
