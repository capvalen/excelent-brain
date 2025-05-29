<template>
	<div class="container-fluid p-2">
		<div class="row mb-3 gx-3 align-items-center">
			<div class="col-auto"><input type="date" class="form-control shadow-sm" v-model="fecha" @change="obtenerHorarios()"></div>
			<div class="col-auto"><button class="btn btn-outline-primary mx-2 border-0" @click="verHorariosAyer()"><i class="fas fa-history"></i> Ayer</button></div>
			<div class="col-auto"><button class="btn btn-outline-primary mx-2 border-0" @click="verHorariosHoy()"><i class="fa-regular fa-clock"></i> Hoy</button></div>
			<div class="col-auto"><button class="btn btn-outline-secondary mx-2 border-0" @click="verHorariosMañana()"><i class="far fa-hourglass"></i> Mañana</button></div>
			<div class="col-auto"><button class="btn btn-outline-secondary mx-2 border-0" @click="verHorariosMañana()"><i class="far fa-hourglass"></i> Pasado Mañana</button></div>
			<div class="col-auto"><button class="btn btn-outline-secondary mx-2 border-0" @click="refrescarHorarios()"><i class="fas fa-sync"></i> Actualizar</button></div>
			<div class="col-auto d-none"><button class="btn btn-outline-secondary border-0 mx-2" data-bs-target="#modalBuscarPacienteExterno" data-bs-toggle="modal"><i class="fa-solid fa-magnifying-glass"></i> Buscar paciente</button></div>
		</div>
		<div class="accordion ">
			<div class="accordion-item"  v-for="(doctor, index) in doctores" :key="doctor.id">
				<h2 class="accordion-header">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="'#panel_'+doctor.id" aria-expanded="false" :aria-controls="'$panel_'+doctor.id">
					<i class="fa-solid fa-user-doctor"></i> <span class="mt-1 ms-3">{{doctor.profession}} {{doctor.name}}</span>
				</button>
				</h2>
				<div class="accordion-collapse collapse" :id="'panel_'+doctor.id">
					<div class="accordion-body">
						<table class="table table-hover table-sm">
							<thead  >
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
								<tr v-for="(hora, indice) in doctor.horarios" :key="hora.id" >
									<td class="d-none" v-if="hora.libre=='0'">{{horasMalas[hora.indexOcupado].id}}</td>
									<td>{{ indice+1 }}</td>
									<td  @click="modalInfo(horasMalas[hora.indexOcupado])" class="puntero" data-bs-toggle="modal" data-bs-target="#infoModal">{{ horaLatam1(hora.check_time) }} - {{ horaLatam2(hora.departure_date) }}</td>
									<td v-if="hora.libre=='0'">
										<span v-if="horasMalas[hora.indexOcupado].formato_nuevo=='0'">
											<span v-if="horasMalas[hora.indexOcupado].clasification==1">Psiquiatría</span>
											<span v-if="horasMalas[hora.indexOcupado].clasification==2">Psicología</span>
											<span v-if="horasMalas[hora.indexOcupado].clasification==3">Certificado</span>
											<span v-if="horasMalas[hora.indexOcupado].clasification==4">Kurame</span>
										</span>
										<span v-else>
											<span>{{ queServicio(horasMalas[hora.indexOcupado].type) }}</span>
										</span>
										<!-- <span v-if="horasMalas[hora.indexOcupado].formato_nuevo=='0'">{{ tipoViejo[horasMalas[hora.indexOcupado].type-1] }}:</span> -->
									</td>
									<td v-else></td>
									<td class="puntero" v-if="hora.libre=='0'" data-bs-toggle="modal" data-bs-target="#patientModal" @click="asignar(horasMalas[hora.indexOcupado]); modalInfo(horasMalas[hora.indexOcupado]);">
										<span class="badge rounded-5 p-2"
										:class="{
											'bg-white': [1].includes(horasMalas[hora.indexOcupado].patient.semaforo?.codigo),
											'bg-success': [2,3,4].includes(horasMalas[hora.indexOcupado].patient.semaforo?.codigo),
											'bg-warning': [5,6,7].includes(horasMalas[hora.indexOcupado].patient.semaforo?.codigo),
											'bg-danger': [8,9,10].includes(horasMalas[hora.indexOcupado].patient.semaforo?.codigo),
											'bg-secondary': !horasMalas[hora.indexOcupado].patient.semaforo,
										}">
											<span v-if="horasMalas[hora.indexOcupado].patient.semaforo?.codigo==1" title="Neutro"><i class="fas fa-smile"></i></span>
											<span v-else-if="horasMalas[hora.indexOcupado].patient.semaforo?.codigo==2" title="Cumplidor"> <i class="fas fa-laugh-wink"></i> </span>
											<span v-else-if="horasMalas[hora.indexOcupado].patient.semaforo?.codigo==3" title="Promotor"> <i class="fas fa-laugh-wink"></i> </span>
											<span v-else-if="horasMalas[hora.indexOcupado].patient.semaforo?.codigo==4" title="Wow"> <i class="fas fa-laugh-wink"></i> </span>
											<span v-else-if="horasMalas[hora.indexOcupado].patient.semaforo?.codigo==5" title="Reprogramador"> <i class="fas fa-meh"></i> </span>
											<span v-else-if="horasMalas[hora.indexOcupado].patient.semaforo?.codigo==6" title="Exigente"> <i class="fas fa-meh"></i> </span>
											<span v-else-if="horasMalas[hora.indexOcupado].patient.semaforo?.codigo==7" title="Deudor"> <i class="fas fa-angry"></i> </span>
											<span v-else-if="horasMalas[hora.indexOcupado].patient.semaforo?.codigo==8" title="Insatisfecho"> <i class="fas fa-frown"></i> </span>
											<span v-else-if="horasMalas[hora.indexOcupado].patient.semaforo?.codigo==9" title="Paciente de riesgo"> <i class="fas fa-frown"></i> </span>
											<span v-else-if="horasMalas[hora.indexOcupado].patient.semaforo?.codigo==10" title="Problemático"> <i class="fas fa-frown"></i> </span>
											<span v-if="!horasMalas[hora.indexOcupado].patient.semaforo" title="Normal"> <i class="fas fa-smile"></i> </span>
										</span>
										<span class="text-uppercase" >{{ (horasMalas[hora.indexOcupado].patient.name).toLowerCase() }} {{ horasMalas[hora.indexOcupado].patient.nombres }}</span>
									</td>
									<td v-else>
										<button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalNuevaCita" @click="prepararAutomaticos(index, indice)" v-if="hora.libre=='1'"><i class="fa-regular fa-circle-check"></i> Libre para citar</button>
									</td>
									<td v-if="hora.libre==0" :title="horasMalas[hora.indexOcupado].mode == 1 ? 'Presencial':'Virtual'">
										<a @click="changeMode(horasMalas[hora.indexOcupado].id, indice)" v-if="horasMalas[hora.indexOcupado].mode == 1" class="btn btn-info btn-sm"><i class="far fa-user"></i></a>
										<a @click="changeMode(horasMalas[hora.indexOcupado].id, indice)" v-else class="btn btn-primary btn-sm"><i class="fas fa-desktop"></i></a>
									</td>
									<td v-else></td>
									<td>
										<a v-if="hora.libre == 0 && horasMalas[hora.indexOcupado].payment" 
											@click="modalInfo(horasMalas[hora.indexOcupado]);indexElegido = hora.indexOcupado" 
											data-bs-toggle="modal" 
											data-bs-target="#pagoModal" 
											class="btn btn-icon-split btn-sm"
											:class='{
												"btn-warning": horasMalas[hora.indexOcupado].payment.pay_status == 1 && horasMalas[hora.indexOcupado].payment.adelanto > 0,
												"btn-secondary": horasMalas[hora.indexOcupado].payment.pay_status == 1 && horasMalas[hora.indexOcupado].payment.adelanto == 0,
												"btn-success": horasMalas[hora.indexOcupado].payment.pay_status == 2,
												"btn-danger": [3, null].includes(horasMalas[hora.indexOcupado].payment.pay_status)
											}'>
											<span class="icon text-white-50">
												<i :class='{
													"fa-regular fa-circle-question": horasMalas[hora.indexOcupado].payment.pay_status == 1,
													"fas fa-check": horasMalas[hora.indexOcupado].payment.pay_status == 2,
													"fas fa-exclamation-circle": [3, null].includes(horasMalas[hora.indexOcupado].payment.pay_status)
												}'></i>
											</span>
											<span class="text labels" v-if="parseInt(horasMalas[hora.indexOcupado].payment.pay_status) == 1 && horasMalas[hora.indexOcupado].payment.adelanto > 0">Con adelanto</span>
    									<span class="text labels" v-else-if="horasMalas[hora.indexOcupado].payment.pay_status == 1">Sin pagar</span>
											<span class="text labels" v-else-if="horasMalas[hora.indexOcupado].payment.pay_status == 2">Pagado</span>
											<span class="text labels" v-else-if="[3, null].includes(horasMalas[hora.indexOcupado].payment.pay_status)">Anulado</span>
										</a>
									</td>

									<td>
										<a v-if="hora.libre==0" @click="modalInfo(horasMalas[hora.indexOcupado])" data-bs-toggle="modal" data-bs-target="#modalEstado" class="btn btn-icon-split btn-sm"
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
											<span class="text labels" v-else-if="horasMalas[hora.indexOcupado].status == 3">Anulado</span>
											<span class="text labels" v-else-if="horasMalas[hora.indexOcupado].status == 4">Reprogramado</span>
										</a>
									</td>
									<td v-if="hora.libre==0">
										<a class="btn btn-success btn-circle btn-sm" title="Hacer intercambio" @click="intercambiarHorario(horasMalas[hora.indexOcupado])" data-bs-target="#modalIntercambio" data-bs-toggle="modal">
											<i class="fas fa-retweet"></i>
										</a>
										<a v-if="horasMalas[hora.indexOcupado].status == 3"  title="Cita cancelada"  class="btn btn-danger btn-circle btn-sm"><i class="fas fa-calendar"></i></a>
										<a v-else @click="modalInfo(horasMalas[hora.indexOcupado])" title="Reprogramar cita" data-bs-target="#reprogModal" data-bs-toggle="modal" class="btn btn-info btn-circle btn-sm"><i class="fas fa-calendar"></i></a>
										
										<!-- <a @click="modalInfo(cita)" title="Información de la cita" data-toggle="modal" data-target="#infoModal" class="btn btn-info btn-circle btn-sm"><i class="fas fa-info"></i></a> -->
										<a @click="eliminar(horasMalas[hora.indexOcupado].id)" title="Eliminar" class="btn btn-danger btn-circle btn-sm"><i class="fas fa-trash"></i></a>

										<!-- Sin numero -->
										<a v-if="horasMalas[hora.indexOcupado].patient.phone ? false : true"
										class="btn btn-secondary btn-circle btn-sm"
										title="Sin número">
										<i class="fab fa-whatsapp"></i>
										</a>

										<!-- Cita virtual - con link -->
										<a 
										:href="`https://wa.me/51${horasMalas[hora.indexOcupado].patient ? horasMalas[hora.indexOcupado].patient.phone.replaceAll(' ', '') : ''}?text=Buen día ${horasMalas[hora.indexOcupado].patient ? horasMalas[hora.indexOcupado].patient.name + ' '+ horasMalas[hora.indexOcupado].patient.nombres : ''}, 
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
										:href="`https://wa.me/51${horasMalas[hora.indexOcupado].patient ? horasMalas[hora.indexOcupado].patient.phone.replaceAll(' ', '') : ''}?text=Buen día ${horasMalas[hora.indexOcupado].patient ? horasMalas[hora.indexOcupado].patient.name + ' '+ horasMalas[hora.indexOcupado].patient.nombres : ''}, le recordamos que tiene reservada una cita el día de hoy a las ${horasMalas[hora.indexOcupado].schedule ? horaLatam2(horasMalas[hora.indexOcupado].schedule.check_time) : ''}, en el Centro Psicológico y Psiquiátrico EXCELENTEMENTE. Al culminar su sesión, no se olvide de reservar su próxima cita.`"
										target="_blank" 
										title="Enviar mensaje" 
										class="btn btn-info btn-circle btn-sm"
										v-else-if="!horasMalas[hora.indexOcupado].link"
										>
										<i class="fab fa-whatsapp"></i>
										</a>
										<a 
										:href="`whatsapp://send?phone=51${horasMalas[hora.indexOcupado].patient ? horasMalas[hora.indexOcupado].patient.phone : ''}&text=Buen día ${horasMalas[hora.indexOcupado].patient ? horasMalas[hora.indexOcupado].patient.name + ' '+ horasMalas[hora.indexOcupado].patient.nombres : ''}, esperamos se encuentre bien, le enviamos la encuesta de satisfacción de su cita en el Centro Psicológico y Psiquiátrico EXCELENTEMENTE, con ello nos ayudara a seguir mejorando en nuestra atención, gracias por su tiempo. 😊 https://forms.gle/VbnwkK85sXyoiVN5A`"
										target="_blank" 
										title="Enviar mensaje" 
										class="btn btn-primary btn-circle btn-sm"
										>
										<i class="fa fa-align-justify"></i>
										</a>
										<button data-bs-toggle="modal" @click="buscarRecetas(horasMalas[hora.indexOcupado].patient.id)" data-bs-target="#recetasModal" class="btn btn-info btn-circle btn-sm" title="Ver recetas">
											<i class="fas fa-file"></i>
										</button>
									</td>
									<td v-else></td>
								
								</tr>
								<tr v-if="doctor.horarios.length==0">
									<td colspan="3">El profesional no trabaja este día</td>
								</tr>
							</tbody>
						</table>

					</div>
				</div>
			</div>
			
		</div>
		<ModalNuevaCita :profesionalElegido="profesionalElegido" :horaElegida="horaElegida" :idUsuario="idUsuario" :fechaElegida='fecha' @actualizarListadoCitas="actualizarListadoCitas" :idSede="idSede"></ModalNuevaCita>
    <modal-estado v-if="cita" :dataCit="cita" :idUsuario="idUsuario"></modal-estado>
    <pago-modal v-if="cita" :cita="cita" :idUsuario="idUsuario" :idSede="idSede" @actualizarAdelanto="actualizarAdelanto"></pago-modal>
		<modal-patient v-if="cita" :dataCit="cita"></modal-patient>
    <reprog-modal v-if="cita" :dataCit="cita" :idUsuario="idUsuario" @ocultarCita="actualizarListadoCitas"></reprog-modal>
		<info-modal v-if="cita" :dataCit="cita" :precios="precios"></info-modal>
		<modal-search-patient></modal-search-patient>
		<ModalIntercambio :posibles="posibles" :primero="primero" @actualizar="actualizarListadoCitas"></ModalIntercambio>
		

	</div>
</template>
<script>
	import moment from 'moment'
	import PagoModal from './PagoModal.vue'
	import ModalEstadoCita from './ModalEstadoCita.vue'
	import ModalNuevaCita from './ModalNuevaCita.vue'
	import InfoModal from './ModalInfo.vue'
	import ReprogModal from './ReprogModal.vue'
	import ModalPatient from './ModalPatient.vue'
	import ModalSearchPatient from './ModalSearchPatient.vue'
	import ModalIntercambio from './ModalIntercambio.vue'
		
	import alertify from 'alertifyjs'
	
	export default{
		name: 'VistaCuaderno',
		data(){ return{
			fecha: moment().format('YYYY-MM-DD'), doctores:[], horasSolas:[], horasMalas:[], profesionalElegido:[], horaElegida:[], alternativo:false, precios:[], recetas:[],
			tipoViejo:['Terapia Inicial niño/adolescente', 'Terapia Inicial adulto', 'Terapia Inicial pareja', 'Terapia Inicial familiar', 'Terapia continua niño/adolescente', 'Terapia continua adulto', 'Terapia continua pareja', 'Terapia continua familiar', 'Orientación Vocacional', 'Sucamec inicial', 'Sucamec renovación', 'Kurame' ], indexElegido:-1,
			cita: {
				address:{patient:{address:{}}, patient:[]},
				patient:{address:[], relative:[]}
			}, posibles:[], primero:{patient:[]}
		}},
		props:[ 'idUsuario', 'nombreUser', 'idSede'],
		components: { PagoModal, ModalEstadoCita, ModalNuevaCita, ModalPatient, InfoModal, ReprogModal, ModalSearchPatient, ModalIntercambio },
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
			actualizarAdelanto(adelanto){
				this.horasMalas[this.indexElegido].payment.price = parseFloat(this.horasMalas[this.indexElegido].payment.price) - parseFloat(adelanto)
				this.horasMalas[this.indexElegido].payment.adelanto = parseFloat(this.horasMalas[this.indexElegido].payment.adelanto) + parseFloat(adelanto)
			},
			async eliminar(id){
      this.$swal({
          title: '¿Quieres eliminar esta cita?',
					html: 'Ingrese un motivo para eliminar la cita. <br> <small>No se generará falta</small>',
					input: 'text',
          showCancelButton: true,
          confirmButtonText: 'Si',
          cancelButtonText: `No`, //denyButtonText
      }).then((result) => {
				if( result.value =='')
					alertify.notify('No elimiando, falta rellenar un motivo' , 'danger', 5);
				else
          if(result.isConfirmed){
              this.axios.post('/api/eliminarCita/'+id, {razon: result.value, usuario: this.nombreUser })
              .then((res) => {
								console.log(res.data)
                this.$swal('Cita eliminada con exito')
              });
            this.obtenerHorarios();
          }
      })
   	 },
			async obtenerHorarios(){
				let dia = this.dayWeek(moment(this.fecha).format('d')-1)
				//console.log('que pediria', `/api/horarioCuadernoOcupado/${this.fecha}/${dia}`);
				
				await this.axios.get(`/api/horarioCuadernoOcupado/${this.fecha}/${dia}`)
				.then(res => { console.log(res.data);
					moment.locale('es')
					alertify.notify('<i class="fa-regular fa-calendar-check"></i> Datos del ' + moment(this.fecha).format('DD [de] MMMM') , 'success', 5);
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
			horaLatam1(horita){ return moment(horita, 'HH:mm:ss').format('h:mm') },
			horaLatam2(horita){ return moment(horita, 'HH:mm:ss').format('h:mm a') },
			prepararAutomaticos(indexProfesional, indexHorario){
				this.profesionalElegido = this.doctores[indexProfesional];
				this.horaElegida = this.profesionalElegido.horarios[indexHorario];
				this.$emit('limpiarDescuentos')
			},
			async listarPrecios(){
				await this.axios.get('/api/listarPreciosTodos')
				.then( response => this.precios = response.data)
			},
			actualizarListadoCitas(){
				this.obtenerHorarios();
			},
			modalInfo (data) {
			if( data.status!=4 )
      	this.cita = data;
    	},
			verHorariosAyer(){ this.fecha = moment().subtract(1, 'day').format('YYYY-MM-DD'); this.obtenerHorarios(); },
			verHorariosHoy(){ this.fecha = moment().format('YYYY-MM-DD'); this.obtenerHorarios(); },
			verHorariosMañana(){ this.fecha = moment().add(1, 'day').format('YYYY-MM-DD'); this.obtenerHorarios(); },
			verHorariosPasado(){ this.fecha = moment().add(2, 'day').format('YYYY-MM-DD'); this.obtenerHorarios(); },
			refrescarHorarios(){ this.obtenerHorarios(); },
			queServicio(idTipo){ //console.log('queTipo', idTipo);
				return this.precios.find(x=> x.id == idTipo).descripcion
			},
			changeMode(id, indice){
				this.$swal.fire({
					title: 'Actualizar',
					text: "¿Está seguro de cambiar el modo de la cita?",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Sí',
					cancelButtonText: 'No'
				}).then((result) => {
					if (result.isConfirmed) {
						this.axios.get(`/api/updateModeAppoinment/${id}`)
						.then(res =>{
							this.horasMalas[indice].mode==1? this.horasMalas[indice].mode=0: this.horasMalas[indice].mode=1
						})
					}
				})
    	},
			buscarRecetas(id){
				this.axios(`/api/verRecetaPorId/${id}`)
				.then(res =>{
					this.recetas = res.data;
					this.$parent.recetas = this.recetas;
				})
			},
			intercambiarHorario(laCita){
				let idProf = laCita.professional_id
				this.primero = laCita;
				this.posibles = this.horasMalas.filter(posible=> posible.professional_id == idProf && posible.date == this.fecha && laCita.id != posible.id )
			},
			faltanDatos(paciente){
				//console.log('paciente', paciente)
				if( !paciente.name || !paciente.phone || !paciente.email || paciente.gender==2 || !paciente.address.address || !paciente.relative.name || !paciente.relative.phone || !paciente.kinship)
					return true
				else return false
			}
		},
		mounted(){
			this.listarProfesionales();
			this.listarPrecios();
		}

	}
</script>
<style>
	.puntero{ cursor: pointer; }
	.alertify-notifier .ajs-message{width: 400px!important;}
	.alertify-notifier.ajs-right .ajs-message.ajs-visible { right: 400px!important; }
	.alertify-notifier .ajs-message.ajs-success{
		background: rgb(22 205 65 / 95%)!important;
    text-shadow: none!important;
	}
	.accordion-button:not(.collapsed) {
    color: #003a25!important;
    background-color: #1cc88a!important
	}
	.accordion-button:focus { box-shadow: 0 0 0 0.25rem #1cc88aa6 } 
	/* .accordion-button:focus { box-shadow: 0 0 0 0.25rem rgb(253 102 13 / 25%); } */
	.accordion button:hover { background-color: #ebebebbf; }
</style>