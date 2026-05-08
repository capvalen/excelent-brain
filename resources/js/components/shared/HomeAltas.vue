<template>
	<div class="container-fluid py-5">
		<h1 class="h3 mb-2 text-gray-800">Listado de Altas</h1>
		<p class="mb-4">Visualice y gestione el estado de las altas psicológicas y psiquiátricas de los pacientes.</p>

		<div class="card shadow mb-4">
			<div class="card-header py-3 d-flex justify-content-between align-items-center">
				<h6 class="m-0 font-weight-bold text-primary">Registros de Altas</h6>
				<input type="text" class="form-control form-control-sm w-25" v-model="searchQuery" placeholder="Buscar por paciente...">
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>#</th>
								<th>Paciente</th>
								<th>Tipo de Alta</th>
								<th>Profesional</th>
								<th>Fecha</th>
								<th>Comentarios</th>
								<th>Estado</th>
								<th>Acción</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(alta, index) in filteredAltas" :key="alta.id">
								<td>{{ index + 1 }}</td>
								<td class="text-capitalize">{{ alta.patient ? (alta.patient.name + ' ' + (alta.patient.nombres || '')).trim() : 'Sin paciente' }}</td>
								<td>
									<span v-if="alta.type == 1" class="badge bg-info text-dark">Psicológica</span>
									<span v-else-if="alta.type == 2" class="badge bg-success">Psiquiátrica</span>
									<span v-else class="badge bg-secondary">Otra</span>
								</td>
								<td>{{ alta.professional ? alta.professional.name : 'Desconocido' }}</td>
								<td>{{ formatFecha(alta.created_at) }}</td>
								<td>{{ alta.comments }}</td>
								<td>
									<select class="form-select form-select-sm" v-model="alta.status">
										<option value="1">Activo</option>
										<option value="2">Inactivo</option>
										<option value="3">Reingreso</option>
									</select>
								</td>
								<td>
									<button class="btn btn-primary btn-sm" @click="actualizarEstado(alta)">Actualizar</button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import moment from 'moment'

export default {
	name: 'HomeAltas',
	data() {
		return {
			altas: [],
			searchQuery: ''
		}
	},
	computed: {
		filteredAltas() {
			if (!this.searchQuery) return this.altas;
			const query = this.searchQuery.toLowerCase();
			return this.altas.filter(alta => {
				const patientName = alta.patient ? (alta.patient.name + ' ' + (alta.patient.nombres || '')).toLowerCase() : '';
				return patientName.includes(query);
			});
		}
	},
	created() {
		this.cargarAltas()
	},
	methods: {
		cargarAltas() {
			this.axios.get('/api/discharges')
				.then(res => {
					this.altas = res.data;
				})
				.catch(err => {
					console.error(err)
				})
		},
		formatFecha(fecha) {
			return moment(fecha).format('DD/MM/YYYY hh:mm a');
		},
		actualizarEstado(alta) {
			this.axios.put('/api/discharges/' + alta.id, {
				status: alta.status
			})
			.then(res => {
				this.$swal.fire('Éxito', res.data.msg, 'success');
			})
			.catch(err => {
				this.$swal.fire('Error', 'Hubo un error al actualizar', 'error');
				console.error(err)
			})
		}
	}
}
</script>
