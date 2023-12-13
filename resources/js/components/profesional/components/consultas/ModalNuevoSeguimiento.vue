<template lang="">
	<div class="modal fade" id="modalNuevoSeguimiento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo seguimiento</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p>Rellene para agregar un nuevo seguimiento</p>
					<textarea class="form-control" id="" cols="30" rows="5" v-model="seguimiento"></textarea>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal" @click="guardar()"><i class="fas fa-plus"></i> Guardar seguimiento</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default {
	props:['idEvolucion', 'idProfesional'],
	data() {
		return {
			seguimiento:''
		}
	},
	methods: {
		guardar(){
			this.axios.post('/api/guardarSeguimiento', {seguimiento: this.seguimiento, idEvolucion:this.idEvolucion, idProfesional: this.idProfesional})
			.then(resp=> {
				this.seguimiento = ''
			} )
		}
	},
}
</script>
<style lang="">
	
</style>