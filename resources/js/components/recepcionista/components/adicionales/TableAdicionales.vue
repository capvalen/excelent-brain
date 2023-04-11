<template>
    <div class="card px-1">
        <div class="d-sm-flex align-items-center justify-content-around mt-4 px-3" style="gap: 10px;">
          <div class="d-none d-sm-inline-block form-inline w-75">
            <div class="input-group">
              <div class="input-group-prepend">
                <button class="btn btn-success">
                    <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
              <input
              type="text"
              class="form-control bg-white shadow-sm border-0 small"
              placeholder="Nombre de Adicional"
              aria-label="Search"
              aria-describedby="basic-addon2"
              id="searchInputAppointment"
              v-on:keyup="search"
              v-model="buscar"
              >
            </div>
          </div>

            <button class="btn btn-success w-25 mx-3 my-3" data-toggle="modal" data-target="#modalAdicionales">+ Adicional  
            </button>

      </div>
        <table class="table table-striped w-100 mt-4">
        <thead class="bg-success text-white">
            <tr>
                <td>Paciente</td>
                <td>Número</td>
                <td>Profesional</td>
                <td>Horario</td>
            </tr>
        </thead>
        <tbody>
            <tr v-for="aditional in aditionalsFiltered">
                <td>{{aditional.name}}</td>
                <td>{{aditional.number}}</td>
                <td>{{aditional.professional.name}}</td>
                <td>{{aditional.schedule}}</td>
            </tr>
        </tbody>
    </table>
    </div>
</template>
<script>
export default {
    name: 'table-adicionales',
    data(){
        return{
            buscar:''
        }
    },
    props:{
        aditionals: Array
    },
    methods:{
        search(e){
            console.log(e.target.value)
        }
    },
    computed:{
        aditionalsFiltered(){
            return this.aditionals.filter((aditional)=> aditional.name.includes(this.buscar))
        }
    }
}
</script>