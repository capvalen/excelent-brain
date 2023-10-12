<template>
	<div class="modal fade" id="modalSeguimiento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Programar seguimiento</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p class="mb-0">Seguimiento a <strong>{{ elegido.name }}</strong></p>
					<p class="mb-0">Ingrese un motivo:</p>
					<input class="form-control" v-model="seguimiento.motivo" type="text" autocomplete="off">
					<p class="mt-2 mb-0">Seleccione un profesional asignado al caso</p>
					<select class="text-capitalize form-select" v-model="seguimiento.idProfesional" id="">
						<option value="1">Ninguno</option>
						<option class="text-capitalize" v-for="profesional in profesionales" :value="profesional.id">{{ profesional.name }}</option>
					</select>
				</div>
				<div class="modal-footer border-0">
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="guardar()" ><i class="far fa-paper-plane"></i> Enviar a seguimiento</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default{
	name: 'modalSeguimiento',
	props:[ 'profesionales', 'idProfesional', 'elegido' ],
	data(){ return{
		seguimiento:{motivo:'', idProfesional: this.idProfesional}
	}},
	watch:{
		idProfesional(){
			this.seguimiento.idProfesional = this.idProfesional
		}
	},
	methods:{
		guardar(){
				if(this.seguimiento.motivo==''){alertify.notify('Debe ingresar un motivo para registrar', 'danger', 10);}
				else{
					let datos = new FormData();
					datos.append('nombre', this.elegido.name)
					datos.append('celular', this.elegido.phone)
					datos.append('motivo', this.seguimiento.motivo)
					datos.append('correo', '')
					datos.append('referencia', 7)
					datos.append('idProfesional', this.seguimiento.idProfesional)
					datos.append('idSeguimiento', this.elegido.seguimiento ?? 1)
					datos.append('origen', 2)
					fetch('/api/nuevoInteresado',{
						method:'POST', body:datos
					})
					.then(response=> response.json())
					.then(texto=>{
						
						if( texto.mensaje )
							this.$swal('Se registró exitosamente');
						else
							this.$swal({icon:'error',title: 'Hubo un error interno'});
					})
				}
			},
	}
}</script>