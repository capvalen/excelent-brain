<template>
  <main>
    <div class="d-flex justify-content-between">
      <h3>Reporte Mensual</h3>
      <div class="d-flex">
        <select @change="getDataForReports" name="" class="form-control" id="selectMonth">
                    <option disabled selected value>Seleccione un mes</option>
                    <option v-for="(month, index) in months" :key="index" :value="month">{{month | optionParseMonth}}</option>
        </select>  
        <a :href="`/api/xlsx_admin/${actual_month}`" target="_blank" class="btn btn-success ml-2">Excel</a>
      </div>
    </div>
    
    <div class="overflow-auto">
      <table class="table table-striped mt-4 tableReport">
        <thead>
          <tr>
            <th>#</th>
            <th>Profesional</th>
            <th>Paciente</th>
            <th>Tipo de Paciente</th>
            <th>Fecha</th>
            <th>Comp. Emit.</th>
            <th>Medio de Pago</th>
            <th>I. Bancaria</th>
            <th>Ingresos</th>
            <th>Observacion</th>
          </tr>
        </thead>
  
        <tbody>
          <tr v-for="(report, index) in dataReports" :key="index">
            <th>{{ index +1 }}</th>
            <td :title="report.professional ? report.professional.name : ''" class="text-capitalize">{{ report.professional ? maxStringCharacter(lowerCase(report.professional.name), 15) : '' }}</td>
            <td :title="report.patient ? report.patient.name : ''" class="text-capitalize">{{ report.patient ? maxStringCharacter(lowerCase(report.patient.name), 15) : '' }}</td>
            <td>{{ report.patient_condition == 1 ? 'Nuevo' : 'Continuante' }}</td>
            <td>{{ report.date | dateParse }}</td>
            <td>{{ report.payment ? report.payment.voucher_issued : '' }}</td>
            <td>{{ report.payment ? report.payment.payment_method : '' }}</td>
            <td>{{ report.payment ? report.payment.bank : '' }}</td>
            <td>S/. {{ report.payment ? report.payment.price : '' }}</td>
            <td :title="report.payment ? report.payment.observation : ''" >{{ report.payment ? maxStringCharacter(report.payment.observation, 15) : '' }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</template>

<script>
import { dateNow } from '../../../../helpers/Time.js'
import moment from 'moment'
  export default {
    name: 'reportes',

    data () {
      return {
        dataReports: {},
        date: dateNow(),
        months:[],
        actual_month:''
      }
    },

    methods: {
      async getDataForReports (e) {
        this.actual_month = e.target.value
        await this.axios.get(`/api/reportsAppointments/${this.actual_month}`)
        .then(res => {
          console.log("resultados", res.data)
          this.dataReports = res.data;
        })
        .catch(err => {
          console.error(err)
        })
      },

      lowerCase (text) {
        return text.toLowerCase();
      },

      horaHumanaMin (hora) {
        let minutos = parseInt(hora.substring(3,5))
        hora = parseInt(hora.substring(0,2))
        if (hora > 12) {
            return `${hora - 12}:${minutos.toString().length === 1 ? '0' + minutos : minutos} PM`
        } else {
          if (hora === 12 && minutos >= 0) {
              return `${hora}:${minutos.toString().length === 1 ? '0' + minutos : minutos} PM`
          }

          if (hora === 0) {
              return `12:${minutos.toString().length === 1 ? '0' + minutos : minutos} AM`
          }
          return `${hora}:${minutos.toString().length === 1 ? '0' + minutos : minutos} AM`
        }
      },

      maxStringCharacter (character, num) {
        character === null ? character = '...' : character
        if (character.length > num) {
          return character.substring(0, num) + '...';
        } else {
          return character;
        }
      },

      setMonths(){
      this.months.push(moment(this.today).format('YYYY-MM'))
      while(moment(this.today).format('YYYY-MM') != '2022-01'){
       this.months.push(moment(this.today).add(-1,'months').format('YYYY-MM'))
       this.today = moment(this.today).add(-1, 'months') 
      }
      }
    },

    created () {
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
  .tableReport {
    min-width: 1200px;
  }

</style>