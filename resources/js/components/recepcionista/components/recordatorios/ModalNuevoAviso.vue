<template>
	
	<div class="modal fade" id="nuevoAviso" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h5 class="modal-title " id="exampleModalLabel">Nuevo aviso, recordatorio, llamada</h5>
					<button type="button" id="closeModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<label for="">Fecha y hora</label>
					<input type="datetime-local" class="form-control" id="fechaHora" v-model="aviso.fecha">
					<label for="">Tipo</label>
					<select v-model="aviso.tipo" class="form-select" id="sltTipo">
						<option value="1">Aviso</option>
						<option value="2">Llamada</option>
						<option value="3">Recordatorio</option>
					</select>
					<label for="">Actividad</label>
					<input type="text" class="form-control" id="fechaHora" v-model="aviso.actividad" autocomplete="off">
					<label for="">Responsable</label>
					<select class="form-select" id="sltUsuarios" v-model="aviso.idResponsable">
						<option v-for="usuario in usuarios" :value="usuario.id">{{ usuario.nombre =='' ? '-': usuario.nombre }}</option>
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
		name: 'ModalNuevoAviso',
		props:{ usuario: null},
		data(){
			return{
				aviso:{
					fecha: moment().format('YYYY-MM-DD[T]HH:mm'),
					actividad:'', responsable:'', tipo:1, idResponsable:-1,
				}, usuarios:[]
			}
		},
		mounted(){
			this.cargarUsuarios();
		},
		methods:{
			cargarUsuarios(){
				this.axios('/api/cargarUsuarios')
				.then(res => this.usuarios = res.data )
			},
			guardar(){
				if(this.aviso.fecha==''){alertify.notify('La fecha debe ser rellenada correctamente', 'danger', 10);}
				else if(this.aviso.actividad==''){alertify.notify('La actividad no puede estar vacío', 'danger', 10);}
				else if(this.aviso.idResponsable=='' || this.aviso.idResponsable==-1 ){alertify.notify('Debe haber un responsable', 'danger', 10);}
				else{
					const select = document.getElementById("sltUsuarios");
					const selectedOption = select.options[select.selectedIndex];
					this.aviso.responsable = selectedOption.textContent;
					let datos = new FormData();
					datos.append('fecha', this.aviso.fecha)
					datos.append('actividad', this.aviso.actividad)
					datos.append('responsable', this.aviso.responsable)
					datos.append('idResponsable', this.aviso.idResponsable)
					datos.append('creador', this.usuario )
					datos.append('tipo', this.aviso.tipo )
					fetch('/api/nuevoAviso',{
						method:'POST', body:datos
					})
					.then(response=> response.json())
					.then(texto=>{
						this.$parent.actualizarAvisos();
						this.cerrarModal()
						if( texto.mensaje )
							this.$swal('Se creó exitosamente');
						else
							this.$swal({icon:'error',title: 'Hubo un error interno'});
					})
				}
			},
			cerrarModal(){
				document.getElementById('closeModal').click();
			}
		}
	}
</script>