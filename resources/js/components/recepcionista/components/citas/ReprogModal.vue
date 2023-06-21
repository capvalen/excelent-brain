<template>
  <div class="modal fade" id="reprogModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Reprogramación de la Cita</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="" id="reproCita">
            <div class="form-group">
              <div class="form-group">
                <label for="">Razón</label>
                <textarea class="form-control" v-model="data.reschedule" name="reschedule" id="" rows="3"></textarea>
              </div>
            </div>
            
            <div class="form-group row">
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
                @change="emitSchedule()"
                >
              </div>
            </div>
               
            <div class="form-group row">
              <div class="col-sm-6">
                <label for="">Horario actual</label>
                <input 
                type="text" 
                class="form-control" 
                readonly 
                :value="`${ dataCit.schedule ? dataCit.schedule.check_time : '...'} - ${ dataCit.schedule ? dataCit.schedule.departure_date : '...'}`"
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
                    {{ hora.check_time }} - {{ hora.departure_date }}
                    </option>
                  </select>
                </div>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button @click="update()" class="btn btn-primary"  data-dismiss="modal">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { dateNow } from '../../../../helpers/Time.js'

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
      })
      .catch(err => {
        console.error(err)

      })
    },
    
    async update () {
      if ( document.getElementById('sltProfesionalHorarioID').value=='' ) {
        return this.$swal("Cita no reprogramada, el campo 'horario' esta vacio")
      }else{
				this.data.user_id = this.idUsuario;
				await this.axios.put(`/api/reprogramado/${this.dataCit.id}`, this.data)
				.then(res => { console.log(res.data);
					this.closeModal()
					this.$swal('Cita reprogramada con éxito')
					this.$parent.searchHistoria()
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
    }
  },
 
  
  computed : {
    repromingSchedule () {
      this.data = this.dataCit
      return this.data.reschedule = ''
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

  
