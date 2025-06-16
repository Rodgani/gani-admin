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

    Route::controller(RegisteredUserController::class)->prefix('register')->group(function () {
        Route::get('/',  'index')->name('register');
        Route::post('/',  'store');
    });

    Route::controller(AuthenticatedSessionController::class)->prefix('login')->group(function () {
        Route::get('/',  'index')
            ->name('login');
        Route::post('/',  'store');
    });

    Route::controller(PasswordResetLinkController::class)->prefix('forgot-password')->group(function () {
        Route::get('/',  'index')
            ->name('password.request');
        Route::post('/',  'store')
            ->name('password.email');
    });

    Route::controller(NewPasswordController::class)->prefix('reset-password')->group(function () {
        Route::get('/{token}',  'index')
            ->name('password.reset');
        Route::post('/',  'store')
            ->name('password.store');
    });
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

    Route::controller(ConfirmablePasswordController::class)->prefix('confirm-password')->group(function () {
        Route::get('/',  'show')
            ->name('password.confirm');
        Route::post('/',  'store');
    });

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
