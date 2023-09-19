<template>
	<main>
		<h1>Usuarios logísticos</h1>
		<div class="card">
			<div class="card-body">
				<div class="mb-2">
					<button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalCrearUsuario"><i class="far fa-user"></i> Crear nuevo usuario logístico</button>
				</div>
				<table class="table table-hover">
					<thead>
						<tr>
							<th>N°</th>
							<th>Nombre</th>
							<th>Nick / Correo</th>
							<th>Nivel</th>
							<th>Privilegios</th>
							<th>@</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(usuario, index) in usuarios">
							<td>{{ index+1 }}</td>
							<td class="text-capitalize">{{ usuario.nombre }}</td>
							<td>{{ usuario.email }}</td>
							<td class="text-capitalize">{{ usuario.rol }}</td>
							<td>{{ usuario.privilegios==0? 'No': 'SI' }}</td>
							<td>
								<button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario" @click="queUsuario=usuario" ><i class="fa-solid fa-pen-to-square"></i> Editar</button>
								<button class="btn btn-outline-danger btn-sm" @click="cargarEliminar(index)" ><i class="fas fa-user-slash"></i> Eliminar</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<ModalEditarUsuario :usuario="queUsuario"></ModalEditarUsuario>
		<ModalNuevoUsuario></ModalNuevoUsuario>
	</main>
</template>
<script>
import ModalEditarUsuario from './ModalEditarUsuario'
import ModalNuevoUsuario from './ModalNuevoUsuario'
export default{
	name:'HomeUsuarios',
	components:{ModalEditarUsuario, ModalNuevoUsuario},
	data(){ return {
		usuarios:[], queUsuario:[]
	}},
	methods:{
		cargarUsuarios(){
			this.axios.get('/api/cargarUsuarios')
			.then(res => this.usuarios = res.data)
		},
		cargarEliminar(index){
			if(confirm(`¿Está seguro que desea elminar a ${this.usuarios[index].email}?`)){
				this.axios.post('/api/eliminarUsuario', {id: this.usuarios[index].id})
				.then(res=> {console.log(res.data); this.cargarUsuarios(); })
			}
		}
	},
	mounted(){
		this.cargarUsuarios()
	}

}</script>