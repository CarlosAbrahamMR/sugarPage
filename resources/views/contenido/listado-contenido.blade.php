@extends('layouts.appsinvue')

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

                <div id="filter" class="row">
                    <div class="col-md-10 col-lg-8 col-md-offset-1 col-lg-offset-2">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route("list.content") }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <select multiple="multiple" name="filtros[]" style="width: 100%;" data-toggle="select2" data-placeholder="Select filter" data-allow-clear="true">
                                        <optgroup label="Gender">
                                            <option value="GENDER-MAN">MAN</option>
                                            <option value="GENDER-WOMAN">WOMAN</option>
                                            <option value="GENDER-OTHER">OTHER</option>
                                        </optgroup>
                                        <optgroup label="Hair">
                                            <option value="HAIR-NEGRO">NEGRO</option>
                                            <option value="HAIR-CASTAÑO">CASTAÑO</option>
                                            <option value="HAIR-RUBIO">RUBIO</option>
                                            <option value="HAIR-PLATINO">PLATINO</option>
                                        </optgroup>
                                        <optgroup label="Skin">
                                            <option value="SKIN-CLARO">CLARO</option>
                                            <option value="SKIN-MORENO">MORENO</option>
                                            <option value="SKIN-OSCURO">OSCURO</option>
                                        </optgroup>
                                        <optgroup label="Residence">
                                            <option value="RES-MEXICO">MEXICO</option>
                                            <option value="RES-CANADÁ">CANADÁ</option>
                                            <option value="RES-ESTADOS_UNIDOS">ESTADOS UNIDOS</option>
                                            <option value="RES-ESPAÑA">ESPAÑA</option>
                                            <option value="RES-FRANCIA">FRANCIA</option>
                                        </optgroup>
                                        <optgroup label="Language">
                                            <option value="LANG-ESPAÑOL">ESPAÑOL</option>
                                            <option value="LANG-INGLÉS">INGLÉS</option>
                                            <option value="LANG-FRANCES">FRANCES</option>
                                        </optgroup>
                                        <optgroup label="Complexion">
                                            <option value="COM-DELGADA">DELGADA</option>
                                            <option value="COM-MEDIA">MEDIA</option>
                                        </optgroup>
                                    </select>
                                    <span class="input-group-btn">
                                        <button class="btn btn-inverse" type="submit">Filter</button>
                                    </span>
                                </div>
                                <!-- /input-group -->
                            </div>
                        </div>
                    </form>
                    </div>
                </div>

            <div class="row" data-toggle="isotope">
                @foreach ($usuarios as $user)
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
                            <a href="{{ route("userByUsername",['username' => $user->username]) }}" class="btn btn-default btn-sm">View more <i class="fa fa-share"></i></a>
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

    </div>
    <!-- /st-pusher -->
@endsection
