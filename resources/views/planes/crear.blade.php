@extends('layouts.app')

@section('content')

<div class="st-pusher" id="content">
    <div class="st-content">
        <div class="st-content-inner">
            <div id="app" class="container-fluid">
                @if(session('Error'))
                        <div class="alert alert-danger">{{session('Error')}}</div>
                    @endif
                @if(session('success'))
                    <div class="alert alert-success">{{session('success')}}</div>
                @endif
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="col-md-12 ">
                                    {{__('traducciones.tipo')}}
                                </label>
                                <select
                                    @change="cambiaTexto()"
                                    v-model="tipo"
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
                            </div>
                        </div>
                        <div v-if="paga">
                            <form role="form" id="form" action="{{route('nuevo.plan')}}" method="POST">
                                {{ csrf_field() }}
                                <div class="row">
                                    <input hidden id="tipo" name="tipo" value="0" >
                                    <div class="form-group col-md-6">
                                        <label
                                            class="col-md-12">
                                            {{__('traducciones.monto')}}
                                        </label>
                                        <input
                                            value="{{ old('monto') }}"
                                            id="monto"
                                            type="text"
                                            class="form-control"
                                            name="monto"
                                            required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label
                                            class="col-md-12 ">
                                            {{__('traducciones.tiempo')}}
                                        </label>
                                        <select
                                            v-model="Tiempo"
                                            id="Tiempo"
                                            type="text"
                                            class="form-control"
                                            name="Tiempo">
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
                                    <div class="form-group col-md-12">
                                        <div class="checkbox checkbox-primary">
                                            <input id="checkbox2" type="checkbox" name="autorizo_marketing" @if(Auth::user()->autorizo_marketing) checked @endif>
                                            <label for="checkbox2">{{__('traducciones.autorizo_marketing')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row justify-content-center">
                                    <div>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> {{__("traducciones.guardar")}}</button>
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
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> {{__('traducciones.guardar_gratis')}}</button>
                                    </div>
                                </div>
                                <br>
                            </form>
                        </div>
                    </div>
                  </div>
                  <h4 class="page-section-heading"></h4>
                  <div class="table-responsive">

                    <!-- Pricing table -->
                    <table class="table panel panel-default table-pricing table-pricing-2">

                      <!-- Table heading -->
                      <thead>
                        <tr>

                        </tr>
                      </thead>
                      <!-- // Table heading END -->

                      <!-- Table body -->
                      <tbody>

                        <!-- Table pricing row -->
                        <tr class="pricing">
                            @foreach($planes as $plan)
                                <td class="text-center">
                                    <span class="price">${{$plan->monto}}</span>
                                    <span>{{$plan->interval_count.' '.$plan->intervalo}}</span>
                                    <br>
                                    <a href="{{route('edit.plan',$plan->id)}}" class="btn btn-primary">Edit</a>
                                </td>

                            @endforeach
                        </tr>
                        <!-- // Table pricing row END -->

                        <!-- Table row -->
                        <!-- // Table row END -->

                      </tbody>
                      <!-- // Table body END -->

                    </table>
                    <!-- // Pricing table END -->

                  </div>



            </div>

        </div>

    </div>

</div>



@endsection
@section('scripts')

    <script>
        // $('#tabla').DataTable( {
        //     searching: false
        // } );
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data() {
                return {
                    tipo: '{{$valor}}',
                    paga: '{{$planpaga}}',
                    gratis: '{{$plangratis}}',
                    Tiempo: '{{ old('Tiempo') ?: '' }}',
                    monto: '{{ old('monto') ?: '' }}'
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
                }
            },
            computed: {}
        })
    </script>
@endsection
