<template>
  <main>
    <h1>Cuestionario de PHQ-15</h1>

    <Scrollable :values="values" @sumatoria="sumatoria" />

    <SearchPatient @selectPatient="selectPatient" />

    <div class="form-row">
      <label for="age">Edad</label>
      <input type="number" name="age" id="age" placeholder="Escribe tu edad" autocomplete="off" class="form-control"
        v-model="dataPersonalPatient.age">
    </div>

    <ExamLabel :items="items" :criteria="criteria" @senddata="sendData" />
  </main>
</template>
  
<script>
import SearchPatient from './SearchPatient.vue';
import ExamLabel from './ExamLabel.vue'
import Scrollable from './Scrollable.vue'
import { saveExams } from '../../../../helpers/httpAxios';

export default {
  components: { SearchPatient, ExamLabel, Scrollable },

  data() {
    return {
      name: 'phq',
      dataPersonalPatient: {
        patient_id: null,
        age: null
      },
      result: 0,
      criteria: {
        'A': 'Nada',
        'B': 'Un poco',
        'C': 'Mucho'
      },
      values: {
        suma: 0,
        resultado: '',
        edad: 0,
        created_at: new Date()
      },
      items: [
        { id: '1', content: 'Dolor de estómago' },
        { id: '2', content: 'Dolor de espalda' },
        { id: '3', content: 'Dolor en sus brazos, piernas o articulaciones (rodillas, caderas, etc.)' },
        { id: '4', content: 'Dolores menstruales u otras molestias asociadas a la menstruación (solo mujeres)' },
        { id: '5', content: 'Dolores de cabeza' },
        { id: '6', content: 'Dolor torácico (zona del pecho)' },
        { id: '7', content: 'Mareos' },
        { id: '8', content: 'Desmayos' },
        { id: '9', content: 'Palpitaciones o sentir el corazón acelerado' },
        { id: '10', content: 'Falta de respiración' },
        { id: '11', content: 'Dolor o problemas durante sus relaciones sexuales' },
        { id: '12', content: 'Estreñimiento, ir suelto de vientre o diarrea' },
        { id: '13', content: 'Nauseas o indigestión ' },
        { id: '14', content: 'Sentirse cansado o pocas energías ' },
        { id: '15', content: 'Problemas de sueño' }
      ]
    }
  },

  methods: {
    async saveData() {
      await saveExams({
        patient_id: this.dataPersonalPatient.patient_id,
        exam: JSON.stringify({ name: this.name, result: this.values })
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

      if (this.values.suma <= 5) {
        this.values.resultado = 'Nivel leve de somatización'
      } else if (this.result >= 5 && this.result <= 14) {
        this.values.resultado = 'Nivel moderado de somatización'
      } else if (this.result >= 15) {
        this.values.resultado = 'Nivel severo de somatización';
      }

      this.saveData()
    }
  },
}
</script>
  