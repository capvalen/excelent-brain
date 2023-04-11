<template>
    <div class="modal fade" id="recetaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{type}} Medicamento {{this.med.name}}</h5>
                    <button type="button" id="close" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <form action="" @submit.prevent>
                        <div class="form-group">
                            <label for="name">Vía</label>
                            <input type="text" class="form-control" autocomplete="off" v-model="med.way" id="name" placeholder="Vía">
                        </div>
                        <div class="form-group">
                            <label for="name">Indicaciones</label>
                            <textarea name="" id="" class="form-control" autocomplete="off" v-model="med.indications" cols="30" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="name">Cantidad</label>
                            <input type="text" class="form-control" autocomplete="off" v-model="med.amount" name="name" id="name" placeholder="Cantidad">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button v-if="type == 'Editar'" @click="updateSelected()" class="btn btn-primary" data-bs-dismiss="modal">Guardar cambios</button>
                            <button v-else type="submit" @click="insertMedic()" class="btn btn-primary" data-bs-dismiss="modal">Agregar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            medicamento:{
                name: this.med.name,
                way: this.med.way,
                indications: this.med.indications,
                amount: this.med.amount,
                kairo_id : this.med.kairo_id
            }
        }
    },
    props:{
        med: Object,
        type: String
    },
    methods: {
        insertMedic(){
            this.$parent.pushSelected(this.med)
            this.cleanModal()
        },
        cleanModal(){
            this.medicamento.name = '',
            this.medicamento.way = '',
            this.medicamento.indications = '',
            this.medicamento.amount = ''
        },
        updateSelected(){
            this.cleanModal()
        }
    },
    mounted() {
        console.log(this.med)
    }
}
</script>

