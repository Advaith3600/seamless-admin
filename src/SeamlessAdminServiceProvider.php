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
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        // registering views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'seamless');

        // registering routes
        $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'seamless-admin');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // publishing public assets
            $this->publishes([__DIR__ . '/resources/assets/css' => public_path('seamless-admin/css')], 'assets');
            $this->publishes([__DIR__ . '/resources/assets/js' => public_path('seamless-admin/js')], 'assets');

            // publishing config file
            $this->publishes([__DIR__ . '/config/config.php' => config_path('seamless-admin.php')], 'config');
        }
    }
}
