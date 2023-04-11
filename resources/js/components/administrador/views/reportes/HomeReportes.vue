<template>
  <main>
    <div class="admin-cards mt-3">
      <div class="d-flex flex-column align-items-center rounded p-4 admin-cards__card shadow-sm bg-white">
        <span class="font-weight-bold admin-cards__span-cant">{{ cantTotalPacientes }}</span>
        <span class="font-weight-light admin-cards__text text-center mt-2">Nro de pacientes del mes</span>
      </div>

      <div class="d-flex flex-column align-items-center rounded p-4 admin-cards__card shadow-sm bg-white">
        <span class="font-weight-light admin-cards__text text-center mt-2">Medicamentos más recetados</span>
        <router-link to="/administrador/reportes/medicamentos" class="btn btn-secondary admin-cards__butons">Reporte</router-link>
      </div>

      <div class="d-flex flex-column align-items-center rounded p-4 admin-cards__card shadow-sm bg-white">
        <span class="font-weight-light admin-cards__text text-center mt-2">Reporte de pacientes</span>
        <router-link to="/administrador/reportes/pacientes" class="btn btn-secondary admin-cards__butons">Reporte</router-link>
      </div>

      <div class="d-flex flex-column align-items-center rounded p-4 admin-cards__card shadow-sm bg-white">
        <span class="font-weight-light admin-cards__text text-center mt-2">Reporte Mensual General</span>
        <router-link to="/administrador/reportes/mensual" class="btn btn-secondary admin-cards__butons">Ingresar</router-link>
      </div>

      <div class="d-flex flex-column align-items-center rounded p-4 admin-cards__card shadow-sm bg-white">
        <span class="font-weight-light admin-cards__text text-center mt-2">Gráfica Mensual General</span>
        <router-link to="/administrador/reportes/chart" class="btn btn-secondary admin-cards__butons">Ingresar</router-link>
      </div>

    </div>
  </main>
</template>

<script>
import ReporteMedicamentos from './ReporteMedicamentos.vue';

export default {
  name: 'HomeReportes',
  
  components: {ReporteMedicamentos},

  data () {
    return {
      cantTotalPacientes: null,
    }
  },

  methods: {
    async getTotalPatients () {
      await this.axios.get('/api/returnTotalPatients')
      .then(res => {
        this.cantTotalPacientes = res.data;
      })
      .catch(err => {
        console.err(err)
      })
    }
  },

  created () {
    this.getTotalPatients()
  }
}
</script>

<style scoped>
  .admin-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    grid-gap: 25px;
  }

  .admin-cards__text {
    font-size: 20px;
  }

  .admin-cards__span-cant {
    font-size: 30px;
  }

  .admin-cards__butons {
    width:  50%;
    min-width: 150px;
  }
</style>