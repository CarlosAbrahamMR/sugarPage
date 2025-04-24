@extends('layouts.login')
@section('content')
    <div class="lock-container">
        <h1>{{__('Verify Your Email Address')}}</h1>
        <div class="panel panel-default text-center" id="app">
            <img src="{{ asset('images/user-profile.png') }}" style="width: 50% !important;" class="img-circle" alt="">
            <div class="panel-body">
                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Please check your email for a verification link.') }}
                        <br>
                    {{ __('If you did not receive the email') }},
                    <form class="d-inline" method="POST" id="formverifi" action="{{ route('verification.resend') }}">
                        @csrf
                        <input type="text" name="name" hidden value="{{$name}}">
                        <input type="text" name="email" hidden value="{{$email}}">
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
