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
					<div class="col-12 col-md-2" v-show="!ocultarSede">
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
								<th>Atenciones</th>
								<th>Atendidos</th>
								<th>Porcentaje</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>Pacientes Nuevos de Psiquiatría</td>
								<td>{{conteo.psiquiatria.nuevo}}</td>
								<td></td>
								<td>{{parseFloat(conteo.psiquiatria.nuevo / conteo.total*100).toFixed(2)}}%</td>
							</tr>
							<tr>
								<td>2</td>
								<td>Pacientes Continuos de Psiquiatría</td>
								<td>{{conteo.psiquiatria.continuo}}</td>
								<td>{{conteo.psiquiatria.sin}}</td>
								<td>{{parseFloat(conteo.psiquiatria.continuo / conteo.total*100).toFixed(2)}}%</td>
							</tr>
							<tr>
								<td>3</td>
								<td>Pacientes Nuevos de Psicología</td>
								<td>{{conteo.psicologia.nuevo}}</td>
								<td></td>
								<td>{{parseFloat(conteo.psicologia.nuevo / conteo.total*100).toFixed(2)}}%</td>
							</tr>
							<tr>
								<td>4</td>
								<td>Pacientes Continuos de Psicología</td>
								<td>{{conteo.psicologia.continuo}}</td>
								<td>{{conteo.psicologia.sin}}</td>
								<td>{{parseFloat(conteo.psicologia.continuo / conteo.total*100).toFixed(2)}}%</td>
							</tr>
							<tr>
								<td colspan="2">Total</td>
								<td>{{conteo.total}}</td>
								<td></td>
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
								<th>Atenciones</th>
								<th>Atendidos</th>
								<th>Porcentaje</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(doctor, indice) in conteoR2">
								<td>{{indice+1}}</td>
								<td>{{doctor.nombre}}</td>
								<td>{{doctor.conteo}}</td>
								<td>{{conteoR3[indice].conteo}}</td>
								<td>{{(doctor.conteo/resultados.total*100).toFixed(1)}}%</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td colspan=2 class="">Total</td>
								<td>{{resultados.total}}</td>
								<td></td>
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
							<tr>
								<td>3</td>
								<td>Atendidos únicos</td>
								<td>{{resultados.continuosSin}}</td>
							</tr>

						</tbody>
					</table>
					<p><strong>Conteo por especialidad</strong></p>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Especialidad</th>
								<th>Atenciones</th>
								<th>Atendidos</th>
								<th>Porcentaje</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(especialidad, clave, index) in resultados.especialidades">
								<td>{{index+1}}</td>
								<td>{{clave}}</td>
								<td>{{especialidad.length}}</td>
								<td>{{resultados.sinRepetir[clave].length}}</td>
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
								<th>Atenciones</th>
								<th>Atendidos</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(aux, clave, index ) in auxiliares">
								<td>{{index+1}}</td>
								<td>{{clave}}</td>
								<td>{{aux}}</td>
								<td>{{resultados.profSin[clave].length}}</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td></td>
								<td>Total:</td>
								<td>{{resultados.length}}</td>
							</tr>
						</tfoot>
					</table>
					<p><strong>Reporte detallado</strong></p>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Profesión</th>
								<th>Profesional</th>
								<!-- <th>N° Pacientes atendidos</th> -->
								<th>N° Pacientes nuevos</th>
								<th>N° Pacientes continuos</th>
								<!-- <th>N° Pacientes Anulados</th> -->
								<th>N° Reevaluaciones</th>
								<th class="d-none">N° Certificados</th>
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
								<td class="d-none">{{doctor.certificados}}</td>
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
								<td>{{sumatorias()}}</td>
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
						<tbody v-for="([key, edad], indice) in Object.entries(resultados.sexos)" :key="key">
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
								<td>{{resultados.psicologia?.total ?? 0}}</td>
							</tr>
							<tr>
								<td>2</td>
								<td>Psiquiatría</td>
								<td>{{resultados.psiquiatria?.total ?? 0}}</td>
							</tr>
							<tr>
								<td>3</td>
								<td>Certificados</td>
								<td>{{resultados.certificados?.total ?? 0}}</td>
							</tr>
							<tr v-for="(pago, clave, indice) in resultados.extras">
								<td>{{indice+4}}</td>
								<td>{{clave.replace('Pago de membresía', 'Pago de paquetes')}}</td>
								<td>{{pago.total.toFixed(2)}}</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td></td>
								<td>Recaudado:</td>
								<td>{{sumatorias()}}</td>
							</tr>
						</tfoot>
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
						<tfoot>
							<tr>
								<td></td>
								<td></td>
								<td>Recaudado:</td>
								<td>{{sumatorias()}}</td>
								<td></td>
							</tr>
						</tfoot>
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
							<tr v-for="(medicamento, indice) in resultados">
								<td>{{indice+1}}</td>
								<td>{{medicamento.nombre}} {{medicamento.presentacion}}</td>
								<td>{{medicamento.cantidad}} </td>
							</tr>
						</tbody>
					</table>
				</div>
				<div v-if="idReporte==15">
					<template v-for="(doctor, clave) in resultados">
						<table class="table table-hover mb-4">
							<thead>
								<tr >
									<th colspan="8">{{clave}}</th>
								</tr>
								<tr>
									<th>N°</th>
									<th>Paciente</th>
									<th>Celular</th>
									<th>Servicio</th>
									<th>Horario</th>
									<th>Monto</th>
									<th>Pago</th>
									<th>Estado</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(cita, index) in doctor">
									<td>{{index+1}}</td>
									<td>{{cita.patient.name}} {{cita.patient.nombres}}</td>
									<td>{{cita.patient.phone}}</td>
									<td>{{cita.precio.descripcion}}</td>
									<td>{{horaLatam(cita.schedule.check_time)}} - {{horaLatam(cita.schedule.departure_date)}}</td>
									<td>{{cita.pagos_extras[0]?.price ?? ''}}</td>
									<td>
										<span v-if="cita.status=='1'">Sin confirmar</span>
										<span v-if="cita.status=='2'">Confirmado</span>
									</td>
									<td>
										<span v-if="cita.payment.pay_status=='1' && cita.payment.adelanto>0">Con adelanto</span>
										<span v-if="cita.payment.pay_status=='1'">Pendiente</span>
										<span v-if="cita.payment.pay_status=='2'">Pagado</span>
									</td>
								</tr>
							</tbody>
						</table>
					</template>
					
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
			idReporte:0, resultados:[], ocultarFechas:false, ocultarSede:true, conteo:{total:0, psiquiatria:{nuevo:0, continuo:0}, psicologia: {nuevo:0, continuo:0}}, auxiliares:[],
			fecha:{ año: moment().format('YYYY'), mes: moment().format('M'), inicio:moment().format('YYYY-MM-DD'), fin:moment().format('YYYY-MM-DD'), idSede:1 }, conteoR2:[], conteoR3:[],
			reportes:[
				{id: 1, nombrado: 'Pacientes anulados'},
				{id: 2, nombrado: 'Cartera de clientes'},
				{id: 3, nombrado: 'Producción mes'},
				{id: 4, nombrado: 'Montos registrados'},
				{id: 5, nombrado: 'Reprogramaciones por profesional'},
				{id: 6, nombrado: 'Pacientes dados de alta'},
				{id: 7, nombrado: 'Recetas por profesional'},
				{id: 8, nombrado: 'Diagnósticos más frecuentes'},
				{id: 9, nombrado: 'Reporte demográfico por clientes'},
				{id: 10, nombrado: 'Comprobantes emitidos'},
				{id: 11, nombrado: 'Ingresos'},
				{id: 12, nombrado: 'Medios de pago'},
				{id: 13, nombrado: 'Estado de pacientes'},
				{id: 14, nombrado: 'Medicamentos más recetados'},
				{id: 15, nombrado: 'Compendio de citas'},
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
			.then(serv=> { //console.log(serv.data);
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
			this.conteo={total:0, psiquiatria:{nuevo:0, continuo:0, sin:0}, psicologia: {nuevo:0, continuo:0, sin:0}}
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
			this.resultados.sinRepetir.forEach(continuo => {
				if( continuo.precio)
					if( continuo.precio.idClasificacion==1) //es Psiquiatria
						{this.conteo.psiquiatria.sin+=1; }
					else if( continuo.precio.idClasificacion==2) //es Psicologia
						{this.conteo.psicologia.sin+=1; }
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
			
			this.conteoR3.push({idProfesional:this.resultados.carteraSinRepetir[0].professional_id, conteo:0, nombre: this.resultados.carteraSinRepetir[0].professional.name})
			this.resultados.carteraSinRepetir.forEach(cita=>{
				if(cita.professional_id !== this.conteoR3[this.conteoR3.length-1].idProfesional ){
					this.conteoR3.push({idProfesional: cita.professional_id, conteo:1, nombre: cita.professional.name})
				}else
					this.conteoR3[this.conteoR3.length-1].conteo++
			})
		},
		contarProduccion(){
			this.conteoR2=[];
			this.auxiliares=[];
			this.conteoR2.push({idProfesional:this.resultados.cartera[0].professional_id, nombre: this.resultados.cartera[0].professional.name, profesion: this.resultados.cartera[0].professional.profession,
				nuevo:0, continuo:0, revaluaciones:0,certificados:0, conteo:0, anulado:0
			})
			this.resultados.cartera.forEach(cita=>{
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
				
				//if( [17,45,52].inclues(parsei))
				if(cita.precio.id == 17 || cita.precio.id==45 || cita.precio.id==52) //|| cita.stauts==4
					this.conteoR2[this.conteoR2.length-1].revaluaciones++
				if(cita.precio.idClasificacion == 3)
					this.conteoR2[this.conteoR2.length-1].certificados++
			})
			
			this.auxiliares  = this.resultados.cartera.reduce((acumulador, cita) => {
				const profession = cita.professional?.profession; // Usa optional chaining por seguridad
				
				if (profession) 
					acumulador[profession] = (acumulador[profession] || 0) + 1;
				
				return acumulador;
			}, {});

		},
		contarMontosRegistrados(){
			this.conteoR2=[];
			this.conteoR2.push({idProfesional:this.resultados.professional_id, nombre: this.resultados.professional.name, profesion: this.resultados.professional.profession,
				nuevo:0, continuo:0, revaluaciones:0,certificados:0, conteo:0, ganancia: 0
			})
			this.resultados.cartera.forEach(cita=>{
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
				this.conteoR2.push({idProfesional:this.resultados[0].user.professional.id,
				nombre: this.resultados[0].user.professional.name,
				profesion: this.resultados[0].user.professional.profession, altas:0 })
			else
				this.conteoR2.push({idProfesional:-1, nombre: this.resultados[0].user.nombre, profesion: 'Ninguna', altas:0})

			this.resultados.forEach(cita=>{
				if(cita.user.professional)
					if(cita.user.professional.id !== this.conteoR2[this.conteoR2.length-1].idProfesional )
						this.conteoR2.push({idProfesional: cita.user.professional.id, conteo:1,
						nombre: cita.user.professional.name, profesion: cita.user.professional.profession, altas:1 })
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
			this.ocultarSede = true;
			this.filtroAnual = this.idReporte==12 ? true : false;
			if( [4,11,12].includes(this.idReporte) ) this.ocultarSede = false;
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
		sumatorias(){
			let acumulador =0
			switch (this.idReporte) {
				case 4:
					let profes = this.resultados.profesionales
					for(const clave in profes)
						acumulador += profes[clave].suma
				break;
				case 11:
					let psiq = parseFloat(this.resultados.psiquiatria?.total ?? 0)
					let psic = parseFloat(this.resultados.psicologia?.total ?? 0)
					
					let extras = this.resultados.extras
					let otros =0
					for(const clave in extras){
						otros += extras[clave].total
					}
					acumulador = psiq+psic+otros
				break;
				case 12:
					Object.keys(this.resultados).forEach(clave =>{
						acumulador+= parseFloat(this.sumaMedios(clave))
					})
				break;
			
				default:
					break;
			}
			return parseFloat(acumulador).toFixed(2)
		}
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