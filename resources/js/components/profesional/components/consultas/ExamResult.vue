<template>
  <div class="w-75 m-auto">
    <div class="my-3">
      <div>
        <span class="font-weight-bolder">Tipo de exámen: </span>
        <span>{{ dataPatient.tipoExam }}</span>
      </div>

      <div>
        <span class="font-weight-bolder">Paciente: </span>
        <span>{{ dataPatient.name }}</span>
      </div>
      <div>
        <span class="font-weight-bolder">Encuestador: </span>
        <span>{{ queProfesional(dataExam.professional_id) }}</span>
      </div>
    </div>
        
    <table class="table table-striped my-3">
      <thead>
        <tr>
          <th colspan="3" class="text-center">Resultados</th>
        </tr>
      </thead>

      <tbody>
        <tr
        v-for="(data, key, index) in dataExam" 
        :key="index"
        >
          <td class="text-capitalize" v-if="key != 'created_at' && key != 'updated_at' && key != 'id' && key != 'patient_id'">{{ key }}</td>
          <td v-if="key != 'created_at' && key != 'updated_at' && key != 'id' && key != 'patient_id' && key!='resultados'">{{ data }}</td>
          <td v-if="key=='resultados'">
            <a target="_blank" :href="queUrl(dataPatient.tipoExam, dataExam.id)">Ver plantilla de resultados </a>
          </td>
        </tr>
      </tbody>
    </table>

    <button class="btn btn-warning my-3" @click="emitExamResult()">Volver</button>
  </div>
</template>

<script>
  export default {
    name: 'ExamResult',
    props: {
      dataPatient: Object,
      dataExam: Object, profesionales: Array
    },

    methods: {
      queUrl(tipoExam, id){
        const base = tipoExam.trim().replace(/\n/g, '')
        let irA = ''
        switch (base) {
          case 'SCL90R': irA = 'scr'; break;
          case 'ANSIEDAD DE BURNS': irA = 'burns'; break;
          default: break;
        }
        return `/profesional/ver/${irA}/${id}`;
      },
      emitExamResult () {
        this.$emit('keepComponentExam', 'ExamTable')
      },
			queProfesional(id){
				console.log('el id ',id);
				if( id=='' || id==null || id==undefined){
					return ''
				}else{
					return this.profesionales.filter(x=> x.id==id)[0].name
				}
			}
    }
  }
</script>