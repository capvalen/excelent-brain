<template>
<!-- Modal -->
<div class="modal fade" id="modalArchivos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header border-0">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Archivos adjuntados</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body border-0">
				<p>Seleccione el archivo a subir</p>
				<div class="input-group">
					<input type="file" class="form-control" ref="archivo" @change="cambioArchivo">
					<button class="btn btn-outline-primary" type="button" @click="subirArchivo()"><i class="fa-solid fa-upload"></i> Subir</button>
				</div>
				<div class="mt-4" v-if="archivos.length>0">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Archivo</th>
								<th>Fecha</th>
								<th>@</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(archivo, index) in archivos">
								<td>{{ index+1 }}</td>
								<td>{{ archivo.nombre }}</td>
								<td>{{ fechaLatam(archivo.fecha) }}</td>
								<td> <a class="btn btn-sm btn-outline-success" :href="'/storage/archivos/'+archivo.archivo" target="_blank"><i class="fa-solid fa-download"></i> Descargar</a> </td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
</template>
<script>
import moment from 'moment';
export default{
	name:'modalArchivos',
	data() {
    return {
      archivoSeleccionado: null, archivos:[]
    };
  },
	props:['idPaciente', 'idProfesional'],
	watch:{
		idPaciente(){
			this.pedirArchivos();
		}
	},
	methods:{
		cambioArchivo(){
			this.archivoSeleccionado = event.target.files[0];
		},
		subirArchivo(){
			const datos = new FormData();
      datos.append('file', this.archivoSeleccionado);
      datos.append('idPaciente', this.idPaciente );
      datos.append('idProfesional', this.idProfesional );

			axios.post('/api/subirArchivo', datos)
			.then((response) => {
				if(response.data.archivo){
					this.archivos.push(response.data)
				}
			})
			.catch((error) => {
				console.error('Error al subir el archivo:', error.response.data.error);
			});

		},
		pedirArchivos(){
			const datos = new FormData();
			datos.append('idPaciente',this.idPaciente)
			axios.post('/api/pedirArchivos', datos)
			.then(response=> this.archivos = response.data )
		},
		fechaLatam(fecha) { return moment(fecha).format('DD/MM/YYYY'); },
	}
}</script>