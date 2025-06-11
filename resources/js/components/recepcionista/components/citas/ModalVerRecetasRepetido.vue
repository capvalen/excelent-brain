<template>
	<!-- Modal de recetas -->
	<div class="modal fade" id="recetasModalRepetido" tabindex="-1" aria-labelledby="modalEvolution" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header border-0">
						<h5 class="modal-title" id="infoModalLabel">Recetas del paciente</h5>
						<button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
					</div>

					<div class="modal-body">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>#</th>
									<th>Fecha</th>
									<th>Ver Receta</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(prescription, index) in prescriptions" :key="index">
									<td>{{ index + 1 }}</td>
									<td>{{ prescription ? fechaLatam(prescription.attention_date) : '...' }}</td>
									<td>
										<a v-if="prescription" class="btn btn-outline-success btn-sm" :href="`/api/pdf/${prescription.id}`"
											target="_blank"><i class="fa-regular fa-file-pdf"></i> Ver en PDF</a>
									</td>
								</tr>
								<tr v-if="prescriptions.length==0">
									<td colspan="3">No hay ningúna receta encontrada</td>
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
export default{
	name: 'modalVerRecetas',
	props:['prescriptions'],
	
	data(){ return{

	}},
	methods:{
		fechaLatam(fecha){
			return moment(fecha).format('DD/MM/YYYY');
		},
	},
	updated(){
		console.log('viene recetaaaaaaaaaaaa', this.prescriptions)
		
	},
	
}
</script>