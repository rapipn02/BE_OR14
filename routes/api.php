<?php

use App\Http\Controllers\Api\AuthController;
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

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::get('user', [AuthController::class, 'userProfile']);
    Route::get('logout', [AuthController::class, 'userLogout']);

    // Profile routes
    Route::get('/profile', [UserProfileController::class, 'getProfile']);
    Route::post('/profile', [UserProfileController::class, 'saveProfile']);
    Route::delete('/profile', [UserProfileController::class, 'destroy']);
    Route::get('/profile/status', [UserProfileController::class, 'checkProfileStatus']);

    // Verification routes for regular users
    Route::post('/verification', [UserVerifikasiController::class, 'uploadFiles']);
    Route::get('/verification', [UserVerifikasiController::class, 'checkVerificationStatus']);
});
