<template>
  <div>
    <h1>MCMI - II</h1>
  
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
      name: 'mcmi',
      dataPersonalPatient: {
        patient_id: null,
        age: null
      },
      values: {
        resultado: 0,
        edad: null,
        created_at: new Date()
      },
      result: {
        resultado: 0,
        edad: null,
        created_at: new Date()
      },
      criteria: {
        'F': 'Falso',
        'V': 'Verdadero',
      },
      items: [
        {id:'1',content: 'Actúo siempre según mis propias ideas en vez de hacer lo que otros esperan que haga.'},
        {id:'2',content: 'He encontrado siempre más cómodo hacer las cosas solo, tranquilamente, que hacerlas con otros.'},
        {id:'3',content: 'Hablar con la gente ha sido casi siempre difícil y desagradable para mí.'},
        {id:'4',content: 'Creo que tengo que ser enérgico y decidido en todo lo que hago.'},
        {id:'5',content: 'Desde hace algunas semanas me pongo a llorar incluso cuando la menor cosa me sale mal.'},

        {id:'6',content: 'Algunas personas piensan que soy vanidoso y egocéntrico.'},
        {id:'7',content: 'Cuando era adolescente tuve muchos problemas por mi mal comportamiento en el colegio.'},
        {id:'8',content: 'Tengo siempre la impresión de no ser aceptado en un grupo.'},
        {id:'9',content: 'Frecuentemente crítico a la gente que me molesta.'},
        {id:'10',content: 'Me encuentro a gusto siguiendo a los demás.'},

        {id:'11',content: 'Me gusta hacer tantas cosas diferentes que no sé por donde empezar'},
        {id:'12',content: 'Algunas veces puedo ser bastante duro o mezquino con mi familia.'},
        {id:'13',content: 'Tengo poco interés en hacer amigos.'},
        {id:'14',content: 'Me considero una persona muy sociable o extrovertida.'},
        {id:'15',content: 'Sé que soy una persona superior a los demás y por eso no me preocupa lo que piensen.'},



        {id:'16',content: 'La gente nunca ha apreciado suficientemente las cosas que he hecho.'},
        {id:'17',content: 'Tengo problemas con la bebida que he intentado solucionar sin éxito.'},
        {id:'18',content: 'Últimamente siento un nudo en el estómago y me invade un sudor frío.'},
        {id:'19',content: 'Siempre he querido permanecer en segundo plano en las actividades sociales.'},
        {id:'20',content: 'A menudo hago cosas sin ninguna razón, sólo porque pueden ser divertidas.'},

        {id:'21',content: 'Me molesta mucho la gente que no es capaza de hacer las cosas bien.'},
        {id:'22',content: 'Si mi familia me obliga o presiona, es probable que me enfade y me resista a hacer lo que ellos quieren.'},
        {id:'23',content: 'Muchas veces pienso que me deberían castigar por las cosas que he hecho.'},
        {id:'24',content: 'La gente se ríe de mi a mis espaldas, hablando de lo que hago o parezco.'},
        {id:'25',content: 'Los demás parecen más seguros que yo sobre lo que son y lo que quieren.'},

        {id:'26',content: 'Soy propenso a tener explosiones de llanto o cólera sin tener motivo.'},
        {id:'27',content: 'Desde hace uno o dos años he comenzado a sentirme solo y vacío.'},
        {id:'28',content: 'Tengo habilidad para “dramatizar” las cosas.'},
        {id:'29',content: 'Me resulta difícil mantener el equilibrio cuando camino.'},
        {id:'30',content: 'Disfruto en situaciones de intensa competitividad.'},



        {id:'31',content: 'Cuando entro en crisis busco enseguida alguien que me ayude. '},
        {id:'32',content: 'Me protejo de los problemas no dejando que la gente sepa mucho sobre mí.'},
        {id:'33',content: 'Casi siempre me siento débil.'},
        {id:'34',content: 'Otras personas se enfadan mucho más que yo por las cosas molestas.'},
        {id:'35',content: 'A menudo, mi adicción a las drogas me ha causado en el pasado bastantes problemas.'},

        {id:'36',content: 'Últimamente me encuentro llorando sin ningún motivo.'},
        {id:'37',content: 'Creo que soy una persona especial, que necesita que los demás me presten una atención especial.'},
        {id:'38',content: 'Nunca me dejo engañar por gente que dice necesitar ayuda.'},
        {id:'39',content: 'Una buena forma de conseguir un mundo en paz es fomentar los valores morales de la gente.'},
        {id:'40',content: 'En el pasado he mantenido relaciones sexuales con muchas personas que no significaban nada especial para mi.'},

        {id:'41',content: 'Me resulta difícil simpatizar con la gente que se siente siempre insegura con todo.'},
        {id:'42',content: 'Soy una persona muy agradable y dócil.'},
        {id:'43',content: 'La principal causa de mis problemas ha sido mi mal carácter.'},
        {id:'44',content: 'No tengo inconvenientes en forzar a los demás a hacer lo que yo quiero.'},
        {id:'45',content: 'En los últimos años, incluso las cosas sin importancia parecen deprimirme.'},



        {id:'46',content: 'Mi deseo de hacer las cosas lo más perfectas posibles muchas veces enlentece mi trabajo.'},
        {id:'47',content: 'Soy tan callado y retraído que la mayoría de la gente no sabe ni que existo.'},
        {id:'48',content: 'Me gusta coquetear con las personas del otro sexo.'},
        {id:'49',content: 'Soy una persona tranquila y temerosa.'},
        {id:'50',content: 'Soy muy variable y cambio de opiniones y sentimientos continuamente.'},

        {id:'51',content: 'Me pongo muy nervioso cuando pienso en los acontecimientos del día.'},
        {id:'52',content: 'Beber alcohol nunca e ha causado verdaderos problemas en mi trabajo.'},
        {id:'53',content: 'Últimamente me siento sin fuerzas, incluso por la mañana.'},
        {id:'54',content: 'Hace algunos años que he comenzado a sentirme un fracasado.'},
        {id:'55',content: 'No soporto a las personas “sabihondas”, que lo saben todo y piensan que pueden hacer cualquier cosa mejor que yo.'},

        {id:'56',content: 'Ha tenido siempre miedo a perder el afecto de las personas que más necesito.'},
        {id:'57',content: 'Parece que me aparto de mis objetivos, dejando que otros me adelanten.'},
        {id:'58',content: 'Últimamente he comenzado a sentir deseos de tirar y romper cosas. '},
        {id:'59',content: 'Recientemente he pensado muy en serio en quitarme de en medio.'},
        {id:'60',content: 'Siempre estoy buscando hacer nuevos amigos y conocer gente nueva.'},



        {id:'61',content: 'Controlo muy bien mi dinero para estar preparado en caso de necesidad.'},
        {id:'62',content: 'El año pasado aparecí en la portada de varias revistas.'},
        {id:'63',content: 'Le gusto a muy poca gente,'},
        {id:'64',content: 'Si alguien me criticase por cometer un error rápidamente le reprocharía sus propios errores.'},
        {id:'65',content: 'Algunas personas dicen que disfruto sufriendo.'},

        {id:'66',content: 'Muchas veces expreso mi rabia y mal humor y luego me siento terriblemente culpable por ello.'},
        {id:'67',content: 'Últimamente me siento nervioso y bajo una terrible tensión sin saber por qué.'},
        {id:'68',content: 'Muy a menudo pierdo mi capacidad para percibir sensaciones en partes de mi cuerpo.'},
        {id:'69',content: 'Creo que hay personas que utilizan la telepatía para influir en mi vida.'},
        {id:'70',content: 'Tomar las llamadas drogas “ilegales” puede ser indeseable o nocivo, pero reconozco que en le pasado las he tomado.'},

        {id:'71',content: 'Me siento continuamente muy cansado.'},
        {id:'72',content: 'No puedo dormirme y me levanto tan cansado como al acostarme.'},
        {id:'73',content: 'He hecho impulsivamente muchas cosas estúpidas que han llegado a causarme grandes problemas.'},
        {id:'74',content: 'Nunca perdono un insulto ni olvido una situación molesta que alguien me haya provocado.'},
        {id:'75',content: 'Debemos respetar a nuestros mayores y no creer que sabemos más que ellos.'},




        {id:'76',content: 'Me siento muy triste y deprimido la mayor parte del tiempo.'},
        {id:'77',content: 'Soy la típica persona de la que los otros se aprovechan.'},
        {id:'78',content: 'Siempre hago lo posible por complacer a los demás, incluso si ellos no me gustan.'},
        {id:'79',content: 'Durante muchos años he pensado seriamente en suicidarme.'},
        {id:'80',content: 'Me doy cuenta en seguida cuando la gente intenta crearme problemas.'},

        {id:'81',content: 'Siempre he tenido menos interés en el sexo que la mayoría de la gente.'},
        {id:'82',content: 'No comprendo por qué, pero parece que disfruto haciendo sufrir a los que quiero.'},
        {id:'83',content: 'Hace mucho tiempo decidí que lo mejor es tener poco que ver con la gente.'},
        {id:'84',content: 'Estoy dispuesto a luchar hasta el final antes de que nadie obstruya mis intereses y objetivos.'},
        {id:'85',content: 'Desde niño siempre he tenido que tener cuidado con la gente que intentaba engañarme.'},

        {id:'86',content: 'Cuando las cosas son aburridas me gusta provocar algo interesante.'},
        {id:'87',content: 'Tengo un problema con el alcohol que nos ha creado dificultades a mí y a mi familia.'},
        {id:'88',content: 'Si alguien necesitaba hacer algo que requiera mucha paciencia, debería contar conmigo.'},
        {id:'89',content: 'Probablemente tengo las ideas más creativas de entre la gente que conozco.'},
        {id:'90',content: 'No he visto ningún coche en los últimos diez años.'},



        {id:'91',content: 'No veo nada incorrec6to en utilizar la gente para conseguir lo que quiero.'},
        {id:'92',content: 'El que me castiguen nunca me ha frenado de hacer lo que he querido.'},
        {id:'93',content: 'Muchas veces me siento muy alegre y animado, sin ningún motivo.'},
        {id:'94',content: 'Siendo adolescente, me fugué de casa por lo menos una vez.'},
        {id:'95',content: 'Muy a menudo digo cosas sin pensarlas y luego me arrepiento de haberlas dicho.'},

        {id:'96',content: 'En las últimas semanas me he sentido exhausto, agotado, sin un motivo especial.'},
        {id:'97',content: 'Últimamente me he sentido muy culpable porque ya no soy capaz de hacer nada bien.'},
        {id:'98',content: 'Algunas ideas me dan vueltas en la cabeza una y otra vez, y no consigo olvidarlas.'},
        {id:'99',content: 'En los últimos dos años me he vuelto muy desanimado y triste sobre la vida.'},
        {id:'100',content: 'Mucha gente ha estado espiando mi vida privada durante años.'},

        {id:'101',content: 'No sé por qué, pero a veces digo cosas crueles para hacer sufrir a los demás.'},
        {id:'102',content: 'Odio o tengo miedo de la mayor parte de la gente.'},
        {id:'103',content: 'Expreso mi opinión sobre las cosas sin que me importe lo que otros puedan pensar.'},
        {id:'104',content: 'Cuando alguien con autoridad insiste en que haga algo, es probable que lo eluda o bien que lo haga intencionalmente mal.'},
        {id:'105',content: 'En el pasado el hábito de abusar de las drogas me ha hecho no acudir al trabajo.'},



        {id:'106',content: 'Estoy siempre dispuesto a ceder ante los otros para evitar disputas.'},
        {id:'107',content: 'Con frecuencia estoy irritable y del mal humor.'},
        {id:'108',content: 'Últimamente ya no tengo fuerzas para luchar ni para defenderme.'},
        {id:'109',content: 'Últimamente tengo que pensar las cosas una y otra vez sin ningún motivo.'},
        {id:'110',content: 'Muchas veces pienso que no merezco las cosas buenas que me suceden.'},

        {id:'111',content: 'Utilizo mi atractivo para conseguir la atención de los demás.'},
        {id:'112',content: 'Cuando estoy solo, a menudo noto la fuerte presencia de alguien cercano que no puede ser visto.'},
        {id:'113',content: 'Me siento desorientado, sin objetivos, y no sé hacia dónde voy en la vida.'},
        {id:'114',content: 'Últimamente he sudado mucho y me he sentido muy tenso.'},
        {id:'115',content: 'A veces siento como si necesitase hacer algo para hacerme daño a mí mismo o a otros.'},

        {id:'116',content: 'La ley me ha castigado injustamente por delitos que nunca he cometido.'},
        {id:'117',content: 'Me he vuelto muy sobresaltado y nerviosos en las últimas semanas.'},
        {id:'118',content: 'Sigo teniendo extraños pensamientos de los que desearía poder liberarme.'},
        {id:'119',content: 'Tengo muchas dificultades para controlar el impulso de beber en exceso.'},
        {id:'120',content: 'Mucha gente piensa que no sirvo para nada.'},



        {id:'121',content: 'Puedo llegar a estar muy excitado sexualmente cuando discuto o peleo con alguien a quien amo.'},
        {id:'122',content: 'Durante años he conseguido mantener en el mínimo mi consumo de alcohol.'},
        {id:'123',content: 'Siempre pongo a prueba a la gente para saber hasta dónde son de confianza.'},
        {id:'124',content: 'Incluso cuando estoy despierto parece que no me doy cuenta de la gente que está cerca de mí.'},
        {id:'125',content: 'Me resulta fácil hacer muchos amigos.'},

        {id:'126',content: 'Me aseguro siempre de que mi trabajo esté bien planeado y organizado.'},
        {id:'127',content: 'Con mucha frecuencia oigo cosas con tanta claridad que me molesta.'},
        {id:'128',content: 'Mis estados de ánimo parecen cambiar de un día para otro.'},
        {id:'129',content: 'No culpo a quien se aprovecha de alguien que se lo permite.'},
        {id:'130',content: 'He cambiado de trabajo por lo menos más de tres veces en los últimos dos años.'},

        {id:'131',content: 'Tengo muchas ideas muy avanzadas para los tiempos actuales.'},
        {id:'132',content: 'Me siento muy triste y melancólico últimamente y parece que no puedo superarlo.'},
        {id:'133',content: 'Creo que siempre es mejor buscar ayuda para lo que hago.'},
        {id:'134',content: 'Muchas veces me enfado con la gente que hace las cosas lentamente.'},
        {id:'135',content: 'Realmente me molesta la gente que espera que haga lo que yo no quiero hacer.'},



        {id:'136',content: 'En estos últimos años me he sentido tan culpable que puedo hacer algo terrible contra í.'},
        {id:'137',content: 'Cuando estoy en una fiesta o reunión nunca me quedo al margen.'},
        {id:'138',content: 'La gente me dice que soy una persona muy íntegra y moral.'},
        {id:'139',content: 'Algunas veces me siento confuso y preocupado cuando la gente es amable conmigo.'},
        {id:'140',content: 'El problema de usar drogas “ilegales” me ha causado discusiones con mi familia.'},

        {id:'141',content: 'Me siento muy incómodo con personas del otro sexo.'},
        {id:'142',content: 'Algunos miembros de mi familia dicen que soy egoísta y que sólo pienso en mí mismo.'},
        {id:'143',content: 'No me importa que la gente no se interese por mí.'},
        {id:'144',content: 'Francamente, miento con mucha frecuencia para salir de dificultades o problemas.'},
        {id:'145',content: 'La gente puede hacerme cambiar de ideas fácilmente, incluso cuando pienso que ya había tomado una decisión.'},

        {id:'146',content: 'Algunos han tratado de dominarme, pero he tenido fuerza de voluntad para superarlo.'},
        {id:'147',content: 'Mis padres me decían con frecuencia que no era bueno.'},
        {id:'148',content: 'A menudo la gente se irrita conmigo cuando les doy órdenes.'},
        {id:'149',content: 'Tengo mucho respeto por los que tienen autoridad sobre mí.'},
        {id:'150',content: 'No tengo casi ningún lazo íntimo conlos demás.'},



        {id:'151',content: 'En el pasado la gente decía que yo estaba muy interesado y que me apasionaba por demasiadas cosas.'},
        {id:'152',content: 'En el último año he cruzado el Atlántico más de treinta veces.'},
        {id:'153',content: 'Estoy de acuerdo con el refrán: “al que madruga Dios le ayuda”.'},
        {id:'154',content: 'Me merezco el sufrimiento que he padecido a lo largo de mi vida. '},
        {id:'155',content: 'Mis sentimientos hacia las personas importantes en mi vida, muchas veces han oscilado entre amarlas y odiarlas.'},

        {id:'156',content: 'Mis padres nunca se ponían de acuerdo entre ellos.'},
        {id:'157',content: 'En alguna ocasión he bebido diez copas o más sin llegar emborracharme. '},
        {id:'158',content: 'Cuando estoy en una reunión social, en grupo, casi siempre me siento tenso y controlado.'},
        {id:'159',content: 'Tengo en alta estima las normas y reglas porque son una buena guía a seguir.'},
        {id:'160',content: 'Desde que era niño he ido perdiendo contacto con la realidad.'},

        {id:'161',content: 'Rara vez me emociono mucho con algo.'},
        {id:'162',content: 'Habitualmente he sido un andariego, inquieto, vagando de un sitio a otro sin tener idea de dónde terminaría.'},
        {id:'163',content: 'No soporto a las personas que llegan tarde a las citas.'},
        {id:'164',content: 'Gente sin escrúpulos intenta con frecuencia aprovecharse de lo que yo he realizado o ideado.'},
        {id:'165',content: 'Me irita mucho que alguien me pida que haga las cosas a su modo en vez de al mío.'},



        {id:'166',content: 'Tengo habilidad para tener éxito en casi todo lo que hago.'},
        {id:'167',content: 'Últimamente me siento completamente destrozado.'},
        {id:'168',content: 'A la gente que quiero, parece que la animo a que me hiera.'},
        {id:'169',content: 'Nunca he tenido pelo, ni en mi cabeza ni en mi cuerpo.'},
        {id:'170',content: 'Cuando estoy con otras personas me gusta ser el centro de atención.'},

        {id:'171',content: 'Personas que en un principio he admirado grandemente, más tarde me han defraudado al conocer la realidad.'},
        {id:'172',content: 'Soy el tipo de persona que puede abordar a cualquiera y echarle una bronca.'},
        {id:'173',content: 'Prefiero estar con gente que me protegerá.'},
        {id:'174',content: 'He tenido muchos períodos en mi vida que he estado tan animado y he derrochado tanta energía que luego me he sentido muy bajo de.'},
        {id:'175',content: 'En el pasado he tenido dificultades para abandonar el abuso de drogas y de alcohol.'},
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

      this.values.edad = this.dataPersonalPatient.age
      this.values.resultado = this.result.resultado

      this.saveData()
    },

    sendData(data) {
      this.result.resultado = 0
      for(let value in data) {
        this.result.resultado += data[value].value
      }
    },
  }
}
</script>