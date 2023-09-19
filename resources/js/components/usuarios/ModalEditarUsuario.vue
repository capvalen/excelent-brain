<template>
	<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Editar usuario logístico</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label for="">Correo</label>
				<input class="form-control" type="text" v-model="usuario.email">
        <label for="">Contraseña</label>
				<input class="form-control" type="text" v-model="clave" autocomplete="off">
        <label for="">Nombre</label>
				<input class="form-control" type="text" v-model="usuario.nombre">
        <label for="">Nivel</label>
				<select class="form-select" v-model="usuario.rol">
					<option value="administrador">Administrador</option>
					<option value="interno">Interno</option>
					<option value="recepcionista">Recepcionista</option>
				</select>
				<div v-if="usuario.rol!='interno'">
					<label for="">¿Tiene privilegios administrativos?</label>
					<select class="form-select" v-model="usuario.privilegios">
						<option value="0">No</option>
						<option value="1">Sí</option>
					</select>
				</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal" @click="guardar()">Actualizar campos</button>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import alertify from 'alertifyjs'
export default{
	name: 'ModalEditarUsuario',
	props:['usuario'],
	data(){ return { clave: '' }},
	methods:{
		guardar(){
			this.axios.post('/api/actualizarDatosUsuarioBasico', {usuario:this.usuario, clave: this.clave})
			.then(res=>{
				console.log(res.data)
				this.clave='';
				alertify.notify('<i class="fa-regular fa-calendar-check"></i> ' + res.data.mensaje, 'success', 5);
			})
		}
	}
}</script>