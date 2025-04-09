<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->prefix("admin")
    ->group(function () {
        Route::get('/users', 'index')->name('user.index');
        Route::delete('/users/{user}', 'destroy')->name('user.destroy');
        Route::put('/users/{user}', 'update')->name('user.update');
        Route::post('/users', 'store')->name('user.store');
    });
