@php
    $name = class_basename($type);
    $instance = new $type;
@endphp

@extends(config('seamless-admin.layout'))

@section('title', "$name")

@section('header')
    @vite('src/resources/assets/js/app.js', 'seamless-admin')
@endsection

@section('content')
    <div class="container mx-auto px-4 py-2" id="app">
        <h2 class="text-3xl sm:text-4xl font-bold">
            {{ $name }}
        </h2>

        <div class="mt-4 grid grid-cols-1 gap-1">
            @foreach($data->toArray() as $key => $column)
                <div class="flex bg-white rounded-lg shadow-sm border p-2">
                    <div class="opacity-60 mr-2">{{ $key }}:</div>
                    <div>{{ $column }}</div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 flex justify-end items-center gap-2">
            <sa-button as-child variant="secondary">
                <a href="{{ route('admin.type.index', request()->type) }}">
                    <i data-lucide="arrow-left" class="size-4"></i>
                    Go Back
                </a>
            </sa-button>

            @if($instance->adminCanAccessEdit())
                <sa-button as-child variant="outline">
                    <a href="{{ route('admin.type.edit', [request()->type, request()->id]) }}">
                        <i data-lucide="edit" class="size-4"></i>
                        Edit
                    </a>
                </sa-button>
            @endif

            @if($instance->adminCanAccessDelete())
                <sa-button as-child variant="destructive">
                    <a href="{{ route('admin.type.delete', [request()->type, 'ids' => [request()->id]]) }}">
                        <i data-lucide="trash-2" class="size-4"></i>
                        Delete
                    </a>
                </sa-button>
            @endif
        </div>
    </div>
@endsection
