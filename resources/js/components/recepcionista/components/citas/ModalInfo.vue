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
                <td class="text-capitalize"> {{ dataCit.patient.name }} {{ dataCit.patient.nombres }}</td>
              </tr>

              <tr>
                <th scope="row">Tipo</th>
                <td v-if="dataCit.formato_nuevo==0">
									<span v-if=" dataCit.type === 1">Terapia Inicial niño/adolescente</span>
									<span v-else-if=" dataCit.type === 2">Terapia Inicial adulto</span>
									<span v-else-if=" dataCit.type === 3">Terapia Inicial pareja</span>
									<span v-else-if=" dataCit.type === 4">Terapia Inicial familiar</span>
									<span v-else-if=" dataCit.type === 5">Terapia continua niño/adolescente</span>
									<span v-else-if=" dataCit.type === 6">Terapia continua adulto</span>
									<span v-else-if=" dataCit.type === 7">Terapia continua pareja</span>
									<span v-else-if=" dataCit.type === 8">Terapia continua familiar</span>
									<span v-else-if=" dataCit.type === 9">Orientación Vocacional</span>
									<span v-else-if=" dataCit.type === 10">Sucamec inicial</span>
									<span v-else-if=" dataCit.type === 11">Sucamec renovación</span>
									<span v-else-if=" dataCit.type === 12">Kurame</span>
								</td>
								<td v-else>
									<span>{{ queServicio(dataCit.type) }}</span>
								</td>
              </tr>

              <tr>
                <th scope="row">Condición del Paciente</th>
                <td v-if=" dataCit.patient_condition === 1">Paciente inicial</td>
                <td v-else-if=" dataCit.patient_condition === 2">Paciente continuo</td>  
              </tr>
              
              <tr>
                <th scope="row">Clasificación</th>
                <td>{{clasificaciones[dataCit.clasification]}}</td>
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

              <tr v-if="dataCit.payment">
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
        data: null,
				clasificaciones:{1:'Psiquiátrico', 2:'Psicológico', 3:'Certificado', 4:'Kurame', 5:'Clínica de día', 6:'Por membresía'}
      }
    },
    
    props: {
      dataCit: Object, precios: Array
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
			queServicio(idTipo){ //console.log('queTipo', idTipo);
				if( idTipo ) return this.precios.find(x=> x.id == idTipo).descripcion
			},
    },

    created () {
      this.getData
    }
  }
</script>

