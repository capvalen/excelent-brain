<template>
	<!-- Modal -->
	<div class="modal fade" id="modalProximaCita" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-sm">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Programación de cita</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body py-0 border-0">
					<p class="my-2"><strong>Profesional:</strong> </p>
					<p class="my-2"> <span>{{profesional.name}}</span></p>
					<p class="my-2"><strong>Clase:</strong> <span>{{profesional.profession}}</span></p>
					<p class="my-2"><strong>Paciente:</strong> </p>
					<p class="my-2"><span class="text-capitalize">{{paciente.name.toLowerCase()}}</span></p>
					
					<label for="">Tipo de servicio</label>
					<select  class="form-select" name="type" id="sltServicio" v-model="cita.idServicio" >
						<option v-for="precio in precios" :value="precio.id" v-if="precio.idClasificacion== profesional.idProfesion && ![1,2,7,13].includes( precio.id)">{{ precio.descripcion }}</option>
					</select>
					<label class="">Fecha</label>
					<input type="date" class="form-control" v-model="fecha" @change="listarHorario()">
					<label class="mt-2">Horario</label>
					<select name="" id="" class="form-select" v-model="cita.idHora">
						<option value="" disabled selected>Selecciona un horario</option>
						<option v-for="hora in horarios" :key="hora.id" :value="hora.id">
							{{ horaLatam1(hora.check_time) }} - {{ horaLatam2(hora.departure_date) }}
						</option>
					</select>
					<label class="mt-2">Modalidad</label>
					<div class=" form-switch">
						<input class="form-check-input" type="checkbox" role="switch" id="flePresencial" v-model="esPresencial" >
						<label class="form-check-label" for="flePresencial">
							<span v-if="esPresencial"><i class="far fa-user"></i> Reunión presencial</span>
							<span v-else><i class="fas fa-desktop"></i> Reunión virtual</span>
						</label>
					</div>
					
				</div>
				<div class="modal-footer border-0">
					<button type="button" class="btn btn-outline-primary" @click="separarCita()" data-bs-dismiss="modal"><i class="fa-regular fa-floppy-disk"></i> Registrar</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import alertify from 'alertifyjs';
import moment from 'moment';

export default{
	name: 'modalProximaCita',
	data(){ return {
		esPresencial:true, data:[], horarios:[], horariosAll:[], schedulesInvalid:[], hoursProfessional:[], fecha: moment().format('YYYY-MM-DD'), precios:[],
		cita:{idServicio:null, }
	}},
	props:['profesional', 'paciente'],
	methods:{
		separarCita(){
			if(!this.cita.idServicio)
				alertify.notify('Se debe seleccionar un servicio', 'danger', 10)
			else if(!this.fecha)
				alertify.notify('Se debe seleccionar una fecha', 'danger', 10)
			else if(!this.cita.idHora)
				alertify.notify('Se debe seleccionar un horario', 'danger', 10)
			else{
				const quePrecio = this.precios.find(x=> x.id == this.cita.idServicio).continuos
				
				this.axios.post('/api/reservarCitaDoctor',{
					date: this.fecha,
					type: this.cita.idServicio,
					patient_condition: 2, //paciente antigüo
					mode: this.esPresencial ? 1: 2,
					status:1,// sin confirmar
					clasification: this.profesional.idProfesion,
					recomendation: 'Auto Generado por Profesional '+ this.profesional.name,
					professional_id: this.profesional.id,
					patient_id: this.paciente.id,
					schedule_id: this.cita.idHora,
					formato_nuevo: 1,
					byDoctor:1,
					precio: quePrecio,
				}).then(response=>{ console.log(response.data);
					if( response.data.mensaje )
						alertify.notify('Reservado con éxito', 'success', 10)
					else
						alertify.notify('Hubo un error guardando la reserva', 'danger', 10)
				})

			}
		},
		async listarPrecios(){
			await this.axios.get('/api/listarPrecios')
			.then( response => this.precios = response.data)
		},
		async listarHorario() {
			
			await this.axios.get(`/api/horario/${this.profesional.id}`)
			.then(res => { 
				this.horarios = res.data.schedulesInvalid;       
				this.horariosAll = res.data.schedules;       
				this.hoursProfessional = this.horarios;

				this.emitSchedule(this.fecha);

				this.schedulesInvalid = []
				this.hoursProfessional.forEach(el => {
					this.schedulesInvalid.push(el.schedule_id)
				});
			}) 
			.catch(err => {
				console.error(err)
			})
		},
		emitSchedule (info) {
			this.horarios = []

			let arraySchedulesInvalid = []
			this.hoursProfessional.forEach(el => {
				if (!arraySchedulesInvalid.includes(el.schedule_id)) {
					arraySchedulesInvalid.push(el.schedule_id)
				}
			})

			this.horariosAll.forEach(el => {
				if (el.day === this.dayWeek(new Date(info).getDay())) {
					if (arraySchedulesInvalid.includes(el.id)) {
						// Hay cita
						//console.log("Hya citas")

						//console.log(el.appointments.forEach(el => console.log(el.date, info, el.status)))
						if (el.appointments.find(el => el.date === info && el.status != 3) ? true : false) {
							
						} else {
							this.horarios.push(el)
						}
					} else {
						//console.log("No Hya citas")
						// No hay cita
						this.horarios.push(el)
					}
				}
			})
		},
		dayWeek (day) {
			switch (day) {
				case 0: return "Lunes"; break;
				case 1: return "Martes"; break;
				case 2: return "Miercoles"; break;
				case 3: return "Jueves"; break;
				case 4: return "Viernes"; break;
				case 5: return "Sabado"; break;
				case 6: return "Domingo"; break;
			}
		},
		horaLatam1(horita){ return moment(horita, 'HH:mm:ss').format('hh:mm') },
		horaLatam2(horita){ return moment(horita, 'HH:mm:ss').format('hh:mm a') },
	},
	mounted(){
		this.listarPrecios()
	},
	computed:{
	
	}
}
</script>
<style scoped>
	.modal-content { color: #555; }
</style>