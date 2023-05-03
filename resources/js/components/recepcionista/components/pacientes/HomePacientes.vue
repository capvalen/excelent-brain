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
                aria-describedby="basic-addon2" @keyup.enter="searchPatients()"
                >
            </div>
        </div>
    </div>
		<button class="btn btn-outline-primary mt-2" data-toggle="modal" data-target="#modalNewPatient"><i class="fas fa-user-nurse"></i> Crear paciente nuevo</button>
		<p class="mt-3 mb-1">Últimos 20 pacientes registrados</p>
    <table class="table table-striped mt-4">
      <thead>
        <tr>
          <th>N°</th>
          <th>Nombre y apellidos</th>
          <th>Semáfoto</th>
          <th>Triaje</th>
          <th>Faltas</th>
          <th>Recetas</th>
          <th>Editar</th>
        </tr>
      </thead>
      <tbody>
        <tr
        v-for="(patients, index) in busqueda" :key = "index">
          <th>{{ index+1 }}</th>
          <td class="text-capitalize" >{{ patients.name ? lowerCase(patients.name) : 'Sin nombre' }}</td>
					<td>
						<button class="btn btn-success btn-circle btn-md" data-toggle="modal" data-target="#modalVerEstados" @click="dataProps(patients)">
							<span title="normal"><i class="fas fa-smile"></i></span>
						</button>
					</td>
					<td>
						<button class="btn btn-secondary btn-circle btn-md" data-toggle="modal" data-target="#modalVerTriajesViejos" title="Historial de Triajes" @click="verTriajesViejos(index)">{{ patients.triajes.length }}</button>
						<button class="btn btn-info btn-circle btn-md" data-toggle="modal" @click="dataProps(patients)" data-target="#modalTriaje" title="Nuevo triaje"><i class="fas fa-file-medical-alt"></i></button>
					</td>
          <td>
            <button
            class="btn btn-info btn-circle btn-md"
            @click="updateFaults(patients.id ,patients.faults)"
            >
            {{ patients.faults }}
            </button>
          </td>
          <td>
            <button
            class="btn btn-info btn-circle btn-md"
            @click="dataProps(patients)"
            data-toggle="modal"
            data-target="#recetasModal"
            >
            <i class="fas fa-file-medical-alt"></i></button>
          </td>
          <td>
            <button
            class="btn btn-info btn-circle btn-md"
            @click="dataProps(patients)"
            data-toggle="modal"
            data-target="#patientModal"
            >
              <i class="fas fa-newspaper"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <modal-edit-patient v-if="data" :dataPatient="data"></modal-edit-patient>
    <modal-recetas v-if="data" :dataPatient="data"></modal-recetas>
    <modal-faltas v-if="data" :dataPatient="data"></modal-faltas>
    <modal-triaje v-if="data" :dataPatient="data" :profesionales="profesionales"></modal-triaje>
		<modal-ver-triajes-viejos :triajes="dataTriajes"></modal-ver-triajes-viejos>
    <modal-new-patient ></modal-new-patient>
		<modal-ver-estados :dataPatient="data" :estados="estados"></modal-ver-estados>
		
  </main>
</template>

<script>
import ModalEditPatient from './ModalEditPatients.vue';
import ModalRecetas from './ModalRecetas.vue';
import ModalFaltas from './ModalFaltas.vue';
import ModalTriaje from './ModalTriaje.vue';
import ModalVerTriajesViejos from './ModalVerTriajesViejos.vue'
import ModalNewPatient from './../pacientes/ModalNewPatient.vue'
import ModalVerEstados from './ModalVerEstados.vue'

export default {
  name: 'Pacientes',

  data () {
    return {
      dataPatients: [],
      data: null, dataTriajes:null,
      busqueda: [],
      totalPatients:[], profesionales:[],
			estados:[
				{id: 1, valor: 'normal'},
				{id: 2, valor: 'excelente'},
				{id: 3, valor: 'exigente'},
				{id: 4, valor: 'deudor'},
				{id: 5, valor: 'insatisfecho'},
				{id: 6, valor: 'peligroso'},
			]
    }
  },

  components: { ModalEditPatient, ModalRecetas, ModalFaltas, ModalTriaje, ModalVerTriajesViejos, ModalNewPatient, ModalVerEstados },

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
  }
}
</script>
