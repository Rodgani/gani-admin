<?php

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth'])->group(function () {

    Route::get('/', fn() => Redirect::to('/login'))
        ->name('login');

    Route::get('dashboard', fn() => Inertia::render('dashboard'))
        ->name('dashboard');
});

