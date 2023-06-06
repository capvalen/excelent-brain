<template>
    <div>
      <h1>SRQ Adultos - Adolescentes</h1>
  
      <Scrollable :values="values" @sumatoria="sumatoria" />
  
      <SearchPatient @selectPatient="selectPatient" />
  
      <ExamLabel :items="items" :criteria="criteria" @senddata="sendData" />
    </div>
  </template>
  
  <script>
  import SearchPatient from './SearchPatient.vue';
  import ExamLabel from './ExamLabel.vue'
  import Scrollable from './Scrollable.vue'
  import { saveExams } from '../../../../helpers/httpAxios';
  
  export default {
    name: "SRQ",
  
    data() {
      return {
        name: 'srq',
        checked : [],
        dataPersonalPatient: {
          patient_id: null,
          age: 'Sin escoger'
        },
        scoreInterpretation:{},
        values: {
          Depresion: 0,
          Angustia: 0,
          Psicosis: 0,
          Epilepsia: 0,
          Alcoholismo: 0,
          ISuicida: 0,
          Total:0,
          created_at: new Date()
        },
        result: {
          Depresion: 0,
          Angustia: 0,
          Psicosis: 0,
          Epilepsia: 0,
          Alcoholismo: 0,
          ISuicida: 0,
          Total:0,
          created_at: new Date()
        },
        criteria: {
          'A': 'No',
          'B': 'Si',
        },
        items: [
          { id: '1', content: '¿Tienes frecuentes dolores de cabeza?', group: 'a', expected: true },
          { id: '2', content: '¿Tiene mal apetito?', group: 'dp', expected: true },
          { id: '3', content: '¿Duerme mal?', group: 'dp', expected: true },
          { id: '4', content: '¿Se asusta con facilidad?', group: 'a', expected: false },
          { id: '5', content: '¿Sufre temblor en las manos?', group: 'a', expected: true },
  
          { id: '6', content: '¿Se siente nervioso(a), tenso(a), o aburrido(a)?', group: 'a', expected: false },
          { id: '7', content: '¿Sufre de mala digestión?', group: 'a', expected: true },
          { id: '8', content: '¿No puede pensar con claridad?', group: 'a', expected: true },
          { id: '9', content: '¿Se siente triste?', group: 'dp', expected: true },
          { id: '10', content: '¿LLora usted con mucha frecuencia?', group: 'dp', expected: true },
  
          { id: '11', content: '¿Tiene dificultad en disfrutar sus actividades diarias?', group: 'dp', expected: true },
          { id: '12', content: '¿Tiene dificultad para tomar decisiones?', group: 'dp', expected: false },
          { id: '13', content: '¿Tiene dificultad en hacer su trabajo? (¿Sufre usted con su trabajo?)', group: 'dp', expected: true },
          { id: '14', content: '¿Es incapaz de desempeñar un papel útil en su vida?', group: 'dp', expected: true },
          { id: '15', content: '¿Ha perdido interés en las cosas?', group: 'dp', expected: true },
          { id: '16', content: '¿Siente que usted es una persona inútil?', group: 'dp', expected: true },
          { id: '17', content: '¿Ha tenido la idea de acabar con su vida?', group: 'sui', expected: true },
          { id: '18', content: '¿Se siente cansado todo el tiempo?', group: 'dp', expected: true },
          { id: '19', content: '¿Tiene sensaciones desagradables en su estómago?', group: 'psi', expected: true },
          { id: '20', content: '¿Se cansa con facilidad?', group: 'psi', expected: false },
          { id: '21', content: '¿Siente usted que alguien ha tratado de herirlo en alguna forma?', group: 'psi', expected: true },
          { id: '22', content: '¿Es usted una persona mucho más importante de lo que piensan lo demás?', group: 'psi', expected: true },
          { id: '23', content: '¿Ha notado interferencias o algo raro en su pensamiento?', group: 'n', expected: true },
          { id: '24', content: '¿Oye voces sin saber de dónde vienen o que otras personas no pueden oír?', group: 'l', expected: true },
          { id: '25', content: '¿Ha tenido convulsiones, ataques o caídas al suelo, con movimientos de brazos y piernas, con mordedura de la lengua o pérdida del conocimiento?', group: 'epi', expected: true },
          { id: '26', content: '¿Alguna vez le ha parecido a su familia, sus amigos, su médico o su sacerdote que usted estaba bebiendo demasiado licor?', group: 'oh', expected: true },
          { id: '27', content: '¿Alguna vez ha querido dejar de beber, pero no ha podido?', group: 'oh', expected: true },
          { id: '28', content: '¿Ha tenido alguna vez dificultades en el trabajo (o estudio) a causa de la bebida, como beber en el trabajo o en el colegio, o faltar a ellos?', group: 'oh', expected: true },
          { id: '29', content: '¿Ha estado en riñas o lo han detenido estando borracho?', group: 'oh', expected: true },
          { id: '30', content: '¿Le ha parecido alguna vez que usted bebía demasiado?', group: 'oh', expected: true },
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
          .then(res => { //console.log(res.data);
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
        }else{
					this.saveData()

				}
  
        //alert('calcula suma??')
      },
  
      sendData(data) {
        console.log(data)
        if(!this.checked.includes(data.id)){
          this.checked.push(data.id)

          if(data.value == 1){
            this.values.Total++
            if(data.group == 'a'){
                this.values.Angustia++
            }else if(data.group == 'dp'){
                this.values.Depresion++
                if(data.id == 3 || data.id == 18){
                  this.values.Total++
                  this.values.Angustia++
                }
            }else if (data.group == 'psi'){
                this.values.Psicosis++
            }else if (data.group == 'epi'){
                this.values.Epilepsia++
            }else if (data.group == 'oh'){
                this.values.Alcoholismo++
            }else if(data.group == 'sui'){
                this.values.Total++
                this.values.Depresion++
                this.values.ISuicida++
            }
          }
        }else{
          if(data.value == 1){
            this.values.Total++
            if(data.group == 'a'){
                this.values.Angustia++
            }else if(data.group == 'dp'){
                this.values.Depresion++
                if(data.id == 3 || data.id == 18){
                  this.values.Total++
                  this.values.Angustia++
                }
            }else if (data.group == 'psi'){
                this.values.Psicosis++
            }else if (data.group == 'epi'){
                this.values.Epilepsia++
            }else if (data.group == 'oh'){
                this.values.Alcoholismo++
            }else if(data.group == 'sui'){
                this.values.Total++
                this.values.Depresion++
                this.values.ISuicida++
            }
          }else{
            this.values.Total--
            if(data.group == 'a'){
                this.values.Angustia--
            }else if(data.group == 'dp'){
                this.values.Depresion--
                if(data.id == 3 || data.id == 18){
                  this.values.Total--
                  this.values.Angustia--
                }
            }else if (data.group == 'psi'){
                this.values.Psicosis--
            }else if (data.group == 'epi'){
                this.values.Epilepsia--
            }else if (data.group == 'oh'){
                this.values.Alcoholismo--
            }else if(data.group == 'sui'){
                this.values.Total--
                this.values.Depresion--
                this.values.ISuicida--
            }
          }
        }

        console.log(this.checked)

        //this.checked.push(data.id)
  
         
        this.calcValueInterpretation('a',data)
      },
  
      calcValueInterpretation(dim, fulldata) {
        fulldata = (data) =>{
            console.log(data.group)
        }
      }
    }
  }
  </script>