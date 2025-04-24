@extends('layouts.admin')
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.css">
@endsection
@section('content')
    <div class="st-pusher" id="content">
        <div class="st-content">
            <div class="st-content-inner">
                <div id="app" class="container-fluid">
                    <div class="panel panel-default">
                        <v-card>
                            <v-card-title>
                                {{__("traducciones.crear_nuevo")}}
                            </v-card-title>
                            @if(session('error'))
                                <div class="alert alert-warning">{{session('error')}}</div>
                            @endif
                            <div class="panel-body">
                                <form action="/save-image-offer" class="dropzone" id="my-awesome-dropzone" style="border:1px;">
                                    {{ csrf_field() }}
                                    <div class="dz-default dz-message">
                                        <p class="text-muted">{{__("traducciones.subir_foto")}}</p>
                                    </div>
                                </form>
                                <form action="/update-product/{{$product['id']}}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="text" hidden name="id" value="{{$product['id']}}">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label
                                                class="col-md-12">
                                                {{__("traducciones.titulo")}}
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                                <input
                                                    v-model="nombre"
                                                    id="nombre"
                                                    type="text"
                                                    class="form-control"
                                                    name="nombre">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label
                                                class="col-md-12">
                                                {{__("traducciones.tipo")}}
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                                <select
                                                    @change="sabertipo()"
                                                    v-model="tiporecompensa"
                                                    id="tiporecompensa"
                                                    type="text"
                                                    class="form-control"
                                                    name="tiporecompensa">
                                                    <option value="" disabled>{{__('traducciones.seleccionar_opcion')}}</option>
                                                    <option value="0">{{__('Livechat')}}</option>
                                                    <option value="1">{{__('otro')}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label
                                                class="col-md-12">
                                                {{__("traducciones.price")}}
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                                <input
                                                    v-model="price"
                                                    id="price"
                                                    type="number"
                                                    class="form-control"
                                                    name="price">
                                            </div>
                                        </div>
                                        <div v-if="!chat" class="form-group col-md-4">
                                            <label
                                                class="col-md-12">
                                                {{__("traducciones.cantidad")}}
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="md-add-shopping-cart"></i></span>
                                                <input
                                                    v-model="cantidad_disponible"
                                                    id="cantidad_disponible"
                                                    type="number"
                                                    class="form-control"
                                                    name="cantidad_disponible">
                                            </div>
                                        </div>
                                        <div v-else class="form-group col-md-4">
                                            <label
                                                class="col-md-12">
                                                {{__("traducciones.cantidad")}}
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                                <select
                                                    v-model="cantidad_participantes"
                                                    id="cantidad_participantes"
                                                    type="text"
                                                    class="form-control"
                                                    name="cantidad_participantes">
                                                    <option value="" disabled>{{__('traducciones.seleccionar_opcion')}}</option>
                                                    <option value="1">{{__('1 a 1')}}</option>
                                                    <option value="ILIMITADO">{{__('Multiparticipante')}}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div v-if="chat" class="form-group col-md-6">
                                            <label
                                                class="col-md-12">
                                                {{__("Fecha Evento")}}
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input v-model="fecha_evento" type="date" class="form-control" name="Fecha_recompensa" id="Fecha_recompensa">
                                            </div>
                                        </div>
                                        <div v-if="chat" class="form-group col-md-6">
                                            <label
                                                class="col-md-12">
                                                {{__("Link")}}
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input v-model="link_livechat" type="text" placeholder="Agrega el link del livechat" class="form-control" name="link_livechat" id="link_livechat">
                                            </div>
                                        </div>
                                        <div v-if="recompensa" class="form-group col-md-4">
                                            <label
                                                class="col-md-12">
                                                {{__("Rewards")}}
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                <input v-model="recompensa_desc" type="text" class="form-control" name="recompensa" id="exampleInputFirstName" placeholder="Rewards description" required>
                                            </div>
                                        </div>

                                        <div v-if="recompensa" class="form-group col-md-4">
                                            <label
                                                class="col-md-12">
                                                fecha recompensa
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
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
                                                          name="descripcion" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <button @click="redirect()" type="button" class="btn btn-danger"><i class="fa fa-times"></i> {{__("traducciones.cancelar")}}</button>
                                    <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> {{__("traducciones.guardar")}}</button>
                                </form>
                            </div>
                        </v-card>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.js"></script>
    <script src="http://momentjs.com/downloads/moment.min.js"></script>
    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data() {
                return {
                    nombre: '{{$product['nombre']}}',
                    price:'{{$product['precio']}}',
                    cantidad_disponible:'{{$product['cantidad_disponible']}}',
                    descripcion:'{{$product['descripcion']}}',
                    tipo: '{{$product['tipo']}}',
                    recompensa: false,
                    chat: false,
                    fechaRecompensa:'',
                    imagenrecompensa: false,
                    tiporecompensa: '{{$product['tipo']}}',
                    cantidad_participantes: '{{$product['cantidad_participantes']}}',
                    recompensa_desc: '{{$product['recompensa_desc']}}',
                    fecha_evento: '{{$product['fecha_evento']}}',
                    link_livechat: '{{$product['link_livechat']}}',
                }
            },
            mounted() {
                var fecha = '{{$product['created_at']}}'
                var fechaUpdated = '{{$product['updated_at']}}'
                var fecha_recompensa = '{{$product['fecha_recompensa']}}'

                var created_at = moment(fecha.slice(0, -9));
                var updated_at = moment(fechaUpdated.slice(0, -9));

                var diferencia = updated_at.diff(created_at, 'days')

                var fechaInicio = diferencia > 0 ? updated_at : created_at;
                var fechaFin = moment(fecha_recompensa)
                this.fechaRecompensa = fechaFin.diff(fechaInicio, 'days')

                if(this.tiporecompensa == 1 || this.tiporecompensa == 0){
                    this.sabertipo()
                }
            },
            methods: {
                save(){
                    axios.post(`/create-product`,{
                        texto: this.actualizacionEstatus
                    })
                        .then(response => {
                            if (response.data.estatus) {
                                location.reload();
                            }
                        }).catch(error => {
                        console.log(error);
                    });
                },
                redirect() {
                    window.location.href = '/products'
                },
                fecharec(){
                    if(this.fechaRecompensa == 0){
                        this.imagenrecompensa = true
                    }else{
                        this.imagenrecompensa = false
                    }
                },
                sabertipo(){
                    //0 es chat  1 otro
                    if(this.tiporecompensa == 1){
                        this.recompensa = true
                        this.chat = false
                        this.imagenrecompensa = false
                    }else if(this.tiporecompensa == 0){
                        this.recompensa = false
                        this.chat = true
                        this.imagenrecompensa = false
                    }
                },
            },
            computed: {}
        })
    </script>
@endsection
