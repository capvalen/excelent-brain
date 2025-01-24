<template>
<div class="row">
<div class="col-xl-12 col-lg-12">
    <a href="#" data-bs-toggle="modal" data-bs-target="#kairosModal" class="btn btn-warning mb-2">Agregar Kairos</a>
    <kairos-modal></kairos-modal>
    <a href="../kairostable" class="btn btn-warning mb-2">Tabla Kairos</a>
  <div class="card shadow mb-4">
    <!-- Card Header - Dropdown -->
    <div class="card-header bg-warning py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-white">Receta</h6>
      <!-- Dropdown ultima receta -->
        <li v-if="last_recipes.length != 0" class="nav-item dropdown no-arrow mx-1 list-unstyled">
                    <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-envelope text-white"></i>
                    </a>
                    <!-- Dropdown - Messages -->
                    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="alertsDropdown">
                        <h6 class="dropdown-header">
                            Ultima Receta
                        </h6>

                        <a v-for="recipe in last_recipes" :key="recipe.id" class="dropdown-item d-flex align-items-center" href="#">
                            <div class="mr-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">{{recipe.pivot.indications}} - {{recipe.pivot.way}} - {{recipe.pivot.amount}} - {{recipe.presentation}}</div>
                                <span class="font-weight-bold">{{recipe.name}} - {{recipe.tradename}} - {{recipe.concentration}}</span>
                            </div>
                        </a>
                    </div>
        </li>
    </div>
      <!-- Card Body -->
  <div class="card-body">
    <div class="form-row">
        <div class="col-md-12">
            <label for="">Paciente</label>
            <input type="text" class="form-control text-capitalize" v-model="full_patient_name" placeholder="Apellidos y Nombres" :readonly="prescription.patient_id !== 0">
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-4">
            <label for="">Fecha Actual</label>
            <input type="date" class="form-control" v-model="prescription.attention_date">
        </div>
        <div class="col-md-4">
            <label for="">Fecha de Vencimiento</label>
            <input type="date" class="form-control" v-model="prescription.effective_date">
        </div>
        <div class="col-md-4">
            <label for="">Firma</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" v-model="prescription.signature">
                <label class="form-check-label" for="defaultCheck1">
                    Firma
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <label for="">Medicamento</label>
            <input v-model="buscar" autocomplete="off" type="text" class="form-control w-100" placeholder="Ingrese el nombre del medicamento" name="" id="via" />
            <div v-show="showResults" class="border border-secondary shadow w-100">
                <div @click="callModal(medicamento,'Agregar')" data-bs-toggle="modal" data-bs-target="#recetaModal" class="border border-secondary"  v-for="medicamento in filtro" :key="medicamento.id">{{medicamento.name}} - {{medicamento.tradename}} - {{medicamento.concentration}} - {{medicamento.presentation}}</div>
            </div>
        </div>
    </div>
    <div class="form-row mt-2">
        <div class="col-md-12">
            <table class="table">
                <thead class="bg-warning text-white">
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Medicamento</th>
                    <th scope="col">Vía</th>
                    <th scope="col">Frecuencia</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(select,key) in selected" :key="select.id">
                        <th scope="row">{{key+1}}</th>
                        <td>{{select.name}}</td>
                        <td>{{select.way}}</td>
                        <td>{{select.indications}}</td>
                        <td>{{select.amount}}</td>
                        <td>
                            <button @click="deleteSelected(key)" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                            <button @click="callModal(select,'Editar')" data-bs-toggle="modal" data-bs-target="#recetaModal" class="btn btn-primary"><i class="fas fa-pencil-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-if="usuario.professional.profession!='Psicólogo'">
					<button @click="insertPrescription" class="btn btn-outline-success"><i class="far fa-save"></i> Registrar receta</button>
					<button @click="print" id="printBtn" class="btn btn-outline-success ml-1" disabled><i class="fas fa-print"></i> Imprimir receta en PDF</button>
					<button @click="print" id="btnPrueba" class="btn btn-outline-warning ml-1"><i class="fas fa-print"></i> Prueba</button>
				</div>
    </div>

  </div>
  </div>

</div>
<recetas-modal v-if="medicamento" :med="medicamento" :type="this.tipo"></recetas-modal>
</div>
</template>

<script>
import KairosModal from './KairosModal.vue'
import RecetasModal from './RecetasModal.vue'
import { dateNow } from '../../../../helpers/Time.js'

export default{
    data(){
        return{
            showResults:false,
            buscar:'', usuario:null,
            medicamentos:[],
            selected:[],
            medicamento:{
                name: '',
                way: '',
                indications: '',
                amount: '',
                kairo_id : ''
            },
            prescription:{
                patient_name:'',
                effective_date: null,
                attention_date: dateNow(),
                patient_id: this.$route.params.patientId,
                medicines: [],
                signature: '', professional_id:-1
            },
            tipo: this.type,
            id_receta : '',
            name_patient: '', // Apellidos
            nombres_patient: '', // Nombres
            prescription: {
                patient_id: this.$route.params.patientId,
            },
            last_recipes:[]
        }
    },
    props:{
        med: {
            type: Object,
            default: () => ({})
        },
        type: String
    },
    components:{RecetasModal, KairosModal},
    methods:{
        getNamePatient(){
            if (this.$route.params.patientId != 0) {
            this.axios.get(`/api/getNamePatient/${this.$route.params.patientId}`)
            .then((result) => {
                console.log(result);
                if (result.data.last_recipe) {
                    this.name_patient = result.data.patient.name; // Apellidos
                    this.nombres_patient = result.data.patient.nombres; // Nombres
                    this.last_recipes = result.data.last_recipe;
                } else {
                    this.name_patient = result.data.name; // Apellidos
                    this.nombres_patient = result.data.nombres; // Nombres
                }


            }).catch((err) => {
                console.log(err)
            });
            }
        },
        cleanFields(){
            this.name_patient = ''
            //document.getElementById('messagesDropdown').style.display = 'none'
        },
        getKairos(){
            this.axios.get('/api/kairos/')
            .then((result) => {
                this.medicamentos = result.data
            })
        },
        callModal(medicamento, tipo){
            this.medicamento = medicamento
            this.tipo = tipo
            this.buscar = ''
        },
        pushSelected(medicamento){
            this.selected.push({...medicamento})
            console.log(this.selected)
        },
        deleteSelected(key){
            this.selected.splice(key,1)
            console.log(key)
        },
        updateSelected(select){
            this.medicamento = select
        },
        insertPrescription(){
					this.prescription.medicines = this.selected
					this.prescription.patient_name = this.name_patient + ', ' + this.nombres_patient
					this.axios.post('/api/addPrescription/', this.prescription)
					.then((result) => { //console.log(result.data);
						if(result.data.id_receta>0){
							this.$swal({
								icon: "success",
								title: 'Receta insertada. Ahora puede imprimir'
							})
							this.id_receta = result.data.id_receta
							document.getElementById('printBtn').disabled = false
						}else{
							this.$swal({
								icon: "error",
								title: 'No se pudo procesar el pedido. Genere una receta nueva'
							})
						}
					});
        },
        print(){
            window.open('/api/pdf/'+this.id_receta)
        },
				queUsuario(){
					this.axios.get('/api/user')
        	.then((res) => { //console.log('soy', res.data.user.professional.id)
						this.usuario = res.data.user;
						this.prescription.professional_id = res.data.user.professional.id
					}).catch((err) => {
							console.log(err)
					});
				}
        
    },
    computed:{
        filtro(){
            if(!this.buscar){
                this.showResults = false
                return null
            }else{
                this.showResults = true
                return this.medicamentos.filter(medicamento => medicamento.name.toLowerCase().includes(this.buscar) || medicamento.tradename.toLowerCase().includes(this.buscar)).splice(0,6)
            }
        },
    
    full_patient_name() {
        return `${this.name_patient} ${this.nombres_patient}`.trim();
    }
    },
    beforeMount(){
        this.getNamePatient()
        this.getKairos()
				this.queUsuario()
    },
    beforeRouteUpdate(to, from, next){
        this.name_patient = '',
        this.last_recipes = [],
        this.prescription.patient_id = 0
        next()
    },
    updated(){
        //console.log('updateando')
        /* if(this.name_patient != ''){
            alert('hay nombre de paciente')
        }else{
            alert('no hay nombre de paciente')
        } */
    }

}
</script>
