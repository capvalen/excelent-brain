<template>
	<div class="modal fade" id="modalIntercambio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Intercambiar</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					Elija de los siguientes pacientes uno para realizar el intercambio de horarios.
					<select class="form-select text-capitalize" id="" v-model="idCambio">
						<option class="text-capitalize" v-for="(posible, index) in posibles" :value="index">{{posible.patient.name.toLowerCase()}}</option>
					</select>
					<p class="mt-3 mb-0">Se intercambiará por: <strong class="text-capitalize">{{ primero.patient.name.toLowerCase() }}</strong></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="intercambiar()"><i class="fas fa-retweet"></i> Cambiar</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default{
	name: 'ModalIntercambio',
	props:['posibles', 'primero'],
	data(){ return{
		idCambio:-1
	}},
	methods:{
		intercambiar(){
			let elegido = this.posibles[this.idCambio]
			this.axios.post('/api/intercambiar',{ idElegido: elegido.id, horaElegido: elegido.schedule_id , idPrimero: this.primero.id, horaPrimero: this.primero.schedule_id })
			.then(response=>{
				this.$emit('actualizar')
			})
		}
	}
}</script>