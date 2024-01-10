<template>
	<div class="modal fade" id="modalEditarPariente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h5 class="modal-title " id="exampleModalLabel">Pariente</h5>
					<button type="button" id="closeModal" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<label for="">Nombre</label>
					<input type="text" class="form-control" v-model="pariente.nombre">
					<label for="">Celular</label>
					<input type="text" class="form-control" v-model="pariente.celular">
					<label for="">Parentesco</label>
					<input type="text" class="form-control" v-model="pariente.parentesco">
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" @click="asignarCambio()"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default{
	name: 'ModalEditarPariente',
	props: ['relative'],
	emits:['updatePariente'],
	data(){
		return {
			pariente:{nombre:'', celular:'', parentesco:''}, seleccionado:0
		}
	},
	mounted(){
		this.pariente.nombre = this.relative.name
		this.pariente.celular = this.relative.phone
		this.pariente.parentesco = this.relative.kinship
	},
	methods:{
		async asignarCambio(){
			await this.axios.post(`/api/editarPariente/${this.relative.id}`, this.pariente)
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