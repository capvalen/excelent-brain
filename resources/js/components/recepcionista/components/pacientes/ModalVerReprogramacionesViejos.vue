<template>
	<div class="modal fade" id="modalVerTriajesViejos" tabindex="-1" aria-labelledby="modalEvolution" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header bg-success text-white">
						<h5 class="modal-title" id="infoModalLabel">Historial de Reprogramaciones</h5>
						<button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
					</div>
					<div class="modal-body">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>N°</th>
									<th>Fecha</th>
									<th>Motivo</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(reprogramacion, index) in reprogramaciones" :key="reprogramacion.id" >
									<td>{{ index+1 }}</td>
									<td>{{fechaLatam(reprogramacion.date)}}</td>
									<td class="text-capitalize">{{reprogramacion.motivo}}</td>
								</tr>
							</tbody>
						</table>
						<p v-if="reprogramaciones.length==0">No hay datos registrados</p>
					</div>
				</div>
			</div>
	</div>
</template>

<script>
import moment from 'moment';
	export default {
		name: 'ModalVerTriajesHistorial',
		props: {
			reprogramaciones: Array
		},
		methods: {
			fechaLatam(fecha){
				return moment(fecha).format('DD/MM/YYYY');
			},
		}
	}
</script>