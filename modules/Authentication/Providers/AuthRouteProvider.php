<?php

namespace Modules\Authentication\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class AuthRouteProvider extends RouteServiceProvider
{
    public function boot()
    {
        $this->routes(function () {
            Route::middleware('web')->group(__DIR__ . '/../Routes/auth.routes.php');
        });
    }
}