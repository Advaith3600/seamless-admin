<?php

namespace Advaith\SeamlessAdmin;

class SeamlessAdmin
{
    private array $routes = [];

    public function add(string $name, string $alias, callable $isAllowed = null): SeamlessAdmin
    {
        $this->routes[] = [$name, $alias, $isAllowed];

        return $this;
    }

    public function getRoutes(): array
    {
        return array_filter($this->routes, fn($route) => is_null($route[2]) || $route[2]());
    }
}
