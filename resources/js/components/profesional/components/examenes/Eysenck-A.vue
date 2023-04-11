<template>
  <div>
    <h1>Eysenck-A</h1>

    <Scrollable :values="values" @sumatoria="sumatoria" />

    <SearchPatient @selectPatient="selectPatient" />

    <div class="form-row">
      <label for="gender">Genero</label>
      <select name="gender" id="gender" class="form-control input-name" v-model="dataPersonalPatient.gender">
        <option value="Sin escoger" selected disabled>Sin escoger</option>
        <option value="m">Masculino</option>
        <option value="f">Femenino</option>
      </select>
    </div>

    <div class="form-row">
      <label for="age">Edad</label>
      <select name="age" id="age" class="form-control" v-model="dataPersonalPatient.age">
        <option value="Sin escoger" selected disabled>Sin escoger</option>
        <option 
          v-for="i in 10"
          :key="i"
          :value="`${i + 6}`"
        >
        {{ i + 6 }}
        </option>
      </select>
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
      name: 'eysencka',
      dataPersonalPatient: {
        patient_id: null,
        gender: 'Sin escoger',
        age: 'Sin escoger'
      },
      scoreInterpretation:{
        m: {
          E: {
            "7": {
              "Altamente Introvert.": [0, 6],
              "Tendencia Introvers.": [7, 11],
              "Ambivertido":          [12, 20],
              "Tendencia Extrovers.": [21, 23],
              "Altamente Extrovert.": [24],
            },
            
            "8": {
              "Altamente Introvert.": [0, 7],
              "Tendencia Introvers.": [8, 12],
              "Ambivertido":          [13, 21],
              "Tendencia Extrovers.": [22, 23],
              "Altamente Extrovert.": [24],
            },

            "9": {
              "Altamente Introvert.": [0, 9],
              "Tendencia Introvers.": [10, 13],
              "Ambivertido":          [14, 20],
              "Tendencia Extrovers.": [21, 23],
              "Altamente Extrovert.": [24],
            },

            "10": {
              "Altamente Introvert.": [0, 10],
              "Tendencia Introvers.": [11, 13],
              "Ambivertido":          [14, 21],
              "Tendencia Extrovers.": [22, 23],
              "Altamente Extrovert.": [24],
            },

            "11": {
              "Altamente Introvert.": [0, 10],
              "Tendencia Introvers.": [11, 14],
              "Ambivertido":          [15, 21],
              "Tendencia Extrovers.": [22, 23],
              "Altamente Extrovert.": [24],
            },

            "12": {
              "Altamente Introvert.": [0, 10],
              "Tendencia Introvers.": [11, 14],
              "Ambivertido":          [15, 21],
              "Tendencia Extrovers.": [22, 23],
              "Altamente Extrovert.": [24],
            },

            "13": {
              "Altamente Introvert.": [0, 11],
              "Tendencia Introvers.": [12, 14],
              "Ambivertido":          [15, 21],
              "Tendencia Extrovers.": [23, 23],
              "Altamente Extrovert.": [24],
            },

            "14": {
              "Altamente Introvert.": [0, 11],
              "Tendencia Introvers.": [12, 14],
              "Ambivertido":          [15, 21],
              "Tendencia Extrovers.": [22, 23],
              "Altamente Extrovert.": [24],
            },

            "15": {
              "Altamente Introvert.": [0, 10],
              "Tendencia Introvers.": [11, 14],
              "Ambivertido":          [15, 20],
              "Tendencia Extrovers.": [21, 23],
              "Altamente Extrovert.": [24],
            },

            "16": {
              "Altamente Introvert.": [0, 10],
              "Tendencia Introvers.": [11, 14],
              "Ambivertido":          [15, 20],
              "Tendencia Extrovers.": [21, 23],
              "Altamente Extrovert.": [24],
            },
          },
          N: {
            "7": {
              "Altamente Estable":    [0, 1],
              "Muy Estable":          [2, 5],
              "Estable":              [6, 15],
              "Tendencia Inestable":  [16, 20],
              "Altamente Inestable":  [21],
            },
            
            "8": {
              "Altamente Estable":    [0, 1],
              "Muy Estable":          [2, 6],
              "Estable":              [7, 16],
              "Tendencia Inestable":  [17, 20],
              "Altamente Inestable":  [23, 24],
            },

            "9": {
              "Altamente Estable":    [0, 2],
              "Muy Estable":          [3, 6],
              "Estable":              [7, 16],
              "Tendencia Inestable":  [17, 20],
              "Altamente Inestable":  [21],
            },

            "10": {
              "Altamente Estable":    [0, 1],
              "Muy Estable":          [2, 6],
              "Estable":              [7, 16],
              "Tendencia Inestable":  [17, 21],
              "Altamente Inestable":  [22],
            },

            "11": {
              "Altamente Estable":    [0, 1],
              "Muy Estable":          [2, 5],
              "Estable":              [6, 16],
              "Tendencia Inestable":  [17, 21],
              "Altamente Inestable":  [22],
            },

            "12": {
              "Altamente Estable":    [0, 1],
              "Muy Estable":          [2, 5],
              "Estable":              [6, 16],
              "Tendencia Inestable":  [17, 21],
              "Altamente Inestable":  [22],
            },

            "13": {
              "Altamente Estable":    [0, 1],
              "Muy Estable":          [2, 5],
              "Estable":              [6, 15],
              "Tendencia Inestable":  [16, 20],
              "Altamente Inestable":  [21],
            },

            "14": {
              "Altamente Estable":    [0, 1],
              "Muy Estable":          [2, 6],
              "Estable":              [7, 15],
              "Tendencia Inestable":  [16, 20],
              "Altamente Inestable":  [21],
            },

            "15": {
              "Altamente Estable":    [0, 1],
              "Muy Estable":          [2, 5],
              "Estable":              [6, 15],
              "Tendencia Inestable":  [16, 20],
              "Altamente Inestable":  [21],
            },

            "16": {
              "Altamente Estable":    [0, 1],
              "Muy Estable":          [2, 4],
              "Estable":              [5, 13],
              "Tendencia Inestable":  [14, 18],
              "Altamente Inestable":  [19],
            },
          },
          L: {
            "7": 9,
            "8": 8,
            "9": 7,
            "10": 5,
            "11": 5,
            "12": 4,
            "13": 3,
            "14": 3,
            "15": 2,
            "16": 2,
          }
        },
        f: {
          E: {
            "7": {
              "Altamente Introvert.": [0, 8],
              "Tendencia Introvers.": [9, 12],
              "Ambivertido":          [13, 18],
              "Tendencia Extrovers.": [21, 21],
              "Altamente Extrovert.": [22],
            },
            
            "8": {
              "Altamente Introvert.": [0, 9],
              "Tendencia Introvers.": [10, 12],
              "Ambivertido":          [13, 19],
              "Tendencia Extrovers.": [20, 22],
              "Altamente Extrovert.": [23, 24],
            },

            "9": {
              "Altamente Introvert.": [0, 9],
              "Tendencia Introvers.": [10, 12],
              "Ambivertido":          [13, 20],
              "Tendencia Extrovers.": [21, 23],
              "Altamente Extrovert.": [24],
            },

            "10": {
              "Altamente Introvert.": [0, 10],
              "Tendencia Introvers.": [11, 13],
              "Ambivertido":          [14, 19],
              "Tendencia Extrovers.": [20, 23],
              "Altamente Extrovert.": [24],
            },

            "11": {
              "Altamente Introvert.": [0, 10],
              "Tendencia Introvers.": [11, 13],
              "Ambivertido":          [14, 20],
              "Tendencia Extrovers.": [21, 23],
              "Altamente Extrovert.": [24],
            },

            "12": {
              "Altamente Introvert.": [0, 10],
              "Tendencia Introvers.": [11, 13],
              "Ambivertido":          [14, 20],
              "Tendencia Extrovers.": [21, 23],
              "Altamente Extrovert.": [24],
            },

            "13": {
              "Altamente Introvert.": [0, 9],
              "Tendencia Introvers.": [10, 13],
              "Ambivertido":          [14, 21],
              "Tendencia Extrovers.": [22, 23],
              "Altamente Extrovert.": [24],
            },

            "14": {
              "Altamente Introvert.": [0, 10],
              "Tendencia Introvers.": [11, 13],
              "Ambivertido":          [14, 21],
              "Tendencia Extrovers.": [22, 23],
              "Altamente Extrovert.": [24],
            },

            "15": {
              "Altamente Introvert.": [0, 7],
              "Tendencia Introvers.": [8, 12],
              "Ambivertido":          [13, 21],
              "Tendencia Extrovers.": [22, 23],
              "Altamente Extrovert.": [24],
            },

            "16": {
              "Altamente Introvert.": [0, 7],
              "Tendencia Introvers.": [8, 12],
              "Ambivertido":          [13, 21],
              "Tendencia Extrovers.": [22, 23],
              "Altamente Extrovert.": [24],
            },
          },
          N: {
            "7": {
              "Altamente Estable":    [0, 1],
              "Muy Estable":          [2, 6],
              "Estable":              [7, 15],
              "Tendencia Inestable":  [16, 20],
              "Altamente Inestable":  [21],
            },
            
            "8": {
              "Altamente Estable":    [0, 1],
              "Muy Estable":          [2, 6],
              "Estable":              [7, 16],
              "Tendencia Inestable":  [17, 21],
              "Altamente Inestable":  [22],
            },

            "9": {
              "Altamente Estable":    [0, 2],
              "Muy Estable":          [3, 7],
              "Estable":              [8, 17],
              "Tendencia Inestable":  [18, 21],
              "Altamente Inestable":  [22],
            },

            "10": {
              "Altamente Estable":    [0, 2],
              "Muy Estable":          [3, 7],
              "Estable":              [8, 17],
              "Tendencia Inestable":  [18, 22],
              "Altamente Inestable":  [24],
            },

            "11": {
              "Altamente Estable":    [0, 2],
              "Muy Estable":          [3, 6],
              "Estable":              [7, 17],
              "Tendencia Inestable":  [18, 22],
              "Altamente Inestable":  [24],
            },

            "12": {
              "Altamente Estable":    [0, 2],
              "Muy Estable":          [3, 6],
              "Estable":              [7, 17],
              "Tendencia Inestable":  [18, 22],
              "Altamente Inestable":  [24],
            },

            "13": {
              "Altamente Estable":    [0, 2],
              "Muy Estable":          [3, 7],
              "Estable":              [8, 18],
              "Tendencia Inestable":  [19, 23],
              "Altamente Inestable":  [24],
            },

            "14": {
              "Altamente Estable":    [0, 4],
              "Muy Estable":          [5, 9],
              "Estable":              [10, 18],
              "Tendencia Inestable":  [19, 23],
              "Altamente Inestable":  [24],
            },

            "15": {
              "Altamente Estable":    [0, 4],
              "Muy Estable":          [5, 9],
              "Estable":              [10, 18],
              "Tendencia Inestable":  [19, 22],
              "Altamente Inestable":  [23, 24],
            },

            "16": {
              "Altamente Estable":    [0, 4],
              "Muy Estable":          [5, 9],
              "Estable":              [11, 18],
              "Tendencia Inestable":  [19, 23],
              "Altamente Inestable":  [24],
            },
          },
          L: {
            "7": 9,
            "8": 8,
            "9": 7,
            "10": 5,
            "11": 5,
            "12": 4,
            "13": 3,
            "14": 3,
            "15": 2,
            "16": 2,
          }
        },
      },
      values: {
        E: 0,
        N: 0,
        L: 0,
        edad: 0,
        created_at: new Date()
      },
      result: {
        E: 0,
        N: 0,
        L: 0,
        edad: 0,
        created_at: new Date()
      },
      criteria: {
        'A': 'No',
        'B': 'Si',
      },
      items: [
        { id: '1', content: '¿Te gusta que haya mucho ruido y alboroto alrededor tuyo?', group: 'e', expected: true },
        { id: '2', content: '¿Necesitas a menudo amigos o compañeros buenos, comprensivos, que te den ánimo o valor?', group: 'n', expected: true },
        { id: '3', content: '¿Casi siempre tienes una corrtfltaclOn o respuesta rapidamente (al toque) cuando la gente te conversa?', group: 'e', expected: true },
        { id: '4', content: '¿Algunas veces te pones malhumorado, de mal genio?', group: 'l', expected: false },
        { id: '5', content: '¿Eres triste?', group: 'n', expected: true },

        { id: '6', content: '¿Prefieres estar solo, en vez de estar acompañado de otros niños?', group: 'e', expected: false },
        { id: '7', content: '¿Pasn ideas por tu cabeza que no te dejan dormir?', group: 'n', expected: true },
        { id: '8', content: '¿Siempre haces inmediatamente conforme lo que te dicen o mandan?', group: 'l', expected: true },
        { id: '9', content: '¿Te gustan las bromas pesadas?', group: 'e', expected: true },
        { id: '10', content: '¿A veces te sientes infeliz, triste sin que haya ninguna razón especial?', group: 'n', expected: true },

        { id: '11', content: '¿Eres vivaz y alegre?', group: 'e', expected: true },
        { id: '12', content: '¿Alguna vez has desobedecido cierta regla de la escuela?', group: 'l', expected: false },
        { id: '13', content: '¿Te aburren o fastidian muchas cosas?', group: 'n', expected: true },
        { id: '14', content: '¿Te gustan hacer las cosas rapidamente?', group: 'e', expected: true },
        { id: '15', content: '¿Te preocupas por cosas terriebles o feas que pudieran sucederte?', group: 'n', expected: true },



        { id: '16', content: '¿Siempre puedes callar todo secreto que sabes?', group: 'l', expected: true },
        { id: '17', content: '¿Puedes hacer que una fiesta, paseo o juegos sean alegres?', group: 'e', expected: true },
        { id: '18', content: '¿Sientes golpes en tu corazon?', group: 'n', expected: true },
        { id: '19', content: '¿Cuándo conoces nuevos amigos, generalmente tu empiezas la conversación?', group: 'e', expected: true },
        { id: '20', content: '¿Has dicho alguna vez alguna mentira?', group: 'l', expected: false },

        { id: '21', content: '¿Te sientes facilmente herido o apenado cuando la gente encuentra un defecto en ti o una falla en el trabajo que haces?', group: 'n', expected: true },
        { id: '22', content: '¿Te gusta "cochinear" (hacer bromas) y contar historias graciosas a tus amigos?', group: 'e', expected: true },
        { id: '23', content: '¿A menudo te sientes cansado sin razón?', group: 'n', expected: true },
        { id: '24', content: '¿Siempre terminas tus tareas antes de ir a jugar?', group: 'l', expected: true },
        { id: '25', content: '¿Estas generalmente alegre o contento?', group: 'e', expected: true },

        { id: '26', content: '¿Te sientes dolido ante ciertas cosas?', group: 'n', expected: true },
        { id: '27', content: '¿Te gusta juntarte con otros chicos?', group: 'e', expected: true },
        { id: '28', content: '¿Dices tus oraciones todas las noches?', group: 'l', expected: true },
        { id: '29', content: '¿Tienes mareos?', group: 'n', expected: true },
        { id: '30', content: '¿Te gusta hacerles travesuras y jugarretas a otros?', group: 'e', expected: true },



        { id: '31', content: '¿Te sientes a menudo harto, hastiado?', group: 'n', expected: true },
        { id: '32', content: '¿Algunas veces alardeas (fanfarroneas) o "tiras pana" un poco?', group: 'l', expected: false },
        { id: '33', content: '¿Estas generalmente tranquilo y callado cuando estas con otros niños?', group: 'e', expected: false },
        { id: '34', content: '¿Algunas veces te pones tan inquieto que no puedes estar sentado en una silla mucho rato?', group: 'n', expected: true },
        { id: '35', content: '¿A menudo decides hacer las cosas de repente, sin pensarlo?', group: 'e', expected: true },

        { id: '36', content: '¿Estas siempre callado en clase, aun cuando el profesor(a) esta fuera del salon?', group: 'l', expected: true },
        { id: '37', content: '¿Tienes muchas pesadillas que te asuntan?', group: 'n', expected: true },
        { id: '38', content: '¿Puedes generalmente participar y disfrutar de un paseo alegre?', group: 'e', expected: true },
        { id: '39', content: '¿Tu sentimiento son facilmente heridos?', group: 'n', expected: true },
        { id: '40', content: '¿Algunas has dicho algo malo, feo u ofensivo acerca de una persona o compañero?', group: 'l', expected: false },

        { id: '41', content: '¿Considerarias que eres feliz, suertudo y lechero?', group: 'e', expected: true },
        { id: '42', content: '¿Si es que sientes que has parecido como un tonto o has hecho el ridiculo te quedas preucupado?', group: 'n', expected: true },
        { id: '43', content: '¿Te gusta a menudo los juegos bruscos, desordenados, brincar, revolcarte?', group: 'e', expected: true },
        { id: '44', content: '¿Siempre comes todo lo que ten dan en la comida?', group: 'l', expected: true },
        { id: '45', content: '¿Te hes muy dificil de aceptar que te digan (NO), te nieguen algo o no te dejen hacer algo?', group: 'n', expected: true },



        { id: '46', content: '¿Te gusta salir a la calle bastante?', group: 'e', expected: true },
        { id: '47', content: '¿Sientes que algunas veces que la vida no vale la pena vivirla?', group: 'n', expected: true },
        { id: '48', content: '¿Haz sido alguna vez insolente con tus padres?', group: 'l', expected: false },
        { id: '49', content: '¿Las personas piensan que tú eres alegre y "vivo"?', group: 'e', expected: true },
        { id: '50', content: '¿A menudo te distraes o piensas otras cosas cuando estas haciendo trabajo o tarea?', group: 'n', expected: true },

        { id: '51', content: '¿Prefieres estar mas sentado y mirar, que jugar y bailar en las fiestas?', group: 'e', expected: true },
        { id: '52', content: '¿A menudo has perdido el sueño por tus preocupaciones?', group: 'n', expected: true },
        { id: '53', content: '¿Generalmente te sientes seguro de que puedas hacer las cosas que tienes que hacer?', group: 'e', expected: true },
        { id: '54', content: '¿A menudo te sientes solo?', group: 'n', expected: true },
        { id: '55', content: '¿Te sientes timido de hablar cuando conoces una nueva persona?', group: 'e', expected: false },

        { id: '56', content: '¿A menudo te decides a hacer algo cuando ya es muy tarde?', group: 'n', expected: true },
        { id: '57', content: '¿Cuando los chicos(as) te gritan, tu les gritan tambien?', group: 'e', expected: true },
        { id: '58', content: '¿Algunas veces te sientes muy alegre y otros momentos tristes sin alguna razón especial?', group: 'n', expected: true },
        { id: '59', content: '¿Encuentras muy dificil disfrutar y divertirte en una fiesta, paseo o juego alegre?', group: 'e', expected: true },
        { id: '60', content: '¿A menudo te metes en problemas o lios por hacer las cosas sin pensar primero?', group: 'n', expected: true },
      ]
    }
  },

  components: { SearchPatient, ExamLabel, Scrollable },

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

    sumatoria() {
      if (!this.dataPersonalPatient.patient_id) {
        document.getElementById('name-patient').focus();
        return;
      }

      if (this.dataPersonalPatient.age == 'Sin escoger') {
        document.getElementById('age').focus();
        return;
      }

      if (this.dataPersonalPatient.gender == 'Sin escoger') {
        document.getElementById('gender').focus();
        return;
      }

      this.calcValueInterpretation('E');
      this.calcValueInterpretation('N');

      this.result.edad = this.dataPersonalPatient.age
      this.values = this.result
      
      this.saveData()
    },

    sendData(data) {
      this.result.E = 0
      this.result.N = 0
      this.result.L = 0

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
        }
      }
    },

    calcValueInterpretation(dim) {
      let selectAge = this.scoreInterpretation[this.dataPersonalPatient.gender][dim][this.dataPersonalPatient.age];

      for (let value in selectAge) {
        let valueInital = selectAge[value][0]
        let valueEnd = selectAge[value][1]

        if (this.result[dim] >= valueInital && this.result[dim] <= valueEnd)
          return this.result[dim] = `${this.result[dim]} - ${value}`;
      }
    }
  }
}
</script>