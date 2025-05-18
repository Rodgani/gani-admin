<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\UserController;

Route::resource('users', UserController::class)
    ->parameters(['users' => 'id']);
