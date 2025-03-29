<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExamController;
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

// Create a debug/test route outside of auth for testing
Route::get('/admin/users/fake', function () {
    $fakeUsers = [];

    for ($i = 1; $i <= 10; $i++) {
        $fakeUsers[] = [
            'id' => $i,
            'name' => "User $i",
            'email' => "user$i@example.com",
            'created_at' => now()->subDays(rand(1, 30))->toDateTimeString(),
            'email_verified_at' => now()->subDays(rand(1, 29))->toDateTimeString(),
            'profile' => [
                'nama_lengkap' => "Full Name $i",
                'panggilan' => "Nick $i",
                'nim' => "A" . str_pad($i, 8, '0', STR_PAD_LEFT),
                'whatsapp' => "08123456" . str_pad($i, 4, '0', STR_PAD_LEFT),
                'program_studi' => "Program Study $i"
            ],
            'verification' => rand(0, 3) > 0 ? [
                'id' => $i,
                'verification_status' => ['diproses', 'disetujui', 'ditolak'][rand(0, 2)],
                'created_at' => now()->subDays(rand(1, 20))->toDateTimeString(),
                'verified_at' => rand(0, 1) ? now()->subDays(rand(1, 10))->toDateTimeString() : null,
                'rejection_reason' => rand(0, 1) ? "Some rejection reason $i" : null
            ] : null,
            'exams' => rand(0, 1) ? [
                [
                    'id' => $i,
                    'score' => rand(50, 100),
                    'division' => [
                        'id' => rand(1, 3),
                        'name' => "Division " . rand(1, 3)
                    ]
                ]
            ] : []
        ];
    }

    return response()->json([
        'success' => true,
        'data' => [
            'current_page' => 1,
            'data' => $fakeUsers,
            'last_page' => 3,
            'per_page' => 10,
            'total' => 30
        ]
    ]);
});

// Admin routes - separated to avoid nesting middleware
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'getUsers']);
    Route::post('/verification/{id}', [AdminController::class, 'processVerification']);
});
