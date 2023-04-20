<template>
<div class="modal fade bd-example-modal-lg" id="addCitaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header border-0">
      <h5 class="modal-title" id="addCitaModalLabel">Registrar Nueva Cita</h5>

      <button type="button" id="closeModal" @click="limpiarInputs(true)" class="btn btn-danger" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <form class="user" @submit="insertar" @keydown="prevenirEvent">
         
					<p class="mb-0"><strong>Datos Personales</strong></p>

          <div class="form-group row">
           <div class="col-sm-6">
              <label for="name">Tipo de documento</label>
              <select class="form-control" id="type_dni" v-model="cita.type_dni">
								<option value="1">D.N.I.</option>
								<option value="2">Carnet de extranjería</option>
								<option value="3">Pasaporte</option>
							</select>
            </div>           
          </div>
          <div class="form-group row">
            <div class="col-sm-6">
              <label v-if="cita.type_dni==1" for="name">D.N.I.</label>
              <label v-else for="name">Doc. Extranjero</label>
              <div class="form-inline">
                <input v-if="cita.type_dni==1" type="text" class="form-control w-75 mr-1" name="dni" id="dni" v-model="cita.dni" placeholder="DNI del paciente">
                <input v-else type="text" class="form-control w-75 mr-1" name="dni" id="dni" v-model="cita.dni" placeholder="Código de extranjería">
                <a @click="reniec" class="btnReniec btn btn-info"><i class="fas fa-search"></i></a>
              </div>  
            </div>

            <div class="col-sm-6">
              <label for="name">Celular</label>
              <input type="text" class="form-control" id="phone" v-model="cita.phone" placeholder="Celular del paciente">
            </div>           
          </div>

          <div class="form-group">
              <label for="name">Paciente</label>
              <input  type="text" class="form-control" id="name" v-model="cita.name" placeholder="Nombre del paciente">
              <!-- <input v-else type="text" class="form-control"  name="name" id="name" v-model="cita.name" placeholder="Nombre del paciente"> -->
          </div>

          <div class="form-group row">                   
            <div class="col-sm-12">
              <label for="name">Dirección de paciente</label>
              <input type="text" class="form-control" name="address" id="address" v-model="cita.address" placeholder="Dirección del paciente">
            </div>
            <div class="col-sm-6 d-none">
              <label for="name">Email de paciente</label>
              <input v-if="patientNew" readonly type="email" class="form-control" id="email" v-model="cita.email"  placeholder="Email de paciente"> 
              <input v-else type="email" class="form-control" name="email" id="email" v-model="cita.email"  placeholder="Email de paciente"> 
            </div>
          </div>

          <div class="form-group row">
          
						<div class="col-sm-4">
                <label for="name">Departamento</label>
								<select v-model="cita.department" class="form-control" id="department" @change="moverProvincias(true)">
									<option v-for="departamento in ubigeo.departamentos" :value="departamento.idDepa">{{ departamento.departamento }}</option>
								</select>
            </div>
						<div class="col-sm-4">
                <label for="name">Provincia</label>
								<select v-model="cita.province" class="form-control" id="provincia" @change="moverDistritos()">
									<option v-for="provincia in provincias" :value="provincia.idProv">{{ provincia.provincia }}</option>
								</select>
            </div>
						<div class="col-sm-4">
                <label for="name">Distrito</label>
								<select v-model="cita.district" class="form-control" id="distrito">
									<option v-for="distrito in distritos" :value="distrito.idDist">{{ distrito.distrito }}</option>
								</select>
            </div>
          </div>
          
          <div class="form-group row">
            <div class="col-sm-6">
                <label for="name">Fecha de nacimiento</label>
                <input type="date" class="form-control" name="birth_date" id="birth_date" v-model="cita.birth_date">
            </div>
            <div class="col-sm-6">
                <label for="name">Ocupación</label>
                <input type="text" class="form-control" name="occupation" id="occupation" v-model="cita.occupation"  placeholder="Ocupación del paciente"> 
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="marital_status">Estado Civil</label>
                <select class="form-control" name="marital_status" id="marital_status" v-model="cita.marital_status">
                  <option value="2">Casado</option>
                  <option value="5">Conviviente</option>
                  <option value="4">Divorciado</option>
                  <option value="1">Soltero</option>
                  <option value="3">Viudo</option>
                </select>
              </div>
            </div>
            
            <div class="col-sm-6">
              <div class="form-group">
                <label for="">Grado de instrucción</label>
             		<select class="form-control" name="instruction_degree" id="instruction_degree" v-model="cita.instruction_degree">
                  <option value="1">Inicial</option>
                  <option value="2">Primaria</option>
                  <option value="3">Secundaria</option>
                  <option value="4">Superior</option>
                  <option value="5">Técnico</option>
                  <option value="6">Sin instrucción</option>
                </select>
              </div>
            </div>
          </div>
					<hr>
					<p class="mb-0"><strong>Datos de la Nueva Cita</strong></p>

          <div class="form-group">
            <div class="form-group">
              <label for="">Profesional</label>                                  
              <select 
              class="form-control" 
              name="professional_id" 
              id="professional_id" 
              v-model="cita.professional_id"
              placeholder="Horario del profesional"
              @change="emitirProf()" 
              >
                <option value="" selected disabled>Selecciona un profesional</option>
                <option 
                v-for="profesional in profes" 
                :key="profesional.id" 
                :value="profesional.id" 
                >
                {{profesional.name}}
                </option>
              </select>
            </div>
          </div>
            
          <div class="form-group row">
            <div class="col-sm-6">
              <label for="name">Fecha de cita</label>
              <input 
              type="date" 
              class="form-control fecha-emit" 
              name="date" 
              id="date" 
              v-model="cita.date"
              @change="emitirFecha()"
              >
            </div>
              
            <div class="col-sm-6">
              <div class="form-group">                            
                <label for="">Horario del profesional</label>
                <select class="form-control" name="schedule_id" id="schedule_id" v-model="cita.schedule_id">
                  <option value="" disabled selected>Seleccione Horario</option>
                  <option 
                  v-for="hora in horas" :key="hora.id" 
                  v-if="hora" 
                  :value="hora.id"
                  >
                  {{ hora.check_time }} - {{ hora.departure_date }}
                  </option>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group row">
              <div class="col-sm-6">
              <div class="form-group">
                <label for="">Clasificación de Consulta</label>
                <select 
                class="form-control" 
                name="clasification" 
                id="clasification" 
                v-model="cita.clasification"
                @change="dynamicPrice()"
                >
                  <option value="1">Psiquiatrica</option>
                  <option value="2">Psicológica</option>
                  <option value="3">Certificado</option>
                  <option value="4">Kurame</option>
                </select>
              </div>
            </div>

            <!-- <div class="col-sm-6">
              <div class="form-group">
                <label for="">Condicion de Paciente</label>
                <select class="form-control" name="patient_condition" id="patient_condition" v-model="cita.patient_condition">
                  <option value="1">Paciente inicial</option>
                  <option value="2">Paciente repitente</option>
                </select>
              </div>
            </div> -->
            <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Tipo</label>
                  <select 
                  class="form-control" 
                  name="type" 
                  id="type" 
                  v-model="cita.type"
                  @change="dynamicPrice()"
                  >
                    <option value="1">Terapia inicial niño / adolescente</option>
                    <option value="2">Terapia inicial adulto</option>
                    <option value="3">Terapia inicial pareja</option>
                    <option value="4">Terapia inicial familiar</option>
                    <option value="5">Terapia continua niño / adolescente</option>
                    <option value="6">Terapia continua adulto</option>
                    <option value="7">Terapia continua pareja</option>
                    <option value="8">Terapia continua familiar</option>
                    <option value="9">Orientación vocacional</option>
                    <option value="10">Sucamec inicial</option>
                    <option value="11">Sucamec renovación</option>
                    <option value="12">Kurame</option>
                  </select>
                </div>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-6">
              <div class="form-group">
                  <label for="">Modo</label>
                  <select 
                  class="form-control" 
                  name="mode" 
                  id="mode" 
                  v-model="cita.mode"
                  @change="dynamicPrice()"
                  >
                    <option value="1" selected>Presencial</option>
                    <option value="2">Virtual</option>
                  </select>
              </div>
            </div>
            
            <div class="col-sm-6">
              <div class="form-group">
                  <label for="">Tipo de Monto</label>
                  <select 
                  class="form-control" 
                  name="mode" 
                  id="type_amount" 
                  v-model="cita.type_amount"
                  @change="dynamicPrice()"
                  >
                    <option value="1" selected>Nuevo</option>
                    <option value="2">Antiguo</option>
                  </select>
              </div>
            </div>  

            
          </div>

          <div class="form-group row">
            <div class="col-sm-6">
              <label for="name">Recomendación</label>
              <input type="text" class="form-control" name="recomendation" id="recomendation" v-model="cita.recomendation">
            </div>   

            <div class="col-sm-6" v-if="cita.mode != '1'">
              <label for="name">Link</label>
              <input type="text" class="form-control" name="link" id="link" v-model="cita.link">
            </div>        
          </div>
					<div class="row">
						<div class="col-sm-6">
              <p class="lead"><small>Precio de consulta</small>	S/ {{ parseFloat(cita.price).toFixed(2) }}</p>
              <!-- <input type="text" class="form-control" name="price" id="price" v-model="cita.price"> -->
            </div>
					</div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Registrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import { dateNow } from '../../../../helpers/Time.js'
import alertify from 'alertifyjs'

export default {
  name: "form-prof",

  data(){
    return{
      switchReciec: 1,
      patientNew: false,
      priceCite: [
        [ //--- Psiquitrica
          [200, 170, 200, 200, 160, 120, 200, 200, null, null, null], // --- Presencial
          [200, 170, 150, 150, 120, 90, 120, 120, null, null, null] // --- Virtual 
        ],
        [ //--- Psicologica
          [100, 90, 150, 150, 90, 80, 150, 150, 200, null, null ], // --- Presencial
          [60, 60, 100, 100, 50, 50, 100, 100, null, null, null] // --- Virtual 
        ],
        [ // --- Certificado
          [null, null, null, null, null, null, null, null, null, 190, 170, null], // --- Presencial
          [null, null, null, null, null, null, null, null, null, null, null] // --- Virtual 
        ],
        [ //--- Kurame
          [null, null, null, null, null, null, null, null, null, null, null, 600], // --- Presencial
          [null, null, null, null, null, null, null, null, null, null, null] // --- Virtual 
        ],
      ],
      priceCiteOld: [
        [ //--- Psiquitrica
          //[190, 160, 190, 190, 150, 110, 200, 200, null, null, null], // --- Presencial
          [666, 555, 444, 333, 222, 111, 999, 888, null, null, null], // --- Presencial
          [200, 170, 150, 150, 120, 90, 120, 120, null, null, null] // --- Virtual 
        ],
        [ //--- Psicologica
          [70, 70, 120, 120, 60, 60, 120, 120, 200, null, null ], // --- Presencial
          [60, 60, 100, 100, 50, 50, 100, 100, null, null, null] // --- Virtual 
        ],
        [ // --- Certificado
          [null, null, null, null, null, null, null, null, null, 190, 170, null], // --- Presencial
          [null, null, null, null, null, null, null, null, null, null, null] // --- Virtual 
        ],
        [ //--- Kurame
          [null, null, null, null, null, null, null, null, null, null, null, 600], // --- Presencial
          [null, null, null, null, null, null, null, null, null, null, null] // --- Virtual 
        ],
      ],
      cita:{
        phone:'',
        dni:'',
        name:'',
        email:'',
        address:'',
        department:12,
        province:103,
        district:1006,
        birth_date:'',
        occupation:'',
        marital_status:1,
        instruction_degree:'',
        relative_name:'',
        relatine_phone:'',
        kinship:'',
        professional_id: '',
        schedule_id:'',
        date: dateNow(),
        clasification:'',
        price:0,
        type:'',
        patient_condition:'',
        recomendation:'',
        mode: '1',
        voucher:'',
        link:'',
        bank:'',
        pay_status:'1',
        status:'',
        type_amount:1,
				type_dni:1
      },
			ubigeo: {departamentos:[], provincias:[], distritos:[]},
			provincias:[], distritos:[],
      token:'087d16c0688f5150268342d085a55d54b5064c7649596011f03b35b935899a50',          
      horario:[],
    }
  },
   
  methods: {
    typePrice(e){
      //alert(e.target.value)
    },
    async insertar(e){ 
      e.preventDefault()
      const config = {
        headers: { 'content-type': 'multipart/form-data' }
      }
			if( this.cita.type_dni==1 && (this.cita.dni =='' || this.cita.dni.length<8) ){
				alertify.notify('Todo paciente debe tener un DNI válido', 'danger', 10);
			}else if( this.cita.type_dni!=1 && (this.cita.dni =='' || this.cita.dni.length<8) ){
				alertify.notify('Todo extranjero debe tener un documento de identidad válido', 'danger', 10);
			}else{
				let formData = new FormData();
				formData.append('dni', this.cita.dni);
				formData.append('phone', this.cita.phone);
				formData.append('name', this.cita.name || 'Sin nombre' );
				formData.append('email',this.cita.email);
				formData.append('address', this.cita.address);
				formData.append('department', this.cita.department);
				formData.append('province', this.cita.province);
				formData.append('district', this.cita.district);
				formData.append('birth_date', this.cita.birth_date);
				formData.append('occupation', this.cita.occupation);
				formData.append('marital_status', this.cita.marital_status);
				formData.append('instruction_degree', this.cita.instruction_degree);
				formData.append('professional_id', this.cita.professional_id);
				formData.append('schedule_id', this.cita.schedule_id);
				formData.append('date', this.cita.date);
				formData.append('clasification', this.cita.clasification);
				formData.append('price', this.cita.price);
				formData.append('type', this.cita.type);
				formData.append('patient_condition', this.cita.patient_condition);
				formData.append('recomendation', this.cita.recomendation);
				formData.append('mode', this.cita.mode);
				formData.append('link', this.cita.link);
				formData.append('type_dni', this.cita.type_dni);
				await this.axios.post('/api/appointment', formData, config)
				.then(response => {
					console.log(response.data)
					this.closeModal()
					this.$swal('Cita inserdata con éxito')
					this.$parent.listar()  
					this.$emit('actualizarArray', true)              
					this.clearModal()
				})
				.catch(error => {
						console.log(error)
				})
			}
    },

    prevenirEvent() {
      if (event.key === 'Enter' || event.code === 13) {
        event.preventDefault();
        this.reniec();
      }
    },

    clearModal(){
			this.cita.phone= '';
			this.cita.dni= '';
			this.cita.name= '';
			this.cita.address= '';
			this.cita.email='';
			this.cita.department= 12;
			this.cita.province= 103;
			this.cita.district= 1006;
			this.cita.birth_date= '';
			this.cita.occupation= '';
			this.cita.instruction_degree= '';
			this.cita.marital_status= 1;
			this.cita.relative_name= '';
			this.cita.relatine_phone= '';
			this.cita.kinship= '';
			this.cita.professional_id='';
			this.cita.schedule_id='';
			this.cita.date= dateNow();
			this.cita.clasification='';
			this.cita.price=0;
			this.cita.type= '';
			this.cita.patient_condition= '';
			this.cita.recomendation= '';
			this.cita.mode= '';
			this.cita.voucher= '';
			this.cita.link= '';
			this.cita.bank= '';
			this.cita.pay_status= '';
      this.cita.status= '';
			this.cita.type_amount=1;

    },

    closeModal() {
      document.getElementById('closeModal').click();
    },

    reniec(){ 
      if (this.switchReciec === 0) return;
      this.switchReciec = 0;
      this.limpiarInputs(false)
      //event.target.closest('.btnReniec').classList.replace('btn-info', 'btn-danger')

      this.$swal.fire({
        title: 'Buscando paciente',
        timer: 2500,
        timerProgressBar: true,
        didOpen: () => {
          timerProgressBar: true,
          this.$swal.showLoading()
        },
      })

      this.axios.get("/api/buscar/"+this.cita.dni)
      .then(res => {
        if (res.data.patient == null) { //Buscar en reniec
					if(this.cita.type_dni==1){
						window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
						this.axios.get(`https://apiperu.dev/api/dni/${this.cita.dni}`)
						.then(response => {
							console.log(response.data)
							this.cita.name = response.data.message || `${response.data.data.apellido_paterno} ${response.data.data.apellido_materno} ${response.data.data.nombres}`;
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
					}
        }else{ //encontro en la DB
          this.$swal.fire({
            title: 'Buscando paciente',
            timer: 10,
          })

          if(res.data.patient.faults != 0){
            this.$swal.fire({
            title: 'Atención, este paciente tiene '+ res.data.patient.faults + ' faltas'
            })
          }

          this.cita.name = res.data.patient.name;
          this.cita.phone = res.data.patient.phone;
          this.cita.email = res.data.patient.email;
          this.cita.birth_date = res.data.patient.birth_date;
          this.cita.marital_status = res.data.patient.marital_status;
          this.cita.instruction_degree = res.data.patient.instruction_degree;
          this.cita.occupation = res.data.patient.occupation;
          this.cita.address = res.data.patient.address.address;
          this.cita.district = res.data.patient.address.district;
          this.cita.province = res.data.patient.address.province;
          this.cita.department = res.data.patient.address.department;
          this.patientNew = true;
					this.moverProvincias(false)
					this.moverDistritos()
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
  

    emitirProf () {
      this.$emit("emitIdProf", event.target.value);
			let profesion = this.profes.filter(x => x.id == event.target.value)[0].profession;

			switch (profesion) {
				case 'Psicólogo': this.cita.clasification = 2; break;
				case 'Psiquiatra': this.cita.clasification = 1; break;
				default: this.cita.clasification = ''; break;
			}
    },

    emitirFecha () {
      this.$emit("emitDate", event.target.value);
    },

    limpiarInputs (value) {
      value ? this.cita.dni = '' : false;
      value ? this.patientNew = false : false

      this.cita.phone = '';
      this.cita.name = '';
      this.cita.email = '';
      this.cita.address = '';
      this.cita.department = 12;
      this.cita.province = 103;
      this.cita.district = 1006;
      this.cita.birth_date = '';
      this.cita.occupation = '';
      this.cita.marital_status = '';
      this.cita.instruction_degree = '';
      this.cita.relative_name = '';
      this.cita.relatine_phone = '';
      this.cita.kinship = '';
      this.cita.professional_id =  '';
      this.cita.schedule_id = '';
      this.cita.date = dateNow();
      this.cita.clasification = '';
      this.cita.price = 0;
      this.cita.type = '';
      this.cita.patient_condition = '';
      this.cita.recomendation = '';
			this.cita.type_amount=1;

      
      // value ? this.cita.mode = '' : false;
      
      this.cita.voucher = '';
      this.cita.link = '';
      this.cita.bank = '';
      this.cita.pay_status = '';
      this.cita.status = '';
    },

    dynamicPrice () {
      if (document.getElementById('clasification').value &&
        document.getElementById('type').value &&
        document.getElementById('mode').value && document.getElementById('type_amount').value) {
          
        let casificationValue = parseInt(document.getElementById('clasification').value) - 1,
          typeValue = parseInt(document.getElementById('type').value) - 1,
          modeValue = parseInt(document.getElementById('mode').value) - 1,
          price = document.getElementById('price');
          if(this.cita.type_amount == 1){
            this.cita.price = this.priceCite[casificationValue][modeValue][typeValue]
          }else{
            this.cita.price = this.priceCiteOld[casificationValue][modeValue][typeValue]
          }
        
      }
    },
		async listarDepartamentos(){
      await this.axios.get('/api/departamentos')
      .then(response => {
				this.ubigeo.departamentos = response.data['departamentos'];
				this.ubigeo.provincias = response.data['provincias'];
				this.ubigeo.distritos = response.data['distritos'];

				this.provincias = this.ubigeo.provincias.filter(provincia=> provincia.idDepa == 12)
				this.distritos = this.ubigeo.distritos.filter(distrito=> distrito.idProv == 103)

				this.cita.department = 12;
				this.cita.province = 103;
				this.cita.district = 1006;
      })
    },
		moverProvincias(borrar){
			let idDepa= this.cita.department;
			this.provincias = this.ubigeo.provincias.filter(provincia=> provincia.idDepa == idDepa)
			if(borrar) this.cita.district=-1;
		},
		moverDistritos(){
			let idProv= this.cita.province;
			this.distritos = this.ubigeo.distritos.filter(distrito=> distrito.idProv == idProv)
		}
  },
	created (){
		this.listarDepartamentos();
	},
	updated(){
		//this.cita.department = 12;
		
	},

  props:{
    profes:Array,
    horas:Array
  },

  computed:{
    filtro(){
      if(this.cita.professional_id){
        return this.horas.filter(hora=> hora.professional_id == cita.professional_id)         
      }
    }
  }
}
</script>

<style>
.ajs-message{border-radius: 5px!important;}
.ajs-success { background-color: rgb(33, 201, 89)!important; }
.ajs-danger { background-color: rgb(232, 27, 0)!important; color:white!important; }
</style>