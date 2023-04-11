<template>
    <main>
        <div class="d-sm-flex align-items-center justify-content-between mb-1">
            <h1 class="h3 mb-0 text-gray-800">Historias Clínicas</h1>
        </div>
        <div class="d-sm-flex align-items-center justify-content-between flex-wrap mt-2 gap-10">
            <form class="d-sm-inline-block form-inline form-search-historia" @submit.prevent>
                <div class="input-group">
                    <input
                    type="text"
                    id="searchHistoriaProfesional"
                    class="form-control bg-white shadow-sm border-0 small"
                    placeholder="Buscar..."
                    aria-label="Search"
                    aria-describedby="basic-addon2"
                    @keyup="searchHistoria"
                    >
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" @click="searchHistoria()">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>
            <div class="d-flex align-items-center">
                <p class="m-0 mx-1">Ordenar por: </p>
                <ul class="nav nav-pills" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button
                    class="btn btn-sm btn-warning order-active"
                    id="pills-home-tab"
                    data-bs-toggle="pill"
                    data-bs-target="#pills-home"
                    type="button"
                    role="tab"
                    aria-controls="pills-home"
                    aria-selected="true"
                    data-orden="true"
                    @click="ordenarAsc()"
                    >
                    Ascendente
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button
                    class="btn btn-sm btn-light"
                    id="pills-profile-tab"
                    data-bs-toggle="pill"
                    data-bs-target="#pills-profile"
                    type="button"
                    role="tab"
                    aria-controls="pills-profile"
                    aria-selected="false"
                    data-orden="false"
                    @click="ordenarDesc()"
                    >
                    Descendente
                    </button>
                </li>
                </ul>
            </div>
        </div>

        <div class="row historia-card">
            <div class="" v-for="(historia, index) in busqueda" :key="index">
                <div class="card h-100 shadow">
                    <!-- Card Header - Dropdown -->
                    <div v-if="historia.discharge != 1"
                        class="card-header bg-warning py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-white">Código de Paciente: {{ historia.id }}</h6>
                    </div>
                    <div v-else
                        class="card-header bg-success py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-white">Código de Paciente: {{ historia.id }}</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="historia-info">
                            <p class="text-capitalize">Historia de: {{ historia.name ? lowerCase(historia.name) : '...' }}</p>
                            <p v-if="historia.discharge == 1"
                            class="text-success"
                            >Paciente dado de alta</p>
                            <p 
                            v-if="!historia.initial_psychological_history 
                            && dataUser.profession === 'Psicólogo'"
                            class="text-danger"
                            >
                            Sin historia inicial de psicología
                            </p>

                            <p 
                            v-if="!historia.initial_psychiatric_history 
                            && dataUser.profession === 'Psiquiatra'"
                            class="text-danger"
                            >Sin historia inicial de psiquiatra</p>

                            <div class="w-100 d-flex align-items-center gap-10">
                                <a
                                class="btn btn-primary btn-circle opacity-50 cursor-disabled" 
                                title="Sin historia inicial de psicologia"
                                v-if="!historia.initial_psychological_history 
                                && dataUser.profession === 'Psicólogo'"
                                >
                                <i class="fas fa-portrait"></i>
                                </a>
                                <!-- 
                                    *
                                    * BTN 
                                    *
                                    *
                                 -->
                                <a 
                                class="btn btn-primary btn-circle opacity-50 cursor-disabled" 
                                title="Sin historia inicial de psiquiatra"
                                v-else-if="!historia.initial_psychiatric_history 
                                && dataUser.profession === 'Psiquiatra'"
                                >
                                <i class="fas fa-portrait"></i>
                                </a>
                                <!-- 
                                    *
                                    * BTN
                                    *
                                    *
                                 -->
                                <router-link 
                                class="btn btn-primary btn-circle" 
                                :to="`evoluciones/${historia.id}`"
                                title="Ver evoluciones"
                                v-else
                                >
                                <i class="fas fa-portrait"></i>
                                </router-link>
                                
                                <a 
                                :href="`/api/pdfEvolution/${historia.id}`" 
                                class="btn btn-primary btn-circle"
                                title="Generar pdf para evoluciones"
                                target="_blank"
                                >
                                <i class="fas fa-file-pdf"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<!-- Promise.all([
    this.getHistoriesPsychiatric(),
    this.getHistoriesPsychological(),
])
.then(values => {
    // console.log(values)
})
.catch(err => {
    console.log(err)
}) -->

<script>
import StorieModal from './StorieModal.vue'
export default {
    name: 'home-stories',

    data () {
        return {
            historias: {},
            busqueda: null
        }
    },

    props: {
        dataUser: Object
    },

    components: { StorieModal },

    methods: {
        async getPatient () {
            this.axios.get('/api/patient')
            .then(res => {
                this.historias = res.data
                this.busqueda = this.historias
                this.busqueda = this.busqueda.slice(0,6)
            })
            .catch(err => {
                console.log(err)
            })
        },

        searchHistoria () {
            let $valueInput = document.getElementById('searchHistoriaProfesional')

            $valueInput.value === ''
                ? this.getPatient()
                : this.busqueda = this.historias.filter((el, index) => el.name.match(new RegExp(`${$valueInput.value}`,'ig')) ? el : null)

            this.busqueda = this.busqueda.slice(0,6)
        },

        lowerCase (text) {
            return text.toLowerCase();
        },

        ordenarAsc () {
            if (!this.busqueda) return;

            if (event.target.dataset.orden == "true") return;

                document.querySelector(".order-active").dataset.orden = "false"
                document.querySelector(".order-active").classList.remove("btn-warning")
                document.querySelector(".order-active").classList.remove("order-active")

                event.target.dataset.orden = "true"
                event.target.classList.add("order-active")
                event.target.classList.add("btn-warning")

                this.asc
        },

        ordenarDesc () {
            if (!this.busqueda) return;

            if (event.target.dataset.orden == "true") return;

            document.querySelector(".order-active").dataset.orden = "false"
            document.querySelector(".order-active").classList.remove("btn-warning")
            document.querySelector(".order-active").classList.remove("order-active")

            event.target.dataset.orden = "true"
            event.target.classList.add("order-active")
            event.target.classList.add("btn-warning")

            this.desc

            // if (event.target.dataset.orden == "true") {
                // }
        }
    },

    computed: {
        asc () {
            return this.busqueda = this.busqueda.reverse();
        },

        desc () {
            return this.busqueda = this.busqueda.reverse();
        }
    },

    mounted () {
        this.getPatient()
    }
}
</script>

<style>
.historia-card {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    grid-gap: 25px;
    padding: 15px;
}

.historia-info {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.text-capitalize {
    text-transform: lowercase !important;
    text-transform: capitalize !important;
}

.gap-10 {
    gap: 10px;;
}

.form-search-historia {
    flex: 1 0 auto;
}

.opacity-50 {
    opacity: .5;
} 

.cursor-disabled {
    cursor: not-allowed !important;
}
</style>
