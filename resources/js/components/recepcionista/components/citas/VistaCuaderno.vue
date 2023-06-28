<template>
	<div class="container-fluid p-2">
		<div class="row mb-3">
			<div class="col-12 col-sm-3">
				<input type="date" class="form-control shadow-sm" v-model="fecha">
			</div>
		</div>
		<table class="table table-hover my-5" v-for="(doctor, index) in doctores" :key="doctor.id">
			<thead  >
				<tr>
					<th  class="table-dark" colspan="9"> {{ index+1 }}. {{doctor.name}}</th>
				</tr>
				<tr>
					<th>#</th>
					<th>Hora</th>
					<th>Servicio</th>
					<th>Paciente</th>
					<th>Modo</th>
					<th>Pago</th>
					<th>Estado</th>
					<th>Opciones</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(hora, indice) in doctor.horarios" :key="hora.id">
					<td>{{ indice+1 }}</td>
					<td>{{ horaLatam1(hora.check_time) }} - {{ horaLatam2(hora.departure_date) }}</td>
					<td v-if="hora.libre=='0'">
						<span v-if="horasMalas[hora.indexOcupado].clasification==1">Psiquiatría</span>
						<span v-if="horasMalas[hora.indexOcupado].clasification==2">Psicología</span>
						<span v-if="horasMalas[hora.indexOcupado].clasification==3">Certificado</span>
						<span v-if="horasMalas[hora.indexOcupado].clasification==4">Kurame</span>
						<span v-if="horasMalas[hora.indexOcupado].formato_nuevo=='0'">{{ tipoViejo[horasMalas[hora.indexOcupado].type-1] }}:</span>
						<span v-else>{{ queServicio(horasMalas[hora.indexOcupado].type) }}:</span>
					</td>
					<td v-else></td>
					<td v-if="hora.libre=='0'" data-bs-toggle="modal" data-bs-target="#exampleModal" @click="asignar(horasMalas[hora.indexOcupado])">
						<span class="text-capitalize" >{{ (horasMalas[hora.indexOcupado].patient.name).toLowerCase() }} </span>
					</td>
					<td v-else>
						<button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalNuevaCita" @click="prepararAutomaticos(index, indice)" v-if="hora.libre=='1'"><i class="fa-regular fa-circle-check"></i> Libre para citar</button>
					</td>
					<td v-if="hora.libre==0" :title="horasMalas[hora.indexOcupado].mode == 1 ? 'Presencial':'Virtual'">
						<a @click="changeMode(horasMalas[hora.indexOcupado].id)" v-if="horasMalas[hora.indexOcupado].mode == 1" class="btn btn-info btn-sm"><i class="far fa-user"></i></a>
						<a @click="changeMode(horasMalas[hora.indexOcupado].id)" v-else class="btn btn-primary btn-sm"><i class="fas fa-desktop"></i></a>
					</td>
					<td v-else></td>
					<td>
						<a v-if="hora.libre==0" @click="cita= horasMalas[hora.indexOcupado]; alternativo=true" data-bs-toggle="modal" data-bs-target="#pagoModal" class="btn btn-icon-split btn-sm"
							:class='{
							"btn-secondary": horasMalas[hora.indexOcupado].payment.pay_status == 1 ,
							"btn-success": horasMalas[hora.indexOcupado].payment.pay_status == 2 ,
							"btn-danger": [3,null].includes(horasMalas[hora.indexOcupado].payment.pay_status)
							}' >
							<span class="icon text-white-50">
								<i :class='{
							"fa-regular fa-circle-question": horasMalas[hora.indexOcupado].payment.pay_status == 1 ,
							"fas fa-check": horasMalas[hora.indexOcupado].payment.pay_status == 2 ,
							"fas fa-exclamation-circle": [3,null].includes(horasMalas[hora.indexOcupado].payment.pay_status)
							}'></i>
							</span>
							<span class="text labels" v-if="horasMalas[hora.indexOcupado].payment.pay_status == 1">Sin pagar</span>
							<span class="text labels" v-else-if="horasMalas[hora.indexOcupado].payment.pay_status == 2">Pagado</span>
							<span class="text labels" v-else-if="[3,null].includes(horasMalas[hora.indexOcupado].payment.pay_status)">Anulado</span>
							</a>
					</td>
					<td>
						<a v-if="hora.libre==0" @click="cita= horasMalas[hora.indexOcupado]; alternativo=true" data-bs-toggle="modal" data-bs-target="#modalEstado" class="btn btn-icon-split btn-sm"
						:class='{
						"btn-secondary": horasMalas[hora.indexOcupado].status == 1,
						"btn-info": horasMalas[hora.indexOcupado].status == 2,
						"btn-danger": horasMalas[hora.indexOcupado].status == 3
						}'>
							<span class="icon text-white-50">
								<i :class="{
									'fas fa-exclamation-circle': horasMalas[hora.indexOcupado].status == 1,
									'fas fa-check': horasMalas[hora.indexOcupado].status == 2,
									'fas fa-times': [3,4,null].includes(horasMalas[hora.indexOcupado].status)
								}"></i>
							</span>
							<span class="text labels" v-if="horasMalas[hora.indexOcupado].status == 1">Sin confirmar</span>
							<span class="text labels" v-else-if="horasMalas[hora.indexOcupado].status == 2">Confirmado</span>
							<span class="text labels" v-else-if="horasMalas[hora.indexOcupado].status == 3">Cancelado</span>
							<span class="text labels" v-else-if="horasMalas[hora.indexOcupado].status == 4">Reprogramado</span>
						</a>
					</td>
					<td v-if="hora.libre==0">
						<a v-if="horasMalas[hora.indexOcupado].status == 3"  title="Cita cancelada"  class="btn btn-danger btn-circle btn-sm"><i class="fas fa-calendar"></i></a>
						<a v-else @click="modalInfo(horasMalas[hora.indexOcupado])" title="Reprogramar cita" data-bs-target="#reprogModal" data-bs-toggle="modal" class="btn btn-info btn-circle btn-sm"><i class="fas fa-calendar"></i></a>
						
						<!-- <a @click="modalInfo(cita)" title="Información de la cita" data-toggle="modal" data-target="#infoModal" class="btn btn-info btn-circle btn-sm"><i class="fas fa-info"></i></a> -->
						<a @click="eliminar(horasMalas[hora.indexOcupado].id)" title="Eliminar" class="btn btn-info btn-circle btn-sm"><i class="fas fa-trash"></i></a>

						<!-- Sin numero -->
						<a v-if="horasMalas[hora.indexOcupado].patient.phone ? false : true"
						class="btn btn-danger btn-circle btn-sm"
						title="Sin número"
						>
						<i class="fab fa-whatsapp"></i>
						</a>

						<!-- Cita virtual - con link -->
						<a 
						:href="`whatsapp://send?phone=51${horasMalas[hora.indexOcupado].patient ? horasMalas[hora.indexOcupado].patient.phone : ''}&text=Buen día ${horasMalas[hora.indexOcupado].patient ? horasMalas[hora.indexOcupado].patient.name : ''}, 
						le recordamos que tiene reservada una cita online el día de hoy a las 
						${horasMalas[hora.indexOcupado].schedule ? horaLatam2(horasMalas[hora.indexOcupado].schedule.check_time) : ''}, 
						le dejo el enlace de la cita ${horasMalas[hora.indexOcupado].link}`"
						target="_blank" 
						title="Enviar mensaje (cita virtual)" 
						class="btn btn-info btn-circle btn-sm"
						v-else-if="horasMalas[hora.indexOcupado].link"
						>
						<i class="fab fa-whatsapp"></i>
						</a>

						<!-- Cita presencial -->
						<a 
						:href="`whatsapp://send?phone=51${horasMalas[hora.indexOcupado].patient ? horasMalas[hora.indexOcupado].patient.phone : ''}&text=Buen día ${horasMalas[hora.indexOcupado].patient ? horasMalas[hora.indexOcupado].patient.name : ''}, le recordamos que tiene reservada una cita el día de hoy a las ${horasMalas[hora.indexOcupado].schedule ? horaLatam2(horasMalas[hora.indexOcupado].schedule.check_time) : ''}, en el Centro Psicológico y Psiquiátrico EXCELENTEMENTE. Al culminar su sesión, no se olvide de reservar su próxima cita.`"
						target="_blank" 
						title="Enviar mensaje" 
						class="btn btn-info btn-circle btn-sm"
						v-else-if="!horasMalas[hora.indexOcupado].link"
						>
						<i class="fab fa-whatsapp"></i>
						</a>
						<a 
						:href="`whatsapp://send?phone=51${horasMalas[hora.indexOcupado].patient ? horasMalas[hora.indexOcupado].patient.phone : ''}&text=Buen día ${horasMalas[hora.indexOcupado].patient ? horasMalas[hora.indexOcupado].patient.name : ''}, esperamos se encuentre bien, le enviamos la encuesta de satisfacción de su cita en el Centro Psicológico y Psiquiátrico EXCELENTEMENTE, con ello nos ayudara a seguir mejorando en nuestra atención, gracias por su tiempo. 😊 https://forms.gle/VbnwkK85sXyoiVN5A`"
						target="_blank" 
						title="Enviar mensaje" 
						class="btn btn-primary btn-circle btn-sm"
						>
						<i class="fa fa-align-justify"></i>
						</a>
						<a class="btn btn-info btn-circle btn-sm" :href="`/api/ticket/${horasMalas[hora.indexOcupado].id}`" target="_blank">
							<i class="fas fa-file"></i>
						</a>
					</td>
					<td v-else></td>
				
				</tr>
				<tr v-if="doctor.horarios.length==0">
					<td colspan="3">El profesional no trabaja este d[ia]</td>
				</tr>
				
			</tbody>
		</table>
		<ModalNuevaCita :profesionalElegido="profesionalElegido" :horaElegida="horaElegida" :idUsuario="idUsuario" :fechaElegida='fecha' @actualizarListadoCitas="actualizarListadoCitas"></ModalNuevaCita>
    <pago-modal v-if="alternativo" :cita="cita" :idUsuario="idUsuario"></pago-modal>
    <modal-estado  v-if="alternativo" :dataCit="cita"></modal-estado>
		
		<modalNuevo v-if="cita" :dataCit="cita"></modalNuevo>
		

	</div>
</template>
<script>
	import moment from 'moment'
	import PagoModal from './PagoModal.vue'
	import ModalEstadoCita from './ModalEstadoCita.vue'
	import ModalNuevaCita from './ModalNuevaCita.vue'
	
	import modalNuevo from './modalNuevo.vue'
	
	export default{
		name: 'VistaCuaderno',
		data(){ return{
			fecha: moment().format('YYYY-MM-DD'), doctores:[], horasSolas:[], horasMalas:[], cita:{}, profesionalElegido:[], horaElegida:[], alternativo:false, precios:[],
			tipoViejo:['Terapia Inicial niño/adolescente', 'Terapia Inicial adulto', 'Terapia Inicial pareja', 'Terapia Inicial familiar', 'Terapia continua niño/adolescente', 'Terapia continua adulto', 'Terapia continua pareja', 'Terapia continua familiar', 'Orientación Vocacional', 'Sucamec inicial', 'Sucamec renovación', 'Kurame' ], cita: {
				address:{patient:{address:{}}}
			}
		}},
		props:[ 'idUsuario'],
		components: { PagoModal, ModalEstadoCita, ModalNuevaCita, modalNuevo },
		methods:{
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
			async listarProfesionales(){
				await this.axios.get('/api/profesional')
				.then(response => {
					this.doctores=response.data;
					this.obtenerHorarios();
				})
			},
			asignar(dato){
				this.cita = dato;
			},
			async obtenerHorarios(){
				let dia = this.dayWeek(moment(this.fecha).format('d')-1)
				
				await this.axios.get(`/api/horarioCuadernoOcupado/${this.fecha}/${dia}`)
				.then(res => { console.log(res.data);
					this.horasSolas = res.data.solos;
					this.horasMalas = res.data.invalidos;

					this.doctores.forEach(profesional =>{
						/* let hora = this.horasSolas.findIndex( horaSola => horaSola.professional_id === profesional.id)
						//console.log('hora', hora);
						if(hora>-1)
							profesional.horarios.push(this.horasSolas[hora]) */
						profesional.horarios = this.horasSolas
						.filter( horaSola => horaSola.professional_id === profesional.id )
						.map(horaSola=> ({...horaSola, libre:1, indexOcupado:-1}) )
					});

					let ocupado = false;
					this.doctores.forEach(profesional=>{
						profesional.horarios.forEach(horario=>{
							ocupado = this.horasMalas.findIndex(hora => hora.schedule_id == horario.id)
							if(ocupado>-1){
								horario.libre=0;
								horario.indexOcupado=ocupado;
							}/* else{
								horario.libre=1;
								horario.indexOcupado=-1
							} */
							
						})
					})

				})
			},
			horaLatam1(horita){ return moment(horita, 'HH:mm:ss').format('hh:mm') },
			horaLatam2(horita){ return moment(horita, 'HH:mm:ss').format('hh:mm a') },
			prepararAutomaticos(indexProfesional, indexHorario){
				this.profesionalElegido = this.doctores[indexProfesional];
				this.horaElegida = this.profesionalElegido.horarios[indexHorario];
			},
			async listarPrecios(){
				await this.axios.get('/api/listarPrecios')
				.then( response => this.precios = response.data)
			},
			actualizarListadoCitas(){
				this.obtenerHorarios();
			},
			queServicio(idTipo){
				return this.precios.find(x=> x.id == idTipo).descripcion

			}
		},
		mounted(){
			this.listarProfesionales();
			this.listarPrecios();
		}

	}
</script>