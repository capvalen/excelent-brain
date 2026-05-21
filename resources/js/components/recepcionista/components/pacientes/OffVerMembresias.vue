<template>
	<div class="offcanvas offcanvas-end " tabindex="-1" id="offVerMembresias" aria-labelledby="offcanvasExampleLabel">
		<div class="offcanvas-header">
			<h5 class="offcanvas-title" id="offcanvasExampleLabel">Paquetes asignados</h5>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
		</div>
		<div class="offcanvas-body">
			<div>
				<p><strong>Paciente:</strong> <span>{{nombrePaciente}}</span></p>
				<p><strong>Membresías asignadas:</strong></p>
			</div>
			<div class="accordion" id="accordionExample">
				<div class="accordion-item" v-for="(membresia, index) in membresias" @click="membresiaActiva = membresia">
					<h2 class="accordion-header">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapse'+membresia.id" aria-expanded="false" :aria-controls="'#collapse'+membresia.id">
							{{membresia.descripcion}} - {{ fechaLatam(membresia.inicio) }}
						</button>
					</h2>
					<div :id="'collapse'+membresia.id" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
						<div class="accordion-body">
							<p class="mb-1"><strong>Paquete</strong> <span>{{ membresia.descripcion }}</span></p>
							<p class="mb-1"><strong>Tipo</strong>
								<span v-if="tipoMembresia(membresia) == 'tiempo'" class="mb-1">Paquete de temporada</span>
								<span v-else class="mb-1">Paquete por sesiones</span>
							</p>
							<p class="mb-1"><strong>Estado</strong>
								<button v-if="membresia.estado==1" @click="abrirEstados(membresia.estado)" class="badge border-0 text-bg-warning" data-bs-toggle="modal" data-bs-target="#modalEstado">Sin activar</button>
								<button v-if="membresia.estado==2" @click="abrirEstados(membresia.estado)" class="badge border-0 text-bg-success" data-bs-toggle="modal" data-bs-target="#modalEstado">Activo</button>
								<button v-if="membresia.estado==3" @click="abrirEstados(membresia.estado)" class="badge border-0 text-bg-success" data-bs-toggle="modal" data-bs-target="#modalEstado">Concluido</button>
								<button v-if="membresia.estado==4" @click="abrirEstados(membresia.estado)" class="badge border-0 text-bg-warning" data-bs-toggle="modal" data-bs-target="#modalEstado">Prorrateado </button>
								<button v-if="membresia.estado==5" @click="abrirEstados(membresia.estado)" class="badge border-0 text-bg-secondary" data-bs-toggle="modal" data-bs-target="#modalEstado">Congelado </button>
								<button v-if="membresia.estado==6" @click="abrirEstados(membresia.estado)" class="badge border-0 text-bg-danger" data-bs-toggle="modal" data-bs-target="#modalEstado">Cancelado </button>
							</p>
							<p class="mb-1"><strong>Fecha de inicio</strong> <span>{{ fechaLatam(membresia.inicio) }}</span></p>
							<p v-if="tipoMembresia(membresia) == 'tiempo'" class="mb-1"><strong>Fecha límite final</strong> <span>{{ fechaLatam(membresia.fin) }}</span></p>
							<p v-else><strong>Sesiones</strong> {{ membresia.contador_paquetes }} de {{ membresia.sesiones }}</p>

							<p class="mb-1"><strong>N° cuotas</strong> <span>{{ membresia.cuotas }}</span></p>
							<p class="mb-1"><strong>Cuotas pagadas:</strong> (<span>{{ membresia.pagados.length }} cuotas</span>) <span v-if="membresia.pagados.length>0" class="badge bg-primary rounded-pull p-2 m-1" style="cursor: pointer;" title="Voucher de pagos acumulados" @click="voucherAcumulados(index)"><i class="far fa-sticky-note"></i></span></p>
							<p class="mb-1"><strong>Monto Total</strong> <span>S/ {{ parseFloat(membresia.monto).toFixed(2) }}</span></p>
							<p class="mb-1"><strong>Comentarios:</strong> <span>{{ membresia.comentarios }}</span></p>
							<p class="mt-2 mb-0"><span class="text-muted">Cuotas pagadas:</span></p>
							<ol class="list-group mb-2">
								<li class="list-group-item d-flex justify-content-between align-items-start" v-for="pagado in membresia.pagados">
									<div class="ms-2 me-auto">
										<div><span class="fw-bold">Cuota #{{ pagado.numero_cuota || '—' }} - Pagado:</span> {{ fechaLatam(pagado.date) }} </div>
										<p class="mb-0">Monto: S/ {{ parseFloat(pagado.price).toFixed(2) }} </p>
									</div>
								</li>
							</ol>
							<p class="text-muted" v-if="membresia.pagados.length==0"><small>No hay pagos registrados aún</small></p>
							<p class="mt-2 mb-0"><span class="text-muted">Cuotas pendientes de pago:</span></p>
							<ol class="list-group mb-2">
								<li class="list-group-item d-flex justify-content-between align-items-start" v-for="(deuda, indice) in membresia.deudas">
									<div class="ms-2 me-auto">
										<div><span class="fw-bold">Cuota #{{ deuda.numero_cuota || '—' }} - Fecha de pago:</span> {{ fechaLatam(deuda.fecha) }} </div>
										<p class="mb-0">Monto: S/ {{ parseFloat(deuda.monto).toFixed(2) }} </p>
									</div>
									<span title="Ampliar fecha" class="badge bg-primary rounded-pill p-2 ms-1" @click="ampliarFechaDeuda(deuda.id, ampliacion=deuda.fecha)" data-bs-toggle="modal" data-bs-target="#modalAmpliarFechaMembresia" style="cursor: pointer;"><i class="far fa-clock"></i></span>
									<!-- Botón dividir: solo en la primera cuota pendiente y si no se ha dividido antes -->
									<span v-if="indice === 0 && !membresia.cuota_dividida" title="Dividir cuota" class="badge bg-warning rounded-pill p-2 ms-1" @click="abrirDividirCuota(index, indice)" style="cursor: pointer;"><i class="fa-solid fa-scissors"></i> ✂️</span>
									<!-- Botón pagar: solo en la cuota más antigua (indice 0) -->
									<span v-if="indice === 0" title="Aplicar pago" class="badge bg-success rounded-pill p-2 ms-1" @click="pagarDeuda(index, indice)" style="cursor: pointer;"><i class="far fa-gem"></i></span>
									<span v-else title="Pague primero la cuota anterior" class="badge bg-secondary rounded-pill p-2 ms-1" style="cursor: not-allowed;">🔒</span>
								</li>
							</ol>
							<p v-if="membresia.deudas.length==0">No hay deudas pendientes</p>

							<button class="mt-2 btn btn-secondary " data-bs-target="#modalVerCitas" data-bs-toggle="modal" @click="pedirCitasMembresia(membresia)"> <i class="fa-solid fa-list"></i> Ver fechas de citas</button>
							<button class="mt-2 btn btn-danger" @click="anular(index)"><i class="fa-solid fa-ban"></i> Anular membresía</button>
						</div>
					</div>
				</div>
				<div v-if="membresias.length==0">El paciente no tiene membresías asignadas</div>
			</div>
		</div>

		<!-- Modal para ampliar la fecha -->
		<div class="modal fade" id="modalVerCitas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header border-0">
						<h1 class="modal-title fs-5" id="exampleModalLabel">Citas programadas para membresías</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col">
								<p><strong>Membresía seleccionada:</strong>
									<span v-if="parseInt(membresiaActiva.sesiones) == 12">{{ membresiaActiva.descripcion }} + Lectura de resultaos</span>
									<span v-else>{{ membresiaActiva.descripcion }}</span>
								</p>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col d-flex d-grid justify-content-between align-items-center">
								<template v-if="tipoMembresia(membresiaActiva) == 'sesiones'">
									<span><i class="fa-solid fa-receipt"></i> {{ contarCitasActivas() }} de {{membresiaActiva.sesiones}} Citas</span>
									<template v-if="parseInt(membresiaActiva.sesiones) == 12">
										<button v-if="contarCitasActivas() < 12 " class="btn btn-outline-primary"  @click="activarFechas=true" data-bs-target="#modalProximaCita" data-bs-toggle="modal"><i class="fa-solid fa-plus"></i> Agregar cita</button>
										<button v-if="contarCitasActivas() == 12 " class="btn btn-outline-primary"  @click="activarFechas=true" data-bs-target="#modalProximaCita" data-bs-toggle="modal"><i class="fa-solid fa-plus"></i> Agregar lectura</button>
									</template>
									<template v-else>
										<button v-if="contarCitasActivas() < parseInt(membresiaActiva.sesiones) " class="btn btn-outline-primary"  @click="activarFechas=true" data-bs-target="#modalProximaCita" data-bs-toggle="modal"><i class="fa-solid fa-plus"></i> Agregar cita</button>
									</template>
								</template>
								<template v-if="tipoMembresia(membresiaActiva) == 'tiempo'">
									<span>Límite de la membresía: <i class="fa-regular fa-clock"></i> {{ fechaLatam(membresiaActiva.fin) }}</span>
									<button v-if="calcularMesesMembresia() " class="btn btn-outline-primary"  @click="activarFechas=true" data-bs-target="#modalProximaCita" data-bs-toggle="modal"><i class="fa-solid fa-plus"></i> Agregar cita</button>
								</template>
							</div>
						</div>
						<table class="table">
							<thead>
								<tr>
									<th>N°</th>
									<th>Fecha y hora</th>
									<th>Profesional</th>
									<th>Servicio</th>
									<th>Estado</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(cita, index) in citas">
									<td>{{ index+1 }}</td>
									<td>{{ fechaLatam(cita.date) }} {{ horaLatam(cita.schedule.check_time) }}</td>
									<td>{{ cita.professional.nombre }}</td>
									<td>{{ cita.precio.descripcion }}</td>
									<td>
										<span v-if="cita.status==1">Sin Confirmar</span>
										<span class="text-primary" v-if="cita.status==2">Cita Confirmada </span>
										<span class="text-danger" v-if="cita.status==3">Cita Anulada </span>
										<span class="text-danger" v-if="cita.status==4">Cita Reprogramada </span>
										<span class="text-danger" v-if="cita.status==5">Cita eliminada </span>
									</td>
								</tr>
							</tbody>
						</table>
						<p v-if="citas.length==0">No hay citas asociadas</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal para cambiar estado -->
		<div class="modal fade" id="modalEstado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header border-0">
						<h1 class="modal-title fs-5" id="exampleModalLabel">Cambiar estado</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<ul class="list-group">
							<li class="list-group-item list-group-item-action puntero" data-bs-dismiss="modal" :class="{'active':queEstado=='1'}" @click="cambiarEstado(1,'Inactivo', $event)"><i class="fa-solid fa-circle-notch"></i> Inactivo</li>
							<li class="list-group-item list-group-item-action puntero" data-bs-dismiss="modal" :class="{'active':queEstado=='2'}" @click="cambiarEstado(2,'Activo', $event)"><i class="fa-solid fa-circle-notch"></i> Activo</li>
							<li class="list-group-item list-group-item-action puntero" data-bs-dismiss="modal" :class="{'active':queEstado=='3'}" @click="cambiarEstado(3,'Concluido', $event)"><i class="fa-solid fa-circle-notch"></i> Concluido</li>
							<li class="list-group-item list-group-item-action puntero" data-bs-dismiss="modal" :class="{'active':queEstado=='4'}" @click="cambiarEstado(4,'Prorrateado', $event)"><i class="fa-solid fa-circle-notch"></i> Prorrateado</li>
							<li class="list-group-item list-group-item-action puntero" data-bs-dismiss="modal" :class="{'active':queEstado=='5'}" @click="cambiarEstado(5,'Congelado', $event)"
								v-if="tipoMembresia(membresiaActiva) == 'tiempo'"
							><i class="fa-solid fa-circle-notch"></i> Congelado</li>
							<li class="list-group-item list-group-item-action puntero" data-bs-dismiss="modal" :class="{'active':queEstado=='6'}" @click="cambiarEstado(6,'Cancelado', $event)"><i class="fa-solid fa-circle-notch"></i> Cancelado</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal para dividir cuota -->
		<div class="modal fade" id="modalDividirCuota" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-sm">
				<div class="modal-content">
					<div class="modal-header border-0 pb-0">
						<h5 class="modal-title">✂️ Dividir cuota</h5>
						<button type="button" id="closeDividir" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body" v-if="dividirData.deuda">
						<p class="mb-1">Cuota #{{ dividirData.deuda.numero_cuota }}</p>
						<p class="mb-2">Monto original: <strong>S/ {{ parseFloat(dividirData.deuda.monto).toFixed(2) }}</strong></p>
						<label>Monto de la primera parte:</label>
						<input type="number" class="form-control mb-2" v-model.number="dividirData.montoPrimera" step="0.01" min="0.01" :max="dividirData.deuda.monto - 0.01">
						<p class="text-muted mb-1"><small>Segunda parte: S/ {{ dividirData.deuda ? (parseFloat(dividirData.deuda.monto) - dividirData.montoPrimera).toFixed(2) : '0.00' }}</small></p>
						<label>Fecha de pago de la segunda parte:</label>
						<input type="date" class="form-control mb-2" v-model="dividirData.fechaSegunda">
					</div>
					<div class="modal-footer border-0">
						<button type="button" class="btn btn-warning" @click="confirmarDividirCuota()">✂️ Dividir</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal para aplicar pago -->
		<div class="modal fade" id="modalAplicarPago" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-sm">
				<div class="modal-content">
					<div class="modal-header border-0 pb-0">
						<h5 class="modal-title">💎 Aplicar pago</h5>
						<button type="button" id="closeAplicarPago" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body" v-if="pagoData.deuda">
						<p class="mb-1"><strong>{{ pagoData.membresia.descripcion }}</strong></p>
						<p class="mb-1">Cuota #{{ pagoData.deuda.numero_cuota }}</p>
						<p class="mb-2">Monto: <strong>S/ {{ parseFloat(pagoData.deuda.monto).toFixed(2) }}</strong></p>
						<label>Método de pago:</label>
						<select class="form-select mb-2" v-model="pagoData.moneda">
							<option v-for="mon in monedas" :value="mon.id">{{mon.tipo}}</option>
						</select>
						<label>Motivo a registrar:</label>
						<input type="text" class="form-control mb-2" v-model="pagoData.motivoNuevo">
					</div>
					<div class="modal-footer border-0">
						<button type="button" class="btn btn-success" @click="confirmarPagarDeuda()">Confirmar Pago</button>
					</div>
				</div>
			</div>
		</div>

		<ModalAmpliarFechaMembresia :queCita ="queCita" :fechaBase="queFecha" tipo="tipo"></ModalAmpliarFechaMembresia>

		<ModalProximaCita :profesional="profesional" :paciente="paciente" :idMembresia="idMembresia" :idServicio="idServicio" :membresia ='membresiaActiva' @citaCreada="pedirCitasMembresia(membresiaActiva)"></ModalProximaCita>
		<ModalPaqueteria :paciente="paciente" :profesionales="profesional"></ModalPaqueteria>
		
	</div>
</template>

<script>
import moment from 'moment';
import alertify from 'alertifyjs';
import ModalAmpliarFechaMembresia from './ModalAmpliarFechaMembresia.vue'
import ModalProximaCita from '../../../profesional/components/consultas/ModalProximaCita.vue'
import ModalPaqueteria from './ModalPaqueteria.vue';


export default{
	name:'ModalVerMembresias',
	props:['queId', 'nombrePaciente', 'idUser', 'paciente', 'profesional'],
	components:{ ModalAmpliarFechaMembresia, ModalProximaCita, ModalPaqueteria },
	data(){return {
		membresias:[], ampliacion:null, queDeuda:null, citas:[], queFecha:null, queCita:null, activarFechas:false, idMembresia:null, idPrecio: null, idServicio:null, membresiaActiva:[], queEstado:null, verDias:false, congelar:0,
		dividirData: { indexMembresia: null, indexDeuda: null, deuda: null, montoPrimera: 0, fechaSegunda: moment().add(15, 'days').format('YYYY-MM-DD') },
		pagoData: { indexMembresia: null, indexDeuda: null, deuda: null, membresia: null, moneda: 1, motivoNuevo: '' },
		monedas: []
	}},
	mounted(){
		//this.buscarMembresias()
		this.axios.get('/api/listarMonedas').then(res => this.monedas = res.data);
	},
	methods:{
		buscarMembresias(){
			this.axios('/api/buscarMembresias/'+this.queId)
			.then(res=> this.membresias = res.data)
		},
		ampliarFechaDeuda(id, fecha){
			this.queCita = id;
			this.queFecha = fecha
			$('#modalAmpliarFechaMembresia').modal('show')
		},
		anular(index){
			if(confirm(`¿Desea anular la membresía ${this.membresias[index].descripcion}?`)){
				this.axios.post('/api/anularMembresia/', { id: this.membresias[index].id} )
				.then(resp => location.reload())
			}
		},
		pagarDeuda(index, indice){
			const membresia = this.membresias[index];
			const deuda = membresia.deudas[indice];
			this.pagoData = {
				indexMembresia: index,
				indexDeuda: indice,
				deuda: deuda,
				membresia: membresia,
				moneda: 1,
				motivoNuevo: `Cuota de ${membresia.descripcion}` + (deuda.numero_cuota ? ` #${deuda.numero_cuota}` : '')
			};
			$('#modalAplicarPago').modal('show');
		},
		confirmarPagarDeuda(){
			let datos = new FormData();
			const d = this.pagoData;
			datos.append('idDeuda', d.deuda.id)
			datos.append('idMembresia', d.membresia.id)
			datos.append('user_id', this.idUser)
			datos.append('nombre', this.nombrePaciente)
			datos.append('precio', d.deuda.monto)
			datos.append('tipo', 7) // 7 = pago de membresía
			datos.append('observación', d.membresia.motivo) // Goes to deudas.observaciones
			datos.append('motivoNuevo', d.motivoNuevo) // Goes to extra_payments.observation
			datos.append('moneda', d.moneda)
			datos.append('estado', 2) // Realizó pago

			this.axios.post('/api/pagarDeudaMembresia', datos)
			.then(res =>{
				if(res.data.error){
					this.$swal({icon:'error', title: res.data.error})
				} else {
					$('#modalAplicarPago').modal('hide');
					this.buscarMembresias();
					this.$swal({icon:'success', title: 'Deuda Pagada'})
				}
			})
			.catch(err => {
				if(err.response && err.response.data && err.response.data.error){
					this.$swal({icon:'error', title: err.response.data.error})
				}
			})
		},
		abrirDividirCuota(indexMembresia, indexDeuda){
			const deuda = this.membresias[indexMembresia].deudas[indexDeuda];
			this.dividirData = {
				indexMembresia,
				indexDeuda,
				deuda,
				montoPrimera: Math.floor(deuda.monto / 2 * 100) / 100,
				fechaSegunda: moment().add(15, 'days').format('YYYY-MM-DD')
			};
			$('#modalDividirCuota').modal('show');
		},
		confirmarDividirCuota(){
			const d = this.dividirData;
			if(d.montoPrimera <= 0 || d.montoPrimera >= d.deuda.monto){
				this.$swal({icon:'error', title:'El monto debe ser mayor a 0 y menor al monto original'});
				return;
			}
			if(!d.fechaSegunda){
				this.$swal({icon:'error', title:'Ingrese la fecha de la segunda cuota'});
				return;
			}

			let datos = new FormData();
			datos.append('idMembresia', this.membresias[d.indexMembresia].id);
			datos.append('monto_primera', d.montoPrimera);
			datos.append('fecha_segunda', d.fechaSegunda);
			datos.append('user_id', this.idUser);

			this.axios.post('/api/dividirCuotaMembresia', datos)
			.then(res => {
				if(res.data.mensaje){
					document.querySelector('#modalDividirCuota #closeDividir').click();
					this.buscarMembresias();
					this.$swal({icon:'success', title: res.data.mensaje});
				} else if(res.data.error){
					this.$swal({icon:'error', title: res.data.error});
				}
			})
			.catch(err => {
				if(err.response && err.response.data && err.response.data.error){
					this.$swal({icon:'error', title: err.response.data.error});
				}
			})
		},
		pedirCitasMembresia(membresia){
			this.membresiaActiva = membresia
			this.idMembresia = membresia.id
			this.idServicio = this.membresias.find(x=> this.idMembresia == x.id).tipo
			this.axios('/api/pedirCitasMembresia/'+this.idMembresia)
			.then(res=> this.citas = res.data )
		},
		abrirEstados(estado){
			this.idMembresia = this.membresiaActiva.id;
			this.queEstado=estado
			const membresia = this.membresias.find(x=> this.idMembresia == x.id)
			this.congelar = 0

			if(estado == '5'){
				const hoy = moment().startOf('day');
				const final = moment(membresia.fin, 'YYYY-MM-DD')
				if(hoy.isBefore(final))
					this.congelar = final.diff(hoy,'days')-1
				else
					this.congelar = 0
			}
		},
		cambiarEstado(estado, texto, event){
			if (event.target.classList.contains('active')) return false;
			if(confirm(`¿Deseas cambiar el estado a ${texto}?`)){
				this.axios('/api/cambiarEstadoMembresia/'+this.idMembresia+'/'+estado+'/'+this.congelar)
				.then(res=> {
					this.membresias.find(x=> this.idMembresia == x.id).estado = estado
					if(estado == 5)
						this.membresias.find(x=> this.idMembresia == x.id).fin = moment().add(this.congelar, 'days').format('YYYY-MM-DD')
				})
			}
		},
		voucherAcumulados(index){
			var sumasa = 0;
			this.membresias[index].pagados.forEach(pago => {
				sumasa += parseFloat(pago.price)
			});
			//window.location.href = `/api/cuponMembresia/${this.membresias[index].pagados[0].idMembresia}/${sumasa}`
			window.open( `/api/cuponMembresia/${this.membresias[index].pagados[0].idMembresia}/${sumasa}` , '_blank')

		},
		calcularMesesMembresia(){
			let hoy = moment().startOf('day');
			let fin = moment(this.membresiaActiva.fin, 'YYYY-MM-DD')

			if (fin.isBefore(hoy)) { //fecha es menor
				return false;
			} else if (fin.isAfter(hoy)) { //fecha es mayor
				return true;
			} else { // fecha es igual
			 	return true
			}

		},
		contarCitasActivas(){
			let contador = this.citas.filter(x=> x.status == '1' || x.status == '2').length
			return contador
		},
		tipoMembresia(membresia){
			if(membresia.meses > 0 ) return 'tiempo'
			if(membresia.sesiones > 0) return 'sesiones'
		},
		fechaLatam(fecha) { return moment(fecha).format('DD/MM/YYYY'); },
		horaLatam(horita){ return moment(horita, 'HH:mm:ss').format('hh:mm a') },
	},
	watch:{
		queId(){
			this.buscarMembresias();
		}
	}
}
</script>
<style scoped>
.btn-secondary:hover {
    color: #393939;
}</style>