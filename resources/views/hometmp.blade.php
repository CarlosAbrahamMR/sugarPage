@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.css">
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

        @media (min-width: 600px) {
            .imgCover {
                width: 100% !important;
                height: 300px !important;
                object-fit: cover !important;
            }
        }
    </style>
@endsection

@section('content')
    <!-- content push wrapper -->
    <div class="st-pusher" id="content">
        <!-- this is the wrapper for the content -->
        <div class="st-content">
            <!-- extra div for emulating position:fixed of the menu -->
            <div class="st-content-inner">
                <div class="container-fluid">
                    @if($contenido == "false")
                        <div class="cover profile" id="coverProfile">
                            <div class="cover-info">
                                <ul class="cover-nav">
                                    <li :class="seguidos">
                                        <a @click="mostrarPublicaciones(0)" href="javascript:void(0)"><i class="fa fa-bookmark"></i> {{__('traducciones.mis_favoritos')}}</a>
                                    </li>
                                    <li :class="cExplorar">
                                        <a @click="mostrarPublicaciones(1)" href="javascript:void(0)"><i class="fa  fa-search"></i> {{__('traducciones.ver_todo')}}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endif
                    <div class="cover overlay cover-menu cover-image-full height-300-lg">
                        <img src="{{Auth::user()->path_imagen_portada ? url('storage'.Auth::user()->path_imagen_portada) : asset('dist/themes/social-1/images/profile-cover.jpg')}}" class="imgCover" />
                        <div class="overlay overlay-full">
                            <div class="v-top">
                                <form name="changePortada" action="{{ route('upload-photo-portada') }}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <label for="form-file" class="btn btn-cover"><i class="fa fa-pencil"></i></label>
                                    <input type="file" name="file" id="form-file" class="hidden" onchange="submitPhoto()" />
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="app">
                        <div class="col-md-12">
                            <ul class="timeline-list">
                                <li class="media media-clearfix-xs">
                                    @if(Auth::user()->roles_id !== 2)
                                        <div class="media-left">
                                            <div class="user-wrapper">
                                                <img src="{{ Auth::user()->path_imagen_perfil ? url('storage'.Auth::user()->path_imagen_perfil) : asset('images/user-profile.png') }}" alt="people" class="img-circle" width="80" />
                                                <div><a href="{{ route('profile') }}">{{ Auth::user()->name }}.</a></div>
                                                <div class="date">{{ date("j F") }}</div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="media-body">
                                        <div class="container-fluid">
                                            <div class="media-body-wrapper">
                                                @if(Auth::user()->roles_id !== 2)
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading panel-heading-gray title">
                                                            {{__('traducciones.crear_publicacion')}}
                                                        </div>
                                                        <div class="panel-body">
                                                            <textarea name="status" class="form-control share-text" rows="3" placeholder="{{__('traducciones.que_piensas')}}" v-model="actualizacionEstatus"></textarea>
                                                            <form action="/save-image" class="dropzone" id="my-awesome-dropzone" style="border:0;">
                                                                <input type="hidden" name="_token" v-bind:value="csrf">
                                                                <div class="dz-default dz-message">
                                                                    <p class="text-muted">{{__('traducciones.agregar_fotos')}} <br>
                                                                        {{__('traducciones.arrastrar_soltar')}}
                                                                    </p>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="panel-footer share-buttons">
                                                            <a href="#"><i class="fa fa-map-marker"></i></a>
                                                            <a href="#"><i class="fa fa-video-camera"></i></a>
                                                            <a href="#"><i class="fa fa-cog" id="configurationPublication"></i></a>
                                                            <button class="btn btn-primary btn-xs pull-right" @click="save()">{{__('traducciones.publicar')}}</button>

                                                            <div class="clearfix"></div>
                                                            <div class="clearfix"></div>
                                                            @if($contenido == "true")
                                                                <div id="configContent" style="display: none;" class="form-group">
                                                                    <div class="checkbox checkbox-primary checkbox-inline">
                                                                        <input type="checkbox" id="inlineCheckbox3" value="option2">
                                                                        <label for="inlineCheckbox3">Agregar precio</label>
                                                                    </div>
                                                                    <div class="form-group bottom-50">
                                                                        <input type="number" class="form-control" placeholder="Precio" id="inputPrecio" min="0">
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li v-for="(publicacion, index) in publicaciones" class="media media-clearfix-xs">
                                    <div class="media-left">
                                        <div class="user-wrapper">
                                            <img :src="publicacion.imgPerfil" class="img-circle" width="80"/>
                                            <div><a href='javascript:void(0)' @click="redireccionPagina(publicacion.username)">@{{publicacion.nameUserPublicacion}}</a></div>
                                            <div class="date">@{{publicacion.date}}</div>

                                            <div v-if="cExplorar == 'active'">
                                                <a @click="seguirCreador(publicacion.username, publicacion.nameUserPublicacion)" href="javascript:void(0)" class="text-muted"><i class="icon-reply-all-fill fa fa-2x "></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-body-wrapper">
                                            <div class="row">
                                                <div class="col-md-8 col-lg-6">
                                                    <div class="panel panel-default">
                                                        <div class="panel-body">
                                                            <p>@{{publicacion.descripcion}}</p>

                                                            <div v-for="image in publicacion.arrImages">
                                                                <div v-if="image.length == 1">
                                                                    <img v-if="!image[0].includes('/videos/')" :src="image[0]" class="img-responsive"/>
                                                                    <video
                                                                        v-else
                                                                        id="my-video"
                                                                        class="video-js"
                                                                        controls
                                                                        preload="auto"
                                                                        width="640"
                                                                        height="264"
                                                                        data-setup='{"fluid": true}'
                                                                    >
                                                                        <source :src="image[0]" type="video/mp4" />
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

                                                                <div class="col-md-12" v-if="image.length > 1">
                                                                    <div v-for="img in image">
                                                                        <a v-if="!img.path.includes('/videos/')" :href="'storage'+img.path+img.nombre" class="swipebox">
                                                                            <img :src="'storage'+img.path+img.nombre" width="90" class="slider-item col-md-3"/>
                                                                        </a>
                                                                        <video
                                                                            v-else
                                                                            id="my-video"
                                                                            class="video-js vjs-1-1"
                                                                            controls
                                                                            preload="auto"
                                                                            data-setup='{"fluid": true}'
                                                                        >
                                                                            <source :src="'storage'+img.path+img.nombre" type="video/mp4" />
                                                                        </video>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="view-all-comments">
                                                            <a class="open-button" popup-open="popup-1" href="javascript:void(0)" @click='listadoComentarios(publicacion.id)'>
                                                                <i class="fa fa-comments-o"></i> {{__('traducciones.ver_todos')}}
                                                            </a>
                                                            <span>@{{ publicacion.cantidadComentarios }} {{__('traducciones.comentarios')}}</span>

                                                            <a href="javascript:void(0)" v-if="publicacion.precio > 0" style="float: right;" @click="comprarPublicacion(publicacion.id, publicacion.precio)">
                                                                <i class="fa fa-money"></i> {{__('traducciones.buy')}}</a>
                                                        </div>

                                                        <ul class="comments">
                                                            <li class="media"  v-if="publicacion.cantidadComentarios > 0">
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
                                                            <li class="comment-form" v-if="publicacion.precio == 0">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" :id="'comment'+publicacion.id"/>
                                                                    <span class="input-group-btn"><button class="btn btn-default" @click='saveComment(publicacion.id)'>{{__('traducciones.comentar')}}</button></span>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        @include("publicaciones.modalComentarios")
                    </div>
                </div>
            </div>
        </div>
        <v-overlay :value="overlay">
            <v-progress-circular
                indeterminate
                size="64"
            ></v-progress-circular>
        </v-overlay>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.js"></script>
    <script src="https://vjs.zencdn.net/8.0.4/video.min.js"></script>
    <script>

        function submitPhoto(){
            console.log('submit foto')
            document.changePortada.submit()
        }

        Dropzone.options.myAwesomeDropzone = {
            paramName: "file",
            maxFilesize: 200000
        };

        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data(){
                return {
                    csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    actualizacionEstatus: '',
                    publicaciones : [],
                    seguidos: "",
                    cExplorar:"",
                    overlay:false
                }
            },
            mounted() {
                this.mostrarPublicaciones(0);
            },
            methods: {
                save() {
                    var priv = {{ $contenido }};
                    var incluyePrecio = $('#inlineCheckbox3').is(':checked');
                    var precioPublicacion = $('#inputPrecio').val();
                    axios.post(`/save-publication`,{
                        texto: this.actualizacionEstatus,
                        privado: priv,
                        incluyePrecio: incluyePrecio,
                        precioPublicacion: precioPublicacion
                    })
                        .then(response => {
                            if (response.data.estatus) {
                                location.reload();
                            }
                        }).catch(error => {
                        console.log(error);
                    });
                },
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
                            this.mostrarDatos(response.data.data);
                        }).catch(error => {
                        console.log(error);
                    });
                },
                mostrarDatos(data) {
                    let view = "";
                    for(var i = 0; i < data.length; i++){
                        view = view+'<li class="media"><div class="media-left"><a href=""><img src="'+data[i]['imagen_perfil']+'"  class="media-object"></a>'+
                            '</div><div class="media-body"><a href="" class="comment-author pull-left">'+data[i]['username']+'</a><span>'+data[i]['descripcion']+'</span><div class="comment-date">Hace '+data[i]['date']+'</div>'+
                            '</div></li>';
                    }
                    document.getElementById('listComments').innerHTML = view;

                    $('[popup-name="popup-1"]').fadeIn(300);
                },
                mostrarPublicaciones(explorar) {
                    this.publicaciones = [];
                    axios.post(`/obtener/publicaciones`,{
                        explorar: explorar
                    })
                    .then(response => {
                        if (response.data.estatus) {
                            this.seguidos = response.data.seguidos;
                            this.cExplorar = response.data.cExplorar;
                            this.publicaciones = response.data.data;
                            inicializarSlide();
                        }
                    }).catch(error => {
                        console.log(error);
                    });
                },
                seguirCreador(username, name) {
                    swal({
                        title: "Confirmacion",
                        text: "¿Seguro que seseas seguir a "+name+"?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((comprar) => {
                        if (comprar) {
                            this.overlay = true
                            axios.post(`/seguidor/guardar`,{
                                username: username
                            })
                                .then(response => {
                                    if (response.data.estatus) {
                                        location.reload();
                                    }
                                }).catch(error => {
                                console.log(error);
                            });
                        }
                    });

                },
                comprarPublicacion(id, precio) {
                    swal({
                        title: "Confirmacion",
                        text: "¿Desea comprar este contenido por $" + precio + ' USD?',
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
                                    }, 2500);
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
                redireccionPagina(username){
                    var url = "{{ route('userByUsername', ['username' => 'replaceusername']) }}";
                    window.location.href = url.replace('replaceusername', username);
                }
            }
        })

        function inicializarSlide() {
            $(function() {
                $('.swipebox').swipebox();
            });
        }
    </script>
@endsection
