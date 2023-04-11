<template>
    <main>
        <h1>Adicionales</h1>
        <table-adicionales :aditionals="this.aditionals" @getAllAditionals = "getAllAditionals()"></table-adicionales>
        <CRM/>
        <modal-adicional @getAllAditionals = "getAllAditionals()"></modal-adicional>
    </main>
</template>

<script>
import TableAdicionales from '../adicionales/TableAdicionales.vue'
import ModalAdicional from '../adicionales/ModalAdicional.vue'
import CRM from '../adicionales/CRM.vue'

export default {
    name: 'homeAdicionales',
    components: { TableAdicionales, ModalAdicional, CRM },
    data(){
        return{
            aditionals:[]
        }
    },
    methods:{
        getAllAditionals(){
        this.axios.get('/api/allAditionals')
        .then(res =>{
            this.aditionals = res.data
        })
        }
    },
    mounted(){
        this.getAllAditionals()
    }
}
</script>