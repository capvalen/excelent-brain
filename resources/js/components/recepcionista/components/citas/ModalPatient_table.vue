<template>
  <div class="modal fade" id="patientModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" >
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="exampleModalLabel"> Datos del Paciente para actualizar</h5>
          <button type="button" id="cerrModalPatient" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" v-if="dataCit.patient">
					<p class="mb-0"><strong>Datos Personales del paciente</strong></p>
          <form action="">
            <div class="form-group row">
              <div class="col-sm-6">
                <label for="name">Dni</label>
                <input type="text" class="form-control" name="dni" id="dni" v-model="dataCit.patient.dni" placeholder="Dni del paciente" readonly="true">
              </div>
              <div class="col-sm-6">
                <label for="name">Celular</label>
                <input type="text" class="form-control" name="phone" id="phone" v-model="dataCit.patient.phone" placeholder="Celular del paciente">
              </div>          
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="name"> Apellidos</label>
                  <input type="text" class="form-control"  name="name" id="name" v-model="dataCit.patient.name" placeholder="Nombre del paciente">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="name">Nombres </label>
                  <input type="text" class="form-control"  name="name" id="name" v-model="dataCit.patient.nombres" placeholder="Nombre del paciente">
                </div>
                
              </div>
            </div>


            <div class="form-group row">     
              <div class="col-sm-6 ">
                <label for="name">Correo electrónico</label>
                <input type="email" class="form-control" name="email" id="email" v-model="dataCit.patient.email"  placeholder="Correo electrónico"> 
              </div>

              <div class="col-sm-12">
                <label for="name">Dirección</label>
                <input type="text" class="form-control" name="address" id="address" v-model="dataCit.patient.address.address" placeholder="Direccion del paciente">
              </div>

              
            </div>

            <div class="form-group row">
							<div class="col-sm-4">
									<label for="name">Departamento</label>
									<select v-model="dataCit.patient.address.department" class="form-select" id="department" @change="moverProvincias(true)">
										<option v-for="departamento in ubigeo.departamentos" :value="departamento.idDepa">{{ departamento.departamento }}</option>
									</select>
							</div>
							<div class="col-sm-4">
									<label for="name">Provincia</label>
									<select v-model="dataCit.patient.address.province" class="form-select" id="provincia" @change="moverDistritos()">
										<option v-for="provincia in provincias" :value="provincia.idProv">{{ provincia.provincia }}</option>
									</select>
							</div>
							<div class="col-sm-4">
									<label for="name">Distrito</label>
									<select v-model="dataCit.patient.address.district" class="form-select" id="distrito">
										<option v-for="distrito in distritos" :value="distrito.idDist">{{ distrito.distrito }}</option>
									</select>
							</div>
						</div>

           

            <div class="form-group row">
							<div class="col-sm-4">
                <label for="name">Ocupación</label>
                <input type="text" class="form-control" name="occupation" id="occupation" v-model="dataCit.patient.occupation"  placeholder="Ocuación del paciente" autocomplete="off"> 
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="">Estado Civil</label>
                  <select class="form-select" name="marital_status" id="marital_status" v-model="dataCit.patient.marital_status">
                    <option value="1">Soltero</option>
                    <option value="2">Casado</option>
                    <option value="3">Viudo</option>
                    <option value="4">Divorciado</option>
                    <option value="5">Conviviente</option>
                  </select>
                </div>  
              </div>

              <div class="col-sm-4">
                <div class="form-group">
                  <label for="">Grado de instrucción</label>
                  <select class="form-select" name="instruction_degree" id="instruction_degree" v-model="dataCit.patient.instruction_degree">
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
						<div class="form-group row">
              <div class="col-sm-6">
                <label for="name">Fecha de nacimiento</label>
                <input type="date" class="form-select" name="birth_date" id="birth_date" v-model="dataCit.patient.birth_date">
              </div>
              <div class="col-sm-6">
								<label for="name">Género</label>
								<select class="form-control" id="sexo" v-model="dataCit.patient.gender">
									<option value="2">Sin definir</option>
									<option value="0">Femenino</option>
									<option value="1">Masculino</option>
							    <option value="3">LGTB+</option>
								</select>
							</div>
            </div>

						<p class="mb-0"><strong>Datos de Contacto de Emergencia</strong></p>
						<div class="form-group row">
							<div class="col-sm-4">
								<label for="name">Nombre</label>
								<input type="text" class="form-control" name="contacto" id="contacto" v-model="dataCit.patient.relative.name"  placeholder="Contacto" autocomplete="off">
							</div>
							<div class="col-sm-4">
								<label for="name">Celular emergencia</label>
								<input type="text" class="form-control" name="contacto_celular" id="contacto_celular" v-model="dataCit.patient.relative.phone"  placeholder="Celular" autocomplete="off">
							</div>
							<div class="col-sm-4">
								<label for="name">Parentesco</label>
								<input type="text" class="form-control" name="parentezco" id="parentezco" v-model="dataCit.patient.relative.kinship"  placeholder="Parentesco" autocomplete="off">
							</div>
						</div>
						<hr>

            <!-- <div class="form-group row"> 
              <div class="col-sm-12">
                <label for="name">Nombre de pariente</label>
                <input type="text" class="form-control" name="relative_name" id="relativename" v-model="dataCit.patient.relative.name" placeholder="Nombre de pariente">
              </div>                               
            </div>  

            <div class="form-group row">
              <div class="col-sm-6">
                <label for="name">Telefono</label>
                <input type="text" class="form-control" name="relative_phone" id="relativephone" v-model="dataCit.patient.relative.phone"  placeholder="Telefono de pariente"> 
              </div>
              <div class="col-sm-6">
                <label for="name">Parentesco</label>
                <input type="text" class="form-control" name="kinship" id="kinship" v-model="dataCit.patient.relative.kinship"  placeholder="Parentesco"> 
              </div>
            </div>                -->
          </form>
        </div>
        <div class="modal-footer border-0">
          <button  @click="updateDataPatient()" type="button" class="btn btn-outline-primary">Editar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "modal_patient",

    props:{
      dataCit: Object
    }, 

    data () {
      return {
        dataPatient: '',
				ubigeo: {departamentos:[], provincias:[], distritos:[]},
				provincias:[], distritos:[],
      }
    },

    methods:{
      async updateDataPatient(){
        this.dataPatient.email = this.dataPatient.email =='null' ?? ''
        await this.axios.put(`/api/patient/${this.dataPatient.patient.id}`, this.dataPatient.patient)
        .then(res => { //console.log(res.data);
          this.closeModal()
          this.$swal('Datos de paciente actualizado con éxito')
        })
        .catch(error => {
            console.log('error'+error)
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
				let idDepa= this.dataPatient.patient.address.department ;
				this.provincias = this.ubigeo.provincias.filter(provincia=> provincia.idDepa == idDepa)
				if(borrar) this.dataPatient.patient.address.district=-1;
			},
			moverDistritos(){
				let idProv= this.dataPatient.patient.address.province;
				this.distritos = this.ubigeo.distritos.filter(distrito=> distrito.idProv == idProv)
			}
    },

    computed: {
      actualizarDatos () {
				console.log('data', this.dataCit)
        this.dataPatient = this.dataCit
				this.listarDepartamentos(false);
				/* if(this.dataPatient.address)
				else
					this.dataPatient.address.department = 12
					this.dataPatient.address.province = 103
					this.dataPatient.address.province = 1006 */
      }
    },
    updated () {
      this.actualizarDatos
    },
    created () {
      this.actualizarDatos;
			this.listarDepartamentos();
    }
  }
</script>

  