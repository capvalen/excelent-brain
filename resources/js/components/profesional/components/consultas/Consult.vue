<template>
  <div class="consulta d-flex align-items-center w-100 justify-content-around mb-2" > <!-- v-if="dataConsult.status!=3" -->
      <div class="h5 mb-0 mx-1">{{ dataConsult.schedule ? horaHumana(dataConsult.schedule.check_time) : '00-00'}}</div>
      <div class="consulta-card card h-100 w-75 py-2 border-0" 
      :class='{
        "border-left-warning": dataConsult.status === 1 || dataConsult.status==4,
        "border-left-danger": dataConsult.status === 3,
        "consultAnulada": dataConsult.status === 3,
        "border-left-success": dataConsult.status === 2,
      }' >
          <div class="card-body">
              <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">
												<span class="text-primary" v-if="dataConsult.status ==2 && dataConsult.payment.pay_status==2">Pagado & Confirmado</span>
												<span class="text-primary" v-else-if="dataConsult.status ==2">Confirmado sin pagar</span>
                        <span class="text-warning" v-else-if="dataConsult.status ==1"> Pendiente</span>
                        <span class="text-danger" v-else-if="dataConsult.status ==3">Anulado</span>
                        <span class="text-warning" v-else-if="dataConsult.status ==4">Reprogramado</span>
                      </div>
                      <router-link class="mb-0 text-capitalize text-secondary" :to="`/profesional/evoluciones/${dataConsult.patient ? dataConsult.patient.id : null}`">
												{{dataConsult.patient.name.toLowerCase()}} {{dataConsult.patient.nombres.toLowerCase()}}
											</router-link>
                      
                      <p class="mb-0" :class="{ 'text-decoration-through': dataConsult.status == 3 }">{{ dataConsult.schedule ? horaHumanaMin(dataConsult.schedule.check_time) : '- : ' }} - {{ dataConsult.schedule ? horaHumanaMin(dataConsult.schedule.departure_date) : '' }} </p>
                  </div>
                  <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-gray-300"></i>
                  </div>
              </div>
              <div class="d-flex align-items-center mt-2 btn-content">
                <!-- Boton de info -->
                <a data-bs-toggle="modal" data-bs-target="#infoModal" class="btn btn-consult btn-action"  title="Información" @click="emitInfo(dataConsult)">
              	  <i class="fas fa-info-circle"></i>
                </a>
                
                <!-- Boton de videollamada -->
                <a v-if="dataConsult.link" :href="dataConsult.link" target="_blank" class="btn btn-consult btn-action" :title="dataConsult.link ? 'Videollamada': 'Sin videollamada'"><i class="fas fa-video"></i></a>

                <!-- Boton pdf para generar receta -->
                <router-link
                :to="{ path: `recetas/${dataConsult.patient.id}` }"
                class="btn btn-consult btn-action"
                title="Generar receta"
                >
                <i class="fas fa-file-alt"></i>
                </router-link>

                <!-- Boton generar pdf orden de examen -->
                <router-link
                :to="{ path: `examenes/${dataConsult.id}`}"
                class="btn btn-consult btn-action"
                title="Orden de exámen">
                <i class="fas fa-stream"></i>
                </router-link>

                <!-- generar pdf para histoia -->
                <a 
                :href="`/api/pdfEvolution/restricted/${dataConsult.patient_id}`"
                target="_blank"
                class="btn btn-consult btn-action"
                title="Generar PDF de historia"
                >
                  <i class="fas fa-file-pdf"></i>
                </a>


                <!-- consultas iniciales Psiquiatria y Psicologia-->
                <button @click="evolucionModal" data-bs-toggle="modal" data-bs-target="#evolutionModal" class="btn btn-consult btn-action" title="Agregar historia inicial psiquiatria" v-if="dataConsult.patient.initial_psychiatric_history === null && dataConsult.professional.profession == 'Psiquiatra'"> <i class="fas fa-portrait"></i> </button>
                <button
                @click="evolucionModal" data-bs-toggle="modal" data-bs-target="#evolutionModal" class="btn btn-consult btn-action" title="Agregar historia inicial psicología" v-else-if="dataConsult.patient.initial_psychological_history === null && dataConsult.professional.profession == 'Psicólogo'"
                >
                <i class="fas fa-portrait"></i>
                </button>

                <!-- evoluciones -->
                <router-link
                class="btn btn-consult btn-action"
                title="Ver evoluciones"
                :to="{ path: `evoluciones/${dataConsult.patient.id}`}"
                v-else
                >
                <i class="fas fa-portrait"></i>
                </router-link>

                <button
                @click="prepararPaciente(dataConsult.patient)" class="btn btn-consult btn-action" title="Agregar triaje" >
                <i class="fa-solid fa-shield-heart"></i>
								<!-- data-bs-toggle="modal" data-bs-target="#modalTriaje" -->
                </button>
                <!-- <router-link
                class="btn btn-consult btn-action"
                title="Ver evoluciones"
                :to="{ path: `evoluciones/${dataConsult.patient.id}`}"
                >
                <i class="fas fa-portrait"></i>
                </router-link> -->
            </div>
          </div>
      </div>
  </div>
</template>

<script>

export default {
  name: 'consulta',
  data () {
    return {
      estadoConsulta: {}, datosPaciente:{}, profesional:[]
    }
  },
  components:{  }, //ModalTriaje
  props: {
    dataConsult: Object,
  },
  methods: {
    prepararPaciente(paciente){
			//this.profesional = {0: this.dataConsult.professional}
     //this.datosPaciente = paciente
			this.$emit('llamarModalTriaje', paciente)
    },
    horaHumana (hora) {
      hora = parseInt(hora.substring(0,2))
      if (hora > 12) {
          return `${hora - 12} PM`
      } else {
        if (hora === 12) {
            return `${hora} PM`
        }

        if (hora === 0) {
            return `12 AM`
        }
        return `${hora} AM`
      }
    },

    horaHumanaMin (hora) {
      let minutos = parseInt(hora.substring(3,5))
      hora = parseInt(hora.substring(0,2))
      if (hora > 12) {
          return `${hora - 12}:${minutos.toString().length === 1 ? '0' + minutos : minutos} p.m.`
      } else {
        if (hora === 12 && minutos >= 0) {
            return `${hora}:${minutos.toString().length === 1 ? '0' + minutos : minutos} p.m.`
        }

        if (hora === 0) {
            return `12:${minutos.toString().length === 1 ? '0' + minutos : minutos} a.m.`
        }
        return `${hora}:${minutos.toString().length === 1 ? '0' + minutos : minutos} a.m.`
      }
    },


    lowerCase (text) {
      return text.toLowerCase();
    },

    evolucionModal () {
      this.$emit('datosEmitModal', this.dataConsult)
    },

    emitInfo (info) {
      this.$emit('emitInfoModal', info);
    },

    maxStringCharacter (character, num) {
      return character.substring(0, num) + '...';
    }
  },
}
</script>

<style scoped>
  .btn-consult {
    background: #6236FF;
    border-radius: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
  }

  .btn-consult i {
    color: #fff;
  }

  .consultPendiente {
    background: #fafff9;
  }

  .consultAnulada { background: #FFF5F5; }

  .btn-content {
    gap: 10px;
  }

  .btn-action {
    opacity: .6;
    transition: opacity .5s ease,
      box-shadow .5s ease;
  }

  .btn-action:hover {
    opacity: 1;
    box-shadow: 3px 3px 8px 0px #ccc;
  }

  .text-capitalize {
    text-transform: lowercase !important;
    text-transform: capitalize !important;
  }

  .text-decoration-through {
    text-decoration: line-through;
  }

  @media screen and (max-width: 750px) {
    .consulta {
      flex-direction: column;
    }

    .consulta-card {
      width: 100% !important;
    }
  } 

</style>
