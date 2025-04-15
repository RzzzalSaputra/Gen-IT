<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PreviewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeacherController;
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
use App\Http\Controllers\Api\ClassroomController;
use App\Http\Controllers\Api\ClassroomMaterialController;
use App\Http\Controllers\Api\ClassroomAssignmentController;
use App\Http\Controllers\Api\ClassroomSubmissionController;
use Illuminate\Support\Facades\Route;

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
Route::get('/convert-docx/{id}', [PreviewController::class, 'convertDocxToPdf'])->name('convert.docx.pdf');
Route::get('/serve-pdf/{id}', [PreviewController::class, 'servePdf'])->name('serve.pdf');

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
    
    // Student Classroom Routes - add to web.php
    Route::prefix('student/classrooms')->middleware(['auth'])->name('student.classrooms.')->group(function () {
        Route::get('/', [ClassroomController::class, 'studentDashboard'])->name('index');
        Route::get('/join', [ClassroomController::class, 'joinForm'])->name('join');
        Route::post('/join', [ClassroomController::class, 'processJoin'])->name('process-join');
        Route::get('/{id}', [ClassroomController::class, 'showForStudent'])->name('show');
        Route::get('/{classroom_id}/materials/{id}', [ClassroomMaterialController::class, 'showForStudent'])->name('materials.show');
        
        // Assignment routes
        Route::get('/{classroom_id}/assignments', [ClassroomAssignmentController::class, 'indexForStudent'])->name('assignments.index');
        Route::get('/{classroom_id}/assignments/{id}', [ClassroomAssignmentController::class, 'showForStudent'])->name('assignments.show');
        Route::get('/{classroom_id}/assignments/{id}/download', [ClassroomAssignmentController::class, 'downloadForStudent'])->name('assignments.download');
        
        // Submission routes
        Route::post('/{classroom_id}/assignments/{assignment_id}/submissions', [ClassroomSubmissionController::class, 'storeForStudent'])->name('submissions.store');
        Route::get('/{classroom_id}/assignments/{assignment_id}/submissions/{id}', [ClassroomSubmissionController::class, 'showForStudent'])->name('submissions.show');
        Route::get('/{classroom_id}/assignments/{assignment_id}/submissions/{id}/download', [ClassroomSubmissionController::class, 'downloadForStudent'])->name('assignments.submissions.download');
        
        // Leave classroom route
        Route::get('/{id}/leave', [ClassroomController::class, 'leaveClassroom'])->name('leave');
    });
});

// Teacher Dashboard Routes
Route::prefix('teacher')->middleware(['auth', 'App\Http\Middleware\RoleMiddleware:teacher'])->name('teacher.')->group(function () {
    // Dashboard - the main teacher page
    Route::get('/', [TeacherController::class, 'dashboard'])->name('dashboard');
    
    // Classrooms management
    Route::get('/classrooms', [TeacherController::class, 'classrooms'])->name('classrooms.index');
    Route::get('/classrooms/create', [TeacherController::class, 'createClassroom'])->name('classrooms.create');
    Route::post('/classrooms', [TeacherController::class, 'storeClassroom'])->name('classrooms.store');
    Route::get('/classrooms/{id}', [TeacherController::class, 'showClassroom'])->name('classrooms.show');
    Route::get('/classrooms/{id}/edit', [TeacherController::class, 'editClassroom'])->name('classrooms.edit');
    Route::put('/classrooms/{id}', [TeacherController::class, 'updateClassroom'])->name('classrooms.update');
    Route::delete('/classrooms/{id}', [TeacherController::class, 'destroyClassroom'])->name('teacher.classrooms.destroy');
    Route::delete('/classrooms/{id}', [TeacherController::class, 'destroyClassroom'])->name('classrooms.destroy');
    
    // Materials management
    Route::get('/classrooms/{classroom_id}/materials', [TeacherController::class, 'materials'])->name('materials.index');
    Route::get('/classrooms/{classroom_id}/materials/create', [TeacherController::class, 'createMaterial'])->name('materials.create');
    Route::post('/classrooms/{classroom_id}/materials', [TeacherController::class, 'storeMaterial'])->name('materials.store');
    Route::get('/classrooms/{classroom_id}/materials/{id}/edit', [TeacherController::class, 'editMaterial'])->name('materials.edit');
    Route::put('/classrooms/{classroom_id}/materials/{id}', [TeacherController::class, 'updateMaterial'])->name('materials.update');
    Route::get('/classrooms/{classroom_id}/materials/{id}', [TeacherController::class, 'showMaterial'])->name('materials.show');
    Route::delete('/classrooms/{classroom_id}/materials/{id}', [TeacherController::class, 'destroyMaterial'])->name('materials.destroy');
    
    // Assignments management
    Route::get('/classrooms/{classroom_id}/assignments', [TeacherController::class, 'assignments'])->name('assignments.index');
    Route::get('/classrooms/{classroom_id}/assignments/create', [TeacherController::class, 'createAssignment'])->name('assignments.create');
    Route::post('/classrooms/{classroom_id}/assignments', [TeacherController::class, 'storeAssignment'])->name('assignments.store');
    Route::get('/classrooms/{classroom_id}/assignments/{id}/edit', [TeacherController::class, 'editAssignment'])->name('assignments.edit');
    Route::put('/classrooms/{classroom_id}/assignments/{id}', [TeacherController::class, 'updateAssignment'])->name('assignments.update');
    Route::get('/classrooms/{classroom_id}/assignments/{id}', [TeacherController::class, 'showAssignment'])->name('assignments.show');
    Route::delete('/classrooms/{classroom_id}/assignments/{id}', [TeacherController::class, 'destroyAssignment'])->name('assignments.destroy');
    
    // Add this new route for downloading assignments
    Route::get('/classrooms/{classroom_id}/assignments/{id}/download', [TeacherController::class, 'downloadAssignment'])
        ->name('assignments.download');
    
    // Submissions/grading
    Route::get('/classrooms/{classroom_id}/assignments/{assignment_id}/submissions', [TeacherController::class, 'submissions'])->name('submissions.index');
    Route::get('/classrooms/{classroom_id}/assignments/{assignment_id}/submissions/{id}', [TeacherController::class, 'showSubmission'])->name('submissions.show');
    Route::post('/classrooms/{classroom_id}/assignments/{assignment_id}/submissions/{id}/grade', [TeacherController::class, 'gradeSubmission'])->name('submissions.grade');
    
    // Students management
    Route::get('/classrooms/{classroom_id}/students', [TeacherController::class, 'students'])->name('students.index');
    
    // Members/Students management
    Route::post('/classrooms/{classroom_id}/members', [TeacherController::class, 'storeMember'])->name('members.store');
    Route::put('/classrooms/{classroom_id}/members/{id}/role', [TeacherController::class, 'updateMemberRole'])->name('members.update.role');
    Route::delete('/classrooms/{classroom_id}/members/{id}', [TeacherController::class, 'destroyMember'])->name('members.remove');
});


require __DIR__.'/auth.php';