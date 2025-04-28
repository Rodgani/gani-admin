<?php

use App\Http\Controllers\ScaffoldController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth'])->group(function (): void {

    Route::get('/', fn() => Redirect::to('/login'));

    Route::get('dashboard', fn() => Inertia::render('dashboard'))
        ->name('dashboard');

    Route::middleware('scaffold')->group(function (): void {
        Route::controller(ScaffoldController::class)->prefix('scaffold')->group(function () {
            Route::get("/", "index");
        });
    });
});

