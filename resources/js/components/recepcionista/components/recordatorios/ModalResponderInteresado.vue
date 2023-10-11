<template>
	
	<div class="modal fade" id="modalResponderInteresado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header border-0 pb-0">
					<h5 class="modal-title " id="exampleModalLabel">Respuesta del interesado</h5>
					<button type="button" id="closeModal" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<label for="">Opciones</label>
					<select class="form-select" id="sltTipo" v-model="respuesta.tipo">
						<option value="1">Se atendió</option>
						<option value="2">No se atendió</option>
					</select>
					<div>
						<label for="">Respuesta del cliente:</label>
						<input type="text" class="form-control" id="txtRespuesta" v-model="respuesta.respuesta" autocomplete="off">
					</div>
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
		name: 'ModalResponderInteresado',
		props:{ usuario: null, queInteresado:null},
		data(){
			return{
				respuesta:{ tipo:2, respuesta:'' }
			}
		},
		methods:{
			async guardar(){
				let datos = new FormData();
				datos.append('id', this.queInteresado.id)
				datos.append('respuesta', JSON.stringify(this.respuesta))
				await axios.post('/api/respuestaInteresado', datos)
				.then(texto=>{
					this.$parent.cargarDatos('interesados');
					this.cerrarModal()
					if(texto.data.mensaje){
						this.$swal(texto.data.mensaje);
					}else{
						this.$swal({icon:'error',title: 'Hubo un error interno'});
					}
				})
			},
			cerrarModal(){
				document.querySelector("#modalResponderInteresado #closeModal").click()
			}
		},
		watch:{
			
		}
	}
</script>