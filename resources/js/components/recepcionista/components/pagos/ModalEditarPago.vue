<template>
	<div class="modal fade" id="modalEditarPago"  tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content modal-sm">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Editar Pago</h5>
					<button type="button" id="cerrModal" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<form action="">
						<div class="form-group row">                                                    
							<div class="col-sm-12">
									<label for="">Método de pago</label>
									<select class="form-select" name="pay_status" id="pay_status" v-model="caso.moneda">
										<option value="1">Efectivo</option>
										<option value="4">Aplicativo Yape</option>
										<option value="10">Aplicativo Plin</option>
										<option value="5">Banco: BCP</option>
										<option value="6">Banco: BBVA</option>
										<option value="7">Banco: Interbank</option>
										<option value="8">Banco: Nación</option>
										<option value="9">Banco: Scotiabank</option>
										<option value="2">Depósito bancario</option>
										<option value="12">IziPay</option>
										<option value="11">Open Pay</option>
										<option value="3">POS</option>
									</select>
							</div>
							<div class="col-sm-12">
								<label for="">Boleta / Factura</label>
								<input type="text" class="form-control" v-model="caso.boleta">
							</div>
							<div class="col-sm-12">
								<label for="">Comprobante de pago</label>
								<input type="text" class="form-control" v-model="caso.comprobante">
							</div>
					</div>
					<div class="form-group">
								<label for="">Observación</label>
								<textarea class="form-control" name="observation" id="observation" cols="10" rows="2" v-model="caso.observation"></textarea>
					</div>
					</form>
				</div>

				<div class="modal-footer">
					<!-- <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button> -->
					<button @click="update()" type="button" class="btn btn-primary"><i class="fas fa-save"></i> Guardar pago</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		name: "modalEditarPago",
		
		data() {
			return{
				dataCita: null,
				caso: {pago:1, moneda:1, comprobante:'', continuo: 1, user_id:-1},
			}
		},
		props:{
			cita: Object, idUsuario:null
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
			}
		},
	
		watch:{
			cita: function (){
				this.dataCita = this.cita;
				this.caso.pago = this.dataCita.payment.pay_status;
				this.caso.moneda = this.dataCita.payment.payment_method == undefined ? 1:this.dataCita.payment.payment_method ;
				this.caso.continuo = this.dataCita.payment.continuo;
				this.caso.user_id = this.idUsuario
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

	
