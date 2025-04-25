<!doctype html>
<html class="st-layout ls-top-navbar ls-bottom-footer show-sidebar sidebar-l2" lang="en" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>{{ config('app.name', 'MySugarFan') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJ+...g==" crossorigin="anonymous"></script>
    <script src="{{ asset('js/app.js') }}"></script>

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
</head>
<body>
    <div class="st-container" id="app">
        @include('layouts.nav')
        @include('layouts.menu')

        @yield('content')
    <!-- /st-pusher -->

    <!-- Footer -->
    <footer class="footer">
      <strong>MySugarFan</strong>&copy; Copyright 2022
    </footer>
    <!-- // Footer -->

  </div>
    <!-- Scripts -->
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJ+Y7DyKtLW7SmkC2utPHsGtvWybwO5v3YwE="
        crossorigin="anonymous"></script>
        
{{--    <script src="{{ asset('js/admin.js')}}"></script>--}}
    @yield('scripts')
    <script src="{{ asset('dist/themes/social-1/js/vendor/all.js')}}"></script>
    <script src="{{ asset('dist/themes/social-1/js/app/app.js')}}"></script>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <script src="{{ asset('js/jquery.swipebox.js')}}"></script>
    <script src="{{ asset('js/notifications.js')}}"></script>
    <script type="text/javascript">
        $("#buttonSubmit").click(function (e) {
            // newButton_Click($(this),e)
            showLoading()
        })

        function showLoading() {
            document.querySelector('#loading').classList.add('loading');
            document.querySelector('#loading-content').classList.add('loading-content');
        }

        function hideLoading() {
            document.querySelector('#loading').classList.remove('loading');
            document.querySelector('#loading-content').classList.remove('loading-content');
        }
    </script>
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
