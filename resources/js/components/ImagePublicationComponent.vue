<template>
    <div class="panel panel-default share clearfix-xs">
        <div class="panel-heading panel-heading-gray title">
            What&acute;s new
        </div>
        <div class="panel-body">
            <textarea name="status" class="form-control share-text" rows="3" placeholder="Share your status..." v-model="actualizacionEstatus"></textarea>
            <form action="/save-image" class="dropzone" id="my-awesome-dropzone" style="border:0;">
                <input type="hidden" name="_token" v-bind:value="csrf">
                <div class="dz-default dz-message">
                    <p class="text-muted">Sube una imagen</p>
                </div>
            </form>
        </div>
        <div class="panel-footer share-buttons">
            <a href="#"><i class="fa fa-map-marker"></i></a>
            <a href="#"><i class="fa fa-photo"></i></a>
            <a href="#"><i class="fa fa-video-camera"></i></a>
            <button class="btn btn-primary btn-xs pull-right" @click="save()">Publicar</button>
        </div>
    </div>
</template>

<script>
    import Dropzone from "dropzone";
    import axios from 'axios'
    export default {
        name: "ImagePublicationComponent",
        data() {
            return {
                //csrf token
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                actualizacionEstatus:"",
            }
        },
        mounted() {
            let myDropzone = new Dropzone("#my-awesome-dropzone");
            myDropzone.options.myAwesomeDropzone = {
                paramName: "file",
                maxFilesize: 2
            };
        }, methods: {
            save() {
                axios.post(`/save-publication`,{
                    texto: this.actualizacionEstatus
                })
                .then(response => {
                    if (response.data.estatus) {
                        location.reload();
                    }
                }).catch(error => {
                    console.log(error);
                });
            }
        }
    }
</script>

