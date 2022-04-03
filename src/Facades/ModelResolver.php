<?php

namespace Advaith\SeamlessAdmin\Facades;

use Illuminate\Support\Facades\Facade;

class ModelResolver extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'modelResolver';
    }
}
