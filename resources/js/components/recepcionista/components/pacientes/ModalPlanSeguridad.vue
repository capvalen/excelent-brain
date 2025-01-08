<template>
	<div class="modal fade" id="modalPlanSeguridad" tabindex="-1" aria-labelledby="modalPlanSeguridadLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-primary text-white">
					<h5 class="modal-title" id="modalPlanSeguridadLabel">Historial de Plan de Seguridad</h5>
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
				</div>
				<div class="modal-body">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>N°</th>
								<th>Fecha</th>
								<th>Responsable</th>
								<th>Ver plan de seguridad</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(plan, index) in planesDeSeguridad" :key="plan.id">
								<td>{{ index + 1 }}</td>
								<td>{{ fechaLatam(plan.fecha) }}</td>
								<td class="text-capitalize">{{ plan.responsable }}</td>
								<td>
									<!-- Aquí el enlace para visualizar el plan de seguridad -->
									<a class="btn btn-info btn-sm" :href="`/api/planSeguridadPDF/${plan.id}`" target="_blank">Ver plan</a>
								</td>
							</tr>
						</tbody>
					</table>
					<p v-if="planesDeSeguridad.length === 0">No hay planes de seguridad registrados.</p>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import moment from 'moment';
export default {
	name: 'ModalPlanSeguridad',
	props: {
		planesDeSeguridad: Array // Array que recibirá la información de los planes de seguridad
	},
	methods: {
		fechaLatam(fecha) {
			return moment(fecha).format('DD/MM/YYYY');
		},
	}
}
</script>