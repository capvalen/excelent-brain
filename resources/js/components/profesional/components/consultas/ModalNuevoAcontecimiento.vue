<template>
	<div class="modal fade" id="nuevoAcontecimiento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header border-0">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo acontencimiento</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<label for="">Edad</label>
				<input type="number" class="form-control" v-model="acontecimiento.edad" min="0" step="1">
				<label for="">Acontecimiento</label>
				<input type="text" class="form-control" v-model="acontecimiento.acontecimiento">
				<label for="">Síntomas</label>
				<input type="text" class="form-control" v-model="acontecimiento.sintomas">
			</div>
			<div class="modal-footer border-0">
				<button type="button" class="btn btn-outline-success" data-bs-dismiss="modal" @click="guardar()"><i class="far fa-save"></i> Agregar</button>
			</div>
		</div>
	</div>
</div>
</template>
<script>
export default{
	name: 'nuevoAcontecimiento',
	props:['idPaciente', 'idProfesional'],
	data(){ return {
		acontecimiento:{}
	}},
	methods:{
		guardar(){
			this.axios.post('/api/crearAcontecimiento', {acontecimiento: this.acontecimiento, idProfesional: this.idProfesional, idPaciente:this.idPaciente})
			.then(res=> {
				this.$parent.cargarLineas()
				alertify.notify('<i class="fa-regular fa-calendar-check"></i> ' + res.data.mensaje, 'success', 5);
			})
			
		}
	}
}</script>