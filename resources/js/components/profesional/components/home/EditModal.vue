<template>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Datos del Profesional</h5>
                    <button type="button" id="close" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>

                <div class="modal-body">
                    <form action="" @submit.prevent="actualizar()">
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" name="name" id="name" v-model="professional.name" placeholder="Ingrese tu nombre">
                        </div>
                        <div class="form-group">
                            <label for="name">Profesión</label>
                            <input type="text" class="form-control" name="name" id="name" :value="professional.profession" placeholder="Ingrese tu profesión" readonly>
                        </div>
                        <div class="form-group">
                            <label for="name">Teléfono</label>
                            <input type="text" class="form-control" name="name" id="name" v-model="professional.phone" placeholder="Ingrese tu teléfono">
                        </div>
                        <div class="form-group">
                            <label for="name">Trayectoria</label>
                            <textarea rows="6" type="text" class="form-control" name="cv_description" id="cv_description" v-model="professional.cv_description" required>
                            </textarea>
                        </div>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  name: "modal-edit",
  data() {
    return {
      professional:this.prof
    }
  },
  props:{
      prof: Object
  },
  methods:{
      async actualizar(){
        await this.axios.put(`/api/professional/${this.prof.id}`,this.professional)
        .then(response => {
            console.log(response.data)
            this.closeModal()
            // this.$parent.getProfesionales()
        })
        .catch(error => {
            console.log(error)
        })
      },

      
      closeModal() {
        document.getElementById('close').click();
      },
  }
};
</script>
