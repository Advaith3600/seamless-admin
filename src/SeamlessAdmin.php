<?php

namespace Advaith\SeamlessAdmin;

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
            fn($route) => is_null($route['options']['isAllowed']) || $route['options']['isAllowed']()
        );
    }
}
