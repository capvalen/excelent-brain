<template>
    <main>
    <h1>Pacientes Continuantes</h1>
    <p>Seleccionar fecha</p>
    <div class="row">
        <input class="form-control w-25" type="date" id="date" v-model="today" @change="getPatientsWhitEvolution">
        <button class="btn btn-success btn-sm ml-2" @click="psiqPatients">Psiquiatría</button>
        <button class="btn btn-success btn-sm ml-2" @click="changeAppointments">Psicología</button>
    </div>
        <table class="table table-striped w-100 mt-4">
            <thead class="bg-success text-white">
                <tr>
                    <th>Paciente</th>
                    <th>Profesional</th>
                    <th>Celular</th>
                    <th>Whatsapp</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="patientsWhitEvolutionsPsiq.length != 0" v-for="(cita, index) in patientsWhitEvolutionsPsiq" :key="index">
                    <td>{{cita.patient.name}}</td>
                    <td>Wilbert Barzola</td>
                    <td>{{cita.patient.phone}}</td>
                    <td><a :href="`whatsapp://send?phone=51${cita.patient ? cita.patient.phone : ''}&text= Estimado(a) paciente ${cita.patient ? cita.patient.name : ''}, le recordamos que el día ${todayFormatted} debería continuar con su tratamiento, en el Centro Psicológico y Psiquiátrico EXCELENTEMENTE. No olvide reservar su cita.`"
                            target="_blank" 
                            title="Enviar mensaje" 
                            class="btn btn-info btn-circle btn-sm">
                  <i class="fab fa-whatsapp"></i>
                  </a>
                </td>
                </tr>
                <tr v-if="patientsWhitEvolutions.length != 0" v-for="(cita, index) in patientsWhitEvolutions" :key="index">
                    <td>{{cita.patient.name}}</td>
                    <td>{{cita.professional.name}}</td>
                    <td>{{cita.patient.phone}}</td>
                    <td><a :href="`whatsapp://send?phone=51${cita.patient ? cita.patient.phone : ''}&text= Estimado(a) paciente ${cita.patient ? cita.patient.name : ''}, le recordamos que el día ${todayFormatted} debería continuar con su tratamiento, en el Centro Psicológico y Psiquiátrico EXCELENTEMENTE. No olvide reservar su cita.`"
                            target="_blank" 
                            title="Enviar mensaje" 
                            class="btn btn-info btn-circle btn-sm">
                  <i class="fab fa-whatsapp"></i>
                  </a>
                </td>
                </tr>
            </tbody>
        </table>
    </main>
</template>
<script>
    import moment from 'moment'
    export default{
        data(){
            return{
                today: moment(new Date()).format('YYYY-MM-DD'),
                todaySubsWeek: moment(new Date()).subtract(7,'days').format('YYYY-MM-DD'),
                patientsWhitEvolutions: [],
                patientsWhitEvolutionsPsiq: [],
                todayFormatted : '',
                /* today: moment(new Date()).format('DD/MM/YYYY'),
                
                ,
                 */
            }
        },
        methods:{
            getPatientsWhitEvolution(){
                this.todaySubsWeek = moment(this.today).subtract(7,'days').format('YYYY-MM-DD')
                this.todayFormatted = moment(this.today).format('DD/MM/YYYY')
                this.axios.get(`/api/getPatientsWhitEvolution/${this.today}`)
                .then((res)=>{
                    this.patientsWhitEvolutionsPsiq = []
                    this.patientsWhitEvolutions = res.data
                    if(res.data.length == 0){
                        alert('No se encontraron continuantes de psicología para esta fecha')
                    }
                })
            },
            psiqPatients(){
                this.axios.get(`/api/getPatientsPsiq/${this.today}`)
                .then((res)=>{
                    this.patientsWhitEvolutions = []
                    this.patientsWhitEvolutionsPsiq = res.data
                    if(res.data.length == 0){
                        alert('No se encontraron continuantes de psiquiatría para esta fecha')
                    }
                })
            },
            changeAppointments(){
                this.patientsWhitEvolutionsPsiq = []
                this.getPatientsWhitEvolution()
            }  
        },
        mounted(){
            this.getPatientsWhitEvolution();
        }
    }
</script>