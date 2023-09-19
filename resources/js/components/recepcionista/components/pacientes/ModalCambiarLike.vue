<template>
	<div class="modal fade" id="editarClub" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h5 class="modal-title " id="exampleModalLabel">¿Pertenece al club?</h5>
					<button type="button" id="closeModal" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p>Seleccione si pertenece al club:</p>
					<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
						<input id="radio1" type="radio" class="btn-check" name="rdbLike" autocomplete="off" value="1" v-model="seleccionado" @click="asignarCambio(1)" data-bs-toggle="popover" data-bs-placement="top" data-bs-trigger="hover focus" data-bs-content="Pertenece al club">
						<label class="btn btn-outline-primary" for="radio1"><i class="fa-solid fa-thumbs-up"></i></label>
						
						<input type="radio" class="btn-check" name="rdbLike" id="radio2" autocomplete="off" value="0" v-model="seleccionado" @click="asignarCambio(0)" data-bs-toggle="popover" data-bs-placement="top" data-bs-trigger="hover focus" data-bs-content="Sin datos sobre el club">
						<label class="btn btn-outline-secondary" for="radio2"><i class="fa-regular fa-hand-back-fist"></i></label>

						<input type="radio" class="btn-check" name="rdbLike" id="radio3" autocomplete="off" value="2" v-model="seleccionado" @click="asignarCambio(2)" data-bs-toggle="popover" data-bs-placement="top" data-bs-trigger="hover focus" data-bs-content="No pertenece al club">
						<label class="btn btn-outline-danger" for="btnadio3"><i class="fa-solid fa-thumbs-down"></i></label>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</template>
<script>
export default{
	name: 'ModalCambiarLike',
	props:{like:null, id: null},
	emits:['updateLike'],
	data(){
		return {
			seleccionado:0
		}
	},
	mounted(){ },
	methods:{
		asignarCambio(valor){
			this.seleccionado=valor;
			this.axios(`/api/cambiarLike/${this.id}/${this.seleccionado}`)
			.then(response=>{
				//this.like = this.seleccionado;
				this.$emit("updateLike", { seleccionado: this.seleccionado, id: this.id});
				document.querySelector("#editarClub #closeModal").click()

			})
			
		}
	},
	watch:{
		like(){
			this.seleccionado = this.like;
		}
	}
}
</script>