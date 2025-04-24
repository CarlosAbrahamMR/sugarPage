<template>
    <div class="media-body">
        <div class="panel panel-default">
          <div class="page-section">
            <div class="row">
              <div class="col-md-10 col-lg-8 col-md-offset-1 col-lg-offset-2">
                <form>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label 
                                class="col-md-12" >
                                Username
                            </label>
                            <input 
                                v-model="username" 
                                id="name" 
                                type="text" 
                                class="form-control" 
                                name="name" 
                                placeholder="Name" 
                                >
                            <span style="color:red;">{{errorUsername}}</span>
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
                                type="number" 
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
                                v-model="residencia"
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
                            <button type="button" @click="savePersonales()" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                    <br>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div id="loading">
            <div id="loading-content"></div>
        </div>
    </div>
</template>
<script>
  import axios from 'axios'
  export default {
      name:"EditProfileComponent",
      data() {
        return {
            username:"",
            sexual_orientation:"",
            hair_color:"",
            skin_color:"",            
            height:"",
            complexion:"",    
            age:"",
            age_range:"",
            residencia:"",
            language:"",
            userInfo: [],
            errorUsername: '',
            personales: [],
            sexual_orientation_list: [{'id':'H', 'nombre':"Men"},{'id':'M', 'nombre':"Women"},{'id':'A', 'nombre':"Both of them"}],
        }
      },
      mounted(){
        console.log('entra al componente editar perfil')
        this.getUserInfo()
      },
      methods:{
        getUserInfo() {
          axios.get('/get-user-info').then(resp=> {
            resp = resp.data
            this.userInfo = resp
            this.personales = this.userInfo.personales
            this.username= this.userInfo.username
            if (this.personales) {
              this.username = this.username
              this.sexual_orientation= this.personales.sexual_orientation
              this.hair_color= this.personales.hair_color
              this.skin_color= this.personales.skin_color            
              this.height= this.personales.height
              this.complexion= this.personales.complexion    
              this.age= this.personales.age
              this.age_range= this.personales.age_range
              this.residencia= this.personales.residencia
              this.language= this.personales.language
            }

            console.log(resp)
          })
        },
        savePersonales() {
          if (true) {
            this.showLoading()
            axios.post(`/save-personal-information`,{
              username: this.username,
              sexual_orientation: this.sexual_orientation,
              hair_color: this.hair_color,
              skin_color: this.skin_color,            
              height: this.height,
              complexion: this.complexion,    
              age: this.age,
              age_range: this.age_range,
              residencia: this.residencia,
              language: this.language,
              creador: true
            })
            .then(response => {
                this.hideLoading()

                if (response.data.error) {
                    this.errorUsername = response.data.message
                } else {
                    location.reload();
                }
            }).catch(error => {
                console.log(error);
                
            })
          }
        },
        showLoading() {
          document.querySelector('#loading').classList.add('loading');
          document.querySelector('#loading-content').classList.add('loading-content');
        },

        hideLoading() {
          document.querySelector('#loading').classList.remove('loading');
          document.querySelector('#loading-content').classList.remove('loading-content');
        }
      }
  } 
</script>
<style type="text/css">
  .loading {
    z-index: 20;
    position: absolute;
    top: 0;
    left:-5px;
    width: 100%;
    height: 100%;
      background-color: rgba(0,0,0,0.4);
  }
  .loading-content {
    position: absolute;
    border: 16px solid #f3f3f3; /* Light grey */
    border-top: 16px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 50px;
    height: 50px;
    top: 40%;
    left:48%;
    animation: spin 2s linear infinite;
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
</style>  