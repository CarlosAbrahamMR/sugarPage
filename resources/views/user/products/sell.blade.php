@extends('layouts.admin')
@section('styles')
    <style>
        .v-card--reveal {
            align-items: center;
            bottom: 0;
            justify-content: center;
            opacity: .8;
            position: absolute;
            width: 100%;
        }
    </style>
@endsection
@section('content')
    <div class="st-pusher" id="content">
        <div class="st-content">
            <div class="st-content-inner">
                <div id="app" class="container-fluid">
                    <div class="panel panel-default">
                        <v-app>
                            <v-main>
                                <v-row>
                                    <v-container>
                                        @foreach($productos as $product)
                                            <v-col
                                                cols="12"
                                                sm="6"
                                                md="3"
                                            >
                                                <v-card
                                                    :loading="loading"
                                                    class="mx-auto my-12"
                                                    max-width="374"
                                                >
                                                    <template slot="progress">
                                                        <v-progress-linear
                                                            color="deep-purple"
                                                            height="10"
                                                            indeterminate
                                                        ></v-progress-linear>
                                                    </template>

                                                    <v-img
                                                        height="250"
                                                        src="{{count($product->imagenes) > 0 ? url('storage'.$product->imagenes[0]->path . $product->imagenes[0]->nombre) : ''}}"
                                                    ></v-img>

                                                    <v-card-title>{{$product['nombre']}}</v-card-title>

                                                    <v-card-text>
                                                        <v-row
                                                            align="center"
                                                            class="mx-0"
                                                        >
                                                            <v-rating
                                                                :value="{{round($product['resenias'], 1)}}"
                                                                color="amber"
                                                                dense
                                                                half-increments
                                                                readonly
                                                                size="14"
                                                            ></v-rating>

                                                            <div class="grey--text ms-4">
                                                                <a href="#" type="button" @click="consultarComentarios({{$product}})" aria-label="Ver comentarios">{{round($product['resenias'], 1)}}  • ({{$product['cantidad']}}) {{__('traducciones.resenias')}}</a>
                                                            </div>
                                                        </v-row>

                                                        <div class="my-4 text-subtitle-1">
                                                            ${{$product->precio}} USD
                                                        </div>

                                                        <div>{{$product['descripcion']}}</div>
                                                    </v-card-text>

                                                    <v-divider class="mx-4"></v-divider>
                                                        <v-card-title>{{__("traducciones.disponible")}}: {{$product->tipo == 0 && $product->cantidad_disponible == 0 && $product->cantidad_participantes != 1 ? 'LIMITADO' : ($product->cantidad_disponible == 0 ? __('traducciones.agotado') : $product->cantidad_disponible)}}</v-card-title>

                                                        <v-card-actions>
                                                            <v-btn
                                                                color="deep-purple lighten-2"
                                                                text
                                                                @click="comprar({{$product}})"
                                                            >
                                                                {{__('traducciones.comprar')}}
                                                            </v-btn>
                                                        </v-card-actions>
                                                    </v-card>
                                                </v-col>
                                            @endforeach
                                                <v-dialog
                                                    v-model="dialogPay"
                                                    persistent
                                                    max-width="600px"
                                                >
                                                    <v-card>
                                                        <v-card-title>
                                                            <span class="text-h5">Comprar producto</span>
                                                        </v-card-title>
                                                        <form action="{{route('comprar-producto')}}" method="POST">
                                                            {{ csrf_field() }}
                                                            <v-card-text>
                                                                <v-row>
                                                                    <v-col
                                                                        cols="12"
                                                                        sm="12"
                                                                        md="12"
                                                                    >
                                                                        <div class="form-group col-md-12">
                                                                            <label
                                                                                class="col-md-12">
                                                                                {{__("traducciones.cantidad")}}
                                                                            </label>
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                                                                <input id="touch-spin-2" name="cantidad" min="1" :max="disponible" type="number" value="1" class="form-control" required />
                                                                                <input hidden type="text" name="producto_id" :value="productoid">
                                                                                <input hidden type="text" name="creador_username" value="{{$creador->username}}">
                                                                                <input hidden type="text" name="tipoRecompensa" :value="tipoRecompensa">
                                                                            </div>
                                                                        </div>
                                                                    </v-col>
                                                                </v-row>
                                                            </v-card-text>
                                                            <v-card-actions>
                                                                <v-spacer></v-spacer>
                                                                <button @click="dialogPay = false" type="button" class="btn btn-danger"><i class="fa fa-times"></i> {{__("traducciones.cancelar")}}</button>
                                                                <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> {{__("traducciones.comprar")}}</button>
                                                            </v-card-actions>
                                                        </form>
                                                    </v-card>
                                                </v-dialog>
                                                <v-dialog
                                                    v-model="ver_comentarios"
                                                    max-width="800px"
                                                >
                                                    <v-card>
                                                        <v-toolbar
                                                            color="primary"
                                                            dark
                                                        >
                                                            Valoración de otros usuarios &nbsp;&nbsp;
                                                            <v-rating
                                                                :value="valoracionSelected"
                                                                color="amber"
                                                                dense
                                                                half-increments
                                                                readonly
                                                                size="14"
                                                            ></v-rating>&nbsp;&nbsp;
                                                            @{{ valoracionSelected }}  • (@{{ cantidadSelected }}) {{__('traducciones.resenias')}}</v-toolbar>
                                                        <v-card-text>
                                                            <v-timeline>
                                                                <v-timeline-item
                                                                    v-for="comentario in comentarios"
                                                                    :key="comentario.id"
                                                                    large
                                                                >
                                                                    <template v-if="comentario.path_imagen_perfil" v-slot:icon>
                                                                        <v-avatar>
                                                                            <img :src="'storage'+comentario.path_imagen_perfil">
                                                                        </v-avatar>
                                                                    </template>
                                                                    <template v-slot:opposite>
                                                                        <span>@{{ new Date(comentario.fecha).toLocaleString(undefined, { year: 'numeric', month: 'long' }) }}</span>
                                                                    </template>
                                                                    <v-card class="elevation-2">
                                                                        <v-card-title class="text-h5">
                                                                            @{{ comentario.name }}
                                                                        </v-card-title>
                                                                        <v-card-text>@{{ comentario.comentario }}</v-card-text>
                                                                    </v-card>
                                                                </v-timeline-item>
                                                            </v-timeline>
                                                        </v-card-text>
                                                        <v-card-actions class="justify-end">
                                                            <v-btn
                                                                text
                                                                @click="ver_comentarios = false"
                                                            >Close</v-btn>
                                                        </v-card-actions>
                                                    </v-card>
                                                </v-dialog>
                                        </v-container>
                                    </v-row>
                                </v-main>
                            </v-app>
                        </div>
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
    @endsection
    @section('scripts')
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            new Vue({
                el: '#app',
                vuetify: new Vuetify(),
                data() {
                    return {
                        loading: false,
                        selection: 1,
                        icons: ['mdi-rewind', 'mdi-play', 'mdi-fast-forward'],
                        transparent: 'rgba(255, 255, 255, 0)',
                        dialogPay: false,
                        disponible: 0,
                        productoid: '',
                        disabledCompra: '{{isset($product) ? (intval($product->cantidad_disponible) !== 0 ? true : false) : false}}',
                        ver_comentarios: false,
                        comentarios: [],
                        valoracionSelected: '',
                        cantidadSelected: '',
                        tipoRecompensa: '',
                        disabled: false
                    }
                },
                mounted() {
                    this.disabled = '{{$product->tipo == 0 && $product->cantidad_disponible == 0 && $product->cantidad_participantes != 1}}'
                    this.disabled = this.disabled == 1 ? true : false
                },
                methods: {
                    reserve() {
                        this.loading = true

                        setTimeout(() => (this.loading = false), 2000)
                    },
                    redirect(action) {
                        console.log(action)
                        if (action === 'create') {
                            window.location.href = 'create-products'
                        }
                        if (action.includes('view')) {
                            let actionExplode = action.split('-')
                            window.location.href = 'view-product/' + actionExplode[1]
                        }
                        if (action === 'edit') {
                            window.location.href = 'view-product'
                        }
                        if (action == 'delete') {
                            window.location.href = 'view-product'
                        }
                    },
                    eliminar(id) {
                        axios.delete('/delete-product/' + id).then(() => {
                            window.location.reload()
                        })
                    },
                    comprar(producto){
                        if(!(producto.tipo == 0 && producto.cantidad_disponible == 0 && producto.cantidad_participantes != 1)){
                            swal({
                                title: "Advertencia",
                                text: "Producto Agotado",
                                icon: "warning",
                            }).then(eliminar => {

                            })
                            console.log('agotado')
                        }else {
                            this.dialogPay = true
                            this.disponible = producto.tipo == 0 ? 1 : producto.cantidad_disponible
                            this.productoid = producto.id
                            this.tiporecompensa = producto.tipo
                            console.log(this.disponible)
                        }
                    },
                    consultarComentarios(recompensa){
                        let idRecompensa = recompensa.recompensas_id
                        this.valoracionSelected = parseFloat((Math.round(recompensa.resenias * 100) / 100).toFixed(1))
                        this.cantidadSelected = recompensa.cantidad
                        axios.get('/get-comentarios-recompensas/' + idRecompensa).then((resp) => {
                            this.comentarios = resp.data.comentarios
                            this.ver_comentarios = true
                            console.log(resp.data.comentarios)
                        })
                    }
                },
                computed: {}
            })
        </script>
    @endsection
