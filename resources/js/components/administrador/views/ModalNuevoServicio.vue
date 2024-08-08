<template lang="">
	<div class="modal fade" id="modalNuevoServicio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo servicio</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<label for="">Nombre del servicio</label>
					<input type="text" class="form-control" v-model="precioNuevo.nombre">
					<label for="">Tipo de servicio</label>
					<select name="" id="" class="form-select" v-model="precioNuevo.tipo">
						<option value="1">Psiquiatría</option>
						<option value="2">Psicología</option>
						<option value="3">Certificabdos</option>
						<option value="6">Nutrición</option>
						<option value="4">Membresía</option>
					</select>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="crearPrecio()">Crear servicio</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import { relativeTimeRounding } from 'moment';

export default {
	name:'NuevoServicio',
	data() {
		return {
			precioNuevo:{
				nombre:'', tipo:1
			}
		}
	},
	methods: {
		crearPrecio(){

			this.axios.post('/api/crearPrecioNuevo', {precioNuevo: this.precioNuevo})
			.then(resp=>{
				//console.log(resp);
				location.reload()
			})
		}
	},
	
}
</script>
<style lang="">
	
</style>