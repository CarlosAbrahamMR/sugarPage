@extends('layouts.app')
@section('content')
<div class="st-pusher" id="content">
    <div class="st-content">
        <div class="st-content-inner">
            <div id="app" class="container-fluid">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <v-app>
                            <template>
                                <v-card
                                    elevation="24"

                                    class="mx-auto"
                                >
                                    <v-system-bar lights-out></v-system-bar>
                                    <v-carousel
                                        :continuous="false"
                                        :cycle="cycle"
                                        :show-arrows="false"
                                        hide-delimiter-background
                                        delimiter-icon="mdi-minus"
                                        height="300"
                                    >
                                        @foreach ($imagenes as $imagen )

                                            <v-carousel-item
                                                src="{{url('storage'.$imagen['src'])}}"
                                                reverse-transition="fade-transition"
                                                transition="fade-transition"
                                            ></v-carousel-item>
                                        @endforeach
                                        <v-sheet
                                            :color="colors[i]"
                                            height="100%"
                                            tile
                                        >
                                            <v-row
                                                class="fill-height"
                                                align="center"
                                                justify="center"
                                            >
                                                <div class="text-h2">
                                                    Slide
                                                </div>
                                            </v-row>
                                        </v-sheet>
                                    </v-carousel>
                                    <v-list two-line>
                                        <v-list-item>


                                            <v-list-item-content>
                                                <v-list-item-title>Titulo: {{$subasta->nombre}}</v-list-item-title>
                                                <v-list-item-title>Descricpcion: {{$subasta->descripcion}}</v-list-item-title>
                                                <v-list-item-title>Precio salida
                                                    ${{$subasta->precio_inicial}}</v-list-item-title>
                                            </v-list-item-content>
                                            <v-list-item-action>
                                                <v-list-item-title>Fecha inicio: {{date_format(new \DateTime($subasta->fecha_inicio),'Y/m/d')}}</v-list-item-title>
                                                <v-list-item-title>Fecha de fin: {{date_format(new \DateTime($subasta->fecha_fin),'Y/m/d')}}</v-list-item-title>

                                            </v-list-item-action>
                                        </v-list-item>
                                    </v-list>
                                </v-card>


                            </template>
                            <template>
                                <div class="text-center">
                                    @if($ofertas)
                                        <v-chip
                                            class="ma-2"
                                            label
                                            color="primary"
                                        >
                                        <v-icon left>
                                            mdi-cash-100
                                        </v-icon>
                                            Mejor Oferta : {{$ofertas->precio}} USD
                                        </v-chip>
                                    @endif
                                  @if($ganando)
                                  <v-chip
                                    class="ma-2"
                                    color="primary"
                                    label
                                  >
                                    <v-icon left>
                                      mdi-account-circle-outline
                                    </v-icon>
                                    Felicidades va ganando tu oferta
                                  </v-chip>
                                  @endif
                                  @if($ofertar)
                                  <v-chip
                                    class="ma-2"
                                    color="primary"
                                    label
                                    text-color="white"
                                  >
                                    <v-icon left>
                                      mdi-calendar-clock
                                    </v-icon>
                                    Mese: {{$meses}} Dias: {{$dias}} Horas: {{$hora}}
                                  </v-chip>
                                  @else
                                  <v-chip
                                    class="ma-2"
                                    color="primary"
                                    label
                                    text-color="white"
                                  >
                                    <v-icon left>
                                      mdi-calendar-clock
                                    </v-icon>
                                    Ya termino la subasta
                                  </v-chip>
                                  @endif
                                </div>
                              </template>
                            <br>
                            <br>

                            <div class="row">
                                <div class="col-md-12">
                                    @if($ofertar)
                                        <form class="" role="form" id="formOfertar"
                                              action="{{route('auctions.ofertar',$subasta->id)}}" method="POST">
                                            {{ csrf_field() }}

                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-2 control-label"></label>
                                                <div class="col-sm-6">
                                                    <input type="text" name="precio" class="form-control"
                                                           id="inputEmail3" placeholder="New offer">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <button type="button" @click="ofertar()" class="btn btn-primary">Lanzar oferta</button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <br>
                            <br>
                            {{-- <div class="table-responsive">
                                <table data-toggle="data-table" class="table" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>oferta</th>
                                        <th>fecha</th>
                                        <th>precio</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>oferta</th>
                                        <th>fecha</th>
                                        <th>precio</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>
                                    @foreach ($ofertas as $oferta)
                                        <tr>
                                            <td>{{$oferta->id}}</td>
                                            <td>{{date_format(new \DateTime($oferta->created_at),'Y/m/d')}}</td>
                                            <td>${{$oferta->precio}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div> --}}
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
                    colors: [
                        'green',
                        'secondary',
                        'yellow darken-4',
                        'red lighten-2',
                        'orange darken-1',
                    ],
                    cycle: false,
                    slides: [
                        'First',
                        'Second',
                        'Third',
                        'Fourth',
                        'Fifth',
                    ],
                    overlay: false
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
                    axios.delete('/delete-product/' + id).then(() => {
                        window.location.reload()
                    })
                },
                ofertar() {
                    swal({
                        title: "Confirmacion",
                        text: "¿Seguro que desea lanzar una oferta para esta subasta?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                        .then((comprar) => {
                            if (comprar) {
                                this.overlay = true
                                document.getElementById('formOfertar').submit();
                                return false;
                            }
                        });
                }
            },
            computed: {}
        })
    </script>
@endsection
