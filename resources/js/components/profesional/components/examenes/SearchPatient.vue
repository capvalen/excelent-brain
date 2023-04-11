<template>
  <div>
    <label for="">Nombre del Paciente</label>
        
    <input type="text" name="name" autocomplete="off" placeholder="Escribe tu nombre en minúsculas" v-model="buscar" id="name-patient" class="form-control input-name">
    
    <div v-if="patients" v-show="showResults" class="border border-secondary shadow w-100">
        <div 
        v-for="patient in filtro" 
        class="border border-secondary" 
        @click="selectPatient(patient)" 
        :key="patient.id">
        {{ patient.name }}
        </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    // patients: Array
  },

  data() {
    return {
      patients: null,
      buscar: '',
      showResults:false,
    }
  },

  methods: {
    getNames(){
      this.axios.get('/api/getNames')
      .then((result) => {
        this.patients = result.data
      }).catch((err) => {
        console.error(err)
      });
    },
    
    selectPatient(patient){
      this.$swal('Usuario aceptado')
      this.$emit('selectPatient', patient.id)
      this.buscar = patient.name
      this.showResults = false
    },
  },

  computed: {
    filtro (){
      if(!this.buscar){
        this.showResults = false
        return null
      }else{
        this.showResults = true
        return this.patients.filter(patient => patient.name.toLowerCase().includes(this.buscar)).slice(0,6)
      }
    }
  },

  created() {
    this.getNames()
  }
}
</script>