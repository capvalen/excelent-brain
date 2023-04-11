<template>
  <div class="w-100">
    <h2 class="admin-title">Profesionales</h2>
    <div class="display-grid mt-4">
      <div
        class="grid-item shadow-sm bg-white rounded"
        v-for="(profesional, index) in profesionales"
        :key="index"
      >
        <img :src="'/storage/'+profesional.photo" alt="Foto de Profesional">
        <div class="px-3 card">
          <span class="d-block name-profesional">{{ profesional.name }}</span>
          <span class="d-block description-profesional mb-3">{{ profesional.profession }}</span>
          <router-link :to="{name:'showAdminProf',params:{profId:profesional.user_id}}" class="btn btn-primary d-block"><i class="fas fa-eye"></i> Ver profesional</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HomeProfesionales',
  data () {
    return {
      profesionales: []
    }
  },
  methods:{
      getProfesionales(){
          this.axios.get('/api/professional')
          .then((result) => {
            console.log(result)
            this.profesionales = result.data
          }).catch((err) => {

          });
      }
  },
  mounted(){
      this.getProfesionales()
  }
}
</script>

<style scoped>
  .admin-title {
    font-weight: 600;
    font-size: 25px;
  }

  .name-profesional {
    font-weight: 600;
  }

  .display-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    grid-gap: 25px;
  }

  .grid-item {
    display: grid;
    justify-items: flex-start;
    grid-template-rows: 150px 1fr;
    grid-gap: 15px;
  }

  .display-grid img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .card {
    border: 0;
    padding-bottom: 15px;
  }
</style>
