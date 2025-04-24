@extends('layouts.admin')
@section('content')

<div class="st-pusher" id="content">
    <div class="st-content">
        <div class="st-content-inner">
            <div id="app" class="container-fluid">
                <div class="panel panel-default">
                    <v-app>
                        <v-main>
                            <v-container>
                                <v-card>
                                    <v-card-title>
                                        Promotions List
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
                                        :items="{{$promotions}}"
                                        sort-by="calories"
                                        class="elevation-1"
                                        :search="search"
                                    >
                                        <template v-slot:top>
                                            <v-toolbar
                                                flat
                                            >
                                                <button @click="generateCode(10)" type="button" class="btn btn-success"><i class="fa fa-plus-circle"></i> {{__("traducciones.crear_nuevo")}}</button>
                                                <v-dialog
                                                    v-model="dialogAdd"
                                                    max-width="50%"
                                                    persistent
                                                >
                                                    <v-card class="mx-auto" >
                                                        <v-card-title>{{__("traducciones.crear_nuevo")}}</v-card-title>
                                                        <v-card-text>
                                                            <form ref="form" :action="nuevo ? '/create-promotion' : '/edit-promotion'" method="POST">
                                                                {{ csrf_field() }}
                                                                <v-text-field
                                                                    v-model="form.id"
                                                                    name="id"
                                                                    v-show="false"
                                                                ></v-text-field>
                                                                <div class="row">
                                                                    <div class="form-group col-md-4">
                                                                        <label
                                                                            class="col-md-12">
                                                                            {{__("traducciones.code")}}
                                                                        </label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                                                            <input
                                                                                v-model="form.code"
                                                                                id="code"
                                                                                type="text"
                                                                                class="form-control"
                                                                                name="code">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label
                                                                            class="col-md-12 ">
                                                                            {{__("traducciones.percent")}}
                                                                        </label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                                                            <input
                                                                                id="percent"
                                                                                type="number"
                                                                                class="form-control"
                                                                                name="percent"
                                                                                min="{{env('PORCENTAJE_USUARIO')}}"
                                                                                max="100"
                                                                                value="{{env('PORCENTAJE_USUARIO')}}"
                                                                                placeholder="{{__("traducciones.percent")}}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label
                                                                            class="col-md-12 ">
                                                                            {{__("traducciones.tiempo")}} / {{__("traducciones.meses")}}
                                                                        </label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon"><i class="md-today"></i></span>
                                                                            <input
                                                                                v-model="tiempo"
                                                                                id="tiempo"
                                                                                type="number"
                                                                                class="form-control"
                                                                                name="tiempo"
                                                                                placeholder="{{__("traducciones.tiempo")}}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label
                                                                            class="col-md-12 ">
                                                                            {{__("traducciones.fecha_inicio")}}
                                                                        </label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                                            <input
                                                                                v-model="form.fecha_inicio"
                                                                                id="fecha_inicio"
                                                                                type="date"
                                                                                class="form-control"
                                                                                name="fecha_inicio"
                                                                                placeholder="{{__("traducciones.fecha_inicio")}}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label
                                                                            class="col-md-12 ">
                                                                            {{__("traducciones.fecha_fin")}}
                                                                        </label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                                            <input
                                                                                v-model="form.fecha_fin"
                                                                                id="fecha_fin"
                                                                                type="date"
                                                                                class="form-control"
                                                                                name="fecha_fin"
                                                                                placeholder="{{__("traducciones.fecha_fin")}}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <button @click="close()" type="button" class="btn btn-danger"><i class="fa fa-times"></i> {{__("traducciones.cancelar")}}</button>
                                                                <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> {{__("traducciones.guardar")}}</button>
                                                            </form>
                                                        </v-card-text>
                                                    </v-card>
                                                </v-dialog>
                                            </v-toolbar>
                                        </template>
                                        <template #item.fecha_inicio="{ item }">
                                            <span>@{{ new Date(item.fecha_inicio).toLocaleString(undefined, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
                                        </template>
                                        <template #item.fecha_fin="{ item }">
                                            <span>@{{ new Date(item.fecha_fin).toLocaleString(undefined, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
                                        </template>
                                        <template v-slot:item.actions="{ item }">
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on, attrs }">
                                                    <v-icon
                                                        @click="editPromotion(item)"
                                                        v-bind="attrs"
                                                        v-on="on"
                                                    >
                                                        mdi-square-edit-outline
                                                    </v-icon>
                                                </template>
                                                <span>{{__("traducciones.editar")}}</span>
                                            </v-tooltip>
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on, attrs }">
                                                    <v-icon
                                                        @click="eliminarPromotion(item)"
                                                        v-bind="attrs"
                                                        v-on="on"
                                                    >
                                                        mdi-delete
                                                    </v-icon>
                                                </template>
                                                <span>{{__("traducciones.eliminar")}}</span>
                                            </v-tooltip>
                                        </template>
                                    </v-data-table>
                                </v-card>
                            </v-container>
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
                    valid: true,
                    promotionsList: [],
                    search: '',
                    loading: true,
                    dialogAdd: false,
                    editItem: {},
                    dialogEdit: false,
                    headers: [
                        {
                            text: '{{__("traducciones.code")}}',
                            align: 'start',
                            sortable: false,
                            value: 'codigo',
                        },
                        {text: '{{__("traducciones.percent")}}', value: 'porcentaje'},
                        {text: '{{__("traducciones.fecha_inicio")}}', value: 'fecha_inicio'},
                        {text: '{{__("traducciones.fecha_fin")}}', value: 'fecha_fin'},
                        {text: '{{__("traducciones.estatus")}}', value: 'status'},
                        {text: '{{__("traducciones.acciones")}}', value: 'actions'},
                    ],
                    form: {
                        id: '',
                        code: '',
                        tipo: 'Percent',
                        fecha_inicio: null,
                        fecha_fin: null,
                        percent: null,
                        amount: null,
                        tiempo: null
                    },

                    codeRules: [
                        v => !!v || 'Code is required',
                        v => (v && v.length <= 10) || 'Code must be less than 10 characters',
                    ],
                    amountRules: [
                        v => !!v || 'Amount is required',
                        v => v >= 1 || "The amount must be greater than 1.",
                    ],
                    percentRules: [
                        v => !!v || 'Amount is required',
                        v => v >= 1 || "The percentage must be greater than 1%",
                        v => v <= 100 || "The percentage must be less than or equal to 100%",
                    ],
                    menu_fecha_inicio: false,
                    menu_fecha_fin: false,
                    nuevo: true
                }
            },
            mounted() {
            },
            methods: {
                generateCode (num) {
                    this.dialogAdd = true
                    const characters ='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                    let result1= '';
                    const charactersLength = characters.length;
                    for ( let i = 0; i < num; i++ ) {
                        result1 += characters.charAt(Math.floor(Math.random() * charactersLength));
                    }

                    this.form.code = result1;
                },
                editPromotion(item) {
                    this.nuevo = false
                    this.editItemIndex = this.promotionsList.indexOf(item)
                    this.editItem = Object.assign({}, item)
                    this.dialogAdd = true
                    this.llenarDatos()
                },
                llenarDatos () {
                    this.form.id = this.editItem.id
                    this.form.code = this.editItem.codigo
                    this.form.tipo= 'Percent'
                    this.form.tiempo= this.editItem.tiempo
                    let fecha_inicio = new Date(this.editItem.fecha_inicio)

                    let dayI = fecha_inicio.getDate()
                    let monthI = fecha_inicio.getMonth() + 1
                    let yearI = fecha_inicio.getFullYear()

                    if(monthI < 10){
                        fecha_inicio = dayI < 10 ? `${yearI}-0${monthI}-0${dayI}` : `${yearI}-0${monthI}-${dayI}`
                    }else{
                        fecha_inicio = dayI < 10 ? `${yearI}-${monthI}-0${dayI}` : `${yearI}-${monthI}-${dayI}`
                    }
                    let fecha_fin = new Date(this.editItem.fecha_fin)

                    let dayF = fecha_fin.getDate()
                    let monthF = fecha_fin.getMonth() + 1
                    let yearF = fecha_fin.getFullYear()

                    if(monthF < 10){
                        fecha_fin = dayF < 10 ? `${yearF}-0${monthF}-0${dayF}` : `${yearF}-0${monthF}-${dayF}`
                    }else{
                        fecha_fin = dayF < 10 ? `${yearF}-${monthF}-0${dayF}` : `${yearF}-${monthF}-${dayF}`
                    }
                    this.form.fecha_inicio= fecha_inicio
                    this.form.fecha_fin= fecha_fin
                    this.form.percent= this.editItem.porcentaje
                    this.form.amount= this.editItem.monto
                },
                eliminarPromotion(item){
                    axios.delete('/delete-promotion/'+item.id).then(() => {
                        window.location.reload()
                    })
                },
                close() {
                    this.dialogAdd = false
                    this.form= {
                        id: '',
                        code: '',
                        tipo: 'Percent',
                        fecha_inicio: null,
                        fecha_fin: null,
                        percent: null,
                        amount: null
                    }
                }
            },
            computed: {
            }
        })
    </script>
@endsection
