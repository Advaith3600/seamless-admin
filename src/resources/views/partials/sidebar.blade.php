@php
    $resolver = app('modelResolver');
    [$groups, $count] = $resolver->getSidebarElements();
@endphp

<aside id="sidebar">
    <ul>
        @foreach($groups as $key => $group)
            @if($key !== '_default') <li class="nav-group">{{ $key }}</li> @endif
            @foreach($group as $item)
                <li>
                    @if(is_string($item))
                        <a
                            href="{{ route('admin.type.index', $resolver->parseType($item) ) }}"
                            class="{{ Str::contains(request()->url(), $resolver->parseType($item)) ? 'active' : '' }}"
                        >
                            @if(($icon = (new $item)->adminIcon) !== null)
                                <i data-lucide="{{ $icon }}" class="mr-2"></i>
                            @endif

                            {{ Str::plural(class_basename($item)) }}
                        </a>
                    @else
                        <a
                            href="{{ route($item['name']) }}"
                            class="{{ Route::is("{$item['name']}.*") || Route::is($item['name']) ? 'active' : '' }}"
                        >
                            @if(isset($item['options']['icon']))
                                <i data-lucide="{{ $item['options']['icon'] }}" class="mr-2"></i>
                            @endif

                            {{ $item['alias'] }}
                        </a>
                    @endif
                </li>
            @endforeach
        @endforeach

        @if ($count === 0)
            <li class="text-center blank">
                No tables found.
            </li>
        @endif
    </ul>
</aside>
