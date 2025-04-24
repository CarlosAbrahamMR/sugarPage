@extends('layouts.app')
@section('styles')

    <link rel="stylesheet" type="text/css" href="{{asset('css/mvp.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/swipebox.css') }}">
    <link href="https://vjs.zencdn.net/8.0.4/video-js.css" rel="stylesheet" />

    <style>
        .mvp-player{
            font-family: Arial, Helvetica, sans-serif;
            max-width: 1220px;
        }
        .popup {
            position:fixed;
            top:0px;
            left:0px;
            background: rgba(0,0,0,0.8);
            z-index: 99999;
            width:100%;
            height:100%;
            display:none;
        }

        .popup-content {
            width: 700px;
            margin: 0 auto;
            box-sizing: border-box;
            padding: 40px;
            margin-top: 100px;
            box-shadow: 0px 2px 6px rgba(0,0,0,1);
            border-radius: 3px;
            background: #fff;
            position: relative;
        }

        .close-button {
            width: 25px;
            height: 25px;
            position: absolute;
            top: -10px;
            right: -10px;
            border-radius: 20px;
            background: rgba(0,0,0,0.8);
            font-size: 20px;
            text-align: center;
            color: #fff;
            text-decoration:none;
        }

        .close-button:hover {
            background: rgba(0,0,0,1);
        }

        .imgBlock:hover {
            background: rgba(0,0,0,0.8);
            z-index: 99999;
            width:100%;
            height:100%;
        }
</style>
@endsection
@section('content')
<div class="st-pusher" id="content">
    <div class="st-content" id="app">
        <div class="st-content-inner">
            <div class="container-fluid">
                <div class="cover profile" id="coverProfile">
                    <div class="wrapper">
                        <div class="image" style="padding-right: 0px;">
                            <img src="{{$creador->path_imagen_portada ? url('storage'.$creador->path_imagen_portada) : asset('dist/themes/social-1/images/profile-cover.jpg')}}" class="imgCover" />
                        </div>
                    </div>
                    <div class="cover-info">
                        <div class="avatar">
                            <img src="{{$creador->path_imagen_perfil ? url('storage'.$creador->path_imagen_perfil) : asset('images/user-profile.png')}}" alt="people" />
                        </div>
                        <div class="name"><a href="#">{{ucwords($creador->name)}}</a></div>
                        <form class="form-horizontal" role="form" id="form_suscribete" action="{{route('crear.suscripcion')}}" method="POST">
                            <ul class="cover-nav">
                                <li :class="publications">
                                    <a @click="obtenerContenido(0)" href="javascript:void(0)"><i class="fa fa-fw icon-ship-wheel"></i> Timeline</a>
                                </li>
                                @if($suscrito)
                                    <li :class="contenido">
                                        <a @click="obtenerContenido(1)" href="javascript:void(0)"><i class="fa fa-file-picture-o"></i> Contenido</a>
                                    </li>
                                    @if(count($productos) > 0)
                                        <li>
                                            <a href="/products/{{$creador->username}}"><i class="fa fa-shopping-cart"></i> Productos</a>
                                        </li>
                                    @endif
{{--                                    <li :class="multimedia">--}}
{{--                                        <a @click="actualizacionPagina()"  href="javascript:void(0)"><i class="fa fa-file-video-o"></i> Multimedia</a>--}}
{{--                                    </li>--}}
                                @endif
                                <li :class="about">
                                    <a @click="mostrarDatosPerfil()" href="javascript:void(0)"><i class="fa fa-fw icon-user-1"></i> About</a>
                                </li>
                                @if(!$suscrito)
                                    @foreach($arreglo_planes as $plan)
                                        @if($plan['monto'] == 0)
                                            <li><a id="submit" href="javascript:{}" @click="submitForm({{$plan['monto']}})"><i class="fa fa-fw fa-users"></i> Suscribete Gratis</a></li>
                                            <input type="hidden" class="form-control" name="tipo" id="exampleInputFirstName" value="0">
                                            <input type="hidden" class="form-control" name="precio" id="exampleInputLastName" value='price_0'>
                                        @else
                                            <li><a id="submit" href="javascript:{}" @click="submitForm({{$plan['monto']}})"><i class="fa fa-fw fa-users"></i>  {{$plan['monto']}} USD/{{$plan['interval']}}</a></li>
                                            <input type="hidden" class="form-control" name="tipo" id="exampleInputFirstName" value="1">
                                            <input type="hidden" class="form-control" name="precio" id="exampleInputLastName" value='{{$plan['id_precio']}}'>
                                        @endif

                                        {{ csrf_field() }}
                                        <input type="hidden" class="form-control" name="creador" id="exampleInputFirstName" value="{{$creador->id}}">
                                        <input type="hidden" class="form-control" name="plan" id="exampleInputLastName" value='{{$plan['id_plan']}}'>

                                    @endforeach
                                @endif
                            </ul>
                            </form>
                    </div>
                </div>
                <v-overlay :value="overlay">
                    <v-progress-circular
                        indeterminate
                        size="64"
                    ></v-progress-circular>
                </v-overlay>
                <div class="timeline row" v-if="about == 'active'">
                    <div class="media media-grid media-clearfix-xs">
                        <div class="media-left">
                            <div class="panel panel-default widget-user-1 text-center">
                                <div class="panel-heading">
                                    Bio
                                </div>
                                <div class="panel-body">
                                    <div class="expandable expandable-indicator-white expandable-trigger">
                                        <div class="expandable-content">
                                            @if($creador->DatosPersonales->bio != "")
                                                <p>{{ $creador->DatosPersonales->bio }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 col-lg-4 item">
                        <div class="timeline-block">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <ul class="list-unstyled profile-about margin-none">
                                        <li class="padding-v-5">
                                            <div class="row">
                                                <div class="col-sm-4"><span
                                                        class="text-muted">{{__('traducciones.username')}}</span>
                                                </div>
                                                <div class="col-sm-8">{{$creador->username}}</div>
                                            </div>
                                        </li>
                                        <li class="padding-v-5">
                                            <div class="row">
                                                <div class="col-sm-4"><span
                                                        class="text-muted">{{__('traducciones.orientacion_sexual')}}</span>
                                                </div>
                                                <div class="col-sm-8">{{ $creador->DatosPersonales->sexo }}</div>
                                            </div>
                                        </li>
                                        <li class="padding-v-5">
                                            <div class="row">
                                                <div class="col-sm-4"><span
                                                        class="text-muted">{{__('traducciones.hair_color')}}</span>
                                                </div>
                                                <div class="col-sm-8">{{ $creador->DatosPersonales->color_cabello }}</div>
                                            </div>
                                        </li>
                                        <li class="padding-v-5">
                                            <div class="row">
                                                <div class="col-sm-4"><span
                                                        class="text-muted">{{__('traducciones.skin_color')}}</span>
                                                </div>
                                                <div class="col-sm-8">{{ $creador->DatosPersonales->color_piel }}</div>
                                            </div>
                                        </li>
                                        <li class="padding-v-5">
                                            <div class="row">
                                                <div class="col-sm-4"><span
                                                        class="text-muted">{{__('traducciones.height')}}</span>
                                                </div>
                                                <div class="col-sm-8">{{ $creador->DatosPersonales->estatura }}</div>
                                            </div>
                                        </li>
                                        <li class="padding-v-5">
                                            <div class="row">
                                                <div class="col-sm-4"><span
                                                        class="text-muted">{{__('traducciones.complexion')}}</span>
                                                </div>
                                                <div class="col-sm-8">{{ $creador->DatosPersonales->complexion }}</div>
                                            </div>
                                        </li>
                                        <li class="padding-v-5">
                                            <div class="row">
                                                <div class="col-sm-4"><span
                                                        class="text-muted">{{__('traducciones.age')}}</span>
                                                </div>
                                                <div class="col-sm-8">{{ $creador->DatosPersonales->edad }}</div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="timeline row" data-toggle="isotope" v-if="multimedia == ''">
                    <template v-for="(publicacion, index) in publicaciones">
                        <div class="col-xs-12 col-md-6 col-lg-4 item">
                            <div class="timeline-block">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="media">
                                            <div class="media-left">
                                                <v-img max-width="50" :src="publicacion.imgPerfil" class="media-object"></v-img>
                                            </div>
                                            <div class="media-body">
                                                <a class="pull-right text-muted">
                                                    <i v-if="publicacion.tipo == 0" class="icon-compose fa fa-2x" title="{{__("traducciones.publicacion")}}"></i>
                                                    <i v-if="publicacion.tipo == 1" class="icon-picture fa fa-2x" title="{{__("traducciones.content")}}"></i>
                                                </a>
                                                <a href="">@{{publicacion.nameUserPublicacion}}</a>
                                                <span :title="publicacion.date2">@{{publicacion.date}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <p>@{{publicacion.descripcion}}</p>

                                        <div v-if="publicacion.arrImages.length == 1">
                                            <img v-if="!publicacion.arrImages[0].includes('/videos/')" :src="publicacion.arrImages" class="img-responsive"/>
                                            <video
                                                v-else
                                                id="my-video"
                                                class="video-js"
                                                controls
                                                preload="auto"
                                                width="300"
                                                height="264"
                                                data-setup="{}"
                                            >
                                                <source :src="publicacion.arrImages" type="video/mp4" />
                                                {{--                            <source src="MY_VIDEO.webm" type="video/webm" />--}}
                                                <p class="vjs-no-js">
                                                    To view this video please enable JavaScript, and consider upgrading to a
                                                    web browser that
                                                    <a href="https://videojs.com/html5-video-support/" target="_blank"
                                                    >supports HTML5 video</a
                                                    >
                                                </p>
                                            </video>
                                        </div>

                                        <div v-if="publicacion.arrImages.length > 1" class="col-md-12">
                                            <a v-for="image in publicacion.arrImages" :href="image" class="swipebox col-md-3">
                                                <img v-if="!image.includes('/videos/')" :src="image" width="90" class="slider-item img-responsive"/>
                                                <video
                                                    v-else
                                                    id="my-video"
                                                    class="video-js"
                                                    controls
                                                    preload="auto"
                                                    data-setup='{"fluid": true}'
                                                >
                                                    <source :src="image" type="video/mp4" />
                                                    {{--                            <source src="MY_VIDEO.webm" type="video/webm" />--}}
                                                    <p class="vjs-no-js">
                                                        To view this video please enable JavaScript, and consider upgrading to a
                                                        web browser that
                                                        <a href="https://videojs.com/html5-video-support/" target="_blank"
                                                        >supports HTML5 video</a
                                                        >
                                                    </p>
                                                </video>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="view-all-comments">
                                        <a class="open-button" popup-open="popup-1" href="#" @click='listadoComentarios(publicacion.id)'>
                                            <i class="fa fa-comments-o"></i> {{__('traducciones.ver_todos')}}
                                        </a>
                                        <span>@{{ publicacion.cantidadComentarios }} {{__('traducciones.comentarios')}}</span>
                                        <a href="javascript:void(0)" v-if="publicacion.precio > 0" style="float: right;" @click="comprarPublicacion(publicacion.id, publicacion.precio)">
                                            <i class="fa fa-money"></i> {{__('traducciones.buy')}}</a>
                                    </div>

                                    <ul class="comments">
                                        <li class="media" v-if="publicacion.cantidadComentarios > 0">
                                            <div class="media-left">
                                                <a href="">
                                                    <img :src="publicacion.comentarios.profileImage" class="media-object" width="60" alt="photo"/>
                                                </a>
                                            </div>
                                            <div class="media-body">
                                                <a href="" class="comment-author pull-left">@{{ publicacion.comentarios.user.name }}</a><span>@{{ publicacion.comentarios.publicacion.descripcion  }}</span>
                                                <div class="comment-date" :title="publicacion.comentarios.date2">@{{ publicacion.comentarios.date }}</div>
                                            </div>
                                        </li>
                                        <li class="comment-form">
                                            <div class="input-group" v-if="publicacion.precio == 0">
                                                <input type="text" class="form-control" :id="'comment'+publicacion.id"/>
                                                <span class="input-group-btn"><button class="btn btn-default" @click='saveComment(publicacion.id)'>{{__('traducciones.comentar')}}</button></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </template>
                    @include("publicaciones.modalComentarios")
                </div>

                <div class="timeline row" v-if="multimedia == 'active'">
                    <div class="col-md-12 item">
                        <div class="timeline-block">
                            <div class="panel panel-default">
                                <div class="panel-heading"></div>
                                <div class="panel-body">
                                    <div id="wrapper"></div>
                                    <div id="mvp-playlist-list">
                                        <div class="playlist-video" id = "mvpVideos">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://vjs.zencdn.net/8.0.4/video.min.js"></script>

    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data() {
                return {
                    publicaciones : [],
                    videos : [],
                    tipo:0,
                    publications: "",
                    about: "",
                    multimedia: "",
                    contenido:"",
                    overlay:false
                }
            },
            mounted() {
                if ("{{$multimedia}}") {
                    this.obtenerVideos();
                    this.multimedia = "active";
                } else {
                    this.obtenerContenido(0);
                }
            },
            methods: {
                saveComment(id) {
                    let idComment = document.getElementById("comment"+id).value;
                    axios.post(`/guardar/comentario`,{
                        publicacion: id,
                        texto: idComment
                    })
                    .then(response => {
                        if (response.data.estatus) {
                            swal({
                                title: "¡{{__('traducciones.exito')}}!",
                                text: "{{__('traducciones.comment_saved')}}",
                                icon: "success",
                                button: "OK!",
                            });
                            setTimeout(function(){
                                window.location.reload();
                            }, 2500);
                        }
                    }).catch(error => {
                        console.log(error);
                    });
                },
                listadoComentarios(id) {
                    axios.post(`/buscar/publicacion`, {
                        publicacion: id
                    })
                    .then(response => {
                        mostrarDatos(response.data.data);
                    }).catch(error => {
                        console.log(error);
                    });
                },
                obtenerContenido(id){
                    this.publicaciones = [];
                    this.videos = [];
                    this.multimedia = "";
                    let username = "{{ $creador->username }}";
                    axios.post(`/obtener/publicaciones/contenido`,{
                        username: username,
                        tipo: id
                    })
                    .then(response => {
                        if (response.data.estatus) {
                            this.publications = response.data.publications;
                            this.contenido = response.data.contenido;
                            this.about = "";
                            this.publicaciones = response.data.data;
                            inicializarSlide();
                        }
                    }).catch(error => {
                        console.log(error);
                    });
                },
                mostrarDatosPerfil(){
                    this.publicaciones = [];
                    this.publications = "";
                    this.contenido = "";
                    this.videos = [];
                    this.multimedia = "";
                    this.about = "active";
                },
                comprarPublicacion(id, precio) {
                    swal({
                        title: "Confirmacion",
                        text: "¿Desea comprar este contenido por $" + precio + " USD?'",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((comprar) => {
                        if (comprar) {
                            axios.post(`/buy/publication`,{
                                id: id
                            })
                            .then(response => {
                                if (response.data.estatus) {
                                    swal("Compra realizada con exito!", {
                                        icon: "success",
                                    });
                                    setTimeout(function(){
                                        window.location.reload();
                                    }, 2000);
                                } else {
                                    swal("¡No se pudo completar la compra!", {
                                        icon: "error",
                                    });
                                }
                            }).catch(error => {
                                swal("¡No se pudo completar la compra!", {
                                    icon: "error",
                                });
                            });
                        }
                    });
                },
                obtenerVideos() {
                    let username = "{{ $creador->username }}";
                    axios.post(`/obtener/videos`,{
                        username: username
                    })
                    .then(response => {
                        if (response.data.estatus) {
                            mostrarVideos(response.data.data);
                        }
                    }).catch(error => {
                        console.log(error);
                    });
                },
                actualizacionPagina() {
                    let username = "{{ $creador->username }}";
                    var url = "{{ route('userByUsername', ['username' => 'replaceusername']) }}";
                    window.location.href = url.replace('replaceusername', username)+'?mult';
                },
                submitForm(precio){
                    console.log(precio)
                    swal({
                        title: "Confirmacion",
                        text: "¿Desea suscribirte por $" + precio + " USD?'",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((comprar) => {
                        if (comprar) {
                            this.overlay = true
                            document.getElementById('form_suscribete').submit();
                            return false;
                        }
                    });

                }
            },
            computed: {}
        })

        function mostrarDatos(data) {
            let view = "";
            for(var i = 0; i < data.length; i++){
                view = view+'<li class="media"><div class="media-left"><a href=""><img style="width:60px" src="'+data[i]['imagen_perfil']+'" class="media-object" alt="people"></a>'+
                    '</div><div class="media-body"><a href="" class="comment-author pull-left">'+data[i]['username']+'</a><span>'+data[i]['descripcion']+'</span><div class="comment-date">Hace '+data[i]['date']+'</div>'+
                    '</div></li>';
            }
            document.getElementById('listComments').innerHTML = view;

            $('[popup-name="popup-1"]').fadeIn(300);
        }

        function inicializarSlide() {
            $(function() {
                $('.swipebox').swipebox();
            });
        }
    </script>

    <script type="text/javascript" src="{{ asset('js/new.js') }}"></script>
    <script type="text/javascript">

        if ("{{$multimedia}}") {
            document.addEventListener("DOMContentLoaded", function(event) {
                var settings = {
                    sourcePath:"",
                    instanceName:"player1",
                    activePlaylist:".playlist-video",
                    activeItem:-1,
                    volume:0.5,
                    autoPlay:false,
                    randomPlay:false,
                    loopingOn:true,
                    mediaEndAction:"next",
                    aspectRatio:1,
                    youtubeAppId:"",
                    playlistOpened:false,
                    useKeyboardNavigationForPlayback:true,
                    rightClickContextMenu:'custom',
                    elementsVisibilityArr: [
                        {
                            width: 400,
                            elements: [ "play", "rewind", "seekbar", "fullscreen", "info", "volume"]
                        }
                    ],
                    skin:'pollux',
                    playlistPosition:'wall',
                    gridType: 'grid1',
                    playlistStyle:'gdbt',
                };
                fetch('{{asset('_skin/pollux.txt')}}')
                    .then(response => response.text())
                    .then(content => {
                        var wrapper = document.getElementById("wrapper")
                        wrapper.innerHTML = content;
                        player = new mvp(wrapper, settings);
                    })
            });
        }

        function mostrarVideos(data) {
            let view = "";
            for(var i = 0; i < data.length; i++){
                var years = '[{"quality": "default", "mp4": "' + data[i]['arrVideos'][0] + '"}]';
                view = view + "<div class='mvp-playlist-item' data-path='" + years.toString() + "' data-type='video' data-title='" + data[i]["descripcion"] +
                    " by " + data[i]["nameUserPublicacion"] +"(" + data[i]["username"] + ") ' " +
                    'data-thumb="{{ asset("images/boton-de-play.png") }}"></div>';
            }
            $( "#mvpVideos" ).append(view);

        }

    </script>
@endsection
