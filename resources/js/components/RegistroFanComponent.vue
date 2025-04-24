<template>
    <div class="container-fluid">
        <form id="form" method="POST" action @submit.prevent="crear('form')">
            <div class="row">
                <div class="form-group col-md-12">
                    <label 
                        class="col-md-12" >
                        Name
                    </label>
                    <input 
                        v-model="name" 
                        id="name" 
                        type="text" 
                        class="form-control" 
                        name="name" 
                        placeholder="Name" 
                        >
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label 
                        class="col-md-12">
                        E-mail
                    </label>
                    <input 
                        v-model="email" 
                        id="email" 
                        type="email" 
                        class="form-control"  
                        name="email" 
                        placeholder="E-mail">
                </div>
                <div class="form-group col-md-6">
                    <label 
                        class="col-md-12">
                        Password
                    </label>
                    <input 
                        v-model="password" 
                        id="password" 
                        type="password" 
                        class="form-control"  
                        name="password" 
                        placeholder="Password">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label 
                        class="col-md-12">
                        Sexual Orientation
                    </label>
                    <select  
                        v-model="sexual_orientation" 
                        id="sexual_orientation" 
                        type="text" 
                        class="form-control" 
                        name="sexual_orientation" >
                        <option value="">Select an option</option>
                        <option v-for="genero in sexual_orientation_list" :key="genero.id" :value="genero.id">{{genero.nombre}}</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label
                        class="col-md-12 ">
                        Hair Color
                    </label>
                    <input 
                        v-model="hair_color" 
                        id="hair_color" 
                        type="text" 
                        class="form-control" 
                        name="hair_color" 
                        placeholder="Hair Color">
                </div>
                <div class="form-group col-md-6">
                    <label
                        class="col-md-12 ">
                        Skin Color
                    </label>
                    <input 
                        v-model="skin_color"
                        id="skin_color" 
                        type="text" 
                        class="form-control" 
                        name="skin_color" 
                        placeholder="Skin Color">
                </div>
                <div class="form-group col-md-6">
                    <label
                        class="col-md-12 ">
                        Height
                    </label>
                    <input 
                        v-model="height"
                        id="height" 
                        type="text" 
                        class="form-control" 
                        name="height" 
                        placeholder="Height">
                </div>
                <div class="form-group col-md-6">
                    <label
                        class="col-md-12 ">
                        Complexion
                    </label>
                    <input 
                        v-model="complexion"
                        id="Complexion" 
                        type="text" 
                        class="form-control" 
                        name="complexion" 
                        placeholder="Complexion">
                </div>
                <div class="form-group col-md-6">
                    <label
                        class="col-md-12 ">
                        Age
                    </label>
                    <input 
                        v-model="age"
                        id="age" 
                        type="text" 
                        class="form-control" 
                        name="age" 
                        placeholder="Age">
                </div>
                <div class="form-group col-md-6">
                    <label
                        class="col-md-12 ">
                        Recidence
                    </label>
                    <input 
                        v-model="recidence"
                        id="recidence" 
                        type="text" 
                        class="form-control" 
                        name="recidence" 
                        placeholder="Recidence">
                </div>
                <div class="form-group col-md-6">
                    <label
                        class="col-md-12 ">
                        Language
                    </label>
                    <input 
                        v-model="language"
                        id="language" 
                        type="text" 
                        class="form-control" 
                        name="language" 
                        placeholder="Language">
                </div>
            </div>
            <br>
            <div class="row justify-content-center">
                <div>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </div>
            <br>
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
                            sexual_orientation: this.sexual_orientation,
                            hair_color: this.hair_color,
                            skin_color: this.skin_color,            
                            height: this.height,
                            complexion: this.complexion,    
                            age: this.age,
                            age_range: this.age_range,
                            recidence: this.recidence,
                            language: this.language,
                            role: 1
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