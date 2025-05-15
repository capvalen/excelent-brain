<template>
<div class="card" style="width: 16rem;">
  <img v-if="prof.photo != '-'" class="card-img-top" :src="'/img/'+prof.photo" width="254px" height="254px" alt="Card image cap" style="object-fit:cover;">
  <img v-else class="card-img-top" src="https://excelentemente.org/logo/logoexcel.jpeg" width="254px" height="254px" alt="Card image cap" style="object-fit:cover;">  
  <div class="card-body">
    <h5 class="m-0 font-weight-bold text-nowrap text-dark w-100" :title="profesional.name" style="overflow:hidden; text-overflow:ellipsis;">{{profesional.name}}</h5>
    <p class="card-text">{{profesional.profession}}</p>
    <div class="row d-flex align-items-center justify-content-around mt-4">
        <a href="#" data-bs-toggle="modal" title="Editar datos del profesional" data-bs-target="#profModal" @click="llamarModal(profesional,'Editar')" class="btn btn-info btn-circle"><i class="fas fa-pencil-alt"></i></a>
        <a href="#" data-bs-toggle="modal" title="Cambiar acceso del profesional" data-bs-target="#accessModal" @click="llamarModal(profesional,'Acces')" class="btn btn-info btn-circle"><i class="fas fa-key"></i></a>
        <a @click="deleteProfesional" title="Eliminar profesional" class="btn btn-info btn-circle"><i class="fas fa-trash"></i></a>
        <a href="#" data-bs-toggle="modal" title="Agregar horarios" data-bs-target="#horarioModal" @click="llamarModalHorarios(profesional)" class="btn btn-info btn-circle"><i class="fas fa-calendar"></i></a>
    </div>
  </div>
</div>
</template>
<script>
export default {
    data(){
        return{
            profesional:{
                id:this.prof.id,
                name: this.prof.name,
                profession: this.prof.profession,
                phone: this.prof.phone,
                cv_description: this.prof.cv_description,
                photo: this.prof.photo,
                signing:this.prof.signing,
                user_id:this.prof.user_id,
                email:this.prof.email,
                password: this.prof.password
            },
            type:''
        }
    },
    props:{
      prof: Object
    },
    methods: {
      
      async deleteProfesional(){
      this.$swal({
          title: '¿Quieres eliminar este profesional?',
          showDenyButton: true,
          confirmButtonText: 'Si',
          denyButtonText: `No`,
      }).then((result) => {
          if(result.isConfirmed){
          this.axios.delete('/api/user'+this.profesional.user_id)
              .then((res) => {
                  console.log(res.data)
                  this.$parent.getProfesionales()
                  this.$swal('Profesional eliminado con éxito')
              });
              this.citas=result.data;
              this.$parent.getProfesionales()
          }
      })
    },

        pasarDatos(profesional){
            this.$parent.pasarData(profesional)
        },
        llamarModal(profesional,tipo){
            this.$parent.llamarModal(profesional,tipo) 
        },
        llamarModalHorarios(profesional){
            this.$parent.llamarModalHorarios(profesional)
        }
    }
}
</script>
