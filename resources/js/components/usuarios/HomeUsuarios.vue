<template>
	<main>
		<h1>Usuarios básicos</h1>
		<div class="card">
			<div class="card-body">
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
							<td><button class="btn btn-outline-primary"><i class="fa-solid fa-pen-to-square"></i> Editar</button></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</main>
</template>
<script>
export default{
	name:'HomeUsuarios',
	data(){ return {
		usuarios:[]
	}},
	methods:{
		cargarUsuarios(){
			this.axios.get('/api/cargarUsuarios')
			.then(res => this.usuarios = res.data)
		}
	},
	mounted(){
		this.cargarUsuarios()
	}

}</script>