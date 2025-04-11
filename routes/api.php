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
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\StudyController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\SubmissionController;
use App\Http\Controllers\Api\ClassroomController;
use App\Http\Controllers\Api\ClassroomMemberController;
use App\Http\Controllers\Api\ClassroomMaterialController;
use App\Http\Controllers\Api\ClassroomAssignmentController;
use App\Http\Controllers\Api\ClassroomSubmissionController;
use App\Http\Middleware\ValidateRememberToken;
use App\Http\Middleware\RoleMiddleware;

Route::middleware('api')->group(function () {
    // Public Routes
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::post('/login', [AuthenticatedSessionController::class, 'login']);

    // Protected Vicon Routes - Admin Only
    Route::prefix('vicons')->middleware([ValidateRememberToken::class, RoleMiddleware::class . ':admin'])->group(function () {
        Route::get('/', [ViconController::class, 'index']);
        Route::post('/', [ViconController::class, 'store']);
        Route::get('/{id}', [ViconController::class, 'show']);  // Changed {vicon} to {id}
        Route::post('/{id}', [ViconController::class, 'update']);  // Changed from PUT to POST
        Route::delete('/{id}', [ViconController::class, 'destroy']);  // Changed {vicon} to {id}
        Route::post('/{id}/restore', [ViconController::class, 'restore']);
    });

    Route::prefix('gallery')->group(function () {
        // Public Routes
        Route::get('/', [GalleryController::class, 'index']);
        Route::get('/{gallery}', [GalleryController::class, 'show']);
        Route::post('/{gallery}', [GalleryController::class, 'update']);

        // Admin Routes
        Route::middleware([ValidateRememberToken::class, RoleMiddleware::class . ':admin'])->group(function () {
            Route::post('/', [GalleryController::class, 'store']);
            Route::delete('/{gallery}', [GalleryController::class, 'destroy']);
        });
    });

    Route::prefix('contacts')->group(function () {
        Route::get('/', [ContactController::class, 'index']);
        Route::get('/{contact}', [ContactController::class, 'show']);

        // User Routes
        Route::middleware([ValidateRememberToken::class, RoleMiddleware::class . ':admin'])->group(function () {
            Route::post('/', [ContactController::class, 'store']);
        });

        // Admin Routes
        Route::middleware([ValidateRememberToken::class, RoleMiddleware::class . ':admin'])->group(function () {
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
        Route::middleware([ValidateRememberToken::class, RoleMiddleware::class . ':admin'])->group(function () {
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

        Route::middleware([ValidateRememberToken::class, RoleMiddleware::class . ':admin'])->group(function () {
            Route::post('/{id}/restore', [ArticleController::class, 'restore']);
            Route::post('/', [ArticleController::class, 'store']);
            Route::put('/{id}', [ArticleController::class, 'update']);
            Route::delete('/{id}', [ArticleController::class, 'destroy']);
        });
    });

    Route::prefix('materials')->group(function () {
        // Public routes
        Route::get('/', [MaterialController::class, 'index']);
        Route::get('/active', [MaterialController::class, 'active']);
        Route::get('/{id}', [MaterialController::class, 'show']);

        // Protected routes
        Route::middleware([ValidateRememberToken::class])->group(function () {
            Route::post('/', [MaterialController::class, 'store']);
            Route::post('/{material}', [MaterialController::class, 'update']);
            Route::delete('/{id}', [MaterialController::class, 'destroy']);
        });

        // Admin only routes
        Route::middleware([ValidateRememberToken::class, RoleMiddleware::class . ':Admin'])->group(function () {
            Route::post('/{id}/restore', [MaterialController::class, 'restore']);
        });
    });

    // School Routes
    Route::prefix('schools')->group(function () {
        Route::get('/', [SchoolController::class, 'index']);
        Route::get('/active', [SchoolController::class, 'active']);
        Route::get('/{id}', [SchoolController::class, 'show']);

        // Admin Routes
        Route::middleware([ValidateRememberToken::class, RoleMiddleware::class . ':admin'])->group(function () {
            Route::post('/{id}/restore', [SchoolController::class, 'restore']);
            Route::post('/{id}', [SchoolController::class, 'update']);
            Route::post('/', [SchoolController::class, 'store']);
            Route::delete('/{id}', [SchoolController::class, 'destroy']);
        });

        // Get studies by school
        Route::get('/{schoolId}/studies', [StudyController::class, 'getStudiesBySchool']);
    });

    // Study Routes
    Route::prefix('studies')->group(function () {
        // Public Routes
        Route::get('/', [StudyController::class, 'index']);
        Route::get('/active', [StudyController::class, 'active']);
        Route::get('/{id}', [StudyController::class, 'show']);

        // Admin Routes
        Route::middleware([ValidateRememberToken::class, RoleMiddleware::class . ':admin'])->group(function () {
            Route::post('/{id}/restore', [StudyController::class, 'restore']);
            Route::post('/{id}', [StudyController::class, 'update']);
            Route::post('/', [StudyController::class, 'store']);
            Route::delete('/{id}', [StudyController::class, 'destroy']);
        });
    });

    // Company Routes
    Route::prefix('companies')->group(function () {
        Route::get('/', [CompanyController::class, 'index']);
        Route::get('/active', [CompanyController::class, 'active']);
        Route::get('/{id}', [CompanyController::class, 'show']);

        // Admin Routes
        Route::middleware([ValidateRememberToken::class, RoleMiddleware::class . ':admin'])->group(function () {
            Route::post('/{id}/restore', [CompanyController::class, 'restore']);
            Route::post('/{id}', [CompanyController::class, 'update']);
            Route::post('/', [CompanyController::class, 'store']);
            Route::delete('/{id}', [CompanyController::class, 'destroy']);
        });

        // Get jobs by company
        Route::get('/{companyId}/jobs', [JobController::class, 'getJobsByCompany']);
    });

    // Job Routes
    Route::prefix('jobs')->group(function () {
        // Public Routes
        Route::get('/', [JobController::class, 'index']);
        Route::get('/active', [JobController::class, 'active']);
        Route::get('/{id}', [JobController::class, 'show']);

        // Admin Routes
        Route::middleware([ValidateRememberToken::class, RoleMiddleware::class . ':admin'])->group(function () {
            Route::post('/{id}/restore', [JobController::class, 'restore']);
            Route::post('/{id}', [JobController::class, 'update']);
            Route::post('/', [JobController::class, 'store']);
            Route::delete('/{id}', [JobController::class, 'destroy']);
        });
    });

    Route::prefix('submissions')->group(function () {
        // Public routes
        Route::get('/', [SubmissionController::class, 'index']);
        Route::get('/{id}', [SubmissionController::class, 'show']);

        // Protected routes
        Route::middleware([ValidateRememberToken::class])->group(function () {
            Route::post('/', [SubmissionController::class, 'store']);
            Route::post('/{submission}', [SubmissionController::class, 'update']);
            Route::delete('/{id}', [SubmissionController::class, 'destroy']);
            Route::patch('/{id}/approve', [SubmissionController::class, 'updateApproval']);
        });

        // Admin only routes
        Route::middleware([ValidateRememberToken::class, RoleMiddleware::class . ':Admin'])->group(function () {
            Route::post('/{id}/restore', [SubmissionController::class, 'restore']);
        });
    });

    // Classroom Routes
    Route::prefix('classrooms')->group(function () {
        // Public Routes
        Route::get('/', [ClassroomController::class, 'index']);
        Route::get('/active', [ClassroomController::class, 'active']);
        Route::get('/{id}', [ClassroomController::class, 'show']);

        // Protected Routes (requires authentication)
        Route::middleware([ValidateRememberToken::class])->group(function () {
            Route::post('/', [ClassroomController::class, 'store']);
            Route::post('/join', [ClassroomController::class, 'join']);  // Move this UP, before parameterized routes
            Route::post('/{id}', [ClassroomController::class, 'update']);
            Route::delete('/{id}', [ClassroomController::class, 'destroy']);
            Route::post('/{id}/restore', [ClassroomController::class, 'restore']);
            
            // Classroom Members Routes (nested)
            Route::prefix('{classroom_id}/members')->group(function () {
                Route::get('/', [ClassroomMemberController::class, 'index']);
                Route::post('/', [ClassroomMemberController::class, 'store']);
                Route::get('/{id}', [ClassroomMemberController::class, 'show']);
                Route::post('/{id}', [ClassroomMemberController::class, 'update']);
                Route::delete('/{id}', [ClassroomMemberController::class, 'destroy']);
            });
            Route::post('{classroom_id}/leave', [ClassroomMemberController::class, 'leaveClassroom']);
            
            // Classroom Materials Routes (nested)
            Route::prefix('{classroom_id}/materials')->group(function () {
                Route::get('/', [ClassroomMaterialController::class, 'index']);
                Route::post('/', [ClassroomMaterialController::class, 'store']);
                Route::get('/{id}', [ClassroomMaterialController::class, 'show']);
                Route::post('/{id}', [ClassroomMaterialController::class, 'update']);
                Route::delete('/{id}', [ClassroomMaterialController::class, 'destroy']);
                Route::post('/{id}/restore', [ClassroomMaterialController::class, 'restore']);
            });
            
            // Classroom Assignments Routes (nested)
            Route::prefix('{classroom_id}/assignments')->group(function () {
                Route::get('/', [ClassroomAssignmentController::class, 'index']);
                Route::post('/', [ClassroomAssignmentController::class, 'store']);
                Route::get('/{id}', [ClassroomAssignmentController::class, 'show']);
                Route::post('/{id}', [ClassroomAssignmentController::class, 'update']);
                Route::delete('/{id}', [ClassroomAssignmentController::class, 'destroy']);
                Route::post('/{id}/restore', [ClassroomAssignmentController::class, 'restore']);
                
                // Submissions for assignments (nested)
                Route::prefix('{assignment_id}/submissions')->group(function () {
                    Route::get('/', [ClassroomSubmissionController::class, 'index']);
                    Route::post('/', [ClassroomSubmissionController::class, 'store']);
                    Route::get('/{id}', [ClassroomSubmissionController::class, 'show']);
                    Route::post('/{id}/grade', [ClassroomSubmissionController::class, 'grade']);
                    Route::get('/download/{id}', [ClassroomSubmissionController::class, 'download']);
                });
            });
        });
    });
});
