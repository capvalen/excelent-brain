<template>
<main>
    <div class="row">
      <div class="col-md-6 mt-4">
      <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header bg-dark py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-white">Gráfico de Ganacias del Mes por Profesional</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <select @change="getDataForCharts" name="" class="form-select" id="selectMonth">
                    <option disabled selected value>Seleccione un mes</option>
                    <option v-for="(month, index) in months" :key="index" :value="month">{{month | optionParseMonth}}</option>
                </select>
                <Pie
                  v-if="chartData.labels.length > 0"
                  :chart-options="chartOptions"
                  :chart-data="chartData"
                  :chart-id="chartId"
                  :dataset-id-key="datasetIdKey"
                  :plugins="plugins"
                  :css-classes="cssClasses"
                  :styles="styles"
                  :width="width"
                  :height="height"
                />
            </div>
          </div>
      </div>
    </div> 

    
</main>
</template>
<script>
import { Pie } from 'vue-chartjs/legacy'
import ChartDataLabels from 'chartjs-plugin-datalabels';
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement, CategoryScale } from 'chart.js'
import moment from 'moment'

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale, ChartDataLabels)

export default {
  name: 'PieChart',
  components: { Pie },
  props: {
    chartId: {
      type: String,
      default: 'bar-chart'
    },
    datasetIdKey: {
      type: String,
      default: 'label'
    },
    width: {
      type: Number,
      default: 500
    },
    height: {
      type: Number,
      default: 500
    },
    cssClasses: {
      default: '',
      type: String
    },
    styles: {
      type: Object,
      default: () => {}
    },
    plugins: {
      type: Array,
      default: () => [ChartDataLabels]
    }
  },
  data() {
    return {
      months:[],
      today: new Date(),
      chartData: {
        labels: [],
        datasets: [ { data: [],backgroundColor:  ['#4dc9f6','#f67019','#f53794','#537bc4','#acc236','#166a8f','#00a950','#58595b','#8549ba']}]
      },
      chartOptions: {
        responsive: false,
        plugins: {
            tooltip: {
                enabled:true
            },
            datalabels:{
                formatter:(value, context) =>{

                    const values = context.chart.data.datasets[0].data;

                    const datapoints = values.map(elem=> parseInt(elem, 10))

                    function totalSum(total, datapoint){
                        return total + datapoint
                    }

                    const totalPercentage = datapoints.reduce(totalSum,0)

                    return (value/totalPercentage * 100).toFixed(1)+'%'
                },
                color: '#fff',
                labels: {
                    title: {
                        font: {
                        weight: 'bold'
                        }
                    }
                }
                }
            }
        }
    }
  },
  methods:{
        setMonths(){
          this.months.push(moment(this.today).format('YYYY-MM'))
          while(moment(this.today).format('YYYY-MM') != '2022-01'){
          this.months.push(moment(this.today).add(-1,'months').format('YYYY-MM'))
          this.today = moment(this.today).add(-1, 'months') 
          }
        },
        async getDataForCharts(e) {
         this.chartData.labels = [],
         this.chartData.datasets[0].data = [] 
         this.axios.get(`/api/chart/${e.target.value}`)
        .then(res => {
          if(res.data.names.length == 0 || res.data.prices.length == 0){
            this.$swal('No se encontraron resultados para este mes')
          }else{
            this.chartData.labels = res.data.names,
            this.chartData.datasets[0].data = res.data.prices 
          } 
        })
        .catch(err => {
          console.error(err)
        })
        }
  },
  mounted(){
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