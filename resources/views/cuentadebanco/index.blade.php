@extends('layouts.app')

@section('content')

<div class="st-pusher" id="content">
    <div class="st-content">
        <div class="st-content-inner">
            <div id="app" class="container-fluid">
                <div class="panel panel-default">
                    <div class="panel-body">
                        @if (\Session::has('succes'))
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <div> {{ session()->get('   ') }}</div>
                            </div>
                        @endif
                        
                        @if (\Session::has('Error'))
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <div> {{ session()->get('Error')['message'] }}</div>
                            </div>
                        @endif
                
                            <h4 class="page-section-heading">{{__('Agregar cuenta')}}</h4>
                            <div class="credit-card-div">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <form role="form" id="form" action="{{route('nuevo.cuenta')}}" method="POST">
                                            {{ csrf_field() }}
                                            <div class="row">
                                                <input hidden id="tipo" name="tipo" value="0" >
                                                <div class="form-group col-md-6">
                                                    <label
                                                        class="col-md-12">
                                                        {{__('account_number')}}
                                                    </label>
                                                    <input
                                                        id="monto"
                                                        type="text"
                                                        class="form-control"
                                                        name="account_number"
                                                        required>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label
                                                        class="col-md-12 ">
                                                        {{__('traducciones.name')}}
                                                    </label>
                                                    <input
                                                        id="Tiempo"
                                                        type="text"
                                                        class="form-control"
                                                        name="nombre"
                                                        required>
                                                   
                                                </div>
                                                {{-- <div class="form-group col-md-6">
                                                    <label
                                                        class="col-md-12 ">
                                                        {{__('traducciones.intervalo')}}
                                                    </label>
                                                    <select
                                                        id="Intervalo"
                                                        type="text"
                                                        class="form-control"
                                                        name="Intervalo">
                                                        <
                                                        option value="">{{__('traducciones.seleccionar_opcion')}}</option>
                                                        <option value="1">1</option>
                                                        <option value="2">3</option>
                                                        <option value="3">6</option>
                                                    </select>
                                                </div> --}}
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label
                                                        class="col-md-12 ">
                                                        {{__('Pais')}}
                                                    </label>
                                                    <select 
                                                        name="pais" 
                                                        @change="cambiaTexto()"
                                                        v-model="pais"
                                                        aria-invalid="false" 
                                                        id="Select613395" 
                                                        required
                                                        class="form-control" 
                                                        style="color: rgb(60, 66, 87)
                                                        ;">
                                                        <option value="" disabled>{{__('traducciones.seleccionar_opcion')}}</option>
                                                        {{-- <option selected="" value="AL">Albania</option>
                                                        <option value="DE">Alemania</option>
                                                        <option value="AO">Angola</option>
                                                        <option value="AG">Antigua y Barbuda</option>
                                                        <option value="SA">Arabia Saudí</option>
                                                        <option value="DZ">Argelia</option>
                                                        <option value="AR">Argentina</option>
                                                        <option value="AM">Armenia</option>
                                                        <option value="AU">Australia</option>
                                                        <option value="AT">Austria</option>
                                                        <option value="AZ">Azerbaiyán</option>
                                                        <option value="BS">Bahamas</option>
                                                        <option value="BD">Bangladés</option>
                                                        <option value="BE">Bélgica</option>
                                                        <option value="BJ">Benín</option>
                                                        <option value="BO">Bolivia</option>
                                                        <option value="BA">Bosnia y Herzegovina</option>
                                                        <option value="BW">Botsuana</option>
                                                        <option value="BR">Brasil</option>
                                                        <option value="BN">Brunéi</option>
                                                        <option value="BG">Bulgaria</option>
                                                        <option value="BT">Bután</option>
                                                        <option value="KH">Camboya</option>
                                                        <option value="CA">Canadá</option>
                                                        <option value="QA">Catar</option>
                                                        <option value="CL">Chile</option>
                                                        <option value="CY">Chipre</option>
                                                        <option value="CO">Colombia</option>
                                                        <option value="KR">Corea del Sur</option>
                                                        <option value="HR">Croacia</option>
                                                        <option value="DK">Dinamarca</option>
                                                        <option value="EC">Ecuador</option>
                                                        <option value="EG">Egipto</option>
                                                        <option value="SV">El Salvador</option>
                                                        <option value="AE">Emiratos Árabes Unidos</option>
                                                        <option value="SK">Eslovaquia</option>
                                                        <option value="SI">Eslovenia</option>
                                                        <option value="ES">España</option> --}}
                                                        <option value="US">Estados Unidos</option>
                                                        {{-- <option value="EE">Estonia</option>
                                                        <option value="ET">Etiopía</option>
                                                        <option value="PH">Filipinas</option>
                                                        <option value="FI">Finlandia</option>
                                                        <option value="FR">Francia</option>
                                                        <option value="GA">Gabón</option>
                                                        <option value="GM">Gambia</option>
                                                        <option value="GE">Georgia</option>
                                                        <option value="GH">Ghana</option>
                                                        <option value="GI">Gibraltar</option>
                                                        <option value="GR">Grecia</option>
                                                        <option value="GY">Guyana</option>
                                                        <option value="HK">Hong Kong</option>
                                                        <option value="HU">Hungría</option>
                                                        <option value="IN">India</option>
                                                        <option value="ID">Indonesia</option>
                                                        <option value="IE">Irlanda</option>
                                                        <option value="IL">Israel</option>
                                                        <option value="IT">Italia</option>
                                                        <option value="JP">Japón</option>
                                                        <option value="JO">Jordania</option>
                                                        <option value="KZ">Kazajistán</option>
                                                        <option value="KE">Kenia</option>
                                                        <option value="LA">Laos</option>
                                                        <option value="LV">Letonia</option>
                                                        <option value="LI">Liechtenstein</option>
                                                        <option value="LT">Lituania</option>
                                                        <option value="LU">Luxemburgo</option>
                                                        <option value="MK">Macedonia del Norte</option>
                                                        <option value="MG">Madagascar</option>
                                                        <option value="MY">Malasia</option>
                                                        <option value="MT">Malta</option>
                                                        <option value="MA">Marruecos</option>
                                                        <option value="MU">Mauricio</option> --}}
                                                        <option value="MX">México</option>
                                                        {{-- <option value="MD">Moldavia</option>
                                                        <option value="MC">Mónaco</option>
                                                        <option value="MN">Mongolia</option>
                                                        <option value="MZ">Mozambique</option>
                                                        <option value="NA">Namibia</option>
                                                        <option value="NE">Níger</option>
                                                        <option value="NG">Nigeria</option>
                                                        <option value="NO">Noruega</option>
                                                        <option value="NZ">Nueva Zelanda</option>
                                                        <option value="OM">Omán</option>
                                                        <option value="NL">Países Bajos</option>
                                                        <option value="PK">Pakistán</option>
                                                        <option value="PA">Panamá</option>
                                                        <option value="PY">Paraguay</option>
                                                        <option value="PE">Perú</option>
                                                        <option value="PL">Polonia</option>
                                                        <option value="PT">Portugal</option>
                                                        <option value="MO">RAE de Macao (China)</option>
                                                        <option value="GB">Reino Unido</option>
                                                        <option value="CZ">República Checa</option>
                                                        <option value="DO">República Dominicana</option>
                                                        <option value="RW">Ruanda</option>
                                                        <option value="RO">Rumanía</option>
                                                        <option value="LC">Santa Lucía</option>
                                                        <option value="SN">Senegal</option>
                                                        <option value="RS">Serbia</option>
                                                        <option value="SG">Singapur</option>
                                                        <option value="LK">Sri Lanka</option>
                                                        <option value="ZA">Sudáfrica</option>
                                                        <option value="SE">Suecia</option>
                                                        <option value="CH">Suiza</option>
                                                        <option value="TH">Tailandia</option>
                                                        <option value="TW">Taiwán</option>
                                                        <option value="TZ">Tanzania</option>
                                                        <option value="TT">Trinidad y Tobago</option>
                                                        <option value="TR">Turquía</option>
                                                        <option value="UY">Uruguay</option>
                                                        <option value="UZ">Uzbekistán</option>
                                                        <option value="VN">Vietnam</option> --}}
                                                    </select>
                                                </div>
                                                <div v-if="paga" class="form-group col-md-6">
                                                    <label
                                                        class="col-md-12 ">
                                                        {{__('ACH routing number')}}
                                                    </label>
                                                    <input
                                                        id="Tiempo"
                                                        type="text"
                                                        class="form-control"
                                                        name="iban">
                                                   
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row justify-content-center">
                                                <div>
                                                    <button type="submit" class="btn btn-primary">{{__("traducciones.guardar")}}</button>
                                                </div>
                                            </div>
                                            <br>
                                        </form>

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

    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data() {
                return {
                    paga: false,
                    pais: null
                }
            },
            mounted() {
                
            },
            methods: {
                cambiaTexto(){
                    if(this.pais == 'US'){
                        this.paga = true
                    }else{
                        this.paga = false
                    }
                    console.log('modal asdfg')
                    console.log(this.pais)
                }
            },
            computed: {}
        })
    </script>
@endsection


