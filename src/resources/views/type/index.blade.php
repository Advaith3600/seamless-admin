@php
    $name = Str::plural(class_basename($type));
    $instance = new $type;
@endphp

@extends(config('seamless-admin.layout'))

@section('title', "Manage $name")

@section('header')
    @vite('src/resources/assets/js/type-index.js', 'seamless-admin')
@endsection

@section('content')
    <div class="container mx-auto px-4 py-2">
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

        <div id="app">
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
