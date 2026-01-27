<template>
	<!-- Modal de recetas -->
	<div class="modal fade" id="modalTiemposEspera" tabindex="-1" aria-labelledby="modalEvolution" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header border-0">
						<h5 class="modal-title" id="infoModalLabel">Tiempos de espera</h5>
						<button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
					</div>

					<div class="modal-body">
						<p>Seleccione uno para asignar horario</p>
						<div class="row" v-if="cita">
							<div class="col">
								<label for=""><i class="fa-solid fa-location-dot"></i> Hora de llegada</label>
								<p v-if="cita.entrance?.length>0">Registrado {{ horaLatam(cita.entrance) }}</p>
								<button v-else class="btn btn-outline-primary" @click="registrar('llegada')" data-bs-dismiss="modal"><i class="fa-solid fa-stopwatch"></i> Asignar hora</button>
								<p><small><span class="text-capitalize">{{calcularFaltante}}</span> para su cita</small></p>
							</div>
							<div class="col">
								<label for=""><i class="fa-solid fa-feather-pointed"></i> Hora de atención</label>
								<p v-if="cita.attention?.length>0">Registrado {{ horaLatam(cita.attention) }}</p>
								<button v-else class="btn btn-outline-primary" @click="registrar('atención')" data-bs-dismiss="modal"><i class="fa-solid fa-stopwatch"></i> Asignar hora</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
</template>

<script>
import alertify from 'alertifyjs';
import moment from 'moment'
export default{
	name: 'modalTiemposEspera',
	props:['cita'],
	
	data(){ return{
		entrance: null, attention: null
	}},
	methods:{
		async registrar(tipo){
			switch (tipo) {
				case 'llegada': this.cita.entrance = moment().format('HH:mm:ss'); break;
				case 'atención': this.cita.attention = moment().format('HH:mm:ss'); break;
				default: break;
			}
			await this.axios.post('/api/registrarHora/',{
				tipo,
				idCita: this.cita.id,
				entrance: this.cita.entrance,
				attention: this.cita.attention
			})
				.then(response => {
					if(response.data?.mensaje == 'Ok'){
						this.$emit('actualizar', 'sksks')
						alertify.notify('<i class="fa-regular fa-calendar-check"></i> Datos actualizados' , 'success', 5);
					}
				})
		},
		fechaLatam(fecha){
			return moment(fecha).format('DD/MM/YYYY');
		},
		horaLatam(hora){
			return moment(hora, 'HH:mm:ss').format('hh:mm a');
		},
	},
	computed:{
		calcularFaltante(){
			//if(this.cita){
				moment.locale('es')
				let horaCita = moment(this.cita.date + ' ' + this.cita.schedule.check_time)
				//console.log(horaCita)
				return horaCita.fromNow();
			//}
		},
	}
}
</script>