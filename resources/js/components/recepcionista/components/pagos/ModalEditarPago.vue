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
									<select class="form-select" id="pay_status" name="pay_status" v-model="caso.moneda">
									<option v-for="moneda in monedas" :value="moneda.id">{{moneda.tipo}}</option>
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
				caso: {pago:1, moneda:1, comprobante:'', continuo: 1, user_id:-1}, monedas:[]
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
					this.closeModal()
					
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
		mounted() {
			this.axios.get("/api/listarMonedas")
			.then(resp => this.monedas = resp.data)
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

	
