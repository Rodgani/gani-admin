<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\UserController;

Route::controller(UserController::class)
    ->group(function () {
        Route::get('/users', 'index')->name('user.index');
        Route::delete('/users/{user}', 'destroy')->name('user.destroy');
        Route::put('/users/{user}', 'update')->name('user.update');
        Route::post('/users', 'store')->name('user.store');
    });
