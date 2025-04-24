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
                    @if(session('error'))
                        <div class="alert alert-danger">{{session('error')}}</div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">{{session('success')}}</div>
                    @endif
                    <div class="row" data-toggle="isotope">
                        @foreach ($following as $user)
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
                                                    {{-- <span><i class="fa fa-users"></i> 372</span>
                                                    <span><i class="fa fa-photo"></i> 43</span>
                                                    <span><i class="fa fa-video-camera"></i> 3</span> --}}
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
                                        <form class="form-horizontal" role="form" id="{{'form_suscribete_'.$user['idCreador']}}" action="{{route('crear.suscripcion')}}" method="POST">
                                            {{ csrf_field() }}
                                            <a href="{{ route("userByUsername",['username' => $user->username]) }}" class="btn btn-default btn-sm">View more <i class="fa fa-share"></i></a>
                                            <a href="javascript:{}" @click="unfollow('{{$user->name}}')" class="btn btn-default btn-sm">Unfollow <i class="fa fa-share"></i></a>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                    Suscribir
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @foreach($user->planes as $plan)
                                                        @if($plan['monto'] == 0)
                                                            <li><a id="submit" href="javascript:{}" @click="suscribir({{$plan['idCreador']}}, {{$plan['monto']}}, '{{$user->name}}')"><i class="fa fa-dollar"></i>Gratis</a></li>
                                                            <input type="hidden" class="form-control" name="tipo" id="exampleInputFirstName" value="0">
                                                            <input type="hidden" class="form-control" name="precio" id="exampleInputLastName" value='price_0'>
                                                        @else
                                                            <li><a id="submit" href="javascript:{}" @click="suscribir({{$plan['idCreador']}}, {{$plan['monto']}}, '{{$user->name}}')"><i class="fa fa-dollar"></i>{{$plan['monto']}} USD/{{$plan['interval']}} {{$user->idCreador}}</a></li>
                                                            <input type="hidden" class="form-control" name="tipo" id="exampleInputFirstName" value="1">
                                                            <input type="hidden" class="form-control" name="precio" id="exampleInputLastName" value='{{$plan['id_precio']}}'>
                                                        @endif

                                                        <input type="hidden" class="form-control" name="creador" id="exampleInputFirstName" value="{{$plan['idCreador']}}">
                                                        <input type="hidden" class="form-control" name="plan" id="exampleInputLastName" value='{{$plan['id_plan']}}'>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </form>
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
                suscribir(creador, precio, name){
                    console.log('form_suscribete_'+creador)
                    swal({
                        title: "Confirmacion",
                        text: "¿Desea suscribirte a "+name+" por $" + precio + " USD?'",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((comprar) => {
                        if (comprar) {
                            this.overlay = true
                            document.getElementById('form_suscribete_'+creador).submit();
                            return false;
                        }
                    });
                },
                unfollow(creador){
                    swal({
                        title: "Confirmacion",
                        text: "¿Seguro que seseas dejar de seguir a "+creador+"?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                        .then((comprar) => {
                            if (comprar) {
                                this.overlay = true
                                window.location.href = "{{ route("unfollow",['username' => $user->username]) }}"
                            }
                        });
                }
            }
        })
    </script>
@endsection
