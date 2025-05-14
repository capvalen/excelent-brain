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
				<div class="accordion-item" v-for="(membresia, index) in membresias">
					<h2 class="accordion-header">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapse'+membresia.id" aria-expanded="false" :aria-controls="'#collapse'+membresia.id">
							{{membresia.descripcion}} - {{ fechaLatam(membresia.inicio) }}
						</button>
					</h2>
					<div :id="'collapse'+membresia.id" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
						<div class="accordion-body">
							<p class="mb-1"><strong>Tipo</strong> <span>{{ membresia.descripcion }}</span></p>
							<p class="mb-1 d-none"><strong>Estado</strong>
								<span v-if="membresia.estado==1" class="text-warning" >En espera</span>
								<span v-if="membresia.estado==2" class="text-success" >Activo</span>
								<span v-if="membresia.estado==3" class="text-danger" >Suspendido</span>
							</p>
							<p class="mb-1"><strong>Fecha de inicio</strong> <span>{{ fechaLatam(membresia.inicio) }}</span></p>
							<p class="mb-1"><strong>Fecha límite final</strong> <span>{{ fechaLatam(membresia.fin) }}</span></p>
							<p class="mb-1"><strong>N° cuotas</strong> <span>{{ membresia.cuotas }}</span></p>
							<p class="mb-1"><strong>Cuotas pagadas:</strong> (<span>{{ membresia.pagados.length }} cuotas</span>) <span v-if="membresia.pagados.length>0" class="badge bg-primary rounded-pull p-2 m-1" style="cursor: pointer;" title="Voucher de pagos acumulados" @click="voucherAcumulados(index)"><i class="far fa-sticky-note"></i></span></p>
							<p class="mb-1"><strong>Monto Total</strong> <span>S/ {{ parseFloat(membresia.monto).toFixed(2) }}</span></p>
							<p class="mb-1"><strong>Comentarios adicionales:</strong> <span>{{ membresia.comentarios }}</span></p>
							<ol class="list-group">
								<li class="list-group-item d-flex justify-content-between align-items-start" v-for="pagado in membresia.pagados">
									<div class="ms-2 me-auto">
										<div><span class="fw-bold">Pagado:</span> {{ fechaLatam(pagado.date) }} </div>
										<p class="mb-0">Monto: S/ {{ parseFloat(pagado.price).toFixed(2) }} </p>
									</div>
								</li>
							</ol>
							<p class="text-muted" v-if="membresia.pagados.length==0"><small>No hay pagos registrados aún</small></p>
							<p class="mt-2 mb-0">Cuotas pendientes de pago:</p>
							<ol class="list-group">
								<li class="list-group-item d-flex justify-content-between align-items-start" v-for="(deuda, indice) in membresia.deudas">
									<div class="ms-2 me-auto">
										<div><span class="fw-bold">Fecha de pago:</span> {{ fechaLatam(deuda.fecha) }} </div>
										<p class="mb-0">Monto: S/ {{ parseFloat(deuda.monto).toFixed(2) }} </p>
									</div>
									<span title="Ampliar fecha" class="badge bg-primary rounded-pill p-2 ms-1" @click="ampliarFechaDeuda(deuda.id, ampliacion=deuda.fecha)" data-bs-toggle="modal" data-bs-target="#modalAmpliarFechaMembresia" style="cursor: pointer;"><i class="far fa-clock"></i></span>
									<span title="Aplicar pago" class="badge bg-success rounded-pill p-2 ms-1" @click="pagarDeuda(index, indice)" style="cursor: pointer;"><i class="far fa-gem"></i></span>
								</li>
							</ol>
							<p v-if="membresia.deudas.length==0">No hay deudas pendientes</p>
							<button class="mt-2 btn btn-outline-secondary btn-sm" data-bs-target="#modalVerCitas" data-bs-toggle="modal" @click="pedirCitasMembresia(membresia.id)">Ver fechas de citas generadas</button>
							<button class="mt-2 btn btn-outline-danger btn-sm" @click="anular(index)">Anular membresía</button>
						</div>
					</div>
				</div>
				<div v-if="membresias.length==0">El paciente no tiene membresías asignadas</div>
			</div>
		</div>

		<!-- Modal para ampliar la fecha -->
		<div class="modal fade" id="modalVerCitas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog ">
				<div class="modal-content">
					<div class="modal-header border-0">
						<h1 class="modal-title fs-5" id="exampleModalLabel">Citas de Membresía</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="row mb-2">
							<div class="col d-flex d-grid justify-content-between">
								<button class="btn btn-outline-primary"  @click="activarFechas=true" data-bs-target="#modalProximaCita" data-bs-toggle="modal"><i class="fa-solid fa-plus"></i> Agregar cita</button>
								<span>0 de 6 Citas</span>
							</div>
						</div>
						<table class="table">
							<thead>
								<tr>
									<th>N°</th>
									<th>Fecha y hora</th>
									<th>Profesional</th>
									<th>Estado</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(cita, index) in citas">
									<td>{{ index+1 }}</td>
									<td>{{ cita.date }} {{ cita.schedule.check_time }}</td>
									<td>{{ cita.professional.nombre }}</td>
									<td>
										<span v-if="cita.status==1">Sin Confirmar</span>
										<span v-if="cita.status==2">Cita Confirmada </span>
										<span v-if="cita.status==3">Cita Anulada </span>
									</td>
								</tr>
							</tbody>
						</table>
						<p v-if="citas.length==0">No hay citas asociadas</p>
					</div>
				</div>
			</div>
		</div>

		<ModalAmpliarFechaMembresia :queCita ="queCita" :fechaBase="queFecha"></ModalAmpliarFechaMembresia>

		<ModalProximaCita :profesional="profesional" :paciente="paciente" :idMembresia="idMembresia" :idServicio="idServicio"></ModalProximaCita>
		
	</div>
</template>

<script>
import moment from 'moment';
import alertify from 'alertifyjs';
import ModalAmpliarFechaMembresia from './ModalAmpliarFechaMembresia.vue'
import ModalProximaCita from '../../../profesional/components/consultas/ModalProximaCita.vue'


export default{
	name:'ModalVerMembresias',
	props:['queId', 'nombrePaciente', 'idUser', 'paciente', 'profesional'],
	components:{ ModalAmpliarFechaMembresia, ModalProximaCita },
	data(){return {
		membresias:[], ampliacion:null, queDeuda:null, citas:[], queFecha:null, queCita:null, activarFechas:false, idMembresia:null, idPrecio: null, idServicio:null
	}},
	mounted(){
		//this.buscarMembresias()
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
			const swalWithBootstrapButtons = this.$swal.mixin({
				customClass: {
					confirmButton: 'btn btn-success',
					cancelButton: 'btn btn-danger mx-2'
				},
				buttonsStyling: false
			})

			swalWithBootstrapButtons.fire({
				title: `¿Deseas realizar un pago de la deuda de S/ ${this.membresias[index].deudas[indice].monto}?`,
				text: "Se pondrá en caja automáticamente",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Si, pagar deuda',
				cancelButtonText: 'Cancelar',
				reverseButtons: true
			}).then((result) => {
				if (result.isConfirmed){
					let datos = new FormData();
					datos.append('idDeuda', this.membresias[index].deudas[indice].id)
					datos.append('idMembresia', this.membresias[index].id)
					datos.append('user_id', this.idUser)
					datos.append('nombre', this.nombrePaciente)
					datos.append('precio', this.membresias[index].deudas[indice].monto)
					datos.append('tipo', this.membresias[index].tipo)
					datos.append('observación', this.membresias[index].motivo)
					datos.append('estado', 2)
					
					this.axios.post('/api/pagarDeudaMembresia', datos)
					.then(res =>{
						console.log(res.data)
						this.buscarMembresias();
						swalWithBootstrapButtons.fire( 'Deuda Pagada', '', 'success' )
					})
				}
			})
		},
		pedirCitasMembresia(id, index){
			this.idMembresia = id
			this.idServicio = this.membresias.find(x=> id == x.id).tipo
			this.axios('/api/pedirCitasMembresia/'+id)
			.then(res=> this.citas = res.data )
		},
		voucherAcumulados(index){
			var sumasa = 0;
			this.membresias[index].pagados.forEach(pago => {
				sumasa += parseFloat(pago.price)
			});
			//window.location.href = `/api/cuponMembresia/${this.membresias[index].pagados[0].idMembresia}/${sumasa}`
			window.open( `/api/cuponMembresia/${this.membresias[index].pagados[0].idMembresia}/${sumasa}` , '_blank')

		},
		fechaLatam(fecha) { return moment(fecha).format('DD/MM/YYYY'); },
	},
	watch:{
		queId(){
			this.buscarMembresias();
		}
	}
}
</script>