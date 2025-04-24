@extends('layouts.admin')

@section('content')

    <div class="st-pusher" id="content">
        <div class="st-content">
            <div class="st-content-inner">
                <div id="app" class="container-fluid">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <v-row>
                                <v-col cols="6" sm="6" md="6">
                                    <h4 class="page-section-heading"> Total ${{$total}}</h4>
                                </v-col>
                                <v-col cols="6" sm="6" md="6">
                                    <h4 class="page-section-heading">Total Revenue:  MySugar: 20% Creador: 80%</h4>
                                </v-col>
                            </v-row>
                            <v-app>
                                <v-card>
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
                                    <v-row>
                                        <v-col cols="6" sm="6" md="3">
                                            <v-menu
                                                ref="menu3"
                                                v-model="menu3"
                                                :close-on-content-click="false"
                                                :return-value.sync="date"
                                                transition="scale-transition"
                                                offset-y
                                                min-width="auto"
                                            >
                                                <template v-slot:activator="{ on, attrs }">
                                                    <v-text-field
                                                        v-model="date"
                                                        label="Fecha Inicio"
                                                        prepend-icon="mdi-calendar"
                                                        readonly
                                                        clearable
                                                        v-bind="attrs"
                                                        v-on="on"
                                                    ></v-text-field>
                                                </template>
                                                <v-date-picker
                                                    v-model="date"
                                                    no-title
                                                    scrollable
                                                    @change="$refs.menu3.save(date)"
                                                >
                                                </v-date-picker>
                                            </v-menu>
                                        </v-col>
                                        <!-- segundo  data picker -->
                                        <v-col cols="6" sm="6" md="3">
                                            <v-menu
                                                ref="menu2"
                                                v-model="menu2"
                                                :close-on-content-click="false"
                                                :return-value.sync="date2"
                                                transition="scale-transition"
                                                offset-y
                                                min-width="auto"
                                            >
                                                <template v-slot:activator="{ on, attrs }">
                                                    <v-text-field
                                                        v-model="date2"
                                                        label="Fecha Fin"
                                                        prepend-icon="mdi-calendar"
                                                        readonly
                                                        clearable
                                                        v-bind="attrs"
                                                        v-on="on"
                                                    ></v-text-field>
                                                </template>
                                                <v-date-picker
                                                    v-model="date2"
                                                    no-title
                                                    scrollable
                                                    @change="filtro()"
                                                >
                                                </v-date-picker>
                                            </v-menu>
                                        </v-col>
                                    </v-row>
                                    <v-data-table
                                        :headers="headersSuscripciones"
                                        :items="pagos"
                                        :search="search"
                                    >
                                        <template #item.name="{ item }">
                                            <span><i class="fa fa-dollar"></i>@{{ item.importe}}</span>
                                        </template>
                                        <template #item.pago="{ item }">
                                            <span><i class="fa fa-dollar"></i>@{{ item.username}}</span>
                                        </template>

                                        <template #item.pago="{ item }">
                                            <span><i class="fa fa-dollar"></i>@{{ item.tipo}}</span>
                                        </template>
                                        <template #item.fecha_inicio="{ item }">
                                            <span>@{{ new Date(item.fecha_pago).toLocaleString(undefined, { year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
                                        </template>
                                    </v-data-table>
                                </v-card>
                            </v-app>
                        </div>
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
                    pagos: [],
                    search: '',
                    headersSuscripciones: [
                        {text: 'Importe', value: 'importe'},
                        {text: 'Username', value: 'username'},
                        {text: 'Tipo ingreso', value: 'tipo'},
                        {text: 'Date', value: 'fecha_pago'},
                        // { text: 'Productos', value: 'productos' },
                    ],
                    date: null,
                    menu3: false,
                    date2: null,
                    menu2: false,
                    filtros: {
                        date: null,
                        date2: null,
                    },
                }
            },
            mounted() {
                this.getBallance()
            },
            watch: {
                date2() {
                    if (this.date2 == null) {
                        this.filtros.date = null
                        this.filtros.date2 = null
                        this.date = null
                        this.filtro()
                    }
                }
            },
            methods: {
                cambiarCategoria() {
                    console.log(this.categoria)
                },
                async getBallance() {
                    await axios
                        .get('/get-ballance')
                        .then(resp => {
                            this.pagos = resp.data.pagos
                            console.log(resp.data.pagos)
                        })
                        .catch((error) => {
                            let datapost = {
                                message: error
                            };
                            console.log("Error de axios" + datapost)
                        })
                },
                async filtro() {
                    var cadena = '?'
                    if (this.date2 != null) {
                        if (this.date === "" || this.date == null) {
                            alert("La fecha de inicio  de busquedad no puede estar vacia");
                        } else {
                            this.filtros.date = this.date
                            this.filtros.date2 = this.date2
                            cadena = cadena + 'dateinicio=' + this.filtros.date + '&'
                            cadena = cadena + 'datefin=' + this.filtros.date2 + '&'
                            this.$refs.menu2.save(this.date2);
                            //alert("La fecha de fin de busquedad no puede estar vacia");
                        }

                    }
                    await axios
                        .get('/suscripcion-filtro' + cadena)
                        .then(resp => {
                            this.pagos = resp.data.pagos
                            console.log(resp)
                        })
                        .catch((error) => {
                            let datapost = {
                                message: error
                            };
                            console.log("Error de axios" + datapost)
                        })
                },
            },
            computed: {}
        })
    </script>
@endsection

