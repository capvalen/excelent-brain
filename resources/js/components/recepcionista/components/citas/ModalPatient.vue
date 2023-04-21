<template>
  <div class="modal fade" id="patientModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"> Datos del Paciente para actualizar</h5>
          <button type="button" id="cerrModalPatient" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="">
            <div class="form-group row">
              <div class="col-sm-6">
                <label for="name">Dni</label>
                <input type="text" class="form-control" name="dni" id="dni" v-model="dataCit.patient.dni" placeholder="Dni del paciente">
              </div>
              <div class="col-sm-6">
                <label for="name">Teléfono</label>
                <input type="text" class="form-control" name="phone" id="phone" v-model="dataCit.patient.phone" placeholder="Telefono del paciente">
              </div>          
            </div>

            <div class="form-group">
              <label for="name">Paciente</label>
              <input type="text" class="form-control"  name="name" id="name" v-model="dataCit.patient.name" placeholder="Nombre del paciente">
            </div>

            <div class="form-group row">                   
              <div class="col-sm-6">
                <label for="name">Direccion de paciente</label>
                <input type="text" class="form-control" name="address" id="address" v-model="dataCit.patient.address.address" placeholder="Direccion del paciene">
              </div>

              <div class="col-sm-6">
                <label for="name">Email de paciente</label>
                <input type="email" class="form-control" name="email" id="email" v-model="dataCit.patient.email"  placeholder="Email de paciente"> 
              </div>
            </div>

            <div class="form-group row">
              <div class="col-sm-4">
                <label for="name">Distrito</label>
                <input type="text" class="form-control" name="district" id="district" v-model="dataCit.patient.address.district" placeholder="Distrito">
              </div>

              <div class="col-sm-4">
                <label for="name">Provincia</label>
                <input type="text" class="form-control" name="province" id="province" v-model="dataCit.patient.address.province"  placeholder="Provincia"> 
              </div>

              <div class="col-sm-4">
                <label for="name">Departamento</label>
                <input type="text" class="form-control" name="department" id="department" v-model="dataCit.patient.address.department"  placeholder="Departamento"> 
              </div>
            </div>

            <div class="form-group row">
              <div class="col-sm-6">
                <label for="name">Fecha de nacimiento</label>
                <input type="date" class="form-control" name="birth_date" id="birth_date" v-model="dataCit.patient.birth_date">
              </div>
              <div class="col-sm-6">
                <label for="name">Ocupación</label>
                <input type="text" class="form-control" name="occupation" id="occupation" v-model="dataCit.patient.occupation"  placeholder="Ocuación del paciente"> 
              </div>
            </div>

            <div class="form-group row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Estado Civil</label>
                  <select class="form-control" name="marital_status" id="marital_status" v-model="dataCit.patient.marital_status">
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
                  <select class="form-control" name="instruction_degree" id="instruction_degree" v-model="dataCit.patient.instruction_degree">
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

            <!-- <div class="form-group row"> 
              <div class="col-sm-12">
                <label for="name">Nombre de pariente</label>
                <input type="text" class="form-control" name="relative_name" id="relativename" v-model="dataCit.patient.relative.name" placeholder="Nombre de pariente">
              </div>                               
            </div>  

            <div class="form-group row">
              <div class="col-sm-6">
                <label for="name">Telefono</label>
                <input type="text" class="form-control" name="relative_phone" id="relativephone" v-model="dataCit.patient.relative.phone"  placeholder="Telefono de pariente"> 
              </div>
              <div class="col-sm-6">
                <label for="name">Parentesco</label>
                <input type="text" class="form-control" name="kinship" id="kinship" v-model="dataCit.patient.relative.kinship"  placeholder="Parentesco"> 
              </div>
            </div>                -->
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          <button  @click="updateDataPatient()" type="button" class="btn btn-primary">Editar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "modal_patient",

    props:{
      dataCit: Object
    }, 

    data () {
      return {
        dataPatient: '',
      }
    },

    methods:{
      async updateDataPatient(){
        await this.axios.put(`/api/patient/${this.dataPatient.patient.id}`, this.dataPatient.patient)
        .then(res => {
          this.closeModal()
          this.$swal('Datos de paciente actualizado con éxito')
        })
        .catch(error => {
            console.log('error'+error)
        })
      },

      closeModal() {
        document.getElementById('cerrModalPatient').click();
      },
    },

    computed: {
      actualizarDatos () {
        this.dataPatient = this.dataCit
      }
    },

    updated () {
      this.actualizarDatos
    },
  
    created () {
      this.actualizarDatos
    }
  }
</script>

  