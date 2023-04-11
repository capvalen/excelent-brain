<template>
    <main>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Profesionales</h1>
            <div class="d-flex align-items-center">
            </div>
        </div>
        <div class="d-sm-flex align-items-center justify-content-around mt-4">
            <form class="d-none d-sm-inline-block form-inline w-75">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button class="btn btn-success" type="button">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                    <input v-model="buscar" type="text" class="form-control bg-white shadow-sm border-0 small" placeholder="Buscar..."
                        aria-label="Search" aria-describedby="basic-addon2">
                </div>
            </form>
            <div class="d-flex justify-content-start">
                <button @click="llamarModal(profesor,'Agregar')" data-toggle="modal" data-target="#profModal" class="btn btn-success"><i class="fas fa-plus"></i> Agregar Profesional</button>
                <modal-profesional v-if="tipo" :prof="prof_edit" :type="tipo"></modal-profesional>
            </div>
        </div>
        <div class="row gx-4 mt-4">
            <div class="col-md-3 mb-3" v-for="(profesional) in filtro" :key="profesional.id">
                <card-profesional :prof="profesional"></card-profesional>
            </div>

        </div>
        <modal-horarios :prof="prof_edit" :horarios="horarios"></modal-horarios>
        <modal-acceso v-if="prof_edit" :prof="prof_edit"></modal-acceso>
    </main>
</template>
<script>
import CardProfesional from './CardProfesional.vue'
import ModalProfesional from './ModalProfesional.vue'
import ModalHorarios from './ModalHorarios.vue'
import ModalAcceso from './ModalAcceso.vue'
export default {
    components: { CardProfesional, ModalProfesional, ModalHorarios, ModalAcceso},
    name:'profesionales',
    data(){
        return{
            profesionals:[],
            prof_unico:{},
            prof_edit:{},
            buscar:'',
            tipo:'',
            horarios: []
        }
    },
    props:{
      prof: Object,
      profesor:{
          type: Object,
          default: () => ({})
      },
      type: String
    },
    computed:{
        filtro(){
            if(!this.buscar){
                return this.profesionals
            }else{
                return this.profesionals.filter(profesional => profesional.name.toLowerCase().includes(this.buscar))
            }
        }
    },
    methods:{
        async getProfesionales(){
            await this.axios.get('/api/professional')
            .then(response => {
                this.profesionals = response.data
                console.log(this.profesionals)
            })
            .catch(error => {
                console.log(error)
            })
        },
        llamarModal(profObj,tipo){
                this.tipo= tipo
                this.prof_edit = profObj
        },
        llamarModalHorarios(profObj){
                this.prof_edit = profObj
                this.getSchedules(this.prof_edit.id)
        },
        getSchedules(id_prof){
            this.axios.get(`/api/schedule/${id_prof}`)
            .then((res) => {
                this.horarios = res.data
            }).catch((err) => {

            });
        }
    },
    mounted(){
        this.getProfesionales()
    }
}
</script>
