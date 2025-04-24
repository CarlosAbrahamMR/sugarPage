<template>
    <div class="panel-body">
        <form class="form-horizontal" role="form" id="form" method="POST" action @submit.prevent="crear('form')">
            <input class="form-control" id="name" type="text" placeholder="Name" name="name" v-model="name" required autofocus>
            <input class="form-control" id="email" type="email" placeholder="E-mail" name="email" v-model="email" required autofocus>
            <input class="form-control" id="password" type="password" placeholder="Password" name="password" v-model="password" required autofocus>
            <a class="forgot-password" href="/">
                Do you already have an account? Login
            </a>
            <button type="submit" id="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</template>

<script>
    import axios from 'axios'
    export default {
        name:"RegistroComponent",
        data() {
            return {
                name:"",
                email:"",
                password:"",
                sexual_orientation:"",
                hair_color:"",
                skin_color:"",            
                height:"",
                complexion:"",    
                age:"",
                age_range:"",
                recidence:"",
                language:"",
                sexual_orientation_list: [{'id':'H', 'nombre':"Hombre"},{'id':'M', 'nombre':"Mujer"},{'id':'A', 'nombre':"Ambos"}],
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created(){
        },
        methods:{
            crear(){
                //this.$validator.validateAll().then(valid => {
                    //if(valid){

                        axios.post(`/usuario`,{
                            name: this.name,
                            email: this.email,
                            password: this.password,
                            // sexual_orientation: this.sexual_orientation,
                            // hair_color: this.hair_color,
                            // skin_color: this.skin_color,            
                            // height: this.height,
                            // complexion: this.complexion,    
                            // age: this.age,
                            // age_range: this.age_range,
                            // recidence: this.recidence,
                            // language: this.language,
                            role: 2
                        })
                        .then(response => {
                            var estatus=response.data.estatus;
                            if (estatus) {
                                location.reload();
                            }else{
                                
                            }
                            
                        }).catch(error => {
                            console.log(error);
                            
                        })
                    //}else{
                        
                    //}
                //})
                
            },
        }
    }
</script>
<style type="text/css">
    .error{
        color: red;
    }
</style>