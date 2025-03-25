<?php

use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;

Route::controller(RoleController::class)->group(function () {
    Route::get('/admin/roles', 'roleIndex')->name('role.index');
    Route::post('/admin/roles', 'store')->name('role.store');
    Route::put('/admin/roles/{role}', 'update')->name('role.update');
});
