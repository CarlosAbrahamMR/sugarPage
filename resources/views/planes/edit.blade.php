@extends('layouts.app')

@section('content')

<div class="st-pusher" id="content">
    <div class="st-content">
        <div class="st-content-inner">
            <div id="app" class="container-fluid">
                @if(session('Error'))
                        <div class="alert alert-danger">{{session('Error')['message']}}</div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">{{session('success')}}</div>
                    @endif
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            {{-- <div class="form-group col-md-6">
                                <label class="col-md-12 ">
                                    {{__('traducciones.tipo')}}
                                </label>
                                <select
                                    @change="cambiaTexto()"
                                    v-model="tipo"
                                    placeholder="{{__('traducciones.seleccionar_opcion')}}"
                                    id="tipo"
                                    type="text"
                                    class="form-control"
                                    name="tipo">
                                    <option value="" disabled>{{__('traducciones.seleccionar_opcion')}}</option>
                                    @if(!count($planes))
                                    <option value="0">{{__('paga')}}</option>
                                    <option value="1">{{__('gratis')}}</option>
                                    @elseif($valor == 0)
                                    <option value="0">{{__('paga')}}</option>
                                    @elseif($valor == 0)
                                    <option value="1">{{__('gratis')}}</option>
                                    @endif
                                </select>
                            </div> --}}
                        </div>
                        <div v-if="paga">
                            <form role="form" id="formEditar" action="{{route('edited.plan')}}" method="POST">
                                {{ csrf_field() }}
                                <div class="row">
                                    <input hidden id="tipo" name="tipo" value="0" >
                                    <input hidden id="tipo" name="edit" value="{{$id}}" >
                                    <div class="form-group col-md-6">
                                        <label
                                            class="col-md-12">
                                            {{__('traducciones.monto')}}
                                        </label>
                                        <input
                                            id="monto"
                                            type="text"
                                            class="form-control"
                                            name="monto"
                                            value="{{ $plan['monto'] }}"
                                            required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label
                                            class="col-md-12 ">
                                            {{__('traducciones.tiempo')}}
                                        </label>
                                        <select
                                            id="Tiempo"
                                            type="text"
                                            class="form-control"
                                            name="Tiempo"
                                            v-model="interval"
                                            required>
                                            <option value="">{{__('traducciones.seleccionar_opcion')}}</option>
                                            <option value="1">1 {{__('traducciones.mes')}}</option>
                                            <option value="3"> 3 {{__('traducciones.mes')}}</option>
                                            <option value="6">6 {{__('traducciones.mes')}}</option>
                                            <option value="12">1 {{__('traducciones.anio')}}</option>
                                        </select>
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
                                            <option value="">{{__('traducciones.seleccionar_opcion')}}</option>
                                            <option value="1">1</option>
                                            <option value="2">3</option>
                                            <option value="3">6</option>
                                        </select>
                                    </div> --}}
                                </div>
                                <br>
                                <div class="row justify-content-center">
                                    <div>
                                        <button type="button" @click="redirect()" class="btn btn-primary"><i class="fa fa-arrow-left"></i> {{__("traducciones.regresar")}}</button>
                                        <button @click="eliminarPlan()" type="button" class="btn btn-danger"><i class="fa fa-trash"></i> {{__("traducciones.eliminar")}}</button>
                                        <button type="button" @click="editarPlan()" class="btn btn-success"><i class="fa fa-check"></i> {{__("traducciones.guardar")}}</button>
                                    </div>
                                </div>
                                <br>
                            </form>
                        </div>
                        <div v-if="gratis">
                            <form role="form" id="form" action="{{route('nuevo.plan')}}" method="POST">
                                {{ csrf_field() }}
                                <div class="row">
                                    <input hidden id="tipo" name="tipo" value="1" >
                                    <input hidden id="monto" name="monto" value="0" >
                                    <input hidden id="Tiempo" name="Tiempo" value="0" >
                                    <input hidden id="Intervalo" name="Intervalo" value="0" >

                                </div>
                                <br>
                                <div class="row justify-content-center">
                                    <div>
                                        <button type="submit" class="btn btn-primary">{{__('traducciones.guardar_gratis')}}</button>
                                    </div>
                                </div>
                                <br>
                            </form>
                        </div>
                    </div>
                </div>
                <div>
                    <form role="form" id="formDelete" action="{{route('delete.plan',$id)}}" method="POST">
                        {{ csrf_field() }}
                    </form>
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
</div>
@endsection
@section('scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data() {
                return {
                    tipo: 0,
                    paga: true,
                    gratis: false,
                    overlay: false,
                    interval: '{{ $plan['interval_count'] }}'
                }
            },
            mounted() {

            },
            methods: {
                cambiaTexto(){
                    if(this.tipo == 0){
                        this.gratis= false
                        this.paga=true
                    }else if(this.tipo == 1){
                        this.paga=false
                        this.gratis= true
                    }
                    console.log('modal asdfg')
                    console.log(this.tipo)
                },
                eliminarPlan(){
                    swal({
                        title: "Confirmacion",
                        text: "¿Seguro que desea eliminar este plan?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                        .then((comprar) => {
                            if (comprar) {
                                this.overlay = true
                                document.getElementById('formDelete').submit();
                                return false;
                            }
                        });

                },
                editarPlan(){
                    swal({
                        title: "Confirmacion",
                        text: "¿Seguro que desea editar este plan?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                        .then((comprar) => {
                            if (comprar) {
                                this.overlay = true
                                document.getElementById('formEditar').submit();
                                return false;
                            }
                        });

                },
                redirect(){
                    window.location.href = '/plan/create'
                }
            },
            computed: {}
        })
    </script>
@endsection
