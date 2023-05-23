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
                <td>Cita</td>
                <td>Cliente - Motivo</td>
                <td>Monto</td>
                <td>Fecha</td>
                <td>Motivo</td>
                <td>Moneda</td>
                <td>Ticket</td>
            </tr>
        </thead>
        <tbody>
            <tr v-for="payment in payments">
                <td>{{ payment.id}}</td>
                <td class="text-capitalize">{{ payment.customer }} {{ payment.observation }}</td>
                <!-- <td v-if="payment.pay_status == 1">Sin cancelar</td>
                <td v-else-if="payment.pay_status == 2">Cancelado</td> -->
                <td :class="{'text-danger' : payment.type==6, 'text-primary': payment.type!=6}">S/ <span v-if="payment.type==6">-</span> {{payment.price}}</td>
                <td>{{payment.created_at | formatedDate}}</td>
								<td>
									<span v-if="payment.type==6">Salida de dinero</span>
									<span v-if="payment.type==5">Pago de cita</span>
									<span v-if="payment.type==4">Otros</span>
									<span v-if="payment.type==3">Informe</span>
									<span v-if="payment.type==2">Paquete Kurame</span>
									<span v-if="payment.type==1">Paquete Membresía</span>
									<span v-if="payment.type==0">Certificado</span>
								</td>
								<td class="text-capitalize">
									<span v-if="payment.moneda==1">Efectivo</span>
									<span v-if="payment.moneda==2">Depósito bancario</span>
									<span v-if="payment.moneda==4">Aplicativo Yape/Plin</span>
									<span v-if="payment.moneda==3">POS</span>
									<span v-if="payment.moneda==5">Banco: BCP</span>
									<span v-if="payment.moneda==6">Banco: BBVA</span>
									<span v-if="payment.moneda==7">Banco: Interbank</span>
									<span v-if="payment.moneda==8">Banco: Nación</span>
									<span v-if="payment.moneda==9">Banco: Scotiabank</span>
								</td>
                <td><a target="_blank" :href="`/api/pdfExtraCupon/${payment.id}`" class="btn btn-danger btn-sm">Generar PDF</a></td>
            </tr>
        </tbody>
				<tfoot>
					<tr>
						<th></th>
						<th>Total</th>
						<th>S/ {{ parseFloat(suma).toFixed(2) }}</th>
						<th></th>
					</tr>
				</tfoot>
    </table>
    </div>
</main>
</template>

<script>
    import moment from 'moment'

    export default{
        data(){
            return{
                payments:[]
            }
        },
        methods:{
            getAllExtraPayments(){
                this.axios.get('/api/getAllExtraPayments')
                .then(res =>{
                    this.payments = res.data
                })
            },
            selectDate(e){
                this.axios.get(`/api/getExtraPaymentsByDay/${e.target.value}`)
                .then(res =>{
                    this.payments = res.data
                })
            },
						exportar(){
							var table2excel = new Table2Excel();
							table2excel.export(document.getElementById('table_export'), `Caja de ${moment().format('DD-MM-YYYY hh-mm a')}`);
						}
        },
        mounted(){
            this.getAllExtraPayments()
        },
        filters:{
            formatedDate(date){
                return moment(date).format('DD/MM/yyyy')
            }
        },
				computed:{
					suma: function (){
						return this.payments.reduce((suma, item)=>{
							if(item.type==6){
								return suma- parseFloat(item.price)
							}else{
								return suma+ parseFloat(item.price)
							}
							
						}, 0)
					}
				}
    }
</script>
