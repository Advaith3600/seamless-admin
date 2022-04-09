<?php

namespace Advaith\SeamlessAdmin\Http\Controllers;

use Advaith\SeamlessAdmin\ModelResolver;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    protected ModelResolver $resolver;

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->resolver = app('modelResolver');
    }

    protected function resolveType(string $type): string
    {
        $type = $this->resolver->resolveType($type);

        // show 404 error page if type is not found
        if (!$type) abort(404);

        return $type;
    }

    protected function hasPrivilege(string $type, string $for): void
    {
        if ($for !== 'Index') $this->hasPrivilege($type, 'Index');

        $method = "adminCanAccess{$for}";

        // show 403, unauthorized user screen if the user doesn't have the privilege
        if (!(new $type)->$method()) abort(403);
    }
}
