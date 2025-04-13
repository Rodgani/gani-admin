<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix("admin")->group(function () {
    require __DIR__ . '/users.route.php';
    require __DIR__ . '/roles.route.php';
});