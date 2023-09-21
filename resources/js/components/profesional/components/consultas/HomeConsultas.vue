<template>
    <main>
        <div class="d-sm-flex main-header align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Mis Consultas</h1>
            <div class="d-flex consultas-btn-top align-content-stretch my-3" style="gap: 5px">
                <div class="d-flex gap fecha-restablecer">
                    <input @change="calendarActive" type="date" class="form-control" name="" id="calendar" >
                    <a @click="now" class="btn btn-sm btn-primary d-flex align-items-center">Restablecer</a>
                </div>
                
                <ul class="d-flex justify-content-start align-content-stretch nav nav-pills" id="pills-tab" role="tablist" v-if="dayActive">
                    <li class="nav-item" role="presentation">
                        <button 
                            class="btnConsultas h-100 btn btn-sm btn-primary btnConsultasActive active btn-hover" 
                            id="pills-home-tab" 
                            data-bs-toggle="pill" 
                            data-bs-target="#pills-home" 
                            type="button" 
                            role="tab" 
                            aria-controls="pills-home" 
                            aria-selected="true"
                            @click="consultaActiva(false, true)">
                        Todas las consultas
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button 
                            class="btnConsultas h-100 btn btn-sm btn-hover" 
                            id="pills-profile-tab" 
                            data-bs-toggle="pill" 
                            data-bs-target="#pills-profile" 
                            type="button" 
                            role="tab" 
                            aria-controls="pills-profile" 
                            aria-selected="false"
                            @click="consultaActiva(true, true)">
                        Consultas pendientes
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card pb-4">
					<div class="row px-1 shadow consult-content pb-3">
						<div class="col-12 mt-3">
							<button class="btn btn-outline-secondary" @click="showConsult()"><i class="fas fa-redo-alt"></i> Actualizar listado</button>
						</div>

                <!-- Ayer -->
                <div class="col-md-4 dayCalendar before">
                    <div class="date text-strong text-center text-dark mt-3 notNow" v-if="dayActive">
                        <h2>{{ dayBeforeNumber }}</h2>
                        <p>{{ stringDate.stringDateBefore }}</p>
                    </div>
                    
                    <!-- Consultas -->
                    <div v-if="consultasAntes.length !== 0">
                        <consult-card
                        v-for="(consult, index) in consultasAntes"
                        :key="index"
                        :dataConsult="consult"
                        @datosEmitModal="evolucionModal"
                        @emitInfoModal="getInfoModal"
                        ></consult-card>
                    </div>

                    <div v-else class="text-center"> <span v-if="dayActive">No hay Citas</span> </div>
                    
                    <!-- :dataConsult="consultas"
                    @modal="handle" -->

                </div>

                <!-- Hoy -->
                <div class="col-md-4 dayCalendar today">
                    <div class="date text-strong text-center text-dark mt-3 now">
                        <h2>{{ dayMonth }}</h2>
                        <p>{{ stringDate.stringDateNow }}</p>
                    </div>
                    
                    <div v-if="consultasHoy.length !== 0">
                        <consult-card
                        v-for="(consult, index) in consultasHoy"
                        :key="index"
                        :dataConsult="consult"
                        @datosEmitModal="evolucionModal"
                        @emitInfoModal="getInfoModal"
                        ></consult-card>
                    </div>

                    <div v-else class="text-center"> No hay Citas </div>
                </div>
                
                <!-- Mañana -->
                <div class="col-md-4 dayCalendar after">
                    <div class="date text-strong text-center text-dark mt-3 notNow" v-if="dayActive">
                        <h2>{{ dayAfterNumber }}</h2>
                        <p>{{ stringDate.stringDateAfter }}</p>
                    </div>
                    
                    <div v-if="consultasSiguientes.length !== 0">
                        <consult-card
                        v-for="(consult, index) in consultasSiguientes"
                        :key="index"
                        :dataConsult="consult"
                        @datosEmitModal="evolucionModal"
                        @emitInfoModal="getInfoModal"
                        ></consult-card>
                    </div>

                    <div v-else class="text-center"> <span v-if="dayActive">No hay Citas</span> </div>
                </div>
                <!-- Pasado Mañana +2 -->
                <div class="col-md-4 dayCalendar after">
                    <div class="date text-strong text-center text-dark mt-3 notNow" v-if="dayActive">
                        <h2>{{ mañana2 }}</h2>
                        <p>{{ stringDate.mañana2 }}</p>
                    </div>
                    
                    <div v-if="consultasSiguientes2.length !== 0">
                        <consult-card
                        v-for="(consult, index) in consultasSiguientes2"
                        :key="index"
                        :dataConsult="consult"
                        @datosEmitModal="evolucionModal"
                        @emitInfoModal="getInfoModal"
                        ></consult-card>
                    </div>

                    <div v-else class="text-center"> <span v-if="dayActive">No hay Citas</span> </div>
                </div>
                <!-- Tras Mañana +3 -->
                <div class="col-md-4 dayCalendar after">
                    <div class="date text-strong text-center text-dark mt-3 notNow" v-if="dayActive">
                        <h2>{{ mañana3 }}</h2>
                        <p>{{ stringDate.mañana3 }}</p>
                    </div>
                    
                    <div v-if="consultasSiguientes3.length !== 0">
                        <consult-card
                        v-for="(consult, index) in consultasSiguientes3"
                        :key="index"
                        :dataConsult="consult"
                        @datosEmitModal="evolucionModal"
                        @emitInfoModal="getInfoModal"
                        ></consult-card>
                    </div>

                    <div v-else class="text-center"> <span v-if="dayActive">No hay Citas</span> </div>
                </div>
                <!-- Tras Pasado Mañana +4 -->
                <div class="col-md-4 dayCalendar after">
                    <div class="date text-strong text-center text-dark mt-3 notNow" v-if="dayActive">
                        <h2>{{ mañana4 }}</h2>
                        <p>{{ stringDate.mañana4 }}</p>
                    </div>
                    
                    <div v-if="consultasSiguientes4.length !== 0">
                        <consult-card
                        v-for="(consult, index) in consultasSiguientes4"
                        :key="index"
                        :dataConsult="consult"
                        @datosEmitModal="evolucionModal"
                        @emitInfoModal="getInfoModal"
                        ></consult-card>
                    </div>

                    <div v-else class="text-center"> <span v-if="dayActive">No hay Citas</span> </div>
                </div>


            </div>
        </div>

        <info-modal :dataModal="infoModal"></info-modal>
        <evolution-modal 
        :dataUser="dataUser.profession" 
        :datosIdEvolucion="datosEvolucion"
        @updateCalendar="updatedCalendar"
        ></evolution-modal>
    </main>
</template>

<script>
import alertify from 'alertifyjs'
import { parse } from 'postcss'
import ConsultCard from './Consult.vue'

/* === Modales === */
import InfoModal from './InfoModal.vue'
import EvolutionModal from './EvolucionModal.vue'
import { operationDate, formatDate, dateNow } from '../../../../helpers/Time.js'


export default {
    name: 'consultas',

    data () {
        return {
            infoModal: null,
            
            /* == Array de consultas == */
            consultasHoy: [],
            consultasAntes: [],
            consultasSiguientes: [],
            consultasSiguientes2: [], consultasSiguientes3: [], consultasSiguientes4: [],
            consultas: [],
            todasConsultas: [],

            /* == Variables de props == */
            userId: this.idUser,
            datosModal: null,
            datosEvolucion: Object,

            /* Variables del calendario */
            tipoConsulta: false,                    // Todas las consultas o consulta pendiente
            dayActive: true,                        // Variable que desaparece cuando realiza busqueda por calendario

            todayDay: new Date().getDay(),          // Obtiene el dia de la semana comnezando 0 como domingo y 6 como sabado
            
            currentYear: new Date().getFullYear(),          // Año antual
            currentYearBefore: new Date().getFullYear(),    // Año antual antes
            currentYearAfter: new Date().getFullYear(),     // Año antual despues

            todayMonth: new Date().getMonth() + 1,          // Obtiene el mes en que estamos
            todayMonthBefore: new Date().getMonth() + 1,    // Obtiene el mes antes en que estamos
            todayMonthAfter: new Date().getMonth() + 1,     // Obtiene el mes despues en que estamos
            
            dayMonth: new Date().getDate(),         // Obtiene el dia del mes
            dayBeforeNumber: null,                  // Obtiene el dia antes del dia de hoy del mes
            dayAfterNumber: null,                   // Obtiene el dia despues del dia de hoy del mes
						mañana2: null, mañana3: null, mañana4: null, 

            todayMonthDay: null,                    // valor que se rellena para la funcion de devolucion del dia del mes

            stringDate: {                           // Nombre del mes / antes / hoy / despues
                stringDateBefore: '',
                stringDateAfter: '',
                stringDateNow: '',
								mañana2: '', mañana3: '', mañana4: ''
            }
        }
    },

    components: { ConsultCard, InfoModal, EvolutionModal },

    methods: {

        /**
         * @function 
         * ## hace la peticion al servidor para devolver las consultas del 
         * profesional
         */

        async showConsult () {
            await this.axios.get(`/api/appointment/${this.dataUser.id}`)
            .then(res => {
                console.log(res.data)
                this.consultas = res.data.consult
                this.todasConsultas = this.consultas
                this.consultWeek()
								alertify.notify('<i class="fa-regular fa-calendar-check"></i> Citas actualizadas ', 'success', 5);

            })
            .catch(error => {
                console.log(error)
            })
        },

        /**
         * @function 
         * ## Trae los dias al cargar el componente
         */

        getDay () { 
            this.dayBeforeNumber = operationDate(1, false).getDate()
            this.dayAfterNumber = operationDate(1, true).getDate() 
            this.mañana2 = operationDate(2, true).getDate()
            this.mañana3 = operationDate(3, true).getDate()
            this.mañana4 = operationDate(4, true).getDate()

            this.stringDate.stringDateNow = this.returnStringMonth(this.todayMonth)
            this.stringDate.stringDateBefore = this.returnStringMonth(operationDate(1, false).getMonth() + 1)
            this.stringDate.stringDateAfter = this.returnStringMonth(operationDate(1, true).getMonth() + 1)
            this.stringDate.mañana2 = this.returnStringMonth(operationDate(2, true).getMonth() + 1)
            this.stringDate.mañana3 = this.returnStringMonth(operationDate(3, true).getMonth() + 1)
            this.stringDate.mañana4 = this.returnStringMonth(operationDate(4, true).getMonth() + 1)
        },
        
        /**
         * @function 
         * ## Actualiza el calendario una vez que haya recibido true 
         * del evento emitido desde el componente consulta
         */

        updatedCalendar (info) {
            if (info === true) {
                this.showConsult()
            }
        },

        /**
         * @function 
         * ## Guarda en un array las consultas del calendario activo
         * es decir de hoy, ayer y mañana
         */

        consultWeek () {
            this.consultasAntes = []
            this.consultasHoy = []
            this.consultasSiguientes = []
            this.consultasSiguientes2 = []; this.consultasSiguientes3 = []; this.consultasSiguientes4 = []

            this.todasConsultas.forEach(consult => {
                // console.log(consult.date.split("-").reverse().join("/"), 
                // ` Antes     ${this.dayBeforeNumber.toString().length === 1 ? '0'+this.dayBeforeNumber.toString() : this.dayBeforeNumber}/${this.todayMonthBefore.toString().length === 1 ? '0'+this.todayMonthBefore.toString() : this.todayMonthBefore}/${this.currentYearBefore}`,
                // ` Hoy       ${this.dayMonth.toString().length === 1 ? '0'+this.dayMonth.toString() : this.dayMonth}/${this.todayMonth.toString().length === 1 ? '0'+this.todayMonth.toString() : this.todayMonth}/${this.currentYear}`,
                // ` Despues   ${this.dayAfterNumber.toString().length === 1 ? '0'+this.dayAfterNumber.toString() : this.dayAfterNumber}/${this.todayMonthAfter.toString().length === 1 ? '0'+this.todayMonthAfter.toString() : this.todayMonthAfter}/${this.currentYearAfter}`)
                   
                console.log(consult.date, formatDate(operationDate(false)))
                if (consult.date == formatDate(operationDate(1, false))) this.consultasAntes.push(consult); // restar
                if (consult.date == formatDate(operationDate(false))) this.consultasHoy.push(consult); // hoy 
                if (consult.date == formatDate(operationDate(1, true))) this.consultasSiguientes.push(consult); // sumar
                if (consult.date == formatDate(operationDate(2, true))) this.consultasSiguientes2.push(consult); // sumar
                if (consult.date == formatDate(operationDate(3, true))) this.consultasSiguientes3.push(consult); // sumar
                if (consult.date == formatDate(operationDate(4, true))) this.consultasSiguientes4.push(consult); // sumar
            })

            // --- Odernar array por las horas
            this.consultasHoy.sort(function (a, b) {
                if (a.schedule ? a.schedule.check_time : a.schedule > b.schedule ? b.schedule.check_time : b.schedule) return 1;
                if (a.schedule ? a.schedule.check_time : a.schedule < b.schedule ? b.schedule.check_time : b.schedule) return -1;
                return 0
            })

            this.consultasAntes.sort(function (a, b) {
                if (a.schedule ? a.schedule.check_time : a.schedule > b.schedule ? b.schedule.check_time : b.schedule) return 1;
                if (a.schedule ? a.schedule.check_time : a.schedule < b.schedule ? b.schedule.check_time : b.schedule) return -1;
                return 0
            })
           
            this.consultasSiguientes.sort(function (a, b) {
                if (a.schedule ? a.schedule.check_time : a.schedule > b.schedule ? b.schedule.check_time : b.schedule) return 1;
                if (a.schedule ? a.schedule.check_time : a.schedule < b.schedule ? b.schedule.check_time : b.schedule) return -1;
                return 0
            })
            this.consultasSiguientes2.sort(function (a, b) {
                if (a.schedule ? a.schedule.check_time : a.schedule > b.schedule ? b.schedule.check_time : b.schedule) return 1;
                if (a.schedule ? a.schedule.check_time : a.schedule < b.schedule ? b.schedule.check_time : b.schedule) return -1;
                return 0
            })
            this.consultasSiguientes3.sort(function (a, b) {
                if (a.schedule ? a.schedule.check_time : a.schedule > b.schedule ? b.schedule.check_time : b.schedule) return 1;
                if (a.schedule ? a.schedule.check_time : a.schedule < b.schedule ? b.schedule.check_time : b.schedule) return -1;
                return 0
            })
            this.consultasSiguientes4.sort(function (a, b) {
                if (a.schedule ? a.schedule.check_time : a.schedule > b.schedule ? b.schedule.check_time : b.schedule) return 1;
                if (a.schedule ? a.schedule.check_time : a.schedule < b.schedule ? b.schedule.check_time : b.schedule) return -1;
                return 0
            })
        },

        /**
         * @function 
         * ## Esta funcion te devuelve los dias del mes, de acuerdo al parametro que recibe
         * ## El parametro es el mes que tiene que ser del 1 al 12
         */

        dateMonth (month, year = new Date().getFullYear()) {
            // Cuantos dias al mes existen 
            switch (month) {
            case 1:
                this.todayMonthDay = 31 // Enero
                break;
            case 2:
                if (year % 4 === 0) this.todayMonthDay = 29;
                this.todayMonthDay = 28 // Febrero
                break;
            case 3:
                this.todayMonthDay = 31 // Marzo
                break;
            case 4:
                this.todayMonthDay = 30 // Abril
                break;
            case 5:
                this.todayMonthDay = 31 // Mayo
                break;
            case 6:
                this.todayMonthDay = 30 // Junio
                break;
            case 7:
                this.todayMonthDay = 31 // Julio
                break;
            case 8:
                this.todayMonthDay = 31 // Agosto
                break;
            case 9:
                this.todayMonthDay = 30 // Setiembre
                break;
            case 10:
                this.todayMonthDay = 31 // Octubre
                break;
            case 11:
                this.todayMonthDay = 30 // Noviembre
                break;
            case 12:
                this.todayMonthDay = 31 // Diciembre
                break;
            }

            return this.todayMonthDay; 
        },

        /**
         * @function 
         * ## Esta funcrion de devuelve el nombre del mes de acuerdo al mes que recibe como 
         * parametro
         */

        returnStringMonth (month) {
            switch (month) {
            case 1:
                return 'Enero'
            case 2:
                return 'Febrero'
            case 3:
                return 'Marzo'
            case 4:
                return 'Abril'
            case 5:
                return 'Mayo'
            case 6:
                return 'Junio'
            case 7:
                return 'Julio'
            case 8:
                return 'Agosto'
            case 9:
                return 'Setiembre'
            case 10:
                return 'Octubre'
            case 11:
                return 'Noviembre'
            case 12:
                return 'Diciembre'
            }
        },

        /**
         * @function 
         * ## Esta funcion reestablece al dia de hoy en que estamos
         */

        now () {
            this.todayDay = new Date().getDay();

            this.dayMonth = new Date().getDate();
            
            this.todayMonth = new Date().getMonth() + 1

            this.getDay()

            // this.dayBeforeNumber = this.dayMonth - 1
            // this.dayAfterNumber = this.dayMonth + 1

            // this.consultasAntes = []
            // this.consultasHoy = []
            // this.consultasSiguientes = []

            if (this.dayActive) {
                this.consultaActiva(this.tipoConsulta, true)
            } else {
                this.showConsult()
                this.dayActive = true;
            }
        },

        /**
         * @function
         * ## Esta funcion esta vinculado con el calendario que se activara 
         * cuando hay un cambio evento (change)
         */

        async calendarActive (event) {
            await this.axios.get(`/api/appoitmentShow/${this.idUser}/${event.target.value}`)
            .then(res => {
                let arrDate = event.target.value.split('-')
                let mes = parseInt(arrDate[1])
                let dia = parseInt(arrDate[2])
                this.dayActive = false;

                this.stringDate.stringDateNow = this.returnStringMonth(mes)
                this.dayMonth = dia

                this.consultas = []
                this.consultasAntes = []
                this.consultasSiguientes = []

                this.consultasHoy = res.data.filter(consult => consult.date === event.target.value)
            })
            .catch(error => {
                console.log(error)
            })
        },

        /**
         * @function 
         * ## Esta funcion devuelve la consulta que estan pendientes o todas las consultas
         * ## Recibe un parametro true o false para saber si devolver todas las consultas 
         * o solamente las consultas pendientes guardando en un array y que pintara en el 
         * calendario
         * @true consultas pendientes
         * @false todas las consultas
         */

        consultaActiva (param, element) {
            if (param) {
                if (element && event.target.matches(".btnConsultas")) {
                    if (event.target.matches(".btnConsultasActive")) return 
                    event.target.classList.add("btn-primary");
                    document.querySelector(".btnConsultasActive").classList.remove("btn-primary")
                    document.querySelector(".btnConsultasActive").classList.remove("btnConsultasActive")
                    event.target.classList.add("btnConsultasActive")

                    this.tipoConsulta = true;
                }
                // console.log("consultas pendientes")
                this.todasConsultas = this.consultas.filter(consult => consult.status === 1 || consult.status === 2);
                this.consultWeek()
            } else {
                if (element && event.target.matches(".btnConsultas")) {
                    if (event.target.matches(".btnConsultasActive")) return;
                    event.target.classList.add("btn-primary");
                    document.querySelector(".btnConsultasActive").classList.remove("btn-primary")
                    document.querySelector(".btnConsultasActive").classList.remove("btnConsultasActive")
                    event.target.classList.add("btnConsultasActive")

                    this.tipoConsulta = false;
                }

                this.todasConsultas = this.consultas.filter(consult => consult.status === 1 || consult.status === 2 || consult.status === 3);
                this.consultWeek()
            }
        },

        evolucionModal (info) {
            return this.datosEvolucion = info;
        },

        getInfoModal (info) {
            console.log(info)
            return this.infoModal = info;
        }
    },

    props: {
        dataConsult: Array,
        dataModal: Object,
        idUser: String,
        dataUser: Object,
        datosIdEvolucion: Object
    },

    created () {
        this.showConsult();
        this.getDay()
    },
}
</script>


<style scoped>
    .consult-content {
        background: #fff;
    }
    .card {
        border: none;
        background: none;
    }
    .now {
        color: #6236FF !important;
        transform: scale(1.2);
    }
    .notNow {
        transform: scale(.85);
    }

    .now h2 {
        font-weight: 600;
        margin: 0;
    }

    .notNow h2 {
        font-weight: 600;
        margin: 0;
    }

    .btn-hover:not(.active):hover {
        background: #eee;
    }

    .gap {
        gap: 5px;
    }

    @media screen and (max-width: 750px) {
        .before, .after {
            display: none;
        }

        .main-header {
            flex-direction: column;
        }

        .consultas-btn-top {
            flex-direction: column;
            gap: 50px;
        }

        .fecha-restablecer {
            flex-wrap: wrap;
        }
    }
</style>