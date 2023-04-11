<template>
    <main>
        <h1>Cuestionario de Ansiedad de Burns</h1>

        <div class="scrollable">
            <p>Resultado Global: {{this.suma}}</p>
            <p>{{this.resultado}}</p>
            <button class="btn btn-primary" @click="sumatoria">Calcular y guardar</button>
        </div>

        <label for="">Nombre del Paciente</label>
         <input type="text" name="" v-model="buscar" id="" class="form-control input-name">
            <div v-show="showResults" class="border border-secondary shadow w-100">
                <div @click="selectPatient(patient)" class="border border-secondary"  v-for="patient in filtro" :key="patient.id">{{patient.name}}</div>
            </div>

        <p class="mt-2">
           ¿En qué medida le ha preocupado el sentimiento correspondiente durante los últimos días?
           Utilice los siguientes criterios:
           <br>
           0: nada 1: algo 2: bastante 3: mucho
        </p>

        <ul class="p-0">
            <li v-for="item in items" :key="item.id">
               {{item.id}}. {{item.content}}
               <br>
               <div class="form-check form-check-inline" @click="inputValue(item.id)">
                   <input class="form-check-input" type="radio" :name="`inlineRadioOptions${item.id}`" :id="`inlineRadio${item.id}-a`" value="0">
                   <label class="form-check-label" :for="`inlineRadio${item.id}-a`">0</label>
               </div>
               <div class="form-check form-check-inline" @click="inputValue(item.id)">
                   <input class="form-check-input" type="radio" :name="`inlineRadioOptions${item.id}`" :id="`inlineRadio${item.id}-b`" value="1">
                   <label class="form-check-label" :for="`inlineRadio${item.id}-b`">1</label>
               </div>
               <div class="form-check form-check-inline" @click="inputValue(item.id)">
                   <input class="form-check-input" type="radio" :name="`inlineRadioOptions${item.id}`" :id="`inlineRadio${item.id}-c`" value="2">
                   <label class="form-check-label" :for="`inlineRadio${item.id}-c`">2</label>
               </div>
               <div class="form-check form-check-inline" @click="inputValue(item.id)">
                   <input class="form-check-input" type="radio" :name="`inlineRadioOptions${item.id}`" :id="`inlineRadio${item.id}-d`" value="3">
                   <label class="form-check-label" :for="`inlineRadio${item.id}-d`">3</label>
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
                {id:'1',content: 'Ansiedad, nerviosismo, preocupación o miedo'},
                {id:'2',content: 'Sensación de que las cosas que le rodean son extrañas o irreales'},
                {id:'3',content: 'Se siente separado de todo o de una parte de su cuerpo'},
                {id:'4',content: 'Episodios repentinos e inesperados de angustia'},
                {id:'5',content: 'Temor o sensación de muerte inminente'},
                {id:'6',content: 'Se siente tenso estresado, inquieto o son los nervios a flor de piel'},
                {id:'7',content: 'Dificultades para concentrarse'},
                {id:'8',content: 'Pensamientos acelerados'},
                {id:'9',content: 'Fantasías o ensoñaciones terroríficas'},
                {id:'10',content: 'Sensación de estar a punto de perder el control'},
                {id:'11',content: 'Miedo a sufrir una crisis nerviosa o a volverse loco'},
                {id:'12',content: 'Miedo a desmayarse o a perder el conocimiento'},
                {id:'13',content: 'Miedo a las enfermedades físicas, a las crisis cardíacas o a la muerte'},
                {id:'14',content: 'Preocupaciones por parecer estúpido (a) o incompetente'},
                {id:'15',content: 'Miedo a quedarse solo(a), aislado(a) o a ser abandonado(a)'},
                {id:'16',content: 'Miedo a las críticas o a la desaprobación'},
                {id:'17',content: 'Miedo a que algo terrible esté a punto de suceder'},
                {id:'18',content: 'Sacudidas del corazón, aumento de la frecuencia cardiaca o palpitaciones'},
                {id:'19',content: 'Dolor, tensión u opresión torácica'},
                {id:'20',content: 'Sensación de entumecimiento u hormigueo en los dedos de las manos o de los pies'},
                {id:'21',content: 'Naúseas o molestias abdominales'},
                {id:'22',content: 'Estreñimiento o diarrea'},
                {id:'23',content: 'Inquietud o desasosiego'},
                {id:'24',content: 'Agarrotamiento y tensión muscular'},
                {id:'25',content: 'Sudoración no debido al calor'},
                {id:'26',content: 'Sensación de tener un nudo en la garganta'},
                {id:'27',content: 'Temblores o sacudidas'},
                {id:'28',content: 'Piernas temblorosas o de consistencia “gomosa”'},
                {id:'29',content: 'Sensación de mareo, aturdimiento o inestabilidad'},
                {id:'30',content: 'Sensación de ahogo, de asfixia o de dificultades respiratorias'},
                {id:'31',content: 'Dolores de cabeza, de nuca o de espalda'},
                {id:'32',content: 'Escalofríos o sofocaciones'},
                {id:'33',content: 'Somnolencia, sensación de debilidad o de agotamiento'}
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
                this.suma += parseInt(rpta)
                })

                if(this.suma >= 51){
                    this.resultado = 'Ansiedad extrema o crisis de angustia'
                }else if(this.suma >= 31){
                    this.resultado = 'Ansiedad grave'
                }else if(this.suma >= 21){
                    this.resultado = 'Ansiedad moderada'
                }else if (this.suma >= 11){
                    this.resultado = 'Ansiedad leve'
                }else if (this.suma > 4){
                    this.resultado = 'Ansiedad límite'
                }else if(this.suma >= 0){
                    this.resultado = 'Ansiedad mínima o ausencia de ansiedad'
                }


                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData()
                formData.append('result', this.suma)
                formData.append('patient_id', this.patient_id)

                this.axios.post('/api/saveBurns', formData, config)
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
