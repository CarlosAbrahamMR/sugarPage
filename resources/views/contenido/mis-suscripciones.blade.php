@extends('layouts.app')

@section('content')

    <!-- content push wrapper -->
    <div class="st-pusher" id="content">

        <!-- sidebar effects INSIDE of st-pusher: -->
        <!-- st-effect-3, st-effect-6, st-effect-7, st-effect-8, st-effect-14 -->

        <!-- this is the wrapper for the content -->
        <div class="st-content">
            <!-- extra div for emulating position:fixed of the menu -->
            <div class="st-content-inner">

                <div class="container-fluid">
                    @if(session('success'))
                        <div class="alert alert-success">{{session('success')}}</div>
                    @endif
                    @if(session('Error'))
                        <div class="alert alert-danger">{{ session()->get('Error')['message'] }}</div>
                    @endif
                    <div class="row" data-toggle="isotope">
                        @foreach ($creadores as $user)
                            <div class="col-md-6 col-lg-4 item">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="media">
                                            <div class="pull-left">
                                                <img style="width: 133px; height: 133px;" src="{{$user->path_imagen_perfil ? url('storage'.$user->path_imagen_perfil) : asset('images/user-profile.png')}}" alt="people" class="media-object img-circle" />
                                            </div>
                                            <div class="media-body">
                                                <h4 class="media-heading margin-v-5"><a href="#"></a></h4>
                                                <div class="profile-icons">
                                                    <span><i class="fa fa-dollar"></i> {{$user->monto}} USD/{{$user->intervalo}}</span>
{{--                                                    <span><i class="fa fa-photo"></i> 43</span>--}}
{{--                                                    <span><i class="fa fa-video-camera"></i> 3</span>--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <p class="common-friends">{{$user->name}}</p>
                                        <p class="common-friends">{{$user->username}}</p>
                                        <div class="user-friend-list">

                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <a href="{{ route("userByUsername",['username' => $user->username]) }}" class="btn btn-default btn-sm">View more <i class="fa fa-share"></i></a>
                                        @if($user->status != 'Cancelar')
                                        <a href="javascript:void(0)" @click="unsuscribe('{{$user->name}}', {{$user->username}})" class="btn btn-default btn-sm">Unsubscribe <i class="fa fa-thumbs-down"></i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>

            </div>
            <!-- /st-content-inner -->

        </div>
        <!-- /st-content -->
        <v-overlay :value="overlay">
            <v-progress-circular
                indeterminate
                size="64"
            ></v-progress-circular>
        </v-overlay>
    </div>
    <!-- /st-pusher -->
@endsection
@section('scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data(){
                return {
                    overlay:false
                }
            },
            mounted() {

            },
            methods: {
                unsuscribe(creador, username){
                    swal({
                        title: "Confirmacion",
                        text: "¿Seguro que seseas cancelar la suscripción con "+creador+"?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((comprar) => {
                        if (comprar) {
                            this.overlay = true
                            let url = "{{ route("unsubscribe",['username' => 'replaceusername']) }}"
                            window.location.href = url.replace('replaceusername', username);
                        }
                    });
                }
            }
        })
    </script>
@endsection
