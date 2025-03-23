<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserVerifikasiController;

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::get('user', 'userProfile')->middleware('auth:sanctum');
    Route::get('logout', 'userLogout')->middleware('auth:sanctum');
    Route::post('/forgot-password', 'sendResetLink');
    Route::post('/reset-password', 'resetPassword');
});

// Pindahkan route ini ke luar grup
Route::get('/verify-email', [AuthController::class, 'verifyEmail']);

// User profile routes
Route::post('/user/{id}/profile', [UserProfileController::class, 'store']); 
Route::get('/user/{id}/profile', [UserProfileController::class, 'show']);
Route::put('/user/{id}/profile', [UserProfileController::class, 'update']);
Route::delete('/user/{id}/profile', [UserProfileController::class, 'destroy']);

Route::middleware('auth:sanctum')->post('/upload-files', [UserVerifikasiController::class, 'uploadFiles']);

