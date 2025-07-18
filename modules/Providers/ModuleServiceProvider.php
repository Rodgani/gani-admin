<?php

declare(strict_types=1);

namespace Modules\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Admin\Providers\AdminProvider;
use Modules\Authentication\Providers\AuthProvider;

final class ModuleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register sub-provider that handles routes and migrations
        $this->app->register(AdminProvider::class);
        $this->app->register(AuthProvider::class);
    }
}
