<template>
<div class="modal fade " id="modalNuevaCita" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
			<h5 class="modal-title" id="addCitaModalLabel">Registrar Nueva Cita</h5>

			<button type="button" id="closeModalNuevaCita" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
			</div>
			<div class="modal-body">
				<form class="user" @submit="insertar" @keydown="prevenirEvent">
				 
					<p class="mb-2 lead text-success"><strong><i class="far fa-address-card"></i> Datos Personales</strong></p>
					<div class="card">
						<div class="card-body">
							<div class="form-group row">
								<div class="col">
									<label for="name">Tipo de documento <span class="text-danger">*</span></label>
									<select class="form-select" id="type_dni" v-model="cita.type_dni">
										<option value="1">D.N.I.</option>
										<option value="2">Carnet de extranjería</option>
										<option value="3">Pasaporte</option>
									</select>
								</div>
								<div class="col">
									<label for="name">Celular personal <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="phone" v-model="cita.phone" placeholder="" @keypress="limitarCel($event)" autocomplete="off">
								</div>
							</div>
		
							<div class="form-group row">
								<div class="col-4">
									<label v-if="cita.type_dni==1" for="name">D.N.I. <span class="text-danger">*</span></label>
									<label v-else for="name">Doc. Extranjero <span class="text-danger">*</span></label>
									<div class="form-inline">
										<input v-if="cita.type_dni==1" type="text" class="form-control w-75 mr-1" name="dni" id="dni" v-model="cita.dni" placeholder="DNI del paciente" autocomplete="off">
										<input v-else type="text" class="form-control w-75 mr-1" name="dni" id="dni" v-model="cita.dni" placeholder="Código de extranjería" autocomplete="off">
										<a @click="reniec" class="btnReniec btn btn-outline-primary"><i class="fas fa-search"></i></a>
									</div>
								</div>
								<div class="col-4">
									<label for="name">Apellidos <span class="text-danger">*</span></label>
									<input  type="text" class="form-control text-uppercase" id="name" v-model="cita.name" placeholder="" autocomplete="off">
								</div>
								<div class="col-4">
									<label for="name">Nombres <span class="text-danger">*</span></label>
									<input  type="text" class="form-control text-uppercase" id="name" v-model="cita.nombres" placeholder="" autocomplete="off">
								</div>
							</div>
		
							<div class="form-group row">
								<div class="col-sm-4">
										<label for="name">Fecha de nacimiento <span class="text-danger">*</span></label>
										<input type="date" class="form-control" name="birth_date" id="birth_date" v-model="cita.birth_date">
								</div>
								<div class="col-sm-4">
										<label for="marital_status">Estado Civil <span class="text-danger">*</span></label>
										<select class="form-select" name="marital_status" id="marital_status" v-model="cita.marital_status">
											<option value="2">Casado</option>
											<option value="5">Conviviente</option>
											<option value="4">Divorciado</option>
											<option value="1">Soltero</option>
											<option value="3">Viudo</option>
										</select>
								</div>
		
							</div>
		
		
							<div class="form-group row" >
								<div class="col-sm-4">
										<label for="">Grado de instrucción <span class="text-danger">*</span></label>
										<select class="form-select" name="instruction_degree" id="instruction_degree" v-model="cita.instruction_degree">
											<option value="1">Inicial</option>
											<option value="2">Primaria</option>
											<option value="3">Secundaria</option>
											<option value="4">Superior</option>
											<option value="5">Técnico</option>
											<option value="6">Sin instrucción</option>
										</select>
								</div>
								<div class="col">
									<label for="name">Dirección de vivienda <span class="text-danger">*</span></label>
									<input type="text" class="form-control" name="address" id="address" v-model="cita.address" placeholder="" autocomplete="off">
								</div>
							</div>
		
		
							<div class="form-group row">
								<div class="col-sm-4">
										<label for="name">Departamento <span class="text-danger">*</span></label>
										<select v-model="cita.department" class="form-select" id="department" @change="moverProvincias(true)">
											<option v-for="departamento in ubigeo.departamentos" :value="departamento.idDepa">{{ departamento.departamento }}</option>
										</select>
								</div>
								<div class="col-sm-4">
										<label for="name">Provincia <span class="text-danger">*</span></label>
										<select v-model="cita.province" class="form-select" id="provincia" @change="moverDistritos()">
											<option v-for="provincia in provincias" :value="provincia.idProv">{{ provincia.provincia }}</option>
										</select>
								</div>
								<div class="col-sm-4">
										<label for="name">Distrito <span class="text-danger">*</span></label>
										<select v-model="cita.district" class="form-select" id="distrito">
											<option v-for="distrito in distritos" :value="distrito.idDist">{{ distrito.distrito }}</option>
										</select>
								</div>
							</div>
		
							<div class="form-group row">
								<div class="col-sm-4">
										<label for="name">Ocupación <span class="text-danger">*</span></label>
										<input type="text" class="form-control" name="occupation" id="occupation" v-model="cita.occupation"  placeholder="Ocupación del paciente" autocomplete="off">
								</div>
		
								<div class="col-sm-4">
									<label for="name">Género <span class="text-danger">*</span></label>
									<select class="form-select" id="sexo" v-model="cita.gender">
										<option value="2">Sin definir</option>
										<option value="0">Femenino</option>
										<option value="1">Masculino</option>
										<option value="3">LGTB+</option>
									</select>
								</div>
								<div class="col-sm-4">
									<label for="name">Correo electrónico</label>
									<input type="text" class="form-control" name="address" id="address" v-model="cita.email" placeholder="Correo electrónico" autocomplete="off">
								</div>
							</div>
						</div>
					</div>
					
					<p class="my-2 lead text-success"><strong><i class="fas fa-people-arrows"></i> Datos de Contacto de Emergencia</strong></p>
					<div class="card">
						<div class="card-body">
							<div class="form-group row" >
								<div class="col-sm-4">
									<label for="name">Nombre del primer contacto <span class="text-danger">*</span></label>
									<input type="text" class="form-control" name="contacto" id="contacto" v-model="cita.contacto"  placeholder="Contacto principal" autocomplete="off">
								</div>
								<div class="col-sm-4">
									<label for="name">Celular emergencia <span class="text-danger">*</span></label>
									<input type="text" class="form-control" name="contacto_celular" id="contacto_celular" v-model="cita.contacto_celular"  placeholder="Celular" autocomplete="off">
								</div>
								<div class="col-sm-4">
									<label for="name">Parentesco <span class="text-danger">*</span></label>
									<input type="text" class="form-control" name="parentezco" id="parentezco" v-model="cita.parentezco"  placeholder="Parentesco" autocomplete="off">
								</div>
							</div>
							<hr>
							<div class="form-group row" >
								<div class="col-sm-4">
									<label for="name">Nombre del segundo contacto</label>
									<input type="text" class="form-control" name="contacto" id="contacto" v-model="cita.contacto2"  placeholder="Contacto secundario" autocomplete="off">
								</div>
								<div class="col-sm-4">
									<label for="name">Celular emergencia</label>
									<input type="text" class="form-control" name="contacto_celular" id="contacto_celular" v-model="cita.contacto_celular2"  placeholder="Celular" autocomplete="off">
								</div>
								<div class="col-sm-4">
									<label for="name">Parentesco</label>
									<input type="text" class="form-control" name="parentezco" id="parentezco" v-model="cita.parentezco2"  placeholder="Parentesco" autocomplete="off">
								</div>
							</div>
						</div>
					</div>
					<p class="my-2 lead text-success"><strong><i class="fas fa-ticket-alt"></i> Datos de la Nueva Cita</strong></p>
					<div class="card">
						<div class="card-body">
							<div class="row row-cols-2">
								<div class="col">
									<p class="mb-0"><strong>Profesional:</strong> {{ profesionalElegido.name }}</p>
									<p class="mb-0"><strong>Profesión:</strong> {{ profesionalElegido.profession }}</p>
									<p class="mb-0"><strong>Última atención:</strong> 
										<span title="Última atención" v-if="cita.etiqueta==''" class="badge rounded-pill text-bg-dark"><i class="fa-solid fa-asterisk"></i> Sin registro previo</span>
										<span title="Última atención" v-if="cita.etiqueta" class="badge rounded-pill text-bg-primary"><i class="fa-solid fa-genderless"></i> {{cita.etiqueta}}</span>
									</p>
									<p class="mb-0" v-if="cita.membresia"><strong>Membresía activa:</strong> 
										<span title="Última atención" class="badge rounded-pill text-bg-warning"><i class="far fa-star"></i> {{cita.membresia.descripcion}}</span>
									</p>
								
								</div>
								<div class="col">
									<p class="mb-0"><strong>Fecha:</strong> {{ fechaElegida }}</p>							
									<p class="mb-0"><strong>Horario:</strong> {{ horaLatam1(horaElegida.check_time) }} - {{ horaLatam2(horaElegida.departure_date) }}</p>							
									<p class="mb-0"><strong>Status anterior:</strong> {{ nombreStatus(cita.prev_status) }} </p>							

								</div>
							</div>
						</div>
					</div>

					<div class="alert alert-danger agrandar m-4" role="alert" v-if="alertaDeudas" >
						<i class="fa-regular fa-comment-dots"></i> <strong>Alto!</strong> <span v-html="mensajeDeudas"></span>
					</div>


			
					<div class="card my-2">
						<div class="card-body">
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
								<div class="col-sm-4">
									<label for="">Tipo de servicio</label>
									<select  class="form-select" name="type" id="sltServicio" v-model="cita.type" @change="precioDinamico()">
										<option v-for="precio in precios" :value="precio.id" v-if="precio.idClasificacion==cita.clasification && precio.servicio=='1' && precio.id!=48 && precio.id!=49 && precio.activo=='1' ">{{ precio.descripcion}}</option>
										<option v-if="cita.club=='1' && cita.clasification=='1'" value="48">Terapia Club Excelentemente</option>
										<option v-if="cita.club=='1' && cita.clasification=='2'" value="48">Terapia Club Excelentemente</option>
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
										<input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" v-model="nosrecomienda" @change="cita.recomendacion_comentario=''	">
										<label class="form-check-label" for="flexSwitchCheckDefault">Referencia <span class="text-danger">*</span></label>
									</div>
								</div>
							
								<div class="row" v-if="nosrecomienda">
									<div class="col-sm-4 my-1" >
										<select class="form-select text-capitalize" name="" id="" v-model="cita.recomendation" >
											<option value="" selected>Ninguno</option>
											<option class=" text-capitalize" v-for="reco in recomendaciones" :value="reco">{{ reco }}</option>
										</select>
									</div>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="cita.recomendacion_comentario" placeholder="¿Comentario extra sobre la recomendación?">
									</div>
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
											<label class="mt-2" for="">Descripción adicional</label>
											<input type="text" class="form-control mb-2" placeholder="Ingrese la fecha del adelanto" v-model="razonAdelanto">
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
										<div v-if="tieneAdelanto">
											<label class="mb-0 mt-2" for="">Método de pago</label>
											<select class="form-select" id="sltMonedas3" v-model="monedaAdelanto">
												<option v-for="moneda in monedas" :value="moneda.id">{{ moneda.tipo }}</option>
											</select>
										</div>
									</div>
								</div>
						</div>
					</div>
				</div>
				<div class="card my-2">
					<div class="card-body">
						<div class="col-sm-12 text-center ">
							<h1 class="display-6"><small class="fw-light text-secondary">Precio a cobrar</small> S/ {{ parseFloat(cita.price).toFixed(2) }}</h1>
						</div>
					</div>
				</div>

					<div class="modal-footer border-0 justify-content-center" v-if="cita.vivo==1" >
						<button type="button" class="btn btn-lg btn-outline-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
						<button type="submit" class="btn btn-lg btn-outline-primary"><i class="fas fa-save"></i> Registrar cita</button>
					</div>
					<div v-else>
						<p class="text-dark text-end">Restringido porque el paciente esta reportado como fallecido ( <i class="fas fa-cross"></i> )</p>
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
			precios: [], nosrecomienda:true, precioNuevo:true, esPresencial: true, masBasicos:true, masEmergencia:false, tieneDescuento:false, descuentoRebaja:0, tieneRebaja:false, razonPorcentaje:'', razonRebaja:'',
			switchReciec: 1, tieneAdelanto:false, descuentoAdelanto:0, razonAdelanto:'',
			status:[{id:4, stat:'Ambulatorio'},{id:3, stat:'Clínica de día'},{id:2, stat:'Kurame'},{id:1, stat:'Ninguno'},], //sacado de la DB:tbl status
			patientNew: false, alertaDeudas:false, mensajeDeudas:'', recomendaciones:['Facebook', 'Instagram', 'TikTok', 'Linkedin', 'Youtube', 'Spotify', 'TV', 'Amigos o familiares', 'Referencia profesional', 'Publicidad escrita', 'Campañas de salud', 'Convenio', 'Paciente Antiguo', 'Otros Centros de Salud', 'Google Maps / Business', 'Referencia del Establecimiento', 'Sucamec', 'Página Web'],
			cita:{
				phone:'',
				dni:'',
				name:'', nombres:'',
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
				type:'', tipo:-1,
				patient_condition:'',
				recomendation:'', recomendacion_comentario:'',
				mode: '1',
				voucher:'',
				link:'',
				bank:'',
				pay_status:'1',
				status:'', new_status:1, prev_status:1,
				type_amount:1,
				type_dni:1,
				contacto: '', contacto_celular: '', parentezco:'', 
				contacto2: '', contacto_celular2: '', parentezco2:'', 
				adelanto:0, vivo:1
			},
			ubigeo: {departamentos:[], provincias:[], distritos:[]},
			provincias:[], distritos:[],
			token:'087d16c0688f5150268342d085a55d54b5064c7649596011f03b35b935899a50',
			horario:[], descuentoPorcentaje:0, monedas:[], monedaAdelanto:1
		}
	},
	mounted(){
		this.$parent.$on('limpiarDescuentos', this.limpiarInputs(false) );
		this.pedirMonedas()
	},
	 
	methods: {
		horaLatam1(horita){ return moment(horita, 'HH:mm:ss').format('hh:mm') },
		horaLatam2(horita){ return moment(horita, 'HH:mm:ss').format('hh:mm a') },
		precioDinamico(){
			this.cita.price = 0;
			if(this.cita.type =='') this.cita.price = 0;
			else{
				let precioPadre = this.precios.find(p=> p.id == this.cita.type)
				let precio = 0;
				let descuentoPorcentual;

				if( this.precioNuevo ) precio = precioPadre.nuevos
				else precio = precioPadre.continuos

				/* console.log('membresia', this.cita.membresia);
				console.log('tipo', this.cita.membresia['tipo']); */
				if( this.cita.membresia )
					if( this.cita.membresia.tipo==15 ) //Pertenece a la membresía kurame, único en hacer descuento especial
						precio = precioPadre.especialMembresias
				
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
		pedirMonedas(){
			this.axios('/api/listarMonedas')
			.then(resp => this.monedas = resp.data )
		},
		async insertar(e){ 
			e.preventDefault()
			const config = {
				headers: { 'content-type': 'multipart/form-data' }
			}
			if( this.cita.type_dni==1 && (this.cita.dni =='' || this.cita.dni.length<8) )
				alertify.error('Todo paciente debe tener un DNI válido', 10);
			else if( this.cita.type_dni!=1 && (this.cita.dni =='' || this.cita.dni.length<8) )
				alertify.error('Todo extranjero debe tener un documento de identidad válido', 10);
			else if(this.cita.name == '' && this.cita.nombres =='') alertify.error('Debe rellenar apellidos y  nombres', 10);
			else if(this.cita.phone == '') alertify.error('Debe rellenar un celular', 10);
			else if( !this.cita.type) alertify.error('Debe seleccionar un tipo de servicio', 10);
			else if( this.tieneDescuento && this.razonPorcentaje=='' ) alertify.error('Tiene que rellenarse un motivo de descuento', 10)
			else if( this.tieneRebaja && this.razonRebaja=='' ) alertify.error('Tiene que rellenarse un motivo de rebaja', 10)
			else if( this.descuentoAdelanto && this.razonAdelanto=='' ) alertify.error('Tiene que rellenarse una fecha o razón del adelanto', 10)
			else if( this.cita.contacto == '' || this.cita.contacto_celular == '' || this.cita.parentezco=='') alertify.error('Debe rellenar el contacto de emergencia', 10)
			else if( this.cita.recomendation == '') alertify.error('Debe rellenar  la referencia', 10)
			else{
				let formData = new FormData();
				formData.append('dni', this.cita.dni);
				formData.append('phone', this.cita.phone);
				formData.append('name', this.cita.name.toUpperCase() || 'Sin apellidos' );
				formData.append('nombres', this.cita.nombres.toUpperCase() || '' );
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
				formData.append('check_time', this.horaElegida.check_time);
				formData.append('date', this.fechaElegida);
				formData.append('clasification', this.cita.clasification);
				formData.append('price', this.cita.price);
				formData.append('type', this.cita.type); //nueva lista de servicios
				//formData.append('patient_condition', this.cita.patient_condition); //El sistema evalúa la condición: nuevo o continuo, no es necesario pasar
				formData.append('recomendation', this.cita.recomendation);
				formData.append('recomendacion_comentario', this.cita.recomendacion_comentario);
				formData.append('mode', (this.esPresencial) ? 1: 2 );
				formData.append('link', this.cita.link);
				formData.append('type_dni', this.cita.type_dni);
				formData.append('contacto', this.cita.contacto);
				formData.append('contacto_celular', this.cita.contacto_celular);
				formData.append('parentezco', this.cita.parentezco);
				formData.append('contacto2', this.cita.contacto2);
				formData.append('contacto_celular2', this.cita.contacto_celular2);
				formData.append('parentezco2', this.cita.parentezco2);
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
				formData.append('razonAdelanto', this.razonAdelanto);
				formData.append('monedaAdelanto', this.monedaAdelanto);
				await this.axios.post('/api/appointment', formData, config)
				.then(response => { //Trabaja en api -> modelo (appointment)>store()
					console.log(response.data)
					this.closeModal();
					this.cita.membresia=''
					this.$emit('actualizarListadoCitas', true)
					//console.log(response.data.cita.id)
					this.$swal({
						icon: 'success',
						title: 'Cita registrada con éxito'
					})
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
			this.cita.name= ''; this.cita.nombres= '';
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
			this.cita.recomendation= ''; this.cita.recomendacion_comentario='';
			this.cita.mode= '';
			this.cita.voucher= '';
			this.cita.link= '';
			this.cita.bank= '';
			this.cita.pay_status= '';
			this.cita.status= '';
			this.cita.type_amount=1;
			this.cita.contacto= ''; this.cita.contacto_celular= ''; this.cita.parentezco='';
			this.cita.contacto2= ''; this.cita.contacto_celular2= ''; this.cita.parentezco2='';
			this.etiqueta =''; this.descuentoAdelanto = 0; this.descuentoPorcentaje=0; this.descuentoPorcentual=0;
			this.tieneAdelanto=false; this.tieneDescuento=false; this.tieneRebaja=false; this.razonAdelanto=''; this.razonRebaja=''
			this.cita.vivo=1; this.monedaAdelanto=1
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

			this.axios.get("/api/buscarPacienteDB/"+this.cita.dni)
			.then(res => {
				if (res.data.patient == null) { //Buscar en reniec, nuevo
					if(this.cita.type_dni==1){
						//window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
						this.axios.get("/api/buscarDni/"+this.cita.dni)
						.then(response => {
							console.log(response.data)
							this.cita.name = (`${response.data.apellido_paterno} ${response.data.apellido_materno} `).trim()
							this.cita.nombres = `${response.data.nombres.trim()}`;
							this.cita.vivo=1
							
							if (response.data.apellido_paterno) {
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
				}else{ //encontró en la DB
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
					this.cita.nombres = res.data.patient.nombres;
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
					this.cita.vivo = res.data.patient.vivo;
					this.cita.contacto = res.data.relacion[0].name ?? '' ;
					this.cita.contacto_celular = res.data.relacion[0].phone ?? '' 
					this.cita.parentezco = res.data.relacion[0].kinship ?? ''
					this.cita.contacto2 = res.data.relacion[1].name ?? ''
					this.cita.contacto_celular2 = res.data.relacion[1].phone ?? '' 
					this.cita.parentezco2 = res.data.relacion[1].kinship ?? '' 
					this.cita.etiqueta = res.data.patient.etiqueta;
					this.cita.deudas = res.data.patient.deudas;
					this.cita.prev_status = res.data.patient.new_status;
					this.cita.club = res.data.patient.club;
					this.cita.membresia = res.data.membresia;
					this.cita.recomendation = res.data.patient.recomendation;
					this.cita.recomendacion_comentario = res.data.patient.recomendacion_comentario;
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
			this.cita.name = ''; this.cita.nombres = '';
			this.cita.email = '';
			this.cita.address = '';
			this.cita.department = 12;
			this.cita.province = 103;
			this.cita.district = 1006;
			this.cita.birth_date = '';
			this.cita.occupation = '';
			this.cita.marital_status = 1;
			this.cita.instruction_degree = 6;
			this.cita.gender = 2;
			this.cita.relative_name = '';
			this.cita.relatine_phone = '';
			this.cita.kinship = '';
			this.cita.prev_status=1;
			this.cita.etiqueta='';
			this.cita.new_status=1;
			this.cita.recomendation='';
			this.cita.membresia = null
			
			this.cita.contacto= ''; this.cita.contacto_celular= ''; this.cita.parentezco='';
			this.cita.contacto2= ''; this.cita.contacto_celular2= ''; this.cita.parentezco='';

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
			this.descuentoRebaja=0; this.razonPorcentaje=''; this.razonRebaja=''
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
			await this.axios.get('/api/listarPreciosTodos')
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
		this.recomendaciones.sort();
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
.ajs-danger, .alertify-notifier .ajs-message.ajs-error { background-color: rgb(232, 27, 0)!important; color:white!important; }
.form-switch label{cursor: pointer;}
.alert-danger {
    color: #ffffff;
    background-color: #ff3521;
}
.agrandar{transition: all 0.2s ease-in-out;}
.agrandar:hover{transform: scale(1.05);}
.modal label{
	color: #737373;
	font-weight: 500;
}
</style>
