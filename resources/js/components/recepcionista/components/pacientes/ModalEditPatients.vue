<template>
  <div class="modal fade" id="patientModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="exampleModalLabel"> Datos del Paciente</h5>
          <button type="button" id="cerrModalPatient" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="">
            <div class="form-group row">
              <div class="col-sm-6">
                <label for="name">Dni</label>
                <input type="text" class="form-control" name="dni" id="dni" v-model="patientData.dni" placeholder="Dni del paciente">
              </div>
              <div class="col-sm-6">
                <label for="name">Teléfono</label>
                <input type="text" class="form-control" name="phone" id="phone" v-model="patientData.phone" placeholder="Telefono del paciente">
              </div>          
              <div class="col-sm-6">
                <label for="name">Paciente</label>
                <input type="text" class="form-control"  name="name" id="name" v-model="patientData.name" placeholder="Apellidos del paciente">
              </div>
              <div class="col-sm-6">
                <label for="name">Paciente</label>
                <input type="text" class="form-control"  name="nombres" id="nombres" v-model="patientData.nombres" placeholder="Nombres del paciente">
              </div>
            </div>

            <div class="form-group row">                   
              <div class="col-sm-6">
                <label for="name">Dirección</label>
                <input type="text" class="form-control" name="address" id="address" v-model="patientData.address.address" placeholder="Direccion del paciente">
              </div>

              <div class="col-sm-6">
                <label for="name">Correo electrónico</label>
                <input type="email" class="form-control" name="email" id="email" v-model="patientData.email" placeholder="Correo electrónico"> 
              </div>
            </div>

            <div class="form-group row">
              <div class="col-sm-4">
                <label for="name">Departamento</label>
                <select v-model="patientData.address.department" class="form-select" id="department" @change="moverProvincias(true)">
                  <option v-for="departamento in ubigeo.departamentos" :key="departamento.idDepa" :value="departamento.idDepa">{{ departamento.departamento }}</option>
                </select>
              </div>
              <div class="col-sm-4">
                <label for="name">Provincia</label>
                <select v-model="patientData.address.province" class="form-select" id="provincia" @change="moverDistritos()">
                  <option v-for="provincia in provincias" :key="provincia.idProv" :value="provincia.idProv">{{ provincia.provincia }}</option>
                </select>
              </div>
              <div class="col-sm-4">
                <label for="name">Distrito</label>
                <select v-model="patientData.address.district" class="form-select" id="distrito">
                  <option v-for="distrito in distritos" :key="distrito.idDist" :value="distrito.idDist">{{ distrito.distrito }}</option>
                </select>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-sm-6">
                <label for="name">Fecha de nacimiento</label>
                <input type="date" class="form-control" name="birth_date" v-model="patientData.birth_date" id="birth_date">
              </div>
              <div class="col-sm-6">
                <label for="name">Ocupación</label>
                <input type="text" class="form-control" name="occupation" v-model="patientData.occupation" id="occupation" placeholder="Ocuación del paciente"> 
              </div>
            </div>

            <div class="form-group row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Estado Civil</label>
                  <select class="form-select" name="marital_status" id="marital_status" v-model="patientData.marital_status">
                    <option value="1">Soltero</option>
                    <option value="2">Casado</option>
                    <option value="3">Viudo</option>
                    <option value="4">Divorciado</option>
                    <option value="5">Conviviente</option>
                  </select>
                </div>  
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Grado de instrucción</label>
                  <select class="form-select" name="instruction_degree" id="instruction_degree" v-model="patientData.instruction_degree">
                    <option value="1">Inicial</option>
                    <option value="2">Primaria</option>
                    <option value="3">Secundaria</option>
                    <option value="4">Superior</option>
                    <option value="5">Tecnico</option>
                    <option value="6">Sin instrucción</option>
                  </select>
                </div>
              </div>       
            </div> 

            <div class="form-group row">
              <label for="">Referencia</label>
							<div class="col-sm-4 my-1" >
										<select class="form-select text-capitalize" name="tipo_recomendacion" id="tipo_recomendacion" v-model="patientData.recomendation" >
											<option value="" selected>Ninguno</option>
                      <option class="text-capitalize" v-for="reco in recomendaciones" :value="reco">{{ reco }}</option>
										</select>
									</div>
									<div class="col-sm-8">
										<input type="text" class="form-control text-capitalize" v-model="patientData.recomendacion_comentario" placeholder="¿Comentario extra sobre la recomendación?">
							</div>
						</div>

            <h5 class="modal-title" id="exampleModalLabel"> Datos del familiar</h5>

            <!-- Primer contacto -->
            <div class="form-group row">
              <div class="col-sm-12">
                <label for="name">Nombre del primer contacto</label>
                <input type="text" class="form-control" name="relative_name_1" v-model="patientData.relative[0].name" id="relativename_1" placeholder="Nombre de pariente">
              </div>                               
            </div>  

            <div class="form-group row">
              <div class="col-sm-6">
                <label for="name">Telefono</label>
                <input type="text" class="form-control" name="relative_phone_1" v-model="patientData.relative[0].phone" id="relativephone_1" placeholder="Telefono de pariente"> 
              </div>
              <div class="col-sm-6">
                <label for="name">Parentesco</label>
                <input type="text" class="form-control" name="kinship_1" id="kinship_1" v-model="patientData.relative[0].kinship" placeholder="Parentesco"> 
              </div>
            </div>

            <!-- Segundo contacto -->
            <div class="form-group row">
              <div class="col-sm-12">
                <label for="name">Nombre del segundo contacto</label>
                <input type="text" class="form-control" name="relative_name_2" v-model="patientData.relative[1].name" id="relativename_2" placeholder="Nombre de pariente">
              </div>                               
            </div>  

            <div class="form-group row">
              <div class="col-sm-6">
                <label for="name">Telefono</label>
                <input type="text" class="form-control" name="relative_phone_2" v-model="patientData.relative[1].phone" id="relativephone_2" placeholder="Telefono de pariente"> 
              </div>
              <div class="col-sm-6">
                <label for="name">Parentesco</label>
                <input type="text" class="form-control" name="kinship_2" id="kinship_2" v-model="patientData.relative[1].kinship" placeholder="Parentesco"> 
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" @click="updatePatient()" class="btn btn-outline-primary"><i class="fas fa-sync-alt"></i> Actualizar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ModalEditPaciente',
  
  props: {
    dataPatient: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      patientData: {
        id: -1,
        dni: '',
        phone: '',
        name: '',
        nombres: '',
        email: '',
        birth_date: '',
        occupation: '',
        marital_status: '',
        instruction_degree: '',
        recomendation:'', recomendacion_comentario:'',
        address: {
          address: '',
          department: -1,
          province: -1,
          district: -1
        },
        relative: [
          { id: -1, name: '', phone: '', kinship: '' },
          { id: -1, name: '', phone: '', kinship: '' }
        ]
      },
      ubigeo: { departamentos: [], provincias: [], distritos: [] },
      provincias: [],
      distritos: [],
      recomendaciones:['Facebook', 'Instagram', 'TikTok', 'Linkedin', 'Youtube', 'Spotify', 'TV', 'Amigos o familiares', 'Referencia profesional', 'Publicidad escrita', 'Campañas de salud', 'Convenio', 'Paciente Antiguo', 'Otros Centros de Salud', 'Google Maps / Business', 'Referencia del Establecimiento', 'Sucamec', 'Página Web']
    }
  },

  methods: {
    async updatePatient() {
      try {
        // Aseguramos que la estructura de datos sea correcta antes de enviar
        this.validateDataBeforeSend();
        
        // Agregamos un log para verificar los datos que se están enviando
        console.log("Datos a enviar:", JSON.stringify(this.patientData));
        
        const response = await this.axios.put(`/api/patient/${this.patientData.id}`, this.patientData);
        console.log("Respuesta del servidor:", response);
        
        // Cerrar el modal primero
        const modal = document.getElementById('patientModal');
        if (typeof bootstrap !== 'undefined') {
          const modalInstance = bootstrap.Modal.getInstance(modal);
          modalInstance.hide();
        } else {
          // Alternativa para cerrar el modal si no está utilizando Bootstrap 5
          document.getElementById('cerrModalPatient').click();
        }
        
        // Mostrar mensaje de éxito después de que el modal se haya cerrado
        this.$swal('Datos del paciente actualizados con éxito').then(() => {
          // Emitir evento para actualizar la lista de pacientes en el componente padre
          this.$emit('patient-updated');
        });
      } catch (error) {
        console.error("Error al actualizar paciente:", error);
        this.$swal({
          icon: 'error',
          title: 'Error',
          text: 'No se pudieron actualizar los datos del paciente'
        });
      }
    },

    validateDataBeforeSend() {
      // Nos aseguramos que relative siempre sea un array con 2 elementos
      if (!Array.isArray(this.patientData.relative)) {
        this.patientData.relative = [
          { id: -1, name: '', phone: '', kinship: '' },
          { id: -1, name: '', phone: '', kinship: '' }
        ];
      }

      // Aseguramos que siempre haya dos contactos
      while (this.patientData.relative.length < 2) {
        this.patientData.relative.push({ id: -1, name: '', phone: '', kinship: '' });
      }

      // Si hay más de dos contactos, limitamos a dos
      if (this.patientData.relative.length > 2) {
        this.patientData.relative = this.patientData.relative.slice(0, 2);
      }

      // Nos aseguramos que cada contacto tenga todos los campos necesarios
      this.patientData.relative.forEach((contact, index) => {
        if (!contact.id) contact.id = -1;
        if (!contact.name) contact.name = '';
        if (!contact.phone) contact.phone = '';
        if (!contact.kinship) contact.kinship = '';
      });
    },

    async listarDepartamentos() {
      try {
        const response = await this.axios.get('/api/departamentos');
        this.ubigeo.departamentos = response.data['departamentos'];
        this.ubigeo.provincias = response.data['provincias'];
        this.ubigeo.distritos = response.data['distritos'];
        
        this.moverProvincias(false);
        this.moverDistritos();
      } catch (error) {
        console.error('Error al cargar datos de ubicación:', error);
      }
    },

    moverProvincias(borrar) {
      const idDepa = this.patientData.address.department;
      this.provincias = this.ubigeo.provincias.filter(provincia => provincia.idDepa == idDepa);
      
      if (borrar) this.patientData.address.district = -1;
    },

    moverDistritos() {
      const idProv = this.patientData.address.province;
      this.distritos = this.ubigeo.distritos.filter(distrito => distrito.idProv == idProv);
    },

    initializeData() {
      // Creamos un objeto temporal para evitar modificar directamente los props
      const tempData = JSON.parse(JSON.stringify(this.dataPatient));
      
      // Inicializar address si no existe
      if (!tempData.address) {
        tempData.address = { address: '', department: -1, province: -1, district: -1 };
      }
      
      // Inicializar el array de contactos
      if (!tempData.relative || !Array.isArray(tempData.relative)) {
        tempData.relative = [];
      }
      
      // Asegurar que siempre haya dos contactos con la estructura correcta
      while (tempData.relative.length < 2) {
        tempData.relative.push({ id: -1, name: '', phone: '', kinship: '' });
      }
      
      // Asegurar que cada contacto tenga todos los campos requeridos
      tempData.relative.forEach((contact, index) => {
        if (!contact.id) contact.id = -1;
        if (!contact.name) contact.name = '';
        if (!contact.phone) contact.phone = '';
        if (!contact.kinship) contact.kinship = '';
      });
      
      // Asignar los datos procesados al objeto patientData
      this.patientData = tempData;
      
      // Cargar datos de ubicación
      this.listarDepartamentos();
    }
  },

  watch: {
    dataPatient: {
      handler(newVal) {
        if (newVal) {
          this.initializeData();
        }
      },
      deep: true,
      immediate: true
    }
  },

  mounted() {
    this.$parent.$on('cambioDato', this.initializeData);
  },

  created() {
    this.initializeData();
  },

  beforeUnmount() {
    // Limpiar evento para evitar memory leaks
    this.$parent.$off('cambioDato', this.initializeData);
  }
}
</script>