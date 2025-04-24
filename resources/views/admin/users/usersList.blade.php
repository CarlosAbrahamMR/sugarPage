@extends('layouts.admin')

@section('content')

<div class="st-pusher" id="content">
    <div class="st-content">
        <div class="st-content-inner">
            <div data-app id="app" class="container-fluid">
                <div class="panel panel-default">
                    <v-app>
                        <v-main>
                            <v-container>
                                <v-card>
                                    <v-card-title>
                                        Users List
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
                                        :items="{{$users}}"
                                        sort-by="calories"
                                        class="elevation-1"
                                        :search="search"
                                    >
                                        <template v-slot:item.fan="{ item }">
                                            <v-icon
                                                small
                                                v-if="item.fan == 1"
                                                color="green darken-2"
                                            >
                                                mdi-check-circle
                                            </v-icon>
                                            <v-icon
                                                small
                                                v-else
                                                color="red darken-2"
                                            >
                                                mdi-close-circle
                                            </v-icon>
                                        </template>
                                        <template v-slot:item.creador="{ item }">
                                            <v-icon
                                                small
                                                v-if="item.creador == 1"
                                                color="green darken-2"
                                            >
                                                mdi-check-circle
                                            </v-icon>
                                            <v-icon
                                                small
                                                v-else
                                                color="red darken-2"
                                            >
                                                mdi-close-circle
                                            </v-icon>
                                        </template>
                                        <template v-slot:item.actions="{ item }">
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on, attrs }">
                                                    <v-icon
                                                        @click="disabledUser(item)"
                                                        v-bind="attrs"
                                                        v-on="on"
                                                        v-if="item.status === 'enabled'"
                                                    >
                                                        mdi-account-cancel
                                                    </v-icon>
                                                    <v-icon
                                                        @click="disabledUser(item)"
                                                        v-bind="attrs"
                                                        v-on="on"
                                                        v-if="item.status === 'disabled'"
                                                    >
                                                        mdi-account-check
                                                    </v-icon>
                                                </template>
                                                <span v-if="item.status === 'disabled'">Enabled</span>
                                                <span v-if="item.status === 'enabled'">Disabled</span>
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
                    usersList:[],
                    search: '',
                    loading: true,
                    correcto: false,
                    headers: [
                        {
                            text: 'Name',
                            align: 'start',
                            sortable: false,
                            value: 'name',
                        },
                        { text: 'Username', value: 'username' },
                        { text: 'Email', value: 'email' },
                        { text: 'Creador', value: 'creador' },
                        { text: 'Fan', value: 'fan' },
                        { text: 'Actions', value: 'actions' },
                    ],
                }
            },
            mounted() {
            },
            methods: {
                disabledUser(item) {
                    let data = {'status':item.status === 'disabled' ? 'enabled' : 'disabled'}
                    console.log(data)
                    axios.patch('/update-users/'+item.email, data).then(() => {
                        window.location.reload()
                    })
                }
            },
            computed: {}
        })
    </script>
@endsection
