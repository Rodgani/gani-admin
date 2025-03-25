<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->group(function () {
    Route::get('/admin/users', 'userIndex')->name('user.index');
    Route::delete('/admin/users/{user}', 'destroy')->name('user.destroy');
    Route::put('/admin/users/{user}', 'update')->name('user.update');
    Route::post('/admin/users', 'store')->name('user.store');
});
