<template>
	
	<div class="modal fade" id="nuevoInteresado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h5 class="modal-title " id="exampleModalLabel">Nuevo seguimiento</h5>
					<button type="button" id="closeModal" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<label for="">Nombre <span class="text-danger">*</span></label>
					<input type="text" class="form-control" v-model="interesado.nombre" autocomplete="off">
					<label for="">Celular <span class="text-danger">*</span></label>
					<input type="text" class="form-control" v-model="interesado.celular" autocomplete="off">
					<label for="">Correo electrónico</label>
					<input type="text" class="form-control" v-model="interesado.correo" autocomplete="off">
					<label for="">Motivo <span class="text-danger">*</span></label>
					<input type="text" class="form-control" v-model="interesado.motivo" autocomplete="off">
					<label for="">Referencia</label>
					<select v-model="interesado.referencia" class="form-select" id="sltTipo">
						<option value="1">Ninguno</option>
						<option value="2">Recomendación</option>
						<option value="3">Publicidad de internet</option>
						<option value="4">Publicidad de Escrita</option>
						<option value="5">Publicidad de TV/Radio</option>
						<option value="6">Referido</option>
					</select>
				</div>
				<div class="modal-footer border-0">
					<button type="button" class="btn btn-primary" @click="guardar()">Guardar</button>
				</div>
			</div>
		</div>
	</div>

</template>
<script>
	import moment from 'moment'
	import alertify from 'alertifyjs'

	export default{
		name: 'ModalNuevoInteresado',
		props:{ usuario: null},
		data(){
			return{
				interesado:{
					actividad:'', responsable:'', tipo:1, correo:''
				}
			}
		},
		methods:{
			guardar(){
				if(this.interesado.nombre==''){alertify.notify('Debe existir un nombre', 'danger', 10);}
				else if(this.interesado.celular==''){alertify.notify('Debe escribir un celular', 'danger', 10);}
				else if(this.interesado.motivo==''){alertify.notify('Debe haber algún motivo', 'danger', 10);}
				else{
					let datos = new FormData();
					datos.append('nombre', this.interesado.nombre)
					datos.append('celular', this.interesado.celular)
					datos.append('motivo', this.interesado.motivo)
					datos.append('correo', this.interesado.correo)
					datos.append('referencia', this.interesado.referencia)
					fetch('/api/nuevoInteresado',{
						method:'POST', body:datos
					})
					.then(response=> response.json())
					.then(texto=>{
						this.cerrarModal()
						if( texto.mensaje )
							this.$swal('Se creó exitosamente');
						else
							this.$swal({icon:'error',title: 'Hubo un error interno'});
					})
				}
			},
			cerrarModal(){
				this.interesado = { actividad:'', responsable:'', tipo:1 }
				document.querySelector('#nuevoInteresado #closeModal').click();
			}
		}
	}
</script>