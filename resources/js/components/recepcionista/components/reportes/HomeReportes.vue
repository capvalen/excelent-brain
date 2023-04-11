<template>
  <main>
    <div class="d-flex justify-content-between">
      <h3>Reporte</h3>
      <div class="d-flex">  
        <input type="date" @change="getDataForReports()" id="dateReport" class="form-control" v-model="date">
        <a :href="`/api/xlsx_recep/${date}`" target="_blank" class="btn btn-success ml-2">Excel</a>
      </div>
    </div>
    
    <div class="overflow-auto">
      <table class="table table-striped mt-4 tableReport">
        <thead>
          <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>FAC/BOL/CONT</th>
            <th>Nombres y apellidos</th>
            <th>N/C</th>
            <th>Ingresos</th>
            <th>Observacion</th>
            <th>Tipo de cuenta</th>
            <th>Profesional</th>
          </tr>
        </thead>
  
        <tbody>
          <tr v-for="(report, index) in dataReports" :key="index">
            <th>{{ index }}</th>
            <td>{{ report.date }}</td>
            <td>{{ report.payment ? report.payment.voucher : '' }}</td>
            <td :title="report.patient ? report.patient.name : ''" class="text-capitalize">{{ report.patient ? maxStringCharacter(lowerCase(report.patient.name), 15) : '' }}</td>
            <td>{{ report.patient_condition == 1 ? 'N' : 'C' }}</td>
            <td>S/. {{ report.payment ? report.payment.price : '' }}</td>
            <td :title="report.payment ? report.payment.observation : ''" >{{ report.payment ? maxStringCharacter(report.payment.observation, 15) : '' }}</td>
            <td>{{ report.payment ? report.payment.bank : '' }}</td>
            <td :title="report.professional ? report.professional.name : ''" class="text-capitalize">{{ report.professional ? maxStringCharacter(lowerCase(report.professional.name), 15) : '' }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</template>

<script>
import { dateNow } from '../../../../helpers/Time.js'

  export default {
    name: 'reportes',

    data () {
      return {
        dataReports: {},
        date: dateNow()
      }
    },

    methods: {
      async getDataForReports () {
        let date = document.getElementById("dateReport") ? document.getElementById("dateReport").value : dateNow();
        await this.axios.get(`/api/reportsAppointments/${date}`)
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
      }
    },

    created () {
      this.getDataForReports()
    }
  }
</script>

<style scoped>
  .tableReport {
    min-width: 1200px;
  }

</style>