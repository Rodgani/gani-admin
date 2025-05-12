<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\UserController;

Route::controller(UserController::class)->prefix('users')
    ->group(function () {
        Route::get('/', 'index')->name('user.index');
        Route::delete('/{id}', 'destroy')->name('user.destroy');
        Route::put('/{id}', 'update')->name('user.update');
        Route::post('/', 'store')->name('user.store');
    });






