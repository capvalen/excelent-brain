<template>
	<div class="modal fade" id="modalCambiarSeguimiento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Cambiar seguimiento</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <select class="form-select" v-model="seguir.idSeguimiento">
					<option v-for="seguimiento in seguimientos" :value="seguimiento.id">{{seguimiento.seguimiento}}</option>
				</select>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal" @click="asginarSeguimiento()"><i class="fas fa-plus"></i> Asignar seguimiento</button>
      </div>
    </div>
  </div>
</div>
</template>

<script>
export default {
	name: 'modalCambiarSeguimiento',
	props:['seguimientos', 'idPaciente'],
	data(){ return {
		seguir:{idSeguimiento:1, idUsuario:null, patient_id:null}
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
				if( parseInt(response.data) > 0)
					this.$emit('cambiar', this.seguir.idSeguimiento)
			})
		}
	},
	mounted(){
		this.buscarUsuario();
	}
}
</script>