<template>
	<div class="modal fade" id="editarClub" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h5 class="modal-title " id="exampleModalLabel">Estado del club</h5>
					<button type="button" id="closeModal" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
						<input type="radio" class="btn-check" name="rdbLike" id="btnradio1" autocomplete="off" value="1" v-model="seleccionado" @click="asignarCambio(1)">
						<label class="btn btn-outline-primary" for="btnradio1"><i class="fa-solid fa-thumbs-up"></i></label>
						
						<input type="radio" class="btn-check" name="rdbLike" id="btnradio2" autocomplete="off" value="0" v-model="seleccionado" @click="asignarCambio(0)">
						<label class="btn btn-outline-primary" for="btnradio2"><i class="fa-regular fa-hand-back-fist"></i></label>

						<input type="radio" class="btn-check" name="rdbLike" id="btnradio3" autocomplete="off" value="2" v-model="seleccionado" @click="asignarCambio(2)">
						<label class="btn btn-outline-primary" for="btnradio3"><i class="fa-solid fa-thumbs-down"></i></label>
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