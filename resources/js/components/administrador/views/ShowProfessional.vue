<template>
  <main>
    <h2 class="admin-title">Profesional</h2>
    <div class="row">
          <div class="col-md-4 mt-4">
              <div class="card shadow" style="width: 100%;">
              <img class="card-img-top" :src="'/storage/'+profesional.photo" alt="Foto de Profesional" width="100%" height="250px">
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
                <h6 class="m-0 font-weight-bold text-white">Detalle de las consultas del mes</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <span>Consultas confirmadas: {{this.confirmed}}</span>
                <br>
                <span>Consultas sin confirmar: {{this.no_confirmed}}</span>
                <br>
                <span>Consultas canceladas: {{this.cancelled}}</span>
            </div>
          </div>
          <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header bg-dark py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-white">Pacientes atendidos</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="row">
                  <div class="col-sm">
                    <select @change="showSelect" class="form-select" id="optionTime">
                      <option disabled selected value>Seleccione una opción de tiempo</option>  
                      <option value="1">Mes</option>
                      <option value="2">Día</option>    
                    </select>
                  </div>
                  <div class="col-sm">
                    <select v-if="show !=0 && show==1" @change="getPatientsPerMonth" class="form-select" id="selectMonth">
                        <option disabled selected value>Seleccione un mes</option>
                        <option v-for="(month, index) in months" :key="index" :value="month">{{month | optionParseMonth}}</option>
                    </select>
                  </div>
                  <div class="col-sm">
                    <input v-if="show !=0 && show==2" @change="getPatientsPerDay" type="date" class="form-control" id="datePicker">
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm">
                   <p><b>Pacientes atendidos: </b> {{this.per_month}}</p>
                  </div>
                  <div class="col-sm">
                    <p><b>Total recaudado: S./ </b> {{this.total_price}}</p>
                  </div>
                </div>              
                
            </div>
          </div>
      </div>
    </div>
    <div class="row mt-4">
      <h2 class="admin-title">Consultas</h2>
      <table class="table">
        <thead class="thead-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Id cita</th>
            <th scope="col">Nombre y apellido</th>
            <th scope="col">Fecha</th>
            <th scope="col">Tipo de Consulta</th>
            <th scope="col">Monto</th>
            <th scope="col">Evolución</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(appointment, index) in appointments" :key="index">
            <th>{{ index + 1 }}</th>
            <th>{{ appointment.id }}</th>
            <td>{{ appointment.patient.name}}</td>
            <td>{{ appointment.date}}</td>
                <td v-if=" appointment.type === 1">Terapia Inicial niño/adolescente</td>
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
                <td v-else>Sin tipo de consulta</td>
            <td>{{appointment.payment ? `S/.${appointment.payment.price}` : 'Sin pago' }}</td>
            <td>
              <button
              v-if="appointment.patient.medical_evolutions[0] && appointment.patient.medical_evolutions[0].content === null" 
              class="btn btn-warning"
              data-toggle="modal"
              data-target="#evolutionModal" 
              @click="getEvolutions(appointment.patient.medical_evolutions[0])"
              >No rellenado</button>
              <button
              v-else-if="!appointment.patient.medical_evolutions[0]" 
              class="btn btn-danger"
              >No confirmado</button>
              <button
              v-else 
              class="btn btn-success"
              data-toggle="modal"
              data-target="#evolutionModal" 
              @click="getEvolutions(appointment.patient.medical_evolutions[0])"
              >Rellenado</button>
              <button v-if="appointment.patient.medical_evolutions[0]" @click="deleteEvolution(appointment.patient.medical_evolutions[0])" class="btn btn-danger">Eliminar</button>
              <p  v-if="!appointment.patient.medical_evolutions[0]" class="text-danger">Evolución no generada o eliminada</p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="row mt-4">
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
            <td>S./{{ extra_payment.price }}</td>
            <td>{{ extra_payment.observation }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <evolucion-modal v-if="evolutions" :dataEvolution="evolutions"></evolucion-modal>
  </main>
</template>

<script>

import EvolucionModal from './EvolucionesModal.vue';
import moment from 'moment'
export default {
  name: 'HomeProfesionales',

  components: { EvolucionModal },

  data () {
    return {
      cancelled:'',
      no_confirmed:'',
      confirmed:'',
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
      show: 0
    }
  },

  props: {
    dataEvolution: Array,
  },

  methods:{
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
        this.getAppointments()
        
      }).catch((err) => {
        console.error(err)
      });
    },

    async getAppointments(){
      this.$swal({
        title: 'Cargando...',
        showConfirmButton: false,
        icon:'info'
      })
      await this.axios.get(`/api/getProfApo/${this.profesional.id}`)
      .then((res) => {
        console.log(res.data)
        this.patients = res.data.patients,
        this.cancelled = res.data.cancelled,
        this.confirmed = res.data.confirmed,
        this.per_month = res.data.per_month,
        this.no_confirmed = res.data.no_confirmed
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
      this.axios.get(`/api/getPatientsPerMonth/${e.target.value}/${this.profesional.id}`)
      .then((res)=>{
        console.log(res.data)
        this.per_month = res.data.patientsPerMonth,
        this.appointments = res.data.appointments,
        this.appointments.forEach(appo => {
          this.total_price += parseFloat(appo.payment ? appo.payment.price : 0 )
        });
        this.extra_payments = res.data.extra_payments
        this.extra_payments.forEach(pay =>{
          this.total_price += parseFloat(!pay.price ? 0 : pay.price)
        });
        this.$swal.close()
      }).catch((err) => {
        console.error(err)
      })
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
      this.axios.get(`/api/getPatientsPerDay/${e.target.value}/${this.profesional.id}`)
      .then((res)=>{
        console.log(res.data)
        this.per_month = res.data.patientsPerMonth,
        this.appointments = res.data.appointments,
        this.appointments.forEach(appo => {
          this.total_price += parseFloat(appo.payment ? appo.payment.price : 0 )
        });
        this.extra_payments = res.data.extra_payments
        this.extra_payments.forEach(pay =>{
          this.total_price += parseFloat(!pay.price ? 0 : pay.price)
        });
        this.$swal.close()
      }).catch((err) => {
        console.error(err)
      })
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
      return moment(val).format('MMMM-YYYY')
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
