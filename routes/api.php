<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OptionController;
use App\Http\Controllers\Api\RegisteredUserController;
use App\Http\Controllers\Api\AuthenticatedSessionController;
use App\Http\Controllers\Api\ViconController;
use App\Http\Middleware\ValidateRememberToken;
use App\Http\Middleware\AdminMiddleware;

Route::middleware('api')->group(function () {
    // Public Routes
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::post('/login', [AuthenticatedSessionController::class, 'login']);

    // Protected Vicon Routes
    Route::prefix('vicons')->middleware([ValidateRememberToken::class, AdminMiddleware::class])->group(function () {
        Route::get('/', [ViconController::class, 'index']);
        Route::post('/', [ViconController::class, 'store']);
        Route::get('/{vicon}', [ViconController::class, 'show']);
        Route::put('/{vicon}', [ViconController::class, 'update']);
        Route::delete('/{vicon}', [ViconController::class, 'destroy']);
        Route::post('/{id}/restore', [ViconController::class, 'restore']);
    });
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