<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OptionController;  // Perhatikan namespace yang benar

Route::middleware('api')->group(function () {
    // Options Routes
    Route::prefix('options')->group(function () {
        Route::get('/', [OptionController::class, 'index']);
        Route::get('/active', [OptionController::class, 'active']);
        Route::post('/', [OptionController::class, 'store']);
        Route::get('/{option}', [OptionController::class, 'show']);
        Route::put('/{option}', [OptionController::class, 'update']);
        Route::delete('/{option}', [OptionController::class, 'destroy']);
        Route::post('/restore/{id}', [OptionController::class, 'restore']);
    });
});