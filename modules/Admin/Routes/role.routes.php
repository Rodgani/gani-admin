<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\RoleController;

Route::resource('roles', RoleController::class)
    ->parameters(['roles' => 'id']);
