@extends('layouts.admin')
@section('content')
    <div class="st-pusher" id="content">
        <div class="st-content">
            <div class="st-content-inner">
                <div data-app id="app" class="container-fluid">
                    <div class="panel panel-default">
                            <h4 class="page-section-heading">{{$user->roles_id == 2 ? 'Register your personal data to become a creator' : 'Update Personal Data'}}</h4>
                            <form class="form container-fluid" role="form" id="form" action="{{ route('save-personal-information') }}" method="POST" enctype="multipart/form-data">
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
                                        <input required type="file" name="identificacion" id="form-file" />
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
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data() {
                return {
                    username: '{{$user->username}}',
                    userEmail: '{{$user->email}}',
                    creador: '{{$user->creador}}',
                    fan: '{{$user->fan}}',
                    foto_perfil: '{{$user->foto_perfil}}',
                    foto_portada: '{{$user->foto_portada}}',
                    capturarDatos: '{{$personales ? false : true}}',
                    sexual_orientation: '{{ old('password') ?: ($personales ? $personales->sexo : '') }}',
                    hair_color: '{{ old('hair_color') ?: ($personales ? $personales->color_cabello : '')}}',
                    skin_color: '{{ old('skin_color') ?: ($personales ? $personales->color_piel : '')}}',
                    height: '{{ old('height') ?: ($personales ? $personales->estatura : '')}}',
                    complexion: '{{ old('complexion') ?: ($personales ? $personales->complexion : '')}}',
                    age: '{{ old('age') ?: ($personales ? $personales->edad : '') }}',
                    age_range: '{{ old('age_range') ?: ($personales ? $personales->age_range : '')}}',
                    residencia: '{{ old('recidence') ?: ($personales ? $personales->residencia : '')}}',
                    language: '{{ old('language') ?: ($personales ? $personales->idioma : '')}}',
                    bio: '{{ old('bio') ?: ($personales ? $personales->bio : '')}}',
                    codeRedeem: '',
                    sexual_orientation_list: ["MAN", "WOMAN", "OTHER"],
                    hair_color_list: ['NEGRO','MORENO','CASTAÑO','RUBIO','PLATINO'],
                    skin_color_list: ['CLARO', 'MORENO','OSCURO'],
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
                    dialogForgot: false,
                    email: '',
                    dialogProfile: false,
                    tipo: 0,
                    paga: true,
                    gratis: false
                }
            },
            mounted() {
            },
            methods: {
                dialog() {
                    console.log('dialog')
                    document.getElementById("editarPerfils").click();
                    // this.editarPersonales = true
                },
            },
            computed: {}
        })
    </script>
@endsection
