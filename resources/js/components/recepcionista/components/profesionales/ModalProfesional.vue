<template>
	<div class="modal fade bd-example-modal-lg" id="profModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-success">
					<h5 class="modal-title text-white" id="exampleModalLabel">{{type}} Profesional</h5>
					<button type="button" id="closeModal" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
				</div>

				<div class="modal-body">
					<form @submit.prevent>
						<div class="row">
							<div class="col-sm-8">
								<div class="form-group row">
									<div class="col-sm-12">
										<label for="name">Nombre Completo</label>
										<input type="text" class="form-control" name="name" id="name" v-model="prof.name" placeholder="Ingrese su nombre">
									</div>
								</div>
								<div v-if="type=='Agregar'" class="form-group row">
									<div class="col-sm-6">
										<label for="name">Email</label>
										<input type="email" class="form-control" name="name" id="email" v-model="prof.email" placeholder="Ingrese su correo electrónico">
									</div>
									<div class="col-sm-6">
										<label for="name">Contraseña</label>
										<input type="password" class="form-control" name="name" id="password" v-model="prof.password" placeholder="Ingrese su contraseña">
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-6">
										<label for="name">Celular</label>
										<input type="text" class="form-control" name="name" id="phone" v-model="prof.phone" placeholder="Ingrese su celular">
									</div>
									<div class="col-sm-6">
										<label for="name">Profesión</label>
									<div class="form-group">
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="Psicólogo" v-model="prof.profession">
										<label class="form-check-label" for="inlineRadio1">Psicólogo</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="Psiquiatra" v-model="prof.profession">
										<label class="form-check-label" for="inlineRadio2">Psiquiatra</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="Tecnólogo" v-model="prof.profession">
										<label class="form-check-label" for="inlineRadio3">Tecnólogo médico</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio4" value="Terapista" v-model="prof.profession">
										<label class="form-check-label" for="inlineRadio4">Terapista</label>
									</div>
								</div>
									</div>
								</div>
								<div class="form-group">
									<label for="name">Trayectoria</label>
									<textarea rows="8" type="text" class="form-control" name="cv_description" v-model="prof.cv_description" id="cv_description" required>
									</textarea>
								</div>
							</div>
							<div class="col-sm-4 d-flex flex-column align-items-center">
									<img v-if="type=='Agregar'" id="imgProf" src="https://img.freepik.com/vector-gratis/fondo-personaje-doctor_1270-84.jpg" alt="Profesional Image" class="rounded-circle" width="150px" height="150px">
									<img v-else id="imgProf" :src="'/storage/'+prof.photo" alt="Profesional Image" class="rounded-circle" width="150px" height="150px" style="object-fit:cover;">
									<input @change="showImg" type="file" class="form-control d-none" name="file" id="foto">
									<label class="btn btn-success mt-3" for="foto">Subir Imagen</label>

									<img v-if="type=='Agregar'|| prof.signing == null" id="imgFirma" src="https://www.wikihow.com/images/6/6b/Sign_a_Cool_Signature_Step_14_Version_2-ES.jpg" alt="Profesional Image" width="200px" height="150px">
									<img v-else id="imgFirma" :src="'/storage/'+prof.signing" alt="Firma Image" width="200px" height="150px">
									<input @change="showFirma" type="file" class="form-control d-none" name="file" id="firma">
									<label class="btn btn-success mt-3" for="firma">Subir firma</label>
							</div>
						</div>
						<div class="text-center">
							<button @click="insertar()" v-if="type=='Agregar'" type="submit" class="btn btn-success btn-lg" data-bs-dismiss="modal">Registrar</button>
							<button @click="update()" v-else type="submit" class="btn btn-success btn-lg" data-bs-dismiss="modal">Editar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default {
   name: "modal-prof",
   data(){
	   return{
		   profe:{
			   photo: null,
			   signing : null
		   }
	   }
   },
   props:{
	   prof: Object,
	   type: String
   },
   methods: {
	   async insertar(){
			const config = {
					headers: { 'content-type': 'multipart/form-data' }
			}
				let formData = new FormData();
				formData.append('email', this.prof.email);
				formData.append('password', this.prof.password);
				formData.append('name', this.prof.name);
				formData.append('phone', this.prof.phone);
				formData.append('profession', this.prof.profession);
				formData.append('cv_description', this.prof.cv_description);
				formData.append('photo', this.profe.photo);
				formData.append('signing', this.profe.signing);
		   await this.axios.post('/api/professional', formData, config)
		   .then(response => {
			   console.log('rpta:'+response)
				this.clearModal()
				this.clearImg()
				this.closeModal()
				this.$parent.getProfesionales()
				this.$swal('Profesional creado con éxito')
		   })
		   .catch(error => {
			   console.log(error)
		   })
	   },
	   async update(){
		   const config = {
					headers: { 'content-type': 'multipart/form-data' }
			}
				let formData = new FormData()
				formData.append('email', this.prof.email)
				formData.append('password', this.prof.password)
				formData.append('name', this.prof.name);
				formData.append('phone', this.prof.phone)
				formData.append('profession', this.prof.profession)
				formData.append('cv_description', this.prof.cv_description)
				formData.append('photo', this.profe.photo)
				formData.append('signing', this.profe.signing)
				formData.append('_method', 'PUT')

		   await this.axios.post('/api/professional/'+this.prof.id, formData, config)
		   .then(response => {
				//this.clearModal()
				//this.clearImg()
				this.closeModal()
				this.$parent.getProfesionales()
				this.$swal('Profesional editado con éxito')
		   })
		   .catch(error => {
			   console.log('error'+error)
		   })
	   },
	   clearModal(){
			this.prof.email= '',
			this.prof.password= '',
			this.prof.name= '',
			this.prof.phone='',
			this.prof.profession='',
			this.prof.cv_description=''
	   }
	   ,
	   closeModal() {
		document.getElementById('closeModal').click();
	   },
	   showImg(e){
			document.getElementById('imgProf').src = URL.createObjectURL(e.target.files[0])
			this.profe.photo = e.target.files[0]
	   },
	   showFirma(e){
			document.getElementById('imgFirma').src = URL.createObjectURL(e.target.files[0])
			this.profe.signing = e.target.files[0]
	   },
	   clearImg(){
			document.getElementById('imgProf').src = 'https://img.freepik.com/vector-gratis/fondo-personaje-doctor_1270-84.jpg'
			this.photo = ''
	   }
   }
}
</script>

