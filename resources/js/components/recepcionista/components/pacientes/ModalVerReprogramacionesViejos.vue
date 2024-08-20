<template>
	<div>
		<div class="modal fade" id="modalVerReprogramacionesViejos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="exampleModalLabel">Reprogramaciones</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
	</div>
</template>

<script>
import moment from 'moment';
export default {
	name: 'ModalVerReprogramacionesViejos',
	props: ['reprogramaciones'],
	methods: {
		fechaLatam(fecha) {
			return moment(fecha).format('DD/MM/YYYY');
		},
	}
}
</script>