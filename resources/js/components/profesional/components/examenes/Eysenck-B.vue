<template>
  <div>
    <h1>Eysenck-B</h1>

    <Scrollable :values="values" @sumatoria="sumatoria" />

    <SearchPatient @selectPatient="selectPatient" />

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

    <ExamLabel :items="items" :criteria="criteria" @senddata="sendData" />
  </div>
</template>

<script>
import SearchPatient from './SearchPatient.vue';
import ExamLabel from './ExamLabel.vue'
import Scrollable from './Scrollable.vue'
import { saveExams } from '../../../../helpers/httpAxios';

export default {
  name: "MCMI",

  data() {
    return {
      name: 'eysenckb',
      dataPersonalPatient: {
        patient_id: null,
        age: null
      },
      finalScore: 0,
      dimensionScore: {
        flematico: {
          E: [0, 13],
          N: [0, 11],
        },
        sanguineo: {
          E: [14, 24],
          N: [0, 11],
        },
        melancolico: {
          E: [0, 13],
          N: [12, 24],
        },
        colerico: {
          E: [14, 24],
          N: [12, 24],
        }
      },
      values: {
        E: 0,
        N: 0,
        L: 0,
        edad: null,
        validez: 0,
        dimension: '',
        created_at: new Date()
      },
      result: {
        E: 0,
        N: 0,
        L: 0,
        edad: null,
        validez: 0,
        dimension: '',
        created_at: new Date()
      },
      criteria: {
        'A': 'Falso',
        'B': 'Verdadero',
      },
      items: [
        { id: '1', content: '¿Le gusta abundancia de excitación y bullicio a su alrededor?', group: 'e', expected: true },
        { id: '2', content: 'Tiene a menudo un sentimiento de intranquilidad, como si quisiera algo pero sin saber qué.', group: 'n', expected: true },
        { id: '3', content: '¿Tiene casi siempre una contestación lista, "a la mano", cuando la gente le habla?', group: 'e', expected: true },
        { id: '4', content: '¿Se siente algunas veces feliz, algunas veces triste, sin una razón real?', group: 'n', expected: true },
        { id: '5', content: '¿Permanece usualmente retraído(a) en fiestas y reuniones?', group: 'e', expected: false },

        { id: '6', content: '¿Cuando era niño(a) hacia siempre inmediatamente lo que le decían, sin refunfuñar?', group: 'l', expected: true },
        { id: '7', content: '¿Se enfada a veces?', group: 'n', expected: true },
        { id: '8', content: '¿Cuándo lo meten en una pelea, prefiere "sacar los trapos al aire", de una vez por todas?', group: 'e', expected: true },
        { id: '9', content: '¿Es usted triste, melancólico(a)?', group: 'n', expected: true },
        { id: '10', content: '¿Le gusta mezclarse con la gente?', group: 'e', expected: true },

        { id: '11', content: '¿Ha perdido a menudo el sueño por sus preocupaciones?', group: 'n', expected: true },
        { id: '12', content: '¿Se enoja a veces?', group: 'l', expected: false },
        { id: '13', content: '¿Se catalogaría a sí mismo(a) como despreocupado(a) o confiado(a) a su buena ventura?', group: 'e', expected: true },
        { id: '14', content: '¿Se decide a menudo demasiado tarde?', group: 'n', expected: true },
        { id: '15', content: '¿Le gusta trabajar solo(a)?', group: 'e', expected: false },



        { id: '16', content: '¿Se ha sentido a menudo apático(a) y cansado(a), sin razón?', group: 'n', expected: true },
        { id: '17', content: '¿Es por el contrario animado(a) y jovial?', group: 'e', expected: true },
        { id: '18', content: '¿Se ríe a veces de chistes groseros?', group: 'l', expected: false },
        { id: '19', content: '¿Se siente a menudo hastiado(a)?', group: 'n', expected: true },
        { id: '20', content: '¿Se siente incómodo(a) con vestidos que no son del diario?', group: 'e', expected: false },

        { id: '21', content: '¿Se distrae (vaga su mente) a menudo cuando trata de prestar atención a algo?', group: 'n', expected: true },
        { id: '22', content: '¿Puede expresar en palabras fácilmente lo que piensa?', group: 'e', expected: true },
        { id: '23', content: '¿Se abstrae (se pierde en sus pensamientos) a menudo?', group: 'n', expected: true },
        { id: '24', content: '¿Está completamente libre de prejuicios de cualquier tipo?', group: 'l', expected: true },
        { id: '25', content: '¿Le gustan las bromas?', group: 'e', expected: true },

        { id: '26', content: '¿Piensa a menudo en su pasado?', group: 'n', expected: true },
        { id: '27', content: '¿Le gusta mucho la buena comida?', group: 'e', expected: true },
        { id: '28', content: 'Cuando se fastidia, ¿Necesita de algún(a) amigo(a) para hablar sobre ello?', group: 'n', expected: true },
        { id: '29', content: '¿Le molesta vender cosas o pedir dinero a la gente para alguna buena causa?', group: 'e', expected: false },
        { id: '30', content: '¿Alardea (se jacta) un poco a veces?', group: 'l', expected: false },



        { id: '31', content: '¿Es Ud. muy susceptible por algunas cosas?', group: 'n', expected: true },
        { id: '32', content: '¿Le gusta quedarse más en casa que ir a una fiesta aburrida?', group: 'e', expected: false },
        { id: '33', content: '¿Se pone a menudo tan inquieto(a) que no puede permanecer sentado(a) durante mucho rato en una silla?', group: 'n', expected: true },
        { id: '34', content: '¿Le gusta planear las cosas cuidadosamente con mucha anticipación?', group: 'e', expected: false },
        { id: '35', content: '¿Tiene a menudo mareo (vértigo)?', group: 'n', expected: true },

        { id: '36', content: '¿Contesta siempre una carta personal tan pronto como puede después de haberla leído?', group: 'l', expected: true },
        { id: '37', content: '¿Hace Ud. usualmente las cosas mejor resolviéndolas sólo(a) que hablando a otras personas sobre ellas?', group: 'e', expected: false },
        { id: '38', content: '¿Le falta frecuentemente aire sin haber hecho un trabajo pesado?', group: 'n', expected: true },
        { id: '39', content: '¿Es Ud. una persona tolerante que no se molesta generalmente si las cosas no están perfectas?', group: 'e', expected: true },
        { id: '40', content: '¿Sufre de los nervios?', group: 'n', expected: true },

        { id: '41', content: '¿Le gustaría mas planear cosas que hacer cosas?', group: 'e', expected: false },
        { id: '42', content: '¿Deja para algunas veces para mañana lo que debería hacer hoy?', group: 'l', expected: false },
        { id: '43', content: '¿Se pone nervioso(a) en lugares tales como ascensores, trenes o túneles?', group: 'n', expected: true },
        { id: '44', content: 'Cuando hace nuevos amigos, ¿Es usualmente Ud. quien inicia la relación o invita a que se produzca?', group: 'e', expected: true },
        { id: '45', content: '¿Sufre de dolores de cabeza?', group: 'n', expected: true },



        { id: '46', content: '¿Siente generalmente que las cosas se arreglarán por si solas y que terminarán bien de algún modo?', group: 'e', expected: true },
        { id: '47', content: '¿Le cuesta trabajo coger el sueño al acostarse en las noches?', group: 'n', expected: true },
        { id: '48', content: '¿Ha dicho alguna vez mentiras en su vida?', group: 'l', expected: false },
        { id: '49', content: '¿Dice algunas veces lo primero que se le viene a la cabeza?', group: 'e', expected: true },
        { id: '50', content: '¿Se preocupa durante un tiempo demasiado largo después de una experiencia embarazosa?', group: 'n', expected: true },

        { id: '51', content: '¿Se mantiene usualmente hermético(a) o encerrado(a) en sí mismo (a) excepto con amigos íntimos?', group: 'e', expected: false },
        { id: '52', content: '¿Se crea a menudo problemas por hacer cosas sin pensar?', group: 'n', expected: true },
        { id: '53', content: '¿Le gusta hacer chistes y referir historias graciosas a sus amigos?', group: 'e', expected: true },
        { id: '54', content: '¿Se le hace más fácil ganar que perder un juego?', group: 'l', expected: false },
        { id: '55', content: '¿Se siente a menudo demasiado consciente de sí mismo(a) o poco natural cuando esta con superiores?', group: 'n', expected: true },

        { id: '56', content: 'Cuando todas las probabilidades están contra Ud., ¿Piensa usualmente que vale la pena probar suerte?', group: 'e', expected: true },
        { id: '57', content: '¿Siente "sensaciones raras" en el abdomen antes de algún hecho importante?', group: 'n', expected: true },
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

      if (this.finalScore > 4) {
        this.result.validez = 'Prueba anulada'
      } else {
        this.result.validez = 'Fiable'
      }

      for (let dimension in this.dimensionScore) {
        let [valueInitalForE, valueEndForE] = this.dimensionScore[dimension].E
        let [valueInitalForN, valueEndForN] = this.dimensionScore[dimension].N

        if (this.result.E >= valueInitalForE && this.result.E <= valueEndForE &&
          this.result.N >= valueInitalForN && this.result.N <= valueEndForN) {
            this.result.dimension = dimension;
        }
      }

      this.result.edad = this.dataPersonalPatient.age
      this.values = this.result

      this.saveData()
    },

    sendData(data) {
      this.result.E = 0
      this.result.N = 0
      this.result.L = 0

      this.finalScore = 0

      for (let value in data) {
        if (data[value].group === 'e') {
          if (data[value].value == data[value].expected) {
            this.result.E += 1;
          }
        }
        if (data[value].group === 'n') {
          if (data[value].value == data[value].expected) {
            this.result.N += 1;
          }
        }
        if (data[value].group === 'l') {
          if (data[value].value == data[value].expected) {
            this.result.L += 1;
          }

          if (data[value].value === 1) {
            this.finalScore = this.finalScore + 1;
          }
        }
      }
    },
  }
}
</script>