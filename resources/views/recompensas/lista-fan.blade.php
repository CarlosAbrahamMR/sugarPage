@extends('layouts.admin')
@section('content')
<div class="st-pusher" id="content">
    <div class="st-content">
        <div class="st-content-inner">
            <div id="app" class="container-fluid">
                <div class="panel panel-default">
                    <div class="panel-body">

                        <v-app>
                            <v-card>
                                <v-card-title>
                                    {{__('traducciones.recompensas')}}
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
                                    :headers="headersSuscripciones"
                                    :items="{{$recompensas}}"
                                    :search="search"
                                >
                                    <template #item.name="{ item }">
                                        <span>@{{ item.tipo == '0' ? "live" : "otro"}}</span>
                                    </template>
                                    <template #item.pago="{ item }">
                                        <span>@{{item.tipo == '0' ? "live" : "otro"}}</span>
                                    </template>

                                    <template #item.tipo="{ item }">
                                        <span>@{{ item.tipo == '0' ? "live" : "otro"}}</span>
                                    </template>
                                    <template #item.fecha_recompensa="{ item }">
                                        <span>@{{ item.fecha_evento == null ? item.fecha_recompensa :  item.fecha_evento }}</span>
                                    </template>
                                    <template v-slot:item.actions="{ item }">
                                        <v-icon
                                          small
                                          class="mr-2"
                                          @click="editItem(item)"
                                        >
                                          mdi-eye
                                        </v-icon>

                                      </template>
                                </v-data-table>
                            </v-card>
                            <v-dialog
                                v-model="verRecompensa"
                                persistent
                                max-width="700px"
                            >
                                <v-card>
                                    <v-card-title class="d-flex flex-column justify-space-between align-center">
                                        <span class="text-h5">¡Aquí tienes tu recompensa!</span>
                                    </v-card-title>
                                    <form action="{{route('valorar.recompensa')}}" method="POST">
                                        {{ csrf_field() }}
                                        <v-card-text>
                                            <v-row>
                                                <v-col
                                                    cols="12"
                                                    sm="12"
                                                    md="12"
                                                >

                                                    <div v-if="!link_livechat" v-for="(recompensa, index) in recompensas" :v-key="index" class="d-flex flex-column justify-space-between align-center">
                                                        <v-img
                                                            :aspect-ratio="16/9"
                                                            :width="400"
                                                            :src="'storage'+recompensa.path+recompensa.nombre"
                                                        ></v-img>
                                                    </div>
                                                    <div v-if="link_livechat" class="d-flex flex-column justify-space-between align-center">
                                                        <h1><a :href="link_livechat">@{{link_livechat}}</a></h1>
                                                    </div>
                                                </v-col>
                                            </v-row>
                                            <v-row>
                                                <v-col
                                                    cols="12"
                                                    sm="12"
                                                    md="12"
                                                >
                                                    <div class="text-center mt-12">
                                                        Si te ha gustado, califica tu experiencia y haz un comentario.
                                                        <v-rating
                                                            v-model="form.valoracion"
                                                            color="yellow darken-3"
                                                            background-color="grey darken-1"
                                                            empty-icon="$ratingFull"
                                                            hover
                                                            large
                                                        ></v-rating>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-comment"></i></span>
                                                            <input placeholder="Escribe un comentario" v-model="form.comentario" id="touch-spin-2" name="comentario" type="text" class="form-control" />
                                                            <input hidden name="idRecompensa" :value="form.idRecompensa" type="text" />
                                                            <input hidden name="valoracion" :value="form.valoracion" type="text" />
                                                        </div>
                                                    </div>
                                                </v-col>
                                            </v-row>
                                        </v-card-text>
                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <button @click="close()" type="button" class="btn btn-danger"><i class="fa fa-times"></i> {{__("traducciones.cancelar")}}</button>&nbsp;
                                            <button type="submit" class="btn btn-success" :disabled="!form.valoracion"><i class="fa fa-check-circle"></i> {{__("Rate Now")}}</button>
                                        </v-card-actions>
                                    </form>
                                </v-card>
                            </v-dialog>
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
                    headersSuscripciones: [
                        {text: 'Tipo', value: 'tipo'},
                        {text: 'Fecha Recompensa o evento', value: 'fecha_recompensa'},
                        { text: 'Actions', value: 'actions', sortable: false },
                        // { text: 'Productos', value: 'productos' },
                    ],
                    search: '',
                    valorar: false,
                    verRecompensa: false,
                    recompesaEnProceso: false,
                    recompensas: [],
                    form: {
                        idRecompensa: '',
                        comentario: '',
                        valoracion: 0
                    },
                    link_livechat: ''
                }
            },
            methods: {
                editItem(item){
                    console.log(item)

                    if(item.imagenes.length > 0 || item.link_livechat){
                        this.verRecompensa = true
                        this.form.idRecompensa = item.id
                        this.recompensas = item.imagenes
                        this.link_livechat = item.link_livechat
                        if(item.comentarios.length > 0){
                            this.form.comentario = item.comentarios[0].comentario
                            this.form.valoracion = item.comentarios[0].valoracion
                        }
                    } else {
                        this.recompesaEnProceso = true
                    }
                    console.log(item, this.link_livechat, item.link_livechat)
                },
                close(){
                    this.verRecompensa = false
                    this.form.idRecompensa = ''
                    this.recompensas = []
                    this.link_livechat = ''
                    this.form.comentario = ''
                    this.form.valoracion = 0
                }
            },
            computed: {}
        })
    </script>
@endsection
