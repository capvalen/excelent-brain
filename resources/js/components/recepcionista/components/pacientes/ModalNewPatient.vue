<template>
  <div class="modal fade" id="modalNewPatient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"> Datos del Nuevo Paciente</h5>
          <button type="button" id="cerrModalPatient" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
					<p class="mb-0"><strong>Datos Personales</strong></p>
          <form action="">
            <div class="form-group row">
              <div class="col-sm-6">
                <label for="name">Dni</label>
                <input type="text" class="form-control" name="dni" id="dni" v-model="paciente.dni" placeholder="Dni del paciente">
              </div>
              <div class="col-sm-6">
                <label for="name">Celular</label>
                <input type="text" class="form-control" name="phone" id="phone" v-model="paciente.phone" placeholder="Celular del paciente">
              </div>          
            </div>

            <div class="form-group">
              <label for="name">Paciente</label>
              <input type="text" class="form-control"  name="name" id="name" v-model="paciente.name" placeholder="Nombre del paciente">
            </div>

            <div class="form-group row">                   
              <div class="col-sm-12">
                <label for="name">Direccion de paciente</label>
                <input type="text" class="form-control" name="address" id="address" v-model="paciente.address.address" placeholder="Direccion del paciene">
              </div>

              <div class="col-sm-6 d-none">
                <label for="name">Email de paciente</label>
                <input type="email" class="form-control" name="email" id="email" v-model="paciente.email"  placeholder="Email de paciente"> 
              </div>
            </div>

            <div class="form-group row">
							<div class="col-sm-4">
									<label for="name">Departamento</label>
									<select v-model="paciente.address.department" class="form-control" id="department" @change="moverProvincias(true)">
										<option v-for="departamento in ubigeo.departamentos" :value="departamento.idDepa">{{ departamento.departamento }}</option>
									</select>
							</div>
							<div class="col-sm-4">
									<label for="name">Provincia</label>
									<select v-model="paciente.address.province" class="form-control" id="provincia" @change="moverDistritos()">
										<option v-for="provincia in provincias" :value="provincia.idProv">{{ provincia.provincia }}</option>
									</select>
							</div>
							<div class="col-sm-4">
									<label for="name">Distrito</label>
									<select v-model="paciente.address.district" class="form-control" id="distrito">
										<option v-for="distrito in distritos" :value="distrito.idDist">{{ distrito.distrito }}</option>
									</select>
							</div>
						</div>

           

            <div class="form-group row">
							<div class="col-sm-4">
                <label for="name">Ocupación</label>
                <input type="text" class="form-control" name="occupation" id="occupation" v-model="paciente.occupation"  placeholder="Ocuación del paciente"> 
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="">Estado Civil</label>
                  <select class="form-control" name="marital_status" id="marital_status" v-model="paciente.marital_status">
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
                  <select class="form-control" name="instruction_degree" id="instruction_degree" v-model="paciente.instruction_degree">
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
                <input type="date" class="form-control" name="birth_date" id="birth_date" v-model="paciente.birth_date">
              </div>
              <div class="col-sm-6">
								<label for="name">Género</label>
								<select class="form-control" id="sexo" v-model="paciente.gender">
									<option value="2">Sin definir</option>
									<option value="0">Femenino</option>
									<option value="1">Masculino</option>
								</select>
							</div>
            </div>

						<p class="mb-0"><strong>Datos de Contacto de Emergencia</strong></p>
						<div class="form-group row">
							<div class="col-sm-4">
								<label for="name">Nombre</label>
								<input type="text" class="form-control" name="contacto" id="contacto" v-model="paciente.relative.name"  placeholder="Contacto" autocomplete="off">
							</div>
							<div class="col-sm-4">
								<label for="name">Celular emergencia</label>
								<input type="text" class="form-control" name="contacto_celular" id="contacto_celular" v-model="paciente.relative.phone"  placeholder="Celular" autocomplete="off">
							</div>
							<div class="col-sm-4">
								<label for="name">Parentezco</label>
								<input type="text" class="form-control" name="parentezco" id="parentezco" v-model="paciente.relative.kinship"  placeholder="Parentezco" autocomplete="off">
							</div>
						</div>
						<hr>

            <!-- <div class="form-group row"> 
              <div class="col-sm-12">
                <label for="name">Nombre de pariente</label>
                <input type="text" class="form-control" name="relative_name" id="relativename" v-model="paciente.relative.name" placeholder="Nombre de pariente">
              </div>                               
            </div>  

            <div class="form-group row">
              <div class="col-sm-6">
                <label for="name">Telefono</label>
                <input type="text" class="form-control" name="relative_phone" id="relativephone" v-model="paciente.relative.phone"  placeholder="Telefono de pariente"> 
              </div>
              <div class="col-sm-6">
                <label for="name">Parentesco</label>
                <input type="text" class="form-control" name="kinship" id="kinship" v-model="paciente.relative.kinship"  placeholder="Parentesco"> 
              </div>
            </div>                -->
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          <button  @click="createDataPatient()" type="button" class="btn btn-primary">Editar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import alertify from 'alertifyjs'
  export default {
    name: "modal_patient",

    props:{
      
    }, 

    data () {
      return {
				ubigeo: {departamentos:[], provincias:[], distritos:[]},
				provincias:[], distritos:[], paciente:{
					dni:'', name:'', phone:'',
					relative:{}, address:{}
				}
      }
    },

    methods:{
      createDataPatient(){
				if(this.paciente.dni.length<8){
					alertify.notify('El DNI debe ser de 8 dígitos.', 'danger', 10);
				}
				else if( this.paciente.name=='' || this.paciente.dni =='' || this.paciente.phone=='' ){
					alertify.notify('Datos mínimos DNI, nombre y celular del paciente', 'danger', 10);
				}
				else{
					this.axios.post(`/api/patient/new/`, {paciente: this.paciente})
					.then(res => { console.log(res.data);
						this.closeModal()
						if(parseInt(res.data)>0){
							this.$swal('Datos de paciente guardados con éxito')
						}else{
							this.$swal({icon: 'error', text: 'El dni ya está registrado: '+ res.data})
						}
					})
					.catch(error => {
							console.log('error'+error)
					})
				}
      },

      closeModal() {
        document.getElementById('cerrModalPatient').click();
      },
			async listarDepartamentos(){
				await this.axios.get('/api/departamentos')
				.then(response => { //console.log(response.data);
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
				let idDepa= this.paciente.address.department;
				this.provincias = this.ubigeo.provincias.filter(provincia=> provincia.idDepa == idDepa)
				if(borrar) this.paciente.address.district=-1;
			},
			moverDistritos(){
				let idProv= this.paciente.address.province;
				this.distritos = this.ubigeo.distritos.filter(distrito=> distrito.idProv == idProv)
			}
    },
		created () {
			this.listarDepartamentos();
			this.paciente.address.department = 12;
			this.paciente.address.province = 103;
			this.paciente.address.district = 1006;
			this.paciente.gender =2;
			this.paciente.marital_status =1;
			this.paciente.instruction_degree = 6;
    }

  }
</script>

<style>
.ajs-message{border-radius: 5px!important;}
.ajs-success { background-color: rgb(33, 201, 89)!important; }
.ajs-danger { background-color: rgb(232, 27, 0)!important; color:white!important; }
</style>