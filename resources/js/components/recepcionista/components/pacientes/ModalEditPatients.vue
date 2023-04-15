<template>
  <div class="modal fade" id="patientModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"> Datos del Paciente</h5>
          <button type="button" id="cerrModalPatient" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="">
            <div class="form-group row">
              <div class="col-sm-6">
                <label for="name">Dni</label>
                <input type="text" class="form-control" name="dni" id="dni" v-model="dataPatient.dni" placeholder="Dni del paciente">
              </div>
              <div class="col-sm-6">
                <label for="name">Teléfono</label>
                <input type="text" class="form-control" name="phone" id="phone" v-model="dataPatient.phone" placeholder="Telefono del paciente">
              </div>          
            </div>

            <div class="form-group">
              <label for="name">Paciente</label>
              <input type="text" class="form-control"  name="name" id="name" v-model="dataPatient.name" placeholder="Nombre del paciente">
            </div>

            <div class="form-group row">                   
              <div class="col-sm-6">
                <label for="name">Direccion de paciente</label>
                <input type="text" class="form-control" name="address" id="address" v-model="dataPatient.address.address" placeholder="Direccion del paciente">
              </div>

              <div class="col-sm-6">
                <label for="name">Email de paciente</label>
                <input type="email" class="form-control" name="email" id="email" v-model="dataPatient.email" placeholder="Email de paciente"> 
              </div>
            </div>

            <div class="form-group row">
              <div class="col-sm-4">
                <label for="name">Distrito</label>
                <input type="text" class="form-control" name="district" id="district" v-model="dataPatient.address.district" placeholder="Distrito">
              </div>

              <div class="col-sm-4">
                <label for="name">Provincia</label>
                <input type="text" class="form-control" name="province" id="province" v-model="dataPatient.address.province" placeholder="Provincia"> 
              </div>

              <div class="col-sm-4">
                <label for="name">Departamento</label>
                <input type="text" class="form-control" name="department" id="department" v-model="dataPatient.address.department" placeholder="Departamento"> 
              </div>
            </div>

            <div class="form-group row">
              <div class="col-sm-6">
                <label for="name">Fecha de nacimiento</label>
                <input type="date" class="form-control" name="birth_date" v-model="dataPatient.birth_date" id="birth_date">
              </div>
              <div class="col-sm-6">
                <label for="name">Ocupación</label>
                <input type="text" class="form-control" name="occupation" v-model="dataPatient.occupation" id="occupation" placeholder="Ocuación del paciente"> 
              </div>
            </div>

            <div class="form-group row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Estado Civil</label>
                  <select class="form-control" name="marital_status" id="marital_status" v-model="dataPatient.marital_status">
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
                  <select class="form-control" name="instruction_degree" id="instruction_degree" v-model="dataPatient.instruction_degree">
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


            <!--  
            <div class="form-group row"> 

              <div class="col-sm-12">
                <label for="name">Nombre de pariente</label>
                <input type="text" class="form-control" name="relative_name" v-model="dataPatient.relative.name" id="relativename" placeholder="Nombre de pariente">
              </div>                               
            </div>   -->

            <!-- <div class="form-group row">
              <div class="col-sm-6">
                <label for="name">Telefono</label>
                <input type="text" class="form-control" name="relative_phone" v-model="dataPatient.relative.phone" id="relativephone" placeholder="Telefono de pariente"> 
              </div>
              <div class="col-sm-6">
                <label for="name">Parentesco</label>
                <input type="text" class="form-control" name="kinship" id="kinship" v-model="dataPatient.relative.kinship" placeholder="Parentesco"> 
              </div>

            </div>                -->

          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          <button type="button" @click="updatePatient()" class="btn btn-primary">Editar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ModalEditPaciente',
  
  props: {
    dataPatient: Object
  },

  data () {
    return {
      datos: ''
    }
  },

  methods: {
    async updatePatient() {
      await this.axios.put(`/api/patient/${this.dataPatient.id}`, this.dataPatient) 
      .then(res => {
        console.log(res)
        this.closeModal()
        this.$swal('Datos de paciente actualizado con éxito')

      })
      .catch(err => {
        console.error(err)        
      })
    },

    closeModal() {
      document.getElementById('cerrModalPatient').click();
    }
  },

  computed: {
    updateValues () {
      return this.datos = this.dataPatient
    }
  },

  updated() {
    this.updateValues;
  },

  created () {
    this.updateValues;
  },
} 
</script>