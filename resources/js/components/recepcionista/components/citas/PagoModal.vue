<template>
	<div class="modal fade" id="pagoModal" ref="pagoModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content modal-sm">
				<div class="modal-header border-0">
					<h5 class="modal-title" id="exampleModalLabel">Pago de cita</h5>
					<button type="button" id="cerrModal" class="btn-sm btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<form action="">
						<div class="form-group row">
							<div class="col-sm-12">
								<!-- <input type="text" class="form-control" name="price" id="price" v-model="dataCita.payment.price"> -->
								<p class="mb-0"><small>Cuenta de la persona:</small></p>
								<p class="lead text-capitalize mb-2 fw-bold"> <span>{{(dataCita.patient.name).toLowerCase()}} {{(dataCita.patient.nombres).toLowerCase()}}</span></p>
							 	<p class="lead mb-0"><small>Precio a cobrar: S/</small> {{ parseFloat(dataCita.payment.price).toFixed(2) }}</p>
							 	<p v-if="dataCita.payment.rebaja>0" class="lead mb-0"><small>Rebajado: S/</small> {{ parseFloat(dataCita.payment.rebaja).toFixed(2) }}</p>
							 	<p v-if="dataCita.payment.adelanto>0" class="lead mb-0"><small>Adelanto: </small> S/ {{ parseFloat(dataCita.payment.adelanto).toFixed(2) }}</p>
								<p v-if="dataCita.payment.razonAdelanto" class="mb-0"><small>Obs. o Fecha: {{ dataCita.payment.razonAdelanto }}</small></p>
							</div>
							<div v-if="dataCita.byDoctor==1">
								<label for="">Aplicar rebaja (S/)
									<br><small>Máximo: S/ {{(maximo).toFixed(2)}}</small>
								</label>
								<input type="number" class="form-control" min="0" :max="maximo" id="txtrebajaDoctor" v-model="caso.rebaja" @keyup="agregarrebaja()">
							</div>
							
							<div class="col-sm-12 mt-2">
									<label class="mt-2 mb-0" for="">Estado de pago</label>
									<select class="form-select" name="pay_status" id="pay_status" v-model="caso.pago">
										<option value="1">Sin pagar</option>
										<option value="2">Pagado</option>
									</select>
							</div>                                                      
							<div class="col-sm-12">
									<label class="mt-2 mb-0" for="">Método de pago</label>
									<select class="form-select" id="pay_type" required name="pay_type" v-model="caso.moneda">
										<option v-for="moneda in monedas" :value="moneda.id">{{moneda.tipo}}</option>
									</select>
							</div>
							<div class="col-sm-12" v-if="caso.moneda!=1">
								<label for="">N° Comprobante de pago</label>
								<input type="text" class="form-control" v-model="caso.comprobante">
							</div>
					</div>
					<div class="form-group">
								<label for="">Observación</label>
								<textarea class="form-control" name="observation" id="observation" cols="10" rows="2" v-model="dataCita.payment.observation"></textarea>
								<small>{{ pedirObservaciones }}</small>
					</div>
					</form>
				</div>

				<div class="modal-footer border-0">
					<!-- <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button> -->
					<button @click="update()" type="button" class="btn btn-outline-primary"><i class="fas fa-save"></i> Guardar pago</button>
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
				caso: {pago:1, moneda:1, comprobante:'', continuo: 1, user_id:-1, rebaja:0, motivoRebaja:''}, maximo:15, monedas:[], neto:0
			}
		},
		props:{
			cita: Object, idUsuario:null
		},
		mounted() {
			this.axios.get("/api/listarMonedas/")
			.then(resp => this.monedas = resp.data)
		},
		methods:{
			async update() {
				await this.axios.put(`/api/pagarCita/${this.dataCita.id}`, {dataCita: this.dataCita, caso: this.caso})
				.then(res => {
					console.log(res.data)
					this.dataCita.payment.pay_status = this.caso.pago;
					this.closeModal()
					//this.$swal('Pago actualizado con éxito')
					if( this.caso.pago ==2){
						this.$swal.fire({
							title: 'Pago actualizado con éxito',
							icon: 'info',
							showCancelButton: true,
							confirmButtonText:
								`<span>Ver Cupón</span>`,
							cancelButtonText:
								'Salir'
						}).then(result=>{
							if(result.isConfirmed){
								this.abrirCupon();
							}
						})
					}else{
						this.$swal.fire({
							title: 'Pago actualizado con éxito',
							icon: 'info',
							showCancelButton: true,
							cancelButtonText:
								'Salir'
						})
					}
				})
				.catch(err => {
					console.error(err)
				})
			},
			abrirCupon(){
				window.open(`/api/pdfCupon/${this.dataCita.id}`, '_blank');
			},
			closeModal() {
				document.getElementById('cerrModal').click();
			},
			pagoModal(data){
				console.log('pago modal');
			},
			modalDePago(){
				console.log('algo');
			},
			agregarrebaja(){
				if(this.caso.rebaja<0){
					this.caso.rebaja=0
					this.dataCita.payment.price = this.neto
					this.caso.motivoRebaja=''
				}
				else if(this.caso.rebaja !='' || this.caso.rebaja>0){
					if(this.caso.rebaja>this.maximo) this.caso.rebaja = this.maximo
					this.dataCita.payment.price = this.neto -this.caso.rebaja
					this.caso.motivoRebaja= 'Se aplica rebaja porque proviene de programación de profesional'
				}
				else{
					this.dataCita.payment.price = this.neto
					this.caso.motivoRebaja=''
				}
			}
		},
		computed:{
			pedirObservaciones(){
				return  this.caso.motivoRebaja??''
			}
		},
	
		watch:{
			cita: function (){
				this.dataCita = this.cita;
				this.caso.pago = this.dataCita.payment.pay_status;
				this.caso.moneda = this.dataCita.payment.payment_method == undefined ? 1:this.dataCita.payment.payment_method ;
				this.caso.continuo = this.dataCita.payment.continuo;
				this.caso.user_id = this.idUsuario
				this.neto = parseFloat(this.dataCita.payment.price)
			}
		},
		created () {
			this.dataCita = this.cita;
			this.caso.pago = this.dataCita.payment.pay_status;
			this.caso.moneda = this.dataCita.payment.payment_method == undefined ? 1:this.dataCita.payment.payment_method ;
			this.caso.continuo = this.dataCita.payment.continuo;
			this.caso.user_id = this.idUsuario


		},
	}
</script>

	
