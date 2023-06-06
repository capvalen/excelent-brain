<template>
	<!-- Modal -->
	<div class="modal fade" id="modalComentarios" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Agregar recomendación</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<label for="">¿Qué desea recomendar a sus colegas?</label>
					<input type="text" class="form-control" autocomplete="off" v-model="texto">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-primary" @click="add()"><i class="fa-regular fa-lightbulb"></i> Agregar recomendación</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default{
	name: 'ModalComentarios',
	data(){ return {
		texto:''
	}},
	prop:{ idProfesional: null, comentarios:Array },
	methods:{
		add(){
			this.axios.post('/api/addRecomendation',{
				texto: this.texto, professional_id: this.$attrs.idProfesional, patient_id: this.$route.params['idPaciente']
			})
			.then(res=> this.$emit('refrescarComentarios')  )
			$('#modalComentarios').modal('hide')
		}
	}
}
</script>