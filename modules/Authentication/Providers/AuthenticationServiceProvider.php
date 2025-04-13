<?php

namespace Modules\Authentication\Providers;

use Illuminate\Support\ServiceProvider;

class AuthenticationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->register(AuthenticationRouteServiceProvider::class);
    }
}