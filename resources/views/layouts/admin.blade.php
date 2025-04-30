<!DOCTYPE html>
<html class="st-layout ls-top-navbar ls-bottom-footer show-sidebar sidebar-l2" lang="en"
      lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>{{ config('app.name', 'MySugarFan') }}</title>


<!-- jQuery primero -->
<script src="{{ asset('js/vendor/jquery-3.5.1.min.js') }}"></script>

<script src="{{ asset('js/plugins/jquery.breakpoints.min.js') }}"></script>


<!-- Moment antes que daterangepicker -->
<script src="{{ asset('js/vendor/moment.min.js') }}"></script>

<!-- Plugins que dependen de moment y jQuery -->
<script src="{{ asset('js/vendor/daterangepicker.min.js') }}"></script>
<script src="{{ asset('js/vendor/jquery.nicescroll.min.js') }}"></script>

<!-- Bootstrap si lo usas -->
<script src="{{ asset('js/vendor/bootstrap.bundle.min.js') }}"></script>

<!-- Vue si lo usas -->
<script src="{{ asset('js/vendor/vue.js') }}"></script>

<!-- Tu script -->


    
    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <link href="{{ asset('dist/themes/social-1/css/vendor/all.css')}}" rel="stylesheet">
    <link href="{{ asset('dist/themes/social-1/css/app/app.css')}}" rel="stylesheet">
    @yield('styles')
    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <style>
        .v-badge__wrapper span{
            background: red !important;
        }
    </style>
    <script src="{{ asset('js/app.js') }}"></script>

</head>
<body>
<div class="st-container" id="app">
    @include('layouts.nav')
    @include('layouts.menu')

    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <strong>MySugarFan</strong>&copy; Copyright 2022
    </footer>
    <!-- // Footer -->
</div>
<script>
    var colors = {
        "danger-color": "#e74c3c",
        "success-color": "#81b53e",
        "warning-color": "#f0ad4e",
        "inverse-color": "#2c3e50",
        "info-color": "#2d7cb5",
        "default-color": "#6e7882",
        "default-light-color": "#cfd9db",
        "purple-color": "#9D8AC7",
        "mustard-color": "#d4d171",
        "lightred-color": "#e15258",
        "body-bg": "#f6f6f6"
    };
    var config = {
        theme: "social-1",
        skins: {
            "default": {
                "primary-color": "#16ae9f"
            },
            "orange": {
                "primary-color": "#e74c3c"
            },
            "blue": {
                "primary-color": "#4687ce"
            },
            "purple": {
                "primary-color": "#af86b9"
            },
            "brown": {
                "primary-color": "#c3a961"
            }
        }
    };
</script>
{{--  <script src="{{ asset('js/admin.js')}}"></script>--}}

@yield('scripts')
<script src="{{ asset('dist/themes/social-1/js/vendor/all.js')}}"></script>
<script src="{{ asset('dist/themes/social-1/js/app/app.js')}}"></script>
<script>
    axios.get('/get-follow').then((resp)=>{
        const data = document.querySelector("#infoMen");
        let newElement = "<span>" + resp.data.data.suscripciones + "</span>";
        newElement += "<span>-</span>";
        newElement += "<span>" + resp.data.data.seguidores + "</span>";
        data.innerHTML = newElement
    })
</script>
</body>
</html>
