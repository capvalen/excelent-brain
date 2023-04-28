<template>
	<main>
		<h1>Recordatorios</h1>
		<div class="row">
			<div class="col-sm-3 ">
				<div class="card mb-0 py-0 border-left-primary cursor">
					<div class="card-body"><i class="fas fa-mobile-alt"></i> Ver llamadas y avisos</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="card mb-0 py-0 border-left-warning cursor">
					<div class="card-body"><i class="fas fa-money-bill-wave-alt"></i> Ver pagos</div>
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
						</tr>
					</thead>
					<tbody>
						<tr v-for="(cliente, index) in clientes">
							<td>{{ index + 1 }}</td>
							<td>{{ cliente.name }}</td>
							<td>{{ fechaLatam(cliente.birth_date) }}</td>
							<td>{{ edad(cliente.birth_date) }} años</td>
						</tr>
					</tbody>
				</table>
			</div>
		</section>

	</main>
</template>
<script>
import moment from 'moment';
export default {
	name: 'HomeRecordatorios',
	data() {
		return {
			mes: moment().format('M'), tipo: null, clientes: []
		}
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