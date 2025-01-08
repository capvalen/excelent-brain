<template>
    <main>
        <h1>Cuestionario de Depresión de Zung</h1>

        <div class="scrollable">
            <p>Resultado: {{this.suma}}</p>
            <p>Porcentaje: {{Math.round(this.suma*5/4)}}</p>
            <p>Condición: {{this.resultado}}</p>
            <button class="btn btn-primary" @click="sumatoria">Calcular y guardar</button>
        </div>

        <label for="">Nombre del Paciente</label>
        <input type="text" name="" v-model="buscar" id="" class="form-control input-name">
        <div v-show="showResults" class="border border-secondary shadow w-100">
            <div @click="selectPatient(patient)" class="border border-secondary"  v-for="patient in filtro" :key="patient.id">{{patient.name}} {{patient.nombres}}</div>
        </div>

        <p class="mt-2">
           Marque la casilla que mejor refleje su situación
           <br>
           1: nunca o casi nunca 2: A veces 3: Con bastante frecuencia 4: Siempre o casi siempre
        </p>

        <ul class="p-0">
            <li v-for="item in items" :key="item.id">
               {{item.id}}. {{item.content}}
               <br>
               <div class="form-check form-check-inline" @click="inputValue(item.id)">
                   <input class="form-check-input" type="radio" :name="`inlineRadioOptions${item.id}`" :id="`inlineRadio${item.id}-a`" value="1">
                   <label class="form-check-label" :for="`inlineRadio${item.id}-a`">1</label>
               </div>
               <div class="form-check form-check-inline" @click="inputValue(item.id)">
                   <input class="form-check-input" type="radio" :name="`inlineRadioOptions${item.id}`" :id="`inlineRadio${item.id}-b`" value="2">
                   <label class="form-check-label" :for="`inlineRadio${item.id}-b`">2</label>
               </div>
               <div class="form-check form-check-inline" @click="inputValue(item.id)">
                   <input class="form-check-input" type="radio" :name="`inlineRadioOptions${item.id}`" :id="`inlineRadio${item.id}-c`" value="3">
                   <label class="form-check-label" :for="`inlineRadio${item.id}-c`">3</label>
               </div>
               <div class="form-check form-check-inline" @click="inputValue(item.id)">
                   <input class="form-check-input" type="radio" :name="`inlineRadioOptions${item.id}`" :id="`inlineRadio${item.id}-d`" value="4">
                   <label class="form-check-label" :for="`inlineRadio${item.id}-d`">4</label>
               </div>
            </li>
        </ul>

        <button class="btn btn-success" @click="print">Imprimir Respuestas</button>
    </main>
</template>

<script>
    export default {
        data(){
            return{
                items:[
                {id:'1',content: 'Me siento abatido y melancólico'},
                {id:'2',content: 'Por las mañanas es cuando me siento mejor'},
                {id:'3',content: 'Tengo accesos de llanto o ganas de llorar'},
                {id:'4',content: 'Tengo problemas para dormir en la noche'},
                {id:'5',content: 'Tengo tanto apetito como antes'},
                {id:'6',content: 'Aun me atraen las personas del sexo opuesto'},
                {id:'7',content: 'Noto que estoy perdiendo peso'},
                {id:'8',content: 'Tengo trastornos intestinales y estreñimiento'},
                {id:'9',content: 'Me late el corazón mas aprisa que de costumbre'},
                {id:'10',content: 'Me canso sin motivo'},
                {id:'11',content: 'Tengo la mente tan clara como antes'},
                {id:'12',content: 'Hago las cosas con la misma facilidad que antes'},
                {id:'13',content: 'Me siento nervioso y no puedo estar quieto'},
                {id:'14',content: 'Tengo esperanza en el futuro'},
                {id:'15',content: 'Estoy mas irritable que antes'},
                {id:'16',content: 'Me es facil tomar decisiones'},
                {id:'17',content: 'Me siento util y necesario'},
                {id:'18',content: 'Me satisface mi vida actual'},
                {id:'19',content: 'Creo que los demas estuvieran mejor si yo muriera'},
                {id:'20',content: 'Disfruto de las mismas cosas que antes'}
                ],
                suma:0,
                objs:[],
                resultado:'',
                buscar:'',
                showResults:false,
                patients:[],
                patient_id :''
            }
        },

        methods:{
            inputValue(nro){
                var name = 'inlineRadioOptions'+nro
                var rpta = document.querySelector('input[name='+name+']:checked').value;
                var obj = {nro, rpta}

                if(this.verifDuplicated(obj)){

                    this.objs.push({...obj})
                }else{

                }


                console.log(this.objs)

                /* var filter = this.objs.filter(obj => obj.nro == nro)

                console.log(filter)

                if(filter.lenght != 0){
                    this.objs.splice(obj,1)
                    obj= {nro, rpta}
                    this.objs.push({...obj})
                }else{

                }

                console.log(this.objs) */



            },
            verifDuplicated(obj2){
                if(this.objs.filter(obj=>obj.nro == obj2.nro).length == 0){
                    return true
                }else{
                    console.log('coincidencia de nro')
                    this.objs = this.objs.filter(obj=>obj.nro != obj2.nro)
                    this.objs.push({...obj2})
                    return false
                }
            },
            sumatoria(){
                this.suma= 0
                this.objs.forEach((value, index)=>{

                var nro = value.nro
                var rpta = value.rpta



                if(nro == 2 || nro == 5 || nro == 6 || nro == 11|| nro == 12 || nro ==14 || nro == 16 || nro == 17 || nro ==18|| nro==20){

                    if(rpta == 4){
                        rpta = 1
                    }else if (rpta == 3){
                        rpta = 2
                    }else if (rpta == 2){
                        rpta = 3
                    }else if(rpta == 1){
                        rpta = 4
                    }
                }

                this.suma += parseInt(rpta)
                })

                var percent = Math.round(this.suma*5/4)

                if(percent >= 71){
                    this.resultado = 'Depresión ambulatoria'
                }else if(percent >= 61){
                    this.resultado = 'Desorden situacional'
                }else if(percent >= 41){
                    this.resultado = 'Desorden Emocional'
                }else if(percent <= 40){
                    this.resultado = 'No hay depresión presente'
                }


                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData()
                formData.append('result', percent)
                formData.append('patient_id', this.patient_id)
								formData.append('professional_id', this.$attrs.professional.id)


                this.axios.post('/api/saveZungDep', formData, config)
                .then((result) => {
                    this.$swal('Resultados regitrados correctamente')
                }).catch((err) => {
                    this.$swal({
                        icon: 'error',
                        title: 'Error...',
                        text: err
                    })
                });
            },
            print(){
                window.print();
            },
            getNames(){
                this.axios.get('/api/getNames')
                .then((result) => {
                    this.patients = result.data
                    //console.log(this.patients)
                }).catch((err) => {

                });
            },
            selectPatient(patient){
                this.$swal('Usuario aceptado')
                this.patient_id = patient.id
                this.buscar = patient.name
                this.showResults = false
            }
            },
            computed:{
            filtro(){
                if(!this.buscar){
                    this.showResults = false
                    return null
                }else{
                    this.showResults = true
                    return this.patients.filter(patient => patient.name.toLowerCase().includes(this.buscar)).slice(0,6)
                }
            }
            },
            mounted(){
                this.getNames()
            }

    }
</script>

<style scoped>
    ul{
        list-style: none;
    }

    li{
        background-color: #fff;
        font-size: 20px;
        border: 1px solid rgba(0, 0, 0, 0.493);
        margin: 10px 0px 10px 0px;
        border-radius: 5px;
        padding: 10px;
    }

    .input-name {
        width: 70%;
        flex: 1 0 auto;
        min-width: 250px;
    }

    .input-select {
        width: 25%;
        flex: 1 0 auto;
    }

    .scrollable{
        background-color: #fff;
        color: #000;
        position: fixed;
        right: 0;
        border: 1px solid rgba(0, 0, 0, 0.493);
        z-index:99;
        border-radius: 5px;
        padding: 5px;
    }

    .gap {
        gap: 15px;
    }

    @media screen and (max-width: 750px) {
        .scrollable {
            position: relative;
        }
    }
</style>
