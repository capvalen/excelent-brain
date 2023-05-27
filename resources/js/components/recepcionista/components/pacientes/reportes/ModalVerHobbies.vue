<template>
	<!-- Modal -->
	<div class="modal fade" id="modalVerHobbies" tabindex="-1"  aria-hidden="true">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Hobbies del paciente</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal"  aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="card mb-2">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<select class="form-select text-capitalize" id="sltHobbie" >
											<option v-for="(hobbie, index) in hobbies" class="text-capitalize" :value="index">{{hobbie}}</option>
										</select>
									</div>
									<div class="col-sm-6">
										<button class="btn btn-outline-secondary" @click="addHobbie()"><i class="fas fa-share"></i> Enviar</button>
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

export default{
	name: 'ModalVerHobbies',
	props:['hobbies','misHobbies', 'id'],
	data(){return {
		
	}},
	methods:{
		async addHobbie(){
			this.misHobbies.push( document.getElementById('sltHobbie').value );	
			this.guardarJSON();
		},
		 borrarHobbie(index){
			this.misHobbies.splice(index,1);
			this.guardarJSON();
		},
		async guardarJSON(){
			await this.axios.post('/api/addHobbie/'+this.id, {misHobbies: this.misHobbies})
			.then(res=> console.log(res.data))
		}
	},
	watch:{
	}
}</script>
