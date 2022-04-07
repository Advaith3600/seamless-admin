<?php

namespace Advaith\SeamlessAdmin\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Advaith\SeamlessAdmin\SeamlessAdminServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            SeamlessAdminServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();
    }
}
