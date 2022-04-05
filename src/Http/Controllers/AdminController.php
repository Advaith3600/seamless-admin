<?php

namespace Advaith\SeamlessAdmin\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Advaith\SeamlessAdmin\ModelResolver;

class AdminController extends Controller
{
    private ModelResolver $resolver;

    public function __construct()
    {
        // only authenticated users will be able to use the admin panel
        $this->middleware(config('seamless-admin.middleware'));

        $this->resolver = app('modelResolver');
    }

    public function welcome(): View
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

    private function hasPrivilege(string $type, string $for): void
    {
        if ($for !== 'Index') $this->hasPrivilege($type, 'Index');

        $method = "adminCanAccess{$for}";

        // show 403, unauthorized user screen if the user doesn't have the privilege
        if (!(new $type)->$method()) abort(403);
    }

    public function index($type): View
    {
        $type = $this->resolveType($type);
        $this->hasPrivilege($type, 'Index');
        $instance = new $type;

        $fillable = collect($instance->adminIndexFields());

        $data = $type::orderBy(request()->by ?? $instance->getKeyName(), request()->order ?? 'desc')
            ->when(request()->q, function ($query) use ($fillable) {
                $search = request()->q;
                foreach ($fillable as $column)
                    $query->orWhere($column, 'like', "%{$search}%");
            })
            ->select((clone $fillable)->add($instance->getKeyName())->toArray())
            ->paginate(request()->perPage ?? 10);

        return view('seamless::type.index', [
            'type' => $type,
            'data' => $data,
            'fillable' => $fillable
        ]);
    }

    public function show($type, $id): View
    {
        $type = $this->resolveType($type);
        $this->hasPrivilege($type, 'Index');
        $instance = new $type;

        $fillable = collect($instance->getFillable())
            ->merge($instance->getHidden())
            ->unique()
            ->add($instance->getKeyName())
            ->toArray();

        $data = $type::find($id, $fillable);

        return view('seamless::type.show', [
            'type' => $type,
            'data' => $data
        ]);
    }

    public function create($type): View
    {
        $type = $this->resolveType($type);
        $this->hasPrivilege($type, 'Create');

        return view('seamless::type.create', [
            'type' => $type,
            'columns' => $this->resolver->getColumns($type)
        ]);
    }

    public function store($type, Request $request): RedirectResponse | string
    {
        $type = $this->resolveType($type);
        $this->hasPrivilege($type, 'Create');

        $columns = collect($this->resolver->getColumns($type));

        try {
            $fields = (new $type)->adminOnCreate($request->only($columns->pluck('Field')->toArray()));
            $data = $type::create($fields);
            $data->adminCreated();
            return redirect()->route('admin.type.show', [request()->type, $data->getKey()]);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function edit($type, $id): View
    {
        $type = $this->resolveType($type);
        $this->hasPrivilege($type, 'Edit');

        $columns = collect($this->resolver->getColumns($type));

        $data = $type::find(
            $id,
            (clone $columns)
                ->map(fn ($f) => $f->Field)
                ->add((new $type)->getKeyName())
                ->toArray()
        );

        return view('seamless::type.edit', [
            'type' => $type,
            'columns' => $columns,
            'data' => $data
        ]);
    }

    public function update($type, $id, Request $request): RedirectResponse | string
    {
        $type = $this->resolveType($type);
        $this->hasPrivilege($type, 'Edit');

        $columns = collect($this->resolver->getColumns($type));

        try {
            $item = $type::find($id);
            $fields = $item->adminOnEdit($request->only($columns->pluck('Field')->toArray()));
            $item->update($fields);
            $item->adminEdited();
            return redirect()->route('admin.type.show', [request()->type, $item->getKey()]);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function delete($type, Request $request): View
    {
        $type = $this->resolveType($type);
        $this->hasPrivilege($type, 'Delete');
        $ids = $request->ids;

        if (count($ids) === 0) abort(404);

        return view('seamless::type.delete', [
            'type' => $type,
            'ids' => $ids
        ]);
    }

    public function destroy($type, Request $request): RedirectResponse | string
    {
        $type = $this->resolveType($type);
        $this->hasPrivilege($type, 'Delete');
        $ids = $request->ids;

        if (count($ids) === 0) abort(404);

        try {
            $type::whereIn((new $type)->getKeyName(), $ids)->delete();
            return redirect()->route('admin.type.index', request()->type);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
