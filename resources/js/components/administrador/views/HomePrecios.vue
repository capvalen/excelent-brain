<template>
	<div>
		<h1 class="h3 mb-0 text-gray-800 mb-2">Precios de servicios</h1>
		<button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoServicio"><i class="fas fa-plus"></i> Nuevo servicio</button>
		<div class="card my-2">
			<div class="card-body">
				<p class="text-muted"><strong>Precios de Psiquiatría</strong></p>
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Servicio</th>
							<th>Precio nuevos</th>
							<th>Precio continuos</th>
							<th>Precio Especial de Membresía</th>
							<th>@</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(precio, index) in precios" v-if="precio.idClasificacion==1 && precio.servicio==1">
							<td>{{ precio.descripcion }}</td>
							<td>S/ {{ parseFloat(precio.nuevos).toFixed(2) }}</td>
							<td>S/ {{ parseFloat(precio.continuos).toFixed(2) }}</td>
							<td>S/ {{ parseFloat(precio.especialMembresias).toFixed(2) }}</td>
							<td>
								<button class="btn btn-sm btn-outline-primary" @click="editarPrecio(index, precio.idClasificacion)"><i class="far fa-edit"></i></button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="card my-2">
			<div class="card-body">
				<p class="text-muted"><strong>Precios den Psicología</strong></p>
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Servicio</th>
							<th>Precio nuevos</th>
							<th>Precio continuos</th>
							<th>Precio Especial de Membresía</th>
							<th>@</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(precio, index) in precios" v-if="precio.idClasificacion==2 && precio.servicio==1">
							<td>{{ precio.descripcion }}</td>
							<td>S/ {{ parseFloat(precio.nuevos).toFixed(2) }}</td>
							<td>S/ {{ parseFloat(precio.continuos).toFixed(2) }}</td>
							<td>S/ {{ parseFloat(precio.especialMembresias).toFixed(2) }}</td>
							<td>
								<button class="btn btn-sm btn-outline-primary" @click="editarPrecio(index, precio.idClasificacion)"><i class="far fa-edit"></i></button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="card my-2">
			<div class="card-body">
				<p class="text-muted"><strong>Precios de certificados</strong></p>
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Servicio</th>
							<th>Precio nuevos</th>
							<th>Precio continuos</th>
							<th>Precio Especial de Membresía</th>
							<th>@</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(precio, index) in precios" v-if="precio.idClasificacion==3 && precio.servicio==1">
							<td>{{ precio.descripcion }}</td>
							<td>S/ {{ parseFloat(precio.nuevos).toFixed(2) }}</td>
							<td>S/ {{ parseFloat(precio.continuos).toFixed(2) }}</td>
							<td>S/ {{ parseFloat(precio.especialMembresias).toFixed(2) }}</td>
							<td>
								<button class="btn btn-sm btn-outline-primary" @click="editarPrecio(index, precio.idClasificacion)"><i class="far fa-edit"></i></button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="card my-2">
			<div class="card-body">
				<p class="text-muted"><strong>Precios de Kurame y Membresías</strong></p>
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Servicio</th>
							<th>Precio nuevos</th>
							<th>Precio continuos</th>
							<th>Precio Especial de Membresía</th>
							<th>N° Sesiones</th>
							<th>@</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(precio, index) in precios" v-if="precio.servicio==0">
							<td>{{ precio.descripcion }}</td>
							<td>S/ {{ parseFloat(precio.nuevos).toFixed(2) }}</td>
							<td>S/ {{ parseFloat(precio.continuos).toFixed(2) }}</td>
							<td>S/ {{ parseFloat(precio.especialMembresias).toFixed(2) }}</td>
							<td>{{ precio.sesiones }}</td>
							<td>
								<button class="btn btn-sm btn-outline-primary" @click="editarPrecio(index)"><i class="far fa-edit"></i></button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<ModalCambiarPrecio :precio="precioElegido"></ModalCambiarPrecio>
		<modalNuevoServicio></modalNuevoServicio>

	</div>
</template>
<script>
import ModalCambiarPrecio from "./ModalCambiarPrecio.vue";
import modalNuevoServicio from "./ModalNuevoServicio.vue";
export default{
	name: 'HomePrecios',
	data(){ return {
		precios:null, precioElegido:[]
	}},
	components:{ModalCambiarPrecio, modalNuevoServicio},
	mounted(){
		this.cargarPrecios()
	},
	methods:{
		async cargarPrecios(){
			await this.axios.get('/api/listarPrecios')
			.then( response => this.precios = response.data)
		},
		editarPrecio(index){
			console.log( this.precios[index].descripcion)
			this.precioElegido=this.precios[index];
			$('#modalCambiarPrecio').modal('show')
		}
	}
}
</script>