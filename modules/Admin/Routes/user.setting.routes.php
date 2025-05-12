<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Modules\Admin\Http\Controllers\PasswordController;
use Modules\Admin\Http\Controllers\ProfileController;

Route::redirect('settings', 'settings/profile');

Route::prefix("settings")->group(function (): void {

    Route::controller(ProfileController::class)->group(function () {
        Route::get('profile', 'edit')->name('profile.edit');
        Route::patch('profile', 'update')->name('profile.update');
        Route::delete('profile', 'destroy')->name('profile.destroy');
    });

    Route::controller(PasswordController::class)->prefix("password")->group(function () {
        Route::get('/', 'edit')->name('password.edit');
        Route::put('/', 'update')->name('password.update');
    });

    Route::get('/appearance', function () {
        return Inertia::render('settings/appearance');
    })->name('appearance');

});