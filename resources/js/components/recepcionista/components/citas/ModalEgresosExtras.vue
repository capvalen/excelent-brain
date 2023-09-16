<template>
  <div class="modal fade" id="egresosExtras" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="exampleModalLabel">Egresos Extras</h5>
          <button type="button" id="cerrModal" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
					<form @submit.prevent>
						<div class="form-group row mb-0">
							<div class="col-sm-12 mb-0">
									<label for="date">Fecha: {{fechaLatam(form.date)}}</label> 
							</div>
              <div class="col-sm-12 d-none">
                <div class="form-group">
                  <label for="customer">Cliente</label>
                  <input type="text" name="customer" required id="customer" class="form-control"
                    v-model="form.customer">
                </div>
              </div>

              <div class="col-sm-12">
                <div class="form-group">
                  <label for="price">Precio</label>
                  <input type="number" name="price" required id="price" class="form-control" v-model="form.price">
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="price">Comprobante</label>
                  <select class="form-select" v-model="form.comprobante" @change="cambiarComprobante()">
										<option value="0">Sin comprobante</option>
										<option value="1">Boleta</option>
										<option value="2">Factura</option>
									</select>
                </div>
              </div>
              <div class="col-sm-12" v-show="form.comprobante!=0">
                <div class="form-group">
                  <label for="price">Serie - Correlativo</label>
                  <input type="text" class="form-control" v-model="form.serie">
                </div>
              </div>
            </div>

						

            <div class="form-group row mb-2">
							<div class="col-sm-12">
								<label for="type">Tipo de pago</label>
								<select class="form-select" id="type" required name="type" v-model="form.type">
									<option value="6" selected>Salida de dinero</option>
								</select>
							</div>
							
              
            </div>

            <div class="form-group">
              <div class="form-group">
                <label for="name">Observación</label>
                <textarea name="observation" class="form-control" id="observation" rows="2"
                  v-model="form.observation"></textarea>
              </div>
            </div>

            <div class="d-flex justify-content-between">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Salir</button>
              <button @click="saveData" type="submit" class="btn btn-outline-danger" data-bs-dismiss="modal"><i class="fas fa-minus-circle"></i> Guardar egreso</button>
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
  name: 'ModalEgresosExtras',

  data() {
    return {
      form: {
        customer: null, comprobante:0,
        price: 0, serie:'', boleta:'',
        type: 6,
        observation: null,
        date: moment().format('YYYY-MM-DD')
      }
    }
  },

  methods: {
    async saveData() {
			if(this.form.price < 0){
				return this.$swal({
					icon:'error',
					title: 'El precio no puede ser 0 o negativo'
				});
			}else if ( !this.form.type || !this.form.date){
        return this.$swal({
					icon:'error',
					title: 'Faltan rellenar datos'
				});
			}else{
				
				switch (this.form.comprobante) {
					case '0': this.form.boleta = ''; break;
					case '1': this.form.boleta = 'Boleta'; break;
					case '2': this.form.boleta = 'Factura'; break;
					default:break;
				}
				this.$swal("Guardando datos")
	
				this.axios.post('/api/egresoExtra', this.form)
				.then(res => { console.log(res);
					this.$parent.actualizar()
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
    },
		cambiarComprobante(){
			if(this.form.comprobante==0) this.form.serie=''
		},
		fechaLatam(fecha) { return moment(fecha).format('DD/MM/YYYY'); },

  }
}
</script>