<template>
  <div class="card">
    <div class="container-fluid">
      <div class="d-sm-flex align-items-center justify-content-around mt-4 px-3" style="gap: 10px;">
          <div class="d-none d-sm-inline-block form-inline w-75">
            <div class="input-group">
              <div class="input-group-prepend">
                <button class="btn btn-success" type="submit" @click="searchHistoria()">
                    <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
              <input
              type="text"
              class="form-control bg-white shadow-sm border-0 small"
              placeholder="Nombre, Profesional, D.N.I."
              aria-label="Search"
              aria-describedby="basic-addon2"
              id="searchInputAppointment" @keyup.enter="searchHistoria()"
              >
            </div>
          </div>

          <div class="d-flex justify-content-start" style="flex-shrink: 0;">
              <input 
              type="date" 
              class="form-control bg-white shadow-sm border-0 small"
              @change="searchByDate"
              >
          </div>
          
          <div class="d-flex justify-content-start" style="flex-shrink: 0;">
              <button data-toggle="modal" data-target="#addCitaModal" class="btn btn-outline-success"><i class="fas fa-plus"></i> Crear nueva Cita</button>
              <modal-cita
              :profes="profesionales"
              :horas="horarios"
              @emitIdProf="listarhorario"
              @emitDate="fechaEmit"
              ></modal-cita>
          </div>

          <div class="d-flex justify-content-start" style="flex-shrink: 0;">
              <button data-toggle="modal" data-target="#pagoExtras" class="btn btn-outline-secondary"><i class="fas fa-plus"></i> Pagos extras</button>
          </div>
          <div class="d-flex justify-content-start" style="flex-shrink: 0;">
						<button data-toggle="modal" data-target="#egresosExtras" class="btn btn-outline-danger"><i class="fas fa-minus"></i> Egresos extras</button>
          </div>
      </div>

      <table class="table table-striped w-100 mt-4">
        <thead class="bg-success text-white">
          <tr>
            <th>Paciente</th>
            <th>Profesional</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Modo</th>
            <th>Pago</th>
            <th>Estado de Cita</th>
            <th>Opciones</th>
          </tr>
        </thead>
        <tbody>
          <tr
          v-for="(cita, index) in citas"
          :key="index"
          >
            <td class="text-capitalize puntero" :title="cita.patient ? cita.patient.name : '...'" @click="modalInfo(cita)" data-toggle="modal" data-target="#patientModal">
							<span><i class="fas fa-brain"></i>  {{index+1}}.</span> <span>{{ cita.patient ? lowerCase(cita.patient.name) : '..' }}</span>
						</td>
            <td class="text-capitalize" :title="cita.professional ? cita.professional.name : '...'">{{ cita.professional ? maxStringCharacter(lowerCase(cita.professional.name), 15) : '...' }}</td>
            <td class="puntero" @click="modalInfo(cita)" title="Información de la cita" data-toggle="modal" data-target="#infoModal">{{ cita.date ? fechaLatam(cita.date) : '...' }}</td>
            <td class="puntero" @click="modalInfo(cita)" title="Información de la cita" data-toggle="modal" data-target="#infoModal">
                <span>{{ cita.schedule ? horaHumana(cita.schedule.check_time) : '...'}}</span>
                <br>
                <span>{{ cita.schedule ? horaHumana(cita.schedule.departure_date) : '...'}}</span> 
            </td>
            <td :title="cita.mode == 1 ? 'Presencial':'Virtual'">
              <a @click="changeMode(cita.id)" v-if="cita.mode == 1" class="btn btn-info btn-sm"><i class="far fa-user"></i></a>
              <a @click="changeMode(cita.id)" v-else class="btn btn-primary btn-sm"><i class="fas fa-desktop"></i></a>
            </td>
            <td>
              <a @click="modalInfo(cita);" data-toggle="modal" data-target="#pagoModal"
              class="btn btn-success btn-icon-split btn-sm"
              :class='{
              "btn-danger":  cita.payment ? cita.payment.pay_status == 1 : false,
              "btn-success": cita.payment ? cita.payment.pay_status == 2 : false}'
              >
                <span class="icon text-white-50">
                  <i :class="{ 
                    'fas fa-exclamation-circle': cita.payment ? cita.payment.pay_status == 1 : false,
                    'fas fa-check': cita.payment ? cita.payment.pay_status == 2 : false 
                  }"></i>
                </span>

                <span class="text labels" v-if="cita.payment && cita.payment.pay_status == 1">Sin cancelar</span>
                <span class="text labels" v-else-if="cita.payment && cita.payment.pay_status == 2">Cancelado</span>
                <span class="text labels" v-else-if="!cita.payment">Error</span>
              </a>
            </td>
            <td>
              <a @click="modalInfo(cita)" data-toggle="modal" data-target="#modalEstado"
              class="btn btn-success btn-icon-split btn-sm"
              :class='{
              "btn-warning": cita.status == 1,
              "btn-info": cita.status == 2,
              "btn-danger": cita.status == 3,
              }'>
                <span class="icon text-white-50">
                  <i :class="{
                    'fas fa-exclamation-circle': cita.status == 1,
                    'fas fa-check': cita.status == 2,
                    'fas fa-times': cita.status == 3
                  }"></i>
                </span>
                <span class="text labels" v-if="cita.status == 1">Sin confirmar</span>
                <span class="text labels" v-else-if="cita.status == 2">Confirmado</span>
                <span class="text labels" v-else-if="cita.status == 3">Cancelado</span>
              </a>
            </td>
            <td>
              <div class="row d-flex align-items-center justify-content-around gap-5">
                  <!-- <a @click="modalInfo(cita)" title="Actualizar paciente" data-toggle="modal" data-target="#patientModal" class="btn btn-info btn-circle btn-sm"><i class="fas fa-user"></i></a> -->
                  
                  <a v-if="cita.status == 3"  title="Cita cancelada"  class="btn btn-danger btn-circle btn-sm"><i class="fas fa-calendar"></i></a>
                  <a v-else @click="modalInfo(cita)" title="Reprogramar cita" data-target="#reprogModal" data-toggle="modal" class="btn btn-info btn-circle btn-sm"><i class="fas fa-calendar"></i></a>
                  
                  <!-- <a @click="modalInfo(cita)" title="Información de la cita" data-toggle="modal" data-target="#infoModal" class="btn btn-info btn-circle btn-sm"><i class="fas fa-info"></i></a> -->
                  <a @click="eliminar(cita.id)" title="Eliminar" class="btn btn-info btn-circle btn-sm"><i class="fas fa-trash"></i></a>

                  <!-- Sin numero -->
                  <a v-if="cita.patient.phone ? false : true"
                  class="btn btn-danger btn-circle btn-sm"
                  title="Sin número"
                  >
                  <i class="fab fa-whatsapp"></i>
                  </a>

                  <!-- Cita virtual - con link -->
                  <a 
                  :href="`whatsapp://send?phone=51${cita.patient ? cita.patient.phone : ''}&text=Buen día ${cita.patient ? cita.patient.name : ''}, 
                  le recordamos que tiene reservada una cita online el día de hoy a las 
                  ${cita.schedule ? horaHumana(cita.schedule.check_time) : ''}, 
                  le dejo el enlace de la cita ${cita.link}`"
                  target="_blank" 
                  title="Enviar mensaje (cita virtual)" 
                  class="btn btn-info btn-circle btn-sm"
                  v-else-if="cita.link"
                  >
                  <i class="fab fa-whatsapp"></i>
                  </a>

                  <!-- Cita presencial -->
                  <a 
                  :href="`whatsapp://send?phone=51${cita.patient ? cita.patient.phone : ''}&text=Buen día ${cita.patient ? cita.patient.name : ''}, le recordamos que tiene reservada una cita el día de hoy a las ${cita.schedule ? horaHumana(cita.schedule.check_time) : ''}, en el Centro Psicológico y Psiquiátrico EXCELENTEMENTE. Al culminar su sesión, no se olvide de reservar su próxima cita.`"
                  target="_blank" 
                  title="Enviar mensaje" 
                  class="btn btn-info btn-circle btn-sm"
                  v-else-if="!cita.link"
                  >
                  <i class="fab fa-whatsapp"></i>
                  </a>
                  <a 
                  :href="`whatsapp://send?phone=51${cita.patient ? cita.patient.phone : ''}&text=Buen día ${cita.patient ? cita.patient.name : ''}, esperamos se encuentre bien, le enviamos la encuesta de satisfacción de su cita en el Centro Psicológico y Psiquiátrico EXCELENTEMENTE, con ello nos ayudara a seguir mejorando en nuestra atención, gracias por su tiempo. 😊 https://forms.gle/VbnwkK85sXyoiVN5A`"
                  target="_blank" 
                  title="Enviar mensaje" 
                  class="btn btn-primary btn-circle btn-sm"
                  >
                  <i class="fa fa-align-justify"></i>
                  </a>
                  <a class="btn btn-info btn-circle btn-sm" :href="`/api/ticket/${cita.id}`" target="_blank">
                    <i class="fas fa-file"></i>
                  </a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <pago-modal  v-if="cita" :cita="cita"></pago-modal>
    <modal-estado  v-if="cita" :dataCit="cita"></modal-estado>
    <modal-patient v-if="cita" :dataCit="cita"></modal-patient>
    <info-modal v-if="cita" :dataCit="cita"></info-modal>
    <reprog-modal v-if="cita" :dataCit="cita"></reprog-modal>
    <modal-pagos-extras />
    <modal-egresos-extras />
  </div>
</template>

<script>
import PagoModal from './PagoModal.vue'
import InfoModal from './ModalInfo.vue'
import ModalPatient from './ModalPatient.vue'
import ModalEstadoCita from './ModalEstadoCita.vue'
import ReprogModal from './ReprogModal.vue'
import ModalPagosExtras from './ModalPagosExtras.vue'
import ModalEgresosExtras from './ModalEgresosExtras.vue'
import moment from 'moment'
import alertify from 'alertifyjs'


export default {
  name: 'table-cita',

  components: { PagoModal, InfoModal, ReprogModal, ModalPatient, ModalEstadoCita, ModalPagosExtras, ModalEgresosExtras },

  props: {
    profes:Array,
    horas:Array,
    dataCit: Object,
  },


  data(){
    return{
      citas:[],
      profesionales:[],
      horarios:[],
      hoursProfessional: [],
      cita: null,
      schedulesInvalid: [],
      horariosAll: [],
      buscar:'',
    }
  },

  methods:{
    async listar(){
      this.schedulesInvalid = [];
      this.horariosAll = [];
      this.horarios = [];
      this.hoursProfessional = [];
      setTimeout(() => {
        document.getElementById("professional_id").value = ""
      }, 0)

      await axios.get('/api/appoitmentsReception')
      .then(res => {
        

        const nani = res.data.sort(function(a,b){
          if(a.schedule != null || b.schedule != null){
            return a.professional.id - b.professional.id || new Date(a.date+':'+a.schedule.check_time).getTime() - new Date(b.date+':'+b.schedule.check_time).getTime() 
          }
        })
        this.citas = nani
      })
      .catch(err => {
        console.error(err)
      });

      this.cita = null;
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
      await this.axios.get(`/api/horario/${id}`)
      .then(res => {
        this.horarios = res.data.schedulesInvalid;
        this.horariosAll = res.data.schedules;
        this.hoursProfessional = this.horarios;

        this.fechaEmit(document.querySelector(".fecha-emit").value);

        this.schedulesInvalid = []
        this.hoursProfessional.forEach(el => {
          this.schedulesInvalid.push(el.schedule_id)
        });
      })
      .catch(err => {
        console.error(err)
      })
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
				console.log(res.data);
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

      this.$swal({title: 'Actualizando citas con fecha: '+e.target.value,})
      await this.axios.get(`/api/searchByDateAppointment/${e.target.value}`)
      .then(res => {
        this.citas = res.data;
        this.$swal.close()
      })
      .catch(err => {
        console.error(err)
      })
    },

    fechaEmit (info) {
      this.horarios = []

      // this.schedulesInvalid = new Array (this.schedulesInvalid);

      let arraySchedulesInvalid = []
      this.hoursProfessional.forEach(el => {
        if (!arraySchedulesInvalid.includes(el.schedule_id)) {
          arraySchedulesInvalid.push(el.schedule_id)
        }
      })

      this.horariosAll.forEach(el => {
        console.log('dia', el.day)
        if (el.day === this.dayWeek(new Date(info).getDay())) {
          if (arraySchedulesInvalid.includes(el.id)) {
            // Hay cita
            if (el.appointments.find(el => el.date === info && el.status != 3) ? true : false) {

            } else {
              this.horarios.push(el)
            }
          } else {
            // No hay cita
            this.horarios.push(el)
          }
        }
      })
    },

    lowerCase (text) {
      return text.toLowerCase();
    },

    dayWeek (day) {
      switch (day) {
        case 0:
          return "Lunes"
        break;
        case 1:
          return "Martes"
        break;
        case 2:
          return "Miercoles"
        break;
        case 3:
          return "Jueves"
        break;
        case 4:
          return "Viernes"
        break;
        case 5:
          return "Sabado"
        break;
        case 6:
          return "Domingo"
        break;
      }
    },

    modalInfo (data) {
			this.cita = null;
      this.cita = data;
    },

    

    filtro() {

      // if(!this.buscar){
      //   return this.citas
      // } else{
      //   return this.citas.filter(cita => cita.patient.name.toLowerCase().includes(this.buscar) || cita.professional.name.toLowerCase().includes(this.buscar))
      // }
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
			return moment(fecha).format('DD/MM/YYYY');
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
		
  },

  created () {
    this.listar();
    this.listarprofesional();
  //  this.listarhorario();
	

  },
	mounted(){
	},

  updated () {
    // this.modalInfo()
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