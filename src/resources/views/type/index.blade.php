@php
    $name = str(class_basename($type))->plural();
    $instance = new $type;
@endphp

@extends(config('seamless-admin.layout'))

@section('title', "Manage $name")

@section('header')
    <script src="{{ asset('seamless-admin/js/type-index.js') }}" defer></script>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-2" id="app">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold">
                Manage {{ $name }}
            </h2>

            @if($instance->adminCanAccessCreate())
                <a class="btn" href="{{ route('admin.type.create', request()->type) }}">
                    <i data-feather="plus"></i>
                    Create
                </a>
            @endif
        </div>

        <div class="flex justify-between items-center mt-4 mb-2">
            <form action="{{ route('admin.type.index', request()->type) }}" method="get" class="flex gap-2"
                  ref="search">
                <div class="search">
                    <input
                        type="text"
                        placeholder="Search in {{ $fillable->join(', ') }}"
                        class="input"
                        name="q"
                        value="{{ request()->q }}"
                    />
                    <div class="icon" v-on:click="$refs.search.submit()">
                        <i data-feather="search"></i>
                    </div>
                </div>

                <select
                    name="perPage"
                    id="perPage"
                    class="input"
                    v-on:change="$refs.search.submit()"
                >
                    @php $values = [5, 10, 20, 50, 100]; @endphp
                    @foreach($values as $value)
                        <option
                            value="{{ $value }}"
                            {{ $value == request()->perPage ? 'selected' : ($value === 10 ? 'selected' : '') }}
                        >
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </form>

            <a
                v-cloak
                v-if="selected.size > 0 && {{ $instance->adminCanAccessDelete() ? 'true' : 'false' }}"
                v-bind:href="'{{ route('admin.type.delete', request()->type) }}?' + massDeleteURI()"
                class="btn red"
            >
                <i data-feather="trash-2"></i>
                Delete
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                <tr>
                    <th>
                        <input
                            type="checkbox"
                            v-bind:checked="selected.size === {{ count($data) }}"
                            v-on:click="checkAll({{ $data->pluck($instance->getKeyName()) }})"
                        />
                    </th>
                    @foreach($fillable as $f)
                        <th>
                            @php
                                if (isset(request()->by, request()->order)) {
                                    if (request()->by === $f) $fill = request()->order === 'asc' ? 'up' : 'down';
                                    else $fill = 'light';
                                }
                            @endphp
                            <div
                                class="flex justify-between items-center sort {{ $fill ?? '' }}"
                                v-on:click="sort('{{ $f }}', '{{ request()->order }}')"
                            >
                                <div>{{ str($f)->ucfirst() }}</div>
                                <div class="flex flex-col">
                                    <i data-feather="chevron-up" class="up" stroke-width="3"></i>
                                    <i data-feather="chevron-down" class="down" stroke-width="3"></i>
                                </div>
                            </div>
                        </th>
                    @endforeach
                    <th></th>
                </tr>
                </thead>

                <tbody>
                @forelse($data as $row)
                    <tr
                        v-bind:class="{ selected: selected.has({{ $row->getKey() }}) }"
                        v-on:click="redirect('{{ route('admin.type.show', [request()->type, $row->getKey()]) }}')"
                    >
                        <td>
                            <input
                                type="checkbox"
                                v-bind:checked="selected.has({{ $row->getKey() }})"
                                v-on:change="checkIndividual({{ $row->getKey() }})"
                                v-on:click.stop
                            />
                        </td>
                        @foreach($fillable as $f)
                            <td>{{ $row[$f] }}</td>
                        @endforeach
                        <td>
                            <div class="flex gap-3">
                                @if($instance->adminCanAccessEdit())
                                    <a
                                        href="{{ route('admin.type.edit', [request()->type, $row->getKey()]) }}"
                                        class="btn yellow small link"
                                        v-on:click.stop
                                    >
                                        <i data-feather="edit"></i>
                                        Edit
                                    </a>
                                @endif
                                @if($instance->adminCanAccessDelete())
                                    <a
                                        href="{{ route('admin.type.delete', [request()->type, 'ids' => [$row->getKey()]]) }}"
                                        class="btn red small link"
                                        v-on:click.stop
                                    >
                                        <i data-feather="trash-2"></i>
                                        Delete
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($fillable) + 2 }}" class="text-center">No data found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination mt-4">
            {{ $data->links('pagination::tailwind') }}
        </div>
    </div>
@endsection
