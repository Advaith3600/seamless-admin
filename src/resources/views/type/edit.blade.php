<?php
    $resolver = app('modelResolver');
    $name = class_basename($type);
?>

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
                    <div class="flex flex-col">
                        <label
                            for="{{ $column->Field }}"
                            class="mb-1 font-semibold"
                        >
                            {{ str($column->Field)->ucfirst() }}
                        </label>

                        <input
                            type="text"
                            id="{{ $column->Field }}"
                            name="{{ $column->Field }}"
                            class="input"
                            placeholder="{{ $column->Type }}"
                            value="{{ old($column->Field) ?? $data[$column->Field] }}"
                        />
                    </div>
                @endforeach
            </div>

            <div class="flex gap-2 mt-4 justify-end">
                <a href="{{ route('admin.type.index', request()->type) }}" class="btn grey">Cancel</a>
                <button class="btn" type="submit">Edit</button>
            </div>
        </form>
    </div>
@endsection
