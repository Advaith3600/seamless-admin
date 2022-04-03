<?php
    $resolver = app('modelResolver');
    $name = str(class_basename($type))->plural();
?>

@extends('seamless::layout')

@section('title', "Manage $name")

@section('content')
    <div class="container px-4 py-2">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold">
                Manage {{ $name }}
            </h2>

            <a class="btn" href="{{ route('admin.type.create', request()->type) }}">
                <i data-feather="plus"></i>
                Create
            </a>
        </div>

        <table class="mt-4">
            <thead>
                <tr>
                    @foreach($fillable as $f)
                        <th>{{ $f }}</th>
                    @endforeach
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $row)
                    <tr data-link="{{ route('admin.type.show', [request()->type, $row->id]) }}">
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
                                <a href="" class="btn red small">
                                    <i data-feather="trash-2"></i>
                                    Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($fillable) + 1 }}" class="text-center">No data found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="flex justify-between items-center">
            <div></div>

            <div>
                {{ $data->links() }}
            </div>
        </div>
    </div>
@endsection
