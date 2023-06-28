<template>
    <div class="modal fade" id="modalAdicionales" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Agregar Adicional</h5>
            <button type="button" id="cerrModal" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
  
          <div class="modal-body">
            <form @submit.prevent>
              <div class="form-group row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="customer">Paciente</label>
                    <input type="text" name="customer" required id="customer" class="form-control"
                      v-model="form.name">
                  </div>
                </div>
  
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="price">Número</label>
                    <input type="number" name="price" required id="price" class="form-control" v-model="form.number">
                  </div>
                </div>
              </div>
  
              <div class="form-group row">
                <div class="col-sm-6">
                  <label for="type">Profesional</label>
                  <select 
                  class="form-select" 
                  name="professional_id" 
                  id="professional_id"  
                  v-model="form.professional_id"
                  >
                    <option value="" selected disabled>Selecciona un profesional</option>
                    <option 
                    v-for="profesional in profesionales" 
                    :key="profesional.id" 
                    :value="profesional.id" 
                    >
                    {{profesional.name}}
                    </option>
                  </select>
                </div>
  
                <div class="col-sm-6">
                </div>
              </div>
  
                <div class="form-group">
                  <label for="name">Detalle de horario</label>
                  <textarea name="observation" class="form-control" id="observation" rows="5"
                    v-model="form.schedule"></textarea>
                </div>
  
              <div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button @click="saveData" type="submit" class="btn btn-primary" data-bs-dismiss="modal">Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </template>

<script>
export default {
  name: 'ModalAdicionales',

  data() {
    return {
      form: {
        name: null,
        number: null,
        professional_id: null,
        schedule: null
      },
      profesionales:[],
      aditionals:[]
    }
  },

  methods: {
    getProfessionals(){
      this.axios.get('/api/profesional')
      .then(res =>{
        this.profesionales = res.data
      })
    },
    async saveData() {
      if (!this.form.name || !this.form.number || !this.form.professional_id || !this.form.schedule)
        return this.$swal("Datos incompletos")

      this.$swal("Guardando datos")

      this.axios.post('/api/saveAditional', this.form)
        .then(res => {
          for (let value in this.form) {
            this.form[value] = ''
          }

          this.$swal('Adicional Insertado')
          this.$emit('getAllAditionals')
        })
        .catch(err => {
          this.$swal('Hubo un error')
        })
    }
  },
  mounted(){
    this.getProfessionals()
  }
}
</script>