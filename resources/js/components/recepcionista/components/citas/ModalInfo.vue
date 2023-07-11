<template>
  <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Información de la cita</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          <table class="table table-striped">
            <tbody>
              <tr>
                <th scope="row">Nombre del paciente</th>
                <td class="text-capitalize"> {{ lowerCase(dataCit.patient.name) }}</td>
              </tr>

              <tr>
                <th scope="row">Tipo</th>
                <td v-if=" dataCit.type === 1">Terapia Inicial niño/adolescente</td>
                <td v-else-if=" dataCit.type === 2">Terapia Inicial adulto</td>
                <td v-else-if=" dataCit.type === 3">Terapia Inicial pareja</td>
                <td v-else-if=" dataCit.type === 4">Terapia Inicial familiar</td>
                <td v-else-if=" dataCit.type === 5">Terapia continua niño/adolescente</td>
                <td v-else-if=" dataCit.type === 6">Terapia continua adulto</td>
                <td v-else-if=" dataCit.type === 7">Terapia continua pareja</td>
                <td v-else-if=" dataCit.type === 8">Terapia continua familiar</td>
                <td v-else-if=" dataCit.type === 9">Orientación Vocacional</td>
                <td v-else-if=" dataCit.type === 10">Sucamec inicial</td>
                <td v-else-if=" dataCit.type === 11">Sucamec renovación</td>
                <td v-else-if=" dataCit.type === 12">Kurame</td>
              </tr>

              <tr>
                <th scope="row">Condición del Paciente</th>
                <td v-if=" dataCit.patient_condition === 1">Paciente inicial</td>
                <td v-else-if=" dataCit.patient_condition === 2">Paciente continuo</td>  
              </tr>
              
              <tr>
                <th scope="row">Clasificación</th>
                <td v-if=" dataCit.clasification === 2">Psicológico</td>
                <td v-if=" dataCit.clasification === 1">Psiquiátrico</td>            
                <td v-if=" dataCit.clasification === 3">Certificado</td>            
                <td v-if=" dataCit.clasification === 4">Kurame</td>            
                <td v-if=" dataCit.clasification === 5">Clínica de día</td>            
              </tr>

              <tr>
                <th scope="row">Modo</th>
                <td v-if=" dataCit.mode === 1">Presencial</td>
                <td v-else-if=" dataCit.mode === 2">Virtual</td>  
              </tr>

              <tr>
                <th scope="row">Recomendación</th>
                <td>{{ dataCit.recomendation }}</td>
              </tr>

              <tr>
                <th scope="row">Monto</th>
                <td>S./ {{ dataCit.payment.price }}</td>
              </tr>
              
              <tr>
                <th scope="row">Link</th>
                <td>{{ dataCit.link }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "modal_info",
    
    data() {
      return{
        data: null
      }
    },
    
    props: {
      dataCit: Object
    },

    computed : {
      getData () {
        return this.data = this.dataCit
      }
    },

    methods: {
      lowerCase (text) {
        return text.toLowerCase();
      },
    },

    created () {
      this.getData
    }
  }
</script>

