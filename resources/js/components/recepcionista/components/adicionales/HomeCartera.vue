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
							<option value="-1">Todo el año</option>
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
							<th>Nombre y Apellidos</th>
							<th>Celular</th>
							<th>N° Citas</th>
							<th>N° Conf.</th>
							<!-- <th>N° Faltas</th> -->
							<th>No asistieron</th>
							<th>N° Anulados</th>
							<th>N° Reprogramados</th>
							<th>Actual</th>
							<th>@</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(cita, index) in citasResumidas">
							<td>{{ index+1 }}</td>	
							<td class="text-capitalize">{{ cita.patient.name.toLowerCase() }}</td>
							<td>{{ cita.patient.phone }}</td>
							<!-- <td>{{ ultimaFecha(cita.patient.id) }}</td> -->
							<td class="puntero" data-bs-toggle="modal" data-bs-target="#modalCitasPreview" @click="cargarCitas('visitas', cita.patient.id)">{{ cita.visitas }}</td>
							<td class="puntero" data-bs-toggle="modal" data-bs-target="#modalCitasPreview" @click="cargarCitas('confirmar', cita.patient.id)">{{ cita.confirmar }}</td>
							<!-- <td class="puntero" data-bs-toggle="modal" data-bs-target="#modalCitasPreview" @click="cargarCitas('faltas', cita.patient.id)">{{ cita.faltas }}</td> -->
							<td class="puntero" data-bs-toggle="modal" data-bs-target="#modalCitasPreview" @click="cargarCitas('sinconfirmar', cita.patient.id)">{{ cita.sinconfirmar }}</td>
							<td class="puntero" data-bs-toggle="modal" data-bs-target="#modalCitasPreview" @click="cargarCitas('anulados', cita.patient.id)">{{ cita.anulados }}</td>
							<td class="puntero" data-bs-toggle="modal" data-bs-target="#modalCitasPreview" @click="cargarCitas('reprogramados', cita.patient.id)">{{ cita.reprogramados }}</td>
							<td>{{ cita.actual }}</td>
							<td class="puntero" @click="idGlobal = cita.patient_id; indexGlobal = index" data-bs-toggle="modal" data-bs-target="#modalCambiarSeguimiento">
								<span v-if="cita.patient.seguimiento==1" title="Sin acción"><i class="fa-regular fa-circle"></i></span>
								<span v-else :class=" queColor(cita.patient.seguimiento)" :title="queSeguimiento(cita.patient.seguimiento)"><i class="fas fa-circle"></i></span>
								<button class="btn btn-sm btn-circle btn-outline-primary" title="Enviar a seguimiento" data-bs-target="#modalSeguimiento" data-bs-toggle="modal" @click="elegido = cita.patient"><i class="far fa-paper-plane"></i></button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<!-- Modal -->
	<div class="modal fade" id="modalCitasPreview" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">{{titulo}}</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<table class="table table-hover table-sm">
						<thead>
							<tr>
								<th>N°</th>
								<th>Fecha</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(preview, index) in previewCitas">
								<td>{{ index+1 }}</td>
								<td>{{ fechaLatam(preview.date) }}</td>
							</tr>
							<tr v-if="previewCitas.length==0">
								<td colspan="2">No se encontraron datos</td>
							</tr>
						</tbody>
					</table>
					
				</div>
			</div>
		</div>
	</div>
	<ModalCambiarSeguimiento :seguimientos="seguimientos" :idPaciente="idGlobal" @cambiar="cambiarItem"></ModalCambiarSeguimiento>
	<ModalSeguimiento :profesionales="profesionales" :elegido="elegido" :idProfesional="filtro.idProfesional"></ModalSeguimiento>

	</div>
</template>

<script>
import ModalCambiarSeguimiento from './ModalCambiarSeguimiento.vue'
import ModalSeguimiento from './ModalSeguimiento.vue'
import moment from 'moment'

export default{
	name: 'HomeCartera',
	data(){ return {
		profesionales:[], años:[], meses:['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'],
		filtro: {idProfesional: 10, año: 2023, mes:-1}, citasResumidas:[], citasCompletas:[], previewCitas:[], titulo:'', idGlobal:-1, indexGlobal:-1,
		seguimientos:[], elegido:[]
	}},
	components:{ModalCambiarSeguimiento, ModalSeguimiento},
	methods:{
		async listarProfesional(){
			await this.axios.get('/api/profesional')
			.then(response => {
				this.profesionales=response.data;
			});

			await this.axios.get('/api/pedirSeguimientos')
			.then(response => this.seguimientos = response.data )
			this.seguimientos.sort()
		},
		buscarCartera(){
			let condicion

			this.axios.post('/api/buscarCartera', this.filtro)
			.then(res => { console.log(res.data);
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
		async cargarCitas(caso, id){
			switch(caso){
				case 'visitas':
					this.titulo = 'Total de Citas'
					this.previewCitas = this.citasCompletas.filter(item=> item.patient_id == id ); break;
				case 'confirmar':
					this.titulo = 'Citas confirmadas'
					this.previewCitas = this.citasCompletas.filter(item=> item.patient_id == id && item.status==2 ); break;
				case 'sinconfirmar':
					this.titulo = 'Citas sin confirmar'
					this.previewCitas = this.citasCompletas.filter(item=> item.patient_id == id && item.status==1 ); break;
				case 'anulados':
					this.titulo = 'Citas anuladas'
					this.previewCitas = this.citasCompletas.filter(item=> item.patient_id == id && item.status==3 ); break;
				case 'reprogramados':
					this.titulo = 'Citas reprogramadas'
					this.previewCitas = this.citasCompletas.filter(item=> item.patient_id == id && item.status==4 ); break;
			}
		},
		queSeguimiento(item){ if(item) return this.seguimientos.find(x=> x.id == item).seguimiento },
		queColor(item){ if(item)  return this.seguimientos.find(x=> x.id == item).color },
		cambiarItem(item){ this.citasResumidas[this.indexGlobal].patient.seguimiento = item }
		
	},
	mounted(){
		for(let i=moment().format('YYYY'); i>=2020 ; i--){
			this.años.push(i)
		}
		this.listarProfesional()
	}
}
</script>

<style >
	.puntero{ cursor: pointer; }
	.text-amarillo{ color: yellow}
</style>