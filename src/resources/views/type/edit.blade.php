@php $name = class_basename($type); @endphp

@extends(config('seamless-admin.layout'))

@section('title', "Edit $name")

@section('header')
    @saSafeVite('src/resources/assets/js/foreign-selection.js')
@endsection

@section('content')
    <div class="container mx-auto px-4 py-2" id="app">
        <h2 class="text-3xl sm:text-4xl font-bold">
            Edit {{ $name }}
        </h2>

        <form action="{{ route('admin.type.update', [request()->type, request()->id]) }}" method="post" class="mt-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($columns as $column)
                    @include('seamless::partials.field-renderer')
                @endforeach
            </div>

            <div class="flex gap-2 mt-4 justify-end">
                <sa-button as-child variant="outline">
                    <a href="{{ route('admin.type.index', request()->type) }}">
                        <i data-lucide="arrow-left" class="size-4"></i>
                        Cancel
                    </a>
                </sa-button>
                <sa-button>
                    <i data-lucide="edit" class="size-4"></i>
                    Update
                </sa-button>
            </div>
        </form>
    </div>
@endsection
