<?php

namespace Gateway\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RoutesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Load routes dynamically from other services
        $this->loadRoutesFrom(base_path('server/authentication/routes/web.php'));
        $this->loadRoutesFrom(base_path('server/ip-handler/routes/web.php'));
    }
}
