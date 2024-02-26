<?php

namespace Advaith\SeamlessAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class APIController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        // only authenticated users will be able to use the admin panel
        $this->middleware(config('seamless-admin.api_middleware'));
    }

    public function search_foreign_references(string $type, Request $request): array
    {
        $limit = 8;
        $type = $this->resolveType($type);
        $this->hasPrivilege($type, 'Index');

        // resolve model from the referenced table name
        $model = $this->resolver->resolveModel($request->referenced_table_name);

        if ($model) {
            $instance = new $model;
            $columns = $instance->adminIndexFields();

            $keyName = $request->referenced_column_name;
            $query = $model::limit($limit)->where($keyName, 'like', "%{$request->search}%");
            foreach ($columns as $column)
                $query->orWhere($column, 'like', "%{$request->search}%");

            return $query
                ->get()
                ->map(fn($item) => [
                    'key' => $item[$keyName],
                    'string' => (string) $item
                ])
                ->toArray();
        }

        return DB::table($request->referenced_table_name)
            ->limit($limit)
            ->where($request->referenced_column_name, 'like', "%{$request->search}%")
            ->select("$request->referenced_column_name as key")
            ->get()
            ->toArray();
    }

    public function type_index_data(string $type, Request $request)
    {
        $type = $this->resolveType($type);
        $this->hasPrivilege($type, 'Index');
        $instance = new $type;

        $fillable = collect($instance->adminIndexFields());

        $data = $type::orderBy(request()->by ?? $instance->getKeyName(), request()->order ?? 'desc')
            ->when(request()->search, function ($query) use ($fillable) {
                $search = request()->search;
                foreach ($fillable as $column)
                    $query->orWhere($column, 'like', "%{$search}%");
            })
            ->select((clone $fillable)->add($instance->getKeyName())->toArray())
            ->paginate(request()->perPage ?? 10);


        return $data;
    }
}
