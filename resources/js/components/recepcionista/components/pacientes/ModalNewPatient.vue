<template>
  <div class="modal fade" id="modalNewPatient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"> Datos del Nuevo Paciente</h5>
          <button type="button" id="cerrModalPatient" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
					<p class="mb-0"><strong>Datos Personales</strong></p>
          <form action="">
            <div class="form-group row">
              <div class="col-sm-6">
								<label for="name">Dni</label>
								<div class="input-group mb-3">
									<input type="text" class="form-control" name="dni" id="dni" v-model="paciente.dni" placeholder="Dni del paciente" @keypress.enter="reniec()">
									<button class="btn btn-outline-secondary" type="button" id="button-addon2" @click="reniec()"><i class="fas fa-search"></i></button>
								</div>
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
                <label for="name">Dirección de paciente</label>
                <input type="text" class="form-control" name="address" id="address" v-model="paciente.address.address" placeholder="Dirección del paciente">
              </div>

              <div class="col-sm-6 d-none">
                <label for="name">Email de paciente</label>
                <input type="email" class="form-control" name="email" id="email" v-model="paciente.email"  placeholder="Email de paciente"> 
              </div>
            </div>

            <div class="form-group row">
							<div class="col-sm-4">
									<label for="name">Departamento</label>
									<select v-model="paciente.address.department" class="form-select" id="department" @change="moverProvincias(true)">
										<option v-for="departamento in ubigeo.departamentos" :value="departamento.idDepa">{{ departamento.departamento }}</option>
									</select>
							</div>
							<div class="col-sm-4">
									<label for="name">Provincia</label>
									<select v-model="paciente.address.province" class="form-select" id="provincia" @change="moverDistritos()">
										<option v-for="provincia in provincias" :value="provincia.idProv">{{ provincia.provincia }}</option>
									</select>
							</div>
							<div class="col-sm-4">
									<label for="name">Distrito</label>
									<select v-model="paciente.address.district" class="form-select" id="distrito">
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
                  <select class="form-select" name="marital_status" id="marital_status" v-model="paciente.marital_status">
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
                  <select class="form-select" name="instruction_degree" id="instruction_degree" v-model="paciente.instruction_degree">
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
                <input type="date" class="form-select" name="birth_date" id="birth_date" v-model="paciente.birth_date">
              </div>
              <div class="col-sm-6">
								<label for="name">Género</label>
								<select class="form-select" id="sexo" v-model="paciente.gender">
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
								<input type="text" class="form-control" name="contacto" id="contacto" v-model="paciente.relative.name"  placeholder="Contacto" autocomplete="off">
							</div>
							<div class="col-sm-4">
								<label for="name">Celular emergencia</label>
								<input type="text" class="form-control" name="contacto_celular" id="contacto_celular" v-model="paciente.relative.phone"  placeholder="Celular" autocomplete="off">
							</div>
							<div class="col-sm-4">
								<label for="name">Parentesco</label>
								<input type="text" class="form-control" name="parentezco" id="parentezco" v-model="paciente.relative.kinship"  placeholder="Parentesco" autocomplete="off">
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
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
          <button  @click="createDataPatient()" type="button" class="btn btn-primary">Editar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import alertify from 'alertifyjs'
  export default {
    name: "modal_new_patient",

    props:{
      
    }, 

    data () {
      return {
				ubigeo: {departamentos:[], provincias:[], distritos:[]},
				token:'087d16c0688f5150268342d085a55d54b5064c7649596011f03b35b935899a50',
				provincias:[], distritos:[], paciente:{
					dni:'', name:'', phone:'',
					relative:{}, address:{}
				}
      }
    },

    methods:{
			reniec(){
				this.$swal.fire({
					title: 'Buscando paciente',
					timer: 2500,
					timerProgressBar: true,
					didOpen: () => {
						timerProgressBar: true,
						this.$swal.showLoading()
					},
				})

				this.axios.get("/api/buscar/"+this.paciente.dni)
				.then(res => {
					if (res.data.patient == null) { //Buscar en reniec
						window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
						this.axios.get(`https://apiperu.dev/api/dni/${this.paciente.dni}`) //?api_token=${this.token}
						.then(response => {
							console.log(response.data)
							this.paciente.name = response.data.message || `${response.data.data.apellido_paterno} ${response.data.data.apellido_materno} ${response.data.data.nombres}`;
							if (response.data.success) {
								this.patientNew = false

								this.$swal.fire({
									icon: 'success',
									title: 'Okey',
									text: 'Paciente nuevo',
								})
							} else {
								this.$swal.fire({
									icon: 'error',
									title: 'Oops...',
									text: 'DNI no encontrado!',
									footer: 'Vuelve a intentarlo'
								})
							}
						})
						.catch(err => {
							console.error(err)
						})
					}else{ //encontro en la DB
						this.$swal.fire({
							title: 'Buscando paciente',
							timer: 10,
						})
						console.log('datos del paciente',res.data);

						this.paciente.name = res.data.patient.name;
						this.paciente.phone = res.data.patient.phone;
						this.paciente.email = res.data.patient.email;
						this.paciente.address.address = res.data.address.address;
						this.paciente.birth_date = res.data.patient.birth_date;
						this.paciente.marital_status = res.data.patient.marital_status;
						this.paciente.instruction_degree = res.data.patient.instruction_degree;
						this.paciente.gender = typeof parseInt(res.data.patient.gender) === 'number' && res.data.patient.gender !==null ? res.data.patient.gender : 2;
						this.paciente.occupation = res.data.patient.occupation;
						this.paciente.address = res.data.patient.address.address;
						this.paciente.department = res.data.patient.address.department;
						this.paciente.province = res.data.patient.address.province;
						this.paciente.district = res.data.patient.address.district;
						this.paciente.name = res.data.relacion.name =='null' ? '' : res.data.relacion.name;
						this.paciente.phone = res.data.relacion.phone =='null' ? '' : res.data.relacion.phone;
						this.paciente.kinship = res.data.relacion.kinship =='null' ? '' : res.data.relacion.kinship;
					}
				})
				.catch(err => {
					console.error(err)
				})
				.finally(result => {
					this.switchReciec = 1;
					document.querySelector(".btnReniec").classList.replace('btn-danger', 'btn-info')
				})
			},
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