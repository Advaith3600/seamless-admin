<?php

namespace Advaith\SeamlessAdmin\Facades;

use Illuminate\Support\Facades\Facade;

class SeamlessAdmin extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'seamlessAdmin';
    }
}
