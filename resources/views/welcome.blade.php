@extends('layouts.app')

@section('content')
    <div id="app">
        <main class="py-4">
            <div class="container">
                <div class="card text-primary">
                    <div class="card-header" style="background-color: orange;">
                        {{ __('Inicio') }}
                    </div>
                    <div class="card-body">
                        <a class="btn btn-primary btn-lg" href="{{ route('fan-register') }}">Fan Register</a>
                        <a class="btn btn-primary btn-lg" href="{{ route('sugar-register') }}">Sugar Register</a>
                        <a class="btn btn-primary btn-lg" href="{{ route('login') }}">Login</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
