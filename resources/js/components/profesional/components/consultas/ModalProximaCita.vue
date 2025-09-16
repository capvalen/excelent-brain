<template>
	<!-- Modal -->
	<div class="modal fade" id="modalProximaCita" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-sm">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Programación de citas</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body py-0 border-0">
					<p class="my-2"><strong>Profesional:</strong> </p>
					<select class="form-select" id="sltProfesional" v-model="idProfesional" @change="listarHorario(); filtrarPrecios()">
						<option value="-1">Seleccionar profesional</option>
						<option v-for="prof in profesional" :value="prof.id">{{ prof.nombre }}</option>
					</select>
					<p class="my-2"><strong>Paciente:</strong> </p>
					<p class="my-2"><span class="text-capitalize">{{paciente.name.toLowerCase()}}</span></p>
					
					<label for="">Tipo de servicio</label>
					<select class="form-select" id="sltPreciosv2" v-model="cita.idServicio">
						<option value="-1">Seleccione un servicio</option>
						<option v-for="precio in preciosFiltrados" :value="precio.id">{{ precio.descripcion }}</option>
					</select>
					<label class="">Fecha</label>
					<input v-if="tipoMembresia()=='sesiones'" type="date" class="form-control text-primary" v-model="fecha" @change="listarHorario()">
					<input v-if="tipoMembresia()=='tiempo'" type="date" :max="membresia.fin" class="form-control text-danger" v-model="fecha" @change="listarHorario()">
					<label class="mt-2">Horario</label>
					<select name="" id="" class="form-select" v-model="cita.idHora">
						<option value="" disabled selected>Selecciona un horario</option>
						<option v-for="hora in horarios" :key="hora.id" :value="hora.id">
							{{ horaLatam1(hora.check_time) }} - {{ horaLatam2(hora.departure_date) }}
						</option>
					</select>
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
		esPresencial:true, data:[], horarios:[], horariosAll:[], schedulesInvalid:[], hoursProfessional:[], fecha: moment().format('YYYY-MM-DD'), precios:[], preciosFiltrados:[],
		cita:{idServicio:null, }, idProfesional:null
	}},
	props:['profesional', 'paciente', 'idMembresia', 'idServicio', 'membresia'],
	methods:{
		separarCita(){
			if(!this.fecha)
				alertify.notify('Se debe seleccionar una fecha', 'danger', 10)
			else if(!this.cita.idHora)
				alertify.notify('Se debe seleccionar un horario', 'danger', 10)
			else{
				const quePrecio = 0// this.precios.find(x=> x.id == this.cita.idServicio).nuevos
				const queProfesional = this.profesional.find(x=> x.id = this.idProfesional)
				
				this.axios.post('/api/reservarCitaDoctor',{
					date: this.fecha,
					type: this.cita.idServicio,
					idMembresia : this.idMembresia,
					patient_condition: 2, //paciente antigüo
					mode: this.esPresencial ? 1: 2,
					status:1,// sin confirmar
					clasification: queProfesional?.idProfesion,
					recomendation: 'Auto Generado por Profesional '+ queProfesional.name,
					professional_id: this.idProfesional,
					patient_id: this.paciente.id,
					schedule_id: this.cita.idHora,
					formato_nuevo: 1,
					byDoctor:1,
					precio: 0
				}).then(response=>{ console.log(response.data);
					this.idProfesional = -1
					this.cita.idHora = ''
					if( response.data.mensaje )
						alertify.notify('Reservado con éxito', 'success', 10)
					else
						alertify.notify('Hubo un error guardando la reserva', 'danger', 10)
				})

			}
		},
		filtrarPrecios(){
			console.log('filtrarPrecios')
			if( this.idProfesional>0 ){
				let indexProf = this.profesional.findIndex(x=> x.id == this.idProfesional)
				console.log('indexprof',indexProf)
				this.preciosFiltrados = this.precios.filter(x=> x.idClasificacion == this.profesional[indexProf].idProfesion)
			}
		},
		async listarPrecios(){
			const resp = await this.axios.get('/api/listarPrecios')
			this.precios = await resp.data
		},
		async listarHorario() {
			
			await this.axios.get(`/api/horario/${this.idProfesional}`)
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
		tipoMembresia(){
			let membresia = this.membresia
			if(membresia.meses > 0 ) return 'tiempo'
			if(membresia.sesiones > 0) return 'sesiones'
		},
		horaLatam1(horita){ return moment(horita, 'HH:mm:ss').format('hh:mm') },
		horaLatam2(horita){ return moment(horita, 'HH:mm:ss').format('hh:mm a') },
	},
	mounted(){
		this.listarPrecios()
	},
}
</script>
<style scoped>
	.modal-content { color: #555; }
</style>