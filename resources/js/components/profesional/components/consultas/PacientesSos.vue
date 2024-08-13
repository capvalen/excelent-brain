<template>
	<div>
		<h1>Pacientes con S.O.S.</h1>
		<p>Los siguientes pacientes están con riesgo de suicidio:</p>

		<table class="table table-hover">
			<thead>
				<th>N°</th>
				<th>Nombres</th>
				<th>Profesional</th>
				<th>Mensaje</th>
				<th>Familiares</th>
				<th>Fecha</th>
			</thead>
			<tbody>
				<tr v-for="(paciente, index) in pacientes">
					<td>{{ index+1 }}</td>
					<td>{{ paciente.name }} {{ paciente.nombres }}</td>
					<td>{{ paciente.nombreProfesional	}} </td>
					<td>{{ paciente.comentarios }} </td>
					<td>
						<p v-for="familiar in paciente.relaciones">
							{{ familiar.name }} <br> {{ familiar.phone }}
						</p>
					</td>
					<td>{{ fechaLatam(paciente.registro) }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</template>

<script>
import moment from 'moment';
export default{
	data(){ return{
		pacientes:[]
	}},
  name: 'PacienteSOS',
  props: {},
	mounted(){
		this.axios.get('/api/pedirSOS')
		.then(res=>{
			console.log(res.data)
			this.pacientes = res.data.pacientes
		})
	},
  methods:{
    fechaLatam(fecha){
			return moment(fecha).format('DD/MM/YYYY');
		},
		fechaLectura(fecha){
			moment.locale('es')
			return moment(fecha).format('dddd, DD [de] MMMM YYYY [a las] hh:mm a');
		},
  }
}
</script>