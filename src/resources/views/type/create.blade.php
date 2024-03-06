@php $name = class_basename($type); @endphp

@extends(config('seamless-admin.layout'))

@section('title', "Create $name")

@section('header')
    @vite('src/resources/assets/js/foreign-selection.js', 'seamless-admin')
@endsection

@section('content')
    <div class="container mx-auto px-4 py-2" id="app">
        <h2 class="text-2xl font-semibold">
            Create {{ $name }}
        </h2>

        <form action="{{ route('admin.type.store', request()->type) }}" method="post" class="mt-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($columns as $column)
                    @include('seamless::partials.field-renderer')
                @endforeach
            </div>

            <div class="flex gap-2 mt-4 justify-end">
                <sa-button as-child variant="outline">
                    <a href="{{ route('admin.type.index', request()->type) }}">
                        <i data-lucide="x" class="size-4"></i>
                        Cancel
                    </a>
                </sa-button>
                <sa-button>
                    <i data-lucide="plus" class="size-4"></i>
                    Create
                </sa-button>
            </div>
        </form>
    </div>
@endsection
