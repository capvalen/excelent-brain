<template>
<div class="row">
   <h1>Tabla de medicamentos kairos</h1>
            <input v-model="buscar" autocomplete="off" type="text" class="form-control w-100" placeholder="Ingrese el nombre del medicamento" name="" id="via" />
            <table class="table">
                <thead class="bg-warning text-white">
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Medicamento</th>
                    <th scope="col">Nombre Comercial</th>
                    <th scope="col">Concentración</th>
                    <th scope="col">Presentación</th>
                    <th scope="col">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(medicamento,key) in filtro" :key="medicamento.id">
                        <th scope="row">{{key+1}}</th>
                        <td>{{medicamento.name}}</td>
                        <td>{{medicamento.tradename}}</td>
                        <td>{{medicamento.concentration}}</td>
                        <td>{{medicamento.presentation}}</td>
                        <td>
                            <button @click="deleteKairos(medicamento.id)" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
</div>
</template>

<script>
export default{
    data(){
        return {
            medicamentos:[],
            buscar:''
        }
    },
    methods:{  
    getKairos(){
            this.axios.get('/api/kairos')
            .then((result) => {
                this.medicamentos = result.data
                console.log(result.data)
            })
    },
    deleteKairos(id){
        this.axios.get(`/api/kairoDelete/${id}`)
        .then((result) => {
                if(result.data.msg == 'success'){
                    this.$swal('Medicamento eliminado correctamente')
                    this.getKairos();
                }else{
                    this.$swal('Server Error')
                }
                
        })
    }
    },
    mounted(){
            this.getKairos();
    },
    computed:{
        filtro(){
            if(!this.buscar){
                return null
            }else{
                return this.medicamentos.filter(medicamento => medicamento.name.toLowerCase().includes(this.buscar) || medicamento.tradename.toLowerCase().includes(this.buscar)).splice(0,6)
            }
        }
    },
}
</script>
