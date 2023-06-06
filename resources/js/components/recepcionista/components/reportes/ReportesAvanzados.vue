<template>
	<div>
		<h1>Reportes avanzados</h1>
		<p>Filtros:</p>
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-12 col-md-3">
						<label for="">Tipo de reporte</label>
						<select class="form-select" v-model="idReporte">
							<option v-for="reporte in reportes" :value="reporte.id">{{ reporte.nombrado }}</option>
						</select>
					</div>
					<div class="col-12 col-md-3">
						<label for="">Año</label>
						<select class="form-select" v-model="fecha.año">
							<option v-for="año in años" :value="año">{{ año }}</option>
						</select>
					</div>
					<div class="col-12 col-md-3">
						<label for="">Mes</label>
						<select class="form-select text-capitalize" v-model="fecha.mes">
							<option class="text-capitalize" v-for="(mes, index) in meses" :value="index+1">{{ mes }}</option>
						</select>
					</div>
					<div class="col-12 col-md-3 d-flex align-items-end">
						<button class="btn btn-outline-primary" @click="pedirReporte()"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
					</div>
				</div>
			</div>
		</div>
		<div class="card mt-2">
			<div class="card-body">
				<table class="table table-sm table-hover">
					<thead>
						<th>N°</th>
						<th>Paciente</th>
						<th>Dr.</th>
						<th>Fecha</th>
						<th>@</th>
					</thead>
					<tbody>
						<tr v-for="(resultado, index) in resultados">
							<td>{{ index+1 }}</td>
							<td class="text-capitalize">{{ resultado.name }}</td>
							<td class="text-capitalize">{{ resultado.nombre}}</td>
							<td>{{ fechaLatam(resultado.attention_date) }}</td>
							<td><a class="btn btn-outline-primary btn-sm" :href="'/api/pdf/'+resultado.id" target="_blank"><i class="fa-solid fa-file-pdf"></i> Ver PDF</a></td>
						</tr>
						<tr v-if="resultados.length==0">
						<td colspan="4">No hay registros</td></tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
</template>
<script>
import moment from 'moment';

	export default{
		name: 'reportesAvanzados',
		data(){ return{
			años: [], meses:['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'],
			idReporte:0, resultados:[],
			fecha:{ año: moment().format('YYYY'), mes: moment().format('M') },
			reportes:[
				{id: 0, nombrado: 'Recetas sueltas'}
			]
		}},
		methods:{
			cargarDatos(){
				for(let i=2022; i<=moment().format('YYYY'); i++){
					this.años.push(i);
				}
				this.años.reverse();
			},
			pedirReporte(){
				this.axios.post('/api/pedirReporte/'+this.idReporte,{
					año: this.fecha.año, mes: this.fecha.mes
				})
				.then(serv=> { console.log(serv.data);
					this.resultados=serv.data
				})	
			},
			fechaLatam(fecha){
				return moment(fecha).format('DD/MM/YYYY');
			},
		},
		mounted(){
			this.cargarDatos()
		}
	}
</script>