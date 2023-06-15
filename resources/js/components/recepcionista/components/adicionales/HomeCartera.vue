<template>
	<div>
		<h1>Cartera de clientes con profesionales</h1>
		<div class="card">
			<div class="card-body ">
				<div class="row row-cols-auto g-3  align-items-center">
				
					<div class="col-3">
						<label for="">Profesional</label>
						<select class="form-select " v-model="filtro.idProfesional">
							<option v-for="profesional in profesionales" :value="profesional.id">{{profesional.name}}</option>
						</select>
					</div>
					<div class="col-3">
						<label for="">Año</label>
						<select class="form-select" v-model="filtro.año">
							<option v-for="año in años" :value="año">{{año}}</option>
						</select>
					</div>
					<div class="col-3">
						<label for="">Mes</label>
						<select class="form-select text-capitalize" v-model="filtro.mes">
							<option class="text-capitalize" v-for="(mes, index) in meses" :value="index+1">{{ mes }}</option>
						</select>
					</div>
					<div class="col-3 d-flex align-items-end">
						<button class="btn btn-outline-primary" @click="buscarCartera()"><i class="fa-solid fa-magnifying-glass"></i> Filtrar cartera</button>
					</div>
				</div>
			</div>
		</div>
		<div class="card mt-3">
			<div class="card-body">
				<table class="table table-sm table-hover">
					<thead>
						<tr>
							<th>N°</th>
							<th>Cod.</th>
							<th>Nombre y Apellidos</th>
							<th>Celular</th>
							<th>N° Citas</th>
							<th>N° Conf.</th>
							<th>N° Faltas</th>
							<th>No asistieron</th>
							<th>N° Anulados</th>
							<th>N° Reprogramados</th>
							<th>Actual</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(cita, index) in citasResumidas">
							<td>{{ index+1 }}</td>
							<td>{{ cita.patient.id }}</td>
							<td>{{ cita.patient.name }}</td>
							<td>{{ cita.patient.phone }}</td>
							<!-- <td>{{ ultimaFecha(cita.patient.id) }}</td> -->
							<td>{{ cita.visitas }}</td>
							<td>{{ cita.confirmar }}</td>
							<td>{{ cita.faltas }}</td>
							<td>{{ cita.sinconfirmar }}</td>
							<td>{{ cita.anulados }}</td>
							<td>{{ cita.reprogramados }}</td>
							<td>{{ cita.actual }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</template>

<script>
import moment from 'moment'

export default{
	name: 'HomeCartera',
	data(){ return {
		profesionales:[], años:[], meses:['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'],
		filtro: {idProfesional: 10, año: 2023, mes:1}, citasResumidas:[], citasCompletas:[]
	}},
	methods:{
		async listarProfesional(){
			await this.axios.get('/api/profesional')
			.then(response => {
				this.profesionales=response.data;
			})
		},
		buscarCartera(){
			let condicion

			this.axios.post('/api/buscarCartera', this.filtro)
			.then(res => {
				this.citasResumidas = res.data.resumidas;
				this.citasCompletas = res.data.completas;
				this.citasResumidas.forEach((cita, index )=> {
					cita.visitas = Object.values(this.citasCompletas).filter(item => item.patient_id === cita.patient_id).length;
					cita.sinconfirmar = Object.values(this.citasCompletas).filter(item => item.patient_id === cita.patient_id && item.status==1 ).length;
					cita.confirmar = Object.values(this.citasCompletas).filter(item => item.patient_id === cita.patient_id && item.status==2 ).length;
					cita.anulados = Object.values(this.citasCompletas).filter(item => item.patient_id === cita.patient_id && item.status==3 ).length;
					cita.reprogramados = Object.values(this.citasCompletas).filter(item => item.patient_id === cita.patient_id && item.status==4 ).length;
					cita.fatas = cita.patient.faults;
					if(cita.patient.discharge==1) cita.actual = 'De Alta'
					else{
						condicion =  Object.values(this.citasCompletas).filter(item => item.patient_id === cita.patient_id && item.patient_condition==2 ).length;
						if(condicion>0) cita.actual = 'Continuante'
						else cita.actual = 'Nuevo'
					}
					if(index==0){
						//cita.
					}
					/* this.citasCompletas.filter(x=> {
						if( x.patient_id==id && x.status ==1 ) ''
					}) */
					
				})
			})
		},
		fechaLatam(fecha){
			return moment(fecha).format('DD/MM/YYYY');
		},
		
	},
	mounted(){
		for(let i=moment().format('YYYY'); i>=2020 ; i--){
			this.años.push(i)
		}
		this.listarProfesional()
	}
}
</script>