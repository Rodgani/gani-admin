<?php

namespace Modules\Admin\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class AdminServiceProvider extends RouteServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->routes(function () {
            Route::middleware('web')->group(__DIR__ . '/../Routes/admin.routes.php');
        });
    }
}