<template lang="">
	<div>
		<h3>Editar pacientes (Nivel gerencial)</h3>
		<div class="card">
			<div class="card-body">
				<div class="d-sm-flex align-items-center justify-content-around mt-4">
					<div class="d-none d-sm-inline-block form-inline w-100">
						<div class="input-group">
							<div class="input-group-prepend">
								<button type="button" class="btn btn-secondary shadow-sm"><i class="fas fa-search fa-sm"></i></button>
							</div>
							<input type="text" id="searchNamePatient" placeholder="Buscar por apellidos, nombres o DNI..." autocomplete="off" class="form-control bg-white shadow-sm border-0 small" @keyup.enter="searchPatients()">
						</div>
					</div>
				</div>
				<p class="mt-3 mb-1">Últimos 20 pacientes registrados</p>

				<table class="table-hover table">
					<thead>
						<tr>
							<th>N°</th>
							<th>ID</th>
							<th>Apellidos y nombres</th>
							<th>Historia</th>
							<th>Edad</th>
							<th>¿Con vida?</th>
							<th>¿Deshabilitado?</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(persona, index) in busqueda">
							<td>{{index+1}}</td>
							<td>{{persona.id}} </td>
							<td class="text-uppercase">{{persona.name}} {{persona.nombres}} </td>
							<td> <a :href="'/api/pdfEvolution/thorough/'+persona.id" target="_blank"><i class="fas fa-file"></i> Ver</a> </td>
							<td>{{calcularEdad(persona.birth_date)}}</td>
							<td class="puntero" @click="cambiarMuerte(persona)">
								<span v-if="persona.vivo==1" class="badge text-bg-primary"><i class="far fa-heart"></i> Vivo </span>
								<span v-else class="badge text-bg-dark"><i class="fas fa-cross"></i> Fallecido </span>
							</td>
							<td class="puntero" @click="cambiarActivo(persona)">
								<span v-if="persona.activo==1" class="badge text-bg-success"><i class="fas fa-child"></i> Activo </span>
								<span v-else class="badge text-bg-danger"><i class="fas fa-power-off"></i> Deshabilitado </span>
							</td>
						</tr>
					</tbody>
				</table>

			</div>
		</div>
	</div>
</template>
<script>
import alertify from 'alertifyjs'
import moment from 'moment'
export default {
	name: 'editarPacientesAdmin',
	data() {
		return {
			busqueda:[],dataPatients:[], totalPatients:[], hoyM:moment()
		}
	},
	mounted() {
		this.getPatients()
	},
	methods: {
		async getPatients () {
			this.busqueda = []
			await this.axios.get(`/api/getLast10PatientsAdmin`)
			.then(res => {
				this.busqueda = res.data
			})
			.catch(err => {
				console.error(err)
			})
		},
		async searchPatients () {
      let valueInput = document.getElementById("searchNamePatient").value; 
      if(valueInput === ''){
        this.getPatients()
      }else{
        await this.axios.get(`/api/searchPatientByNameDniAdmin/${valueInput}`)
				.then(res => {
					this.busqueda = res.data
				})
				.catch(err => {
					console.error(err)
				})
      }
    },
		cambiarMuerte(persona){
			this.axios.post('/api/cambiarMuerte',{ persona })
			.then(resp=> {
				this.searchPatients()
				alertify.notify(' <i class="fas fa-street-view"></i> Acción realizada','success',5)
			})
		},
		cambiarActivo(persona){
			this.axios.post('/api/cambiarPersonaActivo',{ persona })
			.then(resp=> {
				this.searchPatients()
				alertify.notify(' <i class="fas fa-street-view"></i> Acción realizada','success',5)
			})
		},
		calcularEdad(año){
			if( año ){
				const edad = moment().diff(moment(año), 'years');
				return edad <2 ? edad+ ' año' : edad+ ' años'
			}
		}
	},
}
</script>
<style scoped>
	.puntero{ cursor: pointer; }
</style>