@php
    $name = Str::plural(class_basename($type));
    $instance = new $type;
    $count = $instance->count();
@endphp

@extends(config('seamless-admin.layout'))

@section('title', "Manage $name")

@section('header')
    @saSafeVite('src/resources/assets/js/type-index.js')
@endsection

@section('content')
    <div class="container mx-auto px-4 py-2" id="app">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl sm:text-4xl font-bold">
                    Manage {{ $name }}
                </h2>
                <div>{{ $count }} {{ Str::plural('item', $count) }} found</div>
            </div>

            @if($instance->adminCanAccessCreate())
                <sa-button as-child>
                    <a href="{{ route('admin.type.create', request()->type) }}">
                        <i data-lucide="plus" class="size-4"></i>
                        Create
                    </a>
                </sa-button>
            @endif
        </div>

        <div>
            <type-index
                data-fetch-url="{{ route('api.admin.type.type_index_data', request()->type) }}"
                key-name="{{ $instance->getKeyName() }}"
                v-bind:fillable="{!! str_replace('"', '\'', json_encode($instance->adminIndexFields())) !!}"
                v-bind:can-edit="{{ $instance->adminCanAccessEdit() }}"
                v-bind:can-delete="{{ $instance->adminCanAccessDelete() }}"
                v-bind:routes="{
                    'show': '{{ route('admin.type.show', [request()->type, '%key%']) }}',
                    'edit': '{{ route('admin.type.edit', [request()->type, '%key%']) }}',
                    'delete': '{{ route('admin.type.delete', [request()->type]) }}'
                }"
            >Loading...</type-index>
        </div>
    </div>
@endsection
