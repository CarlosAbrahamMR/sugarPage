<!doctype html>
<html class="st-layout ls-top-navbar ls-bottom-footer show-sidebar sidebar-l2" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>{{ config('app.name', 'MySugarFan') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- jQuery DEBE ir antes de todo lo que lo use -->
     <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="{{ asset('js/plugins/jquery.breakpoints.min.js') }}"></script>

    <!-- Primero JQuery y plugins -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1/daterangepicker.min.js"></script>

<!-- Bootstrap 4.6 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>




    <!-- Laravel Mix app.js si es necesario aquí -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts & Styles -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <link href="{{ asset('dist/themes/social-1/css/vendor/all.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/themes/social-1/css/app/app.css') }}" rel="stylesheet">
    @yield('styles')

    <!-- Vue, Vuetify y Axios -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <style>
        .v-badge__wrapper span {
            background: red !important;
        }
    </style>
</head>
<body>
<<script src="https://code.jquery.com/jquery-3.7.1.min.js"
        crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/gh/XhmikosR/jquery-breakpoints@0.0.5/jquery.breakpoints.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

    <div class="st-container" id="app">
        @include('layouts.nav')
        @include('layouts.menu')

        @yield('content')

        <!-- Footer -->
        <footer class="footer">
            <strong>MySugarFan</strong>&copy; Copyright 2022
        </footer>
    </div>

    <!-- Scripts que dependen de jQuery -->
    <script src="{{ asset('dist/themes/social-1/js/vendor/all.js') }}"></script>
    <script src="{{ asset('dist/themes/social-1/js/app/app.js') }}"></script>
    <script src="{{ asset('js/jquery.swipebox.js') }}"></script>
    <script src="{{ asset('js/notifications.js') }}"></script>
    <script src="{{ asset('js/register.js') }}"></script>

    <!-- Polyfills -->
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <!-- Scripts de la vista -->
    @yield('scripts')

    <!-- Scripts inline -->
    <script>
        $(function () {
            $("#buttonSubmit").click(function () {
                showLoading();
            });

            function showLoading() {
                document.querySelector('#loading').classList.add('loading');
                document.querySelector('#loading-content').classList.add('loading-content');
            }

            function hideLoading() {
                document.querySelector('#loading').classList.remove('loading');
                document.querySelector('#loading-content').classList.remove('loading-content');
            }

            axios.get('/get-follow').then((resp) => {
                const data = document.querySelector("#infoMen");
                let newElement = "<span>" + resp.data.data.suscripciones + "</span>";
                newElement += "<span>-</span>";
                newElement += "<span>" + resp.data.data.seguidores + "</span>";
                data.innerHTML = newElement;
            });
        });
    </script>
</body>
</html>
