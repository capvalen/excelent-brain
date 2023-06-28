<template>
  <div class="modal fade" id="updatedModal" tabindex="-1" aria-labelledby="modalEvolution" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="infoModalLabel">Editar evolución</h5>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
          </div>

          <div class="modal-body">
            <form @submit.prevent >
              <div class="row form-group">
                <div class="col">
                  <label for="professional">Profesional</label>
                  <input type="text" class="form-control" id="professional" readonly :value="datosModal.professional ? datosModal.professional.name : '...'">
                </div>

                <div class="col">
                  <label for="type">Tipo</label>
                  <input type="text" class="form-control" id="type" readonly :value="datosModal.professional ? datosModal.professional.profession : '...'" >
                </div>
              </div>

              <div class="row form-group">
                <div class="col">
                  <label for="date">Fecha</label>
                  <input type="date" class="form-control" id="date" readonly :value="datosModal ? datosModal.date : '...'" >
                </div>
                <div class="col">
                  <label for="time">Hora</label>
                  <input type="time" class="form-control" id="time" readonly :value="datosModal ? getTiempo() : '...'" >
                </div>
              </div>

              <div class="form-group">
                <label for="comment">Comentario
                    <button @click="voice()" id="start-btn" class="btn btn-danger btn-circle btn-sm btn-block ml-2">
                        <i id="icon-mic" class="fas fa-microphone-slash"></i>
                    </button>
                </label>
                <textarea class="form-control" id="comment" cols="30" rows="10" v-model="datos.content"></textarea>
              </div>

             <!--  <div class="row form-group">
                <div class="col">
                  <label for="date">Fecha de próxima cita</label>
                  <input type="date" class="form-control" v-model="dataModal.next">
                </div>
                <div class="col"></div>
              </div> -->

              <div class="modal-footer">
                  <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Salir</button>
                  <button type="submit" class="btn btn-outline-danger" data-bs-dismiss="modal" @click="updatedEvolution"><i class="fa-solid fa-rotate-right"></i> Actualizar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
  export default {
    name: 'updateModalEvolution',

    data () {
      return {
        datos: {},
        dataModal: {},
        autosaveData:''
      }
    },
    watch:{
      'datos.content'(newValue){
        localStorage.setItem(this.datos.id, newValue);
      }
    },
    methods: {
      voice(){
        document.getElementById('start-btn').classList.replace('btn-danger','btn-primary')
        document.getElementById('icon-mic').classList.replace('fa-microphone-slash','fa-microphone')

        // initialisation of voicereco
        window.SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        const recognition = new window.SpeechRecognition();
        recognition.lang = "es-PE";
        recognition.interimResults = true;
        var textbox = document.getElementById("comment");
        let total_voice=' '
        // event current voice reco word

        recognition.addEventListener("result", event => {

            var current = event.resultIndex
            console.log(event.results[current][0].transcript)
            var transcript = event.results[current][0].transcript

            total_voice = transcript

        });
        //this.datos.content = ''
        // end of transcription
        recognition.addEventListener("end", event => {
          this.datos.content +=' '+ total_voice
          document.getElementById('icon-mic').classList.replace('fa-microphone','fa-microphone-slash')
          document.getElementById('start-btn').classList.replace('btn-primary','btn-danger')
          recognition.stop();
        });

        recognition.start();

      },
      async updatedEvolution () {
        this.$swal({
              title: 'Guardando...',
              showConfirmButton: false,
              icon:'info',
              isDismissed: false
            })
        await this.axios.put(`/api/evolution/${this.datos.id}`, this.dataModal)
        .then(res => {
          this.$swal({
              title: 'Evolución guardada exitosamente',
              icon: 'success'
            })
        })
        .catch(err => {
          if(err.response.status == 500){
            this.$swal({
              title: 'Hubo un error al subir la evolución',
              text: 'La información se almacenó en el autoguardado.',
              icon: 'error'
            })
            /* console.log(err.response.config)
            const url = localStorage.setItem("url", err.response.config.url);
            this.autosaveData = localStorage.setItem("info", err.response.config.data); */
          }
          
        })      
      },

      getTiempo () {
        return `${new Date().getHours().toString().length == 1 ? '0' + new Date().getHours() : new Date().getHours()}:${new Date().getMinutes().toString().length == 1 ? '0' + new Date().getMinutes() : new Date().getMinutes()}:${new Date().getSeconds().toString().length == 1 ? '0' + new Date().getSeconds() : new Date().getSeconds()}`
      },
    },

    props: {
      datosModal: Object
    },

    computed: {
      updatedData () {
        this.datos = this.datosModal;
        this.dataModal.content = this.datos.content;
        this.dataModal.schedule = this.getTiempo();
      }
    },

    updated () {
      this.updatedData
    },

    mounted () {
      this.updatedData
    }
  }
</script>

<style>

</style>
