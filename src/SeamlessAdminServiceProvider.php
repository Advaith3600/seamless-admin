<?php

namespace Advaith\SeamlessAdmin;

use Advaith\SeamlessAdmin\Console\ClearCache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class SeamlessAdminServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // registering services in the container
        $this->app->singleton('modelResolver', fn() => new ModelResolver());
        $this->app->singleton('seamlessAdmin', fn() => new SeamlessAdmin());

        // registering routes
        $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'seamless-admin');
    }

    public function boot()
    {
        // registering routes
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');

        // registering views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'seamless');

        if ($this->app->runningInConsole()) {
            // publishing public assets
            $this->publishes([
                __DIR__ . '/assets' => public_path('seamless-admin'),
            ], 'seamless-admin-assets');

            // publishing config file
            $this->publishes([
                __DIR__ . '/config/config.php' => config_path('seamless-admin.php')
            ], 'seamless-admin-config');

            // registering console commands
            $this->commands([ClearCache::class]);
        }

        $this->bootDirectives();
    }

    private function bootDirectives()
    {
        Blade::directive('saSafeVite', function ($url) {
            $url = substr($url, 1, -1);
            $vite = \Illuminate\Foundation\Vite::class;
            if ($this->app->has($vite)) {
                return $this->app->make($vite)($url, 'seamless-admin');
            }

            $manifest = json_decode(file_get_contents(__DIR__ . "/assets/manifest.json"), true);
            $scripts = "";

            if (isset($manifest[$url]['imports'])) {
                foreach ($manifest[$url]['imports'] as $preload) {
                    $location = asset("seamless-admin/" . $manifest[$preload]['file']);
                    $scripts .= "<link rel=\"modulepreload\" href=\"{$location}\" />\n";
                }
            }

            $location = asset("seamless-admin/" . $manifest[$url]['file']);
            if (strpos($url, 'css') !== false) {
                $scripts .= "<link rel=\"preload\" href=\"{$location}\" as=\"style\" />\n";
                $scripts .= "<link rel=\"stylesheet\" href=\"{$location}\" />";
            } else {
                $scripts .= "<link rel=\"modulepreload\" href=\"{$location}\" />\n";
                $scripts .= "<script type=\"module\" src=\"{$location}\"></script>";
            }

            return $scripts;
        });
    }
}
