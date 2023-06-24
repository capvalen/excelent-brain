<template>
<div class="modal fade bd-example-modal-lg" id="horarioModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Horarios de {{prof.name}}</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div v-if="this.mostrarHorarios" class="col-md-4 d-flex flex-column">
                <button v-on:click="day='Lunes'" class="btn btn-success mt-3">Lunes</button>
                <button v-on:click="day='Martes'" class="btn btn-success mt-3">Martes</button>
                <button v-on:click="day='Miercoles'" class="btn btn-success mt-3">Miércoles</button>
                <button v-on:click="day='Jueves'" class="btn btn-success mt-3">Jueves</button>
                <button v-on:click="day='Viernes'" class="btn btn-success mt-3">Viernes</button>
                <button v-on:click="day='Sabado'" class="btn btn-success mt-3">Sábado</button>
            </div>
            <div :class="col">
                <div v-if="this.mostrarForm" class="card rounded border border-primary p-4">
                    <div class="card-head d-flex justify-content-between">
                        <h5 class="text-success font-weight-bold">{{title}} horario</h5>
                        <button @click="showHorarios" class="btn btn-success">Ver Horarios</button>
                    </div>
                    <div class="schudles mt-3">
                        <form @submit.prevent>
                            <label v-if="title=='Agregar'" for="">Días</label>
                            <div v-if="title=='Agregar'" class="form-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="horarioLunes" value="Lunes" v-model="schedule.daysSelected">
                                    <label class="form-check-label" for="horarioLunes">Lunes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="horarioMartes" value="Martes" v-model="schedule.daysSelected">
                                    <label class="form-check-label" for="horarioMartes">Martes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="horarioMiercoles" value="Miercoles" v-model="schedule.daysSelected">
                                    <label class="form-check-label" for="horarioMiercoles">Miercoles</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="horarioJueves" value="Jueves" v-model="schedule.daysSelected">
                                    <label class="form-check-label" for="horarioJueves">Jueves</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="horarioViernes" value="Viernes" v-model="schedule.daysSelected">
                                    <label class="form-check-label" for="horarioViernes">Viernes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="horarioSabado" value="Sabado" v-model="schedule.daysSelected">
                                    <label class="form-check-label" for="horarioSabado">Sábado</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Hora de Inicio</label>
                                <input class="form-control" type="time" v-model="schedule.check_time" required>
                            </div>
                            <div class="form-group">
                                <label for="">Hora de Salida</label>
                                <input class="form-control" type="time" v-model="schedule.departure_date" name="" required>
                            </div>
                            <div class="form-group">
                                <button v-if="title=='Agregar'" @click="insertSchedule" class="btn btn-success">Registrar horario</button>
                                <button v-else @click="updateSchedule" class="btn btn-success">Actualizar horario</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div v-else-if="this.mostrarHorarios" class="card rounded border border-primary p-4">
                    <div class="card-head d-flex justify-content-between">
                        <h5 class="text-success font-weight-bold">{{day}}</h5>
                        <button @click="showForm" class="btn btn-success">Agregar Horario</button>
                    </div>
                    <div v-for="horario in filtro" :key="horario.id" class="schudles mt-3">
                        <div v-if="horario.day == day" class="btn btn-success w-100 mt-2 d-flex justify-content-between">
                            <p>{{ horaHumana(horario ? horario.check_time : '...')}} - {{ horaHumana(horario ? horario.departure_date : '...')}}</p>
                            <div class="div">
                                <a @click="editSchedule(horario)" class="btn btn-info btn-circle"><i class="fas fa-pencil-alt"></i></a>
                                <a @click="deleteSchedule(horario.id)" class="btn btn-danger btn-circle"><i class="fas fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>
<script>
export default {
    data(){
        return{
            mostrarForm: false,
            mostrarHorarios: true,
            col:'col-md-8',
            schedule:{
                id:'',
                daysSelected:[],
                check_time: null,
                departure_date: null,
                professional_id: ''
            },
            day:'Lunes',
            title:''
        }
    },

    props:{
        prof: Object,
        horarios: Array
    },
    
    methods:{
        showForm(){
            this.mostrarForm = true
            this.mostrarHorarios = false
            this.col='col-md-12',
            this.title = 'Agregar'
        },
        
        showHorarios(){
            this.mostrarForm = false
            this.mostrarHorarios = true
            this.col='col-md-8'
        },

        insertSchedule(){
            this.schedule.professional_id = this.prof.id
            this.axios.post('/api/schedule', this.schedule)
            .then((result) => {
                if(result.data.mensaje == 'Exito'){
                    this.$swal('Horario insertado')
                }else{
                    this.$swal({icon: 'error', title: 'El horario insertado se cruzaba con otro'})
                }
                this.$parent.getSchedules(this.prof.id)
                this.showHorarios()
                this.cleanModal()
            }).catch((err) => {

            });
        },

        cleanModal(){
                this.schedule.daysSelected=[],
                this.schedule.check_time= null,
                this.schedule.departure_date= null
        },

        editSchedule(horario){
            this.showForm()
            this.schedule.check_time= horario.check_time,
            this.schedule.departure_date= horario.departure_date,
            this.schedule.daysSelected.push(horario.day)
            this.title = 'Editar'
            this.schedule.id = horario.id
            console.log(horario)
        },

        updateSchedule(){
            this.axios.put('/api/schedule/'+this.schedule.id, this.schedule)
            .then((result) => {
                console.log(result)
                this.showHorarios()
                this.cleanModal()
                this.$swal('Horario actualizado con exito')
            })
        },

        deleteSchedule(key){
            this.$swal({
                title: 'Quieres eliminar este horario?',
                showDenyButton: true,
                confirmButtonText: 'Si',
                denyButtonText: `No`,
            }).then((result) => {
                if(result.isConfirmed){
                    this.axios.delete('/api/schedule/'+key)
                    .then((res) => {
                       console.log(res.data)
                       this.$swal('Horario eliminado con éxito')
                    });
                    this.$parent.getSchedules(this.prof.id)
                }
            })
        },

        horaHumana (hora) {
            let minutos = parseInt(hora.substring(3,5))
            hora = parseInt(hora.substring(0,2))
            if (hora > 12) {
                return `${hora - 12}:${minutos.toString().length === 1 ? '0' + minutos : minutos} PM`
            } else {
                if (hora === 12 && minutos >= 0) {
                    return `${hora}:${minutos.toString().length === 1 ? '0' + minutos : minutos} PM`
                }

                if (hora === 0) {
                    return `12:${minutos.toString().length === 1 ? '0' + minutos : minutos} AM`
                }
                return `${hora}:${minutos.toString().length === 1 ? '0' + minutos : minutos} AM`
            }
        },
    },

    computed:{
        filtro(){
            return this.horarios.filter(horario => horario.day==this.day)
        }
    }
}
</script>
