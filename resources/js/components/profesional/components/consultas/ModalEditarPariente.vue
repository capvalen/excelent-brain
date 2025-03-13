<template>
	<div class="modal fade" id="modalEditarPariente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h5 class="modal-title " id="exampleModalLabel">Contacto de parientes</h5>
					<button type="button" id="closeModal" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col col-md-6">
							<label for=""><strong>Pariente N° 1</strong></label><br>
							<label for="">Nombre</label>
							<input type="text" class="form-control text-capitalize" v-model="pariente.nombre">
							<label for="">Celular</label>
							<input type="text" class="form-control" v-model="pariente.celular">
							<label for="">Parentesco</label>
							<input type="text" class="form-control text-capitalize" v-model="pariente.parentesco">
						</div>
						<div class="col col-md-6 border-left">
							<label for=""><strong>Pariente N° 2</strong></label><br>
							<label for="">Nombre</label>
							<input type="text" class="form-control text-capitalize" v-model="pariente.nombre2">
							<label for="">Celular</label>
							<input type="text" class="form-control" v-model="pariente.celular2">
							<label for="">Parentesco</label>
							<input type="text" class="form-control text-capitalize" v-model="pariente.parentesco2">
						</div>
					</div>
				</div>
				<div class="modal-footer border-0">
					<button class="btn btn-outline-primary" @click="asignarCambio()"><i class="fa-solid fa-floppy-disk"></i> Actualizar contactos</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default{
	name: 'ModalEditarPariente',
	props: ['relative', 'id'],
	emits:['updatePariente'],
	data(){
		return {
			pariente:{nombre:'', celular:'', parentesco:'', nombre2:'', celular2:'', parentesco2:''}, seleccionado:0
		}
	},
	mounted(){
		this.pariente.nombre = this.relative[0].name
		this.pariente.celular = this.relative[0].phone
		this.pariente.parentesco = this.relative[0].kinship
		this.pariente.nombre2 = this.relative[0]?.name
		this.pariente.celular2 = this.relative[0]?.phone
		this.pariente.parentesco2 = this.relative[0]?.kinship
	},
	methods:{
		async asignarCambio(){
			await this.axios.post(`/api/editarPariente/${this.id}`, this.pariente)
			.then(response=>{
				document.querySelector("#modalEditarPariente #closeModal").click()
				if(response.data.msg){
					this.$emit("updatePariente", this.pariente );
				}
			})
		}
	}
}
</script>