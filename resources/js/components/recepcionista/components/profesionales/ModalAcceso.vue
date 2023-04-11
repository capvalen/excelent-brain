<template>
    <div class="modal fade" id="accessModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Accesos de {{prof.name}}</h5>
      <button type="button" id="closeModalAccess" class="btn btn-danger" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>
        <form @submit="updateAccesos">
            <div class="modal-body">
                    <div class="form-group">
                        <label for="email">Correo:</label>
                        <input type="text" id="email" v-model="prof.email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña nueva:</label>
                        <input type="password" id="password" v-model="prof.password" class="form-control" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" @click="limpiarInputs()" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Editar Accesos</button>
            </div>
        </form>
    </div>
  </div>
</div>
</template>
<script>
export default {
    data(){
        return {
            password : ''
        }
    },
    props:{
        prof: Object
    },
    methods:{
        updateAccesos(e){
            e.preventDefault()
            this.axios.put('/api/user/'+this.prof.user_id, this.prof)
            .then(res => {
                this.closeModal()
                this.$swal('Acceso actualizado con éxito')
                this.limpiarInputs()
            }).catch((err) => {
                console.log(err)
            });
        },
        
        closeModal() {
            document.getElementById('closeModalAccess').click();
        },

        limpiarInputs () {
            document.getElementById('password').value = ''
        },
    }
}
</script>
