<template>
  <div class="modal-body d-flex flex-gap" :class="{'cursor-wait': !valid}">
    <div class="d-flex flex-column w-25 flex-gap px-2 overflow-auto" style="max-height: 450px;">
      <button 
      class="btn btn-exam btn-warning w-100" 
      v-for="(exam, index) in allExams"
      :key="index"
      @click="btnActive(), examActive(exam.value, exam.name)"
      >
      {{ exam.name }}
      </button>
    </div>

    <div class="w-75 overflow-auto" style="max-height: 450px;">
      <table class="table table-striped" v-if="examenes">
        <thead>
          <th>#</th>
          <th>Fecha</th>
          <th>Respuesta</th>
        </thead>

        <tbody v-for="(examen, index) in examenes" :key="index">
          <th style="vertical-align:middle;"> {{ index + 1 }} </th>
          <td style="vertical-align:middle;"> {{ new Date(examen.created_at).toLocaleString() || '...' }} </td>
          <th>
            <button class="btn btn-warning" @click="keepExamFunction(examen)">Ver respuesta</button>
          </th>
        </tbody>
      </table>

      <div class="w-100 h-100 d-flex justify-content-center align-items-center" v-else>
        <h3 class="text-center">Selecciona un exámen</h3>
      </div>
    </div>
  </div>
</template>

<script>
import { formatDate } from "../../../../helpers/Time"

  export default {
    name: 'ExamTable',

    data () {
      return {
        // --- Resultados de examenes
        allExams: {
          scr: {
            name: 'SCL90R',
            value: []
          },

          burns: {
            name: 'ANSIEDAD DE BURNS',
            value: []
          },

          millon: {
            name: 'MILLON',
            value: []
          },

          zungdep: {
            name: 'DEPRESIÓN DE ZUNG',
            value: []
          },

          zunganxie: {
            name: 'ANSIEDAD DE ZUNG',
            value: []
          },

          phq: {
            name: 'PHQ-9',
            value: []
          },

          gad: {
            name: 'GAD-7',
            value: []
          },

          bdi: {
            name: 'BDI-2',
            value: []
          },

          mcmi: {
            name: 'MCMI-II',
            value: []
          },

          baron: {
            name: 'BARON',
            value: []
          },

          eysencka: {
            name: 'EYSENCK-A',
            value: []
          },

          eysenckb: {
            name: 'EYSENCK-B',
            value: []
          },
        },
        // ---

        examenes: null,
        valid: null
      }
    },

    props: {
      dataPatient: Object,
    },

    methods: {
      getExamPromise () {
        Promise.all([
          this.axios(`/api/zungDep/${this.dataPatient.id}`),
          this.axios(`/api/zungAns/${this.dataPatient.id}`),
          this.axios(`/api/millon/${this.dataPatient.id}`),
          this.axios(`/api/burns/${this.dataPatient.id}`),
          this.axios(`/api/src/${this.dataPatient.id}`),
          this.axios(`/api/exam/${this.dataPatient.id}`),
        ])
        .then(res => {
          const [
            zungDep, 
            zungAns, 
            millon, 
            burn, 
            src, 
            exam
            ] = res

          this.allExams.scr.value = src.data
          this.allExams.burns.value = burn.data
          this.allExams.millon.value = millon.data
          this.allExams.zunganxie.value = zungAns.data
          this.allExams.zungdep.value = zungDep.data


          exam.data.forEach(el => {
            const result = JSON.parse(el.exam)
            if (!result.name) return alert('Hay un examen o exámenes que no tienen nombre')

            this.allExams[result.name].value.push(result.result)
          })

          this.valid = true
        })
        .catch(err => {
          console.error(err)
        })
      },

      formatFecha(date) {
        return formatDate(date);
      },

      saveArrayData (value) {
        this.resultsExam.push(value)
      },

      examActive (value, type) {
        if (this.valid && value) {
          this.examenes = value;

          if (type) {
            this.dataPatient.tipoExam = document.querySelector('.btn-exam.btn-active').textContent
          }
        }
      },

      btnActive () {
        if (this.valid && event.target.matches('.btn-exam')) {
          if (document.querySelector('.btn-exam.btn-active')) document.querySelector('.btn-exam.btn-active').classList.remove('btn-active');
          event.target.classList.add("btn-active");
        }
      },

      keepExamFunction (data) {
        this.$emit('keepComponentExam', { 
          component: 'ExamResult',
          data: data,
        })
      },   
      
      formateDate() {

      }   
    },

    created () {
      this.getExamPromise()
    }
  }
</script>

<style scoped>
.flex-gap {
  gap: 25px;
}

.cursor-wait {
  cursor: wait !important;
}

.btn-exam:not(.btn-active) {
  opacity: .55;
}

.btn-exam:not(.btn-active):hover {
  opacity: .85;
}
</style>