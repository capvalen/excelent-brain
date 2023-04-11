<template>
  <div class="modal fade" id="pagoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Pago</h5>
          <button type="button" id="cerrModal" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <form action="">
            <!-- <div class="form-group row">

              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Comprobante Emitido</label>
                  <select class="form-control" name="voucher_issued" id="voucher_issued" v-model="dataCit.payment.voucher_issued">
                    <option value="Contrato">Contrato</option>
                    <option value="Boleta">Boleta</option>
                    <option value="Factura">Factura</option>
                    <option value="Ninguno">Ninguno</option>
                  </select>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Medio de pago</label>
                  <select class="form-control" name="payment_method" id="payment_method" v-model="dataCit.payment.payment_method">
                    <option value="Efectivo">Efectivo</option>
                    <option value="Depósito">Depósito</option>
                    <option value="Izipay">Izipay</option>
                    <option value="Yape">Yape</option>
                  </select>
                </div>
              </div>                                                       
            </div> -->

            
            <!-- <div class="form-group row">
              <div class="col-sm-6">
                <label for="name">Codigo de Boleta o Voucher</label>
                <input type="text" class="form-control" name="voucher" id="voucher" v-model="dataCit.payment.voucher">
              </div> 
              
              <div class="col-sm-6">
                <label for="name">Entidad Bancaria</label>
                <select class="form-control" name="bank" id="bank" v-model="dataCit.payment.bank">
                    <option value="BBVA">BBVA</option>
                    <option value="BCP">BCP</option>
                    <option value="BANCO_NACION">BANCO DE LA NACION</option>
                    <option value="INTERBANK">INTERBANK</option>
                    <option value="SCOTIABANK">SCOTIABANK</option>
                    <option value="Otro">Otro</option>
                  </select>
              </div>                                                      
            </div>   -->                       
            
            <div class="form-group row">
              <div class="col-sm-6">
                <label for="name">Precio</label>
                <!-- <input type="text" class="form-control" name="price" id="price" v-model="dataCit.payment.price"> -->
               <p>S./ {{ dataCit.payment.price }}</p> 
              </div>
              
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Estado de pago</label>
                  <select class="form-control" name="pay_status" id="pay_status" v-model="dataCit.payment.pay_status">
                    <option value="1">Sin cancelar</option>
                    <option value="2">Cancelado</option>
                  </select>
                </div>
            </div>                                                      
          </div>
          <div class="form-group">
                <label for="">Observacion</label>
                <textarea class="form-control" name="observation" id="observation" cols="10" rows="3" v-model="dataCit.payment.observation"></textarea>
          </div>
          </form>
        </div>

        <div class="modal-footer">
          <a target="_blank" :href="`/api/pdfCupon/${dataCit.id}`" v-if="dataCit.payment.pay_status != 1" class="btn btn-success">Cupón</a>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          <button  @click="update()" type="button" class="btn btn-primary">Editar</button>
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
        data: null
      }
    },

    methods:{
      async update() {
        await this.axios.put(`/api/appointment/${+this.dataCit.id}`, this.dataCit)
        .then(res => {
          console.log(res.data)
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
    },
  
    props:{
      dataCit: Object
    },  

    computed: {
      updateData () {
        return this.data = this.dataCit
      }
    },
    
    created () {
      this.updateData
    },
  }
</script>

  
