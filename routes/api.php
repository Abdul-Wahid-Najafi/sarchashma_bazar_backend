<?php

use App\Http\Controllers\Api\AuthController;

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
     Route::get('status', function() {
        return response()->json(['status' => 'ok']);
    });
    Route::post('send-otp', [AuthController::class, 'sendOtp']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('google-login', [AuthController::class, 'googleLogin']); 
        // سیستم لاگین سنتی با ایمیل و پسورد
    Route::post('login-email', [AuthController::class, 'loginWithEmail']);
    
});


Route::middleware('auth:sanctum')->prefix('auth')->group(function () {
    Route::post('update-profile', [AuthController::class, 'updateProfile']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});


