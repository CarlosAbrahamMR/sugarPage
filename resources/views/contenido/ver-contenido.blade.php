@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/swipebox.css') }}">
    <link href="https://vjs.zencdn.net/8.0.4/video-js.css" rel="stylesheet" />
    <style>
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
                                <img src="{{ Auth::user()->path_imagen_portada ? url('storage'.Auth::user()->path_imagen_portada) : asset('dist/themes/social-1/images/profile-cover.jpg')}}" class="imgCover"/>
                                <img src="{{ asset('/dist/themes/social-1/images/profile-cover.jpg')}}" alt="people" class="imgCover" />
                            </div>
                        </div>
                        <div class="cover-info">
                            <div class="avatar">
                                <img src="{{Auth::user()->path_imagen_perfil ? url('storage'.Auth::user()->path_imagen_perfil) : asset('images/user-profile.png')}}" alt="people" />
                            </div>
                            <div class="name"><a href="#">{{ucwords(Auth::user()->name)}}</a></div>
                        </div>
                    </div>

                    <div class="timeline row" data-toggle="isotope">
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
                                                    <a @click="eliminarContenido(publicacion.id)" class="pull-right text-muted" style="cursor: pointer"><i class="icon-trash-can fa fa-2x" title="{{__("traducciones.eliminar")}}"></i></a>
                                                    <a class="pull-right text-muted">
                                                        <i v-if="publicacion.tipo == 0" class="icon-compose fa fa-2x" title="{{__("traducciones.content")}}"></i>
                                                        <i v-if="publicacion.tipo == 1" class="icon-picture fa fa-2x" title="{{__("traducciones.publicacion")}}"></i>
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
                                                    data-setup='{"fluid": true}'
                                                >
                                                    <source :src="publicacion.arrImages" type="video/mp4" />
                                                </video>
                                            </div>

                                            <div v-if="publicacion.arrImages.length > 1" class="col-md-12">
                                                <a v-for="image in publicacion.arrImages" :href="image" class="swipebox col-md-3">
                                                    <img v-if="!image.includes('/videos/')" :src="image" width="90" class="slider-item"/>
                                                    <video
                                                        v-else
                                                        id="my-video"
                                                        class="video-js"
                                                        controls
                                                        preload="auto"
                                                        data-setup='{"fluid": true}'
                                                    >
                                                        <source :src="image" type="video/mp4" />
                                                    </video>
                                                </a>
                                            </div>
                                        </div>

                                        <div class="view-all-comments">
                                            <a class="open-button" popup-open="popup-1" href="#" @click='listadoComentarios(publicacion.id)'>
                                                <i class="fa fa-comments-o"></i> {{__('traducciones.ver_todos')}}
                                            </a>
                                            <span>@{{ publicacion.cantidadComentarios }} {{__('traducciones.comentarios')}}</span>
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
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </template>
                        @include("publicaciones.modalComentarios")
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
                    tipo:0,
                    publications: "",
                    contenido:""
                }
            },
            mounted() {
                this.obtenerContenido();
            },
            methods: {
                listadoComentarios(id) {
                    axios.post(`/buscar/publicacion`, {
                        publicacion: id
                    })
                    .then(response => {
                        mostrarDatos(response.data.data);
                    }).catch(error => {
                        console.log(error);
                    });
                },obtenerContenido(){
                    let username = "{{ Auth::user()->username }}";
                    axios.post(`/obtener/publicaciones/contenido`,{
                        username: username,
                        isContenList : 1,
                        tipo: 1
                    })
                    .then(response => {
                        this.publicaciones = [];
                        if (response.data.estatus) {
                            this.publications = response.data.publications;
                            this.contenido = response.data.contenido;
                            this.publicaciones = response.data.data;
                            inicializarSlide();
                        }
                    }).catch(error => {
                        console.log(error);
                    });
                },eliminarContenido(id) {
                    swal({
                        title: "Confirmacion",
                        text: "¿Desea eliminar este contenido?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((eliminar) => {
                        if (eliminar) {
                            axios.post(`/eliminar/contenido/creador`,{
                                publicacion: id
                            })
                            .then(response => {
                                if (response.data.estatus) {
                                    swal("El contenido se eliminó con exitó!", {
                                        icon: "success",
                                    });
                                    setTimeout(function(){
                                        window.location.reload();
                                    }, 2500);
                                } else {
                                    swal("¡No se pudo eliminar el contenido!", {
                                        icon: "error",
                                    });
                                }
                            }).catch(error => {
                                console.log(error);
                            });
                        }
                    });
                }
            },
            computed: {}
        })

        function mostrarDatos(data) {
            let view = "";
            for(var i = 0; i < data.length; i++){
                var url = "{{ route('userByUsername', ['username' => 'replaceusername']) }}";
                view = view+'<li class="media"><div class="media-left"><a href="'+url.replace('replaceusername', data[i]['username'])+'"><img src="'+data[i]['imagen_perfil']+'"  class="img-circle" width="80"></a>'+
                    '</div><div class="media-body"><a href="'+url.replace('replaceusername', data[i]['username'])+'" class="comment-author pull-left">'+data[i]['username']+'</a><span>'+data[i]['descripcion']+'</span><div class="comment-date">Hace '+data[i]['date']+'</div>'+
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
@endsection
