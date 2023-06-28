<template>
  <div class="modal fade" id="evolutionModal" tabindex="-1" aria-labelledby="modalEvolution" aria-hidden="true">
    <div class="modal-dialog modal-lg" v-if="dataUser === 'Psiquiatra'">
      <div class="modal-content">
        <div class="modal-header py-1 bg-primary text-white">
            <h5 class="modal-title" id="infoModalLabel">Consulta Inicial de Psiquiatria</h5>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
        </div>

        <div class="modal-body">
          <form @submit.prevent>
            <div class="form-group">
              <label for="sigSinPrin">Signos y sintomas principales</label>
              <textarea class="form-control" name="sigSinPrin" id="sigSinPrin" v-model="inicialPsiquiatria.main_signs_symptoms" cols="30" rows="3"></textarea>
            </div>

            <div class="form-group">
              <label for="anteceGeneFami">Antecedentes generales y familiares</label>
              <input type="text" class="form-control" name="anteceGeneFami" id="anteceGeneFami" v-model="inicialPsiquiatria.general_antecedent">
            </div>
            
            <div class="form-group">
              <label for="efermeAct">Enfermedad actual</label>
              <input type="text" class="form-control" name="efermeAct" id="efermeAct" v-model="inicialPsiquiatria.illness">
            </div>

            <hr>
            <span>Examen Mental</span>
            <hr>
            
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="apcPsiqui">APC</label>
                <input type="text" class="form-control" name="apcPsiqui" id="apcPsiqui" v-model="inicialPsiquiatria.apc">
              </div>
                <div class="form-group col-md-4">
                <label for="lenguaje">Lenguaje</label>
                <input type="text" class="form-control" name="lenguaje" id="lenguaje" v-model="inicialPsiquiatria.languaje">
              </div>
                <div class="form-group col-md-4">
                <label for="pensamiento">Pensamiento</label>
                <input type="text" class="form-control" name="pensamiento" id="pensamiento" v-model="inicialPsiquiatria.thought">
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="afecto">Afecto</label>
                <input type="text" class="form-control" name="afecto" id="afecto" v-model="inicialPsiquiatria.affect">
              </div>
                <div class="form-group col-md-4">
                <label for="percepcion">Percepcion</label>
                <input type="text" class="form-control" name="percepcion" id="percepcion" v-model="inicialPsiquiatria.percetion">
              </div>
                <div class="form-group col-md-4">
                <label for="funciones">Funciones superiores</label>
                <input type="text" class="form-control" name="funciones" id="funciones" v-model="inicialPsiquiatria.superior_function">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="abstraccion">Abstracción</label>
                <input type="text" class="form-control" name="abstraccion" id="abstraccion" v-model="inicialPsiquiatria.abstraction">
              </div>
                <div class="form-group col-md-4">
                <label for="conciencia">Conciencia</label>
                <input type="text" class="form-control" name="conciencia" id="conciencia" v-model="inicialPsiquiatria.conscience">
              </div>
                <div class="form-group col-md-4">
                <label for="insight">Insight</label>
                <input type="text" class="form-control" name="Insight" id="insight" v-model="inicialPsiquiatria.insight">
              </div>
            </div>

            <div class="form-group">
              <label for="problemsDiag">Problemas de diagnóstico</label>
              <input type="text" class="form-control" name="problemsDiag" id="problemsDiag" v-model="inicialPsiquiatria.diagnostic_problems">
            </div>
            
            <div class="form-group position-relative">
              <label for="diagnostico">Diagnóstico</label>
              <input 
                type="text"
                class="form-control" 
                autocomplete="off" 
                name="diagnostico"
                id="diagnostico" 
                v-model="searchCie"
                @keyup="getDiagnostico" 
              >
                <!-- v-model="inicialPsiquiatria.diagnostic" -->
              <div class="cie-content rounded">
                <div 
                v-for="(cie, index) in dataCies"
               :key="index"
               >
                  <span 
                  class="w-100 px-2 py-2 cie--hover d-inline-block pointer cie-item" 
                  :class="{ 'cie-danger': cieAdd.find(el => el.trim() == `${cie.id} - ${cie.code} - ${cie.description}`) }"
                  :data-id="cie.id"
                  @click="addCie"
                  
                  >
                  {{ cie.id }} - {{ cie.code }} - {{ cie.description }}
                  </span>
                </div>
              </div>


              <div class="d-flex flex-gap flex-wrap mt-3">
                <div  
                v-if="cieAdd" 
                v-for="(cieAgregado, index) in cieAdd" 
                :key="`cie${index}`"
                class="bg-warning rounded text-light p-2"
                >
                  {{ cieAgregado }} 
                  <span :data-cie="index" class="cie-item ml-2 pointer" @click="deleteCie"><i class="fas fa-times"></i></span>
                </div>
              </div>
            </div>
            
            <div class="form-group">
              <label for="plan">Plan</label>
              <input type="text" class="form-control" name="plan" id="plan" v-model="inicialPsiquiatria.plan">
            </div>
        
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-danger" data-bs-dismiss="modal" @click="sendEvolution">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal-dialog modal-lg" v-else-if="dataUser === 'Psicólogo'">
      <div class="modal-content">
        <div class="modal-header py-1 bg-primary text-white">
          <h5 class="modal-title" id="infoModalLabel">Consulta Inicial de Psicologia</h5>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
        </div>

        <div class="modal-body">
          <form @submit.prevent>
            <div class="form-group">
              <label for="iPsicoIllness">Problema actual</label>
              <textarea class="form-control" id="iPsicoIllness" v-model="initialPsychological.illness" cols="30" rows="3"></textarea>
            </div>

            <div class="form-group">
              <label for="iPsicoAntecedent">Antecedentes generales y familiares</label>
              <input type="text" class="form-control" id="iPsicoAntecedent" v-model="initialPsychological.antecedent">
            </div>
            
            <div class="form-group">
              <label for="iPsicoDynamic">Dinámica actual</label>
              <input type="text" class="form-control" id="iPsicoDynamic" v-model="initialPsychological.dynamic">
            </div>
            
            <div class="form-group">
              <label for="iPsicoActtitude">Actitud actual</label>
              <input type="text" class="form-control" id="iPsicoActtitude" v-model="initialPsychological.attitude">
            </div>
            
            <div class="form-group">
              <label for="iPsicoDx">DX</label>
              <input type="text" class="form-control" id="iPsicoDx" v-model="initialPsychological.dx">
            </div>
            
            <div class="form-group">
              <label for="iPsicoPlan">Plan de trabajo</label>
              <input type="text" class="form-control" id="iPsicoPlan" v-model="initialPsychological.plan">
            </div>
        
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-danger" data-bs-dismiss="modal" @click="sendEvolution">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'evolucion',
  data () {
    return {
      dataCies: null,
      clickCie: null,
      searchCie: '',
      cieAdd: [],
      
      inicialPsiquiatria: {
        main_signs_symptoms: '',
        general_antecedent: '',
        illness: '',
        apc: '',
        languaje: '',
        thought: '',
        affect: '',
        percetion: '',
        superior_function: '',
        abstraction: '',
        conscience: '',
        insight: '',
        diagnostic_problems: '',
        diagnostic: ' - ',
        diagnosticArray: [],
        plan: '',

        professional_id: '',
        patient_id: ''
      },

      initialPsychological: {
        illness: '', // Enfermedad
        antecedent: '',
        dynamic: '',
        attitude: '',
        dx: '',
        plan: '...',
        professional_id: '',
        patient_id: ''
      },
      
    }
  },
  
  props: {
    dataUser: String,
    datosIdEvolucion: null,
  },

  methods: {
    async sendEvolution () {
      let data = Object;
      if (this.dataUser === 'Psiquiatra') {
        data = this.inicialPsiquiatria 
      } else {
        data = this.initialPsychological
      }

      await axios.post(`/api/${this.dataUser === 'Psiquiatra' 
        ? 'initialPsychiatric'
        : 'initialPsychological'}`, data
      )
      .then(res => {
        console.log(res.data)
        this.$emit('updateCalendar', true)
      })
      .catch(err => {
        console.error(err)
      })
    }, 

    async getDiagnostico () {
      await this.axios.get(`/api/cies/${this.searchCie === '' ? 'a' : this.searchCie }`)
      .then(res => {
        this.dataCies = res.data;
      })

      .catch(err => {
        conole.error(err)
      })
    },

    addCie () {
      let verify = true;
      if (this.cieAdd.length === 0) {
        this.inicialPsiquiatria.diagnosticArray.push(event.target.dataset.id) 
        this.cieAdd.push(event.target.textContent.trim());
        return;
      }
      
      this.cieAdd.find(el => {if (el.trim() === event.target.textContent.trim()) return verify = false})

      if (verify === true) {
        this.inicialPsiquiatria.diagnosticArray.push(event.target.dataset.id) 
        this.cieAdd.push(event.target.textContent.trim());
      } 
    },

    deleteCie () {
      this.cieAdd.splice(parseInt(event.target.closest('.cie-item').dataset.cie), 1)
      this.inicialPsiquiatria.diagnosticArray.splice(parseInt(event.target.closest('.cie-item').dataset.cie), 1)
    }

  },
  computed: {
    updatedValues () {
      if (this.dataUser === 'Psiquiatra') {
        this.inicialPsiquiatria.professional_id = this.datosIdEvolucion.professional.id
        this.inicialPsiquiatria.patient_id = this.datosIdEvolucion.patient.id
      } else {
        this.initialPsychological.professional_id = this.datosIdEvolucion.professional.id
        this.initialPsychological.patient_id = this.datosIdEvolucion.patient.id
      }
        
      return;
    }
  },
  updated () {
    this.updatedValues
  }
}
</script>

<style scoped>
  .cie-content {
    width: 100%;
    background-color: #fff;
    border: .3px solid #22222260;
    visibility: hidden;
    position: absolute;
    z-index: 10;
    transition: visibility 1s normal 3s;
  }

  #diagnostico:focus + .cie-content {
    visibility: visible;
    /* display: block !important; */
  }
  .cie-item:active {
    visibility: visible;
    opacity: 0;
    /* display: block !important; */
  }
  
  .flex-gap {
    gap: 15px;
  }

  .pointer {
    cursor: pointer;
  }

  .cie--hover:hover:not(.cie-danger) {
    background: rgb(236, 236, 236);
  }

  .cie-danger {
    background: rgb(255, 207, 207);
    cursor: no-drop;
  }

</style>