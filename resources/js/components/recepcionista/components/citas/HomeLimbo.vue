<template>
	<div>
		<h1>Limbo</h1>
		<p>Las citas de los siguientes pacientes están pendientes de ser reevaluadas:</p>

		<table class="table table-hover">
			<thead>
				<tr>
					<th>N°</th>
					<th>Paciente</th>
					<th>Servicio</th>
					<th>Profesional</th>
					<th>Fecha</th>
					<th>Hora</th>
					<th>Pago</th>
					<th>@</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(cita, index) in citas">
					<td>{{ index+1 }}</td>
					<td>{{ cita.appointment.patient.name }} {{ cita.appointment.patient.nombres ?? '' }} </td>
					<td>{{ cita.appointment.precio.descripcion }}</td>
					<td>{{ cita.appointment.professional.name }}</td>
					<td>{{ fechaLatam(cita.appointment.date) }}</td>
					<td>{{ horaLatam(cita.appointment.schedule.check_time) }} - {{ horaLatam(cita.appointment.schedule.departure_date) }}</td>
					<td>
						<div class="badge" :class='{
								"btn-success": cita.appointment.payment.pay_status==1 && cita.appointment.payment.adelanto>0,
								"btn-ligth": cita.appointment.payment.pay_status==1,
								"btn-primary": cita.appointment.payment.pay_status==2,
								"btn-danger": cita.appointment.payment.pay_status==3
							}'>
							<span class="text labels" v-if="cita.appointment.payment.pay_status==1 && cita.appointment.payment.adelanto>0">Con adelanto</span>
							<span class="text labels" v-else-if="cita.appointment.payment.pay_status==1">Sin pagar</span>
							<span class="text labels" v-else-if="cita.appointment.payment.pay_status==2">Cancelado</span>
							<span class="text labels" v-else-if="cita.appointment.payment.pay_status==3">Anulado</span>
						</div>
						<p v-if="cita.appointment.payment.observation" class="mb-0"><i class="fa-solid fa-arrow-right"></i> {{cita.appointment.payment.observation}}</p>
					</td>
					<td>
						<button class="btn btn-outline-secondary" title="Reprogramar" @click="prepararDatos(cita)" data-bs-toggle="modal" data-bs-target="#reprogModal"><i class="fa-solid fa-up-right-from-square"></i></button>
					</td>
				</tr>
			</tbody>
		</table>
		<reprog-modal :dataCit="dataCit" :idUsuario="$attrs.idUser"></reprog-modal>
	</div>
</template>

<script>
import ReprogModal from './ReprogModal.vue';
import moment from 'moment';
export default{
	data(){ return{
		citas:[], dataCit:{}
	}},
  name: 'HomeLimbo',
  props: [],
	components: {ReprogModal},
	mounted(){
		this.axios.get('/api/limbos')
		.then(res=>this.citas = res.data)
	},
  methods:{
		prepararDatos(cita){
			//console.log(cita)
			this.dataCit=cita.appointment
			this.dataCit.id=cita.appointment_id
			this.dataCit.patient_id=cita.appointment.patient_id
		},
    horaLatam(hora){
			return moment(hora, 'HH:mm:ss').format('h:mm a');
		},
    fechaLatam(fecha){
			return moment(fecha).format('DD/MM/YYYY');
		},
		fechaLectura(fecha){
			moment.locale('es')
			return moment(fecha).format('dddd, DD [de] MMMM YYYY [a las] hh:mm a');
		},
  }
}
</script>