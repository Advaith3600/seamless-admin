<?php

namespace Advaith\SeamlessAdmin;

class SeamlessAdmin
{
    private array $routes = [];

    public function add(string $name, string $alias, callable $isAllowed = null): SeamlessAdmin
    {
        if (is_null($isAllowed) || $isAllowed()) $this->routes[] = [$name, $alias];

        return $this;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
