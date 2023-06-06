<template>
  <div>
    <h1>BARON</h1>
  
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
    :valuesDefault="valuesDefault"
    @senddata="sendData"
    />
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
      name: 'baron',
      patient_id: null,
      dataPersonalPatient: {
        patient_id: null,
        age: null
      },
      values: {
        edad: null,
        ['GENERAL']: 0,
        ['INTRAPERSONAL']: 0,
        ['Comprensión de sí mismo (CM)']: 0,
        ['Asertividad (AS)']: 0,
        ['Autoconcepto (AC)']: 0,
        ['Autorrealizacion (AR)']: 0,
        ['Independencia (IN)']: 0,
        ['INTERPERSONAL']: 0,
        ['Relaciones Interpersonales (RI)']: 0,
        ['Responsabilidad Social (RS)']: 0,
        ['Empatia (EM)']: 0,
        ['ADAPTABILIDAD']: 0,
        ['Solucion de Problemas (SP)']: 0,
        ['Prueba de la Realidad (PR)']: 0,
        ['Flexibilidad (FL)']: 0,
        ['MANEJO DEL ESTRES']: 0,
        ['Tolerancia al Estrés (TE)']: 0,
        ['Control de Impulsos (CI)']: 0,
        ['ESTADO DE ANIMO G']: 0,
        ['Felicidad (FE)']: 0,
        ['Optimismo (OP)']: 0,
        ['VALIDEZ']: '',
        ['Impresión Positiva']: 0,
        ['Impresión Negativa']: 0,
        created_at: new Date()
      },
      result: {
        edad: null,
        ['GENERAL']: 0,
        ['INTRAPERSONAL']: 0,
        ['Comprensión de sí mismo (CM)']: 0,
        ['Asertividad (AS)']: 0,
        ['Autoconcepto (AC)']: 0,
        ['Autorrealizacion (AR)']: 0,
        ['Independencia (IN)']: 0,
        ['INTERPERSONAL']: 0,
        ['Relaciones Interpersonales (RI)']: 0,
        ['Responsabilidad Social (RS)']: 0,
        ['Empatia (EM)']: 0,
        ['ADAPTABILIDAD']: 0,
        ['Solucion de Problemas (SP)']: 0,
        ['Prueba de la Realidad (PR)']: 0,
        ['Flexibilidad (FL)']: 0,
        ['MANEJO DEL ESTRES']: 0,
        ['Tolerancia al Estrés (TE)']: 0,
        ['Control de Impulsos (CI)']: 0,
        ['ESTADO DE ANIMO G']: 0,
        ['Felicidad (FE)']: 0,
        ['Optimismo (OP)']: 0,
        ['VALIDEZ']: '',
        ['Impresión Positiva']: 0,
        ['Impresión Negativa']: 0,
        created_at: new Date()
      },
      normativeData: {
        ['Positive Impression']: [20.35,	5.30],
        ['Negative Impression']: [10.66,	4.26],
        ['Inconsistency Index']: [5.32,	2.89],

        ['TOTAL EQ']: [465.31,	49.99],
        ['Intrapersonl EQ']: [156.70,	20.47],
        ['Interpersonal EQ']: [99.52,	10.85],
        ['Adaptability EQ']: [100.32,	12.46],
        ['Stress Management  EQ']: [68.27,	9.66],
        ['General Mood EQ']: [70.50,	8.74],

        ['Emotional self-Awareness']: [29.79,	5.28],
        ['Assertiveness']: [25.82,	4.66],
        ['Self-Regard']: [35.78,	6.30],
        ['Self-Actualization']: [37.72,	5.07],
        ['Independence']: [27.37,	4.48],
        ['Empathy']: [33.51,	4.19],
        ['Interpersonal Relationship']: [44.23,	6.02],
        ['Social Responsability']: [43.27,	4.93],
        ['Problem Solving']: [31.79,	4.60],
        ['Reality Testing']: [39.53,	5.58],
        ['Flexibility']: [28.94,	4.86],
        ['Stress Tolerance']: [33.66,	5.64],
        ['Impulse Control']: [34.59,	5.63],
        ['Happiness']: [37.48,	5.13],
        ['Optimism']: [32.98,	4.46],
      },
      criteria: {
        'A': 'Rara vez o nunca es mi caso',
        'B': 'Pocas veces es mi caso',
        'C': 'A veces es mi caso',
        'D': 'Muchas veces es mi caso',
        'E': 'Con mucha frecuencia o Siempre es mi caso.',
      },
      valuesDefault: ["1", "2", "3", "4", "5"],
      items: [
        {id:'1',content: 'Para superar las dificultades que se me presentan actúo paso a paso.', group: ['sp'], valueTotal: 0},
        {id:'2',content: 'Me resulta dificil disfrutar de la vida.', group: ['fe'], convert: 6, valueTotal: 0},
        {id:'3',content: 'Prefiero un tipo de trabajo en el que se me indique todo lo que debe hacer.', group: ['in'], valueTotal: 0},
        {id:'4',content: 'Sé como manejar los problemas más desagradables.', group: ['te'], valueTotal: 0},
        {id:'5',content: 'Me agradan las personas que conozco.', valueTotal: 0, group: ['ip']},

        {id:'6',content: 'Trato de valorar y darle el mejor sentido a mi vida.', group: ['ar'], valueTotal: 0},
        {id:'7',content: 'Me resulta relativamente fácil expresar mis sentimientos.', group: ['cm'], valueTotal: 0},
        {id:'8',content: 'Trato de ser realista, no me gusta fantasear ni soñar despierto(a).', group: ['pr'], valueTotal: 0},
        {id:'9',content: 'Reconozco con facilidad cuales son mis emociones.', group: ['cm'], valueTotal: 0},
        {id:'10',content: 'Soy incapaz de demostrar afecto.', group: ['ri'], convert: 6, valueTotal: 0},

        {id:'11',content: 'Me siento seguro(a) de mí mismo(a) en la mayoría de situaciones.', group: ['ac', 'op'], valueTotal: 0},
        {id:'12',content: 'Tengo la sensación que algo no está bien en mi cabeza.', valueTotal: 0, group: ['in']},
        {id:'13',content: 'Tengo problemas para controlarme cuando me enojo.', group: ['ci'], convert: 6, valueTotal: 0},
        {id:'14',content: 'Me resulta difícil comenzar cosas nuevas.', group: ['fl'], convert: 6, valueTotal: 0},
        {id:'15',content: 'Frente a una situación problemática .. obtengo la mayor cantidad información posible para comprender mejor fo que está pasando.', group: ['sp'], valueTotal: 0},



        {id:'16',content: 'Me gusta ayudar a la gente.', group: ['rs'], valueTotal: 0},
        {id:'17',content: 'Me es dificil sonreír.', group: ['fe'], convert: 6, valueTotal: 0},
        {id:'18',content: 'Soy capaz de comprender cómo se sienten los demás.', group: ['em'], convert: 6, valueTotal: 0},
        {id:'19',content: 'Cuando trabajo con otras personas, tiendo a confiar más en las ideas de los demás que en las mías propias.', group: ['in'], convert: 6, valueTotal: 0},
        {id:'20',content: 'Creo que tengo la capacidad para poder controlar las situaciones difíciles.', group: ['te', 'op'], valueTotal: 0},

        {id:'21',content: 'No puedo identificar mis cualidades, no sé realmente para que cosas soy bueno(a).', group: ['ar'], convert: 6, valueTotal: 0},
        {id:'22',content: 'No soy capaz de expresar mis sentimientos.', group: ['as'], convert: 6, valueTotal: 0},
        {id:'23',content: 'Me es diticil compartir mis sentimientos más profundos.', group: ['cm', 'ri'], convert: 6, valueTotal: 0},
        {id:'24',content: 'No tengo confianza en mí mismo(a).', group: ['ac'], convert: 6, valueTotal: 0},
        {id:'25',content: 'Creo que he perdido la cabeza.', valueTotal: 0, group: ['in']},

        {id:'26',content: 'Casi todo lo que bago lo hago con entusiasmo.', group: ['op'], valueTotal: 0},
        {id:'27',content: 'Cuando comienzo a hablar me resulta difícil detenerme.', group: ['ci'], convert: 6, valueTotal: 0},
        {id:'28',content: 'En general, me resulta dilicil adaptarme a los cambios.', group: ['fl'], convert: 6, valueTotal: 0},
        {id:'29',content: 'Antes de intentar solucionar un problema me gusta saber más sobre lo que está pasando.', group: ['sp'], convert: 6, valueTotal: 0},
        {id:'30',content: 'No me molesta aprovecharme de los demás, especialmente si se lo merecen.', group: ['rs'], convert: 6, valueTotal: 0},



        {id:'31',content: 'Soy una persona bastante alegre y optimista.', group: ['ri', 'fe'], valueTotal: 0},
        {id:'32',content: 'Prefiero que los otros tomen las decisiones por mí.', group: ['in'], convert: 6, valueTotal: 0},
        {id:'33',content: 'Puedo manejar situaciones de estrés, sin ponerme demasiado nervioso.', group: ['te'], valueTotal: 0},
        {id:'34',content: 'Tengo pensamientos positivos para con los demás.', valueTotal: 0, group: ['ip']},
        {id:'35',content: 'Me es dificil entender como me siento.', group: ['cm', 'pr'], convert: 6, valueTotal: 0},

        {id:'36',content: 'He logrado muy poco en los últimos años.', group: ['ar'], convert: 6, valueTotal: 0},
        {id:'37',content: 'Cuando estoy enojado(a) con alguien se lo puedo decir.', group: ['as'], valueTotal: 0},
        {id:'38',content: 'He tenido experiencias extrañas que son inexplicables.', group: ['pr'], convert: 6, valueTotal: 0},
        {id:'39',content: 'Me resulta fácil hacer amigos(as).', group: ['ri'], valueTotal: 0},
        {id:'40',content: 'Me tengo mucho respeto.', group: ['ac'], valueTotal: 0},

        {id:'41',content: 'Hago cosas muy raras.', valueTotal: 0, group: ['in']},
        {id:'42',content: 'Soy impulsivo(a), y eso me trae problemas.', group: ['ci'], convert: 6, valueTotal: 0},
        {id:'43',content: 'Me resulta difícil cambiar de opinión.', group: ['fl'], convert: 6, valueTotal: 0},
        {id:'44',content: 'Soy capaz de comprender los sentimientos ajenos.', group: ['em'], valueTotal: 0},
        {id:'45',content: 'Lo primero que bago cuando tengo un problema es detenerme a pensar.', group: ['sp'], valueTotal: 0},



        {id:'46',content: 'A la gente le resulta dificil confiar en mí.', group: ['rs'], convert: 6, valueTotal: 0},
        {id:'47',content: 'Estoy contento(a) con mi vida.', group: ['fe'], valueTotal: 0},
        {id:'48',content: 'Me resulta dificil tomar decisiones por mi mismo(a).', group: ['in'], convert: 6, valueTotal: 0},
        {id:'49',content: 'No resisto las situaciones problemáticas.', group: ['te'], convert: 6, valueTotal: 0},
        {id:'50',content: 'En mi vida no hago nada malo.', valueTotal: 0, group: ['ip']},

        {id:'51',content: 'No disfruto lo que hago.', group: ['ar'], convert: 6, valueTotal: 0},
        {id:'52',content: 'Me resulta dificil expresar mis sentimientos más profundos.', group: ['cm'], convert: 6, valueTotal: 0},
        {id:'53',content: 'La gente no comprende mi manera de pensar.', group: ['pr'], convert: 6, valueTotal: 0},
        {id:'54',content: 'En general, espero que suceda lo mejor.', group: ['op'], valueTotal: 0},
        {id:'55',content: 'Mis amistades me confían sus intimidades.', group: ['ri', 'em'], valueTotal: 0},

        {id:'56',content: 'No me siento bien conmigo mismo(a).', group: ['ac'], convert: 6, valueTotal: 0},
        {id:'57',content: 'Percibo cosas extrañas que los demás no ven.', valueTotal: 0, group: ['in']},
        {id:'58',content: 'La gente me dice que baje el tono de voz cuando discuto.', group: ['ci'], convert: 6, valueTotal: 0},
        {id:'59',content: 'Me resulta fácil adaptarme a situaciones nuevas.', group: ['fl'], convert: 6, valueTotal: 0},
        {id:'60',content: 'Frente a una situación problemática analizo todas las posibles soluciones y luego escojo la que considero es la correcta.', group: ['sp'], valueTotal: 0},



        {id:'61',content: 'Si veo a un niño llorando me detengo a ayudarlo a encontrar a sus padres, aunque en ese momento tenga otro compromiso.', group: ['rs', 'em'], valueTotal: 0},
        {id:'62',content: 'Soy una persona divertida.', group: ['ri', 'fe'], valueTotal: 0},
        {id:'63',content: 'Soy consciente de cómo me siento.', group: ['cm'], valueTotal: 0},
        {id:'64',content: 'Siento que me resulta dificil controlar mi ansiedad.', group: ['te'], convert: 6, valueTotal: 0},
        {id:'65',content: 'Nada me perturba.', valueTotal: 0, group: ['ip']},

        {id:'66',content: 'No me entusiasman mucho mis intereses.', group: ['ar'], convert: 6, valueTotal: 0},
        {id:'67',content: 'Cuando no estoy de acuerdo con alguien siento que se lo puedo decir.', group: ['as'], valueTotal: 0},
        {id:'68',content: 'Tengo una tendencia a perder contacto con la realidad y a fantasear.', group: ['pr'], convert: 6, valueTotal: 0},
        {id:'69',content: 'Me es difícil relacionarme con los demás.', group: ['ri'], convert: 6, valueTotal: 0},
        {id:'70',content: 'Me resulta dificil aceptarme tal cual soy.', group: ['ac'], convert: 6, valueTotal: 0},

        {id:'71',content: 'Me siento como si estuviera separado(a) de mi cuerpo. ', valueTotal: 0, group: ['in']},
        {id:'72',content: 'Me importa lo que puede sucederle a los demás.', group: ['rs', 'em'], valueTotal: 0},
        {id:'73',content: 'Generalmente pierdo la paciencia.', group: ['ci'], convert: 6, valueTotal: 0},
        {id:'74',content: 'Puedo cambiar mis viejas costumbres.', group: ['fl'], valueTotal: 0},
        {id:'75',content: 'Me resulta dificil escoger la mejor solución cuando tengo que resolver un problema.', group: ['sp'], convert: 6, valueTotal: 0},




        {id:'76',content: 'Si pudiera violar Ja Jey sin pagar las consecuencias, lo haría en determinadas situaciones.', group: ['rs'], convert: 6, valueTotal: 0},
        {id:'77',content: 'Generalmente me siento triste.', group: ['fe'], convert: 6, valueTotal: 0},
        {id:'78',content: 'Sé como mantener la calma en situaciones difíciles.', group: ['te'], valueTotal: 0},
        {id:'79',content: 'Nunca he mentido.', valueTotal: 0, group: ['ip']},
        {id:'80',content: 'En general me siento con ganas para seguir adelante, incluso cuando las cosas se ponen difíciles.', group: ['op'], valueTotal: 0},

        {id:'81',content: 'Trato de seguir adelante con las cosas que me gustan.', group: ['ar'], valueTotal: 0},
        {id:'82',content: 'Me resulta difícil decir "no" aunque tenga el deseo de hacerlo.', group: ['as'], convert: 6, valueTotal: 0},
        {id:'83',content: 'Me dejo llevar por mi imaginación y mis fantasías', group: ['pr'], convert: 6, valueTotal: 0},
        {id:'84',content: 'Mis relaciones más cercanas significan mucho, tanto par mí como para mis amigos.', group: ['ri'], valueTotal: 0},
        {id:'85',content: 'Me siento feliz conmigo mismo(a).', group: ['ac'], valueTotal: 0},

        {id:'86',content: 'Tengo reacciones fuertes, intensas que son difíciles de controlar.', group: ['ci'], convert: 6, valueTotal: 0},
        {id:'87',content: 'En general, me resulta dificil realizar cambios en mi vida cotidiana.', group: ['fl'], convert: 6, valueTotal: 0},
        {id:'88',content: 'Soy consciente de lo que me está pasando, aún cuando estoy alteradora),', group: ['cm', 'pr'], valueTotal: 0},
        {id:'89',content: 'Para poder resolver una situación que se presenta, analizo todas las posibilidades existentes.', group: ['sp'], valueTotal: 0},
        {id:'90',content: 'Soy respetnoso(a) con los demás.', group: ['rs'], valueTotal: 0},



        {id:'91',content: 'No estoy muy contento(a) con mi vida.', group: ['fe'], convert: 6, valueTotal: 0},
        {id:'92',content: 'Prefiero seguir a otros a ser libres.', group: ['in'], convert: 6, valueTotal: 0},
        {id:'93',content: 'Me resulta dificil enfrentar las cosas desagradables de la vida.', group: ['te'], convert: 6, valueTotal: 0},
        {id:'94',content: 'Nunca he violado la ley.', valueTotal: 0, group: ['ip']},
        {id:'95',content: 'Disfruto de las cosas que me interesan.', group: ['ar'], valueTotal: 0},

        {id:'96',content: 'Me resulta relativamente fácil decirle a la gente lo que pienso.', group: ['as'], valueTotal: 0},
        {id:'97',content: 'Tengo tendencia a exagerar.', group: ['pr'], convert: 6, valueTotal: 0},
        {id:'98',content: 'Soy sensible a los sentimientos de las otras personas.', group: ['rs', 'em'], valueTotal: 0},
        {id:'99',content: 'Mantengo buenas relaciones con la gente.', group: ['ri'], valueTotal: 0},
        {id:'100',content: 'Estoy contento(a) con mi cuerpo', group: ['ac'], valueTotal: 0},

        {id:'101',content: 'Soy una persona muy rara.', valueTotal: 0, group: ['in']},
        {id:'102',content: 'Soy impulsivo(a).', group: ['ci'], convert: 6, valueTotal: 0},
        {id:'103',content: 'Me resulta difícil cambiar mis costumbres.', group: ['fl'], convert: 6, valueTotal: 0},
        {id:'104',content: 'Considero que es muy importante ser un(a) ciudadano(a) que respeta la ley.', group: ['rs'], valueTotal: 0},
        {id:'105',content: 'Disfruto las vacaciones y los fines de semana.', group: ['fe'], valueTotal: 0},



        {id:'106',content: 'En general tengo una actitud positiva para todo, aún cuando surgen problemas.', group: ['op'], valueTotal: 0},
        {id:'107',content: 'Tengo tendencia a depender de los demás.', group: ['in'], convert: 6, valueTotal: 0},
        {id:'108',content: 'Creo en mi capacidad para manejar los problemas más difíciles.', group: ['te', 'op'], valueTotal: 0},
        {id:'109',content: 'No me siento avergonzado(a) por nada de lo que he hecho hasta ahora.', valueTotal: 0, group: ['ip']},
        {id:'110',content: 'Trato de aprovechar al máximo las cosas que me gustan.', group: ['ar'], valueTotal: 0},

        {id:'111',content: 'Los demás piensan que no me hago valer, que me falta firmeza.', group: ['as'], convert: 6, valueTotal: 0},
        {id:'112',content: 'Soy capaz de dejar de fantasear para volver aponerme en contacto con la realidad.', group: ['pr'], valueTotal: 0},
        {id:'113',content: 'Los demás opinan que soy una persona sociable.', group: ['ri'], valueTotal: 0},
        {id:'114',content: 'Estoy contento(a) con la forma en que me veo.', group: ['ac'], valueTotal: 0},
        {id:'115',content: 'Tengo pensamientos extraños que los demás no logran entender.', valueTotal: 0, group: ['in']},

        {id:'116',content: 'Me es difícil describir lo que siento.', group: ['cm'], convert: 6, valueTotal: 0},
        {id:'117',content: 'Tengo mal carácter.', group: ['ci'], convert: 6, valueTotal: 0},
        {id:'118',content: 'Por lo general, tengo problemas para decidir sobre diferentes opiniones para resolver un problema.', group: ['sp'], convert: 6, valueTotal: 0},
        {id:'119',content: 'Me es difícil ver sufrir a 1a gente.', group: ['rs', 'em'], valueTotal: 0},
        {id:'120',content: 'Me gusta divertirme.', group: ['fe'], valueTotal: 0},



        {id:'121',content: 'Me parece que necesito de los demás más de lo que ellos me necesitan.', group: ['in'], convert: 6, valueTotal: 0},
        {id:'122',content: 'Me pongo ansioso.', group: ['te'], convert: 6, valueTotal: 0},
        {id:'123',content: 'Nunca tengo un mal día.', valueTotal: 0, group: ['ip']},
        {id:'124',content: 'Intento no herir los sentimientos de los demás.', group: ['em'], valueTotal: 0},
        {id:'125',content: 'No tengo idea de lo que quiero hacer en la vida.', group: ['ar'], convert: 6, valueTotal: 0},

        {id:'126',content: 'Me es dificil hacer valer mis derechos.', group: ['as'], convert: 6, valueTotal: 0},
        {id:'127',content: 'Me es difícil ser realista.', group: ['pr'], convert: 6, valueTotal: 0},
        {id:'128',content: 'No mantengo relación con mis amistades,', group: ['ri'], convert: 6, valueTotal: 0},
        {id:'129',content: 'Mis cualidades superan mis defectos y esto me permite estar contento(a) conmigo mismo(a).', group: ['ac'], valueTotal: 0},
        {id:'130',content: 'Tengo una tendencia a explotar de rabia fácilmente.', group: ['ci'], convert: 6, valueTotal: 0},

        {id:'131',content: 'Si me viera obligado(a) a dejar mi casa actual, me sería dificil adaptarme nuevamente.', group: ['fl'], convert: 6, valueTotal: 0},
        {id:'132',content: 'En general, cuando comienzo algo nuevo tengo la sensación que voy a fracasar.', group: ['op'], convert: 6, valueTotal: 0},
        {id:'133',content: 'He respondido sincera y honestamente a las frases anteriores.', valueTotal: 0},
      ]
    }
  },
  
  components: {SearchPatient, ExamLabel, Scrollable},
  
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

    sumatoria() {
      if (!this.dataPersonalPatient.patient_id) {
        document.getElementById('name-patient').focus();
        return;
      }

      if (!this.dataPersonalPatient.age) {
        document.getElementById('age').focus();
        return;
      }

      this.values['Comprensión de sí mismo (CM)'] = this.formula(this.result['Comprensión de sí mismo (CM)'], this.normativeData['Emotional self-Awareness'])
      this.values['Asertividad (AS)'] = this.formula(this.result['Asertividad (AS)'], this.normativeData['Assertiveness'])
      this.values['Autoconcepto (AC)'] = this.formula(this.result['Autoconcepto (AC)'], this.normativeData['Self-Regard'])
      this.values['Autorrealizacion (AR)'] = this.formula(this.result['Autorrealizacion (AR)'], this.normativeData['Self-Actualization'])
      this.values['Independencia (IN)'] = this.formula(this.result['Independencia (IN)'], this.normativeData['Independence'])
      this.values['Relaciones Interpersonales (RI)'] = this.formula(this.result['Relaciones Interpersonales (RI)'], this.normativeData['Interpersonal Relationship'])
      this.values['Responsabilidad Social (RS)'] = this.formula(this.result['Responsabilidad Social (RS)'], this.normativeData['Social Responsability'])
      this.values['Empatia (EM)'] = this.formula(this.result['Empatia (EM)'], this.normativeData['Empathy'])
      this.values['Solucion de Problemas (SP)'] = this.formula(this.result['Solucion de Problemas (SP)'], this.normativeData['Problem Solving'])
      this.values['Prueba de la Realidad (PR)'] = this.formula(this.result['Prueba de la Realidad (PR)'], this.normativeData['Reality Testing'])
      this.values['Flexibilidad (FL)'] = this.formula(this.result['Flexibilidad (FL)'], this.normativeData['Flexibility'])
      this.values['Tolerancia al Estrés (TE)'] = this.formula(this.result['Tolerancia al Estrés (TE)'], this.normativeData['Stress Tolerance'])
      this.values['Control de Impulsos (CI)'] = this.formula(this.result['Control de Impulsos (CI)'], this.normativeData['Impulse Control'])
      this.values['Felicidad (FE)'] = this.formula(this.result['Felicidad (FE)'], this.normativeData['Happiness'])
      this.values['Optimismo (OP)'] = this.formula(this.result['Optimismo (OP)'], this.normativeData['Optimism'])

      this.values['Impresión Positiva'] = this.formula(this.result['Impresión Positiva'], this.normativeData['Positive Impression'])
      this.values['Impresión Negativa'] = this.formula(this.result['Impresión Negativa'], this.normativeData['Negative Impression'])

      this.values['INTRAPERSONAL'] = this.formula(this.result['INTRAPERSONAL'], this.normativeData['Intrapersonl EQ'])
      this.values['INTERPERSONAL'] = this.formula(this.result['INTERPERSONAL'], this.normativeData['Interpersonal EQ'])
      this.values['ADAPTABILIDAD'] = this.formula(this.result['ADAPTABILIDAD'], this.normativeData['Adaptability EQ'])
      this.values['MANEJO DEL ESTRES'] = this.formula(this.result['MANEJO DEL ESTRES'], this.normativeData['Stress Management  EQ'])
      this.values['ESTADO DE ANIMO G'] = this.formula(this.result['ESTADO DE ANIMO G'], this.normativeData['General Mood EQ'])
      this.values['GENERAL'] = this.formula(this.result['GENERAL'], this.normativeData['TOTAL EQ']);

      this.values.edad = this.dataPersonalPatient.age

      this.saveData()
    },

    formula(result, value) {
      if (result == 0) return
      return Math.round((((result - value[0]) / value[1]) * 15) + 100)
    },

    sendData(data) {
      for(let value in data) {
        if (data[value].convert) {
          data[value].valueTotal = 6 - data[value].value 
        } else {
          data[value].valueTotal = data[value].value 
        }
      }

      for(let value in this.result) {
        this.result[value] = 0
      }

      for(let value in data) {
        if (data[value].group && data[value].group.includes('cm')) {
          this.result['Comprensión de sí mismo (CM)'] += parseInt(data[value].valueTotal)
          // this.values['Cononimiento emocional a si mismo'] = formula(this.values['Cononimiento emocional a si mismo'], this.normativeData['Emotional self-Awareness'])
        }

        if (data[value].group && data[value].group.includes('as')) {
          this.result['Asertividad (AS)'] += parseInt(data[value].valueTotal)
          // this.values['Asertividad (AS)'] = formula(this.values['Asertividad (AS)'], this.normativeData['Assertiveness'])

        }

        if (data[value].group && data[value].group.includes('ac')) {
          this.result['Autoconcepto (AC)'] += parseInt(data[value].valueTotal)
          // this.values['Autoconcepto (AC)'] = formula(this.values['Autoconcepto (AC)'], this.normativeData['Self-Regard'])

        }

        if (data[value].group && data[value].group.includes('ar')) {
          this.result['Autorrealizacion (AR)'] += parseInt(data[value].valueTotal)
          // this.values['Autorrealizacion (AR)'] = formula(this.values['Autorrealizacion (AR)'], this.normativeData['Self-Actualization'])

        }
        
        if (data[value].group && data[value].group.includes('in')) {
          this.result['Independencia (IN)'] += parseInt(data[value].valueTotal)
          // this.values['Independencia (IN)'] = formula(this.values['Independencia (IN)'], this.normativeData['Independence'])

        }
        
        if (data[value].group && data[value].group.includes('ri')) {
          this.result['Relaciones Interpersonales (RI)'] += parseInt(data[value].valueTotal)
          // this.values['Relaciones Interpersonales (RI)'] = formula(this.values['Relaciones Interpersonales (RI)'], this.normativeData['Interpersonal Relationship'])

        }
        
        if (data[value].group && data[value].group.includes('rs')) {
          this.result['Responsabilidad Social (RS)'] += parseInt(data[value].valueTotal)
          // this.values['Responsabilidad Social (RS)'] = formula(this.values['Responsabilidad Social (RS)'], this.normativeData['Social Responsability'])

        }
        
        if (data[value].group && data[value].group.includes('em')) {
          this.result['Empatia (EM)'] += parseInt(data[value].valueTotal)
          // this.values['Empatia (EM)'] = formula(this.values['Empatia (EM)'], this.normativeData['Empathy'])

        }
        
        if (data[value].group && data[value].group.includes('sp')) {
          this.result['Solucion de Problemas (SP)'] += parseInt(data[value].valueTotal)
          // this.values['Solucion de Problemas (SP)'] = formula(this.values['Solucion de Problemas (SP)'], this.normativeData['Problem Solving'])

        }
        
        if (data[value].group && data[value].group.includes('pr')) {
          this.result['Prueba de la Realidad (PR)'] += parseInt(data[value].valueTotal)
          // this.values['Prueba de la Realidad (PR)'] = formula(this.values['Prueba de la Realidad (PR)'], this.normativeData['Reality Testing'])

        }
        
        if (data[value].group && data[value].group.includes('fl')) {
          this.result['Flexibilidad (FL)'] += parseInt(data[value].valueTotal)
          // this.values['Flexibilidad (FL)'] = formula(this.values['Flexibilidad (FL)'], this.normativeData['Flexibility'])

        }
        
        if (data[value].group && data[value].group.includes('te')) {
          this.result['Tolerancia al Estrés (TE)'] += parseInt(data[value].valueTotal)
          // this.values['Tolerancia al Estrés (TE)'] = formula(this.values['Tolerancia al Estrés (TE)'], this.normativeData['Stress Tolerance'])

        }
        
        if (data[value].group && data[value].group.includes('ci')) {
          this.result['Control de Impulsos (CI)'] += parseInt(data[value].valueTotal)
          // this.values['Control de Impulsos (CI)'] = formula(this.values['Control de Impulsos (CI)'], this.normativeData['Impulse Control'])

        }
        
        if (data[value].group && data[value].group.includes('fe')) {
          this.result['Felicidad (FE)'] += parseInt(data[value].valueTotal)
          // this.values['Felicidad (FE)'] = formula(this.values['Felicidad (FE)'], this.normativeData['Happiness'])
        }
        
        if (data[value].group && data[value].group.includes('op')) {
          this.result['Optimismo (OP)'] += parseInt(data[value].valueTotal)
          // this.values['Optimismo (OP)'] = formula(this.values['Optimismo (OP)'], this.normativeData['Optimism'])
        }

        if (data[value].group && data[value].group.includes('ip')) {
          this.result['Impresión Positiva'] += parseInt(data[value].valueTotal)
          // this.values['Optimismo (OP)'] = formula(this.values['Optimismo (OP)'], this.normativeData['Optimism'])
        }

        if (data[value].group && data[value].group.includes('ip')) {
          this.result['Impresión Positiva'] += parseInt(data[value].valueTotal)
          // this.values['Optimismo (OP)'] = formula(this.values['Optimismo (OP)'], this.normativeData['Optimism'])
        }

        if (data[value].group && data[value].group.includes('ip')) {
          this.result['Impresión Negativa'] += parseInt(data[value].valueTotal)
          // this.values['Optimismo (OP)'] = formula(this.values['Optimismo (OP)'], this.normativeData['Optimism'])
        }
      }

      this.result['INTRAPERSONAL'] = this.result['Comprensión de sí mismo (CM)'] + this.result['Asertividad (AS)'] + this.result['Autoconcepto (AC)'] + this.result['Autorrealizacion (AR)'] + this.result['Independencia (IN)']
      this.result['INTERPERSONAL'] = this.result['Relaciones Interpersonales (RI)'] + this.result['Responsabilidad Social (RS)'] + this.result['Empatia (EM)']
      this.result['ADAPTABILIDAD'] = this.result['Solucion de Problemas (SP)'] + this.result['Prueba de la Realidad (PR)'] + this.result['Flexibilidad (FL)']
      this.result['MANEJO DEL ESTRES'] = this.result['Tolerancia al Estrés (TE)'] + this.result['Control de Impulsos (CI)']
      this.result['ESTADO DE ANIMO G'] = this.result['Optimismo (OP)'] + this.result['Felicidad (FE)']

      if (data.inlineRadioOptions55 && data.inlineRadioOptions61 && data.inlineRadioOptions72 && data.inlineRadioOptions98 && data.inlineRadioOptions119) {
        this.result['INTERPERSONAL'] = this.result['INTERPERSONAL'] - (parseInt(data.inlineRadioOptions55.valueTotal) + parseInt(data.inlineRadioOptions61.valueTotal) + parseInt(data.inlineRadioOptions72.valueTotal) + parseInt(data.inlineRadioOptions98.valueTotal) + parseInt(data.inlineRadioOptions119.valueTotal)) 
        if (data.inlineRadioOptions11 && data.inlineRadioOptions20 && data.inlineRadioOptions23 && data.inlineRadioOptions31 && data.inlineRadioOptions35 && data.inlineRadioOptions62 && data.inlineRadioOptions88 && data.inlineRadioOptions108) {
          this.result['GENERAL'] = this.result['INTRAPERSONAL'] + this.result['INTERPERSONAL'] + this.result['ADAPTABILIDAD'] + this.result['MANEJO DEL ESTRES'] + this.result['ESTADO DE ANIMO G']
          this.result['GENERAL'] = this.result['GENERAL'] - (parseInt(data.inlineRadioOptions11.valueTotal) + parseInt(data.inlineRadioOptions20.valueTotal) + parseInt(data.inlineRadioOptions23.valueTotal) + parseInt(data.inlineRadioOptions31.valueTotal) + parseInt(data.inlineRadioOptions35.valueTotal) + parseInt(data.inlineRadioOptions62.valueTotal) + parseInt(data.inlineRadioOptions88.valueTotal) + parseInt(data.inlineRadioOptions108.valueTotal))
        } else {
          this.result['GENERAL'] = 0
        }
      }
    },
  }
}
</script>