<?php

use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;

Route::controller(RoleController::class)->prefix("admin")
    ->group(function () {
        Route::get('/roles', 'index')->name('role.index');
        Route::post('/roles', 'store')->name('role.store');
        Route::put('/roles/{role}', 'update')->name('role.update');
    });
