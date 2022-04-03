<?php $resolver = app('modelResolver'); ?>

<aside id="sidebar">
    <ul>
        @foreach ($resolver->models as $model)
            <li>
                <a
                    href="{{ route('admin.type.index', $resolver->parseType($model) ) }}"
                    class="{{ str(request()->url())->contains($resolver->parseType($model)) ? 'active' : '' }}"
                >
                    {{ str(class_basename($model))->plural() }}
                </a>
            </li>
        @endforeach
    </ul>
</aside>
