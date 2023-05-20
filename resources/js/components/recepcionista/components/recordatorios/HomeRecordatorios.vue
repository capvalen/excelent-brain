<template>
	<main>
		<h1>Recordatorios</h1>
		<div class="row">
			<div class="col-sm-3" @click="cargarDatos('avisos')">
				<div class="card mb-0 py-0 border-left-primary cursor">
					<div class="card-body"><i class="fas fa-mobile-alt"></i> Ver llamadas y avisos</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="card mb-0 py-0 border-left-warning cursor">
					<div class="card-body"><i class="fas fa-money-bill-wave-alt"></i> Ver pagos adelantados</div>
				</div>
			</div>
			<div class="col-sm-3" @click="cargarDatos('cumpleaños')">
				<div class="card mb-0 py-0 border-left-info cursor">
					<div class="card-body"><i class="fas fa-birthday-cake"></i> Ver cumpleaños</div>
				</div>
			</div>
			<div class="col-sm-3"></div>
		</div>
		<section v-if="tipo == 'cumpleaños'">
			<div class="card px-1 my-4 p-2">
				<div class=" card-body">
					<div class="input-group mb-3 col-sm-4">
						<span class="input-group-text" id="basic-addon1">Cambiar mes:</span>
						<input type="date" class="form-control" id="fechaCumple" @change="cambiarFecha()">
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
								<td>{{ cliente.name }}</td>
								<td>{{ fechaLatam(cliente.birth_date) }}</td>
								<td>{{ edad(cliente.birth_date) }} años</td>
								<td><a v-if="cliente.phone!=''" :href="`https://wa.me/51${cliente.phone}?text=Hola, de parte te Excelentemente, queremos enviarte un saludo por tu cumpleaños. Esperamos que la pases bonito en este día`" target="_blank"><i class="fab fa-whatsapp"></i></a></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</section>
		<section v-if="tipo== 'avisos'">
			<div class="card px-1 my-4 p-2">
				<div class="card-body">
					<div class="d-flex justify-content-between">
						<div class="input-group mb-3 col-sm-4">
							<span class="input-group-text" id="basic-addon1">Cambiar mes:</span>
							<input type="date" class="form-control" id="fechaCumple" @change="cambiarFecha()">
						</div>
						<div class="d-grid">
							<button class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#nuevoAviso">Nuevo aviso</button>
							<button class="btn btn-success mx-2" @click="actualizarAvisos()">Actualizar avisos</button>
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
								<th>Estado</th>
								<th>@</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(aviso, index) in avisos">
								<td> {{ index+1 }} </td>
								<td>aviso.fecha</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</section>

		<ModalNuevoAviso :usuario="idUsuario"></ModalNuevoAviso>
	</main>
	
</template>
<script>
import ModalNuevoAviso from './ModalNuevoAviso.vue'
import moment from 'moment';

export default {
	components:{ ModalNuevoAviso },
	name: 'HomeRecordatorios',
	data() {
		return {
			mes: moment().format('M'), tipo: null, clientes: [], avisos:[], idUsuario:null
		}
	},
	mounted(){
		this.axios.get('/api/user').
		then(res=> this.idUsuario = res.data.user.id )
	},
	methods: {
		async cargarDatos(tipo) {
			this.tipo = tipo;
			switch (tipo) {
				case 'cumpleaños':
					await this.axios.get(`/api/listarCumpleanos/${this.mes}`)
						.then(response => {
							this.clientes = response.data
						});
					break;
					case 'aviso':
						//await this.axios.get('');
						break;
				default:
					break;
			}
		},
		fechaLatam(fecha) {
			return moment(fecha).format('DD/MM/YYYY');
		},
		edad(fecha) {
			let miEdad = moment(fecha);
			return moment().diff(miEdad, 'years');
		},
		cambiarFecha(){
			let queMes = moment(document.getElementById('fechaCumple').value).format('M')
			if(queMes != this.mes && queMes !='Invalid date'){
				this.mes = queMes;				
			}
		}

	}
	
}
</script>
<style scoped>
.cursor:hover {
	cursor: pointer;
	box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
}
</style>