<template>
  <div class="media media-grid media-clearfix-xs">
    <div class="media-left">

      <div class="width-250 width-auto-xs">
        <div class="panel panel-default widget-user-1 text-center">
          <div class="avatar">
            <img :src="foto_perfil !== null ? '/images/user-profile' : '/dist/themes/social-1/images/people/110/woman-1.jpg'" alt="" class="img-circle">
            <h3>{{username}}</h3>
            <a href="#" class="btn btn-success">Following <i class="fa fa-check-circle fa-fw"></i></a>
          </div>
          <div class="profile-icons margin-none">
            <span><i class="fa fa-users"></i> 0</span>
            <span><i class="fa fa-photo"></i> 0</span>
            <span><i class="fa fa-video-camera"></i> 0</span>
          </div>
          <div class="panel-body">
            <div class="expandable expandable-indicator-white expandable-trigger">
              <div class="expandable-content">
                <p>Hi! I'm Adrian the Senior UI Designer at
                  <strong>MOSAICPRO</strong>. We hope you enjoy the design and quality of Social.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut autem delectus dolorum necessitatibus neque odio quam quas qui quod soluta? Aliquid eius esse minima.</p>
              </div>
            </div>
          </div>
        </div>

        <form>
          <!-- <div class="col-md-12"> -->
          <div class="form-group form-control-default">
            <label for="exampleInputFirstName">Redeem code</label>
            <input v-model="codeRedeem" type="email" class="form-control" id="exampleInputFirstName" placeholder="Enter code">
          </div>
          <!-- </div> -->
          <a @click="redeemCode()" class="btn btn-primary" type="button">Redeem</a>
        </form>
        <!-- Contact -->
        <div class="panel panel-default">
          <div class="panel-heading">
            Contact
          </div>
          <ul class="icon-list icon-list-block">
            <li><i class="fa fa-envelope fa-fw"></i> <a href="#">{{userEmail}}</a></li>
            <li><i class="fa fa-facebook fa-fw"></i> <a href="#">/facebook</a></li>
            <li><i class="fa fa-behance fa-fw"></i> <a href="#">/user</a></li>
          </ul>
        </div>
      </div>
    </div>

    <edit-profile-component v-if="capturarDatos"></edit-profile-component>

    <div v-if="!capturarDatos" class="media-body">
      <div class="tabbable">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#home" data-toggle="tab"><i class="fa fa-fw fa-picture-o"></i> Photos</a></li>
          <li class=""><a href="#profile" data-toggle="tab"><i class="fa fa-fw fa-folder"></i> Albums</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane fade active in" id="home">
          </div>
          <div class="tab-pane fade" id="profile">
          </div>
          <div class="tab-pane fade" id="dropdown1">
          </div>
          <div class="tab-pane fade" id="dropdown2">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="panel panel-default">
            <div class="panel-heading panel-heading-gray">
              <a href="#" class="btn btn-white btn-xs pull-right"><i class="fa fa-pencil"></i></a>
              <i class="fa fa-fw fa-info-circle"></i> About
            </div>
            <div class="panel-body">
              <ul class="list-unstyled profile-about margin-none">
                <li class="padding-v-5">
                  <div class="row">
                    <div class="col-sm-4"><span class="text-muted">Username</span></div>
                    <div class="col-sm-8">{{username}}</div>
                  </div>
                </li>
                <li class="padding-v-5">
                  <div class="row">
                    <div class="col-sm-4"><span class="text-muted">Sexual Orientation</span></div>
                    <div class="col-sm-8">{{sexual_orientation}}</div>
                  </div>
                </li>
                <li class="padding-v-5">
                  <div class="row">
                    <div class="col-sm-4"><span class="text-muted">Hair Color</span></div>
                    <div class="col-sm-8">{{hair_color}}</div>
                  </div>
                </li>
                <li class="padding-v-5">
                  <div class="row">
                    <div class="col-sm-4"><span class="text-muted">Skin Color</span></div>
                    <div class="col-sm-8">{{skin_color}}</div>
                  </div>
                </li>
                <li class="padding-v-5">
                  <div class="row">
                    <div class="col-sm-4"><span class="text-muted">Height</span></div>
                    <div class="col-sm-8">{{height}}</div>
                  </div>
                </li>
                <li class="padding-v-5">
                  <div class="row">
                    <div class="col-sm-4"><span class="text-muted">Complexion</span></div>
                    <div class="col-sm-8">{{complexion}}</div>
                  </div>
                </li>
                <li class="padding-v-5">
                  <div class="row">
                    <div class="col-sm-4"><span class="text-muted">Age</span></div>
                    <div class="col-sm-8">{{age}}</div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="panel panel-default">
            <div class="panel-heading panel-heading-gray">
              <div class="pull-right">
                <a href="#" class="btn btn-primary btn-xs">Add <i class="fa fa-plus"></i></a>
              </div>
              <i class="icon-user-1"></i> Friends
            </div>
            <div class="panel-body">
            </div>
          </div>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading panel-heading-gray">
          <i class="fa fa-bookmark"></i> Bookmarks
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import axios from 'axios'
  export default {
      name:"ProfileComponent",
      data() {
        return {
          username: '',
          userEmail: '',
          creador: false,
          fan: true,
          foto_perfil: '',
          foto_portada: '',
          userInfo: {},
          capturarDatos: false,
          sexual_orientation:"",
          hair_color:"",
          skin_color:"",
          height:"",
          complexion:"",
          age:"",
          age_range:"",
          residencia:"",
          language:"",
          codeRedeem: ""
        }
      },
      mounted(){
        console.log('entra al componente')
        this.getUserInfo()
      },
      methods:{
        getUserInfo() {
          axios.get('/get-user-info').then(resp=> {
            resp = resp.data
            this.userInfo = resp
            this.username = resp.name
            this.userEmail = resp.email
            this.creador = resp.creador
            this.fan = resp.fan
            this.foto_perfil = resp.path_imagen_perfil
            this.foto_portada = resp.path_imagen_portada
            this.personales = this.userInfo.personales
            if (this.personales) {
              this.username = this.personales.nombre
              this.sexual_orientation= this.personales.sexo === 'H' ? 'Men' : 'Women'
              this.hair_color= this.personales.color_cabello
              this.skin_color= this.personales.color_piel
              this.height= this.personales.estatura
              this.complexion= this.personales.complexion
              this.age= this.personales.edad
              this.age_range= this.personales.age_range
              this.residencia= this.personales.residencia
              this.language= this.personales.idioma
            } else {
              this.capturarDatos = true
            }
            console.log(resp)
          })
        },
        redeemCode() {
          let data = {
            'email':this.userEmail,
            'code':this.codeRedeem
          }
          axios.get('/redeem-code', data).then(resp=> {
            console.log(resp.data)
          })
        }
      }
  }
</script>
