<?php

namespace Advaith\SeamlessAdmin;

use Illuminate\Support\ServiceProvider;

class SeamlessAdminServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // registering singleton
        $this->app->singleton('modelResolver', fn($app) => new ModelResolver());

        // registering routes
        $this->loadRoutesFrom(__DIR__ . './routes/web.php');

        // registering views
        $this->loadViewsFrom(__DIR__ . './resources/views', 'seamless');
    }
}
