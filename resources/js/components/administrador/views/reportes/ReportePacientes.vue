<template>
<main>
  <select @change="getTotalPatients" name="" class="form-control" id="selectMonth">
    <option disabled selected value>Seleccione un mes</option>
    <option v-for="(month, index) in months" :key="index" :value="month">{{month | optionParseMonth}}</option>
  </select>  
 <table class="table table-striped">
    <thead>
      <tr>
        <th>#</th>
        <th>Nombre y apellido</th>
        <th>Nro atentido</th>
      </tr>
    </thead>

    <tbody>
      <tr v-for="(data, index) in dataPatients" :key="index">
        <td>{{ index + 1 }}</td>
        <td>{{ data.name }}</td>
        <td>{{ data.cant }}</td>
      </tr>
      <tr>
        <td></td>
        <th>Nro total de pacientes atendidos</th>
        <th>{{ total }}</th>
      </tr>
    </tbody>
  </table>
</main>  
</template>

<script>
import moment from 'moment'

export default {
  name: 'ReportePacientes',

  data () {
    return {
      dataPatients: {},
      total: null,
      months:[],
      today: new Date()
    }
  },

  methods: {
    async getTotalPatients(e) {
      this.$swal({
        title: 'Cargando...',
        showConfirmButton: false,
        icon:'info'
      })
      this.actual_month = e.target.value
      await this.axios.get(`/api/getPatientsMonth/${this.actual_month}`)
      .then(res => {
        this.dataPatients = res.data.pacientes;
        this.total = res.data.totalPacientes;
        this.$swal.close()
      })
      .catch(err => {
        console.error(err);
      })
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
    this.setMonths();
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