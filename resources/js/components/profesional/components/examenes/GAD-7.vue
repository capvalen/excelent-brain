<template>
  <div>
    <h1>Cuestionario de GAD-7</h1>
    
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
import SearchPatient from './SearchPatient.vue'
import ExamLabel from './ExamLabel.vue'
import Scrollable from './Scrollable.vue'
import { saveExams } from '../../../../helpers/httpAxios'

export default {
  name: 'GAD-7',

  data() {
    return {
      name: 'gad',
      dataPersonalPatient: {
        patient_id: null,
        age: null
      },
      result: 0, 
      values: {
        suma: 0,
        edad: 0,
        created_at: new Date()
      },
      criteria: {
        'A': 'Nunca',
        'B': 'Menos de la mitad de los días',
        'C': 'Más de la mitad de los días',
        'D': 'Casi todos los días'
      },
      items: [
        {id:'1',content: 'Se ha sentido nervioso, ansioso o muy alterado'},
        {id:'2',content: 'No ha podido dejar de preocuparse'},
        {id:'3',content: 'Se ha preocupado excesivamente por diferentes cosas'},
        {id:'4',content: 'Ha tenido difi cultad para relajarse'},
        {id:'5',content: 'Se ha sentido tan intranquilo que no podía estarse quieto'},
        {id:'6',content: 'Se ha irritado o enfadado con facilidad'},
        {id:'7',content: 'Ha sentido miedo, como si fuera a suceder algo terrible'},
      ]
    }
  },
  
  components: { SearchPatient, Scrollable, ExamLabel },
  
  methods: {
    async saveData () {
      await saveExams({
        patient_id: this.dataPersonalPatient.patient_id,
				professional_id: this.$attrs.dataUser.id,
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
      //this.result += data.value
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
      var nro = this.result
      var interp = ''
      if(nro<5){
        interp = 'Ansiedad nula'
      }
      else if(5<=nro && nro<= 9){
        interp = 'Ansiedad leve'
      }else if(9<nro && nro<= 14){
        interp = 'Ansiedad moderada'
      }else if(15<nro && nro<=21){
        interp = 'Ansiedad grave'
      }else{
        interp = 'Indeterminado'
      }


      if (!this.dataPersonalPatient.age) {
        document.getElementById('age').focus();
        return;
      }

      this.values.suma = this.result+' - '+interp

      this.values.edad = this.dataPersonalPatient.age

      this.saveData()
    }    
  },
}
</script>