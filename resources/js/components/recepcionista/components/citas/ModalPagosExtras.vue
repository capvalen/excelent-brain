<template>
  <div class="modal fade" id="pagoExtras" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Pagos Extras</h5>
          <button type="button" id="cerrModal" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <form @submit.prevent>
            <div class="form-group row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="customer">Cliente</label>
                  <input type="text" name="customer" required id="customer" class="form-control"
                    v-model="form.customer">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label for="price">Precio</label>
                  <input type="number" name="price" required id="price" class="form-control" v-model="form.price">
                </div>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-sm-6">
                <label for="type">Tipo de pago</label>
                <select class="form-select" id="type" required name="type" v-model="form.type">
                  <option value="0">Certificado</option>
                  <option value="1">Paquete de membresia</option>
                  <option value="2">Paquete kurame</option>
                  <option value="3">Informe</option>
                  <option value="4">Otros</option>
                </select>
              </div>
              <div class="col-sm-6">
                <label for="type">Moneda</label>
                <select class="form-select" id="type" required name="type" v-model="form.moneda">
                  <option v-for="(moneda, index) in monedas" :value="index+1">{{moneda}}</option>
                </select>
              </div>
              <div class="col-sm-6" v-if="form.moneda!=1">
                <label for="type">N° de operación</label>
                <input type="text" class="form-control" v-model="form.voucher_issued">
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label for="date">Fecha</label>
                  <input type="date" class="form-control" required name="date" id="date" v-model="form.date" readonly="true">
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="form-group">
                <label for="name">Observación</label>
                <textarea name="observation" class="form-control" id="observation" rows="5"
                  v-model="form.observation"></textarea>
              </div>
            </div>

            <div>
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button @click="saveData" type="submit" class="btn btn-outline-primary" data-bs-dismiss="modal">Guardar ingreso</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import moment from 'moment'
export default {
  name: 'PagosExtras',

  data() {
    return {
			monedas:['Efectivo', 'Depósito bancario',  'POS', 'Aplicativo Yape', 'Banco: BCP', 'Banco: BBVA', 'Banco: Interbank', 'Banco: Nación', 'Banco: Scotiabank', 'Aplicativo Plin'],
      form: {
        customer: null,
        price: 0,
        type: null,
				moneda:1,
        observation: null,
        date: moment().format('YYYY-MM-DD'), continuo:-1
      }
    }
  },

  methods: {
    async saveData() {
			console.log('pago',this.form.price);
			if(this.form.price < 0){
				return this.$swal({
					icon:'error',
					title: 'El precio no puede ser 0 o negativo'
				});
			}else if (!this.form.customer || !this.form.type || !this.form.date){
        return this.$swal({
					icon:'error',
					title: 'Faltan rellenar datos'
				});
			}else{
				this.$swal("Guardando datos")
	
				this.axios.post('/api/paymentExtra', this.form)
				.then(res => {
					for (let value in this.form) {
						this.form[value] = ''
					}
					this.form['date'] = moment().format('YYYY-MM-DD');
					this.$swal(res.data)
				})
				.catch(err => {
					this.$swal('Hubo un error')
				})
			}
			document.querySelector('#pagoExtras #cerrModal').click()
    }
  }
}
</script>