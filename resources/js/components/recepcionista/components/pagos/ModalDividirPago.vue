<template>
	<div class="modal fade" id="modalDividirPago"  tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content modal-sm">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Dividir Pago</h5>
					<button type="button" id="cerrModalDividir" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<form action="">
						<div class="form-group row">
							<div class="col-sm-12">
								<label for="">Cliente</label>
								<p class="small">{{ pago.customer }}</p>
							</div>
							<div class="col-sm-12">
								<label for="">Monto base</label>
								<p class="small">S/ {{ parseFloat(pago.price).toFixed(2) }}</p>
							</div>
							<div class="col-sm-12">
								<label for="">Nuevo monto</label>
								<input type="numeric" class="form-control" v-model="caso.monto" @change="minimo()">
							</div>
							<div class="col-sm-12">
								<label for="">Nuevo método de pago</label>
								<select class="form-select" id="pay_status" name="pay_status" v-model="caso.moneda">
									<option v-for="moneda in monedas" :value="moneda.id">{{moneda.tipo}}</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="">Motivo de la división</label>
							<textarea class="form-control" name="observation" id="observation" cols="10" rows="2" v-model="caso.observacion"></textarea>
						</div>
					</form>
				</div>

				<div class="modal-footer">
					<!-- <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button> -->
					<button @click="save()" data-dismiss="modal" type="button" class="btn btn-primary"><i class="fas fa-save"></i> Dividir pago</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import alertify from 'alertifyjs'

	export default {
		name: "modalDividirPago",
		
		data() {
			return{
				caso: {monto:0, moneda:1, comprobante:'', observacion: ''}, monedas:[]
			}
		},
		props:{
			pago: Object, idUsuario:null
		},
		methods:{
			async save() {
				if(this.caso.monto <=0 ){
					alertify.notify('<i class="fas fa-divide"></i> El nuevo monto no está correcto' , 'danger', 5);
					return;
				}
				if(this.caso.observacion==''){
					alertify.notify('<i class="fas fa-divide"></i> Falta un motivo' , 'danger', 5);
					return;
				}

				await this.axios.put(`/api/dividirPago/${this.pago.id}`, { caso: this.caso, idUsuario: this.idUsuario })
				.then(res => {		
					this.closeModal()			
					this.$swal.fire({
						title: 'Pago actualizado con éxito',
						icon: 'info',
						showCancelButton: true,
						cancelButtonText:
							'Salir'
					})
					this.$parent.actualizar();
				})
				.catch(err => {
					console.error(err)
				})
			},
			minimo(){
				if(this.caso.monto >= parseFloat(this.pago.price) ){
					this.caso.monto = 0
					alertify.notify('<i class="fas fa-divide"></i> El nuevo monto no puede ser igual o mayor que el precio base' , 'danger', 5);
				}
			},
			closeModal() {
				document.getElementById('cerrModalDividir').click();
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
		},
		created () {			
		},
	}
</script>
	
