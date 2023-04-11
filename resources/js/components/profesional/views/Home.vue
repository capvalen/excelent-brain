<template>
  <div>
    <div class="container mt-4" v-if="rolUsuario === 'profesional'">
      <div class="row">
        <card-prof :prof="dataUser"></card-prof>
        <card-cv :prof="dataUser"></card-cv>
      </div>
    </div>

    <edit-modal :prof="dataUser"></edit-modal>
  </div>

</template>

<script>
// Componentes para profesionales
import CardProf from '../components/home/CardProfesional.vue'
import CardCv from '../components/home/CardCv.vue'
import EditModal from '../components/home/EditModal.vue'

export default {
  name: 'home',
  data () {
    return {
      rolUsuario: 'profesional',
      professional: {
          name:'',
          lastname:'',
          phone:'',
          profession:'',
          cv_description:''
      }
    }
  },
  components: { CardProf, CardCv, EditModal, /*HomeAdmin*/ },
  props: {
    prof:Object,
    idUser:String,
    dataUser:Object
  },
  mounted(){
      this.getProfessional()
  },
  methods:{
    async getProfessional() {
      console.log(this.dataUser.id)
      await this.axios.get(`/api/professional/${this.dataUser.user_id}`)
      .then(response => {
        console.log(response.data)
        const { name, lastname, phone, profession, cv_description } = response.data
        this.professional.name = name
        this.professional.lastname = lastname
        this.professional.phone = phone
        this.professional.profession = profession
        this.professional.cv_description = cv_description
      })
      .catch(error => {
          console.log(error)
      })
    }
  }
}
</script>
