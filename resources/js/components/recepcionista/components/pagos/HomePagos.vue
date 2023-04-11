<template>
    <main>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Pagos</h1>
            <div class="d-flex align-items-center">
                <input type="date" class="form-control" @change="selectDate">
            </div>
    </div>
    <div class="card px-1">
        <table class="table table-striped w-100 mt-4">
        <thead class="bg-success text-white">
            <tr>
                <td>Cita</td>
                <td>Cliente</td>
                <td>Monto</td>
                <td>Fecha</td>
                <td>Observación</td>
                <td>Ticket</td>
            </tr>
        </thead>
        <tbody>
            <tr v-for="payment in payments">
                <td>{{ payment.id}}</td>
                <td>{{ payment.customer }}</td>
                <!-- <td v-if="payment.pay_status == 1">Sin cancelar</td>
                <td v-else-if="payment.pay_status == 2">Cancelado</td> -->
                <td>S./ {{payment.price}}</td>
                <td>{{payment.created_at | formatedDate}}</td>
								<td>{{ payment.observation }}</td>
                <td><a target="_blank" :href="`/api/pdfExtraCupon/${payment.id}`" class="btn btn-danger btn-sm">Generar PDF</a></td>
            </tr>
        </tbody>
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
            }
        },
        mounted(){
            this.getAllExtraPayments()
        },
        filters:{
            formatedDate(date){
                return moment(date).format('DD/MM/yyyy')
            }
        }
    }
</script>