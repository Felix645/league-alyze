@extends('layouts.example')

@section('content')
    <h2>Welcome to the new Framework!</h2>

    <h3>This is an example view rendered from the ExampleController, it has the following route: {{ route('exampleRoute') }}</h3>

    <br>
    <br>

    @include('templates.example')

    <br>
    <br>

    {{ $share }}
@endsection
