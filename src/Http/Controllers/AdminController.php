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
        $this->middleware('auth');

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

    public function index($type): View
    {
        $type = $this->resolveType($type);

        $fillable = collect((new $type)->getFillable())->diff((new $type)->getHidden());

        $data = $type::orderBy(request()->by ?? 'id', request()->order ?? 'desc')
            ->when(request()->q, function ($query) use ($fillable) {
                $search = request()->q;
                foreach ($fillable as $column)
                    $query->orWhere($column, 'like', "%{$search}%");
            })
            ->select((clone $fillable)->add('id')->toArray())
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

        $fillable = collect((new $type)->getFillable())
            ->merge((new $type)->getHidden())
            ->unique()
            ->add('id')
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

        return view('seamless::type.create', [
            'type' => $type,
            'columns' => $this->resolver->getColumns($type)
        ]);
    }

    public function store($type, Request $request): RedirectResponse | string
    {
        $type = $this->resolveType($type);

        $columns = collect($this->resolver->getColumns($type));

        try {
            $data = $type::create($request->only($columns->pluck('Field')->toArray()));
            return redirect()->route('admin.type.show', [request()->type, $data->id]);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function edit($type, $id): View
    {
        $type = $this->resolveType($type);

        $columns = collect($this->resolver->getColumns($type));

        $data = $type::find(
            $id,
            (clone $columns)
                ->map(fn ($f) => $f->Field)
                ->add('id')
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

        $columns = collect($this->resolver->getColumns($type));

        try {
            $data = $type::find($id)->update($request->only($columns->pluck('Field')->toArray()));
            return redirect()->route('admin.type.show', [request()->type, $data->id]);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function delete($type, Request $request): View
    {
        $ids = $request->ids;

        if (count($ids) === 0) abort(404);

        return view('seamless::type.delete', [
            'type' => $this->resolveType($type),
            'ids' => $ids
        ]);
    }

    public function destroy($type, Request $request): RedirectResponse | string
    {
        $type = $this->resolveType($type);
        $ids = $request->ids;

        if (count($ids) === 0) abort(404);

        try {
            $type::whereIn('id', $ids)->delete();
            return redirect()->route('admin.type.index', request()->type);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
