<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OptionController;
use App\Http\Controllers\Api\ViconController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\StudyController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\JobController;

Route::get('/', function () {
    return view('welcome');
});

// Public Routes
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show');

// Add this route before the existing materials routes
Route::get('/materials={content_type}', [MaterialController::class, 'index'])->name('materials.type');

Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
Route::get('/materials/{id}', [MaterialController::class, 'show'])->name('materials.show');
Route::get('/materials/{material}/download', [MaterialController::class, 'download'])->name('materials.download');
Route::get('/schools', [SchoolController::class, 'index'])->name('schools.index');
Route::get('/studies', [StudyController::class, 'index'])->name('studies.index');
Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';