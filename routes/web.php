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
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\SubmissionController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\PreviewController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

// Gallery Public Routes
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show');

// Vicon Public Routes
Route::get('/vicons', [ViconController::class, 'index'])->name('vicons.index');
Route::get('/vicons/{id}', [ViconController::class, 'show'])->name('vicons.show');


// Post Public Routes
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');

// Material Public Routes
Route::get('/materials={content_type}', [MaterialController::class, 'index'])->name('materials.type');
Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
Route::get('/materials/{id}', [MaterialController::class, 'show'])->name('materials.show');
Route::get('/materials/{material}/download', [MaterialController::class, 'download'])->name('materials.download');

// School Public Routes
Route::get('/schools', [SchoolController::class, 'index'])->name('schools.index');
Route::get('/schools/{id}', [SchoolController::class, 'show'])->name('schools.show');

// Studies Public Routes
Route::get('/studies', [StudyController::class, 'index'])->name('studies.index');
Route::get('/studies/{id}', [StudyController::class, 'show'])->name('studies.show');

// Company Public Routes
Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/{id}', [CompanyController::class, 'show'])->name('companies.show');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.show');

// Added job by company route for web
Route::get('/companies/{companyId}/jobs', [JobController::class, 'getJobsByCompany'])->name('companies.jobs');

Route::get('/preview/{id}', [PreviewController::class, 'preview'])->name('preview.show');
Route::get('/preview/view/{id}', [PreviewController::class, 'viewPreview'])->name('preview.view');

// Add this to your routes/web.php file
Route::get('/convert-docx/{id}', [App\Http\Controllers\PreviewController::class, 'convertDocxToPdf'])->name('convert.docx.pdf');
Route::get('/serve-pdf/{id}', [App\Http\Controllers\PreviewController::class, 'servePdf'])->name('serve.pdf');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Contact routes
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contact/create', [ContactController::class, 'create'])->name('contacts.create');
    Route::post('/contact', [ContactController::class, 'store'])->name('contacts.store');
    
    // Submission routes
    Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/create', [SubmissionController::class, 'create'])->name('submissions.create');
    Route::post('/submissions', [SubmissionController::class, 'store'])->name('submissions.store');
    
    // Move this route before the {id} route to avoid conflicts
    Route::get('/submissions/public', [SubmissionController::class, 'publicIndex'])->name('submissions.public');
    
    // This should come after the /public route
    Route::get('/submissions/{id}', [SubmissionController::class, 'show'])->name('submissions.show');
});

require __DIR__.'/auth.php';