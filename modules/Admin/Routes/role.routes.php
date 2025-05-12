<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\RoleController;

Route::controller(RoleController::class)->prefix('roles')
    ->group(function () {
        Route::get('/', 'index')->name('role.index');
        Route::post('/', 'store')->name('role.store');
        Route::put('/{id}', 'update')->name('role.update');
    });
