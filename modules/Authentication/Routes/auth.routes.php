<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Modules\Authentication\Http\Controllers\AuthenticatedSessionController;
use Modules\Authentication\Http\Controllers\ConfirmablePasswordController;
use Modules\Authentication\Http\Controllers\EmailVerificationNotificationController;
use Modules\Authentication\Http\Controllers\EmailVerificationPromptController;
use Modules\Authentication\Http\Controllers\NewPasswordController;
use Modules\Authentication\Http\Controllers\PasswordResetLinkController;
use Modules\Authentication\Http\Controllers\RegisteredUserController;
use Modules\Authentication\Http\Controllers\VerifyEmailController;

Route::middleware('guest')->group(function (): void {

    // Route::get('register', [RegisteredUserController::class, 'index'])
    //     ->name('register');

    // Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'index'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'index'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'index'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function (): void {

    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
