<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\PasswordController;
use Modules\Admin\Http\Controllers\ProfileController;
use Modules\Admin\Http\Controllers\UserController;
use Inertia\Inertia;

Route::controller(UserController::class)->prefix('users')
    ->group(function () {
        Route::get('/', 'index')->name('user.index');
        Route::delete('/{user}', 'destroy')->name('user.destroy');
        Route::put('/{user}', 'update')->name('user.update');
        Route::post('/', 'store')->name('user.store');
    });






