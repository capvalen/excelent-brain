<template>
	<main>
		<h1>Recordatorios</h1>
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
						<span class="input-group-text" id="basic-addon1">Cambiar fecha:</span>
						<input type="date" class="form-control" v-model="fechaCumple" id="fechaCumple" @change="cambiarFecha()">
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

					<div class="d-flex justify-content-between">
						<div class="input-group mb-3 col-sm-4">
							<span class="input-group-text" id="basic-addon1">Cambiar mes:</span>
							<input type="date" class="form-control" id="fechaCumple" @change="cambiarFecha()">
						</div>
						<div class="d-grid gap-2 d-block">
							<button class="btn btn-outline-primary mx-2" data-bs-toggle="modal" data-bs-target="#nuevoAviso"><i class="fa-regular fa-note-sticky"></i> Nuevo aviso</button>
							<button class="btn btn-outline-success mx-2" @click="actualizarAvisos()"><i class="fas fa-sync"></i> Actualizar lista</button>
						</div>
					</div>

					<table class="table table-hover">
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
							<tr v-for="(aviso, index) in avisos">
								<td> {{ index+1 }} </td>
								<td>{{fechaLatam(aviso.fecha)}}</td>
								<td>{{horaLatam(aviso.fecha)}}</td>
								<td>{{aviso.actividad}}</td>
								<td>{{aviso.responsable}}</td>
								<td>{{aviso.nomCreador}}</td>
								<td>
									<span v-if="aviso.tipo=='1'">Aviso</span>
									<span v-if="aviso.tipo=='2'">Llamada</span>
									<span v-if="aviso.tipo=='3'">Recordatorio</span>
								</td>
								<td>
									<span v-if="aviso.estado=='1'">Creado</span>
									<span class="text-success" v-if="aviso.estado=='2'">Atendido</span>
									<span class="text-secondary" v-if="aviso.estado=='3'">Solucionado</span>
									<span class="text-warning" v-if="aviso.estado=='4'">Pendiente</span>
									<span class="text-danger" v-if="aviso.estado=='5'">Cancelado</span>
								</td>
								<td>
									<button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editarAviso" @click="queAviso=aviso"><i class="fas fa-edit"></i></button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</section>
		<section v-if="tipo=='interesados'">
			<div class="card px-1 my-4 p-2">
				<div class="card-body">
					<div class="card-body">
						<p class="lead">Listado de interesados</p>

						<div class="d-flex justify-content-end">
							<div class="d-grid mb-2">
								<button class="btn btn-outline-primary mx-2" data-bs-toggle="modal" data-bs-target="#nuevoInteresado"><i class="fa-regular fa-circle-user"></i> Nuevo seguimiento</button>
							</div>
						</div>

					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Nombre</th>
								<th>Celular</th>
								<th>Correo</th>
								<th>Motivo</th>
								<th>Referencia</th>
								<th>Fecha y Hora</th>
								<th>@</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(interesado, index) in interesados">
								<td>{{ index+1 }}</td>
								<td class="text-capitalize">{{ interesado.nombre }}</td>
								<td>{{ interesado.celular }}</td>
								<td>{{ interesado.correo }}</td>
								<td class="text-capitalize">{{ interesado.motivo }}</td>
								<td>
									<span v-if="interesado.referencia=='1'">Ninguno</span>
									<span v-if="interesado.referencia=='2'">Recomendación</span>
									<span v-if="interesado.referencia=='3'">Publicidad de internet</span>
									<span v-if="interesado.referencia=='4'">Publicidad Escrita</span>
									<span v-if="interesado.referencia=='5'">Publicidad de TV/Radio</span>
									<span v-if="interesado.referencia=='6'">Referido</span>
									<span v-if="interesado.referencia=='7'">Sistema recepción</span>
								</td>
								<td>{{ fechaLatam(interesado.fecha) }} {{ horaLatam(interesado.fecha) }}</td>
								<td>
									<button class="btn btn-outline-danger btn-sm" @click="borrarInteresado(interesado.id, index)"><i class="fa-solid fa-xmark"></i></button>
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
								<td>{{ fechaLatam(deuda.fecha) }} <small>({{ fechaFrom(deuda.fecha) }})</small></td>
								<td>
									<span v-if="deuda.estado == 1">Deuda pendiente</span>
									<span v-if="deuda.estado == 2">Deuda cobrada</span>
									<span v-if="deuda.estado == 3">Deuda perdida</span>
								</td>
								<td>
									<button class="btn btn-outline-info btn-sm" title="Cambiar de estado" @click="modificarDeuda(deuda.id, index)"><i class="fa-solid fa-arrow-rotate-left"></i></button>
								</td>
							</tr>
						</tbody>
					</table>
					</div>
				</div>
			</div></section>

		<ModalNuevoAviso :usuario="idUsuario"></ModalNuevoAviso>
		<ModalEditarAviso v-if="avisos" :queAviso="queAviso" :usuario="idUsuario"></ModalEditarAviso>
		<ModalNuevoInteresado :usuario="idUsuario"></ModalNuevoInteresado>
		<ModalEditPatients v-if="data" :dataPatient="data"></ModalEditPatients>
		
	</main>
	
</template>
<script>
import ModalNuevoAviso from './ModalNuevoAviso.vue'
import ModalEditarAviso from './ModalEditarAviso.vue'
import ModalNuevoInteresado from './ModalNuevoInteresado.vue'
import ModalEditPatients from '../pacientes/ModalEditPatients.vue'

import moment from 'moment';

export default {
	components:{ ModalNuevoAviso, ModalEditarAviso, ModalNuevoInteresado, ModalEditPatients },
	name: 'HomeRecordatorios',
	data() {
		return {
			mes: moment().format('M'), tipo: null, clientes: [], avisos:[], deudas:[], idUsuario:null, queAviso:null, interesados:[], fechaCumple:moment().format('YYYY-MM-DD'), activoCumple: false, activoAviso: false, activoInteresado: false, activoDeudas: false, nFecha:moment().format('YYYY-MM-DD'), data: null
		}
	},
	mounted(){
		this.axios.get('/api/user').
		then(res=> this.idUsuario = res.data.user.id )
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
						await this.axios.get(`/api/listarAvisos/${moment().format('YYYY-MM-DD')}`)
						.then(response => this.avisos = response.data)
						break;
					case 'interesados':
						this.activoInteresado = true;
						await this.axios.get(`/api/listarInteresados/`)
						.then(response => this.interesados = response.data)
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
		cambiarFecha(){
			let queMes = moment(document.getElementById('fechaCumple').value).format('M')
			if(queMes != this.mes && queMes !='Invalid date'){
				this.mes = queMes;				
			}else{
				this.cargarDatos('cumpleaños')
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
			return moment(fecha, 'YYYY-MM-DD').fromNow();
		},

	}
	
}
</script>
<style scoped>
.cursor:hover {
	cursor: pointer;
	box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
}
.card.active{font-weight: bold; box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px; }
</style>