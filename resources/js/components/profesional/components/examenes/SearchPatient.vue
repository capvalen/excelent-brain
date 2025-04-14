<template>
  <div>
    <label for="name-patient">Nombre del Paciente</label>
        
    <input 
      type="text" 
      name="name" 
      autocomplete="off" 
      placeholder="Escribe el nombre del paciente" 
      v-model="buscar" 
      id="name-patient" 
      class="form-control input-name"
    >
    
    <!-- Indicador de carga -->
    <div v-if="isLoading" class="mt-2">
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      <span class="ml-2">Cargando...</span>
    </div>
    
    <div v-if="patients && showResults" class="border border-secondary shadow w-100">
      <div 
        v-for="patient in filtro" 
        class="border border-secondary p-2 patient-item" 
        @click.stop="handlePatientClick(patient)" 
        :key="patient.id">
        {{ patient.name }} {{ patient.nombres }} 
      </div>
      <div v-if="buscar && (!filtro || filtro.length === 0)" class="p-3 text-center">
        <span>No se encontraron pacientes con ese nombre</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    initialPatients: {
      type: Array,
      default: () => []
    },
    maxResults: {
      type: Number,
      default: 6
    }
  },

  data() {
    return {
      patients: this.initialPatients.length ? this.initialPatients : null,
      buscar: '',
      showResults: false,
      isLoading: false,
      processingSelection: false
    }
  },

  methods: {
    getNames() {
      this.isLoading = true;
      
      this.axios.get('/api/getNames')
        .then((result) => {
          this.patients = result.data;
        })
        .catch((err) => {
          console.error(err);
          this.$swal({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron cargar los pacientes'
          });
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
    
    handlePatientClick(patient) {
      if (this.processingSelection) return;
      
      this.processingSelection = true;
      this.showResults = false;
      
      // Actualizar el campo de búsqueda primero
      this.buscar = `${patient.name} ${patient.nombres}`;
      
      // Luego notificar y emitir evento
      this.$swal('Usuario aceptado');
      this.$emit('selectPatient', patient.id);
      
      // Asegurar que la bandera se reinicie después de un tiempo
      setTimeout(() => {
        this.processingSelection = false;
      }, 300);
    }
  },

  watch: {
    buscar(newValue, oldValue) {
      // Solo activar el filtro si el usuario está escribiendo
      // y no cuando se está estableciendo el valor desde handlePatientClick
      if (!this.processingSelection) {
        if (newValue) {
          this.showResults = true;
        } else {
          this.showResults = false;
        }
      }
    }
  },

  computed: {
    filtro() {
      if (!this.buscar || !this.patients) {
        return [];
      }
      
      return this.patients
        .filter(patient => 
          `${patient.name} ${patient.nombres}`
            .toLowerCase()
            .includes(this.buscar.toLowerCase())
        )
        .slice(0, this.maxResults || 6);
    }
  },

  created() {
    if (!this.initialPatients || this.initialPatients.length === 0) {
      this.getNames();
    }
  }
}
</script>

<style scoped>
.patient-item {
  cursor: pointer;
}

.patient-item:hover {
  background-color: #f5f5f5;
}
</style>