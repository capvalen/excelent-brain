<template>
	<div>
		<h1>Pacientes con S.O.S.</h1>
		<p>Los siguientes pacientes están con riesgo de suicidio:</p>

		<table class="table table-hover">
			<thead>
				<th>N°</th>
				<th>Nombres</th>
				<th>Profesional</th>
				<th>Mensaje</th>
				<th>Familiares</th>
				<th>Fecha</th>
				<th>@</th>
			</thead>
			<tbody>
				<tr v-for="(paciente, index) in pacientes">
					<td>{{ index+1 }}</td>
					<td>{{ paciente.name }} {{ paciente.nombres }}</td>
					<td>{{ paciente.nombreProfesional	}} </td>
					<td>{{ paciente.comentarios }} </td>
					<td>
						<p v-for="familiar in paciente.relaciones">
							{{ familiar.name }} <br> {{ familiar.phone }}
						</p>
					</td>
					<td>{{ fechaLatam(paciente.registro) }}</td>
					<td>
						<a :href="'../api/pdfEvolution/restricted/'+paciente.id" target="_blank" class="btn btn-outline-success" title="Ver Historia"><i class="fa-regular fa-note-sticky"></i></a>
						<button class="btn btn-outline-primary" title="Nuevo Seguimiento" data-bs-toggle="modal" data-bs-target="#modalNuevo" @click="idSos=paciente.idSos"><i class="fa-solid fa-plus"></i></button>
						<button class="btn btn-outline-secondary" title="Ver Seguimientos" data-bs-toggle="modal" data-bs-target="#modalVer" @click="listarComentarios(paciente.idSos)"><i class="fa-solid fa-eye"></i></button>
					</td>
				</tr>
			</tbody>
		</table>

		<!-- Modal -->
		<div class="modal fade" id="modalNuevo" tabindex="-1" aria-labelledby="miModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="miModalLabel">Agregar comentario</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p>¿Cuál es el comentario que desea agregar?</p>
						<input type="text" class="form-control" v-model="comentario">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="nuevo()">Guardar</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="modalVer" tabindex="-1" aria-labelledby="miModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="miModalLabel">Comentarios agregados</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p>Se listan los comentarios agregados:</p>
						<table class="table table-hover">
							<thead>
								<th>N°</th>
								<th>Encargado</th>
								<th>Comentario</th>
								<th>Fecha</th>
							</thead>
							<tbody>
								<tr v-for="(comentario, index) in comentarios">
									<td>{{ index+1 }}</td>
									<td>{{ comentario.nombreUsuario }}</td>
									<td>{{ comentario.comentario }}</td>
									<td>{{ comentario.fecha }}</td>
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
	data(){ return{
		pacientes:[], comentario:'', idSos:-1, comentarios:[]
	}},
  name: 'PacienteSOS',
  props: {},
	mounted(){
		this.axios.get('/api/pedirSOS')
		.then(res=>{
			console.log(res.data)
			this.pacientes = res.data.pacientes
		})
	},
  methods:{
		nuevo(){
			this.axios.post('/api/crearComentarioSOS',{
				idSos: this.idSos,
				comentario: this.comentario,
				idUsuario : this.$attrs.idUser
			}).then(response=>{
				console.log(response)
			})
		},
		listarComentarios(idSos){
			this.axios.get('/api/listarComentariosSOS/'+idSos)
			.then(response=>{
				this.comentarios = response.data
			})
		},
    fechaLatam(fecha){
			return moment(fecha).format('DD/MM/YYYY');
		},
		fechaLectura(fecha){
			moment.locale('es')
			return moment(fecha).format('dddd, DD [de] MMMM YYYY [a las] hh:mm a');
		},
  }
}
</script>