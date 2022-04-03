@extends('seamless::layout')

@section('content')
    Hey {{ auth()->user()->email }}!
@endsection
