@extends('layouts.login')
@section('content')
    <div class="lock-container">
        <h1>{{__('traducciones.acceso')}}</h1>
        <div class="panel panel-default text-center" id="app">
            <img src="{{ asset('images/user-profile.png') }}" style="width: 50% !important;" class="img-circle">
            @if (\Session::has('resent'))
                <div class="alert alert-warning" role="alert">
                    {{ __('Please check your email for a verification link.') }}
                </div>
            @endif
            @if (\Session::has('success'))
                <div class="alert alert-success" role="alert">
                    {{ session()->get('success') }}
                </div>
            @endif
            <div class="panel-body">
                <form method="POST" id="login_form" action="{{ route('iniciar-sesion') }}">
                    @csrf
                    <div class="form-group @error('email') has-error margin-none @enderror">
                        <input class="form-control" id="email" type="email" placeholder="{{__('traducciones.correo')}}" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group @error('password') has-error margin-none @enderror">
                        <input class="form-control" id="password" type="password" placeholder="{{__('traducciones.contrasenia')}}" name="password" required>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div style="text-align: left !important;">
                        <input style="margin-left:5px;" type="checkbox" id="mostrar_contrasena" @click="mostrarPassword()"/>
                        &nbsp;&nbsp;{{__('traducciones.mostrar_contrasenia')}}
                    </div>
                    <!-- <button type="button" id="buttonSubmit" @click="verifyRecapcha()" class="btn btn-primary"> -->
                        <button type="button" id="buttonSubmit"  class="btn btn-primary"> 

                        {{ __('traducciones.iniciar_sesion') }}
                        <i class="fa fa-sign-in"></i>
                    </button>
                    <a class="forgot-password" href="{{ route('recover-pasword') }}">
                        {{ __('traducciones.olvidaste_contrasenia') }}
                    </a>
                    <a class="forgot-password" href="{{ route('register') }}">
                        {{ __('traducciones.sin_cuenta') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data() {
                return {

                }
            },
            mounted() {

            },
            methods: {
                verifyRecapcha() {
                    // grecaptcha.execute('6LeXnSgrAAAAAI0KWPQp7jFAVLOi3XuSzZ8qi6DC', {action: 'login'}).then(function(token) {
                    //     axios.post('/token-recapcha-verify', {response: token}).then(resp=>{
                    //         if(resp.data.success){
                                document.getElementById("login_form").submit();
                    //         }
                    //     })
                    // });
                },
                mostrarPassword(){
                    var cambio = document.getElementById("password");
                    if(cambio.type == "password"){
                        cambio.type = "text";
                    }else{
                        cambio.type = "password";
                    }
                }
            },
            computed: {}
        })
    </script>
@endsection
