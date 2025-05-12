<?php

namespace Modules\Admin\Providers;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->app->register(AdminRouteServiceProvider::class);
    }
}