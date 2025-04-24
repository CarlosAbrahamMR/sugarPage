@extends('layouts.app')
@section('content')
    <div class="st-pusher" id="content">
        <div class="st-content">
            <div class="st-content-inner">
                <div id="app" class="container-fluid">
                    <div class="panel panel-default">
                        @if (\Session::has('succes'))
                            <div class="alert alert-succes">
                                <div> {{ session()->get('succes') }}</div>
                            </div>
                        @endif
                        @if (\Session::has('error'))
                            <div class="alert alert-danger">
                                <div> {{ session()->get('error') }}</div>
                            </div>
                        @endif
                        <v-app>
                            <template>
                                <v-card
                                    elevation="24"
                                    max-width="444"
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
                                                <v-list-item-title>{{$subasta->nombre}}</v-list-item-title>
                                                <v-list-item-subtitle>{{$subasta->descripcion}}</v-list-item-subtitle>
                                                <v-list-item-subtitle>
                                                    ${{$subasta->precio_inicial}}</v-list-item-subtitle>
                                            </v-list-item-content>
                                            <v-list-item-action>
                                                <v-list-item-title>{{date_format(new \DateTime($subasta->fecha_inicio),'Y/m/d')}}</v-list-item-title>
                                                <v-list-item-title>{{date_format(new \DateTime($subasta->fecha_fin),'Y/m/d')}}</v-list-item-title>

                                            </v-list-item-action>
                                        </v-list-item>
                                    </v-list>
                                </v-card>


                            </template>
                            <br>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    @if($cobrar)
                                        <form class="" role="form" id="form"
                                              action="{{route('auctions.cobrar',$subasta->id)}}" method="POST">
                                            {{ csrf_field() }}

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">Cobrar</button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="table-responsive">
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
                            </div>
                        </v-app>
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
                }
            },
            computed: {}
        })
    </script>
@endsection
