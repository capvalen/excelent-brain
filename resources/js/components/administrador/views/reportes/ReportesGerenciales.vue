<template lang="es">
	<div>
		<h1 class="h3 mb-0 text-gray-800 mb-2">Reportes gerenciales</h1>
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-12 col-md-3">
						<label for="">Tipo de reporte</label>
						<select class="form-select" v-model="idReporte" @change="configurarVista()">
							<option v-for="reporte in reportes" :value="reporte.id">{{ reporte.nombrado }}</option>
						</select>
					</div>
					<div class="col-12 col-md-3" v-show="!ocultarFechas">
						<label for="">Año</label>
						<select class="form-select" v-model="fecha.año">
							<option v-for="año in años" :value="año">{{ año }}</option>
						</select>
					</div>
					<div class="col-12 col-md-3" v-show="!ocultarFechas">
						<label for="">Mes</label>
						<select class="form-select text-capitalize" v-model="fecha.mes">
							<option v-if="filtroAnual" value="-1" class="text-capitalize">Todo el año</option>
							<option class="text-capitalize" v-for="(mes, index) in meses" :value="index+1">{{ mes }}</option>
						</select>
					</div>
					<div class="col-12 col-md-3 d-flex align-items-end">
						<button class="btn btn-outline-primary" @click="pedirReporte()"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import moment from 'moment';

export default {
	name: 'ReportesGerenciales',
	data() {
		return {
			años: [], meses:['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'],
			idReporte:0, resultados:[], ocultarFechas:false,
			fecha:{ año: moment().format('YYYY'), mes: moment().format('M') },
			reportes:[
				{id: 1, nombrado: 'Tipos de pacientes'},
			],
			filtroAnual:false, filtro:-1,
		}
	},
	methods: {
		pedirReporte(){
			this.axios.post('/api/pedirReporteGerencial/'+this.idReporte,{
				año: this.fecha.año, mes: this.fecha.mes
			})
			.then(serv=> { console.log(serv.data);
				this.resultados=serv.data;
			})
		},
		fechaFrom(fecha){
			moment.locale('es')
			return moment(fecha, 'YYYY-MM-DD').fromNow();
		},
		fechaLatam(fecha){ return moment(fecha).format('DD/MM/YYYY'); },
		horaLatam(horita){ return moment(horita, 'HH:mm:ss').format('hh:mm a') },
		
	},
	mounted() {
		this.reportes.sort((a, b) => {
			if (a.nombrado < b.nombrado) return -1;
			if (a.nombrado > b.nombrado) return 1;
			return 0;
		});
		this.cargarDatos()
	},
}
</script>
<style lang="">
	
</style>