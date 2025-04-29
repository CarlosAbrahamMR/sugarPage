<!DOCTYPE html>
<html class="hide-sidebar ls-bottom-footer" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>MySugarFan</title>

    <!-- CSS primero -->
    <link href="{{ asset('dist/themes/social-1/css/vendor/all.css')}}" rel="stylesheet">
    <link href="{{ asset('dist/themes/social-1/css/app/app.css')}}" rel="stylesheet">

    <!-- Estilos inline -->
    <style type="text/css">
        .loading {
            z-index: 20;
            position: absolute;
            top: 0;
            left: -5px;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }
        .loading-content {
            position: absolute;
            border: 16px solid #f3f3f3;
            border-top: 16px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            top: 40%;
            left: 48%;
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    @yield('styles')

    <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="/js/plugins/jquery.breakpoints.min.js"></script>

    <!-- Librerías principales -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1/daterangepicker.min.js"></script>

<!-- Bootstrap 4.6 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://www.google.com/recaptcha/api.js?render=6LeXnSgrAAAAAI0KWPQp7jFAVLOi3XuSzZ8qi6DC"></script>


    <!-- Vue, Vuetify, Axios -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>

<body class="login">
<div id="content">
    <div class="container-fluid">
        @yield('content')
    </div>
</div>

<div id="loading">
    <div id="loading-content"></div>
</div>

<!-- Footer -->
<footer class="footer" style="z-index:-10">
    <strong>MySugarFan</strong>&copy; Copyright 2022
</footer>

<!-- Configuración de colores y skins -->
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
            "default": { "primary-color": "#16ae9f" },
            "orange": { "primary-color": "#e74c3c" },
            "blue": { "primary-color": "#4687ce" },
            "purple": { "primary-color": "#af86b9" },
            "brown": { "primary-color": "#c3a961" }
        }
    };
</script>

<!-- reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js?render=6LeXnSgrAAAAAI0KWPQp7jFAVLOi3XuSzZ8qi6DC"></script>

<!-- Scripts propios -->
<script src="{{ asset('dist/themes/social-1/js/vendor/all.js')}}"></script>
<script src="{{ asset('dist/themes/social-1/js/app/app.js')}}"></script>

@yield('scripts')

<!-- Funciones para mostrar loading -->
<script type="text/javascript">
    $("#buttonSubmit").click(function (e) {
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
</script>
</body>
</html>
