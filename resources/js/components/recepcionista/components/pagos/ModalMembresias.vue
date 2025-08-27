<template>
	<!-- Modal -->
	<div class="modal fade" id="modalMembresias" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered ">
			<div class="modal-content">
				<div class="modal-header border-0 pb-0">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Paquetes</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<section v-show="vista == 'buscar'">
						<label for=""><i class="fas fa-search"></i> Filtre al paciente por DNI o Nombres</label>
						<input type="text" class="form-control" v-model="txtBusqueda" @change="activaResultados = false"
							@keyup.enter="searchHistoria()">
						<div class="mt-3" v-if="pacientes.length > 0">
							<p class="mb-1">Pacientes encontrados:</p>
							<ol class="list-group list-group-flush">
								<li class="list-group-item list-group-item-action puntero" v-for="(paciente, index) in pacientes"
									@click="cambiarVista('membresia', index)">
									<div class="d-flex w-100 justify-content-between ">
										<div class="fw-bold text-capitalize">
											<span>{{ paciente.name }} {{ paciente.nombres }} <span
													class="badge text-bg-secondary">{{ paciente.etiqueta }}</span></span>
										</div>
										<button class="btn btn-sm" title="Agregar Membresía"><i class="fa-solid fa-angles-right"></i></button>
									</div>
									<p class="mb-1">{{ paciente.dni }}</p>
									<small><i class="fa-solid fa-mobile-screen"></i> {{ paciente.phone }} </small>
								</li>
							</ol>
						</div>
						<div class="mt-3" v-else-if="activaResultados">
							<p>No se encontraron resultados para {{ txtBusqueda }}</p>
						</div>
					</section>
					<section v-show="vista == 'membresia'">
						<p><a href="#!" @click="cambiarVista('buscar', -1)"><i class="fa-solid fa-angles-left"></i> Volver</a></p>
						<p class="mb-0 text-capitalize"><strong>Paciente</strong> {{ pacienteElegido.name }} {{ pacienteElegido.nombres }}</p>
						<p class="mb-0"><strong>D.N.I</strong> {{ pacienteElegido.dni }}</p>
						<p class="mb-0"><strong>Celular</strong> {{ pacienteElegido.phone }}</p>
						<p class="mb-0"><strong>Última atención</strong> {{ pacienteElegido.etiqueta == '' ? 'Sin registro' :
							pacienteElegido.etiqueta }}</p>
						<label class="mt-3" for="">Tipo de paquete</label>
						<select name="" class="form-select" id="sltMembresia" v-model="membresia.tipo" @change="calcularFechas()">
							<option v-for="precio in precios" :value="precio.id">{{ precio.descripcion }}</option>
						</select>
						<p class="mb-0 mt-2"><i class="fa-regular fa-hourglass"></i> Cantidad de sesiones: {{ cantSesiones }}</p>
						<p class="mb-2"><i class="fa-regular fa-calendar-xmark"></i> Membresía caduca en {{ cantMeses }} meses</p>


						<ul class="nav nav-tabs mt-3 d-none" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
								<button class="nav-link active" id="pagos-tab" data-bs-toggle="tab" data-bs-target="#pagos-tab-pane"
									type="button" role="tab" aria-controls="pagos-tab-pane" aria-selected="true"><i
										class="far fa-calendar-check"></i> Pagos</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="membresia-tab" data-bs-toggle="tab" data-bs-target="#membresia-tab-pane"
									type="button" role="tab" aria-controls="membresia-tab-pane" aria-selected="false"><i
										class="far fa-calendar-alt"></i> Sesiones</button>
							</li>
						</ul>

						<div class="tab-content" id="tabContenedor">
							<div class="tab-pane fade show active" id="pagos-tab-pane" role="tabpanel" aria-labelledby="pagos-tab"
								tabindex="0">
								<!-- Inicio de tab de Pagos -->
								<div class="row pt-3">
									<div class="col-6">
										<label class="mt-2 " for="">N° Cuotas</label>
										<input type="number" class="form-control" v-model="membresia.cuotas" @change="calcularFechas()" min="0" max="5">
									</div>
									<div class="col-6">
										<label clas="mb-0 mt-2">¿Tiene descuento?</label>
										<div class="form-check">
											<input class="form-check-input" type="checkbox" value="" id="HayDescto" v-model="membresia.conDescuento" @change="membresia.descuento = 0">
											<label class="form-check-label">
													<span v-if="!membresia.conDescuento">No</span>
													<span v-else>Si</span>
											</label>
										</div>
									</div>
									<div class="col-6" v-if="membresia.conDescuento">
										<label for="mt-2">Descuento</label>
										<input type="number" class="form-control" v-model="membresia.descuento" @change="calcularFechas()" max="50">
									</div>
								</div>


									<!-- <div class="d-grid col-8 ms-auto">
									<button class="btn btn-outline-primary float-end mt-3" @click="calcularFechas()"><i class="fa-regular fa-handshake"></i> Calcular montos y fechas</button>
								</div> -->

									<table class="table table-hover table-borderless " v-show="fechas.length > 0">
										<thead>
											<tr>
												<th class="pb-1">Fecha de pago</th>
												<th class="pb-1">Monto</th>
												<th class="pb-1">¿Paga?</th>
											</tr>
										</thead>
										<tbody id="tbodyFechas">
												<tr v-for="(fecha, index) in fechas">
														<td> <input type="date" class="form-control" v-model="fecha.dia"> </td>
														<td>
																<!-- Si es una sola cuota, muestra el monto como texto -->
																<span v-if="membresia.cuotas==1">{{ parseFloat(fecha.monto).toFixed(2) }}</span>
																
																<!-- Para la primera y segunda cuota (cuando hay más de 2), permite edición -->
																<input type="number" class="form-control inputPartidos" 
																		v-if="membresia.cuotas>1 " 
																		@keyup="balancearMontos(index)" 
																		:readonly="index == fechas.length-1" 
																		v-model="fecha.monto">
														</td>
														<td>
																<div class="form-check">
																		<input class="form-check-input" type="checkbox" value="" :id="'flexPago' + index"
																				v-model="fecha.pago">
																		<label class="form-check-label" :for="'flexPago' + index">
																				<span v-if="fecha.pago">Si</span>
																				<span v-else>No</span>
																		</label>
																</div>
														</td>
												</tr>
										</tbody>
										<tfoot>
											<tr v-if="membresia.conDescuento">
												<th>Sub Total</th>
												<th>S/ {{ subTotal }}</th>
											</tr>
											<tr v-if="membresia.conDescuento">
												<th>Descuento</th>
												<th>S/ {{ parseFloat( membresia.descuento).toFixed(2) }}</th>
											</tr>
											<tr>
												<th>Total a pagar</th>
												<th>S/ {{ parseFloat(subTotal - membresia.descuento).toFixed(2) }}</th>
											</tr>
										</tfoot>
									</table>
								<!-- Fin de tab de Pagos -->
							</div>
							<div class="tab-pane fade py-3" id="membresia-tab-pane" role="tabpanel" aria-labelledby="membresia-tab"
								tabindex="0">
								<div class="row" v-show="sesionesAcumuladas.length < cantSesiones">
									<div class="col-12">
										<p><i class="fas fa-exclamation-circle"></i> Rellene los datos indispensables para autorrelenar las citas</p>
										<label class="mb-0 mt-2 " for="">Profesional</label>
										<div class="input-group">
											<select class="form-select" id="sltDoctor" v-model="doctorSeleccionado" @change="listarhorario()">
												<option v-for="doctor in doctores" :value="doctor.id">{{ doctor.nombre }}</option>
											</select>
										</div>
									</div>
									<div class="col-6">
										<label class="mb-0 mt-2 " for="">Seleccionar fecha</label>
										<div class="input-group">
											<input type="date" class="form-control" v-model="nuevaFecha.fecha" @change="listarhorario()">
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<label class="mb-0 mt-2">Horario</label>
											<select class="form-select" name="sltProfesionalHorarioID" id="sltProfesionalHorarioID" v-model="idHorario">
												<option value="" disabled selected>Selecciona un horario</option>
												<option v-for="hora in horarios" :key="hora.id" v-if="hora" :value="hora.id">
													{{ horaLatam1(hora.check_time) }} - {{ horaLatam2(hora.departure_date) }}
												</option>
											</select>
										</div>
									</div>
									<div class="col-12 text-end">
										<button class="btn btn-sm btn-outline-success" @click="agregarUnaCita()"><i class="far fa-arrow-alt-circle-down"></i> Agregar cita</button>
									</div>
								</div>
								<div class="row ">
									<div class="col-12">
										<label for="">Sesiones programadas ({{sesionesAcumuladas.length}} de {{cantSesiones}})</label>
									</div>
									<div class="col-12" v-show="sesionesAcumuladas.length>0">
										<table class="table table-hover table-bordered">
											<thead>
												<tr>
													<th>N°</th>
													<th>Profesional</th>
													<th>Fecha y Hora</th>
												</tr>
											</thead>
											<tbody>
												<tr v-for="(sesion, index) in sesionesAcumuladas">
													<td>{{ index+1 }}</td>
													<td><button class="btn btn-outline-danger border-0" @click="borrarSesionAcumulada(index)"><i class="fas fa-times"></i></button> {{ sesion.doctor }}</td>
													<td>{{ fechaLatam(sesion.fecha) }} <br>{{ sesion.hora }}</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

						<label for="">Comentarios adicionales:</label>
						<textarea class="form-control mb-3" id="txtComentarios" v-model="comentarios" row="2"></textarea>


						<div class="d-flex justify-content-end">
							<button class="btn btn-outline-primary" data-bs-dismiss="modal" @click="guardar()"><i class="fa-regular fa-floppy-disk"></i> Guardar membresía</button>
						</div>
					</section>
				</div>

			</div>
		</div>
	</div>
</template>
<script>
import moment from 'moment'
import alertify from 'alertifyjs';
import { TimeScale } from 'chart.js';
export default {
	name: 'ModalMembresias',
	data() {
		return { // vista: 'buscar',
			txtBusqueda: '', pacientes: [], indexGlobal: null, pacienteElegido: {}, precios: [],
			membresia: { tipo: 15, cuotas: 1, precio: 0, fin: moment().add(1, 'month').format('YYYY-MM-DD'), descuento:0, conDescuento:false },
			fechas: [], activaResultados: false, nuevaFecha: { fecha: moment().format('YYYY-MM-DD') },
			doctores: [], horarios: [], horariosAll: [], hoursProfessional: [], schedulesInvalid: {}, horasSolas: [], horasMalas: [], dayWeek: { 0: 'Lunes', 1: "Martes", 2: "Miercoles", 3: "Jueves", 4: "Viernes", 5: "Sabado", 6: "Domingo", }, doctorSeleccionado: -1, sesionesAcumuladas:[], idHorario:'', comentarios:'',
		}
	},
	props: ['idUsuario', 'vista'],
	methods: {
		async searchHistoria() {
			let search = this.txtBusqueda.split('/'),
				nombre = search[0],
				profesional = search[1] || null,
				fecha = search[2] || null,
				dni = search[0]
				;

			if (nombre === '') return this.listar();
			if (dni) dni = dni.trim();

			await this.axios.get(`/api/searchPatientByNameDni/${this.txtBusqueda}`)
				.then(res => {
					//console.log(res.data);
					this.pacientes = res.data;
					this.activaResultados = true;
				})
				.catch(err => {
					console.error(err)
				})

		},
		cambiarVista(vista, index) {
			this.vista = vista;
			if (index > -1) {
				this.indexGlobal = index;
				this.pacienteElegido = this.pacientes[index];
				this.calcularFechas();
			}
		},
		async preciosMembresias() {
			const servidor = await fetch('/api/preciosMembresias')
			this.precios = await servidor.json();
		},
		agregarUnaCita(){
			
			var selec = document.getElementById("sltDoctor");
			var selectHora = document.getElementById("sltProfesionalHorarioID");

			if( selec.value>0 && selectHora.value>0)
				this.sesionesAcumuladas.push({ idHorario: this.idHorario, idProfesional: this.doctorSeleccionado, fecha: this.nuevaFecha.fecha, idPaciente: this.pacienteElegido.id, doctor: selec.options[selec.selectedIndex].text, hora: selectHora.options[selectHora.selectedIndex].text })
			else
				alertify.notify('<i class="fas fa-skull-crossbones"></i> Hay un dato no rellenado', 'danger', 10)

		},
		calcularFechas() {
			this.fechas = [];
			const precioBase = this.mostrarPrecio ;
			const precioParcial = Math.ceil((precioBase / this.membresia.cuotas)*10)/10
			var hoy = moment()
			this.membresia.precio = precioBase;
			for (let i = 0; i < this.membresia.cuotas; i++) {
				this.fechas.push({
					dia: hoy.format('YYYY-MM-DD'),
					monto: parseFloat(precioParcial).toFixed(2),
					total: precioBase,
					pago: false
				})
				if(this.membresia.cuotas>1){ //this.membresia.tipo==71 &&
					const firstTr = document.querySelector('#tbodyFechas tr:first-child');
					const firstInput = firstTr.querySelector('.inputPartidos:first-child');
					firstInput.readOnly = false;
				}
				hoy = moment(hoy).add(1, 'month')
			}
			this.membresia.fin = this.membresia.tipo==47 ?  moment().add(1,'year').format('YYYY-MM-DD') : moment().format('YYYY-MM-DD')
			this.balancearMontos(0);
		},
		balancearMontos(editedIndex) {
			const descuento = this.membresia.descuento ?? 0;
			if (parseInt(this.membresia.cuotas) >1) {
				if (editedIndex === 0) {
					// Si se editó la primera cuota, balanceamos desde la segunda
					let cantidadFechasABalancear = this.fechas.length - 1;
					let montoRestante = (parseFloat(this.fechas[0].total) - parseFloat(this.fechas[0].monto) - descuento) / cantidadFechasABalancear;
					
					this.fechas.forEach((fecha, index) => {
							if (index > 0) { // Balanceamos desde la segunda cuota
									console.log('aplicar desde segunda', fecha, montoRestante);
									fecha.monto = montoRestante.toFixed(2);
							}
					});
				} else if(editedIndex<= this.fechas.length-2) {
					// Si se editó la segunda cuota, respetamos la primera y balanceamos desde la tercera
					let cantidadFechasABalancear = this.fechas.length - editedIndex+1;
					let sumaAnteriores = this.fechas.filter( (_,index) => index<= editedIndex )
					.reduce( (acc, item) => acc + parseFloat(item.monto), 0 )

					let montoRestante = (parseFloat(this.fechas[0].total) - sumaAnteriores - descuento) / cantidadFechasABalancear;

					for(let i=editedIndex+1; i<this.fechas.length; i++)
						this.fechas[i].monto = montoRestante.toFixed(2)
						
				}
			}
		},
		borrarSesionAcumulada(index){
			this.sesionesAcumuladas.splice(index,1)
		},
		async guardar() {
			if (this.membresia.cuotas <= 0){
				alertify.notify('<i class="fa-solid fa-bomb"></i> El número de cuotas mínimo debe ser 1', 'danger', 10);
				return false;
			}
			if (!this.membresia.fin){
				alertify.notify('<i class="fa-solid fa-bomb"></i> Ingrese el último día de la memebresía', 'danger', 10);
				return false;
			}

			if(this.membresia.descuento >0 && this.comentarios ==''){
				alertify.notify('<i class="fa-solid fa-bomb"></i> Debe agregar un motivo por el descuento', 'danger', 10);
				return false;
			}else{
				this.comentarios = 'Descuento por: '+ this.comentarios
			}
				
			var mem = document.getElementById("sltMembresia");

			let datos = new FormData();
			datos.append('idPaciente', this.pacienteElegido.id)
			datos.append('customer', this.pacienteElegido.name+ ' '+ this.pacienteElegido.nombres)
			datos.append('motivo', this.pacienteElegido.id)
			datos.append('membresia', JSON.stringify(this.membresia))
			datos.append('user_id', this.idUsuario)
			datos.append('nombreMembresia', mem.options[mem.selectedIndex].text )
			datos.append('fechas', JSON.stringify(this.fechas))
			datos.append('fechas_membresias', JSON.stringify(this.sesionesAcumuladas))
			datos.append('comentarios', this.comentarios )
			datos.append('meses', this.cantMeses )

			const servidor = await fetch('/api/guardarMembresia', {
				method: 'POST', body: datos
			})
			
			const respuesta = await servidor.json();
			if (respuesta.mensaje) {
				this.pacienteElegido = []
				this.fechas = []
				this.$swal({
					title: 'Se guardó la membresía',
					showConfirmButton: false,
					icon:'success'
				})
				alertify.notify('<i class="fa-regular fa-calendar-check"></i> Membresía guardada', 'success', 10);
			} else
				alertify.notify('<i class="fa-regular fa-bomb"></i> Hubo un error guardando', 'danger', 10);
			
		},
		async listarProfesionales() {
			await this.axios.get('/api/profesional')
				.then(response => {
					this.doctores = response.data;
					this.listarhorario();
				})
		},
		async listarhorario() {
			let id = this.doctorSeleccionado;

			await this.axios.get(`/api/horario/${id}`)
				.then(res => {
					this.horarios = res.data.schedulesInvalid;
					this.horariosAll = res.data.schedules;
					this.hoursProfessional = this.horarios;

					this.emitSchedule(this.nuevaFecha.fecha);

					this.schedulesInvalid = []
					this.hoursProfessional.forEach(el => {
						this.schedulesInvalid.push(el.schedule_id)
					});
				})
				.catch(err => {
					console.error(err)
				})
		},

		emitSchedule(info = this.nuevaFecha.fecha) {
			this.horarios = []

			let arraySchedulesInvalid = []
			this.hoursProfessional.forEach(el => {
				if (!arraySchedulesInvalid.includes(el.schedule_id)) {
					arraySchedulesInvalid.push(el.schedule_id)
				}
			})

			this.horariosAll.forEach(el => {
				if (el.day === this.dayWeek[new Date(info).getDay()]) {
					if (arraySchedulesInvalid.includes(el.id)) {
						// Hay cita
						if (el.appointments.find(el => el.date === info && el.status != 3) ? true : false) {
						} else {
							this.horarios.push(el)
						}
					} else {
						// No hay cita
						this.horarios.push(el)
					}
				}
			})
		},
		fechaLatam(fecha) { return moment(fecha).format('DD/MM/YYYY'); },
		horaLatam1(horita) { return moment(horita, 'HH:mm:ss').format('hh:mm') },
		horaLatam2(horita) { return moment(horita, 'HH:mm:ss').format('hh:mm a') },
		notifica(){ console.info('notificado');}
	},
	computed: {
		mostrarPrecio() { 
			const nuevos = this.precios.find(x => x.id == this.membresia.tipo)
			return nuevos ? parseFloat(nuevos.nuevos).toFixed(2) : 0;
		},
		subTotal(){
			if (this.membresia.cuotas == '1') {
				return this.membresia.precio;
			}else{
				const sum = this.fechas.reduce( (acc, item) => {return acc + parseFloat(item.monto)} ,0)
				return sum.toFixed(2)
			}
		},
		cuentaMes() {
			moment.locale('es')
			return moment(this.membresia.fin).fromNow()
		},
		cantSesiones() { 
			const precio = this.precios.find(x => x.id == this.membresia.tipo)
			return precio ? precio.sesiones :''
		},
		cantMeses() { 
			const precio = this.precios.find(x => x.id == this.membresia.tipo)
			return precio ? precio.meses : 0
		},
		
	},
	mounted() {
		this.preciosMembresias();
		this.listarProfesionales();
		this.$on('alertaSimple', this.notifica())
	}
}
</script>
<style></style>