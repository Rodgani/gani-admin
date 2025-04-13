<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function (): void {
    Route::prefix("admin")->group(function () {
        require __DIR__ . '/user.routes.php';
        require __DIR__ . '/role.routes.php';
    });

    require __DIR__ . '/user.setting.routes.php';
});