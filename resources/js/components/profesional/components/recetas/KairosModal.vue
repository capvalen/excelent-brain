<template>
    <div class="modal fade" id="kairosModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Kairos</h5>
                    <button type="button" id="close" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <form action="" @submit.prevent>
                        <div class="form-group">
                            <label for="name">Nombre Genérico</label>
                            <input type="text" class="form-control" autocomplete="off" v-model="kairo.name" placeholder="Nombre Genérico">
                        </div>
                        <div class="form-group">
                            <label for="name">Nombre Comercial</label>
                            <input type="text" class="form-control" autocomplete="off" v-model="kairo.tradename" placeholder="Nombre Comercial">
                        </div>
                        <div class="form-group">
                            <label for="name">Concentración</label>
                            <input type="text" class="form-control" autocomplete="off" v-model="kairo.concentration" placeholder="Concentración">
                        </div>
                        <div class="form-group">
                            <label for="name">Presentación</label>
                            <input type="text" class="form-control" autocomplete="off" v-model="kairo.presentation" name="name" id="name" placeholder="Presentación">
                        </div>

                        <div class="modal-footer">
                            <button type="submit" @click="insertKairos()" class="btn btn-primary" data-bs-dismiss="modal">Agregar</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

export default{
 data(){
     return{
         kairo:{
             name:'',
             tradename:'',
             concentration:'',
             presentation: ''
        }
     }

 },
 methods:{
     insertKairos(){
         this.axios.post('/api/kairos',this.kairo)
         .then((result) => {
             console.log(result)
             this.$swal('Medicamento agregado correctamente')
         }).catch((err) => {
             this.$swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Medicamento no agregado!',
                footer: 'Hay algún campo vacío. Vuelve a intentarlo'
              })
         });
     }
 }
}
</script>
