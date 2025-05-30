<template>
	<div class="modal fade" id="modalAcuerdos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Acuerdos</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="card my-2">
						<div class="card-body">
							<p><span class="fw-bold">Paciente:</span>  {{ paciente.name }} {{ paciente.nombres }}</p>
							<div class="row">
								<div class="col-12">
									<label for="">Asunto</label>
									<input type="text" class="form-control" v-model="titulo">
								</div>
								<div class="col-12">
									<label for="">Mayor descripción</label>
									<textarea class="form-control" row="2" v-model="descripcion"></textarea>
								</div>
								<div class="col-8 mb-3">
									<label for="formFile" class="form-label">Adjuntar archivo</label>
									<input class="form-control" type="file" id="fileArchivo">
								</div>
								<div class="col-4 d-flex justify-content-center align-items-center">
									<div class="d-block"><button class="btn btn-outline-primary btn-sm" @click="subirArchivo()"><i class="fa-solid fa-plus"></i> Agregar acuerdo</button></div>
								</div>
							</div>
						</div>
					</div>

					<p>Acuerdos asociados</p>
					<table class="table table-hover table-sm">
						<thead>
							<th>Asunto</th>
							<th>Descripción</th>
							<th>Fecha</th>
							<th>Archivo</th>
							<th>Adjunta</th>
						</thead>
						<tbody>
							<tr v-for="(acuerdo, index) in paciente.acuerdos">
								<td>{{ index+1 }}</td>
								<td>{{ acuerdo.titulo }}</td>
								<td>{{ fechaLatam(acuerdo.fecha) }}</td>
								<td>
									<a v-if="acuerdo.ruta" class="btn btn-outline-primary border-0" :href="'/storage/adjuntos/'+acuerdo.ruta" title="Descargar Adjunto"><i class="fa-solid fa-file"></i> Ver</a>
								</td>
								<td>{{acuerdo.nombre_usuario}}</td>
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
	name: 'ModalAcuerdos',
	props: ['paciente'],
	data(){ return {
		titulo:'', descripcion:''
	}},
	methods:{
		subirArchivo(){
			console.log('que archivo')
			const file = document.querySelector('#fileArchivo');
			const formData = new FormData();
			formData.append('file', file.files[0]);
			formData.append('user_id', this.$attrs.idUser);
			formData.append('patient_id', this.paciente.id);
			formData.append('titulo', this.titulo);
			formData.append('descripcion', this.descripcion);

			this.axios.post('/api/subirArchivoAcuerdo/', formData)
			.then(response => {
				if(response.data.archivo) this.paciente.acuerdos.unshift(response.data.archivo)
			} )
			.catch(error => {
					console.log(error);
			})
		},
		horaLatam(hora){
			return moment(hora, 'HH:mm:ss').format('h:mm a');
		},
    fechaLatam(fecha){
			return moment(fecha).format('DD/MM/YYYY');
		},
	}
}
</script>