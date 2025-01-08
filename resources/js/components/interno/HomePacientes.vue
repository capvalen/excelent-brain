<template>
  <main>
    <div class="d-sm-flex align-items-center justify-content-around mt-4">
        <div class="d-none d-sm-inline-block form-inline w-100">
            <div class="input-group">
                <div class="input-group-prepend">
                    <button class="btn btn-success shadow-sm" type="button" @click="searchPatients()">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
                <input
                type="text"
                class="form-control bg-white shadow-sm border-0 small"
                id="searchNamePatient"
                placeholder="Buscar por apellidos, nombres o DNI..."
                aria-describedby="basic-addon2" @keyup.enter="searchPatients()" autocomplete="off"
                >
            </div>
        </div>
    </div>
		<button class="btn btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#modalNewPatient"><i class="fas fa-user-nurse"></i> Crear paciente nuevo</button>
		<p class="mt-3 mb-1">Últimos 20 pacientes registrados</p>
    <table class="table table-striped mt-4">
      <thead>
        <tr>
          <th>N°</th>
          <th>Nombre y apellidos</th>
          <th>Hobbies</th>
          <th>Triaje</th>
          <th>Plan de Seguridad</th>

        </tr>
      </thead>
      <tbody>
        <tr
        v-for="(patients, index) in busqueda"
        :key = "index"
        >
          <th>{{ index+1 }}</th>
          <td class="text-capitalize" ><span v-if="patients.vivo==0"><i class="fas fa-cross"></i></span> {{ patients.name ? lowerCase(patients.name) : 'Sin nombre' }} {{ lowerCase(patients.nombres) }}</td>
					<td>
						<button v-if="patients.vivo==1"  class="btn btn-outline-primary btn-circle btn-md" data-bs-toggle="modal" data-bs-target="#modalVerHobbies" @click="misHobbies=JSON.parse(patients.hobbies); queId = patients.id">
							<i class="fa-solid fa-baseball-bat-ball"></i>
						</button>
					</td>
					<td>
						<button class="btn btn-secondary btn-circle btn-md" data-bs-toggle="modal" data-bs-target="#modalVerTriajesViejos" title="Historial de Triajes" @click="verTriajesViejos(index)">{{ patients.triajes.length }}</button>
						<button v-if="patients.vivo==1"  class="btn btn-info btn-circle btn-md" data-bs-toggle="modal" @click="dataProps(patients)" data-bs-target="#modalTriaje"><i class="fas fa-file-medical-alt"></i></button>
					</td>
          <td>
						<button class="btn btn-secondary btn-circle btn-md" data-bs-toggle="modal" data-bs-target="#modalPlanSeguridad" title="Plan de Seguridad" @click="verTriajesViejos(index)">{{ patients.triajes.length }}</button>
						<button v-if="patients.vivo==1"  class="btn btn-info btn-circle btn-md" data-bs-toggle="modal" @click="dataProps(patients)" data-bs-target="#modalTriaje"><i class="fas fa-file-medical-alt"></i></button>
					</td>
        </tr>
      </tbody>
    </table>

    
    <modal-triaje v-if="data" :dataPatient="data" :profesionales="profesionales" ></modal-triaje>
    <modal-new-patient ></modal-new-patient>
		<modal-ver-triajes-viejos :triajes="dataTriajes"></modal-ver-triajes-viejos>
		<ModalVerHobbies :hobbies="hobbies" :id="queId" :misHobbies="misHobbies" ></ModalVerHobbies> 

  </main>
</template>

<script>

import ModalTriaje from './../recepcionista/components/pacientes/ModalTriaje.vue'
import ModalVerTriajesViejos from './../recepcionista/components/pacientes/ModalVerTriajesViejos.vue'
import ModalNewPatient from './../recepcionista/components/pacientes/ModalNewPatient.vue'
import ModalVerHobbies from './../recepcionista/components/pacientes/reportes/ModalVerHobbies.vue'

export default {
  name: 'Pacientes',

  data () {
    return {
      dataPatients: [],
      data: null,dataTriajes:null, queId:-1,
      busqueda: [],
      totalPatients:[], profesionales:[],
			hobbies:['pintura','dibujo', 'fotografía', 'tejido', 'costura', 'joyería', 'senderismo', 'acampar', 'jardinería', 'pesca', 'ciclismo', 'deportes', 'fútbol', 'basket', 'tenis', 'ajedrez', 'juegos de mesa', 'billar', 'música', 'tocar un instrumento', 'canto', 'composición musical', 'producción musical', 'gastronomía', 'cocina', 'recetas', 'horneado', 'postres', 'manualidades', 'origami', 'modelodo en arcilla', 'creación', 'natación', 'surf', 'kayac', 'buceo', 'esquí', 'tecnología', 'programación', 'robótica', 'computación', 'edición de videos', 'diseño gráfico', 'coleccionismo', 'monedas', 'vinilos', 'baile', 'danzas', 'escritura', 'periodismo', 'poesía', 'libros', 'lectura', 'cuentos', 'idiomas', 'viajes', 'exploración de lugares', 'fitnes', 'gym', 'yoga', 'pilates', 'entrenamiento', 'meditación', 'voluntariado', 'mascotas', 'animalista', 'astronomía', 'jardinería', 'plantas', 'huertos', 'paisajes', 'cine', 'series', 'novelas'], misHobbies:[],
    }
  },

  components: { ModalTriaje, ModalVerTriajesViejos, ModalNewPatient, ModalVerHobbies },

  props: {
    dataPatient: Object
  },

  methods: {

    updateFaults(id,faults){
      if(confirm('Estás seguro de agregar una falta a este usuario?')){
        this.axios.get(`/api/updateFaults/${id}/${faults}`)
          .then(res => {
            this.getPatients()
          })
        }
    },
    async getPatients () {
      this.busqueda = []
      await this.axios.get(`/api/getLast10Patients`)
      .then(res => { 
        this.dataPatients = res.data;
        this.busqueda = this.dataPatients;

        this.busqueda = [];
        this.dataPatients.forEach((el, index) => {
          //if (index < 5) {
            this.busqueda.push(el);
          //}
        })

      })
      .catch(err => {
        console.error(err)
      })
    },

    async searchPatients () {
      let valueInput = document.getElementById("searchNamePatient").value; 
      if(valueInput === ''){
        this.getPatients()
      }else{
        let coincidenceDni = []

				await this.axios.get(`/api/searchPatientByNameDni/${valueInput}`)
				.then(res => {
					console.log(res.data);
					this.totalPatients = res.data;

					coincidenceDni = this.totalPatients.filter(el => el.dni == valueInput)        

					if(coincidenceDni.length > 0){
						this.busqueda = coincidenceDni
					}else{
					const words = valueInput.split(' ')
					words.forEach((word)=>{
						if(/[0-9]/.test(word)){
							this.totalPatients = this.totalPatients.filter(el => el.dni != null && el.dni.matches(word) ? el : null)
							this.busqueda.push(...this.totalPatients)
						}
						this.totalPatients = this.totalPatients.filter(el => el.name.match(new RegExp(`${word}`,'ig')) ? el : null)
						console.log(this.totalPatients)
						this.busqueda.push(...this.totalPatients)
						//const coincidence = this.dataPatients.filter(el => el.name.match(new RegExp(`${word}`,'ig')).split(' ') ? el : null)
						//
					})        
					this.busqueda = this.totalPatients    
					}
				})
				.catch(err => {
					console.error(err)
				})
      }
    },

    dataProps (data) {
      this.data = data;
			this.nuevo = false;
    },
    lowerCase (text = '...') {
      return text.toLowerCase();
    },
		async listarprofesional(){
      await this.axios.get('/api/profesional')
      .then(response => {
        this.profesionales=response.data;
      })
    },
		verTriajesViejos(index){
			this.dataTriajes = this.busqueda[index].triajes;
		}
  },


  created () {
    this.getPatients();
		this.listarprofesional();
		this.hobbies.sort();
  }
}
</script>
