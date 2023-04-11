<template>
    <main>
    <h1>CRM</h1>
    <div class="card">
        <table class="table table-striped w-100 mt-4">
        <thead class="bg-success text-white">
            <tr>
                <td>#</td>
                <td>Paciente</td>
                <td>Número</td>
                <td>Cantidad de Atenciones</td>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(patient, key) in patients" v-if="patient.phone">
                <td>{{ key+1 }}</td>
                <td>{{ patient.name }}</td>
                <td>{{ patient.phone }}</td>
                <td>{{ patient.cantidad }}</td>
            </tr>
        </tbody>
        </table>
    </div>
    </main>
</template>
<script>
    export default{
        data(){
            return{
                patients:[]
            }
        },
        methods:{
            getAllPatients(){
                this.axios.get('/api/getAllPatients')
                .then(res =>{
                    this.patients = res.data
                })
            }
        },
        mounted(){
        this.getAllPatients()
        }
    } 
</script>