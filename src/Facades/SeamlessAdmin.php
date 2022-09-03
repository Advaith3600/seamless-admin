<?php

namespace Advaith\SeamlessAdmin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Advaith\SeamlessAdmin\SeamlessAdmin
 *
 * @method static \Advaith\SeamlessAdmin\SeamlessAdmin add(string $name, string $alias, array $options = [])
 * @method static array getRoutes()
 */
class SeamlessAdmin extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'seamlessAdmin';
    }
}
