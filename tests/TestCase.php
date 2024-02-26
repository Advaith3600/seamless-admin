<?php

namespace Advaith\SeamlessAdmin\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Advaith\SeamlessAdmin\SeamlessAdminServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->prepareStubs();
    }

    private function prepareStubs()
    {
        $hasModelsPrefix = ((float) app()->version()) >= 8.0;
        $stubPath = __DIR__ . '/stubs/User.stub';
        $stub = file_get_contents($stubPath);

        if ($hasModelsPrefix) {
            $namespace = 'App\\Models';
            $destinationPath = app_path('Models/User.php');
        } else {
            $namespace = 'App';
            $destinationPath = app_path('User.php');
        }

        $stub = str_replace('UserStubNamespace', $namespace, $stub);
        file_put_contents($destinationPath, $stub);
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
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
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
