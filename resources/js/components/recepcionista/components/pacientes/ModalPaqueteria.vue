<template>
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Programación de paquetes</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							<div class="col">
								<label for=""><strong>Paciente</strong></label>
								<p class="">{{ paciente.name }} {{ paciente.nombres }}</p>
							</div>
						</div>
						<div class="row mb-4">
							<div class="col">
								<label><strong>Modalidad</strong></label>
								<select class="form-select" id="sltModalidad" v-model="queTipo" @change="cambiarPaquetes()">
									<option value="0">Plan por meses</option>
									<option value="1">Plan por sesiones</option>
								</select>
							</div>
							<div class="col">
								<label><strong>Paquete</strong></label>
								<select class="form-select" id="sltPaquete" v-model="paquete.id">
									<template v-for="precio in precios">
										<option :value="precio.id">{{ precio.descripcion }}</option>
									</template>
								</select>
							</div>
						</div>
						<div class="row my-4">
							<div class="col-6">
								<label><strong>Profesional de Psicología</strong></label>
								<select class="form-select" id="sltPaquete" v-model="paquete.idProfesional" @change="buscarHorarioLibre()">
									<template v-for="profesional in profesionales">
										<option v-if="profesional.idProfesion == 2" :value="profesional.id">{{ profesional.name }}</option>
									</template>
								</select>
							</div>
							<div class="col">
								<label for=""><strong>Periodo</strong></label>
								<p class="mb-0">1 mes</p>
							</div>
						</div>
						<div class="row my-4">
							<div class="col">
								<label for=""><strong>Fecha</strong></label>
								<input type="date" class="form-control" v-model="paquete.fecha" @change="buscarHorarioLibre()">
							</div>
							<div class="col">
								<label for=""><strong>Horario</strong></label>
								<select class="form-select" id="sltHorario" v-model="paquete.idHora">
									<option value="0">Seleccione un horario</option>
									<option v-if="horario.length ==0 " value="-1">No hay horarios disponibles</option>
									<template v-for="hora in horario">
										<option :value="-1">{{ horaLatam(hora.check_time) }} - {{ horaLatam(hora.departure_date) }} </option>
									</template>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Crear paquete</button>
				</div>
			</div>
		</div>
	</div>

</template>
<script>
import moment from 'moment'
export default {
		name: 'ModalPaqueteria',
		props: ['paciente', 'profesionales'], //membresias:Array
		data(){ return {
			queTipo:'0', precios:[], membresias:[], horario:[],
			paquete:{
				id:-1, fecha: moment().format('YYYY-MM-DD'), idHora:0, idProfesional:-1
			}
		}},
		mounted(){
			this.preciosMembresias()
		},
		methods: {
			async preciosMembresias() {
				const servidor = await fetch('/api/preciosMembresias')
				this.membresias = await servidor.json();
				this.cambiarPaquetes()
			},
			async buscarHorarioLibre(){
				this.paquete.idHora = -1
				const servidor = await fetch(`/api/horasLibres/${this.paquete.idProfesional}/${this.paquete.fecha}`)
				this.horario = await servidor.json();
			},
			fechaLatam(fecha){ return moment(fecha).format('DD/MM/YYYY'); },
			horaLatam(horita){ return moment(horita, 'HH:mm:ss').format('hh:mm a') },
			cambiarPaquetes(){
				this.paquete.id=-1
				if(this.queTipo == '0')
					this.precios = this.membresias.filter(x=> parseInt(x.meses) >0)
				else
					this.precios = this.membresias.filter(x=> parseInt(x.sesiones) > 0)
			}
		}
	}
</script>