@extends('layouts.login')
@section('content')
    <div class="lock-container">
    <script src="https://www.google.com/recaptcha/api.js?render=6LeXnSgrAAAAAI0KWPQp7jFAVLOi3XuSzZ8qi6DC"></script>

        <h1>{{__('traducciones.crear_cuenta')}}</h1>
        <div class="panel panel-default text-center">
            <img src="{{ asset('images/user-profile.png') }}" style="width: 50% !important;" class="img-circle">
            <div id="app" class="panel-body">
                    @if (\Session::has('error'))
                        <div class="alert alert-danger">
                            <div> {{ session()->get('error') }}</div>
                        </div>
                    @endif
                    <form class="form-horizontal" role="form" id="register_form" action="/usuario" method="POST">
                        {{ csrf_field() }}

                        <input class="form-control" id="name" type="text" placeholder="{{__('traducciones.nombre')}}" name="name" required
                               autofocus v-model="name">
                        <input class="form-control" id="email" type="email" placeholder="{{__('traducciones.correo')}}" name="email" required
                               autofocus v-model="email" @change="checkInputs()" @input="validarEmail(email)">
                        <span style="color: red" v-if="emailInvalid">{{__('traducciones.correo_invalido')}}</span>
                        <input class="form-control" id="password" type="password" @input="StrengthChecker(password)" placeholder="{{__('traducciones.contrasenia')}}" name="password"
                               required autofocus v-model="password" @change="checkInputs()">
                        <span style="font-size: 11px">{{__('traducciones.requisitos_password')}}</span>
                        <br>
                        <span id="StrengthDisp" class="badge displayBadge" style="color:white !important;">@{{mensaje}}</span>
                        <div style="text-align: left !important;">
                            <input style="margin-left:5px;" type="checkbox" id="mostrar_contrasena" @click="mostrarPassword()"/>
                            &nbsp;&nbsp;{{__('traducciones.mostrar_contrasenia')}}
                        </div>
                        <a class="forgot-password" href="/login">
                            Do you already have an account? Login
                        </a>
                        <button type="button" id="buttonSubmit" :disabled="disabledButton" @click="verifyRecapcha()" class="btn btn-primary">Submit</button>
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
                    password:'{{ old('password') ?: '' }}',
                    email:'{{ old('email') ?: '' }}',
                    name: '{{ old('name') ?: '' }}',
                    mensaje:'',
                    disabledButton: true,
                    regexEmail: /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g,
                    emailInvalid: false
                }
            },
            mounted() {

            },
            methods: {
                verifyRecapcha() {
                    grecaptcha.execute('6LeXnSgrAAAAAI0KWPQp7jFAVLOi3XuSzZ8qi6DC', {action: 'register'}).then(function(token) {
                        axios.post('/token-recapcha-verify', {response: token}).then(resp=>{
                            if(resp.data.success){
                                document.getElementById("register_form").submit();
                            }
                        })
                    });
                },
                mostrarPassword(){
                    var cambio = document.getElementById("password");
                    if(cambio.type == "password"){
                        cambio.type = "text";
                    }else{
                        cambio.type = "password";
                    }
                },
                inputPassword(password){
                    let strengthBadge = document.getElementById('StrengthDisp')

                    strengthBadge.style.display= 'block'
                    clearTimeout(timeout);

                    //We then call the StrengChecker function as a callback then pass the typed password to it

                    timeout = setTimeout(() => StrengthChecker(password), 500);

                    //Incase a user clears the text, the badge is hidden again

                    if(password.length !== 0){
                        strengthBadge.style.display != 'block'
                    } else{
                        strengthBadge.style.display = 'none'
                    }
                },
                StrengthChecker(PasswordParameter){
                    let strengthBadge = document.getElementById('StrengthDisp')
                    let strongPassword = new RegExp('(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{8,})')
                    let mediumPassword = new RegExp('((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,}))|((?=.*[a-z])(?=.*[A-Z])(?=.*[^A-Za-z0-9])(?=.{8,}))')

                    if(strongPassword.test(PasswordParameter)) {
                        strengthBadge.style.backgroundColor = "green"
                        strengthBadge.textContent = 'Strong'
                        this.disabledButton = !(this.name !== '' && this.email !== '' && !this.emailInvalid)
                    } else if(mediumPassword.test(PasswordParameter)){
                        strengthBadge.style.backgroundColor = 'yellow'
                        strengthBadge.textContent = 'Medium'
                        this.disabledButton = !(this.name !== '' && this.email !== '' && !this.emailInvalid);
                    } else{
                        strengthBadge.style.backgroundColor = 'red'
                        strengthBadge.textContent = 'Weak'
                        this.disabledButton = true
                    }
                },
                checkInputs(){
                    this.disabledButton = !(this.name !== '' && this.email !== '' && this.password !== '' && !this.emailInvalid)
                },
                validarEmail(email){
                    this.emailInvalid = !email.match(this.regexEmail);
                }
            },
            computed: {}
        })
    </script>
@endsection
