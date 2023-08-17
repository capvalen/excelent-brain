<template>
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header bg-warning py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-white">Examen</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div>
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="">Paciente</label>
                            <input type="text" class="form-control" v-model="exam.name_patient" placeholder="Nombre del Paciente">
                        </div>

                        <div class="form-group col">
                            <label for="">Fecha Actual</label>
                            <input type="date" class="form-control" v-model="exam.attention_date">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col">
                            <label for="">Tipo de examen
															<button type="button" class="btn btn-outline-primary btn-sm position-relative" data-bs-target="#modalAddExamen" data-bs-toggle="modal"> <i class="fa-solid fa-plus"></i> Agregar nuevo elemento
																<span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle"> </span>
															</button>
														</label>
                            <select name="" id="typeExam" @change="typeExamenSelect" class="form-select">
                                <option value="1">Laboratorio</option>
                                <option value="2">Imagenología</option>
                                <option value="3">Otros</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group w-100 position-relative">
													<label for="">Examen</label>
													<input 
													autocomplete="off" 
													type="text" 
													class="form-control w-100" 
													placeholder="Nombre del estudio" 
													name="" 
													id="diagnostico" 
													@keyup="searchExam"
													/>

													<div class="cie-content rounded">
														<div 
														v-for="(exam, index) in dataSearchExam" :key="index">
																<span 
																class="w-100 px-2 py-2 cie--hover d-inline-block pointer cie-item" 
																:data-id="exam.id"
																:class="{ 'cie-danger': selected.find(el => el.id == `${exam.id}`) }"
																@click="insertExam(exam)">
																		{{ exam.name }}
																</span>
														</div>
													</div>
                        </div>
                    </div>
                </div>

                <div class="form-row mt-2">
                    <div class="col-md-12">
                        <table class="table">
                            <thead class="bg-warning text-white">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tipo de examen</th>
                                    <th scope="col">Examen</th>
                                    <th scope="col">Operaciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="(select,key) in selected" :key="select.id">
                                    <th scope="row">{{ key + 1 }}</th>
                                    <td scope="row">{{ (select.type === 1) ? 'Laboratorio' : (select.type === 2) ? 'Imagenología' : (select.type === 3) ? 'Otro' : 'Sin tipo de examen' }}</td>
                                    <td>{{ select.name }}</td>
                                    <td>
                                        <button @click="deleteSelected(key)" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- <button @click="storeExam" class="btn btn-success">Registrar</button> -->
                    <button @click="print" id="printBtn" class="btn btn-success ml-1">Imprimir PDF</button>
                </div>
            </div>
        </div>
    </div>
		<!-- Modal -->
<div class="modal fade" id="modalAddExamen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo tipo de examen</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border-0">
        <label for="">Categoría que pertenece</label>
				<select name="" class="form-select" v-model="nuevo.tipo">
					<option value="1">Laboratorio</option>
					<option value="2">Imagenología</option>
					<option value="3">Otros</option>
				</select>
        <label for="">Nombre del exámen</label>
				<input type="text" class="form-control" v-model="nuevo.nombre" autocomplete="off">
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-primary" @click="enviarNuevo()"><i class="fa-regular fa-floppy-disk"></i> Ingresar nuevo tipo</button>
      </div>
    </div>
  </div>
</div>
</div>
</template>

<script>
export default {
    data(){
        return{
            exam:{
                appointment: this.$route.params.consultId,
                name_patient:'',
                attention_date: this.getFecha(),
                medical_exams:[]
            },
            buscar:'', nuevo:{tipo:1, nombre:''},
            examenes: {
                laboratorio: [],
                imagenologia: [],
                otros: []
            },
            
            exams:[],
            dataSearchExam: [],
            
            showResults: false,
            
            selected:[],
        }
    },
    
    methods: {
        async getNamePatient(){
            await this.axios.get(`/api/getNameExam/${this.$route.params.consultId}`)
            .then((result) => {
                this.exam.name_patient = result.data.name

            }).catch((err) => {
                console.log(err)
            });
        },
				enviarNuevo(){
					if( this.nuevo.nombre )
						this.axios.post('/api/newExam', this.nuevo)
						.then(response=> { console.log('queresp', response.data);
							this.getExams()
							this.nuevo.nombre =''
						})
						$('#modalAddExamen').modal('hide')
				},

        async getExams(){
            await this.axios.get('/api/medicalExam/')
            .then((result) => {
                result.data.forEach(exam => {
                    if (exam.type === 1) {
                        this.examenes.laboratorio.push(exam);
                    } else if (exam.type === 2) {
                        this.examenes.imagenologia.push(exam);
                    } else if (exam.type === 3) {
                        this.examenes.otros.push(exam);
                    }
                })
            }).catch((err) => {
                console.error(err)
            });
        },

        // async storeExam(){
        //     this.exam.medical_exams = this.selected
        //     await this.axios.post('/api/medicalExam/', this.exam)
        //     .then((res) => {
        //         console.log(res.data.medical_exams)
        //         this.pdf = res.data.medical_exams

        //         // sessionStorage.setItem('data-pdf', '')
        //         // sessionStorage.setItem('data-pdf', JSON.stringify(this.exam))

        //         this.$swal('Examen registrado. Ahora puede imprimir')
        //         document.getElementById('printBtn').disabled = false
        //     }).catch((err) => {
        //         console.log(err)
        //     });
        // },

        insertExam(exam) {
            let switchDuplicate = false

            this.selected.find(el => {
                if (el.id == `${exam.id}`) return switchDuplicate = true;
            });
            
            if (this.selected.length !== 0 && !switchDuplicate) this.selected.push(exam);
            if (this.selected.length === 0) this.selected.push(exam)

            this.buscar=''
            this.showResults= false
        },

        searchExam (event) {
            let expReg = new RegExp(`${event.target.value}`, 'ig');
            // this.dataSearchExam = exams.filter(el => el.name )
            this.dataSearchExam = this.exams.filter(el => el.name.match(expReg))
        },
        
        print () {
            this.exam.medical_exams = this.selected
            window.open('/api/pdf_exam/'+[JSON.stringify(this.exam).split('/').join('-')])
        },
        
        deleteSelected(key){
            this.selected.splice(key,1)
        },

        typeExamenSelect (event = 1) {
            let valueSelectExam = event.target ? event.target.value : event;
            if (valueSelectExam == 1) {
                this.exams = this.examenes.laboratorio
                this.dataSearchExam = this.exams
            } else if (valueSelectExam == 2) {
                this.exams = this.examenes.imagenologia
                this.dataSearchExam = this.exams
            } else if (valueSelectExam == 3) {
                this.exams = this.examenes.otros
                this.dataSearchExam = this.exams
            }
        },

        getFecha() {
            return `${new Date().getFullYear()}-${new Date().getMonth().toString().length == 1 ? new Date().getMonth().toString() + 1 : parseInt(new Date().getMonth()) + 1}-${new Date().getDate().toString().length == 1 ? '0' + new Date().getDate() : new Date().getDate()}`
        },
    },
    mounted() {
       this.getExams(),
       this.getNamePatient(),
       this.typeExamenSelect()
    },
    computed:{
         filtro(){
            if(!this.buscar){
                this.showResults = false
                return null
            }else{
                this.showResults = true
                return this.exams.filter(exam => exam.name.toLowerCase().includes(this.buscar)).slice(0,5)
            }
        }
    }
}
</script>

<style scoped>
 .cie-content {
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
    background-color: #fff;
    border: .3px solid #22222260;
    visibility: hidden;
    position: absolute;
    z-index: 10;
    transition: visibility 1s normal 3s;
  }

  #diagnostico:focus + .cie-content {
    visibility: visible;
    /* display: block !important; */
  }
  .cie-item:active {
    visibility: visible;
    opacity: 0;
    /* display: block !important; */
  }
  
  .flex-gap {
    gap: 15px;
  }

  .pointer {
    cursor: pointer;
  }

  .cie--hover:hover:not(.cie-danger) {
    background: rgb(236, 236, 236);
  }

  .cie-danger {
    background: rgb(255, 207, 207);
    cursor: no-drop;
  }
</style>
