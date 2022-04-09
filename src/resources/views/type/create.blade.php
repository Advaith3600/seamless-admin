@php $name = class_basename($type); @endphp

@extends(config('seamless-admin.layout'))

@section('title', "Create $name")

@section('header')
    <script src="{{ asset('seamless-admin/js/foreign-selection.js') }}" defer></script>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-2" id="app">
        <h2 class="text-2xl font-semibold">
            Create {{ $name }}
        </h2>

        <form action="{{ route('admin.type.store', request()->type) }}" method="post" class="mt-4">
            @csrf

            <div class="grid grid-cols-2 gap-2">
                @foreach($columns as $column)
                    @include('seamless::partials.field-renderer')
                @endforeach
            </div>

            <div class="flex gap-2 mt-4 justify-end">
                <a href="{{ route('admin.type.index', request()->type) }}" class="btn grey">
                    <i data-feather="x"></i>
                    Cancel
                </a>
                <button class="btn" type="submit">
                    <i data-feather="plus"></i>
                    Create
                </button>
            </div>
        </form>
    </div>
@endsection
