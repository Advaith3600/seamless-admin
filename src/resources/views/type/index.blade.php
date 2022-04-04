<?php
    $resolver = app('modelResolver');
    $name = str(class_basename($type))->plural();
?>

@extends('seamless::layout')

@section('title', "Manage $name")

@section('header')
    <script src="{{ asset('seamless-admin/js/type-index.js') }}" defer></script>
@endsection

@section('content')
    <div class="container px-4 py-2" id="app">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold">
                Manage {{ $name }}
            </h2>

            <a class="btn" href="{{ route('admin.type.create', request()->type) }}">
                <i data-feather="plus"></i>
                Create
            </a>
        </div>

        <table class="my-4">
            <thead>
                <tr>
                    <th>
                        <input
                            type="checkbox"
                            v-bind:checked="selected.size === {{ count($data) }}"
                            v-on:click="checkAll({{ $data->pluck('id') }})"
                        />
                    </th>
                    @foreach($fillable as $f)
                        <th>{{ $f }}</th>
                    @endforeach
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $row)
                    <tr data-link="{{ route('admin.type.show', [request()->type, $row->id]) }}">
                        <td>
                            <input
                                type="checkbox"
                                v-bind:checked="selected.has({{ $row->id }})"
                                v-on:change="checkIndividual({{ $row->id }})"
                            />
                        </td>
                        @foreach($fillable as $f)
                            <td>{{ $row[$f] }}</td>
                        @endforeach
                        <td>
                            <div class="flex gap-2">
                                <a
                                    href="{{ route('admin.type.edit', [request()->type, $row->id]) }}"
                                    class="btn yellow small"
                                >
                                    <i data-feather="edit"></i>
                                    Edit
                                </a>
                                <a
                                    href="{{ route('admin.type.delete', [request()->type, 'ids' => [$row->id]]) }}"
                                    class="btn red small"
                                >
                                    <i data-feather="trash-2"></i>
                                    Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($fillable) + 2 }}" class="text-center">No data found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $data->links('pagination::tailwind') }}
    </div>
@endsection
