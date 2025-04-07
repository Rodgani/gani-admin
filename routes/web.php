<?php

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth'])->group(function () {

    Route::get('/', fn() => Redirect::to('/login'))
        ->name('login');

    Route::get('dashboard', fn() => Inertia::render('dashboard'))
        ->name('dashboard');

    require __DIR__ . '/settings.php';
    require __DIR__ . '/users.php';
    require __DIR__ . '/roles.php';
});

require __DIR__ . '/auth.php';

