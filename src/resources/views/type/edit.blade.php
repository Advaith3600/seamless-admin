@php $name = class_basename($type); @endphp

@extends('seamless::layout')

@section('title', "Edit $name")

@section('content')
    <div class="container px-4 py-2">
        <h2 class="text-2xl font-semibold">
            Edit {{ $name }}
        </h2>

        <form action="{{ route('admin.type.update', [request()->type, request()->id]) }}" method="post" class="mt-4">
            @csrf
            @method('PUT')

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
                    <i data-feather="edit"></i>
                    Edit
                </button>
            </div>
        </form>
    </div>
@endsection
