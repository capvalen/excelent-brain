<template>
  <div class="modal fade" id="patientModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="exampleModalLabel"> Datos del Paciente</h5>
          <button type="button" id="cerrModalPatient" class="close"  data-bs-dismiss="modal" aria-label="Close">
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
            </div>

            <div class="form-group">
              <label for="name">Paciente</label>
              <input type="text" class="form-control"  name="name" id="name" v-model="dataPatient.name" placeholder="Nombre del paciente">
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


            <!--  
            <div class="form-group row"> 

              <div class="col-sm-12">
                <label for="name">Nombre de pariente</label>
                <input type="text" class="form-control" name="relative_name" v-model="dataPatient.relative.name" id="relativename" placeholder="Nombre de pariente">
              </div>                               
            </div>   -->

            <!-- <div class="form-group row">
              <div class="col-sm-6">
                <label for="name">Telefono</label>
                <input type="text" class="form-control" name="relative_phone" v-model="dataPatient.relative.phone" id="relativephone" placeholder="Telefono de pariente"> 
              </div>
              <div class="col-sm-6">
                <label for="name">Parentesco</label>
                <input type="text" class="form-control" name="kinship" id="kinship" v-model="dataPatient.relative.kinship" placeholder="Parentesco"> 
              </div>

            </div>                -->

          </form>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" @click="updatePatient()" class="btn btn-outline-primary"><i class="fas fa-sync-alt"></i> Actualizar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ModalEditPaciente',
  
  props: {
    dataPatient: Object
  },

  data () {
    return {
      datos: '',
			ubigeo: {departamentos:[], provincias:[], distritos:[]},
			provincias:[], distritos:[],
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
      document.getElementById('cerrModalPatient').click();
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
			this.listarDepartamentos(false);
    },
  },
	mounted(){
		this.$parent.$on('cambioDato', this.capturaSeñal);
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
		this.listarDepartamentos();

  },
} 
</script>