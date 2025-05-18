<?php

namespace Modules\Admin\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class AdminRouteProvider extends RouteServiceProvider
{
    public function boot()
    {
        $this->routes(function () {
            Route::middleware('web')->group(__DIR__ . '/../Routes/admin.routes.php');
        });
    }
}