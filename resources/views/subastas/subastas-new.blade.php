@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.css">
@endsection
@section('content')

<div class="st-pusher" id="content">
    <div class="st-content">
        <div class="st-content-inner">
            <div id="wrapper" class="container-fluid">
                <div class="panel panel-default">
                    <div class="panel-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error )
                            <li>{{$error}}</li>

                            @endforeach
                        </ul>
                        </div>
                    @endif
                    @if (session('Error'))
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <div> {{ isset(session()->get('Error')['message']) ?: session()->get('Error') }}</div>
                            </div>
                        @endif
                    @if(session("mensaje") && session("tipo"))
                        <div class="notification is-{{session('tipo')}}">
                            {{session('mensaje')}}
                        </div>
                    @endif
                    <div class="panel-body">
                    @php
                      $random=rand(0, 10).rand(0, 10).rand(0, 10).rand(0, 10).rand(0, 10);
                    @endphp
                      <form v-if="!imagenrecompensa" action="/auctions-save-image" class="dropzone" id="my-awesome-dropzone" style="border:0;">
                          {{ csrf_field() }}
                          <input type="hidden" name="subasta" value="{{$random}}" required>
                              <div class="dz-default dz-message">
                                  <p class="text-muted">Sube una imagen</p>
                              </div>
                          </form>
                      </div>
                      <form class="" role="form" id="form" action="{{route('auctions.create')}}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="subasta" value="{{$random}}">
                        <input type="hidden" name="tiporecompensa" v-model="tiporecompensa">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label
                                    class="col-md-12">
                                    Titulo
                                </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                    <input value="{{ old('name') }}" type="text" class="form-control" name="name" id="exampleInputFirstName" placeholder="Your first name" required>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label
                                    class="col-md-12">
                                    {{__("traducciones.price")}}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                    <input value="{{ old('price') ?: '10' }}" id="touch-spin-2" data-toggle="touch-spin" name="price" data-min="10" data-max="1000" data-prefix="$" data-postfix="dllrs" data-step="1" type="text" class="form-control" required />
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label
                                    class="col-md-12">
                                    Fecha Inicio
                                </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class=""></i></span>
                                    <input value="{{ old('Fecha_inicio') }}" type="date"  class="form-control" name="Fecha_inicio" id="exampleInputFirstName" placeholder="Your first name" required>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    class="col-md-12">
                                    Fecha Fin
                                </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class=""></i></span>
                                    <input value="{{ old('Fecha_fin') }}" type="date" name="Fecha_fin" id="exampleInputFirstName" placeholder="Your first name" class="form-control" required />
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label
                                    class="col-md-12">
                                    {{__("traducciones.descripcion")}}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-comment"></i></span>
                                    <textarea v-model="descripcion"
                                        id="descripcion"
                                        type="text"
                                        class="form-control"
                                        name="descripcion" rows="5">
                                    </textarea>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label
                                    class="col-md-12">
                                    {{__("traducciones.tipo")}}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                    <select
                                    @change="sabertipo()"
                                    v-model="tipo"
                                    id="tipo"
                                    type="text"
                                    class="form-control"
                                    name="tipo">
                                    <option value="" disabled>{{__('traducciones.seleccionar_opcion')}}</option>
                                    <option value="0">{{__('Livechat')}}</option>
                                    <option value="1">{{__('otro')}}</option>

                                </select>
                                </div>
                            </div>

                            <div v-if="recompensa" class="form-group col-md-6">
                                <label
                                    class="col-md-12">
                                    {{__("Rewards")}}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                    <input value="{{ old('recompensa') }}" type="text" class="form-control" name="recompensa" id="exampleInputFirstName" placeholder="Recompensa" required>
                                </div>
                            </div>

                            <div v-if="recompensa" class="form-group col-md-6">
                                <label
                                    class="col-md-12">
                                    fecha recompensa
                                </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                    <select
                                    @change="fecharec()"
                                    v-model="fechaRecompensa"
                                    id="tipo"
                                    type="text"
                                    class="form-control"
                                    name="fechaRecompensa">
                                    <option value="" disabled>{{__('traducciones.seleccionar_opcion')}}</option>
                                    <option value="0">inmediato</option>
                                    <option value="3">3 dias</option>
                                    <option value="5">5 dias</option>
                                    <option value="10">10 dias</option>

                                </select>
                                </div>
                            </div>
                            <div v-if="chat" class="form-group col-md-12">
                                <label
                                    class="col-md-12">
                                    Fecha del evento
                                </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class=""></i></span>
                                    <input value="{{ old('Fecha_recompensa') }}" type="date" name="Fecha_recompensa" id="exampleInputFirstName" placeholder="Your first name" class="form-control" required />
                                </div>
                            </div>

                        <div class="col-md-12">
                            <div class="form-group">
                              <button type="submit" class="btn btn-primary">Submit</button>
                             </div>
                        </div>
                      </form>

                        {{-- <div class="col-md-12">
                            <div class="form-group">
                              <button @click="guardar()" type="submit" class="btn btn-primary">Submit</button>
                             </div>
                        </div> --}}
                    </div>
                  </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.js"></script>
    <script type="application/javascript">
    Dropzone.on  = {
            paramName: "file",
            maxFilesize: 2
        };
        new Vue({
            el: '#wrapper',
            vuetify: new Vuetify(),
            data() {
                return {
                    tipo:'{{ old('tipo') ?: '' }}',
                    recompensa: false,
                    chat: false,
                    fechaRecompensa:'{{ old('fechaRecompensa') ?: '' }}',
                    imagenrecompensa: false,
                    descripcion: '{{ old('descripcion') ?: '' }}',
                    tiporecompensa: '{{ old('tiporecompensa') ?: '' }}',
                    fechaEvento: '{{ old('Fecha_recompensa') ?: '' }}'
                }
            },
            mounted() {
                if(this.tipo !== ''){
                    this.sabertipo()
                } else if(this.fechaEvento){
                    this.tipo = 0
                    this.sabertipo()
                }
            },
            methods: {
                sabertipo(){
                    //0 es chat  1 otro
                    console.log(this.tipo)
                    if(this.tipo == 1){
                        this.tiporecompensa= 1
                        this.recompensa = true
                        this.chat = false
                        this.imagenrecompensa = false
                    }else if(this.tipo == 0){
                        this.tiporecompensa= 0
                        this.recompensa = false
                        this.chat = true
                        this.imagenrecompensa = false
                    }
                },
                fecharec(){
                    if(this.fechaRecompensa == 0){
                        this.imagenrecompensa = true
                    }else{
                        this.imagenrecompensa = false
                    }
                    console.log(this.fechaRecompensa)
                },
                guardar (){
                    document.getElementById('form').submit()
                }
            },
            computed: {}
        })
    </script>
@endsection
