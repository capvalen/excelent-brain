<template>
<!-- Modal -->
<div class="modal fade" id="modalTriaje" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ficha de ingreso - Triaje</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
				<p class="mb-0"><strong>Datos de Afiliación del Paciente</strong></p>
				<div class="form-group row">
					<div class="col-sm-6">
						<label for="name">Dni</label>
						<input type="text" class="form-control" name="dni" id="dni" v-model="dataPatient.dni" placeholder="Dni del paciente" readonly>
					</div>
					<div class="col-sm-6">
						<label for="name">Celular</label>
						<input type="text" class="form-control" name="phone" id="phone" v-model="dataPatient.phone" placeholder="Celular del paciente">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-12">
						<label for="name">Apellidos y Nombres</label>
						<input type="text" class="form-control" name="apellidos" id="apellidos_nombres" v-model="fullName" placeholder="Apellidos y Nombres">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-6">
						<label for="name">Género</label>
						<select class="form-select" id="sexo" v-model="dataPatient.gender">
							<option value="">Elija el un género</option>
							<option value="0">Femenino</option>
							<option value="1">Masculino</option>
							<option value="3">LGTB+</option>
						</select>
					</div>
					<div class="col-sm-6">
						<label for="name">Fecha Nacimiento <span></span></label>
						<input type="date" class="form-control" name="phone" id="phone" v-model="dataPatient.birth_date" placeholder="Telefono del paciente">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-6">
						<label for="name">Ocupación</label>
						<input type="text" class="form-control" name="ocupacion" id="ocupacion" v-model="dataPatient.occupation" placeholder="Ocupación del paciente">
					</div>
					<div class="col-sm-6">
						<label for="name">Fecha de atención</label>
						<input type="datetime-local" class="form-control" name="fecha" id="fecha" v-model="consulta.fecha" placeholder="Fecha de atención">
					</div>
				</div>
				<hr>
				<p class="mb-0"><strong>Datos de Contacto del Triaje</strong></p>
				<div class="form-group row">
					<div class="col-sm-2">
						<label for="">F.C.</label>
						<input type="text" class="form-control" name="fc" id="fc" v-model="consulta.fc" autocomplete="off">
					</div>
					<div class="col-sm-2">
						<label for="">F.R.</label>
						<input type="text" class="form-control" name="fr" id="fr" v-model="consulta.fr" autocomplete="off">
					</div>
					<div class="col-sm-2">
						<label for="">PA</label>
						<input type="text" class="form-control" name="pa" id="pa" v-model="consulta.pa" autocomplete="off">
					</div>
					<div class="col-sm-2">
						<label for="">T</label>
						<input type="text" class="form-control" name="t" id="t" v-model="consulta.t" autocomplete="off">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-3">
						<label for="">Peso</label>
						<input type="text" class="form-control" name="peso" id="peso" v-model="consulta.peso" autocomplete="off">
					</div>
					<div class="col-sm-3">
						<label for="">Talla</label>
						<input type="text" class="form-control" name="talla" id="talla" v-model="consulta.talla" autocomplete="off">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-12">
						<label for="name">Personal responsable</label>
						<input type="text" class="form-control" name="responsable" id="responsable" v-model="consulta.responsable" placeholder="Nombres del Personal Responsable" autocomplete="off">
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
					<div class="col-sm-12">
						<label for="name">Tipos de pruebas aplicadas</label>
						<textarea class="form-control" id="pruebas" name="pruebas" rows="3" v-model="consulta.pruebas" ></textarea>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-4">
						<label for="name">Prioridad</label>
						<select class="form-select" id="prioridad" v-model="consulta.prioridad">
							<option value="1">I</option> // Urgencia inmediata (psiquiatra)
							<option value="2">II</option> // Urgencia moderada (psiquiatría)
							<option value="3">III</option> // Paciente con riesgo psicosocial alto (psicología)
							<option value="4">IV</option> // Paciente con riesgo psicosocial medio (psicología)
							<option value="5">V</option> // Paciente con riesgo psicosocial bajo (psicología)
						</select>
					</div>
					<div class="col-sm-4">
						<label for="name">Especialista</label>
						<select class="form-select" id="especialista" v-model="consulta.especialista">
							<option v-for="profesional in profesionales" :value="profesional.id">{{profesional.name}}</option>
						</select>
					</div>
					<div class="col-sm-4">
						<label for="name">Referencia</label>
						<select class="form-select" id="referencia" v-model="consulta.referencia">
							<option value="1">PSICOLOGÍA</option>
							<option value="2">PSIQUIATRÍA</option>
							<option value="3">AMBOS</option>
							
						</select>
					</div>
				</div>
      </div>
			
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="insertarTriaje()">Guardar</button>
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
		dataPatient: [], profesionales:[]
	},
	data(){
		return {
			datos:'', consulta:{
				fecha: moment().format('YYYY-MM-DD HH:mm'),
				patient_id: -1,
				motivo:'', sintomatologia:'',antecedentes:'', especialista:2, prioridad:1,
				responsable: '', fv: '', fc: '', fr: '', pa: '', t: '', referencia:1, pruebas:'', peso:'', talla:''
			}
		}
	},
	methods:{
		async insertarTriaje(){
			this.consulta.patient_id = this.dataPatient.id
			await axios.post(`/api/insertarTriaje/${this.dataPatient.id}`,[this.dataPatient, this.consulta] )
			.then(res => {
				console.log(res.data)
        if(res.data['mensaje']>0){
					this.$swal.fire({title: 'Registrado el triaje ',
						text:'¿Deseas abrir el PDF de este registro?',
						showCancelButton: true,
						confirmButtonText: 'Si, ver el PDF!',
						cancelButtonText: 'No, salir'
					}).then((result) => {
						if (result.isConfirmed) {
							//abrir en una nueva ventana el PDF
							window.open("/api/triajePDF/"+res.data['mensaje'], "_blank");
						}
					})
				}else{
					this.$swal.fire({title: 'Hubo un error, reintenta luego', icon:'error'})
				}
      })
      .catch(err => {
        console.error(err)        
      })
		},
		limpiarModal(){
			this.datos=''
			this.consulta = {
				fecha: moment().format('YYYY-MM-DD HH:mm'),
				patient_id: -1,
				motivo:'', sintomatologia:'',antecedentes:'', especialista:2, prioridad:1,
				responsable: '', fv: '', fc: '', fr: '', pa: '', t: '', referencia:1, pruebas:'', peso:'', talla:''
			}
		}
	},
	watch:{
		dataPatient(){
			this.limpiarModal();
			//this.datos = this.dataPatient;
		}
	},
	computed: {		
		fullName: {
        get() {
					//console.log('asignar',JSON.stringify(this.dataPatient))
          return `${this.dataPatient.name} ${this.dataPatient.nombres}`;
        },
        set(value) {
            const parts = value.split(" ");
            this.dataPatient.name = parts.slice(0, -1).join(" "); // Apellidos
            this.dataPatient.nombres = parts.slice(-1).join(" "); // Nombres
        }
    },
  },
	updated() {
    //this.updateValues;
  },
  created () {
    //this.updateValues;
  },
	
}
</script>