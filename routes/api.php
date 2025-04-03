<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserVerifikasiController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('/forgot-password', 'sendResetLink');
    Route::post('/reset-password', 'resetPassword');
    Route::get('/verify-email', 'verifyEmail');
});

Route::post('/resend-verification', [AuthController::class, 'resendVerification']);

// Get public timelines - accessible without authentication
Route::get('/timelines', [TimelineController::class, 'getPublicTimelines']);

// For development and debugging - can be removed in production
Route::get('/admin/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'Admin route test - publicly accessible',
        'time' => now()->toDateTimeString()
    ]);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::get('user', [AuthController::class, 'userProfile']);
    Route::get('logout', [AuthController::class, 'userLogout']);

    // Profile routes
    Route::get('/profile', [UserProfileController::class, 'getProfile']);
    Route::post('/profile', [UserProfileController::class, 'saveProfile']);
    Route::get('/profile/status', [UserProfileController::class, 'checkProfileStatus']);

    // Verification routes for regular users
    Route::post('/verification', [UserVerifikasiController::class, 'uploadFiles']);
    Route::get('/verification', [UserVerifikasiController::class, 'checkVerificationStatus']);

    // Exam routes
    Route::prefix('exam')->group(function () {
        Route::get('/divisions', [ExamController::class, 'getDivisions']);
        Route::post('/start', [ExamController::class, 'startExam']);
        Route::get('/questions', [ExamController::class, 'getExamQuestions']);
        Route::post('/answer', [ExamController::class, 'submitAnswer']);
        Route::post('/finish', [ExamController::class, 'finishExam']);
        Route::get('/result/{examId?}', [ExamController::class, 'getExamResult']);
        Route::get('/history', [ExamController::class, 'getExamHistory']);
    });
});


// Admin routes - separated to avoid nesting middleware
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'getUsers']);
    Route::post('/verification/{id}', [AdminController::class, 'processVerification']);

    // Timeline Management
    Route::prefix('timeline')->group(function () {
        Route::get('/', [TimelineController::class, 'getTimelines']);
        Route::post('/', [TimelineController::class, 'createTimeline']);
        Route::put('/{id}', [TimelineController::class, 'updateTimeline']);
        Route::delete('/{id}', [TimelineController::class, 'deleteTimeline']);
        Route::post('/reorder', [TimelineController::class, 'reorderTimelines']);
    });
});
