<template>
    <main>
        <div class="scrollable">
            <div class="row">
                <div class="col-md-6">
                    Patrones clínicos de personalidad
            <br>
            1. Esquizoide : {{esquizoide}}
            <br>
            2A. Evitativo : {{evit}}
            <br>
            2B. Depresivo : {{depre}}
            <br>
            3. Dependiente : {{depen}}
            <br>
            4. Histriónico : {{histri}}
            <br>
            5. Narcicista : {{narci}}
            <br>
            6A. Antisocial : {{antiso}}
            <br>
            6B. Agresivo-sádico: {{agre}}
            <br>
            7. Compulsivo : {{compul}}
            <br>
            8A. Negativista (pasivo-agresivo): {{nega}}
            <br>
            8B.Autodestructiva : {{autodes}}
            <br>
            Patología severa de personalidad
            <br>
            S.Esquizotípica : {{esquizotipica}}
            <br>
            C.Límite : {{limit}}
            <br>
            P.Paranoide : {{paranoid}}
            </div>
                <div class="col-md-6">
                     Síndromes clínicos
            <br>
            A. Trastornos de Ansiedad {{trans_ans}}
            <br>
            H. Trastorno Somatoformo {{trans_somat}}
            <br>
            N. Trastorno Bipolar {{trans_bip}}
            <br>
            D. Trastorno Distímico {{trans_dist}}
            <br>
            B. Dependencia del alcohol {{alcohol}}
            <br>
            T. Dependencia de sustancias {{sustancias}}
            <br>
            R. Trastorno estrés postraumático {{ept}}
            <br>
            Síndromes clínicos graves
            <br>
            SS. Desorden del pensamiento {{pens}}
            <br>
            CC. Depresión mayor {{depre_mayor}}
            <br>
            PP. Desorden delusional {{desilu}}
            <br>
            Escalas Modificadores
            <br>
            X. Sinceridad {{sincer}}
            <br>
            Y. Deseabilidad Social {{social}}
            <br>
            Z. Devaluación {{deval}}
            <br>
            V. Validez {{valid}}
            <br>
                </div>
            </div>


            <button class="btn btn-primary" @click="sumatoria">Calcular y guardar</button>
        </div>
        <h1>MILLON</h1>

        <label for="">Nombre del Paciente (minuscula*)</label>
        <div class="d-flex gap flex-wrap">
            <input type="text" name="" v-model="buscar" id="" class="form-control input-name">
             <div v-show="showResults" class="border border-secondary shadow w-100">
                <div @click="selectPatient(patient)" class="border border-secondary"  v-for="patient in filtro" :key="patient.id">{{patient.name}}</div>
            </div>

            <select v-model="sexo" name="" id="" class="form-select input-select">
                <option value="">Sexo</option>
                <option value="M">Varon</option>
                <option value="F">Mujer</option>
            </select>
        </div>

        <p class="mt-2">
           ¿HASTA QUE PUNTO SE HA SENTIDO AFECTADO POR:?
           Utilice los siguientes criterios:
           <br>
           A: nada B: un poco C: moderadamente D: bastante E: mucho
        </p>

        <ul class="p-0">
            <li v-for="item in items" :key="item.id">
               {{item.id}}. {{item.content}}
               <br>
               <div class="form-check form-check-inline" @click="inputValue(item.id)">
                   <input class="form-check-input" type="radio" :name="`inlineRadioOptions${item.id}`" :id="`inlineRadio${item.id}-a`" value="1">
                   <label class="form-check-label" :for="`inlineRadio${item.id}-a`">V</label>
               </div>
               <div class="form-check form-check-inline" @click="inputValue(item.id)">
                   <input class="form-check-input" type="radio" :name="`inlineRadioOptions${item.id}`" :id="`inlineRadio${item.id}-b`" value="0">
                   <label class="form-check-label" :for="`inlineRadio${item.id}-b`">F</label>
               </div>
            </li>
        </ul>

        <button class="btn btn-success" @click="print">Imprimir Respuestas</button>
    </main>
</template>

<script>
    export default {
        data(){
            return{
                items:[
                {id:'1',content:'Últimamente parece que me quedo sin fuerzas, incluso por la mañana'},
                {id:'2',content:'Me parece muy bien que haya normas porque son una buena guía a seguir'},
                {id:'3',content:'Disfruto haciendo tantas cosas diferentes que no puedo decidir por cuál empezar.'},
                {id:'4',content:'Gran parte del tiempo me siento débil y cansado.'},
                {id:'5',content:'Sé que soy superior a los demás, por eso no me importa lo que píense la gente.'},
                {id:'6',content:'La gente nunca ha reconocido suficientemente las cosas que he hecho.'},
                {id:'7',content:'Si mi familia me presiona, es probable que me enfade y me resista a hacer lo que ellos quieren.'},
                {id:'8',content:'La gente se burla de mí a mis espaldas, hablando de lo que hago o parezco.'},
                {id:'9',content:'Frecuentemente critico mucho a la gente que me irrita.'},
                {id:'10',content:'Raramente exteriorizo las pocas emociones que suelo tener.'},
                {id:'11',content:'Me resulta difícil mantener el equilibrio cuando camino.'},
                {id:'12',content:'Muestro mis emociones fácil y rápidamente.'},
                {id:'13',content:'En el pasado, mis hábitos de tomar drogas me han causado problemas a menudo.'},
                {id:'14',content:'Algunas veces puedo ser bastante duro y desagradable con mi familia.'},
                {id:'15',content:'Las cosas que hoy van bien no durarán mucho tiempo.'},
                {id:'16',content:'Soy una persona muy agradable y sumisa.'},
                {id:'17',content:'Cuando era adolescente, tuve muchos problemas por mi mal comportamiento en el colegio.'},
                {id:'18',content:'Tengo miedo a acercarme mucho a otra persona porque podría acabar siendo ridiculizado o avergonzado.'},
                {id:'19',content:'Parece que elijo amigos que terminan tratándome mal.'},
                {id:'20',content:'He tenido pensamientos tristes gran parte de  mi vida desde que era niño.'},
                {id:'21',content:'Me gusta coquetear con las personas del otro sexo.'},
                {id:'22',content:'Soy una persona muy variable y cambio de opiniones y sentimientos continuamente.'},
                {id:'23',content:'Beber alcohol nunca me ha causado verdaderos problemas en mi trabajo.'},
                {id:'24',content:'Hace unos años comencé a sentirme un fracasado.'},
                {id:'25',content:'Me siento culpable muy a menudo sin ninguna razón.'},
                {id:'26',content:'Los demás envidian mis capacidades.'},
                {id:'27',content:'Cuando puedo elegir, prefiero hacer las cosas solo.'},
                {id:'28',content:'Pienso que el comportamiento de mi familia debería ser estrictamente controlado.'},
                {id:'29',content:'La gente normalmente piensa que soy una persona reservada y seria.'},
                {id:'30',content:'Últimamente he comenzado a sentir deseos de destrozar cosas.'},
                {id:'31',content:'Creo que soy una persona especial y merezco que los demás me presten una particular atención.'},
                {id:'32',content:'Siempre estoy buscando hacer nuevos amigos y conocer gente nueva.'},
                {id:'33',content:'Si alguien me criticase por cometer un error, rápidamente le señalaría sus propios errores.'},
                {id:'34',content:'Últimamente he perdido los nervios.'},
                {id:'35',content:'A menudo renuncio a hacer cosas porque temo no hacerlas bien.'},
                {id:'36',content:'Muchas veces me dejo llevar por mis emociones de ira y luego me siento terriblemente culpable por ello.'},
                {id:'37',content:'Muy a menudo pierdo mi capacidad para percibir sensaciones en partes de mi cuerpo.'},
                {id:'38',content:'Hago lo que quiero sin preocuparme de las consecuencias que tenga en los demás.'},
                {id:'39',content:'Tomar las llamadas "drogas ilegales" puede ser imprudente, pero reconozco que en el pasado las he necesitado.'},
                {id:'40',content:'Creo que soy una persona miedosa  e inhibida.'},
                {id:'41',content:'He hecho impulsivamente muchas cosas estúpidas que han llegado a causarme grandes problemas.'},
                {id:'42',content:'Nunca perdono un insulto ni olvido una situación embarazosa que alguien me haya causado.'},
                {id:'43',content:'A menudo me siento triste o tenso, inmediatamente después de que me haya pasado algo bueno.'},
                {id:'44',content:'Ahora me siento terriblemente deprimido y triste gran parte del tiempo.'},
                {id:'45',content:'Siempre hago lo posible por complacer a los demás, incluso a quienes no me gustan.'},
                {id:'46',content:'Siempre he sentido menos interés por el sexo que la mayoría de la gente.'},
                {id:'47',content:'Siempre tiendo a culparme a mí mismo cuando las cosas salen mal.'},
                {id:'48',content:'Hace mucho tiempo decidí que lo mejor es tener poco que ver con la gente.'},
                {id:'49',content:'Desde niño, siempre he tenido que tener cuidado con la gente que intentaba engañarme.'},
                {id:'50',content:'No soporto a las personas influyentes que siempre piensan que pueden hacer las cosas mejor que yo.'},
                {id:'51',content:'Cuando las cosas son aburridas, me gusta provocar algo interesante o divertido.'},
                {id:'52',content:'Tengo un problema con el alcohol que nos ha creado dificultades a mi familia y a mí.'},
                {id:'53',content:'Los castigos nunca me han impedido hacer lo que yo quería.'},
                {id:'54',content:'Muchas veces me siento muy alegre y animado sin ninguna razón.'},
                {id:'55',content:'En las últimas semanas me he sentido agotado sin ningún motivo especial.'},
                {id:'56',content:'Últimamente me he sentido muy culpable porque ya no soy capaz de hacer nada bien.'},
                {id:'57',content:'Pienso que soy una persona muy sociable y extravertida.'},
                {id:'58',content:'Me he vuelto muy nervioso en las últimas semanas.'},
                {id:'59',content:'Controlo muy bien mi dinero para estar preparado en caso de necesidad.'},
                {id:'60',content:'Simplemente, no he tenido la suerte que otros han tenido en la vida.'},
                {id:'61',content:'Algunas ideas me dan vueltas en la cabeza una y otra vez y no desaparecen.'},
                {id:'62',content:'Desde hace uno o dos años, al pensar sobre la vida, me siento muy triste y desanimado.'},
                {id:'63',content:'Mucha gente ha estado espiando mi vida privada durante años.'},
                {id:'64',content:'No sé por qué pero, a veces, digo cosas crueles sólo para hacer sufrir a los demás.'},
                {id:'65',content:'En el último año he cruzado el Atlántico en avión 30 veces.'},
                {id:'66',content:'En el pasado, el hábito de abusar de las drogas me ha hecho faltar al trabajo.'},
                {id:'67',content:'Tengo muchas ideas que son avanzadas para los tiempos actuales.'},
                {id:'68',content:'Últimamente tengo que pensar las cosas una y otra vez sin ningún motivo.'},
                {id:'69',content:'Evito la mayoría de las situaciones sociales porque creo que la gente va a criticarme o rechazarme.'},
                {id:'70',content:'Muchas veces pienso que no merezco las cosas buenas que me pasan.'},
                {id:'71',content:'Cuando estoy solo, a menudo noto cerca de mí la fuerte presencia de alguien que no puede ser visto.'},
                {id:'72',content:'Me siento desorientado, sin objetivos, y no sé hacia dónde voy en la vida.'},
                {id:'73',content:'A menudo dejo que los demás tomen por mí decisiones importantes.'},
                {id:'74',content:'No puedo dormirme, y me levanto tan cansado como al acostarme.'},
                {id:'75',content:'Últimamente sudo mucho y me siento muy tenso.'},
                {id:'76',content:'Tengo una y otra vez pensamientos extraños de los que desearía poder librarme.'},
                {id:'77',content:'Tengo muchos problemas para controlar el impulso de beber en exceso.'},
                {id:'78',content:'Aunque esté despierto, parece que no me doy cuenta de la gente que está cerca de mí.'},
                {id:'79',content:'Con frecuencia estoy irritado y de mal humor.'},
                {id:'80',content:'Para mí es muy fácil hacer muchos amigos.'},
                {id:'81',content:'Me avergüenzo de algunos de los abusos que sufrí cuando era joven.'},
                {id:'82',content:'Siempre me aseguro de que mi trabajo esté bien planeado y organizado.'},
                {id:'83',content:'Mis estados de ánimo cambian mucho de un día para otro.'},
                {id:'84',content:'Me falta confianza en mí mismo para arriesgarme a probar algo nuevo.'},
                {id:'85',content:'No culpo a quien se aprovecha de alguien que se lo permite.'},
                {id:'86',content:'Desde hace algún tiempo me siento triste y deprimido y no consigo animarme.'},
                {id:'87',content:'A menudo me enfado con la gente que hace las cosas lentamente.'},
                {id:'88',content:'Cuando estoy en una fiesta nunca me aíslo de los demás.'},
                {id:'89',content:'Observo a mi familia de cerca para saber en quién se puede confiar y en quién no.'},
                {id:'90',content:'Algunas veces me siento confuso y molesto cuando la gente es amable conmigo.'},
                {id:'91',content:'El consumo de "drogas ilegales" me ha causado discusiones con mi familia.'},
                {id:'92',content:'Estoy solo la mayoría del tiempo y lo prefiero así.'},
                {id:'93',content:'Algunos miembros de mi familia dicen que soy egoísta y que sólo pienso en mí mismo.'},
                {id:'94',content:'La gente puede hacerme cambiar de ideas fácilmente, incluso cuando pienso que ya había tomado una decisión.'},
                {id:'95',content:'A menudo irrito a la gente cuando les doy órdenes.'},
                {id:'96',content:'En el pasado la gente decía que yo estaba muy interesado y apasionado por demasiadas cosas.'},
                {id:'97',content:'Estoy de acuerdo con el refrán: "Al que madruga Dios le ayuda".'},
                {id:'98',content:'Mis sentimientos hacia las personas importantes en mi vida muchas veces oscilan entre el amor y el odio.'},
                {id:'99',content:'Cuando estoy en una reunión social, en grupo, casi siempre me siento tenso y cohibido.'},
                {id:'100',content:'Supongo que no soy diferente de mis padres ya que, hasta cierto punto, me he convertido en un alcohólico.'},
                {id:'101',content:'Creo que no me tomo muchas de las responsabilidades familiares tan seriamente como debería.'},
                {id:'102',content:'Desde que era niño he ido perdiendo contacto con la realidad.'},
                {id:'103',content:'Gente mezquina intenta con frecuencia aprovecharse de lo que he realizado o ideado.'},
                {id:'104',content:'No puedo experimentar mucho placer porque no creo merecerlo.'},
                {id:'105',content:'Tengo pocos deseos de hacer amigos íntimos.'},
                {id:'106',content:'He tenido muchos periodos en mi vida en los que he estado tan animado y he consumido tanta energía que luego me he sentido muy bajo de ánimo.'},
                {id:'107',content:'He perdido completamente mi apetito y la mayoría de las noches tengo problemas para dormir.'},
                {id:'108',content:'Me preocupa mucho que me dejen solo y tenga que cuidar de mí mismo.'},
                {id:'109',content:'El recuerdo de una experiencia muy perturbadora de mi pasado sigue apareciendo en mis pensamientos.'},
                {id:'110',content:'El año pasado aparecí en la portada de varias revistas.'},
                {id:'111',content:'Parece que he perdido el interés en la mayoría de las cosas que solía encontrar placenteras, como el sexo.'},
                {id:'112',content:'He estado abatido y triste mucho tiempo en mi vida desde que era bastante joven.'},
                {id:'113',content:'Me he metido en problemas con la ley un par de veces.'},
                {id:'114',content:'Una buena manera de evitar los errores es tener una rutina para hacer las cosas.'},
                {id:'115',content:'A menudo otras personas me culpan de cosas que no he hecho.'},
                {id:'116',content:'He tenido que ser realmente duro con algunas personas para mantenerlas a raya.'},
                {id:'117',content:'La gente piensa que, a veces, hablo sobre cosas extrañas o diferentes a las de ellos.'},
                {id:'118',content:'Ha habido veces en las que no he podido pasar el día sin tomar drogas.'},
                {id:'119',content:'La gente está intentando hacerme creer que estoy loco.'},
                {id:'120',content:'Haría algo desesperado para impedir que me abandonase una persona que quiero.'},
                {id:'121',content:'Sigo dándome atracones de comida un par de veces a la semana.'},
                {id:'122',content:'Parece que echo a perder las buenas oportunidades que se cruzan en mi camino.'},
                {id:'123',content:'Siempre me ha resultado difícil dejar de sentirme deprimido y triste.'},
                {id:'124',content:'Cuando estoy solo y lejos de casa, a menudo comienzo a sentirme tenso y lleno de pánico.'},
                {id:'125',content:'A veces las personas se molestan conmigo porque dicen que hablo mucho o demasiado deprisa para ellas.'},
                {id:'126',content:'Hoy, la mayoría de la gente de éxito ha sido afortunada o deshonesta.'},
                {id:'127',content:'No me involucro con otras personas a no ser que esté seguro de que les voy a gustar.'},
                {id:'128',content:'Me siento profundamente deprimido sin ninguna razón que se me ocurra.'},
                {id:'129',content:'Años después, todavía tengo pesadillas acerca de un acontecimiento que supuso una amenaza real para mi vida.'},
                {id:'130',content:'Ya no tengo energía para concentrarme en mis responsabilidades diarias.'},
                {id:'131',content:'Beber alcohol me ayuda cuando me siento deprimido.'},
                {id:'132',content:'Odio pensar en algunas de las formas en las que se abusó de mí cuando era un niño.'},
                {id:'133',content:'Incluso en los buenos tiempos, siempre he tenido miedo de que las cosas pronto fuesen mal.'},
                {id:'134',content:'Algunas veces, cuando las cosas empiezan a torcerse en mi vida, me siento como si estuviera loco o fuera de la realidad.'},
                {id:'135',content:'Estar solo, sin la ayuda de alguien cercano de quien depender, realmente me asusta.'},
                {id:'136',content:'Sé que he gastado más dinero del que debiera comprando "drogas ilegales".'},
                {id:'137',content:'Siempre compruebo que he terminado mi trabajo antes de tomarme un descanso para actividades de ocio.'},
                {id:'138',content:'Noto que la gente está hablando de mí cuando paso a su lado.'},
                {id:'139',content:'Se me da muy bien inventar excusas cuando me meto en problemas.'},
                {id:'140',content:'Creo que hay una conspiración contra mí.'},
                {id:'141',content:'Siento que la mayoría de la gente tiene una mala opinión de mí.'},
                {id:'142',content:'Frecuentemente siento que no hay nada dentro de mí, como si estuviera vacío y hueco.'},
                {id:'143',content:'Algunas veces me obligo a vomitar después de comer.'},
                {id:'144',content:'Creo que me esfuerzo mucho por conseguir que los demás admiren las cosas que hago o digo.'},
                {id:'145',content:'Me paso la vida preocupándome por una cosa u otra.'},
                {id:'146',content:'Siempre me pregunto cuál es la razón real de que alguien sea especialmente agradable conmigo.'},
                {id:'147',content:'Ciertos pensamientos vuelven una y otra vez a mi mente.'},
                {id:'148',content:'Pocas cosas en la vida me dan placer.'},
                {id:'149',content:'Me siento tembloroso y tengo dificultades para conciliar el sueño debido a dolorosos recuerdos de un hecho pasado que pasan por mi cabeza repetidamente.'},
                {id:'150',content:'Pensar en el futuro al comienzo de cada día me hace sentir terriblemente deprimido.'},
                {id:'151',content:'Nunca he sido capaz de librarme de sentir que no valgo nada para los demás.'},
                {id:'152',content:'Tengo un problema con la bebida que he tratado de solucionar sin éxito.'},
                {id:'153',content:'Alguien ha estado intentando controlar mi mente.'},
                {id:'154',content:'He intentado suicidarme.'},
                {id:'155',content:'Estoy dispuesto a pasar hambre para estar aún más delgado de lo que estoy.'},
                {id:'156',content:'No entiendo por qué algunas personas me sonríen.'},
                {id:'157',content:'No he visto un coche en los últimos diez años.'},
                {id:'158',content:'Me pongo muy tenso con las personas que no conozco bien, porque pueden querer hacerme daño.'},
                {id:'159',content:'Alguien tendría que ser bastante excepcional para entender mis habilidades especiales.'},
                {id:'160',content:'Mi a afectada por "imágenes mentales" de algo terrible que me pasó.'},
                {id:'161',content:'Parece que creo situaciones con los demás en las que acabo herido o me siento rechazado.'},
                {id:'162',content:'A menudo me pierdo en mis pensamientos y me olvido de lo que está pasando a mi alrededor.'},
                {id:'163',content:'La gente dice que soy una persona delgada, pero creo que mis muslos y mi trasero son demasiado grandes.'},
                {id:'164',content:'Hay terribles hechos de mi pasado que vuelven repetidamente para perseguirme en mis pensamientos y sueños.'},
                {id:'165',content:'No tengo amigos íntimos al margen de mi familia.'},
                {id:'166',content:'Casi siempre actúo rápidamente y no pienso las cosas tanto como debiera.'},
                {id:'167',content:'Tengo mucho cuidado en mantener mi vida como algo privado, de manera que nadie pueda aprovecharse de mí.'},
                {id:'168',content:'Con mucha frecuencia oigo las cosas con tanta claridad que me molesta.'},
                {id:'169',content:'Siempre estoy dispuesto a ceder en una riña o desacuerdo porque temo el enfado o rechazo de los demás.'},
                {id:'170',content:'Repito ciertos comportamientos una y otra vez, algunas veces para reducir mi ansiedad y otras para evitar que pase algo malo.'},
                {id:'171',content:'Recientemente he pensado muy en serio en quitarme de en medio.'},
                {id:'172',content:'La gente me dice que soy una persona muy formal y moral.'},
                {id:'173',content:'Todavía me aterrorizo cuando pienso en una experiencia traumática que tuve hace años.'},
                {id:'174',content:'Aunque me da miedo hacer amistades, me gustaría tener más de las que tengo.'},
                {id:'175',content:'A algunas personas que se supone que son mis amigos les gustaría hacerme daño.'}
                ],
                esquizoide:0,
                evit:0,
                depre:0,
                depen:0,
                histri:0,
                narci:0,
                antiso:0,
                agre:0,
                compul:0,
                nega:0,
                autodes:0,
                esquizotipica:0,
                limit:0,
                paranoid:0,
                trans_ans:0,
                trans_somat:0,
                trans_bip:0,
                trans_dist:0,
                alcohol:0,
                sustancias:0,
                ept:0,
                pens:0,
                depre_mayor:0,
                desilu:0,
                sincer:0,
                social:0,
                deval:0,
                valid:0,
                objs:[],
                sexo:'',
                buscar:'',
                showResults:false,
                patients:[],
                patient_id :''
            }
        },

        methods:{
            inputValue(nro){
                var name = 'inlineRadioOptions'+nro
                var rpta = document.querySelector('input[name='+name+']:checked').value;
                var obj = {nro, rpta}

                if(this.verifDuplicated(obj)){

                    this.objs.push({...obj})
                }else{

                }


                console.log(this.objs)

                /* var filter = this.objs.filter(obj => obj.nro == nro)

                console.log(filter)

                if(filter.lenght != 0){
                    this.objs.splice(obj,1)
                    obj= {nro, rpta}
                    this.objs.push({...obj})
                }else{

                }

                console.log(this.objs) */



            },
            verifDuplicated(obj2){
                if(this.objs.filter(obj=>obj.nro == obj2.nro).length == 0){
                    return true
                }else{
                    console.log('coincidencia de nro')
                    this.objs = this.objs.filter(obj=>obj.nro != obj2.nro)
                    this.objs.push({...obj2})
                    return false
                }
            },
            sumatoria(){
                this.esquizoide=0,
                this.evit=0,
                this.depre=0,
                this.depen=0,
                this.histri=0,
                this.narci=0,
                this.antiso=0,
                this.agre=0,
                this.compul=0,
                this.nega=0,
                this.autodes=0,
                this.esquizotipica=0,
                this.limit=0,
                this.paranoid=0,
                this.trans_ans=0,
                this.trans_somat=0,
                this.trans_bip=0,
                this.trans_dist=0,
                this.alcohol=0,
                this.sustancias=0,
                this.ept=0,
                this.pens=0,
                this.depre_mayor=0,
                this.desilu=0,
                this.sincer=0,
                this.social=0,
                this.deval=0,
                this.valid=0

                this.objs.forEach((value, index)=>{

                var nro = value.nro
                var rpta = value.rpta

                if(nro == 4 || nro == 10 || nro == 27 || nro == 32 || nro == 38 || nro == 46 || nro == 48 || nro == 57 || nro == 92 || nro == 101 || nro == 105 || nro == 142 || nro == 146 || nro == 156 || nro == 165 || nro == 167){
                    this.esquizoide += parseInt(rpta)
                }else if(nro == 18 || nro == 40 || nro == 47 || nro == 48 || nro == 57 || nro == 69 || nro == 80 || nro == 84 || nro == 99 || nro == 127 || nro == 141 || nro == 146 || nro == 148 || nro == 151 || nro == 158 || nro == 174){
                    this.evit += parseInt(rpta)
                }else if(nro == 20 || nro == 24 || nro == 25 || nro == 43 || nro == 47 || nro == 83 || nro == 86 || nro == 112 || nro == 123 || nro == 133 || nro == 142 || nro == 145 || nro == 148 || nro == 151 || nro == 154){
                    this.depre += parseInt(rpta)
                }else if(nro == 16 || nro == 35 || nro == 45 || nro == 47 || nro == 56 || nro == 73 || nro == 82 || nro == 84 || nro == 94 || nro == 108 || nro == 120 || nro == 133 || nro == 135 || nro == 141 || nro == 151 || nro == 169){
                    this.depen += parseInt(rpta)
                }else if(nro == 10 || nro == 12 || nro == 21 || nro == 24 || nro == 27 || nro == 32 || nro == 48 || nro == 51 || nro == 57 || nro == 69 || nro == 80 || nro == 88 || nro == 92 || nro == 99 || nro == 123 || nro == 127 || nro == 174){
                    this.histri += parseInt(rpta)
                }else if(nro == 5 || nro == 21 || nro == 26 || nro == 31 || nro == 35 || nro == 38 || nro == 40 || nro == 47 || nro == 57 || nro == 67 || nro == 69 || nro == 80 || nro == 84 || nro == 85 || nro == 86 || nro == 88 || nro == 93 || nro == 94 || nro == 99 || nro == 116 || nro == 141 || nro == 144 || nro == 159 || nro == 169){
                    this.narci += parseInt(rpta)
                }else if(nro == 7 || nro == 13 || nro == 14 || nro == 17 || nro == 21 || nro == 38 || nro == 41 || nro == 52 || nro == 53 || nro == 93 || nro == 101 || nro == 113 || nro == 122 || nro == 136 || nro == 139 || nro == 166 || nro == 172){
                    this.antiso += parseInt(rpta)
                }else if(nro == 7 || nro == 9 || nro == 13 || nro == 14 || nro == 17 || nro == 28 || nro == 33 || nro == 36 || nro == 39 || nro == 41 || nro == 49 || nro == 53 || nro == 64 || nro == 79 || nro == 87 || nro == 93 || nro == 95 || nro == 96 || nro == 116 || nro == 166){
                    this.agre += parseInt(rpta)
                }else if(nro == 2 || nro == 7 || nro == 14 || nro == 22 || nro == 29 || nro == 41 || nro == 53 || nro == 59 || nro == 72 || nro == 82 || nro == 97 || nro == 101 || nro == 114 || nro == 137 || nro == 139 || nro == 166 || nro == 172){
                    this.compul += parseInt(rpta)
                }else if(nro == 6 || nro == 7 || nro == 15 || nro == 22 || nro == 36 || nro == 42 || nro == 50 || nro == 60 || nro == 79 || nro == 83 || nro == 98 || nro == 115 || nro == 122 || nro == 126 || nro == 133 || nro == 166){
                    this.nega += parseInt(rpta)
                }else if(nro == 18 || nro == 19 || nro == 24 || nro == 25 || nro == 35 || nro == 40 || nro == 43 || nro == 70 || nro == 90 || nro == 98 || nro == 104 || nro == 122 || nro == 148 || nro == 161 || nro == 169){
                    this.autodes += parseInt(rpta)
                }else if(nro == 8 || nro == 48 || nro == 69 || nro == 71 || nro == 76 || nro == 99 || nro == 102 || nro == 117 || nro == 134 || nro == 138 || nro == 141 || nro == 148 || nro == 151 || nro == 156 || nro == 158 || nro == 162){
                    this.esquizotipica += parseInt(rpta)
                }else if(nro == 7 || nro == 22 || nro == 30 || nro == 41 || nro == 72 || nro == 83 || nro == 98 || nro == 120 || nro == 122 || nro == 134 || nro == 135 || nro == 142 || nro == 154 || nro == 161 || nro == 171 || nro == 166){
                    this.limit += parseInt(rpta)
                }else if(nro == 6 || nro == 8 || nro == 33 || nro == 42 || nro == 48 || nro == 49 || nro == 60 || nro == 63 || nro == 89 || nro == 103 || nro == 115 || nro == 138 || nro == 146 || nro == 158 || nro == 159 || nro == 167 || nro == 175){
                    this.paranoid += parseInt(rpta)
                }else if(nro == 40 || nro == 58 || nro == 61 || nro == 65 || nro == 76 || nro == 108 || nro == 109 || nro == 124 || nro == 135 || nro == 145 || nro == 147 || nro == 149 || nro == 164 || nro == 170){
                    this.trans_ans += parseInt(rpta)
                }else if(nro == 1 || nro == 4 || nro == 11 || nro == 37 || nro == 55 || nro == 74 || nro == 75 || nro == 107 || nro == 111 || nro == 130 || nro == 145 || nro == 148){
                    this.trans_somat += parseInt(rpta)
                }else if(nro == 3 || nro == 22 || nro == 41 || nro == 51 || nro == 54 || nro == 83 || nro == 96 || nro == 106 || nro == 117 || nro == 125 || nro == 134 || nro == 166 || nro == 170){
                    this.trans_bip += parseInt(rpta)
                }else if(nro == 15 || nro == 24 || nro == 25 || nro == 55 || nro == 62 || nro == 83 || nro == 86 || nro == 104 || nro == 111 || nro == 130 || nro == 141 || nro == 142 || nro == 148){
                    this.trans_dist += parseInt(rpta)
                }else if(nro == 14 || nro == 23 || nro == 41 || nro == 52 || nro == 64 || nro == 77 || nro == 93 || nro == 100 || nro == 101 || nro == 113 || nro == 122 || nro == 131 || nro == 139 || nro == 152 || nro == 166){
                    this.alcohol += parseInt(rpta)
                }else if(nro == 7 || nro == 13 || nro == 21 || nro == 38 || nro == 39 || nro == 41 || nro == 53 || nro == 66 || nro == 91 || nro == 101 || nro == 113 || nro == 118 || nro == 136 || nro == 139){
                    this.sustancias += parseInt(rpta)
                }else if(nro == 62 || nro == 76 || nro == 83 || nro == 109 || nro == 123 || nro == 129 || nro == 133 || nro == 142 || nro == 147 || nro == 148 || nro == 149 || nro == 151 || nro == 154 || nro == 160 || nro == 164 || nro == 173){
                    this.ept += parseInt(rpta)
                }else if(nro == 22 || nro == 34 || nro == 56 || nro == 61 || nro == 68 || nro == 72 || nro == 76 || nro == 78 || nro == 83 || nro == 102 || nro == 117 || nro == 134 || nro == 142 || nro == 148 || nro == 151 || nro == 162 || nro == 168){
                    this.pens += parseInt(rpta)
                }else if(nro == 1 || nro == 4 || nro == 34 || nro == 44 || nro == 55 || nro == 74 || nro == 104 || nro == 107 || nro == 111 || nro == 128 || nro == 130 || nro == 142 || nro == 148 || nro == 149 || nro == 150 || nro == 154 || nro == 171){
                    this.depre_mayor += parseInt(rpta)
                }else if(nro == 5 || nro == 38 || nro == 49 || nro == 63 || nro == 67 || nro == 89 || nro == 103 || nro == 119 || nro == 138 || nro == 140 || nro == 153 || nro == 159 || nro == 175){
                    this.desilu += parseInt(rpta)
                }else if(nro == 20 || nro == 32 || nro == 35 || nro == 40 || nro == 51 || nro == 57 || nro == 59 || nro == 69 || nro == 80 || nro == 82 || nro == 88 || nro == 97 || nro == 104 || nro == 112 || nro == 123 || nro == 137 || nro == 141 || nro == 148 || nro == 151 || nro == 172){
                    this.social += parseInt(rpta)
                }else if(nro == 1 || nro == 4 || nro == 8 || nro == 15 || nro == 22 || nro == 24 || nro == 30 || nro == 34 || nro == 36 || nro == 44 || nro == 55 || nro == 56 || nro == 58 || nro == 62 || nro == 63 || nro == 70 || nro == 74 || nro == 75 || nro == 76 || nro == 83 || nro == 84 || nro == 86 || nro == 99 || nro == 111 || nro == 123 || nro == 128 || nro == 133 || nro == 134 || nro == 142 || nro == 145 || nro == 150 || nro == 171){
                    this.deval += parseInt(rpta)
                }else if(nro == 65 || nro == 110 || nro == 157){
                    this.valid += parseInt(rpta)
                }
                })


                this.sincer = this.esquizoide + this.evit+ this.depre+ this.depen + this.histri + this.narci + this.antiso + this.agre + this.compul + this.nega + this.autodes;



                //INSERT
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData()
                formData.append('schizoid', this.esquizoide)
                formData.append('avoidant', this.evit)
                formData.append('depressant', this.depre)
                formData.append('dependent', this.depen)
                formData.append('histrionic', this.histri)
                formData.append('narcissistic', this.narci)
                formData.append('antisocial', this.antiso)
                formData.append('agressive_sadistic', this.agre)
                formData.append('compulsive', this.compul)
                formData.append('naysayer', this.nega)
                formData.append('self_destructive', this.autodes)
                formData.append('schizotypal', this.esquizotipica)
                formData.append('limit', this.limit)
                formData.append('paranoid', this.paranoid)
                formData.append('anxiety_disorder', this.trans_ans)
                formData.append('somatoform_disorder', this.trans_somat)
                formData.append('bipolar_disorder', this.trans_bip)
                formData.append('dysthymic_disorder', this.trans_dist)
                formData.append('alcohol_dependence', this.alcohol)
                formData.append('substance_dependence', this.sustancias)
                formData.append('post_traumatic_stress_disorder', this.ept)
                formData.append('thought_disorder', this.pens)
                formData.append('major_depression', this.depre_mayor)
                formData.append('delusional_disorder', this.desilu)
                formData.append('sincerity', this.sincer)
                formData.append('social_desirability', this.social)
                formData.append('devaluation', this.deval)
                formData.append('validaity', this.valid)
                formData.append('patient_id', this.patient_id)
                formData.append('professional_id', this.$attrs.professional.id)

                this.axios.post('/api/saveMillon', formData, config)
                .then((result) => {
                    this.$swal('Resultados regitrados correctamente')
                }).catch((err) => {
                    this.$swal({
                        icon: 'error',
                        title: 'Error...',
                        text: err
                    })
                });
            },
            print(){
                window.print();
            },
            getNames(){
                this.axios.get('/api/getNames')
                .then((result) => {
                    this.patients = result.data
                    //console.log(this.patients)
                }).catch((err) => {

                });
            },
            selectPatient(patient){
                this.$swal('Usuario aceptado')
                this.patient_id = patient.id
                this.buscar = patient.name
                this.showResults = false
            }
        },
        computed:{
        filtro(){
            if(!this.buscar){
                this.showResults = false
                return null
            }else{
                this.showResults = true
                return this.patients.filter(patient => patient.name.toLowerCase().includes(this.buscar)).slice(0,6)
            }
        }
        },
        mounted(){
            this.getNames()
        }
    }
</script>

<style scoped>
    ul{
        list-style: none;
    }

    li{
        background-color: #fff;
        font-size: 20px;
        border: 1px solid rgba(0, 0, 0, 0.493);
        margin: 10px 0px 10px 0px;
        border-radius: 5px;
        padding: 10px;
    }

    .input-name {
        width: 70%;
        flex: 1 0 auto;
        min-width: 250px;
    }

    .input-select {
        width: 25%;
        flex: 1 0 auto;
    }

    .scrollable{
        font-size: 12px;
        background-color: #fff;
        color: #000;
        position: fixed;
        right: 0;
        border: 1px solid rgba(0, 0, 0, 0.493);
        z-index: 99;
        border-radius: 5px;
        padding: 5px;
    }

    .gap {
        gap: 15px;
    }

    @media screen and (max-width: 750px) {
        .scrollable {
            position: relative;
        }
    }
</style>
