<template>
	<div class="offcanvas offcanvas-end" tabindex="-1" id="offAdjunto" aria-labelledby="offcanvasExampleLabel">
		<div class="offcanvas-header">
			<h5 class="offcanvas-title" id="offcanvasExampleLabel">Archivos adjuntos</h5>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
		</div>
		<div class="offcanvas-body">
			<img class="img-fluid" src="/img/celular_fondo.jpeg" alt="">
			<div v-if="habilitarEliminado">
				<div v-if="fotoNueva==''">
					<label class="mt-2" for="">Adjunte su comprobante de pago, aquí</label>
					<div class="input-group" >
						<input type="file" class="form-control" id="fileArchivo"  aria-label="Upload" accept="image/*,application/pdf">
						<button class="btn btn-outline-secondary" type="button" @click="subirArchivo">Subir archivo</button>
					</div>
				</div>
				<div v-else>
					<img class="img-fluid my-2" :src="'/storage/adjuntos/'+this.fotoNueva" alt="">
					<button class="btn btn-danger mt-2" @click="eliminarAdjunto()"><i class="fas fa-unlink"></i> Eliminar adjunto</button>
				</div>
			</div>
			<div v-else>
				<p class="my-2">Por políticas, no se puede adjuntar archivos fuera del día de operaciones.</p>
			</div>
		</div>
	</div>
</template>
<script>

export default{
	name: 'offAdjunto',
	props:['id', 'foto', 'habilitarEliminado'],
	data(){ return { fotoNueva: '' }},
	methods:{
		subirArchivo(){
			const file = document.querySelector('#fileArchivo');
			const formData = new FormData();
			formData.append('file', file.files[0]);
			formData.append('idPago', this.id);

			this.axios.post('/api/subirArchivoPago/', formData)
			.then(response => this.fotoNueva = response.data.archivo ?? '' )
			.catch(error => {
					console.log(error);
			})
		},
		eliminarAdjunto(){
			if(confirm('¿Desea eliminar el adjunto?'))
				this.axios.post('/api/eliminarAdjunto/'+this.id, {archivo: this.fotoNueva})
				.then(res => {
					if(res.data.mensaje) {
						this.fotoNueva='';
						alertify.notify('Comprobante borrado', 'success', 10);
					}else{
						alertify.notify('Hubo un error borrando', 'danger', 10);
					}
				})
		}
	},
	mounted(){
	},
	watch:{
		foto(){
			this.fotoNueva = this.foto;
		}
	}
}</script>