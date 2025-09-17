<template>
	<div>
		<h1>Reportes avanzados</h1>
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
					<div class="col-12 col-md-3" v-show="verDias">
						<label for="">Día</label>
						<input type="date" class="form-control" v-model="dia"">
					</div>
					<div class="col-12 col-md-3 d-flex align-items-end">
						<button class="btn btn-outline-primary" @click="pedirReporte()"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
					</div>
				</div>
			</div>
		</div>
		<div class="card mt-2">
			<div class="card-body">
				<table class="table table-sm table-hover" v-if="idReporte==0">
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
				<div v-if="idReporte==1">
					<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
						<input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked @click="filtro=-1">
						<label class="btn btn-outline-primary" for="btnradio1">Todos</label>

						<input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off" @click="filtro=2">
						<label class="btn btn-outline-primary" for="btnradio2">Psiquiatría</label>
						<input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off" @click="filtro=1">
						<label class="btn btn-outline-primary" for="btnradio3">Psicología</label>
						<input type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off" @click="filtro=4">
						<label class="btn btn-outline-primary" for="btnradio4">Kurame</label>
						<input type="radio" class="btn-check" name="btnradio" id="btnradio5" autocomplete="off" @click="filtro=5">
						<label class="btn btn-outline-primary" for="btnradio5">Membresía</label>
					</div>
					<table class="table table-sm table-hover">
						<thead>
							<th>N°</th>
							<th>Paciente</th>
							<th>Grupo</th>
							<th>Servicio</th>
							<th>Última cita</th>
							<th>Diferencia</th>
							<th>@</th>
						</thead>
						<tbody>
							<tr v-for="(resultado, index) in resultados" v-show="filtro==-1 || filtro ==resultado.idClasificacion ">
								<td>{{ index+1 }}</td>
								<td class="text-capitalize">{{ resultado.name.toLowerCase() }}</td>
								<td>{{ equipo[resultado.idClasificacion-1] }}</td>
								<td>{{ resultado.nomServicio }}</td>
								<td>{{ fechaLatam(resultado.date) }}</td>
								<td>{{ fechaFrom(resultado.date) }}</td>
								<td><button class="btn btn-sm btn-outline-primary" @click="pasarSeguimiento(index)" data-bs-toggle="modal" data-bs-target="#modalSeguimiento"><span><i class="fa-solid fa-network-wired"></i> Pasar a seguimiento</span></button></td>
							</tr>
							<tr v-if="resultados.length==0">
							<td colspan="4">No hay registros</td></tr>
						</tbody>
					</table>
				</div>
				<table class="table table-sm table-hover" v-if="idReporte==2">
					<thead>
						<tr>
							<th>N°</th>
							<th>Paciente</th>
							<th>Hobbies</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(resultado, index) in resultados">
							<td>{{ index+1 }}</td>
							<td>{{ resultado.name }}</td>
							<td>
								<span class="text-capitalize" v-for="actividad in JSON.parse(resultado.hobbies) ">
									{{ hobbies[actividad] }},
								</span>
							</td>
						</tr>
					</tbody>
				</table>
				<table class="table table-sm table-hover" v-if="idReporte==3 || idReporte==4">
					<thead>
						<tr>
							<th>N°</th>
							<th>Paciente</th>
							<th>Club</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(resultado, index) in resultados">
							<td>{{ index+1 }}</td>
							<td>{{ resultado.name }}</td>
							<td> 
								<span v-if="resultado.club==1">Sí pertenece</span>
								<span v-if="resultado.club==2">No desea pertenecer</span>
							</td>
						</tr>
					</tbody>
				</table>
				<table class="table table-sm table-hover" v-if="idReporte==5">
					<thead>
						<tr>
							<th>N°</th>
							<th>Paciente</th>
							<th>Semáforo</th>
							<th>Observaciones</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(resultado, index) in resultados">
							<td>{{ index+1 }}</td>
							<td class="text-capitalize">{{ resultado.name.toLowerCase() }}</td>
							<td class="text-capitalize"> 
								{{ estados[resultado.codigo].valor }}
							</td>
							<td class="text-capitalize">{{ resultado.observaciones }}</td>
						</tr>
					</tbody>
				</table>
				<table class="table table-sm table-hover" v-if="idReporte==6" >
					<thead>
						<tr>
							<th>N°</th>
							<th>Profesional</th>
							<th>Total de citas confirmadas</th>
							<th>Total de citas pagadas</th>
							<th>Acumulado</th>
						</tr>
					</thead>
					<tbody >
						<tr v-for="(doctor, index) in resultados.doctores">
							<td>{{index+1}}</td>
							<td>{{ doctor.name }}</td>
							<td> <span v-if="suma[doctor.id]">{{ suma[doctor.id].confirmado  }}</span> <span v-else>0</span></td>
							<td> <span v-if="suma[doctor.id]">{{ suma[doctor.id].pagado  }}</span> <span v-else>0</span></td>
							<td>S/ <span v-if="suma[doctor.id]">{{ parseFloat(suma[doctor.id].monto).toFixed(2)  }}</span> <span v-else>0.00</span></td>
						</tr>
					</tbody>
				</table>
				<table class="table table-sm table-hover" v-if="idReporte==6" v-for="doctor in resultados.doctores">
					<thead>
						<tr>
							<th colspan="4">{{ doctor.name }}</th>
						</tr>
						<tr>
							<th>N°</th>
							<th>Fecha</th>
							<th>Paciente</th>
							<th>Servicio</th>
							<th>Estado</th>
							<th>Monto</th>
							<th>Pagado</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(cita, indice) in resultados.citas" v-if="cita.professional_id == doctor.id">
							<td>{{ indice+1 }}</td> <!-- {{ cita.id }} -->
							<td>{{ fechaLatam(cita.date) }} <span v-if="cita.schedule">{{ horaLatam(cita.schedule.check_time) }}</span></td>
							<td class="text-capitalize">{{ cita.patient.name }}</td>
							<td>{{ cita.precio.descripcion }}</td>
							<td>
								<span v-if="cita.status==1">Sin confirmar</span>
								<span v-if="cita.status==2">Confirmado</span>
								<span v-if="cita.status==3">Anulado</span>
							</td>
							<td>{{ cita.payment.price }}</td>
							<td>
								<span v-if="cita.payment.pay_status==1">Sin pagar</span>
								<span v-if="cita.payment.pay_status==2">Pagado</span>
								<span v-if="cita.payment.pay_status==3">Anulado</span>
							</td>
						</tr>
						
					</tbody>
				</table>
				<div v-if="idReporte==7">
					<table class="table table-sm table-hover" >
						<thead>
							<tr>
								<th>N°</th>
								<th>Servicio</th>
								<th>Total de citas confirmadas</th>
								<th>Total de citas pagadas</th>
								<th>Acumulado</th>
							</tr>
						</thead>
						<tbody >
							<tr v-for="(servicio, index) in resultados.servicios">
								<td>{{index+1}}</td>
								<td> 
									<span v-if="servicio.idClasificacion==1">Psiquiatría</span>
									<span v-if="servicio.idClasificacion==2">Psicología</span>
									<span v-if="servicio.idClasificacion==3">Certificado</span>
									<span v-if="servicio.idClasificacion==4">Membresía</span>
									<span>{{ servicio.descripcion }}</span>
								</td>
								<td> <span v-if="suma[servicio.id]">{{ suma[servicio.id].confirmado  }}</span> <span v-else>0</span></td>
								<td> <span v-if="suma[servicio.id]">{{ suma[servicio.id].pagado  }}</span> <span v-else>0</span></td>
								<td>S/ <span v-if="suma[servicio.id]">{{ parseFloat(suma[servicio.id].monto).toFixed(2) }}</span> <span v-else>0.00</span></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div v-if="idReporte==7" >
					<p>Ojo: Recuerde que la nueva lista de servicios se aplicó a partir de Julio 2023</p>
					<table class="table table-sm table-hover" v-for="servicio in resultados.servicios" >
						<thead>
							<tr>
								<th colspan="4">{{ servicio.descripcion }}</th>
							</tr>
							<tr>
								<th>N°</th>
								<th>Paciente</th>
								<th>Servicio</th>
								<th>Profesional</th>
								<th>Fecha</th>
								<th>Estado</th>
								<th>Monto</th>
								<th>Pagado</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(cita, indice) in resultados.citas" v-if="cita.type == servicio.id">
								<td>{{ indice+1 }}</td> <!-- {{ cita.id }} -->
								<td class="text-capitalize">{{ cita.patient.name }}</td>
								<td>{{ queServicio(cita.type) }}</td>
								<td> {{ cita.professional.nombre }}</td>
								<td>{{ fechaLatam(cita.date) }} {{ horaLatam(cita.schedule.check_time) }}</td>
								<td>
									<span v-if="cita.status==1">Sin confirmar</span>
									<span v-if="cita.status==2">Confirmado</span>
									<span v-if="cita.status==3">Anulado</span>
								</td>
								<td>{{ cita.payment.price }}</td>
								<td>
									<span v-if="cita.payment.pay_status==1">Sin pagar</span>
									<span v-if="cita.payment.pay_status==2">Pagado</span>
									<span v-if="cita.payment.pay_status==3">Anulado</span>
								</td>
							</tr>
					
						</tbody>
					</table>
				</div>
				<div v-if="idReporte==8 || idReporte==9">
					<p><strong>Total de pacientes:</strong> {{ resultados.conteo }} </p>
					<table class="table table-sm table-hover" >
						<thead>
							<tr>
								<th>N°</th>
								<th>Fecha</th>
								<th>Paciente</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(cita, indice, item) in resultados.citas">
								<td>{{ item+1 }}</td> <!-- {{ cita.id }} -->
								<td>{{ cita[0].date }}</td>
								<td class="text-capitalize">{{ cita[0].patient.name }}</td>
							</tr>
						</tbody>
					</table>
				</div>
				<table class="table table-sm table-hover" v-if="idReporte==10" >
					<thead>
						<tr>
							<th>N°</th>
							<th>Tipo de medio de pago</th>
							<th>Acumulado</th>
						</tr>
					</thead>
					<tbody >
						<tr v-for="(medio, index) in resultados.monedas">
							<td>{{index+1}}</td>
							<td>{{ medio.tipo }}</td>
							<td>S/ <span v-if="suma[medio.id]">{{ parseFloat(suma[medio.id].monto).toFixed(2)  }}</span> <span v-else>0.00</span></td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="2" class="text-center fw-bold">Total</td>
							<td>{{ parseFloat(sumaTodoMedio).toFixed(2) }}</td>
						</tr>
					</tfoot>
				</table>
				<div v-if="idReporte==10" >
					<p>Ojo: Recuerde que los pagos fueron aplicados desde Julio 2023</p>
					<table class="table table-sm table-hover" >
						<thead>
							<tr>
								<th>N°</th>
								<th>Paciente</th>
								<th>Fecha</th>
								<th>Servicio</th>
								<th>Monto</th>
								<th>Pagado</th>
								<th>Observaciones</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(pago, indice) in resultados.pagos">
								<td>{{ indice+1 }}</td> <!-- {{ pago.id }} -->
								<td class="text-capitalize">{{ pago.customer }}</td>
								<td>{{ pago.date }}</td>
								<td>{{ queServicio(pago.type) }}</td>
								
								
								<td>{{ pago.price }}</td>
								<td>
									<span v-if="pago.pay_status==1">Sin pagar</span>
									<span v-if="pago.pay_status==2">Pagado</span>
									<span v-if="pago.pay_status==3">Anulado</span>
									<span v-if="!pago.pay_status">Pagado</span>
								</td>
								<td>{{ pago.observation }}</td>
							</tr>
					
						</tbody>
					</table>
				</div>
				<table class="table table-sm table-hover" v-if="idReporte==11" >
					<thead>
						<tr>
							<th>N°</th>
							<th>Tipo</th>
							<th>N° Citas</th>
							<th>Acumulado</th>
						</tr>
					</thead>
					<tbody >
						<tr>
							<td>1</td>
							<td>Psicología</td>
							<td>{{resultados.cuantosPsicologia}}</td>
							<td>S/ {{resultados.montoPsicologia}}</td>
						</tr>
						<tr>
							<td>2</td>
							<td>Psiquiatría</td>
							<td>{{resultados.cuantosPsiquiatria}}</td>
							<td>S/ {{resultados.montoPsiquiatria}}</td>
						</tr>
					</tbody>
				</table>
				<table class="table table-sm table-hover" v-if="idReporte==12" >
					<thead>
						<tr>
							<th>N°</th>
							<th>Estado</th>
							<th>Total de pacientes</th>
						</tr>
					</thead>
					<tbody >
						<tr v-for="(estado, index) in resultados.estados">
							<td>{{index+1}}</td>
							<td>{{ estado.seguimiento }}</td>
							<td> <span v-if="suma[estado.id]">{{ suma[estado.id].contador  }}</span> <span v-else>0</span></td>
						</tr>
					</tbody>
				</table>
				<table class="table table-sm table-hover" v-if="idReporte==13" >
					<thead>
						<tr>
							<th>N°</th>
							<th>Fecha</th>
							<th>Cliente</th>
							<th>Monto</th>
							<th>N° Bol./Fact.</th>
							<th>N° Operación</th>
							<th>Observaciones</th>
						</tr>
					</thead>
					<tbody >
						<tr v-for="(pago, index) in resultados.pagos">
							<td>{{index+1}}</td>
							<td>{{ pago.date }}</td>
							<td>{{ pago.customer }}</td>
							<td>{{ parseFloat(pago.price).toFixed(2) }}</td>
							<td>{{ pago.voucher }}</td>
							<td>{{ pago.voucher_issued }}</td>
							<td>{{ pago.observation }}</td>
						</tr>
					</tbody>
				</table>
				<table class="table table-sm table-hover" v-if="idReporte==14 || idReporte==15">
					<thead>
						<tr>
							<th>N°</th>
							<th>Fecha de cita</th>
							<th>Cliente</th>
							<th>Profesional</th>
							<th>Servicio</th>
							<th>Registrado</th>
						</tr>
					</thead>
					<tbody >
						<tr v-for="(cita, index) in resultados">
							<td>{{index+1}}</td>
							<td>{{ fechaLatam(cita.created_at) }} {{ horaLatam(cita.appointment.schedule.check_time) }}</td>
							<td class="text-capitalize">{{ cita.appointment.patient.name.toLowerCase() }}</td>
							<td>{{ cita.appointment.professional.name }}</td>
							<td>{{ cita.appointment.precio.descripcion }}</td>
							<td>{{ fechaLatam(cita.fecha ? cita.fecha: cita.created_at ) }}</td>
						</tr>
					</tbody>
				</table>
				<table class="table table-sm table-hover" v-if="idReporte==16">
					<thead>
						<tr>
							<th>N°</th>
							<th>Fecha de cita</th>
							<th>Cliente</th>
							<th>Profesional</th>
							<th>Servicio</th>
							<th>Registrado</th>
						</tr>
					</thead>
					<tbody >
						<tr v-for="(cita, index) in resultados">
							<td>{{index+1}}</td>
							<td>{{ fechaLatam(cita.created_at) }} {{ horaLatam(cita.schedule.check_time) }}</td>
							<td class="text-capitalize">{{ cita.patient.name.toLowerCase() }}</td>
							<td>{{ cita.professional.name }}</td>
							<td>{{ cita.precio.descripcion }}</td>
							<td>{{ fechaLatam(cita.date) }}</td>
						</tr>
					</tbody>
				</table>
				<table class="table table-sm table-hover" v-if="idReporte==17">
					<thead>
						<tr>
							<th>N°</th>
							<th>Paciente</th>
							<th>@</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(paciente, index) in resultados">
							<td>{{index+1}}</td>
							<td>{{paciente.name}}</td>
							<td>
								<button class="btn btn-outline-primary" @click="dataPaciente = paciente" data-bs-target="#patientModal" data-bs-toggle="modal"><i class="far fa-edit" ></i> Editar</button>
							</td>
						</tr>
					</tbody>
				</table>
				<div v-if="idReporte==18">
					<p>Pacientes que provienen de Cartera de clientes con algún caso para hacer seguimiento.</p>
					<table class="table table-sm table-hover" >
						<thead>
							<tr>
								<th>N°</th>
								<th>Paciente</th>
								<th>Fecha</th>
								<th>Estado</th>
								<th>Motivo</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(paciente, index) in resultados">
								<td>{{index+1}}</td>
								<td>{{paciente.nombre}}</td>
								<td>{{fechaLatam(paciente.fecha)}}</td>
								<td>{{paciente.icono}}</td>
								<td>{{paciente.motivo}}</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div v-if="idReporte == 19" class="table-responsive">
					<template v-if="resultados.citas">
						<table class="table table-hover">
						<thead>
							<tr>
								<th>Día</th>
								<th>Horario</th>
								<th>Profesional</th>
								<th>Paciente</th>
								<th>Tipo</th>
								<th>Moneda</th>
								<th>Pago</th>
								<th>Voucher</th>
								<th>Observación</th>
								<th>Tipo Pago</th>
								<th>Fecha Pago Anterior</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="table-dark" colspan="11">Orientado a entradas con citas</td>
							</tr>
							<tr>
								<td colspan="11" v-if="resultados.citas.length==0">No se encontraron registros</td>
							</tr>
							<tr v-for="cita in resultados.citas">
								<td>{{ fechaLatam(cita.DIA) }}</td>
								<td>{{ cita.HORARIO }}</td>
								<td>{{ cita.PROFESIONAL }}</td>
								<td>{{ cita.PACIENTE }}</td>
								<td>{{ cita.TIPO_PACIENTE }}</td>
								<td>{{ cita.MONEDA }}</td>
								<td>{{ cita.PAGO }}</td>
								<td>{{ cita.VOUCHER }}</td>
								<td>{{ cita.OBSERVACION }}</td>
								<td>{{ cita.TIPO_PAGO }}</td>
								<td>{{ cita.FECHA_PAGO_ANTERIOR }}</td>
							</tr>

							<tr>
								<td class="table-dark" colspan="11">Orientado a entradas sin citas</td>
							</tr>
							<tr>
								<td colspan="11" v-if="resultados.sinCitas.length==0">No se encontraron registros</td>
							</tr>
							<tr v-for="sinCita in resultados.sinCitas">
								<td>{{ fechaLatam(sinCita.DIA) }}</td>
								<td>{{ sinCita.HORARIO }}</td>
								<td>{{ sinCita.PROFESIONAL }}</td>
								<td>{{ sinCita.PACIENTE }}</td>
								<td>{{ sinCita.TIPO_PACIENTE }}</td>
								<td>{{ sinCita.MONEDA }}</td>
								<td>{{ sinCita.PAGO }}</td>
								<td>{{ sinCita.VOUCHER }}</td>
								<td>{{ sinCita.OBSERVACION }}</td>
								<td>{{ sinCita.TIPO_PAGO }}</td>
								<td>{{ sinCita.FECHA_PAGO_ANTERIOR }}</td>
							</tr>

							<tr>
								<td class="table-dark" colspan="11">Orientado a entradas sin citas</td>
							</tr>
							<tr>
								<td colspan="11" v-if="resultados.entradas.length==0">No se encontraron registros</td>
							</tr>
							<tr v-for="entrada in resultados.entradas">
								<td>{{ fechaLatam(entrada.DIA) }}</td>
								<td>{{ entrada.HORARIO }}</td>
								<td>{{ entrada.PROFESIONAL }}</td>
								<td>{{ entrada.PACIENTE }}</td>
								<td>{{ entrada.TIPO_PACIENTE }}</td>
								<td>{{ entrada.MONEDA }}</td>
								<td>{{ entrada.PAGO }}</td>
								<td>{{ entrada.VOUCHER }}</td>
								<td>{{ entrada.OBSERVACION }}</td>
								<td>{{ entrada.TIPO_PAGO }}</td>
								<td>{{ entrada.FECHA_PAGO_ANTERIOR }}</td>
							</tr>

							<tr>
								<td class="table-dark" colspan="11">Adelantos o pagos de otros días</td>
							</tr>
							<tr>
								<td colspan="11" v-if="resultados.adelantos.length==0">No se encontraron registros</td>
							</tr>
							<tr v-for="adelanto in resultados.adelantos">
								<td>{{ fechaLatam(adelanto.DIA) }}</td>
								<td>{{ adelanto.HORARIO }}</td>
								<td>{{ adelanto.PROFESIONAL }}</td>
								<td>{{ adelanto.PACIENTE }}</td>
								<td>{{ adelanto.TIPO_PACIENTE }}</td>
								<td>{{ adelanto.MONEDA }}</td>
								<td>{{ adelanto.PAGO }}</td>
								<td>{{ adelanto.VOUCHER }}</td>
								<td>{{ adelanto.OBSERVACION }}</td>
								<td>{{ adelanto.TIPO_PAGO }}</td>
								<td>{{ adelanto.FECHA_PAGO_ANTERIOR }}</td>
							</tr>

							<tr>
								<td class="table-dark" colspan="11">Pagos del día subidos al limbo.</td>
							</tr>
							<tr>
								<td colspan="11" v-if="resultados.limbos.length==0">No se encontraron registros</td>
							</tr>
							<tr v-for="limbo in resultados.limbos">
								<td>{{ fechaLatam(limbo.DIA) }}</td>
								<td>{{ limbo.HORARIO }}</td>
								<td>{{ limbo.PROFESIONAL }}</td>
								<td>{{ limbo.PACIENTE }}</td>
								<td>{{ limbo.TIPO_PACIENTE }}</td>
								<td>{{ limbo.MONEDA }}</td>
								<td>{{ limbo.PAGO }}</td>
								<td>{{ limbo.VOUCHER }}</td>
								<td>{{ limbo.OBSERVACION }}</td>
								<td>{{ limbo.TIPO_PAGO }}</td>
								<td>{{ limbo.FECHA_PAGO_ANTERIOR }}</td>
							</tr>

							</tbody>
						</table>
					</template>

				</div>
				
				
			</div>
		</div>
		<ModalSeguimiento :profesionales="profesionales" :idProfesional="7" :elegido="elegido"></ModalSeguimiento>
		<ModalEditPatients :dataPatient="dataPaciente"></ModalEditPatients>
	</div>
	
</template>
<script>
import ModalSeguimiento from "../../../recepcionista/components/adicionales/ModalSeguimiento.vue"
import ModalEditPatients from '../../..//recepcionista/components/pacientes/ModalEditPatient.vue'
import moment from 'moment';

	export default{
		name: 'reportesAvanzados',
		components:{ ModalSeguimiento, ModalEditPatients },
		data(){ return{
			años: [], meses:['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'],
			idReporte:0, resultados:[], ocultarFechas:false, dataPaciente:[],
			fecha:{ año: moment().format('YYYY'), mes: moment().format('M') },
			dia: moment().format('YYYY-MM-DD'), verDias: false,
			reportes:[
				{id: 1, nombrado: 'Pacientes ausentes mayor a 7 días'},
				{id: 0, nombrado: 'Recetas sueltas'},
				{id: 2, nombrado: 'Hobbies de los pacientes'},
				{id: 3, nombrado: 'Pacientes integrantes al club'},
				{id: 4, nombrado: 'Pacientes no desean el club'},
				{id: 5, nombrado: 'Pacientes semáforo'},
				{id: 6, nombrado: 'Recaudaciones por profesional - N° Atenciones'},
				{id: 7, nombrado: 'Recaudaciones por servicio - N° Atenciones'},
				{id: 8, nombrado: 'Pacientes nuevos'},
				{id: 9, nombrado: 'Pacientes continuantes'},
				{id: 10, nombrado: 'Recaudaciones por medio de pago'},
				{id: 11, nombrado: 'Atenciones (Psicológicas y Psiquiátricas) recaudadas'},
				{id: 12, nombrado: 'Seguimiento de Pacientes'},
				{id: 13, nombrado: 'Comprobantes emitidos'},
				{id: 14, nombrado: 'Citas reprogramadas'},
				{id: 15, nombrado: 'Citas anuladas con faltas'},
				{id: 16, nombrado: 'Citas confirmadas'},
				{id: 17, nombrado: 'Pacientes con datos incompletos'},
				{id: 18, nombrado: 'Pacientes desde cartera de clientes'},
				{id: 19, nombrado: 'Reporte financiero diario'},
			],
			hobbies:['pintura','dibujo', 'fotografía', 'tejido', 'costura', 'joyería', 'senderismo', 'acampar', 'jardinería', 'pesca', 'ciclismo', 'deportes', 'fútbol', 'basket', 'tenis', 'ajedrez', 'juegos de mesa', 'billar', 'música', 'tocar un instrumento', 'canto', 'composición musical', 'producción musical', 'gastronomía', 'cocina', 'recetas', 'horneado', 'postres', 'manualidades', 'origami', 'modelodo en arcilla', 'creación', 'natación', 'surf', 'kayac', 'buceo', 'esquí', 'tecnología', 'programación', 'robótica', 'computación', 'edición de videos', 'diseño gráfico', 'coleccionismo', 'monedas', 'vinilos', 'baile', 'danzas', 'escritura', 'periodismo', 'poesía', 'libros', 'lectura', 'cuentos', 'idiomas', 'viajes', 'exploración de lugares', 'fitnes', 'gym', 'yoga', 'pilates', 'entrenamiento', 'meditación', 'voluntariado', 'mascotas', 'animalista', 'astronomía', 'jardinería', 'plantas', 'huertos', 'paisajes', 'cine', 'series', 'novelas'], 
			estados:[
				{id: 1, valor: 'Neutro', detalle: 'No tiene ningún registro de actitud'},
				{id: 2, valor: 'cumplidor', detalle: 'es un paciente exclente'},
				{id: 3, valor: 'promotor', detalle: 'promueve actividades entre sus compañeros o la empresa'},
				{id: 4, valor: 'wow', detalle: 'es involucrado en actividades, participativo'},
				{id: 5, valor: 'reprogramador', detalle: 'suele aplazarcitas y actividades'},
				{id: 6, valor: 'exigente', detalle: 'un paciente/cliente que siempre pide un trato especial'},
				{id: 7, valor: 'deudor', detalle: 'paciente con deudas'},
				{id: 8, valor: 'insatisfecho', detalle: 'suele quejarse de los servicios'},
				{id: 9, valor: 'peligroso', detalle: 'paciente con amenazas o actos de violencia.'},
			], suma:{}, sumaTodoMedio:0, profesionales:[{id:7, name:'Recepción'}], elegido:{name:'', phone:'', motivo:''}, filtroAnual:false, filtro:-1,
			equipo:['Psicología', 'Psiquiatría', 'Certificado', 'Kurame', 'Membresía']
		}},
		methods:{
			cargarDatos(){
				for(let i=2022; i<=moment().format('YYYY'); i++){
					this.años.push(i);
				}
				this.años.reverse();
			},
			pedirReporte(){
				if( this.idReporte == 19 ){
					this.axios.post('/api/reportsJimmy',{ fecha: this.dia })
					.then(serv =>{
						this.resultados=serv.data;
					})
					return false
				}

				this.axios.post('/api/pedirReporte/'+this.idReporte,{
					año: this.fecha.año, mes: this.fecha.mes, fecha: this.dia
				})
				.then(serv=> { console.log(serv.data);
					this.resultados=serv.data
					if(this.idReporte==6) this.sumaProfesionales();
					if(this.idReporte==7) this.sumaServicios();
					if(this.idReporte==10) this.sumaMedios();
					if(this.idReporte==12) this.sumaEstados();
				})
			},
			pasarSeguimiento(index){
				this.elegido.name = this.resultados[index].name;
				this.elegido.phone = this.resultados[index].phone;
				this.elegido.motivo = '';
			},
			configurarVista(){
				this.resultados=[];
				//incluidos a ocultar: 1,2,3,4,
				this.ocultarFechas=true;
				this.verDias=false;
				this.filtroAnual = this.idReporte==12 ? true : false;
				switch(this.idReporte){
					case 0: case 6: case 7: case 8: case 9: case 10: case 11: case 12: case 13: case 14: case 15: case 16: case 18:
						this.ocultarFechas=false;break;
					case 19:this.verDias=true;break;
				}
			},
			fechaFrom(fecha){
				moment.locale('es')
				return moment(fecha, 'YYYY-MM-DD').fromNow();
			},
			fechaLatam(fecha){ if(fecha) return moment(fecha).format('DD/MM/YYYY'); },
			horaLatam(horita){ return moment(horita, 'HH:mm:ss').format('hh:mm a') },
			sumaProfesionales(){
				this.suma={}
				this.resultados.citas.forEach(cita => {
					const idProfesional = cita.professional_id;
					const monto = parseFloat(cita.payment.price ?? 0);
					if(!this.suma[idProfesional]){
						this.suma[idProfesional] = {monto: monto, confirmado:0, pagado:0}
					}else{
						this.suma[idProfesional].monto+=monto
					}
					if(cita.status=='2') //confirmado
						this.suma[idProfesional].confirmado++;
					if(cita.payment.pay_status=='2') //confirmado
						this.suma[idProfesional].pagado++;
				});
			},
			queServicio(id){
				const servi= this.resultados.servicios.find(x=> x.id==id)
				if(servi) return servi.descripcion
				else return 'Otros'
			},
			sumaServicios(){
				this.suma={}
				this.resultados.citas.forEach(cita => {
					const idServicio = cita.type;
					const monto = parseFloat(cita.payment.price ?? 0);
					if(!this.suma[idServicio]){
						this.suma[idServicio] = {monto: monto, confirmado:0, pagado:0}
					}else{
						this.suma[idServicio].monto+=monto
					}
					if(cita.status=='2') //confirmado
						this.suma[idServicio].confirmado++;
					if(cita.payment.pay_status=='2') //confirmado
						this.suma[idServicio].pagado++;
				});
			},
			sumaMedios(){
				this.suma={}
				this.sumaTodo=0;
				this.resultados.pagos.forEach(medio => {
					const idMedio = medio.moneda;
					const monto = parseFloat(medio.price ?? 0);
					this.sumaTodoMedio+=monto;
					if(!this.suma[idMedio]){
						this.suma[idMedio] = {monto: monto, confirmado:0, pagado:0}
					}else{
						this.suma[idMedio].monto+=monto
					}
				});
			},
			sumaEstados(){
				this.suma={}
				this.resultados.seguimientos.forEach(seguido => {
					const idSeguido = seguido.idSeguimiento;
					//const monto = parseFloat(seguido.payment.price ?? 0);
					if(!this.suma[idSeguido]){
						this.suma[idSeguido] = {contador:1}
					}else{
						this.suma[idSeguido].contador++
					}
					
				});
			},
			queEstado(id){
				const servi= this.resultados.estados.find(x=> x.id==id)
				if(servi) return servi.seguimiento
				else return 'Otros'
			}
		},
		mounted(){
			this.reportes.sort((a, b) => {
				if (a.nombrado < b.nombrado) return -1;
				if (a.nombrado > b.nombrado) return 1;
				return 0;
			});
			this.cargarDatos()
		},
		computed:{
			
		}
	}
</script>