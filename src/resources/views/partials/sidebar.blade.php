@php
    $resolver = app('modelResolver');
    $models = array_filter($resolver->models, fn($model) => (new $model)->adminCanAccessIndex());
@endphp

<aside id="sidebar">
    <ul>
        @forelse ($models as $model)
            <li>
                <a
                    href="{{ route('admin.type.index', $resolver->parseType($model) ) }}"
                    class="{{ str(request()->url())->contains($resolver->parseType($model)) ? 'active' : '' }}"
                >
                    {{ str(class_basename($model))->plural() }}
                </a>
            </li>
        @empty
            <li class="text-center blank">
                No tables found.
            </li>
        @endforelse
    </ul>
</aside>
