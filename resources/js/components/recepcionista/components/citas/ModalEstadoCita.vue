<template>
  <div class="modal fade" id="modalEstado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Estado de la cita {{dataCit.id}}</h5>
          <button type="button" id="cerrModalEstado" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          <form action="">
            <div class="form-group row">                                                                           
              <div class="col-sm-12">
                <div class="form-group">                                 
                  <select class="form-select status-appointment" name="status" id="status" v-model="dataCit.status">
                    <option value="1">Sin Confirmar</option>
                    <option value="2">Confirmado</option>
                    <option value="3">Cancelado</option>
                  </select>
									<div v-if="dataCit.status==3">
										<label for="">Motivo</label>
										<input type="text" class="form-control" v-control="motivo">
									</div>
                </div>                                                                                             
              </div>
            </div>
          </form>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          <button  @click="update()" type="button" class="btn btn-primary">Actualizar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
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
					alert('Debe ingresar un motivo para anular la cita')
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

  
