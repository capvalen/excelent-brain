<template>
  <main>
    <h1>Cuestionario de BDI-2</h1>

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
  name: 'BDI-2',
  components: {SearchPatient, ExamLabel, Scrollable},
  data() {
    return {
      name: 'bdi',
      dataPersonalPatient: {
        patient_id: null,
        age: null
      },
      values: {
        resultado: 0,
        edad: 0,
        created_at: new Date()
      },
      result: {
        resultado: 0,
        edad: 0,
        created_at: new Date()
      },
      criteria: null,
      
      items: [
        {
          id:'1',
          content: 'Tristeza',
          answer: {
            0: 'No me siento triste.',
            1: 'Me siento triste gran parte del tiempo',
            2: 'Me siento triste todo el tiempo.',
            3: 'Me siento tan triste o soy tan infeliz que no puedo soportarlo.',
          }
        },

        {
          id:'2',
          content: 'Pesimismo',
          answer: {
            0: 'No estoy desalentado respecto del mi futuro',
            1: 'Me siento más desalentado respecto de mi futuro que lo que solía estarlo',
            2: 'No espero que las cosas funcionen para mi',
            3: 'Siento que no hay esperanza para mi futuro y que sólo puede empeorar',
          }
        },

        {
          id:'3',
          content: 'Fracaso',
          answer: {
            0: 'No me siento como un fracasado',
            1: 'He fracasado más de lo que hubiera debido',
            2: 'Cuando miro hacia atrás, veo muchos fracasos',
            3: 'Siento que como persona soy un fracaso total',
          }
        },

        {
          id:'4',
          content: 'Pérdida de Placer',
          answer: {
            0: 'Obtengo tanto placer como siempre por las cosas de las que disfruto',
            1: 'No disfruto tanto de las cosas como solía hacerlo',
            2: 'Obtengo muy poco placer de las cosas que solía disfrutar',
            3: 'No puedo obtener ningún placer de las cosas de las que solía disfrutar',
          }
        },

        {
          id:'5',
          content: 'Sentimientos de Culpa',
          answer: {
            0: 'No me siento particularmente culpable',
            1: 'Me siento culpable respecto de varias cosas que he hecho o que debería haber hecho',
            2: 'Me siento bastante culpable la mayor parte del tiempo',
            3: 'Me siento culpable todo el tiempo',
          }
        },

        {
          id:'6',
          content: 'Sentimientos de Castigo',
          answer: {
            0: 'No siento que este siendo castigado',
            1: 'Siento que tal vez pueda ser castigado.',
            2: 'Espero ser castigado.',
            3: 'Siento que estoy siendo castigado.',
          }
        },

        {
          id:'7',
          content: 'Disconformidad con uno mismo',
          answer: {
            0: 'Siento acerca de mi lo mismo que siempre.',
            1: 'He perdido la confianza en mí mismo.',
            2: 'Estoy decepcionado conmigo mismo.',
            3: 'No me gusto a mí mismo.',
          }
        },

        {
          id:'8',
          content: 'Autocrítica',
          answer: {
            0: 'No me critico ni me culpo más de lo habitual',
            1: 'Estoy más crítico conmigo mismo de lo que solía estarlo',
            2: 'Me critico a mí mismo por todos mis errores',
            3: 'Me culpo a mí mismo por todo lo malo que sucede.',
          }
        },

        {
          id:'9',
          content: 'Pensamientos o Deseos Suicidas',
          answer: {
            0: 'No tengo ningún pensamiento de matarme.',
            1: 'He tenido pensamientos de matarme, pero no lo haría',
            2: 'Querría matarme',
            3: 'Me mataría si tuviera la oportunidad de hacerlo.',
          }
        },

        {
          id:'10',
          content: 'Llanto',
          answer: {
            0: 'No lloro más de lo que solía hacerlo.',
            1: 'Lloro más de lo que solía hacerlo',
            2: 'Lloro por cualquier pequeñez.',
            3: 'Siento ganas de llorar pero no puedo.',
          }
        },

        {
          id:'11',
          content: 'Agitación',
          answer: {
            0: 'No estoy más inquieto o tenso que lo habitual.',
            1: 'Me siento más inquieto o tenso que lo habitual.',
            2: 'Estoy tan inquieto o agitado que me es difícil quedarme quieto',
            3: 'Estoy tan inquieto o agitado que tengo que estar siempre en movimiento o haciendo algo.',
          }
        },

        {
          id:'12',
          content: 'Pérdida de Interés',
          answer: {
            0: 'No he perdido el interés en otras actividades o personas.',
            1: 'Estoy menos interesado que antes en otras personas o cosas.',
            2: 'He perdido casi todo el interés en otras personas o cosas.',
            3: 'Me es difícil interesarme por algo.',
          }
        },

        {
          id:'13',
          content: 'Indecisión',
          answer: {
            0: 'Tomo mis propias decisiones tan bien como siempre.',
            1: 'Me resulta más difícil que de costumbre tomar decisiones',
            2: 'Encuentro mucha más dificultad que antes para tomar decisiones.',
            3: 'Tengo problemas para tomar cualquier decisión.',
          }
        },

        {
          id:'14',
          content: 'Desvalorización',
          answer: {
            0: 'No siento que yo no sea valioso',
            1: 'No me considero a mi mismo tan valioso y útil como solía considerarme',
            2: 'Me siento menos valioso cuando me comparo con otros.',
            3: 'Siento que no valgo nada.',
          }
        },

        {
          id:'15',
          content: 'Pérdida de Energía',
          answer: {
            0: 'Tengo tanta energía como siempre.',
            1: 'Tengo menos energía que la que solía tener.',
            2: 'No tengo suficiente energía para hacer demasiado',
            3: 'No tengo energía suficiente para hacer nada.',
          }
        },

        {
          id:'16',
          content: 'Cambios en los Hábitos de Sueño',
          answer: {
            0: 'No he experimentado ningún cambio en mis hábitos de sueño.',
            1: 'Duermo un poco más que lo habitual.',
            2: 'Duermo un poco menos que lo habitual.',
            3: 'Duermo mucho más que lo habitual.',
            4: 'Duermo mucho menos que lo habitual',
            5: 'Duermo la mayor parte del día',
            6: 'Me despierto 1-2 horas más temprano y no puedo volver a dormirme',
          }
        },

        {
          id:'17',
          content: 'Irritabilidad',
          answer: {
            0: 'No estoy tan irritable que lo habitual.',
            1: 'Estoy más irritable que lo habitual.',
            2: 'Estoy mucho más irritable que lo habitual.',
            3: 'Estoy irritable todo el tiempo.',
          }
        },

        {
          id:'18',
          content: 'Cambios en el Apetito',
          answer: {
            0: 'No he experimentado ningún cambio en mi apetito.',
            1: 'Mi apetito es un poco menor que lo habitual.',
            2: 'Mi apetito es un poco mayor que lo habitual.',
            3: 'Mi apetito es mucho menor que antes.',
            4: 'Mi apetito es mucho mayor que lo habitual',
            5: 'No tengo apetito en absoluto.',
            6: 'Quiero comer todo el día.',
          }
        },

        {
          id:'19',
          content: 'Dificultad de Concentración',
          answer: {
            0: 'Puedo concentrarme tan bien como siempre.',
            1: 'No puedo concentrarme tan bien como habitualmente',
            2: 'Me es difícil mantener la mente en algo por mucho tiempo.',
            3: 'Encuentro que no puedo concentrarme en nada.',
          }
        },

        {
          id:'20',
          content: 'Cansancio o Fatiga',
          answer: {
            0: 'No estoy más cansado o fatigado que lo habitual.',
            1: 'Me fatigo o me canso más fácilmente que lo habitual.',
            2: 'Estoy demasiado fatigado o cansado para hacer muchas de las cosas que solía hacer.',
            3: 'Estoy demasiado fatigado o cansado para hacer la mayoría de las cosas que solía hacer.',
          }
        },

        {
          id:'21',
          content: 'Pérdida de Interés en el Sexo',
          answer: {
            0: 'No he notado ningún cambio reciente en mi interés por el sexo.',
            1: 'Estoy menos interesado en el sexo de lo que solía estarlo.',
            2: 'Estoy mucho menos interesado en el sexo.',
            3: 'He perdido completamente el interés en el sexo.',
          }
        },

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
        this.$swal('Resultados registrados correctamente')
      })
      .catch(err => {
        this.$swal({
          icon: 'error',
          title: 'Error...',
          text: err
        })
      })
    },

    sendData(data) {
      this.result.resultado = 0
      for(let value in data) {
        this.result.resultado += data[value].value
      }
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

      this.values.edad = this.dataPersonalPatient.age

      var suma = this.result.resultado
      var interp = ''

      if(suma <= 13){
        interp = 'Depresión Mínima'
      }else if(13 < suma && suma <= 19){
        interp = 'Depresión Leve'
      }else if(19 < suma && suma <= 28){
        interp = 'Depresión Moderada'
      }else{
        interp = 'Depresión Grave'
      }

      this.values.resultado = this.result.resultado+' - '+ interp

      this.saveData()
    }    
  }
}
</script>