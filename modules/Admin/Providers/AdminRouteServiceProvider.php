<?php

namespace Modules\Admin\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use Illuminate\Support\Facades\Route;

class AdminRouteServiceProvider extends BaseRouteServiceProvider
{
    public function boot(): void
    {
        $this->routes(function () {
            Route::middleware('web')->group(__DIR__ . '/../Routes/admin.routes.php');
        });
    }
}
