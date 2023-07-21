<template>
	<!-- Modal -->
<div class="modal fade" id="modalMembresias" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Membresías</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <section v-show="vista=='buscar'">
					<label for="">Ubique al paciente por DNI o Nombres</label>
					<input type="text" class="form-control" v-model="txtBusqueda" @keyup.enter="searchHistoria()">
					<div v-if="pacientes.length>0">
						<p>Pacientes encontrados:</p>
						<ol class="list-group list-group-flush">
							<li class="list-group-item" v-for="(paciente, index) in pacientes">
								<div class="d-flex w-100 justify-content-between">
									<div class="fw-bold text-capitalize">
										<span>{{paciente.name.toLowerCase()}} <span class="badge text-bg-secondary">{{paciente.etiqueta}}</span></span>
									</div>
									<button class="btn btn-sm" title="Agregar Membresía" @click="cambiarVista('membresia', index)"><i class="fa-solid fa-angles-right"></i></button>
								</div>
								<p class="mb-1">{{paciente.dni}}</p>
								<small><i class="fa-solid fa-mobile-screen"></i> {{ paciente.phone }} </small>
							</li>
						</ol>
					</div>
				</section>
				<section v-show="vista == 'membresia'">
					<p><a href="#!" @click="cambiarVista('buscar', -1)" ><i class="fa-solid fa-angles-left"></i> Volver</a></p>
					<p class="mb-0 text-capitalize"><strong>Paciente</strong> {{ pacienteElegido.name }}</p>
					<p class="mb-0"><strong>D.N.I</strong> {{ pacienteElegido.dni }}</p>
					<p class="mb-0"><strong>Celular</strong> {{ pacienteElegido.phone }}</p>
					<p class="mb-0"><strong>Última atención</strong> {{ pacienteElegido.etiqueta }}</p>
					<label class="mt-3" for="">Tipo de membresía</label>
					<select name="" class="form-select" v-model="membresia.tipo" @change="calcularFechas()">
						<option v-for="precio in precios" :value="precio.id">{{ precio.descripcion }}</option>
					</select>
					<div class="row">
						<div class="col-6">
							<label class="mb-0 mt-2 " for="">N° Cuotas</label>
							<input type="number" class="form-control" v-model="membresia.cuotas" @change="calcularFechas()">
						</div>
						<div class="col-6">
							<label class="mb-0 mt-2 " for="">Última sesión <small>({{cuentaMes}})</small></label>
							<input type="date" class="form-control" v-model="membresia.fin">
						</div>
					</div>
					<!-- <div class="d-grid col-8 ms-auto">
						<button class="btn btn-outline-primary float-end mt-3" @click="calcularFechas()"><i class="fa-regular fa-handshake"></i> Calcular montos y fechas</button>
					</div> -->
					
					<table class="table table-hover table-borderless" v-show="fechas.length>0">
						<thead>
							<tr>
								<th class="pb-1">Fecha de pago</th>
								<th class="pb-1">Monto</th>
								<th class="pb-1">¿Paga?</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(fecha, index) in fechas">
								<td> <input type="date" class="form-control" v-model="fecha.dia"> </td>
								<td>{{ parseFloat(fecha.monto).toFixed(2) }}</td>
								<td>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="" :id="'flexPago'+index" v-model="fecha.pago">
										<label class="form-check-label" :for="'flexPago'+index">
											<span v-if="fecha.pago">Si</span>
											<span v-else>No</span>
										</label>
									</div>
								</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<th>Total a pagar</th>
								<th>S/ {{ mostrarPrecio }}</th>
							</tr>
						</tfoot>
					</table>

					<button class="btn btn-outline-primary" data-bs-dismiss="modal" @click="guardar()"><i class="fa-regular fa-floppy-disk"></i> Guardar membresía</button>
				</section>
      </div>
      
    </div>
  </div>
</div>
</template>
<script>
import moment from 'moment'
import alertify from 'alertifyjs';
export default{
	name: 'ModalMembresias',
	data(){ return{
		txtBusqueda:'', pacientes:[], vista:'buscar', indexGlobal:null, pacienteElegido:{}, precios:[], membresia:{tipo:15, cuotas:1, precio: 0, fin: moment().add(1, 'month').format('YYYY-MM-DD')}, fechas:[]
	}},
	props:['idUsuario'],
	methods:{
		async searchHistoria () {
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
      })
      .catch(err => {
        console.error(err)
      })

    },
		cambiarVista(vista, index){
			this.vista = vista;
			if(index>-1){
				this.indexGlobal = index;
				this.pacienteElegido = this.pacientes[index];
				this.calcularFechas();
			}
		},
		async preciosMembresias(){
			const servidor = await fetch('/api/preciosMembresias')
			this.precios = await servidor.json();
		},
		calcularFechas(){
			this.fechas = [];
			const precioBase = this.mostrarPrecio;
			const precioParcial = precioBase / this.membresia.cuotas
			var hoy = moment()
			this.membresia.precio = precioBase;
			for( let i =0; i< this.membresia.cuotas; i++ ){
				this.fechas.push({
					dia: hoy.format('YYYY-MM-DD'),
					monto: precioParcial,
					total: precioBase,
					pago: false
				})
				hoy = moment(hoy).add(1, 'month')
			}
		},
		async guardar(){
			if(this.membresia.cuotas<=0) alertify.notify('<i class="fa-solid fa-bomb"></i> El número de cuotas mínimo debe ser 1', 'danger', 10);
			else if(!this.membresia.fin) alertify.notify('<i class="fa-solid fa-bomb"></i> Ingrese el último día de la memebresía', 'danger', 10);
			else{
				let datos = new FormData();
				datos.append('idPaciente', this.pacienteElegido.id)
				datos.append('customer', this.pacienteElegido.name)
				datos.append('motivo', this.pacienteElegido.id)
				datos.append('membresia', JSON.stringify(this.membresia))
				datos.append('user_id', this.idUsuario ),
				datos.append('fechas', JSON.stringify(this.fechas))
				const servidor = await fetch('/api/guardarMembresia',{
					method:'POST', body: datos})
				const respuesta = await servidor.json();
				if(respuesta.mensaje){
					this.pacienteElegido=[]
					this.fechas=[]
					alertify.notify('<i class="fa-regular fa-calendar-check"></i> Membresía guardada' , 'success', 10);
				}else
					alertify.notify('<i class="fa-regular fa-bomb"></i> Hubo un error guardando' , 'danger', 10);
			}
		},
		fechaLatam(fecha){
			return moment(fecha).format('DD/MM/YYYY');
		}
	},
	computed:{
		mostrarPrecio(){
			return parseFloat(this.precios.find(x=> x.id == this.membresia.tipo ).nuevos).toFixed(2)
		},
		cuentaMes(){
			moment.locale('es')
			return moment(this.membresia.fin).fromNow()
		}
	},
	mounted(){
		this.preciosMembresias();
	}
}</script>