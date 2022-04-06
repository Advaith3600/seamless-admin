@php
    $resolver = app('modelResolver');
    $models = array_filter($resolver->getModels(), fn($model) => (new $model)->adminCanAccessIndex());
    $routes = app('seamlessAdmin')->getRoutes();
@endphp

<aside id="sidebar">
    <ul>
        @foreach ($models as $model)
            <li>
                <a
                    href="{{ route('admin.type.index', $resolver->parseType($model) ) }}"
                    class="{{ str(request()->url())->contains($resolver->parseType($model)) ? 'active' : '' }}"
                >
                    {{ str(class_basename($model))->plural() }}
                </a>
            </li>
        @endforeach

        @foreach ($routes as $route)
            <li>
                <a
                    href="{{ route($route[0]) }}"
                    class="{{ Route::is("{$route[0]}.*") || Route::is($route[0]) ? 'active' : '' }}"
                >{{ $route[1] }}</a>
            </li>
        @endforeach

        @if (count($models) + count($routes) === 0)
            <li class="text-center blank">
                No tables found.
            </li>
        @endif
    </ul>
</aside>
