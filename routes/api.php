<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OptionController;
use App\Http\Controllers\Api\RegisteredUserController;
use App\Http\Controllers\Api\AuthenticatedSessionController;

Route::middleware('api')->group(function () {
    // Auth Routes
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::post('/login', [AuthenticatedSessionController::class, 'login']);
    
    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthenticatedSessionController::class, 'logout']);
        
        // Options Routes
        Route::prefix('options')->group(function () {
            Route::get('/', [OptionController::class, 'index']);
            Route::get('/active', [OptionController::class, 'active']);
            Route::post('/', [OptionController::class, 'store']);
            Route::get('/{option}', [OptionController::class, 'show']);
            Route::put('/{option}', [OptionController::class, 'update']);
            Route::delete('/{option}', [OptionController::class, 'destroy']);
            Route::post('/{id}/restore', [OptionController::class, 'restore']);
        });
    });
});