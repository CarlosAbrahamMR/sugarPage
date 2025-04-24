@extends('layouts.admin')
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
                                        <v-col
                                            cols="12"
                                            sm="12"
                                            md="12"
                                        >
                                            <v-btn block outlined color="indigo" @click="redirect('create')">
                                                <v-icon left>
                                                    mdi-plus
                                                </v-icon>
                                                {{__("traducciones.crear_nuevo")}}
                                            </v-btn>
                                        </v-col>
                                        @foreach($productos as $product)
                                            <v-col
                                                cols="12"
                                                sm="6"
                                                md="3"
                                            >
                                                <v-hover v-slot="{ hover }">
                                                    <v-card
                                                        :loading="loading"
                                                        class="mx-auto my-2"
                                                        :elevation="hover ? 12 : 2"
                                                        :class="{ 'on-hover': hover }"
                                                    >
                                                        <template slot="progress">
                                                            <v-progress-linear
                                                                color="deep-purple"
                                                                height="10"
                                                                indeterminate
                                                            ></v-progress-linear>
                                                        </template>
                                                        @if(count($product->imagenes) > 1)
                                                            <v-carousel hide-delimiters height="30%">
                                                                @foreach($product->imagenes as $imagen)
                                                                    <v-carousel-item
                                                                        src="{{url('storage'.$imagen->path . $imagen->nombre)}}"
                                                                    ></v-carousel-item>
                                                                @endforeach
                                                            </v-carousel>
                                                        @else
                                                            <v-img
                                                                height="250"
                                                                src="{{url('storage'.$product->imagenes[0]->path.$product->imagenes[0]->nombre)}}"
                                                            ></v-img>
                                                        @endif

                                                        <v-card-title>{{$product['nombre']}} • ${{$product['precio']}}</v-card-title>
                                                        <v-card-text>
                                                            <div class="my-2 text-subtitle-1">
                                                                Disponibles: {{$product['cantidad_disponible'] == 0 ? 'MULTIPARTICIPANTE' : $product['cantidad_disponible']}}<br>
                                                                Vendidos: {{$product['cantidad_vendida']}}
                                                            </div>
                                                            <div>{{strlen($product['descripcion']) > 40 ? substr($product['descripcion'], 0, 40) . '...' : $product['descripcion']}}</div>
                                                        </v-card-text>
                                                        <v-divider class="mx-2"></v-divider>
                                                        <v-card-actions>
                                                            <v-spacer></v-spacer>
                                                            <v-tooltip bottom>
                                                                <template v-slot:activator="{ on, attrs }">
                                                                    <v-btn @click="eliminar({{$product['id']}})" icon>
                                                                        <v-icon>mdi-delete</v-icon>
                                                                    </v-btn>
                                                                </template>
                                                                <span>{{__("traducciones.eliminar")}}</span>
                                                            </v-tooltip>
                                                            <v-tooltip bottom>
                                                                <template v-slot:activator="{ on, attrs }">
                                                                    <v-btn @click="redirect('view-{{$product->id}}')"
                                                                           icon>
                                                                        <v-icon>mdi-eye</v-icon>
                                                                    </v-btn>
                                                                </template>
                                                                <span>{{__("traducciones.ver")}}</span>
                                                            </v-tooltip>
                                                        </v-card-actions>
                                                    </v-card>
                                                </v-hover>
                                            </v-col>
                                        @endforeach
                                    </v-container>
                                </v-row>
                            </v-main>
                        </v-app>
                    </div>
                </div>
            </div>
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
                    loading: false,
                    selection: 1,
                    icons: ['mdi-rewind', 'mdi-play', 'mdi-fast-forward'],
                    transparent: 'rgba(255, 255, 255, 0)',
                }
            },
            mounted() {
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
                    swal({
                        title: "Confirmacion",
                        text: "¿Desea eliminar esta oferta?'",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then(eliminar => {
                        if(eliminar){
                            axios.delete('/delete-product/' + id).then(() => {
                                window.location.reload()
                            })
                        }
                    })
                }
            },
            computed: {}
        })
    </script>
@endsection
