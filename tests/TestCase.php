<?php

namespace Advaith\SeamlessAdmin\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Advaith\SeamlessAdmin\SeamlessAdminServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // replace the User model with the stub
        copy(
            __DIR__ . '/stubs/User.stub',
            app_path('Models/User.php')
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            SeamlessAdminServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(base_path('database/migrations'));
    }

    protected function getBasePath(): string
    {
        return __DIR__ . '/../vendor/laravel/laravel';
    }
}
