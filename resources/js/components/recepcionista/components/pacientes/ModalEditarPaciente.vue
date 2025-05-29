<template>
	<div class="modal fade" id="modalEdicionPaciente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"> Datos del Paciente</h5>
        <button type="button" id="btnCerrarEdPac" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form action="">
            <div class="form-group row">
              <div class="col-sm-6">
                <label for="name">Dni</label>
                <input type="text" class="form-control" name="dni" id="dni" v-model="dataPatient.dni" placeholder="Dni del paciente">
              </div>
              <div class="col-sm-6">
                <label for="name">Teléfono</label>
                <input type="text" class="form-control" name="phone" id="phone" v-model="dataPatient.phone" placeholder="Telefono del paciente">
              </div>          
              <div class="col-sm-6">
                <label for="name">Paciente</label>
                <input type="text" class="form-control"  name="name" id="name" v-model="dataPatient.name" placeholder="Apellidos del paciente">
              </div>
              <div class="col-sm-6">
                <label for="name">Paciente</label>
                <input type="text" class="form-control"  name="nombres" id="nombres" v-model="dataPatient.nombres" placeholder="Nombres del paciente">
              </div>
            </div>


            <div class="form-group row">                   
              <div class="col-sm-6">
                <label for="name">Dirección</label>
                <input type="text" class="form-control" name="address" id="address" v-model="dataPatient.address.address" placeholder="Direccion del paciente">
              </div>

              <div class="col-sm-6">
                <label for="name">Correo electrónico</label>
                <input type="email" class="form-control" name="email" id="email" v-model="dataPatient.email" placeholder="Correo electrónico"> 
              </div>
            </div>

            <div class="form-group row">
							<div class="col-sm-4">
									<label for="name">Departamento</label>
									<select v-model="dataPatient.address.department" class="form-select" id="department" @change="moverProvincias(true)">
										<option v-for="departamento in ubigeo.departamentos" :value="departamento.idDepa">{{ departamento.departamento }}</option>
									</select>
							</div>
							<div class="col-sm-4">
									<label for="name">Provincia</label>
									<select v-model="dataPatient.address.province" class="form-select" id="provincia" @change="moverDistritos()">
										<option v-for="provincia in provincias" :value="provincia.idProv">{{ provincia.provincia }}</option>
									</select>
							</div>
							<div class="col-sm-4">
									<label for="name">Distrito</label>
									<select v-model="dataPatient.address.district" class="form-select" id="distrito">
										<option v-for="distrito in distritos" :value="distrito.idDist">{{ distrito.distrito }}</option>
									</select>
							</div>
						</div>

            <div class="form-group row">
              <div class="col-sm-6">
                <label for="name">Fecha de nacimiento</label>
                <input type="date" class="form-control" name="birth_date" v-model="dataPatient.birth_date" id="birth_date">
              </div>
							<div class="col-sm-6">
								<label for="name">Género</label>
								<select class="form-select" id="sexo" v-model="dataPatient.gender">
									<option value="2">Sin definir</option>
									<option value="0">Femenino</option>
									<option value="1">Masculino</option>
							    <option value="3">LGTB+</option>
								</select>
							</div>
              <div class="col-sm-6">
                <label for="name">Ocupación</label>
                <input type="text" class="form-control" name="occupation" v-model="dataPatient.occupation" id="occupation" placeholder="Ocuación del paciente"> 
              </div>
            </div>

            <div class="form-group row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Estado Civil</label>
                  <select class="form-select" name="marital_status" id="marital_status" v-model="dataPatient.marital_status">
                    <option value="1">Soltero</option>
                    <option value="2">Casado</option>
                    <option value="3">Viudo</option>
                    <option value="4">Divorciado</option>
                    <option value="5">Conviviente</option>
                  </select>
                </div>  
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Grado de instrucción</label>
                  <select class="form-select" name="instruction_degree" id="instruction_degree" v-model="dataPatient.instruction_degree">
                    <option value="1">Inicial</option>
                    <option value="2">Primaria</option>
                    <option value="3">Secundaria</option>
                    <option value="4">Superior</option>
                    <option value="5">Tecnico</option>
                    <option value="6">Sin instrucción</option>
                  </select>
                </div>
              </div>       
            </div> 

            <hr>
          <h5 class="modal-title" id="exampleModalLabel"> Datos del familiar</h5>

            <div class="form-group row">
              <div class="col-sm-12">
                <label for="name">Nombre del primer contacto</label>
                <input type="text" class="form-control" name="relative_name" v-model="dataPatient.relative[0].name" id="relativename" placeholder="Nombre de pariente">
              </div>                               
            
              <div class="col-sm-6">
                <label for="name">Telefono</label>
                <input type="text" class="form-control" name="relative_phone" v-model="dataPatient.relative[0].phone" id="relativephone" placeholder="Telefono de pariente"> 
              </div>
              <div class="col-sm-6">
                <label for="name">Parentesco</label>
                <input type="text" class="form-control" name="kinship" id="kinship" v-model="dataPatient.relative[0].kinship" placeholder="Parentesco"> 
              </div>
            </div>


            <div class="form-group row">
              <div class="col-sm-12">
                <label for="name">Nombre del segundo contacto</label>
                <input type="text" class="form-control" name="relative_name" v-model="dataPatient.relative[1].name" id="relativename" placeholder="Nombre de pariente">
              </div>                               
            
              <div class="col-sm-6">
                <label for="name">Telefono</label>
                <input type="text" class="form-control" name="relative_phone" v-model="dataPatient.relative[1].phone" id="relativephone" placeholder="Telefono de pariente"> 
              </div>
              <div class="col-sm-6">
                <label for="name">Parentesco</label>
                <input type="text" class="form-control" name="kinship" id="kinship" v-model="dataPatient.relative[1].kinship" placeholder="Parentesco"> 
              </div>
            </div>

          </form>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" @click="updatePatient()" class="btn btn-outline-primary" data-bs-dismiss="modal"><i class="fas fa-sync-alt"></i> Actualizar</button>
        </div>
    </div>
  </div>
</div>
</template>
<script>
export default {
  name: 'ModalEditarPaciente',
  
  props: {
    dataPatient: Object
  },

  data () {
    return {
      datos: '',
			ubigeo: {departamentos:[], provincias:[], distritos:[]},
			provincias:[], distritos:[],relacion:[]
    }
  },

  methods: {
    async updatePatient() {
      await this.axios.put(`/api/patient/${this.dataPatient.id}`, this.dataPatient) 
      .then(res => {
        console.log(res)
        this.closeModal()
        this.$swal('Datos de paciente actualizado con éxito')

      })
      .catch(err => {
        console.error(err)        
      })
    },

    closeModal() {
      document.getElementById('btnCerrarEdPac').click();
    },
		async listarDepartamentos(){
			await this.axios.get('/api/departamentos')
			.then(response => {
				this.ubigeo.departamentos = response.data['departamentos'];
				this.ubigeo.provincias = response.data['provincias'];
				this.ubigeo.distritos = response.data['distritos'];

				this.provincias = this.ubigeo.provincias.filter(provincia=> provincia.idDepa == 12)
				this.distritos = this.ubigeo.distritos.filter(distrito=> distrito.idProv == 103)
	
				this.moverProvincias(false)
				this.moverDistritos()
			})
		},
		moverProvincias(borrar){
			let idDepa= this.dataPatient.address.department ;
			this.provincias = this.ubigeo.provincias.filter(provincia=> provincia.idDepa == idDepa)
			if(borrar) this.dataPatient.patient.address.district=-1;
		},
		moverDistritos(){			
			let idProv= this.dataPatient.address.province;
			this.distritos = this.ubigeo.distritos.filter(distrito=> distrito.idProv == idProv)
		},
		capturaSeñal() {
      console.log('apli');
      if(this.dataPatient.relative.length==0){
        this.dataPatient.relative.push({ id:-1, name: '', nombres: '', phone:'' })
      }
			this.listarDepartamentos(false);
    },
  },
	mounted(){
		//this.$parent.$on('cambioDato', this.capturaSeñal);
	},

  computed: {
    updateValues () {      
			this.listarDepartamentos();
      return this.datos = this.dataPatient
    }
  },

  updated() {
    //this.updateValues;
		//console.log('ver paciente rel ',this.dataPatient.relative)
  },

  created () {
    this.updateValues;
  },
} 
</script>