@extends('layouts.admin')
@section('styles')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.2/Chart.min.js"></script>
    <style>
        .containergr {
            width: 70%;
            margin: 15px auto;
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
                                <v-row dense>
                                    <v-container>
                                        <v-card
                                            class="mx-auto"
                                        >
                                            <v-col
                                                v-for="(item, i) in items"
                                                :key="i"
                                                cols="12"
                                                sm="6"
                                                md="6"
                                            >
                                                <v-card
                                                    :color="item.color"
                                                    dark
                                                >
                                                    <div class="d-flex flex-no-wrap justify-space-between">
                                                        <div>
                                                            <v-card-title
                                                                class="text-h4"
                                                                v-text="item.title"
                                                            ></v-card-title>
                                                            <br>
                                                            <v-card-subtitle style="font-size: 50px"
                                                                             v-text="item.artist"></v-card-subtitle>
                                                        </div>
                                                        <v-avatar
                                                            class="ma-3"
                                                            size="125"
                                                            tile
                                                        >
                                                            <v-img max-width="125" :src="item.src"></v-img>
                                                        </v-avatar>
                                                    </div>
                                                </v-card>
                                                <br>
                                            </v-col>
                                        </v-card>
                                    </v-container>
                                </v-row>
                                <div class="containergr">
                                    <h2>INGRESOS</h2>
                                    <div>
                                        <canvas id="myChart"></canvas>
                                    </div>
                                </div>
                                <div class="containergr table-responsive">
                                    <div class="col-12">
                                        <select v-model="categoria" class="form-control" name="categorias" id="categorias" @change="cambiarCategoria()">
                                            <option value="">Filtrar por categoría</option>
                                            <option value="SUS">Suscripcion</option>
                                            <option value="SUB">Subastas</option>
                                            <option value="OFE">Ofertas</option>
                                            <option value="CON">Contenido</option>
                                        </select>
                                    </div>
                                    <v-card v-if="categoria === '' || categoria === 'SUS'">
                                        <v-card-title>
                                            Suscripciones
                                            <v-spacer></v-spacer>
                                            <v-text-field
                                                v-model="search"
                                                append-icon="mdi-magnify"
                                                label="Search"
                                                single-line
                                                hide-details
                                            ></v-text-field>
                                        </v-card-title>
                                        <v-data-table
                                            :headers="headers"
                                            :items="{{$suscripciones}}"
                                            :search="search"
                                        >
                                            <template #item.name="{ item }">
{{--                                                <img alt="people"--}}
{{--                                                     :src="item.path_imagen_perfil"--}}
{{--                                                     width="40" height="40" class="img-circle"/>--}}
                                                @{{item.name}}
                                            </template>
{{--                                            <template #item.fecha_inicio="{ item }">--}}
{{--                                                <span>@{{ new Date(item.fecha_inicio).toLocaleString(undefined, { year: 'numeric', month: 'long', day: 'numeric' }) }}</span>--}}
{{--                                            </template>--}}
                                            <template #item.pago="{ item }">
                                                <span><i class="fa fa-dollar"></i>@{{ item.pago}}</span>
                                            </template>
                                        </v-data-table>
                                    </v-card>
                                    <v-card v-if="categoria === 'SUB'">
                                        <v-card-title>
                                            Subastas
                                            <v-spacer></v-spacer>
                                            <v-text-field
                                                v-model="search"
                                                append-icon="mdi-magnify"
                                                label="Search"
                                                single-line
                                                hide-details
                                            ></v-text-field>
                                        </v-card-title>
                                        <v-data-table
                                            :headers="headers"
                                            :items="{{$subastas}}"
                                            :search="search"
                                        >
                                            <template #item.name="{ item }">
{{--                                                <img alt="people"--}}
{{--                                                     :src="item.path_imagen_perfil"--}}
{{--                                                     width="40" height="40" class="img-circle"/>--}}
                                                @{{item.name}}
                                            </template>
                                            <template #item.pago="{ item }">
                                                <span><i class="fa fa-dollar"></i>@{{ item.pago}}</span>
                                            </template>
                                        </v-data-table>
                                    </v-card>
                                    <v-card v-if="categoria === 'OFE'">
                                        <v-card-title>
                                            Ofertas
                                            <v-spacer></v-spacer>
                                            <v-text-field
                                                v-model="search"
                                                append-icon="mdi-magnify"
                                                label="Search"
                                                single-line
                                                hide-details
                                            ></v-text-field>
                                        </v-card-title>
                                        <v-data-table
                                            :headers="headers"
                                            :items="{{$productos}}"
                                            :search="search"
                                        >
                                            <template #item.name="{ item }">
{{--                                                <img alt="people"--}}
{{--                                                     :src="item.path_imagen_perfil"--}}
{{--                                                     width="40" height="40" class="img-circle"/>--}}
                                                @{{item.name}}
                                            </template>
                                            <template #item.pago="{ item }">
                                                <span><i class="fa fa-dollar"></i>@{{ item.pago}}</span>
                                            </template>
                                        </v-data-table>
                                    </v-card>
                                    <v-card v-if="categoria === 'CON'">
                                        <v-card-title>
                                            Contenido
                                            <v-spacer></v-spacer>
                                            <v-text-field
                                                v-model="search"
                                                append-icon="mdi-magnify"
                                                label="Search"
                                                single-line
                                                hide-details
                                            ></v-text-field>
                                        </v-card-title>
                                        <v-data-table
                                            :headers="headers"
                                            :items="{{$contenidos}}"
                                            :search="search"
                                        >
                                            <template #item.name="{ item }">
{{--                                                <img alt="people"--}}
{{--                                                     :src="item.path_imagen_perfil"--}}
{{--                                                     width="40" height="40" class="img-circle"/>--}}
                                                @{{item.name}}
                                            </template>
                                            <template #item.pago="{ item }">
                                                <span><i class="fa fa-dollar"></i>@{{ item.pago}}</span>
                                            </template>
                                        </v-data-table>
                                    </v-card>
                                </div>
                            </v-main>
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
                    items: [
                        {
                            color: '#952175',
                            title: 'Suscriptores',
                            src: '/images/people.png',
                            artist: '{{$subscriptores}}',
                        },
                        {
                            color: '#15a5a5',
                            title: 'Seguidores',
                            src: '/images/follow.png',
                            artist: '{{$seguidores}}',
                        },
                    ],
                    search: '',
                    headersSuscripciones: [
                        {
                            text: 'Name',
                            align: 'start',
                            sortable: false,
                            value: 'name',
                        },
                        { text: 'Suscrito Desde', value: 'fecha_inicio' },
                        { text: 'Pagó (USD)', value: 'pago' },
                        // { text: 'Productos', value: 'productos' },
                    ],
                    headers: [
                        {
                            text: 'Name',
                            align: 'start',
                            sortable: false,
                            value: 'name',
                        },
                        // { text: 'Productos', value: 'productos' },
                        { text: 'Pagó (USD)', value: 'pago' },
                        // { text: 'Productos', value: 'productos' },
                    ],
                    categoria: ''
                }
            },
            mounted() {
            },
            methods: {
                cambiarCategoria(){
                    console.log(this.categoria)
                }
            },
            computed: {}
        })
    </script>
    <script>
        var ctx = document.getElementById("myChart").getContext("2d");
        var myChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: [
                    "Suscripciones",
                    "Subastas",
                    "Ofertas",
                    "Contenido",
                ],
                datasets: [
                    {
                        label: "Ingresos",
                        data: ['{{$ing_suscripcion}}', '{{$ing_subastas}}', '{{$ing_ofertas}}', '{{$ing_contenido}}'],
                        backgroundColor: "rgba(153,205,1,0.6)",
                    },
                ],
            },
        });
    </script>
@endsection
