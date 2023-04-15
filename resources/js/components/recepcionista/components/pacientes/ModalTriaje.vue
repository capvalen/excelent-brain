<template>
<!-- Modal -->
<div class="modal fade" id="modalTriaje" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ficha de ingreso - Triaje</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><strong>DATOS DE AFILIACIÓN</strong></p>
				<div class="form-group row">
					<div class="col-sm-6">
						<label for="name">Dni</label>
						<input type="text" class="form-control" name="dni" id="dni" v-model="dataPatient.dni" placeholder="Dni del paciente" readonly>
					</div>
					<div class="col-sm-6">
						<label for="name">Celular</label>
						<input type="text" class="form-control" name="phone" id="phone" v-model="dataPatient.phone" placeholder="Telefono del paciente">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-12">
						<label for="name">Apellidos y Nombres</label>
						<input type="text" class="form-control" name="apellidos" id="dni" v-model="dataPatient.name" placeholder="Dni del paciente">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-6">
						<label for="name">Género</label>
						<select class="form-control" id="sexo" v-model="dataPatient.gender">
							<option value="">Elija el un género</option>
							<option value="0">Femenino</option>
							<option value="1">Masculino</option>
						</select>
					</div>
					<div class="col-sm-6">
						<label for="name">Fecha Nac. <span></span></label>
						<input type="date" class="form-control" name="phone" id="phone" v-model="dataPatient.birth_date" placeholder="Telefono del paciente">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-6">
						<label for="name">Ocupación</label>
						<input type="text" class="form-control" name="ocupacion" id="ocupacion" v-model="dataPatient.occupation" placeholder="Ocupación del paciente">
					</div>
					<div class="col-sm-6">
						<label for="name">Fecha actual</label>
						<input type="date" class="form-control" name="phone" id="phone" v-model="consulta.fecha" placeholder="Telefono del paciente" readonly>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-12">
						<label for="name">Motivo de la consulta</label>
						<textarea class="form-control" id="Motivo" name="Motivo" rows="3" v-model="consulta.motivo" ></textarea>
					</div>
				</div>
				
				<div class="form-group row">
					<div class="col-sm-12">
						<label for="name">SINTOMATOLOGÍA</label>
						<textarea class="form-control" id="sintomatologia" name="sintomatologia" rows="3" v-model="consulta.sintomatologia" ></textarea>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-12">
						<label for="name">ANTECEDENTES</label>
						<textarea class="form-control" id="antecedentes" name="antecedentes" rows="3" v-model="consulta.antecedentes" ></textarea>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-6">
						<label for="name">Prioridad</label>
						<select class="form-control" id="prioridad" v-model="consulta.prioridad">
							<option value="1">I</option>
							<option value="2">II</option>
							<option value="3">III</option>
							<option value="4">IV</option>
						</select>
					</div>
					<div class="col-sm-6">
						<label for="name">Especialista</label>
						<select class="form-control" id="prioridad" v-model="consulta.especialista">
							<option v-for="profesional in profesionales" :value="profesional.id">{{profesional.name}}</option>
						</select>
					</div>
				</div>
      </div>
			
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="insertarTriaje()">Guardar</button>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import moment from "moment"
export default {
	name: 'modalTriaje',
	props: {
		dataPatient: Object, profesionales:[]
	},
	data(){
		return {
			datos:'', consulta:{
				fecha: moment().format('YYYY-MM-DD'),
				patient_id: this.dataPatient.id,
				motivo:'', sintomatologia:'',antecedentes:'', especialista:2, prioridad:1
			}
		}
	},
	methods:{
		async insertarTriaje(){
			await axios.post(`/api/insertarTriaje/${this.dataPatient.id}`,[this.dataPatient, this.consulta] )
			.then(res => {
				console.log(res.data['mensaje'])
        if(res.data['mensaje']>0){
					this.$swal.fire({title: 'Registrado el triaje ',
						text:'¿Deseas abrir el PDF de este registro?',
						showCancelButton: true,
						confirmButtonText: 'Si, ver el PDF!',
						cancelButtonText: 'No, salir'
					}).then((result) => {
						if (result.isConfirmed) {
							//abrir en una nueva ventana el PDF
						}
					})
				}else{
					this.$swal.fire({title: 'Hubo un error, reintenta luego', icon:'error'})
				}
      })
      .catch(err => {
        console.error(err)        
      })
		}
	},
	computed: {
    updateValues () {
      return this.datos = this.dataPatient
    }
  },
	updated() {
    this.updateValues;
  },
  created () {
    this.updateValues;
  },
	
}
</script>