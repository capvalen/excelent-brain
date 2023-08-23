<template>
<div class="modal fade " id="modalNuevaCita" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
			<h5 class="modal-title" id="addCitaModalLabel">Registrar Nueva Cita</h5>

			<button type="button" id="closeModalNuevaCita" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
			</div>
			<div class="modal-body">
				<form class="user" @submit="insertar" @keydown="prevenirEvent">
				 
					<p class="mb-2 lead"><strong>Datos Personales</strong></p>

					<div class="form-group row">
					 <div class="col-sm-6">
							<label for="name">Tipo de documento</label>
							<select class="form-select" id="type_dni" v-model="cita.type_dni">
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
								<input v-if="cita.type_dni==1" type="text" class="form-control w-75 mr-1" name="dni" id="dni" v-model="cita.dni" placeholder="DNI del paciente" autocomplete="off">
								<input v-else type="text" class="form-control w-75 mr-1" name="dni" id="dni" v-model="cita.dni" placeholder="Código de extranjería" autocomplete="off">
								<a @click="reniec" class="btnReniec btn btn-outline-primary"><i class="fas fa-search"></i></a>
							</div>  
						</div>

						<div class="col-sm-6">
							<label for="name">Celular</label>
							<input type="text" class="form-control" id="phone" v-model="cita.phone" placeholder="Celular del paciente" @keypress="limitarCel($event)" autocomplete="off">
						</div>           
					</div>

					<div class="form-group">
							<label for="name">Apellidos y nombres</label>
							<input  type="text" class="form-control" id="name" v-model="cita.name" placeholder="Nombre del paciente" autocomplete="off">
							<!-- <input v-else type="text" class="form-control"  name="name" id="name" v-model="cita.name" placeholder="Nombre del paciente"> -->
					</div>
					
					
					<div class="row">
						<div class="col-sm-6 my-2">
							<div class=" form-switch">
								<input class="form-check-input" type="checkbox" role="switch" id="fleMasBasicos" v-model="masBasicos" >
								<label class="form-check-label" for="fleMasBasicos">
									<span v-if="!masBasicos"><i class="fa-solid fa-caret-down"></i> Agregar más datos básicos</span>
									<span v-else><i class="fa-solid fa-caret-up"></i> Ocultar datos básicos</span>
								</label>
							</div>
						</div>
					</div>

					<div class="form-group row" v-show="masBasicos">
						<div class="form-group row">
							<div class="col-sm-12">
								<label for="name">Correo electrónico</label>
								<input type="text" class="form-control" name="address" id="address" v-model="cita.email" placeholder="Correo electrónico" autocomplete="off">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-12">
								<label for="name">Dirección</label>
								<input type="text" class="form-control" name="address" id="address" v-model="cita.address" placeholder="Dirección del paciente" autocomplete="off">
							</div>
						</div>
						<div class="col-sm-4">
								<label for="name">Departamento</label>
								<select v-model="cita.department" class="form-select" id="department" @change="moverProvincias(true)">
									<option v-for="departamento in ubigeo.departamentos" :value="departamento.idDepa">{{ departamento.departamento }}</option>
								</select>
						</div>
						<div class="col-sm-4">
								<label for="name">Provincia</label>
								<select v-model="cita.province" class="form-select" id="provincia" @change="moverDistritos()">
									<option v-for="provincia in provincias" :value="provincia.idProv">{{ provincia.provincia }}</option>
								</select>
						</div>
						<div class="col-sm-4">
								<label for="name">Distrito</label>
								<select v-model="cita.district" class="form-select" id="distrito">
									<option v-for="distrito in distritos" :value="distrito.idDist">{{ distrito.distrito }}</option>
								</select>
						</div>
					</div>
					
					<div class="form-group row" v-show="masBasicos">
						<div class="col-sm-4">
								<label for="name">Fecha de nacimiento</label>
								<input type="date" class="form-control" name="birth_date" id="birth_date" v-model="cita.birth_date">
						</div>
						<div class="col-sm-4">
								<label for="marital_status">Estado Civil</label>
								<select class="form-select" name="marital_status" id="marital_status" v-model="cita.marital_status">
									<option value="2">Casado</option>
									<option value="5">Conviviente</option>
									<option value="4">Divorciado</option>
									<option value="1">Soltero</option>
									<option value="3">Viudo</option>
								</select>
						</div>
						 <div class="col-sm-4">
								<label for="">Grado de instrucción</label>
						 		<select class="form-select" name="instruction_degree" id="instruction_degree" v-model="cita.instruction_degree">
									<option value="1">Inicial</option>
									<option value="2">Primaria</option>
									<option value="3">Secundaria</option>
									<option value="4">Superior</option>
									<option value="5">Técnico</option>
									<option value="6">Sin instrucción</option>
								</select>
						</div>
					</div>

					<div class="form-group row" v-show="masBasicos">
						<div class="col-sm-6">
								<label for="name">Ocupación</label>
								<input type="text" class="form-control" name="occupation" id="occupation" v-model="cita.occupation"  placeholder="Ocupación del paciente" autocomplete="off"> 
						</div>
						<div class="col-sm-6">
							<label for="name">Género</label>
							<select class="form-select" id="sexo" v-model="cita.gender">
								<option value="2">Sin definir</option>
								<option value="0">Femenino</option>
								<option value="1">Masculino</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 my-2">
							<div class=" form-switch">
								<input class="form-check-input" type="checkbox" role="switch" id="fleMasEmergencia" v-model="masEmergencia" >
								<label class="form-check-label" for="fleMasEmergencia">
									<span v-if="!masEmergencia"><i class="fa-solid fa-caret-down"></i> Agregar datos de emergencia</span>
									<span v-else><i class="fa-solid fa-caret-up"></i> Ocultar datos de emergencia</span>
								</label>
							</div>
						</div>
					</div>
					<div class="form-group row" v-show="masEmergencia">
						<p class="mb-2 lead"><strong>Datos de Contacto de Emergencia</strong></p>
						<div class="col-sm-4">
							<label for="name">Nombre</label>
							<input type="text" class="form-control" name="contacto" id="contacto" v-model="cita.contacto"  placeholder="Contacto" autocomplete="off">
						</div>
						<div class="col-sm-4">
							<label for="name">Celular emergencia</label>
							<input type="text" class="form-control" name="contacto_celular" id="contacto_celular" v-model="cita.contacto_celular"  placeholder="Celular" autocomplete="off">
						</div>
						<div class="col-sm-4">
							<label for="name">Parentesco</label>
							<input type="text" class="form-control" name="parentezco" id="parentezco" v-model="cita.parentezco"  placeholder="Parentesco" autocomplete="off">
						</div>
					</div>
					<p class="mb-2 lead"><strong>Datos de la Nueva Cita</strong></p>
					<div class="row row-cols-2">
						<div class="col">
							<p class="mb-0"><strong>Profesional:</strong> {{ profesionalElegido.name }}</p>
							<p class="mb-0"><strong>Profesión:</strong> {{ profesionalElegido.profession }}</p>
							<p class="mb-0"><strong>Última atención:</strong> 
								<span title="Última atención" v-if="cita.etiqueta==''" class="badge rounded-pill text-bg-dark"><i class="fa-solid fa-asterisk"></i> Sin registro previo</span>
								<span title="Última atención" v-if="cita.etiqueta" class="badge rounded-pill text-bg-primary"><i class="fa-solid fa-genderless"></i> {{cita.etiqueta}}</span>
							</p>
						</div>
						<div class="col">
							<p class="mb-0"><strong>Fecha:</strong> {{ fechaElegida }}</p>							
							<p class="mb-0"><strong>Horario:</strong> {{ horaLatam1(horaElegida.check_time) }} - {{ horaLatam2(horaElegida.departure_date) }}</p>							
							<p class="mb-0"><strong>Status anterior:</strong> {{ nombreStatus(cita.prev_status) }} </p>							

						</div>
					</div>

					<div class="alert alert-danger agrandar m-4" role="alert" v-if="alertaDeudas" >
						<i class="fa-regular fa-comment-dots"></i> <strong>Alto!</strong> <span v-html="mensajeDeudas"></span>
					</div>


			
					<div class="form-group row mt-3">
						<div class="col-sm-4">
							<label for="">Clasificación de Consulta</label>
							<select 
							class="form-select" 
							name="clasification" 
							id="clasification" 
							v-model="cita.clasification"
							@change="precioDinamico(); cita.type=''"
							>
								<option value="3">Certificado</option>
								<option value="1" v-if="profesionalElegido.idProfesion=='1'" selected >Psiquiatrica</option>
								<option value="2" v-if="profesionalElegido.idProfesion=='2'" selected >Psicológica</option>
								<option value="4">Kurame</option>
							</select>
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
						<div class="col-sm-4">
							<label for="">Tipo de servicio</label>
							<select  class="form-select" name="type" id="sltServicio" v-model="cita.type" @change="precioDinamico()">
								<option v-for="precio in precios" :value="precio.id" v-if="precio.idClasificacion==cita.clasification ">{{ precio.descripcion }}</option>
							</select>
						</div>
						<div class="col-sm-4">
							<label for="">Status</label>
							<select  class="form-select" name="type" id="sltServicio" v-model="cita.new_status" >
								<option v-for="statu in status" :value="statu.id" >{{ statu.stat }}</option>
							</select>
						</div>
					</div>

			
					
					<div class="form-group row">
						<div class="col-sm-6 my-2">
							<div class=" form-switch">
								<input class="form-check-input" type="checkbox" role="switch" id="flePrecio" v-model="precioNuevo" @change="precioDinamico()">
								<label class="form-check-label" for="flePrecio">
									<span v-if="precioNuevo"><i class="fa-solid fa-pizza-slice"></i> Precio de nuevo cliente</span>
									<span v-else><i class="fa-solid fa-champagne-glasses"></i> Precio de cliente continuante</span>
								</label>
							</div>
						</div>
						<div class="col-sm-6 my-2">
							<div class=" form-switch">
								<input class="form-check-input" type="checkbox" role="switch" id="flePresencial" v-model="esPresencial" >
								<label class="form-check-label" for="flePresencial">
									<span v-if="esPresencial"><i class="far fa-user"></i> Reunión presencial</span>
									<span v-else><i class="fas fa-desktop"></i> Reunión virtual</span>
								</label>
							</div>
						</div>
						<div class="col-sm-4 my-2">
							<div class=" form-switch">
								<input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" v-model="nosrecomienda" >
								<label class="form-check-label" for="flexSwitchCheckDefault">¿Nos recomendaron?</label>
							</div>
						</div>
					
						<div class="col-sm-12 my-1" v-if="nosrecomienda">
							<input type="text" class="form-control" name="recomendation" id="recomendation" v-model="cita.recomendation" placeholder="¿De dónde nos recomienda?" autocomplete="off">
						</div>   

						<div class="col-sm-12 my-1" v-if="!esPresencial">
							<input type="text" class="form-control" name="link" id="link" v-model="cita.link" placeholder="Ingrese el link de la reunión virtual" autocomplete="off">
						</div>        
					</div>
					<div class="row d-flex align-content-end">
						<div class="col-sm-6">
							<div class=" form-switch">
								<input class="form-check-input" type="checkbox" role="switch" id="fleAdelanto" v-model="tieneAdelanto" @change="precioDinamico(); descuentoAdelanto=0;">
								<label class="form-check-label" for="fleAdelanto">
									<span v-if="!tieneAdelanto"><i class="fa-solid fa-percent"></i> ¿Tiene adelanto?</span>
									<span v-else class="text-danger"><i class="fa-regular fa-money-bill-1"></i> No posee adelanto</span>
								</label>
								<div v-if="tieneAdelanto">
									<label class="mb-0 mt-2" for="">Adelanto en S/:</label>
									<input type="number" min="0" step="1" class="form-control" v-model="descuentoAdelanto" @keyup="precioDinamico()">
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class=" form-switch">
								<input class="form-check-input" type="checkbox" role="switch" id="fleRebaja" v-model="tieneRebaja" @change="precioDinamico(); descuentoRebaja=0;">
								<label class="form-check-label" for="fleRebaja">
									<span v-if="!tieneRebaja"><i class="fa-solid fa-percent"></i> ¿Tiene rebaja?</span>
									<span v-else class="text-danger"><i class="fa-regular fa-money-bill-1"></i> Sin rebaja</span>
								</label>
								<div v-if="tieneRebaja">
									<label class="mb-0 mt-2" for="">Rebaja a aplicar en S/</label>
									<input type="number" min="0" step="0.5" class="form-control" v-model="descuentoRebaja" @change="precioDinamico()">
									<label class="mb-0 mt-2" for="">Motivo de la rebaja</label>
									<input type="text" class="form-control my-2" placeholder="Ingresa una razón para la rebaja" v-model="razonRebaja">
								</div>
							</div>
						</div>
						<div class="col-sm-12 text-center ">
							<p class="lead"><small>Precio a cobrar</small>	S/ {{ parseFloat(cita.price).toFixed(2) }}</p>
							<!-- <input type="text" class="form-control" name="price" id="price" v-model="cita.price"> -->
						</div>
					</div>

					<div class="modal-footer border-0">
						<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
						<button type="submit" class="btn btn-outline-primary"><i class="fas fa-save"></i> Registrar cita</button>
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
import moment, { relativeTimeThreshold } from 'moment'

export default {
	name: "ModalNuevaCita",
	props:{ profesionalElegido: null, horaElegida: null , idUsuario:null, fechaElegida:null },
	data(){
		return{
			precios: [], nosrecomienda:false, precioNuevo:true, esPresencial: true, masBasicos:false, masEmergencia:false, tieneDescuento:false, descuentoRebaja:0, tieneRebaja:false, razonPorcentaje:'', razonRebaja:'',
			switchReciec: 1, tieneAdelanto:false, descuentoAdelanto:0,
			status:[{id:4, stat:'Ambulatorio'},{id:3, stat:'Clínica de día'},{id:2, stat:'Kurame'},{id:1, stat:'Ninguno'},], //sacado de la DB:tbl status
			patientNew: false, alertaDeudas:false, mensajeDeudas:'',
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
				instruction_degree: 6,
				relative_name:'',
				relatine_phone:'',
				kinship:'',
				professional_id: '',
				schedule_id:'',
				date: dateNow(),
				clasification:'',
				gender:2,
				price:0,
				type:'',
				patient_condition:'',
				recomendation:'',
				mode: '1',
				voucher:'',
				link:'',
				bank:'',
				pay_status:'1',
				status:'', new_status:1, prev_status:1,
				type_amount:1,
				type_dni:1,
				contacto: '', contacto_celular: '', parentezco:'', adelanto:0
			},
			ubigeo: {departamentos:[], provincias:[], distritos:[]},
			provincias:[], distritos:[],
			token:'087d16c0688f5150268342d085a55d54b5064c7649596011f03b35b935899a50',          
			horario:[], descuentoPorcentaje:0
		}
	},
	 
	methods: {
		horaLatam1(horita){ return moment(horita, 'HH:mm:ss').format('hh:mm') },
		horaLatam2(horita){ return moment(horita, 'HH:mm:ss').format('hh:mm a') },
		precioDinamico(){
			this.cita.price = 0;
			if(this.cita.type =='') this.cita.price = 0;
			else{
				let precio = this.precios.find(p=> p.id == this.cita.type)
				let descuentoPorcentual;

				if( this.precioNuevo ) precio = precio.nuevos
				else precio = precio.continuos

				if( this.tieneAdelanto )
					if( parseInt(this.descuentoAdelanto) <= 0 || this.descuentoAdelanto=='' ) this.descuentoAdelanto=0
					else precio = precio - parseFloat(this.descuentoAdelanto);

				if( this.tieneDescuento ){
					if( parseInt(this.descuentoPorcentaje) <= 0 ) descuentoPorcentual=0
					else descuentoPorcentual = parseFloat((precio * 1/ parseInt(this.descuentoPorcentaje)).toFixed(1));				
					precio = precio - descuentoPorcentual;
				}

				if( this.tieneRebaja )
					precio = precio - parseFloat(this.descuentoRebaja);
				
				this.cita.price = precio;
			}
		},
		async insertar(e){ 
			e.preventDefault()
			const config = {
				headers: { 'content-type': 'multipart/form-data' }
			}
			if( this.cita.type_dni==1 && (this.cita.dni =='' || this.cita.dni.length<8) )
				alertify.notify('Todo paciente debe tener un DNI válido', 'danger', 10);
			else if( this.cita.type_dni!=1 && (this.cita.dni =='' || this.cita.dni.length<8) )
				alertify.notify('Todo extranjero debe tener un documento de identidad válido', 'danger', 10);
			else if( !this.cita.type)
				alertify.notify('Debe seleccionar un tipo de servicio', 'danger', 10);
			else if( this.tieneDescuento && this.razonPorcentaje=='' ) alertify.notify('Tiene que rellenarse un motivo de descuento', 'danger', 10)
			else if( this.tieneRebaja && this.razonRebaja=='' ) alertify.notify('Tiene que rellenarse un motivo de rebaja', 'danger', 10)
			else{
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
				formData.append('gender', parseInt(this.cita.gender));
				formData.append('occupation', this.cita.occupation);
				formData.append('marital_status', this.cita.marital_status);
				formData.append('instruction_degree', this.cita.instruction_degree);
				formData.append('professional_id', this.cita.professional_id);
				formData.append('schedule_id', this.horaElegida.id);
				formData.append('date', this.fechaElegida);
				formData.append('clasification', this.cita.clasification);
				formData.append('price', this.cita.price);
				formData.append('type', this.cita.type); //nueva lista de servicios
				//formData.append('patient_condition', this.cita.patient_condition); //El sistema evalúa la condición: nuevo o continuo, no es necesario pasar
				formData.append('recomendation', this.cita.recomendation);
				formData.append('mode', (this.esPresencial) ? 1: 2 );
				formData.append('link', this.cita.link);
				formData.append('type_dni', this.cita.type_dni);
				formData.append('contacto', this.cita.contacto);
				formData.append('contacto_celular', this.cita.contacto_celular);
				formData.append('parentezco', this.cita.parentezco);
				formData.append('continuo', this.precioNuevo ? '1': 2 ); //this.cita.type_amount
				formData.append('user_id', this.idUsuario);
				formData.append('formato_nuevo', 1);
				formData.append('etiqueta', $('#sltServicio option:selected').text());
				formData.append('rebaja', this.descuentoRebaja);
				formData.append('motivoRebaja', this.razonRebaja);
				formData.append('descuento', this.descuentoPorcentaje);
				formData.append('motivoDescuento', this.razonPorcentaje);
				formData.append('new_status', this.cita.new_status);
				formData.append('adelanto', this.descuentoAdelanto);
				await this.axios.post('/api/appointment', formData, config)
				.then(response => { //Trabaja en api -> modelo>store()
					console.log(response.data)
					this.closeModal();
					this.$emit('actualizarListadoCitas', true)
					//console.log(response.data.cita.id)
					this.$swal('Cita inserdata con éxito')
					//this.$parent.listar()
					this.clearModal()
				})
				.catch(error => {
						console.log(error)
				})
			}
		},
		closeModal(){
      document.getElementById('closeModalNuevaCita').click();
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
			this.cita.instruction_degree= 6;
			this.cita.gender= 2;
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
			this.contacto= ''; this.contacto_celular= ''; this.parentezco='';
			this.etiqueta =''; this.descuentoAdelanto = 0; this.descuentoPorcentaje=0; this.descuentoPorcentual=0;
			this.tieneAdelanto=false; this.tieneDescuento=false; this.tieneRebaja=false;
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
					console.log('datos del paciente',res.data);
					let sumaDeudas = 0;
					const cantDeudas =  res.data.deudas.length;
					if( cantDeudas >0){
						for(let i=0; i< cantDeudas ; i++ ){
							sumaDeudas+= res.data.deudas[i].monto
						}
						this.alertaDeudas = true;
						this.mensajeDeudas = `El paciente tiene <strong>${(cantDeudas==1) ? '1 deuda': cantDeudas+' deudas'}</strong> de <strong>${moment(res.data.deudas[0].fecha).fromNow()}</strong> por un total de <strong>S/ ${parseFloat(sumaDeudas).toFixed(2)}</strong>`;
						
					}else this.alertaDeudas=false;

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
					this.cita.gender = typeof parseInt(res.data.patient.gender) === 'number' && res.data.patient.gender !==null ? res.data.patient.gender : 2;
					this.cita.occupation = res.data.patient.occupation;
					this.cita.address = res.data.patient.address.address;
					this.cita.department = res.data.patient.address.department;
					this.cita.province = res.data.patient.address.province;
					this.cita.district = res.data.patient.address.district;
					this.cita.contacto = res.data.relacion.name =='null' ? '' : res.data.relacion.name;
					this.cita.contacto_celular = res.data.relacion.phone =='null' ? '' : res.data.relacion.phone;
					this.cita.parentezco = res.data.relacion.kinship =='null' ? '' : res.data.relacion.kinship;
					this.cita.etiqueta = res.data.patient.etiqueta;
					this.cita.deudas = res.data.patient.deudas;
					this.cita.prev_status = res.data.patient.new_status;
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

		horaSimple1(horita){ return moment(horita, 'HH:mm:ss').format('h:mm')},
		horaSimple2(horita){ return moment(horita, 'HH:mm:ss').format('h:mm a')},

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
			this.$emit("emitDate", this.cita.professional_id, event.target.value);
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
			this.cita.instruction_degree = 6;
			this.cita.gender = 2;
			this.cita.relative_name = '';
			this.cita.relatine_phone = '';
			this.cita.kinship = '';
			this.cita.prev_status=1;
			this.cita.etiqueta='';
			this.cita.new_status=1
			
			this.contacto= ''; this.contacto_celular= ''; this.parentezco='';

			this.moverProvincias(false)
			this.moverDistritos()
			// value ? this.cita.mode = '' : false;
			
			this.cita.voucher = '';
			this.cita.link = '';
			this.cita.bank = '';
			this.cita.pay_status = '';
			this.cita.status = '';
			this.descuentoAdelanto = 0; this.descuentoPorcentaje=0; this.descuentoPorcentual=0;
			this.tieneAdelanto=false; this.tieneDescuento=false; this.tieneRebaja=false;
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
		async listarPrecios(){
			await this.axios.get('/api/listarPrecios')
			.then( response => this.precios = response.data)
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
				this.moverProvincias(false)
				this.moverDistritos()
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
		},
		limitarCel($event){
			if( this.cita.phone.length>16 ){
				$event.preventDefault();
			}
		},
		nombreStatus(tipo){
			let enc = this.status.find(x=> x.id == tipo)
			//console.log(enc);
			return enc.stat
		}
	},
	created (){
		this.listarPrecios();
		this.listarDepartamentos();
	},
	updated(){
		//this.cita.department = 12;
		
	},
	watch:{
		horaElegida(){
			this.cita.professional_id = this.profesionalElegido.id;
			this.cita.clasification = this.profesionalElegido.idProfesion;
		}
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
.form-switch label{cursor: pointer;}
.alert-danger {
    color: #ffffff;
    background-color: #ff3521;
}
.agrandar{transition: all 0.2s ease-in-out;}
.agrandar:hover{transform: scale(1.05);}
</style>
