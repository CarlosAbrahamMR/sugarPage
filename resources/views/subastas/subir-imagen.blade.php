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
                                <div> {{ session()->get('Error')['message'] }}</div>
                            </div>
                        @endif
                    @if(session("mensaje") && session("tipo"))
                        <div class="notification is-{{session('tipo')}}">
                            {{session('mensaje')}}
                        </div>
                    @endif
                    <div class="panel-body">
                    @php
                      $random=rand(0, 10).rand(0, 10).rand(0, 10).rand(0, 10).rand(0, 10).rand(0, 10).rand(0, 10).rand(0, 10).rand(0, 10).rand(0, 10);
                    @endphp
                      <div class="panel-body">
                        <form action="/auctions-save-image-recompensa" class="dropzone" id="my-great-dropzone" style="border:0;">
                          {{ csrf_field() }}
                          <input type="hidden" name="subasta" value="{{$recompensa}}">
                              <div class="dz-default dz-message">
                                  <p class="text-muted">Sube una imagen</p>
                              </div>
                          </form>
                        </div>

                        {{-- <div class="col-md-12">
                            <div class="form-group">
                              <button @click="guardar()" type="submit" class="btn btn-primary">Submit</button>
                             </div>
                        </div> --}}
                    </div>
                    <div class="col-md-12">
                        <form action="{{route('auctions.validar',$recompensa)}}" >
                            <div class="form-group">
                              <button type="submit" class="btn btn-primary">Listo</button>
                             </div>
                        </form>     
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
                    tipo:'',
                    recompensa: false,
                    chat: false,
                    fechaRecompensa:'',
                    imagenrecompensa: false,
                    descripcion: '',
                    tiporecompensa: ''
                }
            },
            mounted() {
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
