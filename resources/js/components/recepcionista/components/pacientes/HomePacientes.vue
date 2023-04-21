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
		<p class="mt-3 mb-1">Últimos 20 pacientes registrados</p>
    <table class="table table-striped mt-4">
      <thead>
        <tr>
          <th>N°</th>
          <th>Nombre y apellidos</th>
          <th>Triaje</th>
          <th>Faltas</th>
          <th>Recetas</th>
          <th>Editar</th>
        </tr>
      </thead>
      <tbody>
        <tr
        v-for="(patients, index) in busqueda"
        :key = "index"
        >
          <th>{{ index+1 }}</th>
          <td class="text-capitalize" >{{ patients.name ? lowerCase(patients.name) : 'Sin nombre' }}</td>
					<td>
						<button class="btn btn-info btn-circle btn-md" data-toggle="modal" @click="dataProps(patients)" data-target="#modalTriaje"><i class="fas fa-file-medical-alt"></i></button>
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
  </main>
</template>

<script>
import ModalEditPatient from './ModalEditPatients.vue';
import ModalRecetas from './ModalRecetas.vue';
import ModalFaltas from './ModalFaltas.vue';
import ModalTriaje from './ModalTriaje.vue';

export default {
  name: 'Pacientes',

  data () {
    return {
      dataPatients: [],
      data: null,
      busqueda: [],
      totalPatients:[], profesionales:[]
    }
  },

  components: { ModalEditPatient, ModalRecetas, ModalFaltas, ModalTriaje },

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
  },


  created () {
    this.getPatients();
		this.listarprofesional();
  }
}
</script>
