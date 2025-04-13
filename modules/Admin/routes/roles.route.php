<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\RoleController;

Route::controller(RoleController::class)
    ->group(function () {
        Route::get('/roles', 'index')->name('role.index');
        Route::post('/roles', 'store')->name('role.store');
        Route::put('/roles/{role}', 'update')->name('role.update');
    });
