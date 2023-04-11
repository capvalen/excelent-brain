<template>
  <div class="modal fade" id="evolutionModal" tabindex="-1" aria-labelledby="modalEvolution" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header py-1 bg-secondary text-white">
            <h5 class="modal-title" id="infoModalLabel">Ver evolución</h5>
            <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        
        <div class="modal-body">
          <p><b>Fecha: </b>{{dataEvolution.date}}</p>          
          <p v-if="dataEvolution.content"><b>Contenido: </b>{{dataEvolution.content}}</p>
          <p v-else="dataEvolution.content === null"><b>Contenido: </b>-</p>
          <div v-if="!dataEvolution.content" class="custom-control custom-switch" :data-id="`updateEvolution${dataEvolution.id}`">
                    <input 
                    type="checkbox" 
                    class="custom-control-input" 
                    @click="updateAuth(dataEvolution.id)" 
                    :id="`authEvolution${dataEvolution.id}`"
                    checked
                    v-if="dataEvolution.auth"
                    >
                    <input 
                    type="checkbox" 
                    class="custom-control-input" 
                    @click="updateAuth(dataEvolution.id)" 
                    :id="`authEvolution${dataEvolution.id}`"
                    v-else
                    >
                    <label class="custom-control-label" :for="`authEvolution${dataEvolution.id}`">Autorizar</label>
            </div>
         
         <!--  <table class="table table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Horario</th>
                <th>Autorizar</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(data, index) in dataEvolution" :key="index">
                <th>{{ index + 1 }}</th>
                <td>{{ data }}</td>
                <td>{{ data.schedule ? horaHumanaMin(data.schedule) : '...' }}</td>
                <td>
                  <div class="custom-control custom-switch" :data-id="`updateEvolution${dataEvolution.id}`">
                    <input 
                    type="checkbox" 
                    class="custom-control-input" 
                    @click="updateAuth(dataEvolution.id)" 
                    :id="`authEvolution${dataEvolution.id}`"
                    checked
                    v-if="dataEvolution.auth"
                    >
                    <input 
                    type="checkbox" 
                    class="custom-control-input" 
                    @click="updateAuth(dataEvolution.id)" 
                    :id="`authEvolution${dataEvolution.id}`"
                    v-else
                    >
                    <label class="custom-control-label" :for="`authEvolution${dataEvolution.id}`">Autorizar</label>
            </div>
                </td>
              </tr>
            </tbody>
          </table> -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'ModalEvolution',

    data () {
      return {
        evolutions: null
      }
    },

    methods: {
      async updateAuth(id){
        let $inputChecbox = document.getElementById(`authEvolution${id}`);
        if ($inputChecbox.checked) {
          await this.axios.put('/api/authEvol/', { medical_evolution: id, auth: 1 })
          .then((res) => {
            this.$swal('Se autorizó exitosamente');
          }).catch((err) => {
            console.error(err)
          });
        } else {
          await this.axios.put('/api/authEvol/', { medical_evolution: id, auth: 0 })
          .then((res) => {
            this.$swal('Se desautorizo exitosamente');
          }).catch((err) => {
            console.error(err)
          });
        }
      },

      horaHumanaMin (hora) {
        let minutos = parseInt(hora.substring(3,5))
        hora = parseInt(hora.substring(0,2))
        if (hora > 12) {
            return `${hora - 12}:${minutos.toString().length === 1 ? '0' + minutos : minutos} PM`
        } else {
          if (hora === 12 && minutos >= 0) {
              return `${hora}:${minutos.toString().length === 1 ? '0' + minutos : minutos} PM`
          }

          if (hora === 0) {
              return `12:${minutos.toString().length === 1 ? '0' + minutos : minutos} AM`
          }
          return `${hora}:${minutos.toString().length === 1 ? '0' + minutos : minutos} AM`
        }
      },
    },

    props: {
      dataEvolution: Object
    }
  }
</script>