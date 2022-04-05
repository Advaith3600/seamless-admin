@php
    $name = class_basename($type);
    $instance = new $type;
@endphp

@extends('seamless::layout')

@section('title', "$name")

@section('content')
    <div class="container px-4 py-2">
        <h2 class="text-2xl font-semibold">
            {{ $name }}
        </h2>

        <div class="mt-4 grid grid-cols-1 gap-1">
            @foreach($data->toArray() as $key => $column)
                <div class="flex">
                    <div class="opacity-60 mr-2">{{ str($key)->ucfirst() }}:</div>
                    <div>{{ $column }}</div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 flex justify-end items-center gap-2">
            <a href="{{ route('admin.type.index', request()->type) }}" class="btn grey">
                <i data-feather="x"></i>
                Go Back
            </a>

            @if($instance->adminCanAccessEdit())
                <a href="{{ route('admin.type.edit', [request()->type, request()->id]) }}" class="btn yellow">
                    <i data-feather="edit"></i>
                    Edit
                </a>
            @endif

            @if($instance->adminCanAccessDelete())
                <a href="{{ route('admin.type.delete', [request()->type, 'ids' => [request()->id]]) }}" class="btn red">
                    <i data-feather="trash-2"></i>
                    Delete
                </a>
            @endif
        </div>
    </div>
@endsection
