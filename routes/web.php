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
    
    // Student Classroom Routes - add to web.php
    Route::prefix('student/classrooms')->middleware(['auth'])->name('student.classrooms.')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\ClassroomController::class, 'studentDashboard'])->name('index');
        Route::get('/join', [App\Http\Controllers\Api\ClassroomController::class, 'joinForm'])->name('join');
        Route::post('/join', [App\Http\Controllers\Api\ClassroomController::class, 'processJoin'])->name('process-join');
        Route::get('/{id}', [App\Http\Controllers\Api\ClassroomController::class, 'showForStudent'])->name('show');
        Route::get('/{classroom_id}/materials/{id}', [App\Http\Controllers\Api\ClassroomMaterialController::class, 'showForStudent'])->name('materials.show');
        
        // Assignment routes
        Route::get('/{classroom_id}/assignments', [App\Http\Controllers\Api\ClassroomAssignmentController::class, 'indexForStudent'])->name('assignments.index');
        Route::get('/{classroom_id}/assignments/{id}', [App\Http\Controllers\Api\ClassroomAssignmentController::class, 'showForStudent'])->name('assignments.show');
        Route::get('/{classroom_id}/assignments/{id}/download', [App\Http\Controllers\Api\ClassroomAssignmentController::class, 'downloadForStudent'])->name('assignments.download');
        
        // Submission routes
        Route::post('/{classroom_id}/assignments/{assignment_id}/submissions', [App\Http\Controllers\Api\ClassroomSubmissionController::class, 'storeForStudent'])->name('submissions.store');
        Route::get('/{classroom_id}/assignments/{assignment_id}/submissions/{id}', [App\Http\Controllers\Api\ClassroomSubmissionController::class, 'showForStudent'])->name('submissions.show');
        Route::get('/{classroom_id}/assignments/{assignment_id}/submissions/{id}/download', [App\Http\Controllers\Api\ClassroomSubmissionController::class, 'downloadForStudent'])->name('assignments.submissions.download');
    });
    Route::post('/{classroom_id}/assignments/{assignment_id}/submissions', 
        [App\Http\Controllers\Api\ClassroomSubmissionController::class, 'storeForStudent'])
        ->name('student.classrooms.assignments.submissions.store');
});

// Teacher Dashboard Routes
Route::prefix('teacher')->middleware(['auth', 'App\Http\Middleware\RoleMiddleware:teacher'])->name('teacher.')->group(function () {
    // Dashboard - the main teacher page
    Route::get('/', [App\Http\Controllers\TeacherController::class, 'dashboard'])->name('dashboard');
    
    // Classrooms management
    Route::get('/classrooms', [App\Http\Controllers\TeacherController::class, 'classrooms'])->name('classrooms.index');
    Route::get('/classrooms/create', [App\Http\Controllers\TeacherController::class, 'createClassroom'])->name('classrooms.create');
    Route::post('/classrooms', [App\Http\Controllers\TeacherController::class, 'storeClassroom'])->name('classrooms.store');
    Route::get('/classrooms/{id}', [App\Http\Controllers\TeacherController::class, 'showClassroom'])->name('classrooms.show');
    Route::get('/classrooms/{id}/edit', [App\Http\Controllers\TeacherController::class, 'editClassroom'])->name('classrooms.edit');
    Route::put('/classrooms/{id}', [App\Http\Controllers\TeacherController::class, 'updateClassroom'])->name('classrooms.update');
    Route::delete('/classrooms/{id}', [App\Http\Controllers\TeacherController::class, 'destroyClassroom'])->name('teacher.classrooms.destroy');
    Route::delete('/classrooms/{id}', [App\Http\Controllers\TeacherController::class, 'destroyClassroom'])->name('classrooms.destroy');
    
    // Materials management
    Route::get('/classrooms/{classroom_id}/materials', [App\Http\Controllers\TeacherController::class, 'materials'])->name('materials.index');
    Route::get('/classrooms/{classroom_id}/materials/create', [App\Http\Controllers\TeacherController::class, 'createMaterial'])->name('materials.create');
    Route::post('/classrooms/{classroom_id}/materials', [App\Http\Controllers\TeacherController::class, 'storeMaterial'])->name('materials.store');
    Route::get('/classrooms/{classroom_id}/materials/{id}/edit', [App\Http\Controllers\TeacherController::class, 'editMaterial'])->name('materials.edit');
    Route::put('/classrooms/{classroom_id}/materials/{id}', [App\Http\Controllers\TeacherController::class, 'updateMaterial'])->name('materials.update');
    Route::get('/classrooms/{classroom_id}/materials/{id}', [App\Http\Controllers\TeacherController::class, 'showMaterial'])->name('materials.show');
    Route::delete('/classrooms/{classroom_id}/materials/{id}', [App\Http\Controllers\TeacherController::class, 'destroyMaterial'])->name('materials.destroy');
    
    // Assignments management
    Route::get('/classrooms/{classroom_id}/assignments', [App\Http\Controllers\TeacherController::class, 'assignments'])->name('assignments.index');
    Route::get('/classrooms/{classroom_id}/assignments/create', [App\Http\Controllers\TeacherController::class, 'createAssignment'])->name('assignments.create');
    Route::post('/classrooms/{classroom_id}/assignments', [App\Http\Controllers\TeacherController::class, 'storeAssignment'])->name('assignments.store');
    Route::get('/classrooms/{classroom_id}/assignments/{id}/edit', [App\Http\Controllers\TeacherController::class, 'editAssignment'])->name('assignments.edit');
    Route::put('/classrooms/{classroom_id}/assignments/{id}', [App\Http\Controllers\TeacherController::class, 'updateAssignment'])->name('assignments.update');
    Route::get('/classrooms/{classroom_id}/assignments/{id}', [App\Http\Controllers\TeacherController::class, 'showAssignment'])->name('assignments.show');
    Route::delete('/classrooms/{classroom_id}/assignments/{id}', [App\Http\Controllers\TeacherController::class, 'destroyAssignment'])->name('assignments.destroy');
    
    // Submissions/grading
    Route::get('/classrooms/{classroom_id}/assignments/{assignment_id}/submissions', [App\Http\Controllers\TeacherController::class, 'submissions'])->name('submissions.index');
    Route::get('/classrooms/{classroom_id}/assignments/{assignment_id}/submissions/{id}', [App\Http\Controllers\TeacherController::class, 'showSubmission'])->name('submissions.show');
    Route::post('/classrooms/{classroom_id}/assignments/{assignment_id}/submissions/{id}/grade', [App\Http\Controllers\TeacherController::class, 'gradeSubmission'])->name('submissions.grade');
    
    // Students management
    Route::get('/classrooms/{classroom_id}/students', [App\Http\Controllers\TeacherController::class, 'students'])->name('students.index');
    
    // Members/Students management
    Route::post('/classrooms/{classroom_id}/members', [App\Http\Controllers\TeacherController::class, 'storeMember'])->name('members.store');
    Route::put('/classrooms/{classroom_id}/members/{id}/role', [App\Http\Controllers\TeacherController::class, 'updateMemberRole'])->name('members.update.role');
    Route::delete('/classrooms/{classroom_id}/members/{id}', [App\Http\Controllers\TeacherController::class, 'destroyMember'])->name('members.remove');
});


require __DIR__.'/auth.php';