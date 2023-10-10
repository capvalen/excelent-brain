<template>
	<div class="modal fade" id="modalAmpliarFechaMembresia" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Ampliar fecha</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p>Seleccione la nueva fecha</p>
					<input class="form-control" type="date" v-model="fechaBase" :min="fechaBase">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal" @click="ampliarFecha()"><i class="far fa-edit"></i> Actualizar</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import alertify from 'alertifyjs';

export default{
	name: 'ModalAmpliarFechaMembresia',
	data(){ return {
	}},
	props:['fechaBase', 'queCita'],
	methods:{
		ampliarFecha(){
			this.axios.post('/api/ampliarFechaMembresia', { fecha:this.fechaBase, id: this.queCita})
			.then(res=>{
				if(res.data.mensaje){
					this.$parent.buscarMembresias();
					alertify.notify('<i class="fa-regular fa-calendar-check"></i> Datos actualizados ', 'success', 5)
				}else
				 alertify.notify('<i class="fas fa-bug"></i> Hubo un error actualizando ', 'danger', 5)
			})
		},
	},
	update:{
		
	}
}</script>