<template>
	
	<div class="modal fade" id="editarAviso" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header border-0 pb-0">
					<h5 class="modal-title " id="exampleModalLabel">Editar aviso, recordatorio, llamada</h5>
					<button type="button" id="closeModal" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
				</div>
				<div class="modal-body">
					<label for="">Estado</label>
					<select class="form-select" id="sltTipo" v-model="aviso.tipo">
						<option value="1">Creado</option>
						<option value="2">Atendido</option>
						<option value="3">Solucionado</option>
						<option value="4">Pendiente</option>
						<option value="5">Cancelado</option>
					</select>
					<label for="">¿Observaciones?</label>
					<input type="text" class="form-control" id="fechaHora" v-model="aviso.observaciones" autocomplete="off">
				</div>
				<div class="modal-footer border-0">
					<button type="button" class="btn btn-primary" @click="guardar()">Actualizar</button>
				</div>
			</div>
		</div>
	</div>

</template>
<script>
	import alertify from 'alertifyjs'

	export default{
		name: 'ModalEditarAviso',
		props:{ usuario: null, queAviso:null},
		data(){
			return{
				aviso:{
					tipo:1, observaciones:''
				}
			}
		},
		methods:{
			async guardar(){
				if(this.aviso.tipo==1) alertify.notify('No se acepta el tipo creado nuevamente', 'danger', 10)
				else{
					let datos = new FormData();
					datos.append('id', this.queAviso.id)
					datos.append('tipo', this.aviso.tipo)
					datos.append('observaciones', this.aviso.observaciones)
					datos.append('actualizador', this.usuario)
					await axios.post('/api/actualizarAviso', datos)
					.then(texto=>{

						this.cerrarModal()
						if( texto.data.mensaje )
							this.$swal('Se actualizó exitosamente');
						else{
							this.$swal({icon:'error',title: 'Hubo un error interno'});
						}
					})
				}
				
			},
			cerrarModal(){
				document.querySelector("#editarAviso #closeModal").click()
			}
		},
		watch:{
			queAviso: function(){
				this.aviso.tipo = this.queAviso.tipo
				this.aviso.observaciones = this.queAviso.observaciones
			}
		}
	}
</script>