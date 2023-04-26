<template>
    <main>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Mis Recursos</h1>
        </div>

        <div class="row">
            <CardRecurso 
            title="SCL90R"
            color="primary"
            url="scr"
            />

            <CardRecurso 
            title="Ansiedad de BURNS"
            color="success"
            url="burns"
            />
        </div>

        <div class="row">
            <CardRecurso 
            title="MILLON"
            color="primary"
            url="millon"
            />

            <CardRecurso 
            title="Depresión de Zung"
            color="success"
            url="depzung"
            />
        </div>
        
        <div class="row">
            <CardRecurso 
            title="Ansiedad de Zung"
            color="primary"
            url="anszung"
            />

            <CardRecurso 
            title="PHQ - 9"
            color="success"
            url="phq-9"
            />
        </div>

        <div class="row">
            <CardRecurso 
            title="GAD - 7"
            color="primary"
            url="gad-7"
            />

            <CardRecurso 
            title="BDI - 2"
            color="success"
            url="bdi-2"
            />
        </div>

        <div class="row">
            <CardRecurso 
            title="MCMI - II"
            color="primary"
            url="mcmi"
            />

            <CardRecurso 
            title="BARON"
            color="success"
            url="baron"
            />
        </div>

        <div class="row">
            <CardRecurso 
            title="Eysenck-A"
            color="primary"
            url="eysenck-a"
            />

            <CardRecurso 
            title="Eysenck-B"
            color="success"
            url="eysenck-b"
            />
        </div>
        <div class="row">
            <CardRecurso
            title="SRQ 18"
            color="primary"
            url="srq" 
            />

            <CardRecurso 
            title="PHQ-15"
            color="success"
            url="phq-15"
            />
        </div>
        <div class="row">
            <CardRecurso
            title="MDQ"
            color="primary"
            url="mdq" 
            />
        </div>
    </main>
</template>

<script>
import CardRecurso from './CardRecurso';
export default {
    components: {CardRecurso},
    
    methods:{
        getNames(){
            this.axios.get('/api/getNames')
            .then((result) => {
                console.log(result)
            }).catch((err) => {

            });
        }
    },

    computed(){

    },
    
    mounted(){
        this.getNames()
    }
}
</script>
