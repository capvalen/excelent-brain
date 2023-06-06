<template>
  <div>
    <h1>MDQ</h1>

    <Scrollable :values="values" @sumatoria="sumatoria" />

    <SearchPatient @selectPatient="selectPatient" />

    <div class="form-row">
      <label for="age">Edad</label>

      <input type="number" name="age" id="age" placeholder="Escribe tu edad" autocomplete="off" class="form-control"
        v-model="dataPersonalPatient.age">

      <h5>A.1. ¿Alguna vez ha pasado por un período en el que sentía que no era la misma persona de siempre, y ...</h5>
    </div>

    <ExamLabel :items="items" :criteria="criteria" :criteriab="criteriab" @senddata="sendData" />
  </div>
</template>
  
<script>
import SearchPatient from './SearchPatient.vue';
import ExamLabel from './ExamLabel.vue'
import Scrollable from './Scrollable.vue'
import { saveExams } from '../../../../helpers/httpAxios';

export default {
  name: "MDQ",

  data() {
    return {
      name: 'mdq',
      dataPersonalPatient: {
        patient_id: null,
        age: null
      },
      finalScore: 0,
      a2: 0,
      a3:0,
      values: {
        sumatoria: 0,
        situacion: '',
        edad: 0,
        created_at: new Date()
      },
      criteria: {
        'A': 'No',
        'B': 'Si',
      },
      criteriab: {
        'A': 'Ningún problema',
        'B': 'Pequeños problemas',
        'C': 'Problemas moderados',
        'D': 'Problemas graves'
      },
      items: [
        { id: '1', content: '¿Se sintió tan bien o tan eufórico/a que otras personas pensaron que usted no era el/la mismo/a de siempre o estaba tan eufórico/a que se metió en problemas?', group: 'e', expected: true },
        { id: '2', content: '¿Estaba tan irritable que gritaba a la gente o provocaba peleas o discusiones?', group: 'n', expected: true },
        { id: '3', content: '¿Se sentía mucho más seguro/a de sí mismo/a de lo habitual? ', group: 'e', expected: true },
        { id: '4', content: '¿Dormía mucho menos que de costumbre y no necesitaba dormir más?', group: 'n', expected: true },
        { id: '5', content: '¿Era mucho más hablador/a o hablaba más rápido que de costumbre?', group: 'e', expected: false },
        { id: '6', content: '¿Le pasaban ideas muy rápidamente por la cabeza o no podía hacer que su mente fuera más despacio? ', group: 'l', expected: true },
        { id: '7', content: '¿Se distraía tan fácilmente con cosas de su alrededor que tenía dificultades para concentrarse o para seguir con lo que estaba haciendo?', group: 'n', expected: true },
        { id: '8', content: '¿Tenía mucha más energía que de costumbre?', group: 'e', expected: true },
        { id: '9', content: '¿Era mucho más activo/a o hacía muchas más cosas que de costumbre?', group: 'n', expected: true },
        { id: '10', content: '¿Era mucho más sociable o abierto/a que de costumbre, por ejemplo, telefoneaba a amigos en mitad de la noche?', group: 'e', expected: true },
        { id: '11', content: '¿Estaba mucho más interesado/a en el sexo que de costumbre??', group: 'n', expected: true },
        { id: '12', content: '¿Hacía cosas que eran inusuales en usted o que otras personas podrían haber considerado excesivas, insensatas o arriesgadas ?', group: 'l', expected: false },
        { id: '13', content: '¿El gasto de dinero le creó problemas a usted o a su familia?', group: 'e', expected: true },
        { id: '14', content: 'A2. Si usted marcó SI en más de una de las preguntas anteriores, ¿algunas de estas situaciones ocurrieron durante el mismo período? Por favor.', group: 'e', expected: true },
        { id: '15', content: 'A3. ¿Hasta qué punto alguna de estas situaciones le causó problemas (como no poder trabajar, problemas familiares, de dinero o legales, implicarse en discusiones o peleas)?', group: 'e', expected: true, especial: true }
      ]
    }
  },

  components: { SearchPatient, ExamLabel, Scrollable },

  methods: {
    async saveData() {
      await saveExams({
        patient_id: this.dataPersonalPatient.patient_id,
				professional_id: this.$attrs.professional.id,
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

    sumatoria() {
      if (!this.dataPersonalPatient.patient_id) {
        document.getElementById('name-patient').focus();
        return;
      }

      if (!this.dataPersonalPatient.age) {
        document.getElementById('age').focus();
        return;
      }

      this.values.sumatoria = this.finalScore

      if (this.finalScore > 7) {
        this.values.situacion = 'Requiere atención especializada'
      } else {
        if (this.a2 == 1) {
          this.values.situacion = 'Requiere atención especializada'
        } else {
          this.values.situacion = 'No requiere atención especializada'
        }
        if(this.a3 == 1){
          this.values.situacion = 'Requiere atención especializada'
        }
      }

      this.values.edad = this.dataPersonalPatient.age

      this.saveData()
    },

    sendData(data) {

      this.finalScore = 0

      for(let value in data) {

      if (data[value].id == '14' && data[value].value == 1) {
        this.a2 = 1
      }else if(data[value].id == '15'){
        if(data[value].value == '2' || data[value].value == '3'){
          this.a3 = 1
        }else{
          this.a3 = 0
        }
      } 
      else {
        this.finalScore += data[value].value
      }

    }


    },
  }
}
</script>