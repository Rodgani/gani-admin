<?php

namespace Modules\Authentication\Providers;

use Illuminate\Support\ServiceProvider;

class AuthProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->register(AuthRouteProvider::class);
    }
}