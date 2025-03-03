<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OptionController;
use App\Http\Controllers\Api\RegisteredUserController;
use App\Http\Controllers\Api\AuthenticatedSessionController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ViconController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Middleware\ValidateRememberToken;
use App\Http\Middleware\RoleMiddleware;

Route::middleware('api')->group(function () {
    // Public Routes
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::post('/login', [AuthenticatedSessionController::class, 'login']);

    // Protected Vicon Routes - Admin Only
    Route::prefix('vicons')->middleware([ValidateRememberToken::class, RoleMiddleware::class.':admin'])->group(function () {
        Route::get('/', [ViconController::class, 'index']);
        Route::post('/', [ViconController::class, 'store']);
        Route::get('/{vicon}', [ViconController::class, 'show']);
        Route::put('/{vicon}', [ViconController::class, 'update']);
        Route::delete('/{vicon}', [ViconController::class, 'destroy']);
        Route::post('/{id}/restore', [ViconController::class, 'restore']);
    });

    Route::prefix('gallery')->group(function () {
        // Public Routes
        Route::get('/', [GalleryController::class, 'index']);
        Route::get('/{gallery}', [GalleryController::class, 'show']);
        Route::post('/{gallery}', [GalleryController::class, 'update']);

        // Admin Routes
        Route::middleware([ValidateRememberToken::class, RoleMiddleware::class.':admin'])->group(function () {
            Route::post('/', [GalleryController::class, 'store']);
            Route::delete('/{gallery}', [GalleryController::class, 'destroy']);
        });
    });

    Route::prefix('contacts')->group(function () {
        Route::get('/', [ContactController::class, 'index']);
        Route::get('/{contact}', [ContactController::class, 'show']);
        
        // User Routes
        Route::middleware([ValidateRememberToken::class, RoleMiddleware::class.':admin'])->group(function () {
            Route::post('/', [ContactController::class, 'store']);
        });
        
        // Admin Routes
        Route::middleware([ValidateRememberToken::class, RoleMiddleware::class.':admin'])->group(function () {
            Route::put('/{id}/respond', [ContactController::class, 'updateResponse']);
            Route::post('/{id}/restore', [ContactController::class, 'restore']);
        });
        
        // Admin or User Routes
        Route::middleware([ValidateRememberToken::class])->group(function () {
            Route::put('/{contact}', [ContactController::class, 'update']);
            Route::delete('/{contact}', [ContactController::class, 'destroy']);
        });
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
    
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index']);
        Route::get('/active', [PostController::class, 'active']);
        Route::get('/{id}', [PostController::class, 'show']);
        Route::post('/{post}', [PostController::class, 'update']);
        
        // Admin Routes
        Route::middleware([ValidateRememberToken::class, RoleMiddleware::class.':admin'])->group(function () {
            Route::post('/{id}/restore', [PostController::class, 'restore']);
        });

        // Admin or User Routes
        Route::middleware([ValidateRememberToken::class])->group(callback: function () {
            Route::post('/', [PostController::class, 'store']);
            Route::delete('/{id}', [PostController::class, 'destroy']);
        });
    });
    
    // Article Routes
    Route::prefix('articles')->group(function () {
        Route::get('/', [ArticleController::class, 'index']);
        Route::get('/active', [ArticleController::class, 'active']);
        Route::get('/{id}', [ArticleController::class, 'show']);
        
        // Admin Routes
        Route::middleware([ValidateRememberToken::class])->group(callback: function () {
            Route::post('/{id}/restore', [ArticleController::class, 'restore']);
            Route::post('/', [ArticleController::class, 'store']);
            Route::put('/{id}', [ArticleController::class, 'update']);
            Route::delete('/{id}', [ArticleController::class, 'destroy']);
        });
    });
});