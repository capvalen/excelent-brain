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
                <input type="text" class="form-control bg-white shadow-sm border-0 small" id="searchNamePatient"
                placeholder="Buscar por apellidos, nombres o DNI..." autocomplete="off" @keyup.enter="searchPatients()">
            </div>
        </div>
    </div>
		<button class="btn btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#modalNewPatient"><i class="fas fa-user-nurse"></i> Crear paciente nuevo</button>
		<p class="mt-3 mb-1">Últimos 20 pacientes registrados</p>
    <table class="table table-hover mt-4">
      <thead>
        <tr>
          <th>N°</th>
          <th>Nombre y apellidos</th>
          <th>Hobbie</th>
          <th>Club</th>
          <th>Semáforo</th>
          <th>Triaje</th>
          <th>Faltas</th>
          <th>Reprog.</th>
          <th>Recetas</th>
        </tr>
      </thead>
      <tbody>
        <tr
        v-for="(paciente, index) in busqueda" :key = "index">
          <th>{{ index+1 }}</th>
          <td class="text-capitalize" @click="dataProps(paciente)" data-bs-toggle="modal" data-bs-target="#patientModal" style="cursor:pointer">{{ paciente.name ? lowerCase(paciente.name) : 'Sin nombre' }}</td>
					<td>
						<button class="btn btn-outline-primary btn-circle btn-md" data-bs-toggle="modal" data-bs-target="#modalVerHobbies" @click="misHobbies=JSON.parse(paciente.hobbies); queId = paciente.id">
							<i class="fa-solid fa-baseball-bat-ball"></i>
						</button>
					</td>
					<td>
						<button class="btn btn-light" v-if="paciente.club=='0'" data-bs-toggle="modal" data-bs-target="#editarClub" @click="datosLike(paciente.club, paciente.id)"><i class="fa-regular fa-hand-back-fist"></i></button>
						<button class="btn btn-primary" v-if="paciente.club=='1'" data-bs-toggle="modal" data-bs-target="#editarClub" @click="datosLike(paciente.club, paciente.id)"><i class="fa-solid fa-thumbs-up"></i></button>
						<button class="btn btn-danger" v-if="paciente.club=='2'" data-bs-toggle="modal" data-bs-target="#editarClub" @click="datosLike(paciente.club, paciente.id)"><i class="fa-solid fa-thumbs-down"></i></button>
					</td>
					<td>
						<div v-if="paciente.semaforo[0]">
							<button v-if="paciente.semaforo[0].codigo==1" class="btn btn-primary btn-circle btn-md" data-bs-toggle="modal" data-bs-target="#modalVerEstados" @click="dataProps(paciente)">
								<span  title="Normal"><i class="fas fa-smile"></i></span>
							</button>
							<button v-if="paciente.semaforo[0].codigo==2" class="btn btn-success btn-circle btn-md" data-bs-toggle="modal" data-bs-target="#modalVerEstados" @click="dataProps(paciente)">
								<span title="Excelente"> <i class="fas fa-laugh-wink"></i> </span>
							</button>
							<button v-if="paciente.semaforo[0].codigo==3" class="btn btn-success btn-circle btn-md" data-bs-toggle="modal" data-bs-target="#modalVerEstados" @click="dataProps(paciente)">
								<span title="Exigente"> <i class="fas fa-laugh-wink"></i> </span>
							</button>
							<button v-if="paciente.semaforo[0].codigo==4" class="btn btn-success btn-circle btn-md" data-bs-toggle="modal" data-bs-target="#modalVerEstados" @click="dataProps(paciente)">
								<span title="Deudor"> <i class="fas fa-laugh-wink"></i> </span>
							</button>
							<button v-if="paciente.semaforo[0].codigo==5" class="btn btn-warning btn-circle btn-md" data-bs-toggle="modal" data-bs-target="#modalVerEstados" @click="dataProps(paciente)">
								<span title="Insatisfecho"> <i class="fas fa-meh"></i> </span> 
							</button>
							<button v-if="paciente.semaforo[0].codigo==6" class="btn btn-warning btn-circle btn-md" data-bs-toggle="modal" data-bs-target="#modalVerEstados" @click="dataProps(paciente)">
								<span title="Peligroso"> <i class="fas fa-meh"></i> </span>
							</button>
							<button v-if="paciente.semaforo[0].codigo==7" class="btn btn-danger btn-circle btn-md" data-bs-toggle="modal" data-bs-target="#modalVerEstados" @click="dataProps(paciente)">
								<span title="Peligroso"> <i class="fas fa-angry"></i> </span>
							</button>
							<button v-if="paciente.semaforo[0].codigo==8" class="btn btn-warning btn-circle btn-md" data-bs-toggle="modal" data-bs-target="#modalVerEstados" @click="dataProps(paciente)">
								<span title="Peligroso"> <i class="fas fa-frown"></i> </span>
							</button>
							<button v-if="paciente.semaforo[0].codigo==9" class="btn btn-warning btn-circle btn-md" data-bs-toggle="modal" data-bs-target="#modalVerEstados" @click="dataProps(paciente)">
								<span title="Peligroso"> <i class="fas fa-frown"></i> </span>
							</button>
						</div>
						<div v-else>
							<button class="btn btn-secondary btn-circle btn-md" data-bs-toggle="modal" data-bs-target="#modalVerEstados" @click="dataProps(paciente)">
								<span title="Normal sin registro"><i class="fas fa-smile"></i></span>
							</button>
						</div>
					</td>
					<td>
						<button class="btn btn-outline-secondary btn-circle btn-md" data-bs-toggle="modal" data-bs-target="#modalVerTriajesViejos" title="Historial de Triajes" @click="verTriajesViejos(index)">{{ paciente.triajes.length }}</button>
						<button class="btn btn-outline-info btn-circle btn-md" data-bs-toggle="modal" @click="dataProps(paciente)" data-bs-target="#modalTriaje" title="Nuevo triaje"><i class="fa-solid fa-lungs"></i></button>
					</td>
          <td>
            <button class="btn btn-outline-info btn-circle btn-md" data-bs-toggle="modal" data-bs-target="#modalVerFaltas" @click="queId=paciente.id; cantFaltas = paciente.faults;" >{{ paciente.faults }} </button>
          </td>
					<td>
						<button class="btn btn-outline-secondary btn-circle btn-md" data-bs-toggle="modal" data-bs-target="#modalVerTriajesViejos" title="Historial de Triajes" @click="verReprogramacionesViejos(paciente.id)">{{ paciente.reprogramaciones }}</button>
					</td>
          <td>
            <button
            class="btn btn-outline-info btn-circle btn-md"
            @click="dataProps(paciente)"
            data-bs-toggle="modal"
            data-bs-target="#recetasModal"
            >
            <i class="fa-solid fa-flask-vial"></i></button>
          </td>
         
        </tr>
      </tbody>
    </table>

    <modal-edit-patient v-if="data" :dataPatient="data"></modal-edit-patient>
    <modal-recetas v-if="data" :dataPatient="data"></modal-recetas>
    <modal-faltas v-if="data" :dataPatient="data"></modal-faltas>
    <modal-triaje v-if="data" :dataPatient="data" :profesionales="profesionales"></modal-triaje>
		<modal-ver-triajes-viejos :triajes="dataTriajes"></modal-ver-triajes-viejos>
		<modal-ver-reprogramaciones-viejos :reprogramaciones="reprogramaciones"></modal-ver-reprogramaciones-viejos>
    <modal-new-patient ></modal-new-patient>
		<modal-ver-estados :dataPatient="data" :estados="estados"></modal-ver-estados>
		<ModalCambiarLike :like="like" :id="id" @updateLike="Like"></ModalCambiarLike>
		<ModalVerFaltas :queId="queId" :cantFaltas="cantFaltas"></ModalVerFaltas>
		<ModalVerHobbies :hobbies="hobbies" :id="queId" :misHobbies="misHobbies" ></ModalVerHobbies> 
		
  </main>
</template>

<script>
import ModalEditPatient from './ModalEditPatients.vue';
import ModalRecetas from './ModalRecetas.vue';
import ModalFaltas from './ModalFaltas.vue';
import ModalTriaje from './ModalTriaje.vue';
import ModalVerTriajesViejos from './ModalVerTriajesViejos.vue'
import ModalVerReprogramacionesViejos from './ModalVerReprogramacionesViejos.vue'
import ModalNewPatient from './../pacientes/ModalNewPatient.vue'
import ModalVerEstados from './ModalVerEstados.vue'
import ModalCambiarLike from './ModalCambiarLike.vue'
import ModalVerFaltas from './reportes/ModalVerFaltas.vue'
import ModalVerHobbies from './reportes/ModalVerHobbies.vue'

export default {
  name: 'Pacientes',

  data () {
    return {
      dataPatients: [], queId:null,
      data: null, dataTriajes:null,
      busqueda: [], like:0, id:-1, cantFaltas:-1, reprogramaciones:[],
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
			],
			hobbies:['pintura','dibujo', 'fotografía', 'tejido', 'costura', 'joyería', 'senderismo', 'acampar', 'jardinería', 'pesca', 'ciclismo', 'deportes', 'fútbol', 'basket', 'tenis', 'ajedrez', 'juegos de mesa', 'billar', 'música', 'tocar un instrumento', 'canto', 'composición musical', 'producción musical', 'gastronomía', 'cocina', 'recetas', 'horneado', 'postres', 'manualidades', 'origami', 'modelodo en arcilla', 'creación', 'natación', 'surf', 'kayac', 'buceo', 'esquí', 'tecnología', 'programación', 'robótica', 'computación', 'edición de videos', 'diseño gráfico', 'coleccionismo', 'monedas', 'vinilos', 'baile', 'danzas', 'escritura', 'periodismo', 'poesía', 'libros', 'lectura', 'cuentos', 'idiomas', 'viajes', 'exploración de lugares', 'fitnes', 'gym', 'yoga', 'pilates', 'entrenamiento', 'meditación', 'voluntariado', 'mascotas', 'animalista', 'astronomía', 'jardinería', 'plantas', 'huertos', 'paisajes', 'cine', 'series', 'novelas'], misHobbies:[],

    }
  },

  components: { ModalEditPatient, ModalRecetas, ModalFaltas, ModalTriaje, ModalVerTriajesViejos, ModalNewPatient, ModalVerEstados, ModalCambiarLike, ModalVerFaltas, ModalVerHobbies, ModalVerReprogramacionesViejos },

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
      .then(res => { //console.log(res.data);
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
		},
		async verReprogramacionesViejos(id){
			await this.axios.get('/api/verReprogramaciones/'+id)
			.then(response=>{
				this.reprogramaciones = response.data;
			})
		}
  },


  created () {
    this.getPatients();
		this.listarprofesional();
		this.hobbies.sort();

  }
}
</script>
