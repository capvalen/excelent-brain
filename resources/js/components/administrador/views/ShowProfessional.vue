<template>
  <main>
    <h2 class="admin-title">Profesional</h2>
    <div class="row">
          <div class="col-md-4 mt-4">
              <div class="card shadow" style="width: 100%;">
              <img class="card-img-top w-50 mx-auto" style="height: auto;object-fit: cover;" 
              :src="profesional.photo=='-' || profesional.photo=='' ? '/img/doc_default.jpg' : '/img/'+profesional.photo" alt="Foto de Profesional" width="100%" >
              <div class="card-body">
                  <h4 class="d-block name-profesional">{{ profesional.name }}</h4>
                  <span class="d-block description-profesional mb-3">{{ profesional.profession }}</span>
              </div>
              </div>
          </div>
      <div class="col-md-8 mt-4">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header bg-dark py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-white">Filtro de consultas</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="row">
                  <div class="col-sm col-md-6">
                    <select @change="showSelect" class="form-select" id="optionTime">
                      <option disabled selected value>Seleccione una opción de tiempo</option>  
                      <option value="1">Mes</option>
                      <option value="2">Día</option>    
                    </select>
                  </div>
                  <div v-if="show !=0 && show==1" class="col-sm">
                    <select @change="getPatientsPerMonth" class="form-select" id="selectMonth">
                        <option disabled selected value>Seleccione un mes</option>
                        <option v-for="(month, index) in months" :key="index" :value="month">{{month | optionParseMonth}}</option>
                    </select>
                  </div>
                  <div v-if="show !=0 && show==2" class="col-sm">
                    <input @change="getPatientsPerDay" type="date" class="form-control" id="datePicker">
                  </div>
                </div>

                <div class="row mt-3 d-none">
                  <div class="col-sm">
                   <p><b>Pacientes atendidos: </b> {{this.per_month}}</p>
                  </div>
                  <div class="col-sm">
                  </div>
                </div>              
                
            </div>
          </div>
          
          <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header bg-dark py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-white">Resumen de la consulta</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
              <p class="mb-0"><b>Total recaudado: S/ </b> {{this.total_price}}</p>
              <p class="mb-0"><b>Total de citas: </b> {{this.totalCitas}}</p>
                <span>Consultas confirmadas: {{this.confirmed}}</span>
                <br>
                <span>Consultas sin confirmar: {{this.no_confirmed}}</span>
                <br>
                <span>Consultas reprogramadas: {{this.reprogramed}}</span>
                <br>
                <span>Consultas canceladas: {{this.cancelled}}</span>
            </div>
          </div>
          
      </div>
    </div>
    <div class="row mt-4">
      <h2 class="admin-title">Consultas</h2>
			<div class="row">
				<div class="col">
					<label for="">Filtros</label>
					<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
						<input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked @click="empezarFiltro('todos')">
						<label class="btn btn-outline-primary" for="btnradio1">Todos</label>
						<input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off" @click="empezarFiltro('siRellenado')">
						<label class="btn btn-outline-primary" for="btnradio2">Rellenados</label>
						<input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off" @click="empezarFiltro('noRellenado')">
						<label class="btn btn-outline-primary" for="btnradio3">No rellenados</label>
						<input type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off" @click="empezarFiltro('noConfirmado')">
						<label class="btn btn-outline-primary" for="btnradio4">No confirmados</label>
					</div>
				</div>
			</div>
      <table class="table">
        <thead class="thead-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Id cita</th>
            <th scope="col">Nombre y apellido</th>
            <th scope="col">Fecha</th>
            <th scope="col">Tipo de Consulta</th>
            <th scope="col">Monto</th>
            <th scope="col">Estado pago</th>
            <th scope="col">Estado cita</th>
            <th scope="col">Evolución</th>
          </tr>
        </thead>
        <tbody id="bodyConsultas">
          <tr v-for="(appointment, index) in appointments" :key="index">
            <th>{{ index + 1 }}</th>
            <th>{{ appointment.id }}</th>
            <td>{{ appointment.patient.name}}  {{ appointment.patient.nombres }}</td>
            <td>{{ fechaLatam(appointment.date)}}</td>
                <!-- <td v-if=" appointment.type === 1">Terapia Inicial niño/adolescente</td>
                <td v-else-if=" appointment.type === 2">Terapia Inicial adulto</td>
                <td v-else-if=" appointment.type === 3">Terapia Inicial pareja</td>
                <td v-else-if=" appointment.type === 4">Terapia Inicial familiar</td>
                <td v-else-if=" appointment.type === 5">Terapia continua niño/adolescente</td>
                <td v-else-if=" appointment.type === 6">Terapia continua adulto</td>
                <td v-else-if=" appointment.type === 7">Terapia continua pareja</td>
                <td v-else-if=" appointment.type === 8">Terapia continua familiar</td>
                <td v-else-if=" appointment.type === 9">Orientación Vocacional</td>
                <td v-else-if=" appointment.type === 10">Sucamec inicial</td>
                <td v-else-if=" appointment.type === 11">Sucamec renovación</td>
                <td v-else-if=" appointment.type === 12">Kurame</td>
                <td v-else>Sin tipo de consulta</td> -->
            <td>{{ appointment.precio.descripcion }} </td>
            <td>{{appointment.payment ? `S/ ${appointment.payment.price}` : 'Sin pago' }}</td>
            <td>
              <span class="text-muted" v-if="appointment.payment.pay_status==1">Pendiente</span>
              <span class="text-success" v-else-if="appointment.payment.pay_status==2">Pagado</span>
              <span class="text-danger" v-else-if="appointment.payment.pay_status==3">Anulado</span>
            </td>
            <td>
              <span class="text-danger" v-if="appointment.status==1">Sin asistir</span>
              <span class="text-sucess" v-if="appointment.status==2">Asistido</span>
              <span class="text-danger" v-if="appointment.status==3">Anulado</span>
              <span class="text-danger" v-if="appointment.status==4">Reprogramado</span>
            </td>
            <td>
              <button v-if="appointment.patient.medical_evolutions[0] && appointment.patient.medical_evolutions[0].content === null" class="btn btn-outline-secondary btnRellenado" data-bs-toggle="modal" data-bs-target="#evolutionModal" @click="getEvolutions(appointment.patient.medical_evolutions[0])" data-contenido="noRellenado">No rellenado</button>
              <button v-else-if="!appointment.patient.medical_evolutions[0]" class="btn btn-outline-danger btnRellenado" data-contenido="noConfirmado">No confirmado</button>
              <button v-else class="btn btn-outline-success btnRellenado" data-bs-toggle="modal" data-bs-target="#evolutionModal" @click="getEvolutions(appointment.patient.medical_evolutions[0])"  data-contenido="siRellenado">Rellenado</button>
              <button v-if="appointment.patient.medical_evolutions[0]" @click="deleteEvolution(appointment.patient.medical_evolutions[0])" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
              <p v-if="!appointment.patient.medical_evolutions[0]" class="text-danger">Evolución no generada o eliminada</p>
            </td>
          </tr>
        </tbody>
      </table>
      <p v-if="appointments.length==0">No hay registros</p>

    </div>
    <!-- <div class="row mt-4 d-none">
      <h2 class="admin-title">Pagos Extra</h2>
      <table class="table">
        <thead class="thead-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Nombre y apellido</th>
            <th scope="col">Fecha</th>
            <th scope="col">Tipo</th>
            <th scope="col">Monto</th>
            <th scope="col">Observación</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(extra_payment, index) in extra_payments" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ extra_payment.customer }}</td>
            <td>{{ extra_payment.date }}</td>
            <td>
             <p v-if=" extra_payment.type === 0">Certificado</p>
             <p v-else-if=" extra_payment.type === 1">Paquete Membresía</p> 
             <p v-else-if=" extra_payment.type === 2">Paquete Kurame</p> 
             <p v-else-if=" extra_payment.type === 3">Informe</p>
             <p v-else-if=" extra_payment.type === 4">Otros</p>
             <p v-else>Sin tipo de consulta</p>   
            </td>
            <td>S/ {{ extra_payment.price }}</td>
            <td>{{ extra_payment.observation }}</td>
          </tr>
        </tbody>
      </table>
      <p v-if="extra_payments.length==0">No hay registros</p>
    </div> -->

    <evolucion-modal v-if="evolutions" :dataEvolution="evolutions"></evolucion-modal>
  </main>
</template>

<script>

import EvolucionModal from './EvolucionesModal.vue';
import moment, { relativeTimeThreshold } from 'moment'
export default {
  name: 'HomeProfesionales',

  components: { EvolucionModal },

  data () {
    return {
      fecha:'',
      cancelled:0,
      no_confirmed:0,
      confirmed:0,
      per_month:'',
      total_price:0,
      today: new Date(),
      profesional: {},
      appointments:[],
      extra_payments:[],
      patients:[],
      months:[],
      autorize:'No Autorizado',
      evolutions: null,
      evolution:{
        profesional_id: '',
        date: null,
        check_time: null
      },
      show: 0, totalCitas:0
    }
  },

  props: {
    dataEvolution: Array,
  },

  methods:{
		empezarFiltro(tipo){
			const filas = document.querySelectorAll('#bodyConsultas tr')
			
			filas.forEach(fila=>{
				let contiene = fila.querySelector('.btnRellenado').getAttribute('data-contenido')
				console.log(contiene , tipo)
				if( tipo=='todos')
					fila.classList.remove('d-none')
				else if(contiene == tipo )
					fila.classList.remove('d-none')
				else
					fila.classList.add('d-none')
			})
		},
    showSelect(e){
      if(e.target.value == 1){
        this.show = 1
      }else{
        this.show = 2
      }
    },
    setMonths(){
      this.months.push(moment(this.today).format('YYYY-MM'))
      while(moment(this.today).format('YYYY-MM') != '2022-01'){
       this.months.push(moment(this.today).add(-1,'months').format('YYYY-MM'))
       this.today = moment(this.today).add(-1, 'months') 
      }
    },
    getProfesionales(){
      
      this.axios.get(`/api/professional/${this.$route.params.profId}`)
      .then((result) => {
        this.profesional = result.data
        this.evolution.profesional_id = this.profesional.id
        //this.getAppointments()
        
      }).catch((err) => {
        console.error(err)
      });
    },

    async getSummaryAppointments(){
      this.$swal({
        title: 'Cargando...',
        showConfirmButton: false,
        icon:'info'
      })
      await this.axios.get(`/api/getProfessionalSummaryAppointment/${this.profesional.id}/${this.fecha}`)
      .then((res) => {
        console.log(res.data)
        this.patients = res.data.patients,
        this.cancelled = res.data.cancelled,
        this.confirmed = res.data.confirmed,
        this.per_month = res.data.per_month,
        this.no_confirmed = res.data.no_confirmed
        this.reprogramed = res.data.reprogramed
        this.$swal.close()
      }).catch((err) => {
        console.error(err)
      });
    },
    async getPatientsPerMonth(e){
      this.$swal({
        title: 'Cargando...',
        showConfirmButton: false,
        icon:'info',
        isDismissed: false
      })
      this.total_price = 0
      this.fecha = e.target.value;
      this.axios.get(`/api/getPatientsPerMonth/${this.fecha}/${this.profesional.id}`)
      .then((res)=>{
        console.log(res.data)
        this.per_month = res.data.patientsPerMonth,
        this.appointments = res.data.appointments,
        this.appointments.forEach(cita => {
          //if(cita.status == 3 || cita.status == 4) this.cancelled++
          this.total_price += parseFloat(cita.payment ? cita.payment.price : 0 )
        });
        this.totalCitas = this.appointments.length
        this.extra_payments = res.data.extra_payments
        this.extra_payments.forEach(pay =>{
          this.total_price += parseFloat(!pay.price ? 0 : pay.price)
        });
        let num = Math.round(this.total_price * 10) / 10;
        this.total_price = num.toFixed(2)

        this.$swal.close()
      }).catch((err) => {
        console.error(err)
      })
      this.getSummaryAppointments()

    },
    async getPatientsPerDay(e){
      this.$swal({
        title: 'Cargando...',
        showConfirmButton: false,
        icon:'info',
        isDismissed: false
      })
      console.log(e.target.value)
      this.total_price = 0
      this.fecha = e.target.value
      this.axios.get(`/api/getPatientsPerDay/${this.fecha}/${this.profesional.id}`)
      .then((res)=>{
        console.log(res.data)
        this.per_month = res.data.patientsPerMonth,
        this.appointments = res.data.appointments,
        this.appointments.forEach(appo => {
          this.total_price += parseFloat(appo.payment ? appo.payment.price : 0 )
        });
        this.totalCitas = this.appointments.length
        this.extra_payments = res.data.extra_payments
        this.extra_payments.forEach(pay =>{
          this.total_price += parseFloat(!pay.price ? 0 : pay.price)
        });
        let num = Math.round(this.total_price * 10) / 10;
        this.total_price = num.toFixed(2)

        this.$swal.close()
      }).catch((err) => {
        console.error(err)
      })
      this.getSummaryAppointments()
    },

    getEvolutions (evolution) {
      this.evolutions = evolution
    },

    deleteEvolution(evolution){
      //console.log(evolution)
      this.$swal({
          title: 'Está seguro de eliminar esta evolución?',
          showDenyButton: true,
          confirmButtonText: 'Si',
          denyButtonText: `No`,
      }).then((result) => {
          if(result.isConfirmed){
              this.axios.delete('/api/deleteEvolution/'+evolution.id)
              .then((res) => {
                this.$swal('Evolución eliminada con exito')
                location.reload()
              });
          }
      })
    },
    fechaLatam(fecha) {
			return moment(fecha).format('DD/MM/YYYY');
		},
    capitalizar(texto) {
				const primeraLetra = texto.charAt(0);
				const primeraLetraMayuscula = primeraLetra.toUpperCase();
				return primeraLetraMayuscula + texto.slice(1);
			}

  },

  mounted(){
    this.getProfesionales(),
    this.setMonths()
  },
  
  filters:{
    dateParse: function(val){
      return moment(val).format('DD/MM/YYYY');
    },
    monthParse : function(val){
      return moment(val+'-01').format('YYYY-MM')
    },
    optionParseMonth: function(val){
      moment.locale('es');
      const texto = moment(val).format('MMMM YYYY')
      const primeraLetra = texto.charAt(0);
      const primeraLetraMayuscula = primeraLetra.toUpperCase();
      return primeraLetraMayuscula + texto.slice(1);
    },
    timeParse: function(time){
      var datetime = new Date('1970-01-01T' + time + 'Z')
      datetime.setHours(datetime.getHours()+5)
      return moment(datetime).format('LT');
    }
  }
}
</script>
<style scoped>
  img{
    object-fit: cover;
  }
</style>
