<template>
	<main>
		<h1>Gestor de datos</h1>
		<div class="row">
			<div class="col-sm-3" @click="cargarDatos('avisos')">
				<div class="card mb-0 py-0 border-left-primary cursor" :class="{'active': activoAviso}">
					<div class="card-body"><i class="fas fa-mobile-alt"></i> Recordatorios</div>
				</div>
			</div>
			<div class="col-sm-3" @click="cargarDatos('interesados')">
				<div class="card mb-0 py-0 border-left-success cursor" :class="{'active': activoInteresado}">
					<div class="card-body"><i class="fa-regular fa-circle-user"></i> Seguimiento</div>
				</div>
			</div>
			<div class="col-sm-3" @click="cargarDatos('deudas')">
				<div class="card mb-0 py-0 border-left-warning cursor" :class="{'active': activoDeudas}">
					<div class="card-body"><i class="fas fa-money-bill-wave-alt"></i> Deudas</div>
				</div>
			</div>
			<div class="col-sm-3" @click="cargarDatos('cumpleaños')">
				<div class="card mb-0 py-0 border-left-info cursor" :class="{'active': activoCumple}">
					<div class="card-body"><i class="fas fa-birthday-cake"></i> Cumpleaños</div>
				</div>
			</div>
			<div class="col-sm-3"></div>
		</div>
		<section v-if="tipo == 'cumpleaños'">
			<div class="card px-1 my-4 p-2">
				<div class=" card-body">
					<p class="lead">Cumpleaños</p>

					<div class="input-group mb-3 col-sm-4">
						<span class="input-group-text" id="basic-addon1">Mes de cumpleaños:</span>
						<input type="date" class="form-control" v-model="fechaCumple" id="fechaCumple" @change="cambiarFecha('cumpleaños')">
					</div>
					<table class="table table-striped mt-4">
						<thead>
							<tr>
								<th>N°</th>
								<th>Nombres</th>
								<th>Fecha Cumpleaños</th>
								<th>Edad</th>
								<th>@</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(cliente, index) in clientes">
								<td>{{ index + 1 }}</td>
								<td @click="dataProps(cliente)" data-bs-toggle="modal" data-bs-target="#patientModal" style="cursor:pointer">{{ cliente.name }}</td>
								<td>{{ fechaLatam(cliente.birth_date) }}</td>
								<td>{{ edad(cliente.birth_date) }} años</td>
								<td><a v-if="cliente.phone!=''" :href="`https://wa.me/51${cliente.phone}?text=Feliz cumpleaños 🎂 ${cliente.name}, recuerda que el que piensa positivo, ve lo invisible, siente lo intangible y logra lo imposible. Te desea la clínica Excelentemente 🤗`" target="_blank"><i class="fab fa-whatsapp"></i></a></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</section>
		<section v-if="tipo== 'avisos'">
			<div class="card px-1 my-4 p-2">
				<div class="card-body">
					<p class="lead">Avisos</p>

					<p>Avisos pendientes hasta la fecha</p>
					<table class="table table-hover" v-if="avisosAnteriores.length>0">
						<thead>
							<tr>
								<th>N°</th>
								<th>Fecha</th>
								<th>Hora</th>
								<th>Actividad</th>
								<th>Responsable</th>
								<th>Creador</th>
								<th>Tipo</th>
								<th>Estado</th>
								<th>@</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(anterior, indice) in avisosAnteriores">
								<td> {{ indice+1 }} </td>
								<td>{{fechaLatam(anterior.fecha)}}</td>
								<td>{{horaLatam(anterior.fecha)}}</td>
								<td class="text-capitalize">
									<span>{{anterior.actividad}}</span>
									<span v-if="anterior.respuesta">{{ anterior.respuesta }}</span>
								</td>
								<td class="text-capitalize">{{anterior.responsable}}</td>
								<td>{{anterior.nomCreador}}</td>
								<td>
									<span v-if="anterior.tipo=='1'"><span class="badge rounded-pill text-bg-primary p-2" title="Aviso"><i class="fas fa-paperclip"></i></span></span>
									<span v-if="anterior.tipo=='2'"><span class="badge rounded-pill text-ligth bg-warning p-2" title="Llamada"><i class="fas fa-phone"></i></span></span>
									<span v-if="anterior.tipo=='3'"><span class="badge rounded-pill text-bg-success p-2" title="Recordatorio"><i class="fas fa-brain"></i></span></span>
								</td>
								<td>
									<span class="text-muted" v-if="anterior.estado=='1'"><span title="Recién creado"><i class="far fa-circle"></i></span></span>
									<span class="text-success" v-if="anterior.estado=='2'"><span title="Atendido"><i class="fas fa-shield-alt"></i></span></span>
									<span class="text-success" v-if="anterior.estado=='3'"><span title="Solucionado"><i class="fas fa-check"></i></span></span>
									<span class="text-warning" v-if="anterior.estado=='4'"><span title="Pendiente aún"><i class="fas fa-water"></i></span></span>
									<span class="text-danger" v-if="anterior.estado=='5'"><span title="Cancelado"><i class="fas fa-user-slash"></i></span></span>
								</td>
								<td>
									<button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editarAviso" @click="queAviso=anterior"><i class="fas fa-edit"></i></button>
								</td>
							</tr>
						</tbody>
					</table>
					<p class="text-mute" v-else>Listado de avisos pendientes limpio hasta el día de hoy</p>

					<p>Avisos por fecha</p>

					<div class="d-flex justify-content-between">
						<div class="input-group mb-3 col-sm-4">
							<span class="input-group-text" id="basic-addon1">Mostrando avisos de:</span>
							<input type="date" class="form-control" v-model="fechaAviso" @change="cargarDatos('avisos')">
						</div>
						<div class="d-grid gap-2 d-block">
							<button class="btn btn-outline-primary mx-2" data-bs-toggle="modal" data-bs-target="#nuevoAviso"><i class="fa-regular fa-note-sticky"></i> Nuevo aviso</button>
							<button class="btn btn-outline-success mx-2" @click="actualizarAvisos()"><i class="fas fa-sync"></i> Actualizar lista</button>
						</div>
					</div>
					<table class="table table-hover" v-if="avisos.length>0">
						<thead>
							<tr>
								<th>N°</th>
								<th>Fecha</th>
								<th>Hora</th>
								<th>Actividad</th>
								<th>Responsable</th>
								<th>Creador</th>
								<th>Tipo</th>
								<th>Estado</th>
								<th>@</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(aviso, indice) in avisos">
								<td> {{ indice+1 }} </td>
								<td>{{fechaLatam(aviso.fecha)}}</td>
								<td>{{horaLatam(aviso.fecha)}}</td>
								<td class="text-capitalize">
									<span>{{aviso.actividad}}</span>
									<span v-if="aviso.respuesta"><br><i class="fas fa-user-alt"></i>	{{ aviso.respuesta }}</span>
								</td>
								<td>{{aviso.responsable}}</td>
								<td>{{aviso.nomCreador}}</td>
								<td>
									<span v-if="aviso.tipo=='1'"><span class="badge rounded-pill text-bg-primary p-2" title="Aviso"><i class="fas fa-paperclip"></i></span></span>
									<span v-if="aviso.tipo=='2'"><span class="badge rounded-pill text-ligth bg-warning p-2" title="Llamada"><i class="fas fa-phone"></i></span></span>
									<span v-if="aviso.tipo=='3'"><span class="badge rounded-pill text-bg-success p-2" title="Recordatorio"><i class="fas fa-brain"></i></span></span>
								</td>
								<td>
									<span class="text-muted" v-if="aviso.estado=='1'"><span title="Recién creado"><i class="far fa-circle"></i></span></span>
									<span class="text-success" v-if="aviso.estado=='2'"><span title="Atendido"><i class="fas fa-shield-alt"></i></span></span>
									<span class="text-success" v-if="aviso.estado=='3'"><span title="Solucionado"><i class="fas fa-check"></i></span></span>
									<span class="text-warning" v-if="aviso.estado=='4'"><span title="Pendiente aún"><i class="fas fa-water"></i></span></span>
									<span class="text-danger" v-if="aviso.estado=='5'"><span title="Cancelado"><i class="fas fa-user-slash"></i></span></span>
								</td>
								<td>
									<button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editarAviso" @click="queAviso=aviso"><i class="fas fa-edit"></i></button>
								</td>
							</tr>
						</tbody>
					</table>
					<p class="text-mute" v-else>Listado de avisos pendientes limpio hasta el día de hoy</p>
				</div>
			</div>
		</section>
		<section v-if="tipo=='interesados'">
			<div class="card px-1 my-4 p-2">
				<div class="card-body">
					<div class="card-body">
						<p class="lead">Listado de interesados</p>

						<div class="d-flex justify-content-between">
							<div class="input-group mb-3 col-sm-4">
								<span class="input-group-text" id="basic-addon1">Fecha:</span>
								<input type="date" class="form-control" v-model="fechaInteresados" id="fechInteresados" @change="cargarDatos('interesados')">
							</div>

							<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
								<input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
								<label class="btn btn-outline-primary" for="btnradio1" @click="filtro='todos'">Todos</label>

								<input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
								<label class="btn btn-outline-primary" for="btnradio2" @click="filtro='1'"><i class="fas fa-check"></i> Con respuestas</label>

								<input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
								<label class="btn btn-outline-primary" for="btnradio3" @click="filtro='2'"><i class="far fa-times-circle"></i> Sin respuestas</label>
							</div>
							<div>
								<select class="form-select" v-model="filtroDoc">
									<option value="-1">Todos</option>
									<option v-for="doctor in doctores" :value="doctor.id">{{ doctor.nombre }}</option>
								</select>
							</div>
							
							
							<div class="d-grid d-flex mb-2">
								<button class="btn btn-outline-success" @click="cargarDatos('interesados')"><i class="fas fa-redo-alt"></i> Actualizar </button>
								<button class="btn btn-outline-primary mx-2" data-bs-toggle="modal" data-bs-target="#nuevoInteresado"><i class="fa-regular fa-circle-user"></i> Nuevo seguimiento</button>
							</div>
							
						</div>

					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Nombre</th>
								<th>Celular</th>
								<th>Profesional</th>
								<th>Origen</th>
								<th>Motivo</th>
								<th>Usuario</th>
								<th>Referencia</th>
								<th>Fecha y Hora</th>
								<th>Est.</th>
								<th>@</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(interesado, index) in interesados" :class="{'todos':interesado.atendido==0, 'con':interesado.atendido==1, 'sin':interesado.atendido==2}" v-show="(filtro=='todos' || filtro==1 && interesado.respuesta !='' || filtro==2 && interesado.respuesta=='') && (interesado.idProfesional == filtroDoc || filtroDoc==-1) ">
								<td>{{ index+1 }}</td>
								<td class="text-capitalize">{{ interesado.nombre }}</td>
								<td>{{ interesado.celular }}</td>
								<td>{{ interesado.nomProf }}</td>
								<td>
									<span v-if="interesado.origen=='1'">Manual</span>
									<span v-if="interesado.origen=='2'">Cartera de clientes</span>
									<span v-if="interesado.origen=='3'">Cita anulada</span>
								</td>
								<td class="text-capitalize">
									<span>{{ interesado.motivo }}</span>
									<span v-if="interesado.atendido>0"><br><i class="fas fa-user-alt"></i> {{ interesado.respuesta }}</span>
								</td>
								<td>{{ interesado.usuNombre }}</td>
								<td>
									<span v-if="interesado.referencia=='1'">Ninguno</span>
									<span v-if="interesado.referencia=='2'">Recomendación</span>
									<span v-if="interesado.referencia=='3'">Publicidad de internet</span>
									<span v-if="interesado.referencia=='4'">Publicidad Escrita</span>
									<span v-if="interesado.referencia=='5'">Publicidad de TV/Radio</span>
									<span v-if="interesado.referencia=='6'">Referido</span>
									<span v-if="interesado.referencia=='7'"> </span> <!-- Sistema recepcion -->
								</td>
								<td>{{ fechaLatam(interesado.fecha) }} {{ horaLatam(interesado.fecha) }}</td>
								<td>
									<span class="text-muted" v-if="interesado.atendido=='0'"><span title="Recién creado"><i class="far fa-circle"></i></span></span>
									<span class="text-success" v-if="interesado.atendido=='1'"><span title="Cliente respondió"><i class="fas fa-check"></i></span></span>
									<span class="text-danger" v-if="interesado.atendido=='2'"><span title="Cliente no respondió"><i class="far fa-times-circle"></i></span></span>
								</td>
								<td>
									<button class="btn btn-outline-primary btn-sm " v-if="interesado.atendido=='0'" @click="responderInteresado(interesado, index)" data-bs-target="#modalResponderInteresado" data-bs-toggle="modal"><i class="far fa-comment-dots"></i></button>
									<!-- <button class="btn btn-outline-danger btn-sm border-0" @click="borrarInteresado(interesado.id, index)"><i class="fa-solid fa-xmark"></i></button> -->
								</td>
							</tr>
						</tbody>
					</table>
					</div>
				</div>
			</div>
		</section>
		<section v-if="tipo=='deudas'">
			<div class="card px-1 my-4 p-2">
				<div class="card-body">
					<div class="card-body">
						<p class="lead">Deudas por cobrar</p>
						<div class="input-group mb-3 col-sm-4">
							<span class="input-group-text" id="basic-addon1">Cambiar fecha:</span>
							<input type="date" class="form-control" v-model="nFecha" id="fechaDeudas" @change="cargarDatos('deudas')">
						</div>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Nombre</th>
								<th>Motivo</th>
								<th>Monto</th>
								<th>Fecha de deuda</th>
								<th>Estado</th>
								<th>@</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(deuda, index) in deudas">
								<td>{{ index+1 }}</td>
								<td class="text-capitalize" @click="dataProps(deuda)" data-bs-toggle="modal" data-bs-target="#patientModal" style="cursor:pointer">{{ deuda.name }}</td>
								<td class="text-capitalize">{{ deuda.motivo }}</td>
								<td>S/ {{ parseFloat(deuda.monto).toFixed(2) }}</td>
								<td>{{ fechaLatam(deuda.fecha) }} <small>({{ fechaFrom(deuda.fecha) }})</small></td>
								<td>
									<span v-if="deuda.estado == 1"><span title="Deuda pendiente">⚪</span></span>
									<span v-if="deuda.estado == 2"><span title="Deuda cobrada">🟢</span></span>
									<span v-if="deuda.estado == 3"><span title="Deuda perdida">🔴</span></span>
								</td>
								<td >
									<button v-if="deuda.estado==1" class="btn btn-outline-primary btn-sm" title="Cambiar pago" @click="queDeuda = deuda" data-bs-target="#modalPagarDeuda" data-bs-toggle="modal"><i class="fas fa-hand-holding-usd"></i></button>
								</td>
							</tr>
						</tbody>
					</table>
					</div>
				</div>
			</div></section>

		<ModalNuevoAviso :usuario="idUsuario"></ModalNuevoAviso>
		<ModalEditarAviso :queAviso="queAviso" :usuario="idUsuario"></ModalEditarAviso>
		<ModalNuevoInteresado :usuario="idUsuario"></ModalNuevoInteresado>
		<ModalEditPatients v-if="data" :dataPatient="data"></ModalEditPatients>
		<ModalResponderInteresado :queInteresado="queInteresado"></ModalResponderInteresado>
		<ModalPagarDeuda :deuda="queDeuda" :usuario="idUsuario"></ModalPagarDeuda>
		
	</main>
	
</template>
<script>
import ModalNuevoAviso from './ModalNuevoAviso.vue'
import ModalEditarAviso from './ModalEditarAviso.vue'
import ModalNuevoInteresado from './ModalNuevoInteresado.vue'
import ModalEditPatients from '../pacientes/ModalEditPatients.vue'
import ModalResponderInteresado from './ModalResponderInteresado.vue'
import ModalPagarDeuda from './ModalPagarDeuda.vue'

import moment from 'moment';

export default {
	components:{ ModalNuevoAviso, ModalEditarAviso, ModalNuevoInteresado, ModalEditPatients, ModalResponderInteresado, ModalPagarDeuda },
	name: 'HomeRecordatorios',
	data() {
		return {
			mes: moment().format('M'), tipo: null, clientes: [], avisos:[], deudas:[], idUsuario:null, queAviso:null, interesados:[], fechaCumple:moment().format('YYYY-MM-DD'), activoCumple: false, activoAviso: false, activoInteresado: false, activoDeudas: false, nFecha:moment().format('YYYY-MM-DD'), data: null, fechaAviso: moment().format('YYYY-MM-DD'), avisosAnteriores:[], queInteresado:[], filtro:'todos', filtroDoc:-1, fechaInteresados: moment().format('YYYY-MM-DD'), queDeuda:null
		}
	},
	mounted(){
		this.axios.get('/api/user').
		then(res=> this.idUsuario = res.data.user.id )
		this.listarProfesionales();
	},
	methods: {
		async cargarDatos(tipo) {
			this.activoCumple = false;
			this.activoAviso = false;
			this.activoInteresado = false;
			this.tipo = tipo;
			switch (tipo) {
				case 'cumpleaños':
					this.activoCumple = true;
					await this.axios.get(`/api/listarCumpleanos/${this.fechaCumple}`)
						.then(response => { //console.log(response.data);
							this.clientes = response.data
						});
					break;
					case 'avisos':
						this.activoAviso = true;
						await this.axios.get(`/api/listarAvisos/${this.fechaAviso}`)
						.then(response => {this.avisos = response.data.avisos; this.avisosAnteriores = response.data.anteriores})
						break;
					case 'interesados':
						this.activoInteresado = true;
						await this.axios.get(`/api/listarInteresados/${this.fechaInteresados}`)
						.then(response => this.interesados = response.data.interesados )
						break;
					case 'deudas':
						this.activoDeudas = true;
						await this.axios.get(`/api/listarDeudas/`+this.nFecha)
						.then(response => this.deudas = response.data.deudas)
						break;
				default:
					break;
			}
		},
		dataProps(data){
			this.$emit('cambioDato');
			this.data= data
		},
		actualizarAvisos(){ this.cargarDatos('avisos') },
		fechaLatam(fecha) {
			return moment(fecha).format('DD/MM/YYYY');
		},
		horaLatam(fecha) {
			return moment(fecha).format('hh:mm a');
		},
		edad(fecha) {
			let miEdad = moment(fecha);
			return moment().diff(miEdad, 'years');
		},
		cambiarFecha(tipo){
			let queMes = moment(document.getElementById('fechaCumple').value).format('M')
			if(queMes != this.mes && queMes !='Invalid date'){
				this.mes = queMes;				
			}
			switch(tipo){
				case 'avisos': this.cargarDatos('avisos'); break;
				case 'cumpleaños': this.cargarDatos('cumpleaños'); break;
			}
		},
		async borrarInteresado(id, index){
			if(confirm(`¿Desea eliminar el registro de ${this.interesados[index].nombre}?`)){
				await this.axios.get(`/api/borrarInteresados/${id}`)
				.then(response =>{
					this.cargarDatos('interesados')
					if(response.data) this.$swal('Se actualizó exitosamente')
					else this.$swal({icon:'error',title: 'Hubo un error interno'})
				});
			}
		},
		fechaFrom(fecha){
			moment.locale('es')
			return moment(fecha, 'YYYY-MM-DD').fromNow(true);
		},
		responderInteresado(interesado, index){
			this.queInteresado = interesado;
		},
		async listarProfesionales() {
			await this.axios.get('/api/profesional')
				.then(response => {
					this.doctores = response.data;
				})
		}
	}
	
}
</script>
<style scoped>
.cursor:hover {
	cursor: pointer;
	box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
}
.card.active{font-weight: bold; box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px; }
.btn-group>.btn:first-child{
	border-top-left-radius: 5px!important;
	border-bottom-left-radius: 6px!important;
}
</style>