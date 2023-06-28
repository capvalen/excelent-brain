<template>
	<div class="modal fade" id="modalVerTriajesViejos" tabindex="-1" aria-labelledby="modalEvolution" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header bg-success text-white">
						<h5 class="modal-title" id="infoModalLabel">Historial de Triajes</h5>
						<button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
					</div>
					<div class="modal-body">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>N°</th>
									<th>Fecha</th>
									<th>Responsable</th>
									<th>Triaje</th>
								</tr>
							</thead>
							<tbody>
								<tr v-if="triajes.length>0" v-for="(triaje, index) in triajes" :key="triaje.id" >
									<td>{{ index+1 }}</td>
									<td>{{fechaLatam(triaje.fecha)}}</td>
									<td class="text-capitalize">{{triaje.responsable}}</td>
									<td><a class="btn btn-success btn-sm" :href="`/api/triajePDF/${triaje.id}`" target="_blank">Ver PDF</a></td>
								</tr>
								<tr v-else>
									<td></td>
									<td >No hay datos registrados</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
	</div>
</template>

<script>
import moment from 'moment';
	export default {
		name: 'ModalVerTriajesViejos',
		props: {
			triajes: Array
		},
		methods: {
			fechaLatam(fecha){
				return moment(fecha).format('DD/MM/YYYY');
			},
		}
	}
</script>