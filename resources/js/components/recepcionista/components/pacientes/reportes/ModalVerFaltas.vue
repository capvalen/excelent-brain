<template>
	<!-- Modal -->
	<div class="modal fade" id="modalVerFaltas" tabindex="-1"  aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Faltas del paciente</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<table class="table">
						<thead>
							<tr>
								<th>N°</th>
								<th>Profesional</th>
								<th>Fecha</th>
								<th>Hora</th>
								<th>Motivo</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(falta, index) in faltas">
								<td>{{ index+1 }}</td>
								<td>{{ falta.name }}</td>
								<td>{{ fechaLatam(falta.fecha) }}</td>
								<td>{{ horaLatam(falta.hora) }}</td>
								<td>{{ falta.observaciones }}</td>
							</tr>
							<tr v-if="faltas.length === 0" >
								<td colspan="4">No existen registros de faltas. Faltas anteriores a la actualización: <span>{{ cantFaltas-faltas.length }}</span></td>
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

export default{
	name: 'ModalVerFaltas',
	data(){ return {
		faltas:[]
	}},
	props:['queId', 'cantFaltas'],
	methods:{
		fechaLatam(fecha) {
			return moment(fecha).format('DD/MM/YYYY');
		},
		horaLatam(fecha) {
			return moment(fecha,'HH:mm:ss').format('hh:mm a');
		},
	},
	watch:{
		queId(){
			this.axios(`/api/verHistorialFaltas/${this.queId}`)
			.then(response=> this.faltas = response.data)
			console.log(this.faltas.length);
		}
	}
}</script>