<template>
  <div class="modal fade" id="reprogModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="exampleModalLabel">Reprogramación de la Cita</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body border-0">
          <div class="row">
            <div class="col-12">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="radioTipo" id="exampleRadios1" value="reprogramar"  v-model="caso">
                <label class="form-check-label" for="exampleRadios1">
                  Reprogramación de cita
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="radioTipo" id="exampleRadios2" value="limbo" v-model="caso">
                <label class="form-check-label" for="exampleRadios2">
                  Enviar al limbo
                </label>
              </div>
            </div>
          </div>
          <form action="" id="reproCita" @submit.prevent="onSubmit">
            <div class="form-group mt-3">
              <div class="form-group">
                <label v-if="caso=='reprogramar'">Motivo de reprogramación</label>
                <label v-if="caso!='reprogramar'">Motivo para enviar al limbo</label>
                <textarea class="form-control" v-model="data.reschedule" name="reschedule" id="" rows="2"></textarea>
              </div>
            </div>
            
            <div class="form-group row" v-if="caso=='reprogramar'">
              <div class="col-sm-6">
                <label for="">Profesional</label>                                  
                <select 
                class="form-select value-professional" 
                name="professional_id" 
                id="sltProfesional" 
                placeholder="Horario del profesional"
                v-model="data.professional_id"
                @change="listarhorario()" 
                >
                  <option value="" disabled selected>Selecciona un profesional</option>
                  <option 
                  v-for="(professional, index) in professionals"
                  :key="index" 
                  :value="professional.id" 
                  >
                  {{professional.name}}
                  </option>
                </select>
              </div>

              <div class="col-sm-6">
                <label for="name">Fecha de cita</label>
                <input 
                type="date" 
                class="form-control emit-fecha" 
                name="date" 
                id="date" 
                v-model="data.date"
                :min="getDateNow()"
                @change="listarhorario()"
                >
              </div>
            </div>
               
            <div class="form-group row" v-if="caso=='reprogramar'">
              <div class="col-sm-6">
                <label for="">Horario actual</label>
                <input 
                type="text" 
                class="form-control" 
                readonly 
                :value="`${ dataCit.schedule ? horaLatam1(dataCit.schedule.check_time) : '...'} - ${ dataCit.schedule ? horaLatam2(dataCit.schedule.departure_date) : '...'}`"
                >
              </div>
              
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Horario del profesional</label>
                  <select class="form-select" name="sltProfesionalHorarioID" id="sltProfesionalHorarioID" v-model="dataCit.schedule_id">
                    <option value="" disabled selected>Selecciona un horario</option>
                    <option 
                    v-for="hora in horarios" :key="hora.id" 
                    v-if="hora" 
                    :value="hora.id"
                    >
                    {{ horaLatam1(hora.check_time) }} - {{ horaLatam2(hora.departure_date) }}
                    </option>
                  </select>
                </div>
              </div>
            </div>

            <div class="modal-footer pt-0 border-0">
              <button @click="update($event)" class="btn btn-outline-primary" data-bs-dismiss="modal"><i class="fa-regular fa-floppy-disk"></i> 
                <span v-if="caso=='reprogramar'">Reprogramar</span>
                <span v-if="caso!='reprogramar'">Enviar a limbo</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { dateNow } from '../../../../helpers/Time.js'
import alertify from 'alertifyjs'
import moment from 'moment'

export default {
  name: "modal-reprogramacion",

  data() {
    return{
      data: null,
      professionals: {},
      horarios: {},
      horariosAll: {},
      hoursProfessional: [],
      schedulesInvalid: [],
      caso: 'reprogramar'
    }
  },
  props:{
    dataCit:Object, idUsuario:null
  },  

  methods: {
    async getProfessional () {
      await this.axios.get(`/api/professional`)
      .then(res => {
        this.professionals = res.data
				this.professionals.sort()
      })
      .catch(err => {
        console.error(err)

      })
    },
    
    async update ($event) {			
      $event.preventDefault();
      if(this.caso=='reprogramar') this.reprogramar()
      else this.irALimbo()
    },
    irALimbo(){
      if( this.data.reschedule =='' ){
				alertify.notify('Debe haber un motivo de reprogramación', 'danger', 10)
        return;
      }

      this.axios.post('/api/limbos',{
        appointment_id: this.dataCit.id,
        user_id: this.idUsuario,
        motivo: this.data.reschedule
      }).then(res => { //console.log(res.data);
					this.closeModal()
					this.$emit('ocultarCita')
					this.$swal({icon:'sucess', title: 'Cita reprogramada con éxito'})
					//this.$parent.searchHistoria()
				})
    },

    async reprogramar(){
      if ( document.getElementById('sltProfesionalHorarioID').value=='' ) 
				alertify.notify('El horario no puede estar vacío', 'danger', 10)
			else if( this.data.reschedule =='' )
				alertify.notify('Debe haber un motivo de reprogramación', 'danger', 10)
      else{
				this.data.user_id = this.idUsuario;
				await this.axios.put(`/api/reprogramado/${this.dataCit.id}`, this.data)
				.then(res => { console.log(res.data);
					this.closeModal()
					this.$emit('ocultarCita')
					this.$swal({icon:'sucess', title: 'Cita reprogramada con éxito'})
					//this.$parent.searchHistoria()
				})
				.catch(err => {
					console.error(err)
				})
			}
    },

    async listarhorario () {
      let id = this.data.professional_id
      
      await this.axios.get(`/api/horario/${id}`)
      .then(res => { 
        this.horarios = res.data.schedulesInvalid;       
        this.horariosAll = res.data.schedules;       
        this.hoursProfessional = this.horarios;

        this.emitSchedule(document.querySelector(".emit-fecha").value);

        this.schedulesInvalid = []
        this.hoursProfessional.forEach(el => {
          this.schedulesInvalid.push(el.schedule_id)
        });
      }) 
      .catch(err => {
        console.error(err)
      })
    },

    emitSchedule (info = document.querySelector(".emit-fecha").value) {
      this.horarios = []

      let arraySchedulesInvalid = []
      this.hoursProfessional.forEach(el => {
        if (!arraySchedulesInvalid.includes(el.schedule_id)) {
          arraySchedulesInvalid.push(el.schedule_id)
        }
      })

      this.horariosAll.forEach(el => {
        if (el.day === this.dayWeek(new Date(info).getDay())) {
          if (arraySchedulesInvalid.includes(el.id)) {
            // Hay cita
            //console.log("Hya citas")

            //console.log(el.appointments.forEach(el => console.log(el.date, info, el.status)))
            if (el.appointments.find(el => el.date === info && el.status != 3) ? true : false) {
              
            } else {
              this.horarios.push(el)
            }
          } else {
            //console.log("No Hya citas")
            // No hay cita
            this.horarios.push(el)
          }
        }
      })
    },
    
    closeModal() {
      document.getElementById('cerrModal').click();
    },

    dayWeek (day) {
      switch (day) {
        case 0: return "Lunes"; break;
        case 1: return "Martes"; break;
        case 2: return "Miercoles"; break;
        case 3: return "Jueves"; break;
        case 4: return "Viernes"; break;
        case 5: return "Sabado"; break;
        case 6: return "Domingo"; break;
      }
    },

    getDateNow () {
      return dateNow()
    },
		horaLatam1(horita){ return moment(horita, 'HH:mm:ss').format('hh:mm') },
		horaLatam2(horita){ return moment(horita, 'HH:mm:ss').format('hh:mm a') },
  },
 
  
  computed : {
    repromingSchedule () {
      this.data = this.dataCit
      return this.data.reschedule = ''
    }
  },
	watch:{
		data(){
			this.listarhorario();
		}
	},

  created () {
    this.repromingSchedule
    this.getProfessional()
  },  

  mounted () {
    this.listarhorario()
  },
  
  updated () {
    this.repromingSchedule
  }
}
</script>

  
