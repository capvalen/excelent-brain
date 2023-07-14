<template>
	<!-- Modal -->
<div class="modal fade" id="modalBuscarPaciente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Buscar pacientes</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Puedes realizar uns búsqueda por DNI, Apellidos o Nombres</p>
				<input type="text" class="form-control" v-model="texto" @keyup.enter="buscarPaciente()">
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-primary" @click="buscarPaciente()" data-bs-dismiss="modal"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
      </div>
    </div>
  </div>
</div>

</template>

<script>
export default{
	name: 'modalBuscarPaciente',
	data(){ return {
		texto:''
	}},
	method:{
		buscarPaciente(){
			this.$emit('buscarPacientes', this.texto)
		}
	}
}
</script>