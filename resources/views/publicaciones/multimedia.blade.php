@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.css">
    <link rel="stylesheet" href="{{ asset('css/swipebox.css') }}">
@endsection

@section('content')
    <!-- content push wrapper -->
    <div class="st-pusher" id="content">
        <!-- this is the wrapper for the content -->
        <div class="st-content">
            <!-- extra div for emulating position:fixed of the menu -->
            <div class="st-content-inner">
                <div class="container-fluid">
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
                                                            <form action="/save-video" class="dropzone" id="my-awesome-dropzone" style="border:0;">
                                                                <input type="hidden" name="_token" v-bind:value="csrf">
                                                                <div class="dz-default dz-message">
                                                                    <p class="text-muted">{{__('traducciones.agregar_videos')}} <br>
                                                                        {{__('traducciones.arrastrar_soltar')}}
                                                                    </p>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="panel-footer share-buttons">
                                                            <a href="#"><i class="fa fa-map-marker"></i></a>
                                                            <a href="#"><i class="fa fa-video-camera"></i></a>
                                                            <button class="btn btn-primary btn-xs pull-right" @click="save()">{{__('traducciones.publicar')}}</button>
                                                            <div class="clearfix"></div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.js"></script>
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
                    actualizacionEstatus: ''
                }
            },
            mounted() {

            },
            methods: {
                save() {
                    axios.post(`/save-publication`,{
                        texto: this.actualizacionEstatus,
                        privado: 2,
                    })
                    .then(response => {
                        if (response.data.estatus) {
                            swal({
                                title: "¡{{__('traducciones.exito')}}!",
                                text: "{{__('traducciones.video_saved')}}",
                                icon: "success",
                                button: "OK!",
                            });
                            setTimeout(function(){
                                window.location.reload();
                            }, 2500);
                        }
                    }).catch(error => {
                        swal("¡No se pudo subir el contenido!", {
                            icon: "error",
                        });
                    });
                }
            }
        });
    </script>
@endsection
