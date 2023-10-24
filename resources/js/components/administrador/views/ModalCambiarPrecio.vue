<template>
	<div class="modal fade" id="modalCambiarPrecio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Modificar precio</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<label for="">Nombre del servicio</label>
					<input type="text" class="form-control" v-model="precio.descripcion" >
					<label for="">Precio para nuevos pacientes</label>
					<input type="number" class="form-control" v-model="precio.nuevos" >
					<label for="">Precio para pacientes continuos</label>
					<input type="number" class="form-control" v-model="precio.continuos" >
					<div v-if="precio.servicio==0">
						<label for="">N° Sesiones</label>
						<input type="number" class="form-control" v-model="precio.sesiones" >
					</div>
				</div>
				<div class="modal-footer border-0">
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="actualizar()">Actualizar</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import alertify from 'alertifyjs';
export default {
	name: 'ModalCambiarPrecio',
	props:['precio'],
	data() {
		return {
			
		}
	},
	mounted() {
		
	},
	methods: {
		actualizar(){
			this.axios.post('/api/actualizarPrecioAdmin', this.precio)
			.then(res => console.log(res.data) )
		}
	},
}
</script>
<style lang="">
	
</style>