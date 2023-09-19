<template>
	<!-- Modal -->
	<div class="modal fade" id="modalVerHobbies" tabindex="-1"  aria-hidden="true">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Hobbies del paciente</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="card mb-2">
							<div class="card-body">
								
								<div class="row">
									<div class="col-sm-6">
										<template>
											<VSelect addClass="text-capitalize" :searchable="true" searchPlaceholder="Buscar hobbies" defaultTitle="Hobbies" searchNotFound="No hay resultados" v-model="selected" :options="actividades" />
										</template>		
										<!-- <select class="form-select text-capitalize" id="sltHobbie" >
											<option v-for="(hobbie, index) in hobbies" class="text-capitalize" :value="index">{{hobbie}}</option>
										</select> -->
									</div>
									<div class="col-sm-6">
										<button class="btn btn-outline-secondary" @click="addHobbie()"><i class="fas fa-paperclip"></i> Agregar</button>
									</div>
								</div>
							</div>
						</div>
					<table class="table">
						<thead>
							<tr>
								<th>N°</th>
								<th>Hobbie</th>
								<th>@</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(hobbie, index) in misHobbies">
								<td>{{ index+1 }}</td>
								<td class="text-capitalize">{{ hobbies[hobbie] }}</td>
								<td><button class="btn btn-outlihe-danger" @click="borrarHobbie(index)"><i class="fa-solid fa-eraser"></i></button></td>
							</tr>
							<tr v-if="misHobbies.length === 0" >
								<td colspan="4">No existen hobbies</td>
							</tr>
						</tbody> 
					</table>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import VSelect from "vue-select-picker-bootstrap";

export default{
	name: 'ModalVerHobbies',
	props:['hobbies','misHobbies', 'id'],
	components: { VSelect },
	data(){return { actividades:[],
		countries: [
        { value: 1, text: "Pakistan" },
        { value: 2, text: "China" },
        { value: 3, text: "Bangladesh" },
        { value: 4, text: "USA" },
        { value: 5, text: "Australia" },
      ],
		selected: {value: null},
	}},
	mounted(){
		this.recargarLista()
	},
	methods:{
		async addHobbie(){
			this.misHobbies.push( this.selected.value );	 //document.getElementById('sltHobbie').value
			this.guardarJSON();
		},
		 borrarHobbie(index){
			this.misHobbies.splice(index,1);
			this.guardarJSON();
		},
		async guardarJSON(){
			await this.axios.post('/api/addHobbie/'+this.id, {misHobbies: this.misHobbies})
			.then(res=> console.log(res.data))
		},
		capitalizarPrimeraLetra(palabra) {
			if (palabra.length === 0) return "";
			// Convierte la primera letra a mayúscula y el resto a minúscula
			palabra = palabra.toLowerCase();
			palabra = palabra.charAt(0).toUpperCase() + palabra.slice(1);
			return palabra;
		},
		recargarLista(){
			this.hobbies.forEach((hob, index)=>{
				this.actividades.push({ value: index, text: this.capitalizarPrimeraLetra(hob) })
			})
		}
	},
	watch:{
		//hobbies(){ this.recargarLista() }
	}
}</script>

<style>
	.v-select-toggle, .v-dropdown-item{font-size: 15px!important;}
	
</style>