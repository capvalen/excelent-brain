<template>
    <div>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Pagos en caja</h1>
            <div class="d-flex align-items-center d-print-none">
                <input type="date" class="form-control" @change="selectDate" v-model="fecha">
            </div>
    </div>
    <div class="card px-1 pt-2 ">
			<div class="m-4 d-print-none">
				<button class="btn btn-outline-success d-none" @click="exportar()"><i class="fas fa-file-excel"></i> Exportar a Excel</button>
				<button class="btn btn-outline-primary" @click="membresias()" data-bs-target="#modalMembresias" data-bs-toggle="modal"><i class="fa-solid fa-arrow-up-right-dots"></i> Ingresar membresía</button>
				<button data-bs-toggle="modal" data-bs-target="#pagoExtras" class="btn btn-outline-secondary ms-auto"><i class="fas fa-plus"></i> Ingresos extras</button>
				<button data-bs-toggle="modal" data-bs-target="#egresosExtras" class="btn btn-outline-danger"><i class="fas fa-minus"></i> Egresos extras</button>
				<button class="btn btn-outline-secondary " v-if="consultarFecha()" @click="verTicketCierre()"> <i class="fas fa-print"></i> Ticket de cierre</button>
				<button class="btn btn-outline-secondary border-0" @click="actualizar()"> <i class="fas fa-sync"></i> Actualizar</button>
				
				
			</div>

			<table class="table table-hover mt-1 " id="table_export">
        <thead class="">
					<tr class="">
						<th class="text-primary" colspan="15"><i class="fas fa-angle-right"></i> Cuadro de entradas de dinero</th>
					</tr>
					<tr >
						<td class="text-primaryd-print-none" v-if="tienePrivilegios=='1'">@</td>
						<td class="text-primary">N°</td>
						<td class="text-primary"><i class="far fa-user"></i> | Registro</td>
						<td class="text-primary">Fact. Bol.</td>
						<td class="text-primary">Ticket</td>
						<td class="text-primary">Cliente</td>
						<td class="text-primary">Tipo</td>
						<td class="text-primary">Obs.</td>
						<td class="text-primary">Monto</td>
						<td class="text-primary">Motivo</td>
						<td class="text-primary">Medio de pago</td>
						<td class="text-primary">N° Op.</td>
						<td class="text-primary">Prof.</td>
						<td class="text-primary">Fecha y Hora</td>
						<td class="text-primary d-print-none" style="white-space: nowrap">@</td>
					</tr>
        </thead>
        <tbody>
            <tr v-for="(payment, index) in payments" >
							<td class="d-print-none" v-if="tienePrivilegios=='1'">
								<button class="btn btn-sm btn-outline-danger" @click="mostrarModalBorrar(payment.id, index)" data-bs-toggle="modal" data-bs-target="#modalMotivoBorrar" ><i class="fa-solid fa-xmark"></i></button>
							</td>
							<td>
								<span>{{index+1}}</span>
							</td>
							<td v-if="payment.usuario" style="white-space: nowrap;" :title="payment.usuario.nombre" data-bs-toggle="tooltip" data-bs-placement="top" :data-bs-title="payment.usuario.nombre" ><i class="far fa-user"></i> {{payment.created_at | formatedDate}}</td>
							<td v-else style="white-space: nowrap;" :title="payment.user.nombre" data-bs-toggle="tooltip" data-bs-placement="top" :data-bs-title="payment.user.nombre"> <i class="far fa-user"></i> {{payment.created_at | formatedDate}}</td>
							<td>{{payment.voucher}}</td>
							<td>{{ payment.id}}</td>
							<td class="text-capitalize">{{ payment.customer }} <span v-if="payment.observation!=''"></span></td>
							<!-- <td v-if="payment.pay_status == 1">Sin cancelar</td>
								<td v-else-if="payment.pay_status == 2">Cancelado</td> -->
							<td>
								<span v-if="payment.continuo=='1'">N</span>
								<span v-else-if="payment.continuo=='2'">C</span>
								<span v-else-if="payment.continuo=='3'">M</span>
								<span v-else>
									<span v-if="payment.continuo=='-1'">X</span>
									<span v-if="payment.continuo==null">X</span>
								</span>
							</td>
							<td class="text-capitalize">
								<span> {{ payment.observation }} </span>
								<span v-if="payment.descuento>0" > {{ payment.motivoDescuento }} S/ {{ parseFloat(payment.descuento).toFixed(2) }} </span>
								<span v-if="payment.rebaja>0" > {{ payment.motivoRebaja }} S/ {{ parseFloat(payment.rebaja).toFixed(2) }} </span>
							</td>
							<td :class="{'text-danger' : payment.type==6, 'text-primary': payment.type!=6}">S/ <span v-if="payment.type==6">-</span> {{ retornarFloat(payment.price)}}</td>
							<td>
								<span v-if="payment.type==8">Adelanto de cita</span>
								<span v-if="payment.type==7">Pago de membresía</span>
								<span v-if="payment.type==5">Pago de cita</span>
								<span v-if="payment.type==4">Otros</span>
								<span v-if="payment.type==3">Informe</span>
								<span v-if="payment.type==2">Paquete Kurame</span>
								<span v-if="payment.type==1">Paquete Membresía</span>
								<span v-if="payment.type==0">Certificado</span>
								<span v-if="payment.type==15">Pago de membresía</span>
								<span v-if="payment.type==16">Revaluación gratuita</span>
								<small v-if="payment.type!=4"><br>{{ payment.detalle }}</small>
							</td>
							<td class="text-capitalize"> <span>{{queMoneda(payment.moneda)}}</span> </td>
							<td>{{ payment.voucher_issued }} </td>
							<td>{{ payment.profesional_name }}</td>
							<td>{{ fechaLatam(payment.fechaCita) }} {{ payment.horario }}</td>
							<td class="d-print-none" style="white-space: nowrap">
								<button class="btn btn-outline-success btn-sm" data-bs-toggle="offcanvas" data-bs-target="#offAdjunto"  @click="verAdjunto(payment.id)" title="Adjuntar archivo"><i class="far fa-file"></i></button>
								<button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditarPago" @click="editar(index)" v-if="consultarFecha()"><i class="fa-solid fa-pen-to-square"></i></button>
								<!-- <a v-if="payment.appointment_id!==0" target="_blank" :href="`/api/pdfCupon/${payment.appointment_id}`" class="btn btn-danger btn-sm"><i class="fa-solid fa-file-pdf"></i> PDF</a> -->
								<a target="_blank" :href="`/api/pdfExtraCupon/${payment.id}`" class="btn btn-danger btn-sm"><i class="fa-solid fa-file-pdf"></i> PDF</a>
							</td>
            </tr>
        </tbody>
				<tfoot>
					<tr v-for="tipo in sumaTipos">
						<th colspan="7"></th>
						<th>{{ tipo.moneda }}</th>
						<th>S/ {{ tipo.suma.toFixed(2) }}</th>
						<th></th>
					</tr>
					<tr>
						<th colspan="7"></th>
						<th>Total</th>
						<th>S/ {{ parseFloat(suma).toFixed(2) }}</th>
						<th></th>
					</tr>
				</tfoot>
   	 </table>
			<table class="table table-hover w-100 mt-1" id="table_export2">
				<thead class="">
					<tr class="">
						<th class="text-warning" colspan="15"><i class="fas fa-angle-right"></i> Cuadro de salidas de dinero</th>
					</tr>
					<tr>
						<td class="text-warning d-print-none" v-if="tienePrivilegios=='1'">@</td>
						<td class="text-warning">N°</td>
						<td class="text-warning"><i class="far fa-user"></i> | Registro</td>
						<td class="text-warning">Fact. Bol.</td>
						<td class="text-warning">Ticket</td>
						<td class="text-warning">Cliente - Motivo</td>
						<td class="text-warning">Obs.</td>
						<td class="text-warning">Monto</td>
						<td class="text-warning">Motivo</td>
						<td class="text-warning">Medio de pago</td>
						<td class="text-warning">N° Op.</td>
						<td class="text-warning">Hora</td>
						<td class="text-warning d-print-none">@</td>
					</tr>
        </thead>
        <tbody>
            <tr v-for="(payment, index) in salidas">
							<td class="d-print-none" v-if="tienePrivilegios=='1'">
								<button class="btn btn-sm btn-outline-danger" @click="mostrarModalBorrar(payment.id, index)" data-bs-toggle="modal" data-bs-target="#modalMotivoBorrar" ><i class="fa-solid fa-xmark"></i></button>
							</td>
							<td>
								<span>{{index+1}}</span>
							</td>
							<td v-if="payment.usuario" style="white-space: nowrap;" :title="payment.usuario.nombre" data-bs-toggle="tooltip" data-bs-placement="top" :data-bs-title="payment.usuario.nombre" ><i class="far fa-user"></i> {{payment.created_at | formatedDate}}</td>
							<td v-else style="white-space: nowrap;" title="Sin datos" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Sin datos">{{payment.created_at | formatedDate}}</td>
							<td>{{payment.voucher}}</td>

							<td>{{ payment.id}}</td>
							<td class="text-capitalize">{{ payment.customer }} <span v-if="payment.observation!=''"></span></td>
							<!-- <td v-if="payment.pay_status == 1">Sin cancelar</td>
								<td v-else-if="payment.pay_status == 2">Cancelado</td> -->
							<td class="text-capitalize">
								<span> {{ payment.observation }} </span>
								<span v-if="payment.descuento>0" > {{ payment.motivoDescuento }} S/ {{ parseFloat(payment.descuento).toFixed(2) }} </span>
								<span v-if="payment.rebaja>0" > {{ payment.motivoRebaja }} S/ {{ parseFloat(payment.rebaja).toFixed(2) }} </span>
							</td>
							<td :class="{'text-danger' : payment.type==6, 'text-primary': payment.type!=6}">S/ <span v-if="payment.type==6">-</span> {{ retornarFloat(payment.price)}}</td>
							<td>
								
								<span v-if="payment.type==6">Salida de dinero</span>
							</td>
							<td class="text-capitalize"> <span>{{queMoneda(payment.moneda)}}</span> </td>
							<td>{{ payment.voucher_issued }}</td>
							<td>{{ horaLatam(payment.created_at) }}</td>
							<td class="d-print-none" style="white-space: nowrap">
								<button class="btn btn-outline-success btn-sm" data-bs-toggle="offcanvas" data-bs-target="#offAdjunto"  @click="verAdjunto(payment.id)" title="Adjuntar archivo"><i class="far fa-file"></i></button>
								<button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditarPago" @click="editar(index)"><i class="fa-solid fa-pen-to-square"></i></button>
								<a v-if="payment.appointment_id!==0" target="_blank" :href="`/api/pdfCupon/${payment.appointment_id}`" class="btn btn-danger btn-sm"><i class="fa-solid fa-file-pdf"></i> PDF</a>
								<a target="_blank" :href="`/api/pdfExtraCupon/${payment.id}`" class="btn btn-danger btn-sm"><i class="fa-solid fa-file-pdf"></i> PDF</a>
							</td>
            </tr>
        </tbody>
				<tfoot>
					<tr v-for="tipo in sumaSalidas">
						<th colspan="7"></th>
						<th>{{ tipo.moneda }}</th>
						<th>S/ {{ tipo.suma.toFixed(2) }}</th>
						<th></th>
					</tr>
					<tr>
						<th colspan="7"></th>
						<th>Total</th>
						<th>S/ {{ parseFloat(sumaSal).toFixed(2) }}</th>
						<th></th>
					</tr>
				</tfoot>
   	 </table>
			<p class="mt-2 text-danger" v-if="eliminados.length>0"><strong>Pagos eliminados</strong></p>
			<table class="table table-hover w-100 mt-1" id="table_eliminados" v-if="eliminados.length>0">
				<thead class="">
					<tr class="">
						<th class="text-danger" colspan="15"><i class="fas fa-angle-right"></i>  Cuadro anulados</th>
					</tr>
					<tr>
						<td class="text-danger d-print-none" v-if="tienePrivilegios=='1'">@</td>
						<td class="text-danger">N°</td>
						<td class="text-danger"><i class="far fa-user"></i> | Registro</td>
						<td class="text-danger">Fact. Bol.</td>
						<td class="text-danger">Ticket</td>
						<td class="text-danger">Cliente - Motivo</td>
						<td class="text-danger">Tipo</td>
						<td class="text-danger">Obs.</td>
						<td class="text-danger">Monto</td>
						<td class="text-danger">Moneda</td>
						<td class="text-danger">Medio de pago</td>
						<td class="text-danger">N° Op.</td>
						<td class="text-danger">Hora</td>
						<td class="text-danger d-print-none">@</td>
					</tr>
        </thead>
				<tbody>
					<tr v-for="(payment, index) in eliminados">
						<td>
							<span>{{index+1}}</span>
						</td>
						<td v-if="payment.usuario" style="white-space: nowrap;" :title="payment.usuario.nombre" data-bs-toggle="tooltip" data-bs-placement="top" :data-bs-title="payment.usuario.nombre" ><i class="far fa-user"></i> {{payment.created_at | formatedDate}}</td>
						<td v-else style="white-space: nowrap;" title="Sin datos" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Sin datos">{{payment.created_at | formatedDate}}</td>
						<td>{{payment.voucher}}</td>

						<td>{{ payment.id}}</td>
						<td class="text-capitalize">{{ payment.customer }} <span v-if="payment.observation!='' && payment.observacion!=null"> <br />Obs. {{ payment.observation }}</span> <span class="text-danger"><br><strong>Motivo: </strong>{{payment.razon}}</span></td>
						<!-- <td v-if="payment.pay_status == 1">Sin cancelar</td>
							<td v-else-if="payment.pay_status == 2">Cancelado</td> -->
						<td>
							<span v-if="payment.continuo=='1'">N</span>
							<span v-else-if="payment.continuo=='2'">C</span>
							<span v-else-if="payment.continuo=='3'">M</span>
							<span v-else>
								<span v-if="payment.continuo=='-1'">X</span>
								<span v-if="payment.continuo==null">X</span>
							</span>
						</td>
						<td>{{ payment.observation }}</td>
						<td :class="{'text-danger' : payment.type==6, 'text-primary': payment.type!=6}">S/ <span v-if="payment.type==6">-</span> {{ retornarFloat(payment.price)}}</td>
						<td>
							<span v-if="payment.type==6">Salida de dinero</span>
							<span v-if="payment.type==5">Pago de cita</span>
							<span v-if="payment.type==3">Informe</span>
							<span v-if="payment.type==2">Paquete Kurame</span>
							<span v-if="payment.type==1">Paquete Membresía</span>
							<span v-if="payment.type==0">Certificado</span>
						</td>
						<td class="text-capitalize"> <span>{{queMoneda(payment.moneda)}}</span> </td>
						<td>{{ payment.voucher_issued }}</td>
						<td>{{ payment.profesional_name }}</td>
						<td>{{ horaLatam(payment.created_at) }}</td>
						<td class="d-print-none" style="white-space: nowrap">
								<button class="btn btn-outline-success btn-sm" data-bs-toggle="offcanvas" data-bs-target="#offAdjunto"  @click="verAdjunto(payment.id)" title="Adjuntar archivo"><i class="far fa-file"></i></button>
							<a v-if="payment.appointment_id!==0" target="_blank" :href="`/api/pdfCupon/${payment.appointment_id}`" class="btn btn-danger btn-sm"><i class="fa-solid fa-file-pdf"></i> PDF</a>
							<a v-else target="_blank" :href="`/api/pdfExtraCupon/${payment.id}`" class="btn btn-danger btn-sm"><i class="fa-solid fa-file-pdf"></i> PDF</a>
						</td>
					</tr>
        </tbody>
			</table>

    </div>

		<!-- Modal -->
		<div class="modal fade" id="modalMotivoBorrar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="exampleModalLabel">Borrar pago</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p class="mb-0">Esta a punto de eliminar el pago:</p>
						<p class="fst-italic mb-0 text-capitalize">{{ contenido }}</p>
						<p>Ingrese una razón para borrar</p>
						<input type="text" class="form-control" v-model="razon" autocomplete="off">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" @click="borrarPagoExtra()"><i class="fa-solid fa-trash"></i> Eliminar pago</button>
					</div>
				</div>
			</div>
		</div>
		<ModalMembresias :idUsuario="idUsuario"></ModalMembresias>

		<!-- Modal -->
		<div class="modal fade" id="modalEditarPago" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="exampleModalLabel">Editar pago</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="form-group row">                                                    
							<div class="col-sm-12">
								<label for="">Método de pago</label>
								<select class="form-select" id="pay_status" name="pay_status" v-model="caso.moneda">
									<option v-for="moneda in monedas" :value="moneda.id">{{moneda.tipo}}</option>
								</select>
							</div>
							<div class="col-sm-12">
								<label for="">N° de Operación</label>
								<input type="text" class="form-control" v-model="caso.comprobante">
							</div>
							<div class="col-sm-12">
								<label for="">Tipo de comprobante</label>
								<select class="form-select" id="sltTipoComprobante" v-model="caso.tipo">
									<option value="1">Factura</option>
									<option value="4">Contrato</option>
									<option value="3">Boleta de venta</option>
									<option value="2">Recibo por honorarios</option>
									<option value="-1">Ninguno</option>
								</select>
							</div>
							<div class="col-sm-12" v-show="caso.tipo!=-1">
								<label for="">Boleta / Factura</label>
								<input type="text" class="form-control" v-model="caso.boleta">
							</div>
						
							<div class="form-group">
								<label for="">Observación</label>
								<textarea class="form-control" name="observation" id="observation" cols="10" rows="2" v-model="caso.observacion"></textarea>
							</div>
						</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal" @click="editarPagoExtra()"><i class="fa-solid fa-rotate-right"></i> Actualizar pago</button>
					</div>
				</div>
				</div>
			</div>
		</div>
		
		<modal-pagos-extras :idUsuario="$attrs.idUser" />
    <modal-egresos-extras :idUsuario="$attrs.idUser" :nombreUser="$attrs.nombreUser" />
		<OffcanvasAdjuntos :id="idSeleccionado" :foto="foto" :habilitarEliminado="habilitarEliminado" ></OffcanvasAdjuntos>
	</div>
</template>

<script>
import ModalMembresias from "./ModalMembresias.vue"
import ModalPagosExtras from './../citas/ModalPagosExtras.vue'
import ModalEgresosExtras from './../citas/ModalEgresosExtras.vue'
import OffcanvasAdjuntos from './OffcanvasAdjuntos.vue'
import moment from 'moment'

	export default{
		data(){
			return{
				payments:[], sumaTipos:[], sumaSalidas:[], salidas:[], monedas:['Efectivo', 'Depósito bancario',  'POS', 'Aplicativo Yape', 'Banco: BCP', 'Banco: BBVA', 'Banco: Interbank', 'Banco: Nación', 'Banco: Scotiabank', 'Aplicativo Plin', 'Open pay'], idSeleccionado:-1,
				idUsuario: null, tienePrivilegios: null, razon:'', queId:null, queINdex:null, contenido:'', eliminados:[], caso:{id:-1,index:-1,moneda:1, boleta:'', comprobante:'', observacion:'', tipo:-1}, foto:'', habilitarEliminado:false, fecha:moment().format('YYYY-MM-DD'), monedas:[]
			}
		},
		name: 'HomePagos',
		props:{},
		components:{ ModalMembresias, ModalPagosExtras, ModalEgresosExtras, OffcanvasAdjuntos },
		methods:{
			verAdjunto(id){
				this.idSeleccionado=id;
				this.axios.get('/api/verAdjuntoPago/'+id)
				.then(res=> {this.foto = res.data.archivo ? res.data.archivo[0].file : '' } )
			},
				getAllExtraPayments(){
					this.habilitarEliminado=true;
					this.foto=''
					this.axios.get('/api/getAllExtraPayments')
						.then(res =>{ console.log(res.data)
							this.payments = res.data.activos
							this.salidas = res.data.salidas
							this.eliminados = res.data.eliminados
						})
					.finally(()=>{
						const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
						const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
					})
				},
				selectDate(e){
					this.habilitarEliminado = (e.target.value == moment().format('YYYY-MM-DD') ) ? true : false;
					this.foto=''
					this.axios.get(`/api/getExtraPaymentsByDay/${e.target.value}`)
					.then(res =>{ console.log(res.data)
						this.payments = res.data.activos
						this.salidas = res.data.salidas
						this.eliminados = res.data.eliminados
					})
				},
				exportar(){
					var table2excel = new Table2Excel();
					table2excel.export(document.getElementById('table_export'), `Caja de ${moment().format('DD-MM-YYYY hh-mm a')}`);
				},
				horaLatam(horita){
					return moment(horita).format('hh:mm a')
				},
				retornarFloat(price){
					if (price==null || price == undefined){ return '0.00'}else{
						return parseFloat(price).toFixed(2)
					}
				},
				mostrarModalBorrar(id, index){
					this.queId = id; this.queINdex = index;
					this.contenido = `${this.payments[index].customer} ${this.payments[index].observation??''}`;
				},
				borrarPagoExtra(){
					if(this.tienePrivilegios==1){
						if(this.razon==''){
							this.$swal({ icon:'error', title: 'Faltan rellenar una razón' });
						}
						else{
							this.axios.post('/api/borrarPagoExtra',{ id: this.queId, razon:this.razon})
							.then(res=> { console.log(res.data);
								this.razon='';
								if(res.data.mensaje){
									this.payments.splice(this.queIndex, 1)
									this.$swal({title: 'Elimado con éxito'})
								}
							})

						}
					}
				},
				fechaLatam(fecha) {
					if(fecha) return moment(fecha).format('DD/MM/YYYY');
				},
				editar(index){
					this.caso.index =index
					this.caso.id = this.payments[index].id
					this.caso.moneda = this.payments[index].moneda
					this.caso.boleta = this.payments[index].voucher
					this.caso.comprobante = this.payments[index].voucher_issued
					this.caso.observacion = this.payments[index].observation
				},
				editarPagoExtra(){
					this.axios.post('/api/editarPagoExtra', this.caso)
					.then(res=> {
						if(res.data.msg=='success'){
							this.$swal({title: 'Actualizado con éxito'})
							this.payments[this.caso.index].id = this.caso.id
							this.payments[this.caso.index].moneda = this.caso.moneda
							this.payments[this.caso.index].voucher = this.caso.boleta
							this.payments[this.caso.index].voucher_iss = this.caso.comprobante
							this.payments[this.caso.index].observation = this.caso.observacion
							this.payments[this.caso.index].tipo= this.caso.tipo
						}
						this.caso.tipo=-1
					})
				},
				actualizar(){ this.getAllExtraPayments() },
				consultarFecha(){
					return this.fecha == moment().format('YYYY-MM-DD')
				},
				verTicketCierre(){ window.open('/api/ticketCierreCaja/'+this.fecha+'/'+this.$attrs.nombreUser, '_blank'); },
				queMoneda(idMoneda){
					return this.monedas.find(x=> x.id == idMoneda)?.tipo
				}
		},
		mounted(){
			this.getAllExtraPayments()
			this.axios.get('/api/user')
			.then((res) => {
				this.idUsuario = res.data.user.id
				this.tienePrivilegios = res.data.user.privilegios
			})
			this.axios.get("/api/listarMonedas/")
			.then(resp => this.monedas = resp.data)
		},
		filters:{
			formatedDate(date){
				return moment(date).format('DD/MM/yyyy')
			}
		},
		computed:{
			suma: function (){
				this.sumaTipos=[]
				if(this.payments.length>0){
					return this.payments.reduce((suma, item)=>{
						console.log(item);
						let queIndex= this.sumaTipos.findIndex(x=> x.moneda== this.queMoneda(item.moneda) );
						if( queIndex>-1 ){ //encuentra
							if( item.type==6)
								this.sumaTipos[queIndex].suma-= parseFloat(item.price ?? 0)
							else
								this.sumaTipos[queIndex].suma+= parseFloat(item.price ?? 0)
						}else{
							if( item.type==6 )
								this.sumaTipos.push({suma: -parseFloat(item.price ?? 0), moneda: this.queMoneda(item.moneda)})
							else
								this.sumaTipos.push({suma: parseFloat(item.price ?? 0), moneda: this.queMoneda(item.moneda)})
						}
						//console.log(item.type==6);
						
						if(item.type==6){
							return suma- parseFloat(item.price??0)
						}else{
							return suma+ parseFloat(item.price??0)
						}
						
					}, 0)
				}else{
					return 0;
				}
			},
			sumaSal: function (){
				this.sumaSalidas=[]
				if(this.salidas.length>0){
					return this.salidas.reduce((suma, item)=>{
						let queIndex= this.sumaSalidas.findIndex(x=> x.moneda== this.queMoneda(item.moneda ));
						//console.log(queIndex);
						if( queIndex>-1 ){ //encuentra
							if( item.type==6)
								this.sumaSalidas[queIndex].suma-= parseFloat(item.price ?? 0)
							else
								this.sumaSalidas[queIndex].suma+= parseFloat(item.price ?? 0)
						}else{
							if( item.type==6 )
								this.sumaSalidas.push({suma: -parseFloat(item.price ?? 0), moneda: this.queMoneda(item.moneda)})
							else
								this.sumaSalidas.push({suma: parseFloat(item.price ?? 0), moneda: this.queMoneda(item.moneda)})
						}
						//console.log(item.type==6);
						
						if(item.type==6){
							return suma- parseFloat(item.price??0)
						}else{
							return suma+ parseFloat(item.price??0)
						}
						
					}, 0)
				}else{
					return 0;
				}
			}
		}
	}
</script>
<style>
</style>