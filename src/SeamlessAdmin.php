<?php

namespace Advaith\SeamlessAdmin;

use Composer\InstalledVersions;

class SeamlessAdmin
{
    private array $routes = [];

    public function add(string $name, string $alias, array $options = []): SeamlessAdmin
    {
        $this->routes[] = [
            'name' => $name,
            'alias' => $alias,
            'options' => $options
        ];

        return $this;
    }

    public function getRoutes(): array
    {
        return array_filter(
            $this->routes,
            fn($route) => !isset($route['options']['isAllowed']) || $route['options']['isAllowed']()
        );
    }

    public static function getPackageVersion(): string
    {
        return InstalledVersions::getPrettyVersion('advaith/seamless-admin');
    }
}
