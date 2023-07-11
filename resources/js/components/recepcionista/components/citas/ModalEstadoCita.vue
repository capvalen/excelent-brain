<template>
  <div class="modal fade" id="modalEstado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Estado de la cita {{dataCit.id}}</h5>
          <button type="button" id="cerrModalEstado" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          <form action="">                                                                      
							<label for="">Estado</label>
							<select class="form-select status-appointment" name="status" id="status" v-model="dataCit.status">
								<option value="1">Sin Confirmar</option>
								<option value="2">Confirmar cita</option>
								<option value="3">Anular cita</option>
							</select>
							<div class="mt-2" v-if="dataCit.status==3">
								<label for="">Motivo</label>
								<input type="text" class="form-control" v-model="motivo">
							</div>                                                                                           
          </form>
        </div>

        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Salir</button>
          <button  @click="update()" type="button" class="btn btn-outline-primary">Actualizar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import alertify from 'alertifyjs';
  export default {
    name: "modal_estado",
    data() {
      return{
        data: null, motivo : ''
      }
    },

    methods:{
      update(){
				if(document.querySelector(".status-appointment").value == 3 && this.motivo==''){
					alertify.notify('<i class="fa-solid fa-skull-crossbones"></i> Debe ingresar un motivo para anular la cita' , 'danger', 10);
				}else{
					if (document.querySelector(".status-appointment").value == 3) {
						this.data.schedule_id = null;
						this.updateStatuAppointment()
					} else {
						this.updateStatuAppointment()
					}
				}
				
      },

      async updateStatuAppointment () {
        console.log('id_appointment',this.dataCit.id)
        await this.axios.post(`/api/updateStatus/${this.dataCit.id}/${this.dataCit.status}`,{dataCit: this.dataCit, motivo: this.motivo})
        .then(res => {
          console.log("actualizado",res.data)
          this.closeModal()
          this.$swal('Cita editada con éxito')
          //this.$parent.searchHistoria()
          this.motivo=''
        })
        .catch(err => {
            console.error(err)
          })
      },


      closeModal() {
        document.getElementById('cerrModalEstado').click();
      },
    },
  
    props:{
      dataCit: Object
    },  

    computed: {
      updateStatu () {
        return this.data = this.dataCit
      }
    },

    created () {
      this.updateStatu
    }
  }
</script>

  
