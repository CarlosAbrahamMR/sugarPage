@extends('layouts.login')
@section('content')
        <div class="lock-container">
            <h1>Recover Password</h1>
            <div class="panel panel-default text-center">
                <div id="app" class="panel-body">
                    <div class="panel-body">
                        <div class="form-group col-md-12">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @elseif (session('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif
                        </div>
                        <form class="form-horizontal" role="form" id="form" action="{{ route('password.email') }}" method="POST">
                            {{ csrf_field() }}

                            <input class="form-control" id="email" type="email" placeholder="E-mail" name="email" required autofocus>
                            <a class="forgot-password" href="/">
                                Back Login
                            </a>
                            <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection
