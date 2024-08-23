<template>
		<div class="modal fade" id="modalAgendarCita" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Nueva cita</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Seleccione una fecha nueva para agendar una cita.</p>
				<input type="date" class="form-control" v-model="fechaCita">
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-primary" @click="crear()" data-bs-dismiss="modal" >Nueva cita</button>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import alertify from 'alertifyjs';
import moment from 'moment';
	export default {
		name: 'ModalAgendarCita',
		data () {
			return {
				fechaCita:moment().format('YYYY-MM-DD')
			}
		},
		props:['profesional', 'paciente'],


		methods: {
			crear(){
				this.axios.post('/api/agendarNuevaCita',{
					fecha: this.fechaCita,
					idProfesional : this.profesional.id,
					idPaciente : this.paciente.id
				}).then(response=>{
					console.log(response.data)
				})
			},
			
			fechaLatam(fecha){
				return moment(fecha).format('DD/MM/YYYY');
			},
		}
	}
</script>