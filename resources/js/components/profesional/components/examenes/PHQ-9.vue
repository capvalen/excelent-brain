<template>
  <main>
    <h1>Cuestionario de PHQ-9</h1>

    <Scrollable 
    :values="values"
    @sumatoria="sumatoria"
    />

    <SearchPatient
    @selectPatient="selectPatient"
    />
    
    <div class="form-row">
      <label for="age">Edad</label>
      <input 
        type="number" 
        name="age" 
        id="age" 
        placeholder="Escribe tu edad" 
        autocomplete="off"
        class="form-control" 
        v-model="dataPersonalPatient.age"
      >
    </div>

    <ExamLabel 
    :items="items"
    :criteria="criteria"
    @senddata="sendData"
    />
  </main>
</template>

<script>
import SearchPatient from './SearchPatient.vue';
import ExamLabel from './ExamLabel.vue'
import Scrollable from './Scrollable.vue'
import { saveExams } from '../../../../helpers/httpAxios';

export default {
  components: {SearchPatient, ExamLabel, Scrollable},
  
  data() {
    return {
      name: 'phq',
      dataPersonalPatient: {
        patient_id: null,
        age: null
      },
      result: 0,
      criteria: {
        'A': 'Ningún Día',
        'B': 'Varios dias',
        'C': 'Más de la mitad de los días',
        'D': 'Casi todos los días'
      },
      values: {
        suma: 0,
        resultado: '',
        edad: 0,
        created_at: new Date()
      },
      items: [
        {id:'1',content: 'Poco interés o placer en hacer cosas'},
        {id:'2',content: 'Se ha sentido decaído(a), deprimido(a) o sin esperanzas'},
        {id:'3',content: 'Ha tenido dificultad para quedarse o permanecer dormido(a), o ha dormido demasiado'},
        {id:'4',content: 'Se ha sentido cansado(a) o con poca energía'},
        {id:'5',content: 'Sin apetito o ha comido en exceso'},
        {id:'6',content: 'Se ha sentido mal con usted mismo(a) – o que es un fracaso o que ha quedado mal con usted mismo(a) o con su familia'},
        {id:'7',content: 'Ha tenido dificultad para concentrarse en ciertas actividades, tales como leer el periódico o ver la televisión'},
        {id:'8',content: '¿Se ha movido o hablado tan lento que otras personas podrían haberlo notado? o lo contrario – muy inquieto(a) o agitado(a) que ha estado moviéndose mucho más de lo normal'},
        {id:'9',content: 'Pensamientos de que estaría mejor muerto(a) o de lastimarse de alguna manera'},
      ]
    }
  },

  methods: {
    async saveData () {
      await saveExams({
        patient_id: this.dataPersonalPatient.patient_id,
				professional_id: this.$attrs.professional.id,
        exam: JSON.stringify({name: this.name, result: this.values})
      })
      .then(res => {
        this.$swal('Resultados regitrados correctamente')
      })
      .catch(err => {
        this.$swal({
          icon: 'error',
          title: 'Error...',
          text: err
        })
      })
    },

    selectPatient(id) {
      this.dataPersonalPatient.patient_id = id
    },

    sendData(data) {
      this.result = 0
      
      for(let value in data) {
        this.result += data[value].value
      }

      console.log(this.result)
    },

    sumatoria() {
      if (!this.dataPersonalPatient.patient_id) {
        document.getElementById('name-patient').focus();
        return;
      }

      if (!this.dataPersonalPatient.age) {
        document.getElementById('age').focus();
        return;
      }

      this.values.edad = this.dataPersonalPatient.age
      this.values.suma = this.result

      if (this.values.suma <= 4) {
        this.values.resultado = 'La puntuación indica que, probablemente, el paciente no necesita tratamiento para la depresión'
      } else if (this.result >= 5 && this.result <= 14) {
        this.values.resultado = 'El médico debe utilizar su juicio clínico sobre el tratamiento, tomando en consideración la duración de los síntomas del paciente y su transtorno funcional'
      } else if (this.result >= 15) {
        this.values.resultado = 'Se justifica el tratamiento de la depresión con antidepresivos, psicoterapia o una combinación de tratamientos';
      }
      
      this.saveData()
    }    
  },
}
</script>
