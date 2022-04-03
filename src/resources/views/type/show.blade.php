<?php
    $resolver = app('modelResolver');
    $name = class_basename($type);
?>

@extends('seamless::layout')

@section('title', "$name")

@section('content')
    <div class="container px-4 py-2">
        <h2 class="text-2xl font-semibold">
            {{ $name }}
        </h2>

        <div class="mt-4 grid grid-cols-2 gap-2">
            @foreach($data->toArray() as $key => $column)
                <div class="flex">
                    <div class="opacity-60 mr-2">{{ str($key)->ucfirst() }}: </div>
                    <div>{{ $column }}</div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
