<template>
	<div class="modal fade" id="pagoExtras" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h5 class="modal-title" id="exampleModalLabel">Nuevo Pago Extra</h5>
					<button type="button" id="cerrModal" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<form @submit.prevent>
						<div class="form-group row">
							<div class="col-sm-12 mb-0">
								<label for="name">Dni</label>
								<div class="input-group mb-3">
									<input type="text" class="form-control" name="dni" id="dni" v-model="form.dni" autocomplete="off" placeholder="Dni del paciente" @keypress.enter="reniec()">
									<button class="btn btn-outline-secondary" type="button" id="button-addon2" @click="reniec()"><i class="fas fa-search"></i></button>
								</div>
							</div>
							<div class="col-sm-12 mb-0">
								<label for="date">Fecha: {{fechaLatam(form.date)}}</label> 
							</div>
							<div class="col-sm-12 " id="divClienteNuevo">
								<div class="form-group">
									<label for="customer">Cliente</label>
									<input type="text" name="customer" required id="customer" class="form-control" v-model="form.customer" autocomplete="off" readonly>
								</div>
							</div>

							<div class="col-sm-12">
								<div class="form-group">
									<label for="price">Precio (S/)</label>
									<input type="number" name="price" required id="price" class="form-control" v-model="form.price">
								</div>
							</div>
							<div class="col-sm-12">
								<label for="type">Tipo de pago</label>
								<select class="form-select" id="type" required name="type" v-model="form.type">
								<!-- 	<option value="0">Certificado</option>
									<option value="1">Paquete de membresia</option>
									<option value="2">Paquete kurame</option>
									<option value="3">Informe</option> -->
									<option value="4">Otros</option>
								</select>
							</div>
							<div class="col-sm-12 mt-2">
								<label for="type">Moneda</label>
								<select class="form-select" id="type" required name="type" v-model="form.moneda">
									<option v-for="(moneda, index) in monedas" :value="index+1">{{moneda}}</option>
								</select>
							</div>
							<div class="col-sm-12" v-if="form.moneda!=1">
								<label for="type">N° de operación</label>
								<input type="text" class="form-control" v-model="form.voucher_issued">
							</div>

							<div class="col-12 mt-2">
								<div class="form-group">
								<label for="name">Observación</label>
								<textarea name="observation" class="form-control" id="observation" rows="2" v-model="form.observation"></textarea>
							</div>
							</div>
						</div>

						<div class="d-flex justify-content-between">
							<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Salir</button>
							<button @click="saveData" type="submit" class="btn btn-outline-primary" data-bs-dismiss="modal"><i class="fas fa-plus-circle"></i> Guardar ingreso</button>
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
			monedas:['Efectivo', 'Depósito bancario',  'POS', 'Aplicativo Yape', 'Banco: BCP', 'Banco: BBVA', 'Banco: Interbank', 'Banco: Nación', 'Banco: Scotiabank', 'Aplicativo Plin', 'Open pay', 'IziPay'],
			form: {
				customer: null,
				price: 0,
				type: 4,
				moneda:1,
				observation: null,
				date: moment().format('YYYY-MM-DD'), continuo:-1
			}
		}
	},

	methods: {
		reniec(){
			this.$swal.fire({
				title: 'Buscando paciente',
				timer: 2500,
				timerProgressBar: true,
				didOpen: () => {
					timerProgressBar: true,
					this.$swal.showLoading()
				},
			})

			this.axios.get("/api/buscar/"+this.form.dni)
			.then(res => {
				if (res.data.patient == null) { //Buscar en reniec
					window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
					this.axios.get(`https://apiperu.dev/api/dni/${this.form.dni}`) //?api_token=${this.token}
					.then(response => {
						console.log(response.data)
						this.paciente.name = response.data.message || `${response.data.data.apellido_paterno} ${response.data.data.apellido_materno} ${response.data.data.nombres}`;
						if (response.data.success) {
							this.patientNew = false

							this.$swal.fire({
								icon: 'success',
								title: 'Okey',
								text: 'Paciente nuevo',
							})
						} else {
							this.$swal.fire({
								icon: 'error',
								title: 'Oops...',
								text: 'DNI no encontrado!',
								footer: 'Vuelve a intentarlo'
							})
						}
					})
					.catch(err => {
						console.error(err)
					})
				}else{ //encontro en la DB
					this.$swal.fire({
						title: 'Buscando paciente',
						timer: 10,
					})
					console.log('datos del paciente',res.data);

					this.form.customer = res.data.patient.name;
				}
			})
			.catch(err => {
				console.error(err)
			})
			.finally(result => {
				this.switchReciec = 1;
				document.querySelector(".btnReniec").classList.replace('btn-danger', 'btn-info')
			})
		},
		async saveData() {
			//console.log('pago',this.form.price);
			if(this.form.price < 0){
				return this.$swal({
					icon:'error',
					title: 'El precio no puede ser 0 o negativo'
				});
			}else if (!this.form.type || !this.form.date){ //!this.form.customer
				return this.$swal({
					icon:'error',
					title: 'Faltan rellenar datos'
				});
			}else{
				this.$swal("Guardando datos")
	
				this.axios.post('/api/paymentExtra', this.form)
				.then(res => {
					this.$parent.actualizar()
					for (let value in this.form) {
						this.form[value] = ''
					}
					this.form['date'] = moment().format('YYYY-MM-DD');
					this.$swal({icon: "success", text: res.data})
				})
				.catch(err => {
					this.$swal('Hubo un error')
				})
			}
			document.querySelector('#pagoExtras #cerrModal').click()
		},
		fechaLatam(fecha) { return moment(fecha).format('DD/MM/YYYY'); },
	}
}
</script>