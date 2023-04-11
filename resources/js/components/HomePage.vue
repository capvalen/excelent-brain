<template>
<main>
    <div id="wrapper">
        <side-bar :rolUser="this.currentUser.rol"></side-bar>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav-bar 
                v-if="currentUser.id" 
                :professional="{ 
                    name: datosUsuario 
                        ? datosUsuario.name 
                        : currentUser.rol === 'recepcionista'
                            ? 'Recepción'
                            : currentUser.rol, 
                    imagen: datosUsuario ?  `/storage/${datosUsuario.photo}` : '/storage/img_profesionales/0xwP15erQOpeGiNxqgny657k23NpJ4uqr06JdagZ.gif'
                }"
                >
                </nav-bar>
                <div class="container-fluid">
                    <router-view
                    v-if="currentUser.id"
                    :idUser="currentUser.id"
                    :rolUser="currentUser.rol"
                    :dataUser="datosUsuario"
                    >
                    </router-view>
                </div>
            </div>
        </div>
    </div>
</main>
</template>
<script>
import SideBar from './layout/Sidebar.vue'
import NavBar from './layout/Nav.vue'

export default {
    name:'homepage',
    
    data () {
       return{
           currentUser:{
               id:'',
               rol:'',
           },
           datosUsuario: {},
           token: localStorage.getItem('token'),
       }
    },

    props:{
        rolUser : String,
        idUser: String,
        dataUser: Object,
        professional: Object
    },
    
    components: { NavBar, SideBar },

    methods: {
        routePathValidation () {
            if (this.$router.history.current.path.split("/")[1] !== this.currentUser.rol) {
                this.$router.push({ path: `/${this.currentUser.rol}/home` })    
            }
        }
    },

    mounted(){
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        this.axios.get('/api/user')
        .then((res) => {
            console.log('home page', res.data.user)
            const {id, email, rol} = res.data.user

            this.datosUsuario = res.data.user.professional
            this.currentUser.id = id.toString()
            this.currentUser.rol = rol
            this.currentUser.email = email

            window.addEventListener('popstate', e => {
                this.routePathValidation()
            })

            this.routePathValidation()
            
        }).catch((err) => {
            console.log(err)
        });
    },

   
}
</script>

