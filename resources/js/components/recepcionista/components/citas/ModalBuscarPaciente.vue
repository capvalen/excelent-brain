<template>
	<!-- Modal -->
<div class="modal fade" id="modalBuscarPaciente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header border-0">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Buscar pacientes v4</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<p>Puedes realizar uns búsqueda por DNI, Apellidos o Nombres</p>
				<input type="text" class="form-control" v-model="texto" @keyup.enter="buscarPaciente()">
				<div v-if="encontrados">
					<table class="table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Datos personales</th>
								<th>Celular</th>
								<th>@</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(busqueda, index) in busquedas">
								<td> {{ index+1 }} </td>
								<td> {{ busqueda.name }} </td>
								<td> {{ busqueda.phone }} </td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer border-0" v-show="!encontrados">
				<button type="button" class="btn btn-outline-primary" @click="buscarPaciente()" ><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
			</div>
		</div>
	</div>
</div>

</template>

<script>
export default{
	name: 'modalBuscarPaciente',
	data(){ return {
		texto:'', busquedas:[], encontrados:false
	}},
	methods:{
		async buscarPaciente(){
			console.log('comienza');
			this.encontrados = true;
			await this.axios.get(`/api/searchPatientByNameDni/${this.texto}`)
				.then(res => {
					console.log(res.data);
					this.busquedas =res.data   
				})
				.catch(err => {
					console.error(err)
				})

		},
		
	}
}
</script>