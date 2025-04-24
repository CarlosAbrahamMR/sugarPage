@extends('layouts.admin')
@section('styles')
    <style>
        .credit-card-div span {
            padding-top: 10px;
        }

        .credit-card-div img {
            padding-top: 30px;
        }

        .credit-card-div .small-font {
            font-size: 9px;
        }

        .credit-card-div .pad-adjust {
            padding-top: 10px;
        }

        html {
            overflow-y: auto
        }

        .dropbtn {
            background-color: #04AA6D;
            color: white;
            padding: 16px;
            font-size: 16px;
            border: none;
        }

        /* The container <div> - needed to position the dropdown content */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        /* Dropdown Content (Hidden by Default) */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f1f1f1;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        /* Links inside the dropdown */
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        /* Change color of dropdown links on hover */
        .dropdown-content a:hover {
            background-color: #ddd;
        }

        /* Show the dropdown menu on hover */
        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Change the background color of the dropdown button when the dropdown content is shown */
        .dropdown:hover .dropbtn {
            background-color: #3e8e41;
        }
    </style>
@endsection
@section('content')

    <div class="st-pusher" id="content">
        <div class="st-content">
            <div class="st-content-inner">
                <div id="app" class="container-fluid">
                    @if(session('error'))
                        <div class="alert alert-danger">{{session('error')}}</div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">{{session('success')}}</div>
                    @endif
                    <div class="media media-grid media-clearfix-xs">
                        <div class="media-left">
                            <div class="width-250 width-auto-xs">
                                <div class="panel panel-default widget-user-1 text-center">
                                    <br>
                                    <v-hover v-slot="{ hover }">
                                        <v-avatar size="120" color="white">
                                            <v-img
                                                alt="Imagen de Perfil"
                                                src="{{$user->path_imagen_perfil ?  url('storage'.$user->path_imagen_perfil): asset('images/user-profile.png')}}"
                                            >
                                                <v-expand-transition>
                                                    <div
                                                        v-if="hover"
                                                        class="d-flex transition-fast-in-fast-out orange darken-2 v-card--reveal text-h2 white--text"
                                                        style="height: 100%; text-align: center !important;"
                                                    >
                                                        <form name="changePortada"
                                                              action="{{ route('upload-photo-profile') }}" method="POST"
                                                              enctype="multipart/form-data">
                                                            {{ csrf_field() }}
                                                            <label for="form-file" class="btn btn-cover">
                                                                <v-icon color="teal darken-2"> mdi-camera-outline
                                                                </v-icon>
                                                            </label>
                                                            <input type="file" name="imagen" id="form-file"
                                                                   class="hidden" onchange="submitPhoto()"/>
                                                        </form>
                                                    </div>
                                                </v-expand-transition>
                                            </v-img>
                                        </v-avatar>
                                    </v-hover>
                                    <br>
                                    <br>
                                </div>
                                <div v-if="bio !== ''" class="panel panel-default widget-user-1 text-center">
                                    <div class="panel-heading">
                                        Bio
                                    </div>
                                    <div class="panel-body">
                                        <div class="expandable expandable-indicator-white expandable-trigger">
                                            <div class="expandable-content">
                                                <p>@{{bio}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="media-body">
                            <div class="tabbable">
                                <ul class="nav nav-tabs">
                                    @if(Auth::user()->roles_id !== 2)
                                        <li :class="hash === 'photos' || hash === '' ? 'active' : ''" @click="navTab('photos')">
                                            <a :style="capturarDatos ? 'pointer-events: none;' : ''" href="#photos"
                                               data-toggle="tab">
                                                <i class="fa fa-fw fa-user"></i> {{__('traducciones.personal_data')}}
                                            </a>
                                        </li>
                                        <li :class="hash === 'editarPerfil' ? 'active' : ''" @click="navTab('editarPerfil')">
                                            <a type="button" id="editarPerfils" href="#editarPerfil" data-toggle="tab">
                                                <i class="fa fa-fw fa-pencil"></i> {{__('traducciones.editar_perfil')}}
                                            </a>
                                        </li>
                                    @endif
                                    <li :class="rol == 2 ? (hash === 'tarjeta' || hash === '' ? 'active' : '') : ''">
                                        <a href="#tarjeta" data-toggle="tab" @click="navTab('tarjeta')">
                                            <i class="fa fa-fw fa-credit-card"></i> {{__('traducciones.aniadir_tarjeta')}}
                                        </a>
                                    </li>
                                    <li :class="hash === 'cuenta' ? 'active' : ''" @click="navTab('cuenta')">
                                        <a href="#cuenta" data-toggle="tab">
                                            <i class="fa fa-fw fa-user"></i> {{__('traducciones.cuenta')}}
                                        </a>
                                    </li>
                                    <li :class="hash === 'password' ? 'active' : ''" @click="navTab('password')">
                                        <a href="#password" data-toggle="tab">
                                            <i class="fa fa-fw fa-key"></i> {{__('traducciones.contrasenia')}}
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    @if(Auth::user()->roles_id !== 2)
                                        <div :class="'tab-pane ' + (hash === 'photos' || hash === '' ? 'active' : 'fade')"
                                             id="photos">
                                            <h4 class="page-section-heading">{{__('traducciones.personal_data')}}</h4>
                                            <div class="panel panel-default">
                                                <div class="panel-body">
                                                    <ul class="list-unstyled profile-about margin-none">
                                                        <li class="padding-v-5">
                                                            <div class="row">
                                                                <div class="col-sm-4"><span
                                                                        class="text-muted">{{__('traducciones.username')}}</span>
                                                                </div>
                                                                <div class="col-sm-8">{{$user->username}}</div>
                                                            </div>
                                                        </li>
                                                        <li class="padding-v-5">
                                                            <div class="row">
                                                                <div class="col-sm-4"><span
                                                                        class="text-muted">{{__('traducciones.orientacion_sexual')}}</span>
                                                                </div>
                                                                <div class="col-sm-8">@{{sexual_orientation}}</div>
                                                            </div>
                                                        </li>
                                                        <li class="padding-v-5">
                                                            <div class="row">
                                                                <div class="col-sm-4"><span
                                                                        class="text-muted">{{__('traducciones.hair_color')}}</span>
                                                                </div>
                                                                <div class="col-sm-8">@{{hair_color}}</div>
                                                            </div>
                                                        </li>
                                                        <li class="padding-v-5">
                                                            <div class="row">
                                                                <div class="col-sm-4"><span
                                                                        class="text-muted">{{__('traducciones.skin_color')}}</span>
                                                                </div>
                                                                <div class="col-sm-8">@{{skin_color}}</div>
                                                            </div>
                                                        </li>
                                                        <li class="padding-v-5">
                                                            <div class="row">
                                                                <div class="col-sm-4"><span
                                                                        class="text-muted">{{__('traducciones.height')}}</span>
                                                                </div>
                                                                <div class="col-sm-8">@{{height}}</div>
                                                            </div>
                                                        </li>
                                                        <li class="padding-v-5">
                                                            <div class="row">
                                                                <div class="col-sm-4"><span
                                                                        class="text-muted">{{__('traducciones.complexion')}}</span>
                                                                </div>
                                                                <div class="col-sm-8">@{{complexion}}</div>
                                                            </div>
                                                        </li>
                                                        <li class="padding-v-5">
                                                            <div class="row">
                                                                <div class="col-sm-4"><span
                                                                        class="text-muted">{{__('traducciones.age')}}</span>
                                                                </div>
                                                                <div class="col-sm-8">@{{age}}</div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div :class="'tab-pane ' + (hash === 'editarPerfil' ? 'active' : 'fade')"
                                             id="editarPerfil">
                                            <h4 class="page-section-heading">{{__('traducciones.actualizar_datos_personales')}}</h4>
                                            @if(session('errorEditData'))
                                                <div class="alert alert-danger">{{session('errorEditData')}}</div>
                                            @endif
                                            <form class="form container-fluid" role="form" id="form" action="/save-personal-information" method="POST" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <input hidden id="username" name="username" value="{{Auth::user()->username}}" >
                                                <div class="row">
                                                    <div class="form-group col-md-4">
                                                        <label
                                                            class="col-md-12">
                                                            {{__("traducciones.orientacion_sexual")}}
                                                        </label>
                                                        <select
                                                            v-model="sexual_orientation"
                                                            id="sexual_orientation"
                                                            type="text"
                                                            class="form-control"
                                                            required
                                                            name="sexual_orientation">
                                                            <option value="">{{__('traducciones.seleccionar_opcion')}}</option>
                                                            <option v-for="genero in sexual_orientation_list"
                                                                    :key="genero" :value="genero">
                                                                @{{genero}}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label
                                                            class="col-md-12 ">
                                                            {{__("traducciones.hair_color")}}
                                                        </label>
                                                        <select
                                                            v-model="hair_color"
                                                            id="hair_color"
                                                            type="text"
                                                            class="form-control"
                                                            required
                                                            name="hair_color">
                                                            <option value="">{{__('traducciones.seleccionar_opcion')}}</option>
                                                            <option v-for="hair in hair_color_list"
                                                                    :key="hair" :value="hair">
                                                                @{{hair}}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label
                                                            class="col-md-12 ">
                                                            {{__("traducciones.skin_color")}}
                                                        </label>
                                                        <select
                                                            v-model="skin_color"
                                                            id="skin_color"
                                                            type="text"
                                                            class="form-control"
                                                            required
                                                            name="skin_color">
                                                            <option value="">{{__('traducciones.seleccionar_opcion')}}</option>
                                                            <option v-for="skin in skin_color_list"
                                                                    :key="skin" :value="skin">
                                                                @{{skin}}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label
                                                            class="col-md-12 ">
                                                            {{__("traducciones.height")}}
                                                        </label>
                                                        <input
                                                            v-model="height"
                                                            id="height"
                                                            type="number"
                                                            class="form-control"
                                                            required
                                                            name="height"
                                                            placeholder="Height">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label
                                                            class="col-md-12 ">
                                                            {{__("traducciones.complexion")}}
                                                        </label>
                                                        <select
                                                            v-model="complexion"
                                                            id="complexion"
                                                            type="text"
                                                            class="form-control"
                                                            required
                                                            name="complexion">
                                                            <option value="">{{__('traducciones.seleccionar_opcion')}}</option>
                                                            <option v-for="complexion in complexion_list"
                                                                    :key="complexion" :value="complexion">
                                                                @{{complexion}}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label
                                                            class="col-md-12 ">
                                                            {{__("traducciones.age")}}
                                                        </label>
                                                        <input
                                                            v-model="age"
                                                            id="age"
                                                            type="text"
                                                            class="form-control"
                                                            required
                                                            name="age"
                                                            placeholder="Age">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label
                                                            class="col-md-12 ">
                                                            {{__("traducciones.residence")}}
                                                        </label>
                                                        <select
                                                            v-model="residencia"
                                                            id="recidence"
                                                            type="text"
                                                            class="form-control"
                                                            required
                                                            name="recidence">
                                                            <option value="">{{__('traducciones.seleccionar_opcion')}}</option>
                                                            <option v-for="residencia in residencia_list"
                                                                    :key="residencia" :value="residencia">
                                                                @{{residencia}}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label
                                                            class="col-md-12 ">
                                                            {{__("traducciones.language")}}
                                                        </label>
                                                        <select
                                                            v-model="language"
                                                            id="language"
                                                            type="text"
                                                            class="form-control"
                                                            required
                                                            name="language">
                                                            <option value="">{{__('traducciones.seleccionar_opcion')}}</option>
                                                            <option v-for="language in language_list"
                                                                    :key="language" :value="language">
                                                                @{{language}}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label
                                                            class="col-md-12 ">
                                                            {{__("traducciones.identificacion")}}
                                                        </label>
                                                        <input type="file" name="identificacion" id="form-file" />
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label
                                                            class="col-md-12 ">
                                                            {{__("traducciones.bio")}}
                                                        </label>
                                                        <textarea
                                                            v-model="bio"
                                                            id="bio"
                                                            type="text"
                                                            class="form-control"
                                                            required
                                                            name="bio"
                                                            placeholder="Describe yourself"
                                                            rows="3">
                                                            </textarea>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row justify-content-center">
                                                    <div>
                                                        <button type="submit"
                                                                class="btn btn-primary">{{__("traducciones.guardar")}}</button>
                                                    </div>
                                                </div>
                                                <br>
                                            </form>
                                        </div>
                                    @endif
                                    <div
                                        :class="'tab-pane ' + (rol == 2 ? (hash === 'tarjeta' || hash === '' ? 'active' : 'fade') : 'fade')"
                                        id="tarjeta">
                                        <h4 class="page-section-heading">{{__('traducciones.update_payment_method')}}</h4>
                                        <div class="credit-card-div">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <div class="row ">
                                                        <div class="col-md-12">
                                                            <div class="form-control" id="card-element"></div>
                                                        </div>
                                                    </div>
                                                    <div class="row ">
                                                        <div class="col-md-12 pad-adjust">
                                                            <select class="form-control" id="country" name="country">
                                                                <option value="1">Eu</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row ">
                                                        <div class="col-md-12 pad-adjust">
                                                            <input placeholder="Titular" type="email"
                                                                   id="card-holder-name"
                                                                   name="card-holder-name"
                                                                   class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row ">
                                                        <div class="col-md-6 col-sm-6 col-xs-6 pad-adjust">
                                                            <button id="card-button" @click="clic()"
                                                                    data-secret="{{ $intent->client_secret }}"
                                                                    class="btn btn-success"
                                                            >
                                                                {{__('traducciones.update_payment_method')}}
                                                            </button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="py-12">
                                            <form id="payment_method_form" method="post"
                                                  action="{{ route("agreagar.tarjeta") }}">
                                                @csrf
                                                <input type="hidden" id="card_holder_name" name="card_holder_name"/>
                                                <input type="hidden" id="pm" name="pm"/>
                                                <input type="hidden" id="country_id" name="country_id"/>
                                                <input type="hidden" id="stripe" name="stripe"/>
                                            </form>
                                        </div>
                                    </div>
                                    <div :class="'tab-pane ' + (hash === 'cuenta' ? 'active' : 'fade')" id="cuenta">
                                        <h4 class="page-section-heading">{{__('traducciones.actualizar_usuario')}}</h4>
                                        @if (\Session::has('error-cuenta'))
                                            <div class="alert alert-danger">{{ session()->get('error-cuenta') }}</div>
{{--                                            <span style="color:red;">{{ session()->get('usererror') }}</span>--}}
                                        @endif
                                        <div class="row">
                                            <form class="form col-md-12" role="form" method="post"
                                                  action="{{route('edit-cuenta',['id' => $user->id])}}">
                                                @method('PATCH')
                                                @csrf
                                                <div class="form-group col-md-6">
                                                    <label class="col-md-12">{{__("traducciones.username")}}</label>
                                                    <input type="text" class="form-control"
                                                           style="text-transform:lowercase"
                                                           @keypress="sinEspacio($event)" id="username" name="username"
                                                           @change="buttonUsername = true" v-model="username">
                                                    @if(Auth::user()->roles_id !== 2)
                                                        <span>https://mysugar.fan/@{{username.toLowerCase()}}</span>
                                                    @endif
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="col-md-12">{{__('traducciones.correo')}}</label>
                                                    <input type="text" class="form-control" v-model="userEmail"
                                                           id="email" name="email" @change="buttonEmail = true">
                                                    @if(Auth::user()->roles_id !== 2)
                                                        <span>&nbsp</span>
                                                    @endif
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="col-md-12">{{__('traducciones.pais_origen')}}</label>
                                                    <select class="form-control" style="width: 100%;"
                                                            v-model="pais_origen" @change="cambiaselect($event)" id="pais_origen" name="pais_origen">
                                                        <option
                                                            value="">{{__('traducciones.seleccionar_opcion')}}</option>
                                                        @foreach($paises as $pais)
                                                            <option value="{{$pais->iso}}">{{$pais->nombre}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="col-md-12">{{__("traducciones.lenguaje")}}</label>
                                                    <select class="form-control" name="language"
                                                            onchange="location = this.value;">
                                                        <option
                                                            value="">{{__('traducciones.seleccionar_opcion')}}</option>
                                                        @foreach (Config::get('languages') as $lang => $language)
                                                            <option
                                                                value="{{ route('lang.switch', $lang) }}">{{$language['display']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label
                                                        class="col-md-12 ">
                                                        {{__("traducciones.bio")}}
                                                    </label>
                                                    <textarea
                                                        v-model="bio"
                                                        id="bio_fan"
                                                        type="text"
                                                        class="form-control"
                                                        name="bio_fan"
                                                        placeholder="Describe yourself"
                                                        rows="5">
                                                    </textarea>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox2" type="checkbox" name="notificaciones" @if($user->recibir_notificacion) checked @endif>
                                                        <label for="checkbox2">{{__('traducciones.activar_notifi')}}</label>
                                                    </div>
                                                </div>
                                                <div style="text-align: center;">
                                                    <button type="submit" class="btn btn-primary">{{__('traducciones.actualizar_info')}}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div :class="'tab-pane ' + (hash === 'password' ? 'active' : 'fade')" id="password">
                                        <h4 class="page-section-heading">{{__('traducciones.cambiar_contrasenia')}}</h4>
                                        <form action="{{ route('update-password') }}" method="POST" class="form"
                                              role="form">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label for="oldPasswordInput"
                                                           class="col-md-12">{{__('traducciones.contrasenia_anterior')}}</label>
                                                    <input name="old_password" type="password"
                                                           class="form-control @error('old_password') is-invalid @enderror"
                                                           id="oldPasswordInput"
                                                           placeholder="Old Password">
                                                    @error('old_password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="newPasswordInput"
                                                           class="col-md-12">{{__('traducciones.contrasenia_nueva')}}</label>
                                                    <input name="new_password" type="password"
                                                           class="form-control @error('new_password') is-invalid @enderror"
                                                           id="newPasswordInput"
                                                           placeholder="New Password">
                                                    @error('new_password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="confirmNewPasswordInput"
                                                           class="col-md-12">{{__('traducciones.confirmar_contrasenia')}}</label>
                                                    <input name="new_password_confirmation" type="password"
                                                           class="form-control" id="confirmNewPasswordInput"
                                                           placeholder="Confirm New Password">
                                                </div>
                                            </div>
                                            <div class="form-group margin-none">
                                                <a type="button" @click="modalForgot()"
                                                   class="forgot-password">{{__('traducciones.olvidaste_contrasenia')}}</a>
                                            </div>
                                            <div class="form-group margin-none">
                                                <button class="btn btn-primary"><i
                                                        class="fa fa-check-circle"></i> {{__("traducciones.guardar")}}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <v-app v-if="dialogForgot">
                        <v-dialog
                            v-model="dialogForgot"
                            persistent
                            max-width="600px"
                        >
                            <v-card>
                                <v-card-title>
                                    <span class="text-h5">c</span>
                                </v-card-title>
                                <v-card-text>
                                    <form method="POST" action="{{ route('password.email') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label
                                                    class="col-md-12">
                                                    {{ __('traducciones.correo') }}
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="md md-email"></i></span>
                                                    <input
                                                        v-model="email"
                                                        id="email"
                                                        type="text"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        required autocomplete="email" autofocus
                                                        name="email">
                                                </div>
                                            </div>
                                        </div>
                                        <button @click="close()" type="button" class="btn btn-danger"><i
                                                class="fa fa-times"></i> {{__("traducciones.cancelar")}}</button>
                                        <button type="submit" class="btn btn-success"><i
                                                class="fa fa-check-circle"></i> {{ __('traducciones.enviar_link_reset_pass') }}
                                        </button>
                                    </form>
                                </v-card-text>
                            </v-card>
                        </v-dialog>
                    </v-app>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        function submitPhoto() {
            console.log('submit foto')
            document.changePortada.submit()
        }

        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data() {
                return {
                    rol: '{{Auth::user()->roles_id}}',
                    username: '{{$user->username}}',
                    userEmail: '{{$user->email}}',
                    creador: '{{$user->creador}}',
                    fan: '{{$user->fan}}',
                    foto_perfil: '{{$user->foto_perfil}}',
                    foto_portada: '{{$user->foto_portada}}',
                    capturarDatos: '{{$personales ? false : true}}',
                    sexual_orientation: '{{$personales ? $personales->sexo : ''}}',
                    hair_color: '{{$personales ? $personales->color_cabello : ''}}',
                    skin_color: '{{$personales ? $personales->color_piel : ''}}',
                    height: '{{$personales ? $personales->estatura : ''}}',
                    complexion: '{{$personales ? $personales->complexion : ''}}',
                    age: '{{$personales ? $personales->edad : ''}}',
                    age_range: '{{$personales ? $personales->age_range : ''}}',
                    residencia: '{{$personales ? $personales->residencia : ''}}',
                    language: '{{$personales ? $personales->idioma : ''}}',
                    bio: '{{$personales ? $personales->bio : $user->bio_fan}}',
                    codeRedeem: '',
                    sexual_orientation_list: ["MAN", "WOMAN", "OTHER"],
                    hair_color_list: ['NEGRO', 'MORENO', 'CASTAÑO', 'RUBIO', 'PLATINO'],
                    skin_color_list: ['CLARO', 'MORENO', 'OSCURO'],
                    residencia_list: ['MEXICO', 'CANADÁ', 'ESTADOS UNIDOS', 'ESPAÑA', 'FRANCIA'],
                    language_list: ['ESPAÑOL', 'INGLÉS', 'FRANCES'],
                    complexion_list: ['DELGADA', 'MEDIA'],
                    editarPersonales: false,
                    country: '',
                    cardHolderName: '',
                    clientSecret: '',
                    cardElement: '',
                    stripe: '',
                    buttonUsername: false,
                    buttonEmail: false,
                    buttonPais_origen: false,
                    dialogForgot: false,
                    email: '',
                    dialogProfile: false,
                    tipo: 0,
                    paga: true,
                    gratis: false,
                    lang: '',
                    hash: '',
                    pais_origen: '{{$user->pais_origen ?: ''}}'
                }
            },
            mounted() {
                this.stripe = Stripe('{{ config("cashier.key") }}');

                const elements = this.stripe.elements();
                this.cardElement = elements.create('card');

                this.cardElement.mount('#card-element');

                this.country = document.getElementById('country');
                this.cardHolderName = document.getElementById('card-holder-name');
                const cardButton = document.getElementById('card-button');
                this.clientSecret = cardButton.dataset.secret;

                this.hash = location.hash.replace(/^#/, '');  // ^ means starting, meaning only match the first hash
                console.log(this.pais_origen)
            },
            methods: {
                dialog() {
                    console.log('dialog')
                    document.getElementById("editarPerfils").click();
                    // this.editarPersonales = true
                },
                async clic() {
                    console.log('entra')
                    const {setupIntent, error} = await this.stripe.confirmCardSetup(
                        this.clientSecret, {
                            payment_method: {
                                card: this.cardElement,
                                billing_details: {name: this.cardHolderName.value}
                            }
                        }
                    );

                    if (error) {
                        alert(error.message)
                    } else {
                        await this.stripe.createToken(this.cardElement).then(function (result) {
                            if (result.error) {
                                alert(result.error.message)
                                console.log(result.error.message)
                            } else {
                                // Send the token to your server.
                                console.log(result)
                                console.log('result')
                                console.log(result.token)
                                var token = result.token;
                                console.log('result2')
                                document.getElementById("pm").value = setupIntent.payment_method;
                                console.log('result3')
                                //document.getElementById("card_holder_name").value = this.cardHolderName.value;
                                console.log('result4')
                                //document.getElementById("country_id").value = this.country.value;
                                console.log('result5')
                                document.getElementById("stripe").value = token.id;
                                console.log('result6')
                                document.getElementById("payment_method_form").submit();
                            }
                        });
                        //document.getElementById("pm").value = setupIntent.payment_method;
                        //document.getElementById("card_holder_name").value = this.cardHolderName.value;
                        //document.getElementById("country_id").value = this.country.value;
                        //document.getElementById("payment_method_form").submit();
                    }
                },
                sinEspacio(e) {
                    if (e.keyCode === 32) {
                        e.preventDefault();
                    }
                },
                modalForgot() {
                    console.log('modal forgot')
                    this.dialogForgot = true
                    console.log('dialogForgot', this.dialogForgot)
                },
                close() {
                    this.dialogForgot = false
                },
                closeDialogProfile() {
                    this.dialogProfile = false
                },
                cambiaTexto() {
                    if (this.tipo == 0) {
                        this.gratis = false
                        this.paga = true
                    } else if (this.tipo == 1) {
                        this.paga = false
                        this.gratis = true
                    }
                    console.log('modal asdfg')
                    console.log(this.tipo)
                },
                redirectLang() {
                    console.log('cambio')
                    var e = document.getElementById("selectflags");
                    window.location.href = e.value
                },
                navTab(tab) {
                    // Javascript to enable link to tab
                    var hash = location.hash.replace(/^#/, '');  // ^ means starting, meaning only match the first hash
                    console.log('hash', hash)
                    if (hash) {
                        console.log($('.nav-tabs a[href="#' + hash + '"]'))
                        $('.nav-tabs a[href="#' + hash + '"]').addClass('active');
                    }
                    $('.nav-tabs a').on('shown.bs.tab', function (e) {
                        window.location.hash = e.target.hash;
                    })
                },
                cambiaselect(event) {
                    this.buttonPais_origen = true
                    console.log('cambio select', event.target.value)
                }
            },
            computed: {}
        })
    </script>
@endsection
