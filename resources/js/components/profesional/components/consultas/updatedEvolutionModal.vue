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
                <textarea class="form-control" id="comment" cols="15" rows="5" v-model="datos.content"></textarea>
              </div>
							<div class="form-group">
								<label for="">Diagnóstico</label>
								<div class="form-group position-relative diagnostico-input" style="min-height: 100px;">
									<div class="position-relative">
										<input type="text" class="form-control" autocomplete="off" name="diagnostic" id="diagnostic"
											v-model="searchCie" @keyup="getDiagnostico">
										
										<!-- v-model="inicialPsiquiatria.diagnostic" -->
										<div class="cie-content rounded overflow-auto" v-if="searchCie.length>0">
											<div v-for="(cie, index) in dataCies" :key="index">
												<span class="w-100 px-2 py-2 cie--hover d-inline-block pointer cie-item"
													
													:data-id="cie.id" @click="addCie(cie)">
													{{ cie.id }} - {{ cie.code }} - {{ cie.description }}
												</span>
											</div>
										</div>
									</div>
									<div class="d-flex flex-gap flex-wrap mt-3 overflow-auto" id="divDiags">
										<div v-for="(cieAgregado, index) in datos.diagnostic" :key="`cie${index}`"
											class="bg-warning rounded text-light p-2">
											{{ cieAgregado.id}} - {{cieAgregado.code}} - {{cieAgregado.description}}
											<span :data-cie="index" class="cie-item ml-2 pointer" @click="deleteCie(index)"  ><i
													class="fas fa-times"></i></span>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="">Tratamiento</label>
								<textarea class="form-control" id="treatment" rows="2" v-model="datos.treatment"></textarea>
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
        datos: { cies:[] },
        dataModal: {},
        autosaveData:'', dataCies:[], searchCie:''
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
			async getDiagnostico() {
				await this.axios.get(`/api/cies/${this.searchCie === '' ? 'a' : this.searchCie}`)
					.then(res => {
						this.dataCies = res.data;
					})
					.catch(err => {
						console.error(err)
					})
			},
			addCie(cie) {
				this.datos.diagnostic.push(cie);
				this.searchCie = ''
			},
			deleteCie(index){
				this.datos.diagnostic.splice(index,1)
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
        this.dataModal.diagnostic = this.datos.diagnostic;
        this.dataModal.treatment = this.datos.treatment;
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
.pointer {
	cursor: pointer;
}

.cie-content {
	width: 100%;
	background-color: #fff;
	border: .3px solid #22222260;
	
	position: absolute;
	z-index: 100;
	transition: visibility 1s normal 3s;
}

</style>
