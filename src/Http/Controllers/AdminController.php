<?php

namespace Advaith\SeamlessAdmin\Http\Controllers;

class AdminController extends Controller
{
    private $resolver;

    public function __construct()
    {
        // only authenticated users will be able to use the admin panel
        $this->middleware('auth');

        $this->resolver = app('modelResolver');
    }

    public function welcome()
    {
        return view('seamless::welcome');
    }

    private function resolveType(string $type): string
    {
        $type = $this->resolver->resolveType($type);

        // show 404 error page if type is not found
        if (!$type) abort(404);

        return $type;
    }

    private function getFillable(string $type): array
    {
        $dummy = new $type;
        return collect($dummy->getFillable())->diff($dummy->getHidden())->toArray();
    }

    public function index($type)
    {
        $type = $this->resolveType($type);

        $fillable = $this->getFillable($type);

        $data = $type::orderBy('id', 'desc')->select($fillable)->paginate(10);

        return view('seamless::type.index', [
            'type' => $type,
            'data' => $data,
            'fillable' => $fillable
        ]);
    }
}
