<template>
    <main>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Pagos</h1>
            <div class="d-flex align-items-center">
                <input type="date" class="form-control" @change="selectDate">
            </div>
    </div>
    <div class="card px-1 pt-2">
			<div>
				<button class="btn btn-outline-success" @click="exportar()"><i class="fas fa-file-excel"></i> Exportar a Excel</button>
			</div>
        <table class="table table-striped w-100 mt-4" id="table_export">
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
                <td>Moneda</td>
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
							<td class="text-capitalize">{{ payment.customer }} <span v-if="payment.observation!=''"> <br />Obs. {{ payment.observation }}</span></td>
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
								<button class="btn btn-outline-primary btn-sm" @click="editar(index)"><i class="fa-solid fa-pen-to-square"></i></button>
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
    </div>

		<!-- Modal -->
		<div class="modal fade" id="modalMotivoBorrar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
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
		
</main>
</template>

<script>
	import moment from 'moment'

	export default{
		data(){
			return{
				payments:[], sumaTipos:[], monedas:['Efectivo', 'Depósito bancario',  'POS', 'Aplicativo Yape', 'Banco: BCP', 'Banco: BBVA', 'Banco: Interbank', 'Banco: Nación', 'Banco: Scotiabank', 'Aplicativo Plin'],
				idUsuario: null, tienePrivilegios: null, razon:'', queId:null, queINdex:null, contenido:''
			}
		},
		props:{},
		methods:{
				getAllExtraPayments(){
						this.axios.get('/api/getAllExtraPayments')
						.then(res =>{ console.log(res.data)
								this.payments = res.data
						})
				},
				selectDate(e){
						this.axios.get(`/api/getExtraPaymentsByDay/${e.target.value}`)
						.then(res =>{ console.log(res.data)
								this.payments = res.data
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
				}
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
				return this.payments.reduce((suma, item)=>{
					let queIndex= this.sumaTipos.findIndex(x=> x.moneda== item.moneda );
					//console.log(queIndex);
					if( !queIndex ){ //encuentra
						this.sumaTipos[queIndex].suma+= parseFloat(item.price ?? 0)
					}else{
						this.sumaTipos.push({suma: parseFloat(item.price ?? 0), moneda: item.moneda})
					}
					if(item.type==6){
						return suma- parseFloat(item.price??0)
					}else{
						return suma+ parseFloat(item.price??0)
					}
					
				}, 0)
			}
		}
	}
</script>
