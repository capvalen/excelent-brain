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
					<div class="col-12 col-md-2" v-show="!ocultarFechas">
						<label for="">Fecha Inicial</label>
						<input type="date" class="form-control" v-model="fecha.inicio">
						<!-- <select class="form-select" v-model="fecha.año">
							<option v-for="año in años" :value="año">{{ año }}</option>
						</select> -->
					</div>
					<div class="col-12 col-md-2" v-show="!ocultarFechas">
						<label for="">Fecha Final</label>
						<input type="date" class="form-control" v-model="fecha.fin">
						<!-- <label for="">Mes</label>
						<select class="form-select text-capitalize" v-model="fecha.mes">
							<option v-if="filtroAnual" value="-1" class="text-capitalize">Todo el año</option>
							<option class="text-capitalize" v-for="(mes, index) in meses" :value="index+1">{{ mes }}</option>
						</select> -->
					</div>
					<div class="col-12 col-md-2">
						<label for="">Sede</label>
						<select class="form-select" id="sltSede" v-model="fecha.idSede">
							<option value="1">Sede El Tambo</option>
							<option value="2">Sede San Carlos</option>
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
				<div  v-if="idReporte==1">
					<table class="table table_hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Tipo de paciente</th>
								<th>Cantidad</th>
								<th>Porcentaje</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>Pacientes Nuevos de Psiquiatría</td>
								<td>{{conteo.psiquiatria.nuevo}}</td>
								<td>{{parseFloat(conteo.psiquiatria.nuevo / conteo.total*100).toFixed(2)}}%</td>
							</tr>
							<tr>
								<td>2</td>
								<td>Pacientes Contninuos de Psiquiatría</td>
								<td>{{conteo.psiquiatria.continuo}}</td>
								<td>{{parseFloat(conteo.psiquiatria.continuo / conteo.total*100).toFixed(2)}}%</td>
							</tr>
							<tr>
								<td>3</td>
								<td>Pacientes Nuevos de Psicología</td>
								<td>{{conteo.psicologia.nuevo}}</td>
								<td>{{parseFloat(conteo.psicologia.nuevo / conteo.total*100).toFixed(2)}}%</td>
							</tr>
							<tr>
								<td>4</td>
								<td>Pacientes Continuos de Psicología</td>
								<td>{{conteo.psicologia.continuo}}</td>
								<td>{{parseFloat(conteo.psicologia.continuo / conteo.total*100).toFixed(2)}}%</td>
							</tr>
							<tr>
								<td colspan="2">Total</td>
								<td>{{conteo.total}}</td>
								<td>100%</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div v-if="idReporte==2">
					<p><strong>Conteo por profesionales</strong></p>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Profesional</th>
								<th>N° Pacientes atendidos</th>
								<th>Porcentaje</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(doctor, indice) in conteoR2">
								<td>{{indice+1}}</td>
								<td>{{doctor.nombre}}</td>
								<td>{{doctor.conteo}}</td>
								<td>{{(doctor.conteo/resultados.total*100).toFixed(1)}}%</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td colspan=2 class="">Total</td>
								<td>{{resultados.total}}</td>
								<td>100%</td>
							</tr>
						</tfoot>
					</table>
					<p><strong>Conteo por continuidad</strong></p>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Condición</th>
								<th>Cantidad</th>
								<th>Porcentaje</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>Nuevos</td>
								<td>{{resultados.nuevos}}</td>
								<td>{{(resultados.nuevos/resultados.total*100).toFixed(1)}}%</td>
							</tr>
							<tr>
								<td>2</td>
								<td>Contínuos</td>
								<td>{{resultados.continuos}}</td>
								<td>{{(resultados.continuos/resultados.total*100).toFixed(1)}}%</td>
							</tr>
						</tbody>
					</table>
					<p><strong>Conteo por especialidad</strong></p>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Especialidad</th>
								<th>Cantidad</th>
								<th>Porcentaje</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(especialidad, clave, index) in resultados.especialidades">
								<td>{{index+1}}</td>
								<td>{{clave}}</td>
								<td>{{especialidad.length}}</td>
								<td>{{(especialidad.length/resultados.total*100).toFixed(1)}}%</td>
							</tr>
						</tbody>
					</table>
					<p>Detallado:</p>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Profesional</th>
								<th>Paciente</th>
								<th>Especialidad</th>
								<th>Servicio</th>
								<th>Fecha y Hora</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(cita, index) in resultados.cartera" >
								<td>{{index+1}}</td>
								<td v-if="cita.professional">{{cita.professional.name}} {{cita.professional.nombres}}</td>
								<td v-else></td>
								<td v-if="cita.patient">{{cita.patient.name}} {{cita.patient.nombres}}</td>
								<td v-else></td>
								<td v-if="cita.patient">{{cita.professional.profession}}</td>
								<td v-else></td>
								<td v-if="cita.precio">{{cita.precio.descripcion}}</td>
								<td v-else></td>
								<td v-if="cita.schedule">{{cita.schedule.check_time}}</td>
								<td v-else></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div v-if="idReporte==3">
					<p><strong>Reporte agrupado</strong></p>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Profesión</th>
								<th>N° Pacientes atendidos</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(aux, clave, index ) in auxiliares">
								<td>{{index+1}}</td>
								<td>{{clave}}</td>
								<td>{{aux}}</td>
							</tr>
						</tbody>
					</table>
					<p><strong>Reporte detallado</strong></p>
					<table class="table table-hover">
						<thead>
							<tr>
								<td>N°</td>
								<td>Profesión</td>
								<td>Profesional</td>
								<!-- <td>N° Pacientes atendidos</td> -->
								<td>N° Pacientes nuevos</td>
								<td>N° Pacientes continuos</td>
								<!-- <td>N° Pacientes Anulados</td> -->
								<td>N° Reevaluaciones</td>
								<td>N° Certificados</td>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(doctor, indice) in conteoR2">
								<td>{{indice+1}}</td>
								<td>{{doctor.profesion}}</td>
								<td>{{doctor.nombre}}</td>
								<!-- <td>{{doctor.conteo}}</td> -->
								<td>{{doctor.nuevo}}</td>
								<td>{{doctor.continuo}}</td>
								<!-- <td>{{doctor.anulado}}</td> -->
								<td>{{doctor.revaluaciones}}</td>
								<td>{{doctor.certificados}}</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div v-if="idReporte==4">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Profesión</th>
								<th>Profesional</th>
								<th># de atenciones</th>
								<th>Monto</th>
							</tr>
						</thead>
						<tbody v-for="(doctor, clave, indice) in resultados.profesionales">
							<tr>
								<td>{{indice+1}}</td>
								<td>{{doctor.profesion}}</td>
								<td>{{clave}}</td>
								<td>{{doctor.citas.length}}</td>
								<td>{{doctor.suma.toFixed(2)}}</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td colspan=4 class="text-end">Total</td>
								<td>{{sumaMontosRegistrados}}</td>
							</tr>
						</tfoot>
					</table>
				</div>
				<div v-if="idReporte==5">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Profesión</th>
								<th>Profesional</th>
								<th>N° Reprogramaciones</th>
							</tr>
						</thead>
						<tbody v-for="(doctor, indice) in conteoR2">
							<tr>
								<td>{{indice+1}}</td>
								<td>{{doctor.profesion}}</td>
								<td>{{doctor.nombre}}</td>
								<td>{{doctor.reprogramaciones}}</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div v-if="idReporte==6">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Profesión</th>
								<th>Profesional</th>
								<th>N° Altas</th>
							</tr>
						</thead>
						<tbody v-for="(doctor, indice) in conteoR2">
							<tr>
								<td>{{indice+1}}</td>
								<td>{{doctor.profesion}}</td>
								<td>{{doctor.nombre}}</td>
								<td>{{doctor.altas}}</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div v-if="idReporte==7">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Profesión</th>
								<th>Profesional</th>
								<th>N° Recetas	</th>
							</tr>
						</thead>
						<tbody v-for="(doctor, indice) in conteoR2">
							<tr>
								<td>{{indice+1}}</td>
								<td>{{doctor.profesion}}</td>
								<td>{{doctor.nombre}}</td>
								<td>{{doctor.recetas}}</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div v-if="idReporte==8">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Código</th>
								<th>Diagnóstico</th>
								<th>Cant. Diag.</th>
							</tr>
						</thead>
						<tbody v-for="(cie, indice) in resultados">
							<tr>
								<td>{{indice+1}}</td>
								<td>{{cie.code}}</td>
								<td>{{cie.description}}</td>
								<td>{{cie.contador}}</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div v-if="idReporte==9">
					<p class="mt-2"><strong>Reporte de pacientes por edades</strong></p>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Edad</th>
								<th>Cantidad</th>
								<th>Porcentajes</th>
							</tr>
						</thead>
						<tbody v-for="(edad, key, indice) in resultados.edades" :key='key'>
							<tr>
								<td>{{indice+1}}</td>
								<td>{{key}} años</td>
								<td>{{edad.length}}</td>
								<td>{{ parseFloat(edad.length/resultados.total*100).toFixed(2) }}%</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td></td>
								<td>Total</td>
								<td>{{ resultados.total }}</td>
								<td>100%</td>
							</tr>
						</tfoot>
					</table>
					<p class="mt-2"><strong>Reporte de pacientes por sexos</strong></p>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Género</th>
								<th>Cantidad</th>
								<th>Porcentajes</th>
							</tr>
						</thead>
						<tbody v-for="(edad, key, indice) in resultados.sexos" >
							<tr>
								<td>{{indice+1}}</td>
								<td>
									<span v-if="key==0">Femenino</span>
									<span v-if="key==3">LGTB+</span>
									<span v-if="key==1">Masculino</span>
									<span v-if="key==2">No indicó</span>
									<span v-if="key==null">No data</span>
									<span v-if="key=='sin_dato'">No data</span>
								</td>
								<td>{{edad.length}}</td>
								<td>{{ parseFloat(edad.length/resultados.total*100).toFixed(2) }}%</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td></td>
								<td>Total</td>
								<td>{{ resultados.total }}</td>
								<td>100%</td>
							</tr>
						</tfoot>
					</table>
					<p class="mt-2"><strong>Reporte de recomendaciones</strong></p>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Género</th>
								<th>Cantidad</th>
								<th>Porcentajes</th>
							</tr>
						</thead>
						<tbody v-for="(reco, indice) in resultados.recomendados" >
							<tr>
								<td>{{indice+1}}</td>
								<td>{{reco.recomendation}}</td>
								<td>{{reco.contador}}</td>
								<td>{{ parseFloat(parseInt(reco.contador)/parseInt(contarRecomendados)*100).toFixed(2) }}%</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td></td>
								<td>Total</td>
								<td>{{ contarRecomendados }}</td>
								<td>100%</td>
							</tr>
						</tfoot>
					</table>
				</div>
				<div v-if="idReporte==10">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Comprobante</th>
								<th>Cantidad</th>
								<th>Monto recaudado</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(pago, key, indice) in resultados"" :key="key">
								<td>{{indice+1}}</td>
								<td>{{key}}</td>
								<td>{{pago.length}}</td>
								<td>S/ {{ sumaTipoComprobante(key) }}</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div v-if="idReporte==11">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Servicio</th>
								<th>Monto recaudado</th>
								</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>Psicología</td>
								<td>{{resultados.psicologia.total}}</td>
							</tr>
							<tr>
								<td>2</td>
								<td>Psiquiatría</td>
								<td>{{resultados.psiquiatria.total}}</td>
							</tr>
							<tr v-for="(pago, clave, indice) in resultados.extras">
								<td>{{indice+3}}</td>
								<td>{{clave.replace('Pago de membresía', 'Pago de paquetes')}}</td>
								<td>{{pago.total.toFixed(2)}}</td>
							</tr>
							
						</tbody>
					</table>
				</div>
				<div v-if="idReporte==12">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Medio de pago</th>
								<th>N° Registros</th>
								<th>Monto recaudado</th>
								</tr>
						</thead>
						<tbody>
							<tr v-for="(pago, key, indice) in resultados" :key="key">
								<td>{{indice+1}}</td>
								<td>{{key}}</td>
								<td>{{pago.length}}</td>
								<td>{{sumaMedios(key)}}</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div v-if="idReporte==13">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>Especialidad</th>
								<th>Estado</th>
								<th>Conteo</th>
								<th>Porcentaje</th>
								</tr>
						</thead>
						<tbody>
							<template v-for="(especialidad, clave) in resultados.especialidades">
								<tr>
									<td>{{clave}}</td>
									<td>Nuevos</td>
									<td>{{especialidad.nuevos}}</td>
									<td>{{ parseFloat(especialidad.nuevos/resultados.total*100).toFixed(2) }}%</td>

								</tr>
								<tr>
									<td>{{clave}}</td>
									<td>Contínuos</td>
									<td>{{especialidad.continuos}}</td>
									<td>{{ parseFloat(especialidad.continuos/resultados.total*100).toFixed(2) }}%</td>
								</tr>
							</template>
						</tbody>
						<tfoot v-if="resultados.especialidades">
							<tr>
								<td></td>
								<td>Total</td>
								<td>{{resultados.total}}</td>
								<td>100%</td></tr>
						</tfoot>
					</table>
				</div>
				<div v-if="idReporte==14">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Medicamento</th>
								<th>Cantidad</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(medicamento, clave, indice) in resultados">
								<td>{{indice+1}}</td>
								<td>{{clave}}</td>
								<td>{{medicamento}}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import { TimeScale } from 'chart.js';
import moment from 'moment';

export default {
	name: 'ReportesGerenciales',
	data() {
		return {
			años: [], meses:['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'],
			idReporte:0, resultados:[], ocultarFechas:false, conteo:{total:0, psiquiatria:{nuevo:0, continuo:0}, psicologia: {nuevo:0, continuo:0}}, auxiliares:[],
			fecha:{ año: moment().format('YYYY'), mes: moment().format('M'), inicio:moment().format('YYYY-MM-DD'), fin:moment().format('YYYY-MM-DD'), idSede:1 }, conteoR2:[],
			reportes:[
				{id: 1, nombrado: 'Tipos de pacientes'},
				{id: 2, nombrado: 'Cartera de clientes'},
				{id: 3, nombrado: 'Producción mes'},
				{id: 4, nombrado: 'Montos registrados'}, //
				{id: 5, nombrado: 'Reprogramaciones por profesional'},
				{id: 6, nombrado: 'Pacientes dados de alta'},
				{id: 7, nombrado: 'Recetas por profesional'},
				{id: 8, nombrado: 'Diagnósticos más frecuentes'},
				{id: 9, nombrado: 'Reporte demográfico por clientes'},
				{id: 10, nombrado: 'Comprobantes emitidos'},
				{id: 11, nombrado: 'Ingresos'}, //
				{id: 12, nombrado: 'Medios de pago'}, //
				{id: 13, nombrado: 'Estado de pacientes'},
				{id: 14, nombrado: 'Medicamentos más recetados'},
			],
			filtroAnual:false, filtro:-1,
		}
	},
	methods: {
		cargarDatos(){
			for(let i=2022; i<=moment().format('YYYY'); i++){
				this.años.push(i);
			}
			this.años.reverse();
		},
		pedirReporte(){
			this.resultados=[];
			this.axios.post('/api/pedirReporteGerencial/'+this.idReporte,{
				/* año: this.fecha.año, mes: this.fecha.mes */
				inicio: this.fecha.inicio, fin: this.fecha.fin, idSede:this.fecha.idSede
			})
			.then(serv=> { console.log(serv.data);
				this.resultados=serv.data;
				switch (this.idReporte) {
					case 1: this.contarTiposPaciente(); break;
					case 2: this.contarReporte2(); break;
					case 3: this.contarProduccion(); break;
					case 4: this.contarMontosRegistrados(); break;
					case 5: this.contarReprogramaciones(); break;
					case 6: this.contarAltas(); break;
					case 7: this.contarRecetas(); break;
					default: break;
				}
				
			})
		},
		contarTiposPaciente(){
			this.conteo={total:0, psiquiatria:{nuevo:0, continuo:0}, psicologia: {nuevo:0, continuo:0}}
			this.resultados.nuevos.forEach(nuevo => {
				if( nuevo.precio)
					if( nuevo.precio.idClasificacion==1) //es Psiquiatria
						{this.conteo.psiquiatria.nuevo+=1; this.conteo.total++; }
					else if( nuevo.precio.idClasificacion==2) //es Psicologia
						{this.conteo.psicologia.nuevo+=1; this.conteo.total++; }
			});
			this.resultados.continuos.forEach(continuo => {
				if( continuo.precio)
					if( continuo.precio.idClasificacion==1) //es Psiquiatria
						{this.conteo.psiquiatria.continuo+=1; this.conteo.total++; }
					else if( continuo.precio.idClasificacion==2) //es Psicologia
						{this.conteo.psicologia.continuo+=1; this.conteo.total++; }
			});
		},
		contarReporte2(){
			this.conteoR2=[];
			this.conteoR2.push({idProfesional:this.resultados.cartera[0].professional_id, conteo:0, nombre: this.resultados.cartera[0].professional.name})
			this.resultados.cartera.forEach(cita=>{
				
				if(cita.professional_id !== this.conteoR2[this.conteoR2.length-1].idProfesional ){
					this.conteoR2.push({idProfesional: cita.professional_id, conteo:1, nombre: cita.professional.name})
				}else
					this.conteoR2[this.conteoR2.length-1].conteo++
			})
		},
		contarProduccion(){
			this.conteoR2=[];
			this.auxiliares=[];
			this.conteoR2.push({idProfesional:this.resultados[0].professional_id, nombre: this.resultados[0].professional.name, profesion: this.resultados[0].professional.profession,
				nuevo:0, continuo:0, revaluaciones:0,certificados:0, conteo:0, anulado:0
			})
			this.resultados.forEach(cita=>{
				if(cita.professional_id !== this.conteoR2[this.conteoR2.length-1].idProfesional ){
					this.conteoR2.push({idProfesional: cita.professional_id, conteo:1, nombre: cita.professional.name, profesion: cita.professional.profession, nuevo:0, continuo:0, revaluaciones:0,certificados:0, anulado:0 })
				}else
					this.conteoR2[this.conteoR2.length-1].conteo++

				switch (cita.patient_condition) {
					case 1: this.conteoR2[this.conteoR2.length-1].nuevo++; break;
					case 2: this.conteoR2[this.conteoR2.length-1].continuo++; break;
					default: break;
				}
				
				//if(cita.status == '4' ) this.conteoR2[this.conteoR2.length-1].revaluaciones++
				if(cita.status == '3' ) this.conteoR2[this.conteoR2.length-1].anulado++
				
				if(cita.precio.id == 17 || cita.precio.id==45 || cita.precio.id==52) //|| cita.stauts==4
					this.conteoR2[this.conteoR2.length-1].revaluaciones++
				if(cita.precio.idClasificacion == 3)
					this.conteoR2[this.conteoR2.length-1].certificados++
			})
			this.auxiliares  = this.resultados.reduce((acumulador, cita) => {
				const profession = cita.professional?.profession; // Usa optional chaining por seguridad
				
				if (profession) 
					acumulador[profession] = (acumulador[profession] || 0) + 1;
				
				return acumulador;
			}, {});

		},
		contarMontosRegistrados(){
			this.conteoR2=[];
			this.conteoR2.push({idProfesional:this.resultados[0].professional_id, nombre: this.resultados[0].professional.name, profesion: this.resultados[0].professional.profession,
				nuevo:0, continuo:0, revaluaciones:0,certificados:0, conteo:0, ganancia: 0
			})
			this.resultados.forEach(cita=>{
				if(cita.professional_id !== this.conteoR2[this.conteoR2.length-1].idProfesional ){
					this.conteoR2.push({idProfesional: cita.professional_id, conteo:1, nombre: cita.professional.name, profesion: cita.professional.profession, nuevo:0, continuo:0, revaluaciones:0,certificados:0, ganancia: parseFloat(cita.payment.price)})
				}else{
					this.conteoR2[this.conteoR2.length-1].conteo++
					this.conteoR2[this.conteoR2.length-1].ganancia+= parseFloat(cita.payment.price)
				}
			})
		},
		contarReprogramaciones(){
			this.conteoR2=[];
			this.conteoR2.push({idProfesional:this.resultados[0].appointment.professional_id, nombre: this.resultados[0].appointment.professional.name, profesion: this.resultados[0].appointment.professional.profession,
				reprogramaciones:0
			})
			this.resultados.forEach(cita=>{
				if(cita.appointment.professional_id !== this.conteoR2[this.conteoR2.length-1].idProfesional ){
					this.conteoR2.push({idProfesional: cita.appointment.professional_id, conteo:1, nombre: cita.appointment.professional.name, profesion: cita.appointment.professional.profession, reprogramaciones:1 })
				}else
					this.conteoR2[this.conteoR2.length-1].reprogramaciones++
			})
		},
		contarAltas(){
			this.conteoR2=[];
			if(this.resultados[0].user.professional)
				this.conteoR2.push({idProfesional:this.resultados[0].user.professional.id, nombre: this.resultados[0].user.nombre, profesion: this.resultados[0].user.professional.profession, 				altas:0 })
			else
				this.conteoR2.push({idProfesional:-1, nombre: this.resultados[0].user.nombre, profesion: 'Ninguna', altas:0})
			this.resultados.forEach(cita=>{
				if(cita.user.professional)
					if(cita.user.professional.id !== this.conteoR2[this.conteoR2.length-1].idProfesional )
						this.conteoR2.push({idProfesional: cita.user.professional.id, conteo:1, nombre: cita.user.professional.nombre, profesion: cita.user.professional.profession, altas:1 })
					else
						this.conteoR2[this.conteoR2.length-1].altas++
				else
					this.conteoR2[this.conteoR2.length-1].altas++
			})
		},
		contarRecetas(){
			this.conteoR2=[];
			this.conteoR2.push({idProfesional:this.resultados[0].professional.id, nombre: this.resultados[0].professional.name, profesion: this.resultados[0].professional.profession,
				recetas:0
			})
			this.resultados.forEach(cita=>{
				if(cita.professional.id !== this.conteoR2[this.conteoR2.length-1].idProfesional )
					this.conteoR2.push({idProfesional: cita.professional.id, conteo:1, nombre: cita.professional.name, profesion: cita.professional.profession, recetas:1 })
				else
					this.conteoR2[this.conteoR2.length-1].recetas++
			})
		},
		configurarVista(){
			this.resultados=[];
			this.ocultarFechas=false;
			this.filtroAnual = this.idReporte==12 ? true : false;
			switch(this.idReporte){
				case 0:
					this.ocultarFechas=true;
			}
		},
		fechaFrom(fecha){
			moment.locale('es')
			return moment(fecha, 'YYYY-MM-DD').fromNow();
		},
		fechaLatam(fecha){ return moment(fecha).format('DD/MM/YYYY'); },
		horaLatam(horita){ return moment(horita, 'HH:mm:ss').format('hh:mm a') },
		
	},
	computed:{
		sumaMontosRegistrados(){
			let suma = 0;
			this.conteoR2.forEach(conteo => {
				suma += parseFloat(conteo.ganancia)
			});
			return suma.toFixed(2);
		},
		contarRecomendados(){
			let contador =0
			
			this.resultados.recomendados.forEach(item=>{
				contador += parseInt(item.contador)
			})
			return contador;
		},
		sumaRecaudadoMedios(){
			let suma = 0;
			this.resultados.pagos.forEach(item=>{
				suma+=parseFloat(item.suma)
			})
			return suma.toFixed(2);
		},
		sumaTipoComprobante(){
			return (llave)=>{
				let suma = 0;
				this.resultados[llave].forEach(item=>{
					suma += parseFloat(item.price)
				})
				return suma.toFixed(2);
			}
		},
		sumaMedios(){
			return (llave)=>{
				let suma = 0;
				this.resultados[llave].forEach(item=>{
					suma += parseFloat(item.price)
				})
				return suma.toFixed(2);
			}
		},
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