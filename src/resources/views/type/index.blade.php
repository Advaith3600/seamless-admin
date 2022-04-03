<?php $resolver = app('modelResolver'); ?>

@extends('seamless::layout')

@section('content')
    <div class="container px-4 py-2">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold">
                Manage {{ str(class_basename($type))->plural() }}
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
                @foreach($data as $row)
                    <tr>
                        @foreach($fillable as $f)
                            <td>{{ $row[$f] }}</td>
                        @endforeach
                        <td>
                            <a href="">Edit</a>
                            <a href="">Delete</a>
                        </td>
                    </tr>
                @endforeach
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
