<template>
      <v-dialog
      v-model="dialog"
      persistent
      width="50%"
    >
      <v-card
        color="primary"
        dark
      >
        <v-card-text align="center">
          Por favor espere...
          <v-progress-linear
            indeterminate
            color="white"
            class="mb-0"
          ></v-progress-linear>
        </v-card-text>
      </v-card>
    </v-dialog>

</template>

<script>
export default {
        data(){
            return {
                dialog: true
            }
        },
    }
</script>