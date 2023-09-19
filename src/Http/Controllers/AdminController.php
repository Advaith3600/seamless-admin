<?php

namespace Advaith\SeamlessAdmin\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        // only authenticated users will be able to use the admin panel
        $this->middleware(config('seamless-admin.middleware'));
    }

    public function welcome(): View
    {
        return view('seamless::welcome');
    }

    private function wrap_foreign_columns(string $type, array $columns): array
    {
        $foreign_keys = collect($this->resolver->foreign_keys($type));

        return array_map(function ($column) use ($foreign_keys) {
            $column->foreign = $foreign_keys
                ->filter(fn ($key) => $key->column_name === $column->field)
                ->first();

            return $column;
        }, $columns);
    }

    public function index($type): View
    {
        $type = $this->resolveType($type);
        $this->hasPrivilege($type, 'Index');

        return view('seamless::type.index', [
            'type' => $type
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
            'columns' => $this->wrap_foreign_columns(
                $type,
                $this->resolver->getColumns($type)
            )
        ]);
    }

    public function store($type, Request $request): RedirectResponse | string
    {
        $type = $this->resolveType($type);
        $this->hasPrivilege($type, 'Create');

        $columns = collect($this->resolver->getColumns($type));

        try {
            $fields = (new $type)->adminOnCreate($request->only($columns->pluck('field')->toArray()));
            $data = $type::forceCreate($fields);
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
                ->map(fn ($f) => $f->field)
                ->add((new $type)->getKeyName())
                ->toArray()
        );

        return view('seamless::type.edit', [
            'type' => $type,
            'data' => $data,
            'columns' => $this->wrap_foreign_columns($type, $columns->toArray())
        ]);
    }

    public function update($type, $id, Request $request): RedirectResponse | string
    {
        $type = $this->resolveType($type);
        $this->hasPrivilege($type, 'Edit');

        $columns = collect($this->resolver->getColumns($type));

        try {
            $item = $type::find($id);
            $fields = $item->adminOnEdit($request->only($columns->pluck('field')->toArray()));
            $type::unguard(); // turn of Laravel's mass assignment protection
            $item->update($fields);
            $type::reguard(); // turn backing on the mass assignment protection
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
