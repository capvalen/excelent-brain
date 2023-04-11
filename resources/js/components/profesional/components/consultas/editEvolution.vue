<template>
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="modalEvolution" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="infoModalLabel">Editar evolución {{datosModal.id}}</h5>
            <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
          </div>

          <div class="modal-body">
            <form @submit.prevent >

              <div class="form-group">
                <label for="comment">Comentario
                </label>
                <textarea class="form-control" id="comment" cols="30" rows="10" v-model="datosModal.content"></textarea>
              </div>
              <div class="modal-footer">
                  <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                  <button @click="editEvolution" type="submit" class="btn btn-danger" data-dismiss="modal">Editar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
    export default {
        name: 'editEvolution',
        data(){
            return{

            }
        },
        props: {
            datosModal: Object
        },
        methods:{
            editEvolution(){
                this.axios.post('/api/editEvolution', this.datosModal)
                .then((res)=>{
                    this.$swal(res.data.msg)
                })
            }
        }
    }
</script>