<template>
	<div class="modal fade" id="modalVerEstados" tabindex="-1" aria-labelledby="modalEvolution" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header bg-primary text-white">
						<h5 class="modal-title" id="infoModalLabel">Perfil del paciente</h5>
						<button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
					</div>
					<div class="modal-body">
						<p class="mb-0 lead">Paciente: {{ dataPatient.name }}</p>
						<div class="card mb-2">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-3">
										<select class="form-select text-capitalize" id="" v-model="semaforo.codigo">
											<option v-for="estado in estados" class="text-capitalize" :value="estado.id">{{estado.valor}}</option>
										</select>
									</div>
									<div class="col-sm-6">
										<input type="text" class="form-control" placeholder="Detalles" v-model="semaforo.observaciones">
									</div>
									<div class="col-sm-3">
										<button class="btn btn-outline-secondary" @click="enviarSemaforo()"><i class="fas fa-share"></i> Enviar</button>
									</div>
								</div>
								<p class="mt-2 mb-0">Detalle: {{ verDetalle(semaforo.codigo) }}</p>
							</div>
						</div>
						<table class="table table-striped">
							<thead>
								<tr>
									<th>N°</th>
									<th>Fecha</th>
									<th>Calificación</th>
									<th>Detalle</th>
									<th>@</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(semaf, index) in dataPatient.semaforo">
									<td>{{ index+1 }}</td>
									<td>{{ fechaLatam(semaf.registro) }}</td>
									<td class="text-capitalize">{{ queCodigo(semaf.codigo) }}</td>
									<td class="text-capitalize">{{ semaf.observaciones }}</td>
									<td><button class="btn btn-danger btn-sm" @click="eliminarEstado(semaf.id, index)"><i class="fas fa-trash"></i></button></td>
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
	export default {
		name: 'ModalVerEstados',
		props: {
			dataPatient: Object, estados:[]
		},
		data(){
			return {
				semaforo:{ codigo: 1, observaciones:'' }
			}
		},
		methods: {
			fechaLatam(fecha){
				return moment(fecha).format('DD/MM/YYYY');
			},
			enviarSemaforo(){
				this.axios.post('/api/insertarSemaforo',{
					id: this.dataPatient.id,
					semaforo: this.semaforo
				}).then(response=>{
					if(response.data.msg =='insertado con éxito'){
						this.dataPatient.semaforo.unshift( {registro: moment(), codigo: this.semaforo.codigo, observaciones: this.semaforo.observaciones});
					}
					this.semaforo.codigo = 1
					this.semaforo.observaciones=''
				})
			},
			queCodigo(tipo){
				return this.estados.filter(z=> z.id == tipo )[0].valor;
			},
			eliminarEstado(id, index){
				if(confirm('¿Deseas eliminar el estado?')){
					this.axios.post('/api/eliminarSemaforo/'+id)
					.then(response =>{
						if(response.data.msg=='eliminado'){
							this.dataPatient.semaforo.splice(index, 1)
						}
					})
				}
			},
		},
		computed:{
			verDetalle(){
				return (id)=>{
					return this.estados.find(x=> x.id == id).detalle
				}
			}
		}
	}
</script>