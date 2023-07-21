<template>
    <div>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Pagos</h1>
            <div class="d-flex align-items-center">
                <input type="date" class="form-control" @change="selectDate">
            </div>
    </div>
    <div class="card px-1 pt-2">
			<div class="m-4">
				<button class="btn btn-outline-success" @click="exportar()"><i class="fas fa-file-excel"></i> Exportar a Excel</button>
				<button class="btn btn-outline-primary" @click="membresias()" data-bs-target="#modalMembresias" data-bs-toggle="modal"><i class="fa-solid fa-arrow-up-right-dots"></i> Ingresar membresía</button>
			</div>
			<p class="mt-2"><strong>Cuadre de caja: Pagos, Entradas y Salidas</strong></p>

			<table class="table table-striped w-100 mt-1" id="table_export">
        <thead class="bg-success text-white">
            <tr>
							<td v-if="tienePrivilegios=='1'">@</td>
                <td>N°</td>
                <td>Fecha</td>
                <td>Fact. Bol.</td>
                <td>Cita</td>
                <td>Cliente - Motivo</td>
                <td>N/C</td>
								<td>Obs.</td>
                <td>Monto</td>
                <td>Motivo</td>
                <td>Medio de pago</td>
                <td>N° Op.</td>
                <td>Prof.</td>
                <td>Hora</td>
                <td>Ticket</td>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(payment, index) in payments">
							<td v-if="tienePrivilegios=='1'">
								<button class="btn btn-sm btn-outline-danger" @click="mostrarModalBorrar(payment.id, index)" data-bs-toggle="modal" data-bs-target="#modalMotivoBorrar" ><i class="fa-solid fa-xmark"></i></button>
							</td>
							<td>
								<span>{{index+1}}</span>
							</td>
							<td>{{payment.created_at | formatedDate}}</td>
							<td>{{payment.voucher}}</td>

							<td>{{ payment.id}}</td>
							<td class="text-capitalize">{{ payment.customer }} <span v-if="payment.observation!=''"></span></td>
							<!-- <td v-if="payment.pay_status == 1">Sin cancelar</td>
								<td v-else-if="payment.pay_status == 2">Cancelado</td> -->
							<td>
								<span v-if="payment.continuo=='1'">N</span>
								<span v-if="payment.continuo=='2'">C</span>
								<span v-if="payment.continuo=='-1'">X</span>
								<span v-if="payment.continuo==null">X</span>
							</td>
							<td>{{ payment.observation }}</td>
							<td :class="{'text-danger' : payment.type==6, 'text-primary': payment.type!=6}">S/ <span v-if="payment.type==6">-</span> {{ retornarFloat(payment.price)}}</td>
							<td>
								
								<span v-if="payment.type==7">Pago de membresía</span>
								<span v-if="payment.type==6">Salida de dinero</span>
								<span v-if="payment.type==5">Pago de cita</span>
								<span v-if="payment.type==4">Otros</span>
								<span v-if="payment.type==3">Informe</span>
								<span v-if="payment.type==2">Paquete Kurame</span>
								<span v-if="payment.type==1">Paquete Membresía</span>
								<span v-if="payment.type==0">Certificado</span>
							</td>
							<td class="text-capitalize"> <span>{{monedas[payment.moneda-1]}}</span> </td>
							<td>{{ payment.voucher_issued }}</td>
							<td>{{ payment.profesional_name }}</td>
							<td>{{ horaLatam(payment.created_at) }}</td>
							<td>
								<button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditarPago" @click="editar(index)"><i class="fa-solid fa-pen-to-square"></i></button>
								<a v-if="payment.appointment_id!==0" target="_blank" :href="`/api/pdfCupon/${payment.appointment_id}`" class="btn btn-danger btn-sm"><i class="fa-solid fa-file-pdf"></i> PDF</a>
								<a v-else target="_blank" :href="`/api/pdfExtraCupon/${payment.id}`" class="btn btn-danger btn-sm"><i class="fa-solid fa-file-pdf"></i> PDF</a>
							</td>
            </tr>
        </tbody>
				<tfoot>
					<tr v-for="tipo in sumaTipos">
						<th colspan="7"></th>
						<th>{{ monedas[tipo.moneda-1] }}</th>
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
			<p class="mt-2" v-if="eliminados.length>0"><strong>Pagos eliminados</strong></p>
			<table class="table table-striped w-100 mt-1" id="table_eliminados" v-if="eliminados.length>0">
				<thead class="bg-danger text-white">
					<tr>
						<td>N°</td>
						<td>Fecha</td>
						<td>Fact. Bol.</td>
						<td>Cita</td>
						<td>Cliente - Motivo</td>
						<td>N/C</td>
						<td>Obs.</td>
						<td>Monto</td>
						<td>Motivo</td>
						<td>Moneda</td>
						<td>N° Op.</td>
						<td>Prof.</td>
						<td>Hora</td>
						<td>Ticket</td>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(payment, index) in eliminados">
						<td>
							<span>{{index+1}}</span>
						</td>
						<td>{{payment.created_at | formatedDate}}</td>
						<td>{{payment.voucher}}</td>

						<td>{{ payment.id}}</td>
						<td class="text-capitalize">{{ payment.customer }} <span v-if="payment.observation!='' && payment.observacion!=null"> <br />Obs. {{ payment.observation }}</span> <span class="text-danger"><br><strong>Motivo: </strong>{{payment.razon}}</span></td>
						<!-- <td v-if="payment.pay_status == 1">Sin cancelar</td>
							<td v-else-if="payment.pay_status == 2">Cancelado</td> -->
						<td>
							<span v-if="payment.continuo=='1'">N</span>
							<span v-if="payment.continuo=='2'">C</span>
							<span v-if="payment.continuo=='-1'">X</span>
							<span v-if="payment.continuo==null">X</span>
						</td>
						<td>{{ payment.observation }}</td>
						<td :class="{'text-danger' : payment.type==6, 'text-primary': payment.type!=6}">S/ <span v-if="payment.type==6">-</span> {{ retornarFloat(payment.price)}}</td>
						<td>
							<span v-if="payment.type==6">Salida de dinero</span>
							<span v-if="payment.type==5">Pago de cita</span>
							<span v-if="payment.type==4">Otros</span>
							<span v-if="payment.type==3">Informe</span>
							<span v-if="payment.type==2">Paquete Kurame</span>
							<span v-if="payment.type==1">Paquete Membresía</span>
							<span v-if="payment.type==0">Certificado</span>
						</td>
						<td class="text-capitalize"> <span>{{monedas[payment.moneda-1]}}</span> </td>
						<td>{{ payment.voucher_issued }}</td>
						<td>{{ payment.profesional_name }}</td>
						<td>{{ horaLatam(payment.created_at) }}</td>
						<td>
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
								<select class="form-select" name="pay_status" id="pay_status" v-model="caso.moneda">
									<option value="1">Efectivo</option>
									<option value="4">Aplicativo Yape</option>
									<option value="10">Aplicativo Plin</option>
									<option value="2">Depósito bancario</option>
									<option value="3">POS</option>
									<option value="5">Banco: BCP</option>
									<option value="6">Banco: BBVA</option>
									<option value="7">Banco: Interbank</option>
									<option value="8">Banco: Nación</option>
									<option value="9">Banco: Scotiabank</option>
								</select>
							</div>
							<div class="col-sm-12">
								<label for="">Boleta / Factura</label>
								<input type="text" class="form-control" v-model="caso.boleta">
							</div>
							<div class="col-sm-12">
								<label for="">N° de Operación</label>
								<input type="text" class="form-control" v-model="caso.comprobante">
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
		
		
	</div>
</template>

<script>
import ModalMembresias from "./ModalMembresias.vue"
import moment from 'moment'

	export default{
		data(){
			return{
				payments:[], sumaTipos:[], monedas:['Efectivo', 'Depósito bancario',  'POS', 'Aplicativo Yape', 'Banco: BCP', 'Banco: BBVA', 'Banco: Interbank', 'Banco: Nación', 'Banco: Scotiabank', 'Aplicativo Plin'],
				idUsuario: null, tienePrivilegios: null, razon:'', queId:null, queINdex:null, contenido:'', eliminados:[], caso:{id:-1,index:-1,moneda:1, boleta:'', comprobante:'', observacion:''}
			}
		},
		props:{},
		components:{ModalMembresias},
		methods:{
				getAllExtraPayments(){
						this.axios.get('/api/getAllExtraPayments')
						.then(res =>{ console.log(res.data)
							this.payments = res.data.activos
							this.eliminados = res.data.eliminados
						})
				},
				selectDate(e){
						this.axios.get(`/api/getExtraPaymentsByDay/${e.target.value}`)
						.then(res =>{ console.log(res.data)
							this.payments = res.data.activos
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
						}
					})
				},
				membresias(){}
		},
		mounted(){
			this.getAllExtraPayments()
			this.axios.get('/api/user')
			.then((res) => {
				this.idUsuario = res.data.user.id
				this.tienePrivilegios = res.data.user.privilegios
			})
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
						let queIndex= this.sumaTipos.findIndex(x=> x.moneda== item.moneda );
						//console.log(queIndex);
						if( queIndex>-1 ){ //encuentra
							if( item.type==6)
								this.sumaTipos[queIndex].suma-= parseFloat(item.price ?? 0)
							else
								this.sumaTipos[queIndex].suma+= parseFloat(item.price ?? 0)
						}else{
							if( item.type==6 )
								this.sumaTipos.push({suma: -parseFloat(item.price ?? 0), moneda: item.moneda})
							else
								this.sumaTipos.push({suma: parseFloat(item.price ?? 0), moneda: item.moneda})
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
