<template>
	<div class="modal fade" id="modalCambiarSeguimiento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Cambiar seguimiento</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
				<label for="">Tipo de seguimiento</label>
        <select class="form-select" v-model="seguir.idSeguimiento">
					<option v-for="seguimiento in seguimientos" :value="seguimiento.id">{{seguimiento.seguimiento}}</option>
				</select>
				<label for="">¿Algún dato para anotar?</label>
				<input type="text" class="form-control" v-model="seguir.motivo">
				<div class="mt-2 d-grid d-flex justify-content-end">
					<button type="button" class="btn btn-outline-primary btn-sm" data-bs-dismiss="modal" @click="asginarSeguimiento()"><i class="fas fa-plus"></i> Asignar seguimiento</button>
				</div>
				<p class="my-2"><strong>Historial de seguimientos</strong></p>
				<ol class="list-group list-group-numbered">
					<li class="list-group-item d-flex justify-content-between align-items-start" v-for="historial in historiales">
						<div class="ms-2 me-auto">
							<div class="fw-bold">{{historial.seguimiento}}</div>
							<p class="mb-0 text-capitalize"><i class="far fa-comment"></i> {{ historial.observaciones ? historial.observaciones : 'Sin respuesta' }}</p>
						</div>
						<span class="badge rounded-pill text-bg-light"><i class="far fa-calendar-alt"></i> {{ fechaLatam(historial.registro) }}</span>
					</li>
				</ol>
				<p v-if="historiales.length==0">No hay registros anteriores</p>

      </div>
      <div class="modal-footer border-0">
      </div>
    </div>
  </div>
</div>
</template>

<script>
import moment from 'moment'
export default {
	name: 'modalCambiarSeguimiento',
	props:['seguimientos', 'idPaciente'],
	data(){ return {
		seguir:{idSeguimiento:1, idUsuario:null, patient_id:null, motivo:''}, historiales:[]
	}},
	methods:{
		buscarUsuario(){
			this.axios.get('/api/user')
			.then((res) => {
				this.seguir.idUsuario = res.data.user.id
			})
		},
		asginarSeguimiento(){
			this.seguir.patient_id=this.idPaciente
			this.axios.post('/api/insertarSeguimiento', this.seguir)
			.then(response => { //console.log(response.data);
				this.$emit('borrarIDSeguimiento')
				if( parseInt(response.data) > 0)
					this.$emit('cambiar', this.seguir.idSeguimiento)
					this.seguir = {idSeguimiento:1, idUsuario:null, patient_id:null, motivo:''}
			})
		},
		fechaLatam(fecha){
			return moment(fecha).format('DD/MM/YYYY');
		},
	},
	watch:{
		idPaciente(){
			this.axios('/api/pedirHistorialSeguimientos/'+this.idPaciente)
			.then(res => this.historiales = res.data)
		}
	},
	mounted(){
		this.buscarUsuario();
	}
}
</script>