<template>
	
	<div class="modal fade" id="modalPagarDeuda" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-sm ">
			<div class="modal-content">
				<div class="modal-header border-0 pb-0">
					<h5 class="modal-title " id="exampleModalLabel">Procesar pago</h5>
					<button type="button" id="closeModal" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
				</div>
				<div class="modal-body">
					<p>A favor de <strong class="text-capitalize">{{ deuda.name }}</strong></p>
					<p>Esta por hacer una modificación de la membresía <strong>{{ deuda.motivo }}</strong></p>
					<p>Monto S/ <strong>{{ parseFloat(deuda.monto).toFixed(2) }}</strong></p>
					<label for="">Opciones</label>
					<select class="form-select" id="sltTipo" v-model="respuesta.estado">
						<option value="2">Realizó el pago</option>
						<option value="3">No pagará</option>
					</select>
					<div>
						<label for="">¿Alguna observación?</label>
						<input type="text" class="form-control" id="txtRespuesta" v-model="respuesta.respuesta" autocomplete="off">
					</div>
					<p><small><strong>Ojo:</strong> Este pago será registrado automáticamente en caja el día de hoy con su nombre de usuario</small></p>
				</div>
				<div class="modal-footer border-0">
					<button type="button" class="btn btn-primary" @click="guardar()">Registrar el pago</button>
				</div>
			</div>
		</div>
	</div>

</template>
<script>
	import alertify from 'alertifyjs'

	export default{
		name: 'ModalPagarDeuda',
		props:{ usuario: null, deuda:null},
		data(){
			return{
				respuesta:{ estado:3, respuesta:'' }
			}
		},
		methods:{
			async guardar(){
				let datos = new FormData();
				datos.append('idDeuda', this.deuda.idDeuda )
				datos.append('idPaciente', this.deuda.id )
				datos.append('estado', this.respuesta.estado )
				datos.append('nombre', this.deuda.name )
				datos.append('precio', this.deuda.monto )
				datos.append('motivo', 'Cuota de '+this.deuda.motivo )
				datos.append('observacion', this.respuesta.respuesta )
				datos.append('tipo', this.deuda.idPago )//tabla pagos
				datos.append('user_id', this.usuario )

				await axios.post('/api/pagarDeudaMembresia', datos)
				.then(texto=>{
					console.log(texto.data);
					this.$parent.cargarDatos('deudas');
					if(texto.data.mensaje){
						this.respuesta.respuesta=''
						this.cerrarModal()
						this.$swal(texto.data.mensaje);
					}else{
						this.$swal({icon:'error',title: 'Hubo un error interno'});
					}
				})
			},
			cerrarModal(){
				document.querySelector("#modalPagarDeuda #closeModal").click()
			}
		},
		watch:{
			
		}
	}
</script>