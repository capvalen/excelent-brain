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
          <th>Club</th>
          <th>Semáforo</th>
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
						<button class="btn btn-light" v-if="patients.club=='0'" data-bs-toggle="modal" data-bs-target="#editarClub" @click="datosLike(patients.club, patients.id)"><i class="fa-regular fa-hand-back-fist"></i></button>
						<button class="btn btn-primary" v-if="patients.club=='1'" data-bs-toggle="modal" data-bs-target="#editarClub" @click="datosLike(patients.club, patients.id)"><i class="fa-solid fa-thumbs-up"></i></button>
						<button class="btn btn-danger" v-if="patients.club=='2'" data-bs-toggle="modal" data-bs-target="#editarClub" @click="datosLike(patients.club, patients.id)"><i class="fa-solid fa-thumbs-down"></i></button>
					</td>
					<td>
						<div v-if="patients.semaforo[0]">
							<button v-if="patients.semaforo[0].codigo==1" class="btn btn-primary btn-circle btn-md" data-toggle="modal" data-target="#modalVerEstados" @click="dataProps(patients)">
								<span  title="Normal"><i class="fas fa-smile"></i></span>
							</button>
							<button v-if="patients.semaforo[0].codigo==2" class="btn btn-success btn-circle btn-md" data-toggle="modal" data-target="#modalVerEstados" @click="dataProps(patients)">
								<span title="Excelente"> <i class="fas fa-laugh-wink"></i> </span>
							</button>
							<button v-if="patients.semaforo[0].codigo==3" class="btn btn-danger btn-circle btn-md" data-toggle="modal" data-target="#modalVerEstados" @click="dataProps(patients)">
								<span title="Exigente"> <i class="fas fa-meh"></i> </span>
							</button>
							<button v-if="patients.semaforo[0].codigo==4" class="btn btn-warning btn-circle btn-md" data-toggle="modal" data-target="#modalVerEstados" @click="dataProps(patients)">
								<span title="Deudor"> <i class="fas fa-frown-open"></i> </span>
							</button>
							<button v-if="patients.semaforo[0].codigo==5" class="btn btn-info btn-circle btn-md" data-toggle="modal" data-target="#modalVerEstados" @click="dataProps(patients)">
								<span title="Insatisfecho"> <i class="fas fa-angry"></i> </span>
							</button>
							<button v-if="patients.semaforo[0].codigo==6" class="btn btn-danger btn-circle btn-md" data-toggle="modal" data-target="#modalVerEstados" @click="dataProps(patients)">
								<span title="Peligroso"> <i class="fas fa-frown"></i> </span>
							</button>
						</div>
						<div v-else>
							<button class="btn btn-secondary btn-circle btn-md" data-toggle="modal" data-target="#modalVerEstados" @click="dataProps(patients)">
								<span title="Normal sin registro"><i class="fas fa-smile"></i></span>
							</button>
						</div>
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
		<ModalCambiarLike :like="like" :id="id" @updateLike="Like"></ModalCambiarLike>
		
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
import ModalCambiarLike from './ModalCambiarLike.vue'

export default {
  name: 'Pacientes',

  data () {
    return {
      dataPatients: [],
      data: null, dataTriajes:null,
      busqueda: [], like:0, id:-1,
      totalPatients:[], profesionales:[],
			estados:[
				{id: 1, valor: 'Neutro'},
				{id: 2, valor: 'excelente'},
				{id: 3, valor: 'promotor'},
				{id: 4, valor: 'wow'},
				{id: 5, valor: 'reprogramador'},
				{id: 6, valor: 'exigente'},
				{id: 7, valor: 'deudor'},
				{id: 8, valor: 'insatisfecho'},
				{id: 9, valor: 'peligroso'},
			]
    }
  },

  components: { ModalEditPatient, ModalRecetas, ModalFaltas, ModalTriaje, ModalVerTriajesViejos, ModalNewPatient, ModalVerEstados, ModalCambiarLike },

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

		Like(valores){
			let index = this.busqueda.findIndex(b=> b.id ==valores.id)
			this.busqueda[index].club = valores.seleccionado;
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
		datosLike(like, id){
			this.like = like;
			this.id = id;
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
