<?php

namespace Advaith\SeamlessAdmin\Tests;

use Illuminate\Database\Schema\Blueprint;
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
            $destinationPath = 'Models/User.php';
        } else {
            $namespace = 'App';
            $destinationPath = 'User.php';
        }

        $locations = [
            app_path($destinationPath),
            __DIR__ . '/../vendor/laravel/laravel/app/' . $destinationPath
        ];

        $stub = str_replace('{{namespace}}', $namespace, $stub);
        foreach ($locations as $location) {
            file_put_contents($location, $stub);
        }
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

    protected function setUpDatabase($app)
    {
        $schema = $app['db']->connection()->getSchemaBuilder();

        $schema->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }
}
