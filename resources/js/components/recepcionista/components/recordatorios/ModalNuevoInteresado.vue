<template>
	
	<div class="modal fade" id="nuevoInteresado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h5 class="modal-title " id="exampleModalLabel">Nuevo seguimiento</h5>
					<button type="button" id="closeModal" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<label for="name">Dni</label>
					<div class="input-group mb-3">
						<input type="text" class="form-control" name="dni" id="dni" v-model="interesado.dni" placeholder="Dni del paciente" @keypress.enter="reniec()">
						<button class="btn btn-outline-secondary" type="button" id="button-addon2" @click="reniec()"><i class="fas fa-search"></i></button>
					</div>
					<label for="">Nombre <span class="text-danger">*</span></label>
					<input type="text" class="form-control" v-model="interesado.nombre_completo" autocomplete="off">
					<label for="">Celular <span class="text-danger">*</span></label>
					<input type="text" class="form-control" v-model="interesado.celular" autocomplete="off">
					<label for="">Correo electrónico</label>
					<input type="text" class="form-control" v-model="interesado.correo" autocomplete="off">
					<label for="">Motivo <span class="text-danger">*</span></label>
					<input type="text" class="form-control" v-model="interesado.motivo" autocomplete="off">
					<label for="">Referencia</label>
					<select v-model="interesado.referencia" class="form-select" id="sltTipo">
						<option value="1">Ninguno</option>
						<option value="2">Recomendación</option>
						<option value="3">Publicidad de internet</option>
						<option value="4">Publicidad de Escrita</option>
						<option value="5">Publicidad de TV/Radio</option>
						<option value="6">Referido</option>
					</select>
				</div>
				<div class="modal-footer border-0">
					<button type="button" class="btn btn-primary" @click="guardar()">Guardar</button>
				</div>
			</div>
		</div>
	</div>

</template>
<script>
	import moment from 'moment'
	import alertify from 'alertifyjs'

	export default{
		name: 'ModalNuevoInteresado',
		props:{ usuario: null},
		data(){
			return{
				token:'087d16c0688f5150268342d085a55d54b5064c7649596011f03b35b935899a50',
				interesado:{
					actividad:'', responsable:'', tipo:1, correo:'', nombre_completo:''
				}
			}
		},
		methods:{
			reniec(){
				this.$swal.fire({
				title: 'Buscando paciente',
				timer: 2500,
				timerProgressBar: true,
				didOpen: () => {
					timerProgressBar: true,
					this.$swal.showLoading()
				},
			})

			this.axios.get("/api/buscar/"+this.interesado.dni)
			.then(res => {
				if (res.data.patient == null) { //Buscar en reniec
					window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
					this.axios.get(`https://apiperu.dev/api/dni/${this.interesado.dni}`) //?api_token=${this.token}
					.then(response => {
						console.log(response.data)
						this.interesado.nombre_completo =`${response.data.data.apellido_paterno} ${response.data.data.apellido_materno} ${response.data.data.nombres}`;
						if (response.data.success) {
							this.$swal.fire({
								icon: 'success',
								title: 'Okey',
								text: 'interesado nuevo',
							})
						} else {
							this.$swal.fire({
								icon: 'error',
								title: 'Oops...',
								text: 'DNI no encontrado!',
								footer: 'Vuelve a intentarlo'
							})
						}
					})
					.catch(err => {
						console.error(err)
					})
				}else{ //encontro en la DB
					this.$swal.fire({
						title: 'Buscando interesado',
						timer: 10,
					})
					console.log('datos del interesado',res.data);

					this.interesado.nombre_completo = res.data.patient.name;
					this.interesado.celular = res.data.patient.phone;
					this.interesado.correo = res.data.patient.email;
				}
				})
				.catch(err => {
					console.error(err)
				})
				.finally(result => {
					this.switchReciec = 1;
					document.querySelector(".btnReniec").classList.replace('btn-danger', 'btn-info')
				})
			},
			guardar(){
				if(this.interesado.nombre_completo==''){alertify.notify('Debe existir un nombre', 'danger', 10);}
				else if(this.interesado.celular==''){alertify.notify('Debe escribir un celular', 'danger', 10);}
				else if(this.interesado.motivo==''){alertify.notify('Debe haber algún motivo', 'danger', 10);}
				else{
					let datos = new FormData();
					datos.append('nombre', this.interesado.nombre_completo)
					datos.append('celular', this.interesado.celular)
					datos.append('motivo', this.interesado.motivo)
					datos.append('correo', this.interesado.correo)
					datos.append('referencia', this.interesado.referencia)
					datos.append('origen', 1)
					datos.append('idProfesional', 1)
					fetch('/api/nuevoInteresado',{
						method:'POST', body:datos
					})
					.then(response=> response.json())
					.then(texto=>{
						this.cerrarModal()
						if( texto.mensaje )
							this.$swal('Se creó exitosamente');
						else
							this.$swal({icon:'error',title: 'Hubo un error interno'});
					})
				}
			},
			cerrarModal(){
				this.interesado = { actividad:'', responsable:'', tipo:1 }
				document.querySelector('#nuevoInteresado #closeModal').click();
			}
		}
	}
</script>