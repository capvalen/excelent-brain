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
          <td v-if="key != 'created_at' && key != 'updated_at' && key != 'id' && key != 'patient_id'">{{ key }}</td>
          <td v-if="key != 'created_at' && key != 'updated_at' && key != 'id' && key != 'patient_id'">{{ data }}</td>
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
      dataExam: Object
    },

    methods: {
      emitExamResult () {
        this.$emit('keepComponentExam', 'ExamTable')
      }
    }
  }
</script>