<template>
  <div class="card">
    <div class="container-fluid pt-2">

			<ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
				
				<li class="nav-item d-none" role="presentation">
					<button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#calendario-tab" type="button" role="tab" aria-controls="calendario-tab" aria-selected="false"><i class="fa-regular fa-calendar-days"></i> Vista calendario</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="book-tab" data-bs-toggle="tab" data-bs-target="#cuaderno-tab" type="button" role="tab" aria-controls="cuaderno-tab" aria-selected="true"><i class="fa-solid fa-book"></i> Vista cuaderno</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link " id="home-tab" data-bs-toggle="tab" data-bs-target="#inicio-tab" type="button" role="tab" aria-controls="inicio-tab" aria-selected="false"><i class="fas fa-search"></i> Búsqueda del paciente</button>
				</li>
			</ul>
			<div class="tab-content" id="myTabContent">
				<!-- Inicio de primera tab -->
				<div class="tab-pane fade " id="inicio-tab" role="tabpanel" aria-labelledby="inicio-tab" tabindex="0">
					<div class="d-sm-flex align-items-center justify-content-around mt-4 px-3" style="gap: 10px;">
							<div class="d-none d-sm-inline-block form-inline w-75">
								<div class="input-group">
									<div class="input-group-prepend">
										<button class="btn btn-success" type="submit" @click="searchHistoria()"> <i class="fas fa-search fa-sm"></i> </button>
									</div>
									<input type="text" class="form-control bg-white shadow-sm border-0 small" placeholder="Nombre, D.N.I." aria-label="Search" aria-describedby="basic-addon2" id="searchInputAppointment" @keyup.enter="searchHistoria()" autocomplete="off">
								</div>
							</div>

							<div class="d-flex justify-content-start" style="flex-shrink: 0;">
									<input 
									type="date" 
									class="form-control bg-white shadow-sm border-0 small" v-model="fechaSinImportancia"
									@change="searchByDate"
									>
							</div>
							<div>
								<button class="btn btn-outline-success" @click="searchByDate()" title="Actualizar la lista"><i class="fas fa-sync-alt"></i></button>
							</div>
							
							<!-- <div class="d-flex justify-content-start" style="flex-shrink: 0;">
									<button data-bs-toggle="modal" data-bs-target="#addCitaModal" class="btn btn-outline-success"><i class="fas fa-plus"></i> Crear nueva Cita</button>
									<modal-cita :profes="profesionales" :horas="horarios" @emitIdProf="listarhorario" @emitDate="fechaEmit" :idUsuario="idUsuario"></modal-cita>
							</div> -->

							
					</div>

					<table class="table table-striped w-100 mt-4">
						<thead class="bg-success text-white">
							<tr>
								<th>Paciente</th>
								<th>Profesional</th>
								<th>Fecha y Hora</th>
								<th>Modo</th>
								<th>Pago</th>
								<th>Estado de Cita</th>
								<th>Opciones</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(qCita, index) in citas" :key="qCita.id" >
								<td class="d-none">{{qCita.id}}</td>
								<td class="text-uppercase puntero" @click="modalInfo(qCita)" data-bs-toggle="modal" data-bs-target="#patientModal2">
									<!-- <span v-html="retornarCara(qCita.patient)"></span> -->
									<!-- <i class="fas fa-brain"></i> -->
									<span> {{index+1}}.</span> <span>{{ qCita.patient.name }} {{ qCita.patient.nombres }}</span>
								</td>
								<td class="text-capitalize" :title="qCita.professional ? qCita.professional.name : '...'">{{ qCita.professional ? maxStringCharacter(lowerCase(qCita.professional.name), 15) : '...' }}</td>
								<td class="puntero" @click="modalInfo(qCita)" title="Información de la cita" data-bs-toggle="modal" data-bs-target="#infoModal">{{ qCita.date ? fechaLatam(qCita.date) : '...' }}
									<br>
									<span>{{ qCita.schedule ? horaHumana(qCita.schedule.check_time) : '...'}}</span>
										<br>
										<span>{{ qCita.schedule ? horaHumana(qCita.schedule.departure_date) : '...'}}</span> 
								</td>
								<td :title="qCita.mode == 1 ? 'Presencial':'Virtual'">
									<button disabled v-if="qCita.mode == 1" class="btn btn-info btn-sm"><i class="far fa-user"></i></button> <!-- @click="changeMode(qCita.id)" -->
									<button disabled v-else class="btn btn-primary btn-sm"><i class="fas fa-desktop"></i></button> <!-- @click="changeMode(qCita.id)" -->
								</td>
								<td>
									<button 
										class="btn btn-sm"
										:class='{
											"btn-warning": qCita.payment && qCita.payment.pay_status == 1 && qCita.payment.adelanto > 0,
											"btn-secondary": qCita.payment && qCita.payment.pay_status == 1 && qCita.payment.adelanto == 0,
											"btn-success": qCita.payment && qCita.payment.pay_status == 2,
											"btn-danger": qCita.payment && [3, null].includes(qCita.payment.pay_status),
											"btn-secondary": [null].includes(qCita.payment),
										}'>
										<span class="icon text-white-50">
											<i :class="{ 
												'fa-regular fa-circle-question': qCita.payment ? qCita.payment.pay_status == 1 : false,
												'fas fa-check': qCita.payment ? qCita.payment.pay_status == 2 : false,
												'fas fa-exclamation-circle': qCita.payment ? qCita.payment.pay_status == 3 : false,
												'fa-solid fa-xmark': qCita.payment ? qCita.payment.pay_status == null : false,
											}"></i>
										</span>

										<span class="text labels" v-if="qCita.payment && qCita.payment.pay_status == 1 && qCita.payment.adelanto > 0">Con Adelanto</span>
										<span class="text labels" v-else-if="qCita.payment && qCita.payment.pay_status == 1">Sin pagar</span>
										<span class="text labels" v-else-if="qCita.payment && qCita.payment.pay_status == 2">Cancelado</span>
										<span class="text labels" v-else-if="qCita.payment && [3, null].includes(qCita.payment.pay_status)">Anulado</span>
										<span class="text labels" v-else-if="!qCita.payment">Sin registro de pago</span>
									</button>

									<template v-if="qCita.payment">
										<p v-if="qCita.payment.rebaja>0"><i class="fas fa-wallet"></i>
												<small >Rebaja S/ {{ qCita.payment.rebaja }} </small>
												<small class="text-capitalize" ><i class="fas fa-angle-double-right"></i> {{ qCita.payment.motivoRebaja }} </small>
										</p>
										<p v-if=" qCita.payment.descuento>0"><i class="fas fa-wallet"></i>
												<small >Descuento S/ {{ qCita.payment.descuento }} </small>
												<small class="text-capitalize" ><i class="fas fa-angle-double-right"></i> {{ qCita.payment.motivoDescuento }} </small>
										</p>
										<p v-if="qCita.payment.adelanto>0"><i class="fas fa-wallet"></i>
												<small >Adelanto S/ {{ qCita.payment.adelanto }} </small>
												<small class="text-capitalize" ><i class="fas fa-angle-double-right"></i> {{ qCita.payment.razonAdelanto }} </small>
										</p>
									</template>
								</td>
								<td>
									<button 
									class="btn btn-sm"
									:class='{
									"btn-secondary": qCita.status == 1,
									"btn-info": qCita.status == 2,
									"btn-danger": qCita.status == 3 || qCita.status==4 || qCita.status==5
									
									}'> <!-- @click="modalInfo(qCita)" data-bs-toggle="modal" data-bs-target="#modalEstado" -->
										<span class="icon text-white-50">
											<i :class="{
												'fas fa-exclamation-circle': qCita.status == 1 || qCita.status==5,
												'fas fa-check': qCita.status == 2,
												'fas fa-minus-circle': qCita.status == 3 || qCita.status==4
											}"></i>
										</span>
										<span class="text labels" v-if="qCita.status == 1">Sin confirmar</span>
										<span class="text labels" v-else-if="qCita.status == 2">Confirmado</span>
										<span class="text labels" v-else-if="qCita.status == 3">Anulado</span>
										<span class="text labels" v-else-if="qCita.status == 4">Reprogramado</span>
										<span class="text labels" v-else-if="qCita.status == 5">Eliminado</span>
									</button>
									<small class="text-capitalize" v-if="(qCita.status == 3 || qCita.status==5) && qCita.faltas"><br><i class="far fa-comment"></i> {{queObservacion(qCita.faltas[0])}}</small>
									<small class="text-capitalize" v-if="qCita.status == 4 && qCita.faltas"><br><i class="far fa-comment"></i> {{queRazon(qCita.faltas[0])}} <span v-if="qCita.faltas[0].fechaProxima!=''">- Proxima cita: {{ fechaLatam(qCita.faltas[0].fechaProxima )}}</span></small>
								</td>
								<td>
									<div class="d-flex align-items-center justify-content-around gap-1">
											<!-- <a @click="modalInfo(cita)" title="Actualizar paciente" data-toggle="modal" data-target="#patientModal" class="btn btn-info btn-circle btn-sm"><i class="fas fa-user"></i></a> -->
											
											<a v-if="qCita.status == 3"  title="Cita cancelada"  class="btn btn-danger btn-circle btn-sm"><i class="fas fa-calendar"></i></a>
											<!-- <a v-else @click="modalInfo(qCita)" title="Reprogramar cita" data-bs-target="#reprogModal" data-bs-toggle="modal" class="btn btn-info btn-circle btn-sm"><i class="fas fa-calendar"></i></a> -->
											
											<!-- <a @click="modalInfo(cita)" title="Información de la cita" data-toggle="modal" data-target="#infoModal" class="btn btn-info btn-circle btn-sm"><i class="fas fa-info"></i></a> -->
											<!-- <a @click="eliminar(qCita.id)" title="Eliminar" class="btn btn-info btn-circle btn-sm"><i class="fas fa-trash"></i></a> -->

											<!-- Sin numero -->
											<a v-if="qCita.patient.phone ? false : true"
											class="btn btn-secondary btn-circle btn-sm"
											title="Sin número"
											>
											<i class="fab fa-whatsapp"></i>
											</a>

											<!-- Cita virtual - con link -->
											<a 
											:href="`whatsapp://send?phone=51${qCita.patient ? qCita.patient.phone : ''}&text=Buen día ${qCita.patient ? qCita.patient.name + '' + qCita.patient.nombres : ''}, 
											le recordamos que tiene reservada una cita online el día de hoy a las 
											${qCita.schedule ? horaHumana(qCita.schedule.check_time) : ''}, 
											le dejo el enlace de la cita ${qCita.link}`"
											target="_blank" 
											title="Enviar mensaje (cita virtual)" 
											class="btn btn-info btn-circle btn-sm"
											v-else-if="qCita.link"
											>
											<i class="fab fa-whatsapp"></i>
											</a>

											<!-- Cita presencial -->
											<a 
											:href="`whatsapp://send?phone=51${qCita.patient ? qCita.patient.phone : ''}&text=Buen día ${qCita.patient ? qCita.patient.name +' '+qCita.patient.nombres : ''}, le recordamos que tiene reservada una cita el día de hoy a las ${qCita.schedule ? horaHumana(qCita.schedule.check_time) : ''}, en el Centro Psicológico y Psiquiátrico EXCELENTEMENTE. Al culminar su sesión, no se olvide de reservar su próxima cita.`"
											target="_blank" 
											title="Enviar mensaje" 
											class="btn btn-info btn-circle btn-sm"
											v-else-if="!qCita.link"
											>
											<i class="fab fa-whatsapp"></i>
											</a>
											<a 
											:href="`whatsapp://send?phone=51${qCita.patient ? qCita.patient.phone : ''}&text=Buen día ${qCita.patient ? qCita.patient.name +' '+qCita.patient.nombres : ''}, esperamos se encuentre bien, le enviamos la encuesta de satisfacción de su cita en el Centro Psicológico y Psiquiátrico EXCELENTEMENTE, con ello nos ayudara a seguir mejorando en nuestra atención, gracias por su tiempo. 😊 https://forms.gle/VbnwkK85sXyoiVN5A`"
											target="_blank" 
											title="Enviar mensaje" 
											class="btn btn-primary btn-circle btn-sm"
											>
											<i class="fa fa-align-justify"></i>
											</a>
											<button data-bs-toggle="modal" @click="buscarRecetas(qCita.patient.id)" data-bs-target="#recetasModalRepetido" class="btn btn-info btn-circle btn-sm" title="Ver recetas">
											<i class="fas fa-file"></i></button>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<!-- Fin de primera tab -->
				<!-- Inicio de segunda tab -->
				<div class="tab-pane fade" id="calendario-tab" role="tabpanel" aria-labelledby="calendario-tab" tabindex="0">
					<vista-calendario :profesionales="profesionales" ></vista-calendario>
				</div>
				<div class="tab-pane fade show active" id="cuaderno-tab" role="tabpanel" aria-labelledby="cuaderno-tab" tabindex="0">
					<vista-cuaderno :nombreUser="nombreUser" :idSede="idSede" ></vista-cuaderno>
				</div>
				<!-- Fin de segunda tab -->
			</div>

    </div>

    <pago-modal v-if="cita" :cita="cita" :idUsuario="idUsuario"></pago-modal>
    <modal-estado  v-if="cita" :dataCit="cita"></modal-estado>
    <modal-patient v-if="cita" :dataCit="cita"></modal-patient>
    <info-modal v-if="cita" :dataCit="cita" :precios="precios"></info-modal>
    <reprog-modal v-if="cita" :dataCit="cita" :idUsuario="idUsuario"></reprog-modal>
		<modalVerRecetasRepetido :prescriptions="recetas"></modalVerRecetasRepetido>
    
  </div>
</template>

<script>
import PagoModal from './PagoModal.vue'
import InfoModal from './ModalInfo.vue'
import ModalPatient from './ModalPatient_table.vue'
import ModalEstadoCita from './ModalEstadoCita.vue'
import ReprogModal from './ReprogModal.vue'
import ModalVerRecetasRepetido from './ModalVerRecetasRepetido.vue'




import VistaCalendario from './VistaCalendario.vue'
import VistaCuaderno from './VistaCuaderno.vue'
import moment from 'moment'
import alertify from 'alertifyjs'


export default {
  name: 'table-cita',

  components: { PagoModal, InfoModal, ReprogModal, ModalEstadoCita, VistaCalendario, VistaCuaderno, ModalPatient, ModalVerRecetasRepetido },

  props: {
    profes:Array,
    horas:Array,
    dataCit: Object, nombreUser:String, idSede:null
  },


  data(){
    return{
      citas:[], fechaSinImportancia: moment().format('YYYY-MM-DD'), idUsuario:-1, tienePrivilegios:0,
      profesionales:[],
      horarios:[],
      hoursProfessional: [], recetas:[], precios:[],
      cita: null,
      schedulesInvalid: [],
      horariosAll: [],horasSolas:[],horasMalas:[],
      buscar:'', estados:[
				{id: 1, valor: 'Neutro', detalle: 'No tiene ningún registro de actitud'},
				{id: 2, valor: 'cumplidor', detalle: 'es un paciente exclente'},
				{id: 3, valor: 'promotor', detalle: 'promueve actividades entre sus compañeros o la empresa'},
				{id: 4, valor: 'wow', detalle: 'es involucrado en actividades, participativo'},
				{id: 5, valor: 'reprogramador', detalle: 'suele aplazarcitas y actividades'},
				{id: 6, valor: 'exigente', detalle: 'un paciente/cliente que siempre pide un trato especial'},
				{id: 7, valor: 'deudor', detalle: 'paciente con deudas'},
				{id: 8, valor: 'insatisfecho', detalle: 'suele quejarse de los servicios'},
				{id: 9, valor: 'peligroso', detalle: 'paciente con amenazas o actos de violencia.'},
			]
    }
  },

  methods:{
    async listar(){
      this.schedulesInvalid = [];
      this.horariosAll = [];
      this.horarios = [];
      this.hoursProfessional = [];
      /*setTimeout(() => {
        document.getElementById("professional_id").value = ""
      }, 0)*/

      //await axios.get('/api/appoitmentsReception')
      await axios.get('/api/searchByDateAppointment/'+ moment().format('YYYY-MM-DD'))
      .then(res => {

       /*  const nani = res.data.sort(function(a,b){
          if(a.schedule != null || b.schedule != null){
            return a.professional.id - b.professional.id || new Date(a.date+':'+a.schedule.check_time).getTime() - new Date(b.date+':'+b.schedule.check_time).getTime() 
          }
        }) */
			 //this.citas = nani;
        this.citas = res.data;
      })
      .catch(err => {
        console.error(err)
      });

      this.cita = null;
    },
		buscarRecetas(id){
			this.axios(`/api/verRecetaPorId/${id}`)
			.then(res =>{
				this.recetas = res.data;
			})
		},

    async eliminar(id){
      this.$swal({
          title: 'Quieres eliminar esta cita?',
          showDenyButton: true,
          confirmButtonText: 'Si',
          denyButtonText: `No`,
      }).then((result) => {
          if(result.isConfirmed){
              this.axios.delete('/api/appointment/'+id)
              .then((res) => {
                this.$swal('Cita eliminada con exito')
              });
              this.citas=result.data;
              this.listar();
          }
      })
    },

    async listarprofesional(){
      await this.axios.get('/api/profesional')
      .then(response => {
        this.profesionales=response.data;
      })
    },

    async listarhorario (id) {
      /* await this.axios.get(`/api/horario/${id}`)
      .then(res => {
        //this.horarios = res.data.schedulesInvalid;
        this.horariosAll = res.data.schedules;
        this.hoursProfessional = this.horarios;
      })
      .catch(err => {
        console.error(err)
      }) */
			this.fechaEmit(id,document.querySelector(".fecha-emit").value);

    },
		async fechaEmit (id, diaSeleccionado) {
      this.horarios = []
			
			await this.axios.get(`/api/horarioOcupado/${id}/${diaSeleccionado}`)
      .then(res => { //console.log(res.data);
				this.horasSolas = res.data.solos;
				this.horasMalas = res.data.invalidos;
			})
      // this.schedulesInvalid = new Array (this.schedulesInvalid);

      //let arraySchedulesInvalid = []
			
			//No vale el codigo siguiente:
      /* this.hoursProfessional.forEach(el => {
        if (!arraySchedulesInvalid.includes(el.schedule_id)) {
          arraySchedulesInvalid.push(el.schedule_id)
        }
      }) */
			
			this.horasSolas.forEach(fecha=>{
				if(fecha.day == this.diaDeLaSemana(new Date(diaSeleccionado).getDay()) ){ //Digamos Martes
					this.horarios.push(fecha)
				}
			})
			this.horasMalas.forEach(mal=> {
				let indice =  this.horarios.findIndex( el => el.id == mal.schedule_id )
				console.log(indice);
				this.horarios.splice(indice,1)
			})

      /* this.horariosAll.forEach(el => {
        //console.log('dia', el.day)
				//Si es el dia elegido, en ese rango busca si tiene una cita [appointment], si tiene no agrega nada, si no tiene agrega a horarios (horas libres)
        if (el.day === this.diaDeLaSemana(new Date(diaSeleccionado).getDay())) { //compara el día de hoy con la lista Ejm: martes con indice(2) en la lista de horarios
					if (el.appointments.find( el => {el.date === diaSeleccionado && el.status != 3; console.log(el)} ) ? true : false) { // Hay cita
					} else { //hora libre
						this.horarios.push(el)
					}
				
        }
      }) */
    },
    async searchHistoria () {
      let search = document.getElementById("searchInputAppointment").value.split('/'),
        nombre = search[0],
        profesional = search[1] || null,
        fecha = search[2] || null,
				dni = search[0]
				;

      if (nombre === '') return this.listar();
      if (profesional) profesional = profesional.trim();
      if (fecha) fecha = fecha.trim();
      if (dni) dni = dni.trim();

      await this.axios.get(`/api/searchAppointment/${nombre}/${profesional}/${fecha}/${dni}`)
      .then(res => {
				console.log('otros',res.data);
        this.citas = res.data;
      })
      .catch(err => {
        console.error(err)
      })

      // event.target.value === ''
      //   ? this.listar()
      //   : this.busqueda = this.citas.filter((el, index) => el.patient.name.match(new RegExp(`${event.target.value}`,'ig')) && index < 9 ? el : null)
    },

    actualizarDatosArray (info) {
      if (info) {
        this.horarios = []
        this.hoursProfessional = []
        this.schedulesInvalid = []
        this.horariosAll = []
      }
    },
    changeMode(id){
      if(confirm('Está seguro de cambiar el modo de la cita?')){
        this.$swal({title: 'Actualizando modo'})
        this.axios.get(`/api/updateModeAppoinment/${id}`)
        .then(res =>{
          this.listar()
          this.$swal.close()
        })
      }
    },

    async searchByDate(e){

      this.$swal({title: 'Actualizando citas con fecha: '+ this.fechaSinImportancia })
      await this.axios.get(`/api/searchByDateAppointment/${this.fechaSinImportancia}`)
      .then(res => {
        this.citas = res.data;
        this.$swal.close()
      })
      .catch(err => {
        console.error(err)
      })
    }, 

    lowerCase (text) {
      return text.toLowerCase();
    },

    diaDeLaSemana (dia) {
      switch (dia) {
        case 0: return "Lunes"; break;
        case 1: return "Martes"; break;
        case 2: return "Miercoles"; break;
        case 3: return "Jueves"; break;
        case 4: return "Viernes"; break; 
				case 5: return "Sabado"; break;
        case 6: return "Domingo"; break;
      }
    },

    modalInfo (data) {
			//this.cita = null;
			if( data.status!=4 )
      	this.cita = data;
			else
				this.$swal({title: 'La cita ya esta reprogramada, no se puede cambiar el estado', icon:'error'})
    },
    horaHumana (hora) {
      let minutos = parseInt(hora.substring(3,5))
      hora = parseInt(hora.substring(0,2))
      if (hora > 12) {
          return `${hora - 12}:${minutos.toString().length === 1 ? '0' + minutos : minutos} p.m.`
      } else {
          if (hora === 12 && minutos >= 0) {
              return `${hora}:${minutos.toString().length === 1 ? '0' + minutos : minutos} p.m.`
          }

          if (hora === 0) {
              return `12:${minutos.toString().length === 1 ? '0' + minutos : minutos} a.m.`
          }
          return `${hora}:${minutos.toString().length === 1 ? '0' + minutos : minutos} a.m.`
      }
    },
		fechaLatam(fecha){
			if(fecha) return moment(fecha).format('DD/MM/YYYY');
		},

    maxCharacter (character, num) {
      let characterArray = character.split(' ');
      if (characterArray.length >= num) {
        characterArray.splice(num);
        characterArray[num - 1] += '...';
      }

      return characterArray.join(' ');
    },

    maxStringCharacter (character, num) {
      return character.substring(0, num) + '...';
    },
		retornarCara(caras){
			if(caras.semaforo!= undefined && caras.semaforo!=null){
				if(caras.semaforo.length>0){
					if( [1].includes(caras.semaforo[0].codigo) ){ return '<span class="badge rounded-pill p-2 text-bg-secondary" title="Normal"><i class="fas fa-smile"></i></span>' }
					if( [2, 3, 4].includes(caras.semaforo[0].codigo) ){ return `<span class="badge rounded-pill p-2 text-bg-primary" title="${this.estados[caras.semaforo[0].codigo].valor}"> <i class="fas fa-laugh-wink"></i> </span>` }
					if( [5, 6, 7].includes(caras.semaforo[0].codigo) ){ return `<span class="badge rounded-pill p-2 text-bg-warning" title="${this.estados[caras.semaforo[0].codigo].valor}"> <i class="fas fa-meh"></i> </span>` }
					if( [8,9].includes(caras.semaforo[0].codigo) ){ return `<span class="badge rounded-pill p-2 text-bg-danger" title="${this.estados[caras.semaforo[0].codigo].valor}"> <i class="fas fa-meh"></i> </span>` }
				}else{
					return '<span class="badge rounded-pill p-2 text-bg-secondary" title="Sin dato"><i class="fas fa-smile"></i></span>'
				}
			}else return '<span class="badge rounded-pill p-2 text-bg-secondary" title="Sin dato"><i class="fas fa-smile"></i></span>'

		},
		
		queObservacion(faltas){
			
			if(faltas) return faltas.observaciones
			else return ''
		},
		queRazon(faltas){
			if(faltas) return faltas.reason
			else return ''
		},
		async listarPrecios(){
			await this.axios.get('/api/listarPreciosTodos')
			.then( response => this.precios = response.data)
		},
		
  },

  created () {
    this.listar();
    this.listarprofesional();
  //  this.listarhorario();
	

  },
	mounted(){
		this.axios.get('/api/user')
		.then((res) => {
			this.idUsuario = parseInt(res.data.user.id)
			this.tienePrivilegios = res.data.user.privilegios
			this.listarPrecios();
		})
	},

  updated () {
    // this.modalInfo()
  },
	beforeUnmount() {
    //this.$refs.modal.removeEventListener('hidden.bs.modal', this.manejarCierreModal);
  },

  computed:{
		
  },
}
</script>

<style scoped>
  .gap-5 {
    gap: 5px;
  }

  .labels { 
    min-width: 90px;
    word-break: break-all;
    font-size: 12px; 
  }
	.puntero{
		cursor: pointer;
	}
	
</style>

<style>
.ajs-message{border-radius: 5px!important;}
.ajs-success { background-color: rgb(33, 201, 89)!important; }
.ajs-danger { background-color: rgb(232, 27, 0)!important; color:white!important; }
</style>