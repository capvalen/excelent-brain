<template>
  <div class="modal fade" id="pagoModal" ref="pagoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content modal-sm">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Pago</h5>
          <button type="button" id="cerrModal" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <form action="">
            <div class="form-group row">
              <div class="col-sm-12">
                <!-- <input type="text" class="form-control" name="price" id="price" v-model="dataCita.payment.price"> -->
               <p class="lead"><small>Precio: S/</small> {{ dataCita.payment.price }}</p>
              </div>
              
							<div class="col-sm-12">
                  <label for="">Estado de pago</label>
                  <select class="form-control" name="pay_status" id="pay_status" v-model="caso.pago">
                    <option value="1">Sin cancelar</option>
                    <option value="2">Cancelado</option>
                  </select>
            	</div>                                                      
							<div class="col-sm-12">
                  <label for="">Método de pago</label>
                  <select class="form-control" name="pay_status" id="pay_status" v-model="caso.moneda">
                    <option value="1">Efectivo</option>
                    <option value="4">Aplicativo Yape/Plin</option>
                    <option value="2">Depósito bancario</option>
                    <option value="3">POS</option>
                  </select>
            	</div>                                                      
          </div>
          <div class="form-group">
                <label for="">Observación</label>
                <textarea class="form-control" name="observation" id="observation" cols="10" rows="2" v-model="dataCita.payment.observation"></textarea>
          </div>
          </form>
        </div>

        <div class="modal-footer">
					<!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button> -->
          <button @click="update()" type="button" class="btn btn-primary"><i class="fas fa-save"></i> Guardar pago</button>
          <a target="_blank" :href="`/api/pdfCupon/${dataCita.id}`" v-if="dataCita.payment.pay_status != 1" class="btn btn-outline-success">Cupón</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "modal_pago",
    
    data() {
      return{
        dataCita: null,
				caso: {pago:1, moneda:1}
      }
    },
    methods:{
      async update() {
        await this.axios.put(`/api/appointment/${+this.dataCita.id}`, {dataCita: this.dataCita, caso: this.caso})
        .then(res => {
          console.log(res.data)
					this.dataCita.payment.pay_status = this.caso.pago;
          this.closeModal()
          this.$swal('Pago actualizado con éxito')
        })
        .catch(err => {
          console.error(err)
        })
      },
        
      closeModal() {
        document.getElementById('cerrModal').click();
      },
			pagoModal(data){
				console.log('pago modal');
			}
    },
  
    props:{
      cita: Object
    },  

    computed: {
      updateData () {
        return this.dataCita = this.cita
      }
    },
    
    created () {
      this.updateData
    },
  }
</script>

  
