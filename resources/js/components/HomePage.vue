<template>
<main>
    <div id="wrapper">
        <side-bar :rolUser="this.currentUser.rol"></side-bar>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav-bar 
                v-if="currentUser.id" :nombreUser = "currentUser.nombre"
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
                <!-- Viene profesional-->
                <div class="container-fluid">
                    <router-view
                    v-if="currentUser.id"
                    :idUser="currentUser.id"
                    :rolUser="currentUser.rol"
                    :idSede="currentUser.idSede"
                    :dataUser="datosUsuario" :nombreUser = "currentUser.nombre" >
                    </router-view>
                    <bWhats></bWhats>
                </div>
            </div>
        </div>
    </div>
</main>
</template>

<script>
import io from 'socket.io-client'
import alertify from 'alertifyjs';

import SideBar from './layout/Sidebar.vue'
import NavBar from './layout/Nav.vue'
import bWhats from './profesional/components/consultas/grafico/botonWhatsapp.vue'

export default {
    name:'homepage',
    
    data () {
       return{
           currentUser:{
               id:'',
               rol:'', nombre:'', idSede:1
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
    
    components: { NavBar, SideBar, bWhats },

    methods: {
			routePathValidation () {
				if (this.$router.history.current.path.split("/")[1] !== this.currentUser.rol) {
						this.$router.push({ path: `/${this.currentUser.rol}/home` })    
				}
			},
			capitalizarPrimeraLetra(texto) {
				// Obtenemos la primera letra del texto
				const primeraLetra = texto.charAt(0);
				// Convertimos la primera letra a mayúscula
				const primeraLetraMayuscula = primeraLetra.toUpperCase();
				// Devolvemos el texto con la primera letra en mayúscula
				return primeraLetraMayuscula + texto.slice(1);
			}
    },

    mounted(){
			const socket = io("http://localhost:3001");

			window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
			this.axios.get('/api/user')
			.then((res) => {
					console.log('home page', res.data.user)
					const {id, email, rol, idSede} = res.data.user

					this.datosUsuario = res.data.user.professional
					this.currentUser.id = id.toString()
					this.currentUser.rol = rol
					this.currentUser.idSede = idSede
					this.currentUser.email = email
					this.currentUser.nombre = res.data.user.nombre

					window.addEventListener('popstate', e => {
							this.routePathValidation()
					})

					socket.emit('pedirAvisosDeAhora');

					this.routePathValidation()
					
			}).catch((err) => {
					console.log(err)
			});

			socket.on('update', (avisos)=>{
				avisos.forEach((aviso, index) => {
					if(aviso.idResponsable == this.currentUser.id){
						alertify.message(`<i class="fas fa-envelope"></i> <span>¡Recordatorio para ${aviso.responsable}! <br> ${index+1}°. ${this.capitalizarPrimeraLetra(aviso.actividad)}</span>`, false)
					}
				});
				/* if(this.currentUser.rol=="recepcionista"){
					console.log('avisos',avisos)
					if(avisos.length>0)
						if(avisos[1])
							alertify.message(`<i class="fas fa-envelope"></i> <span>¡Recordatorio! <br> 2°. ${this.capitalizarPrimeraLetra(avisos[1].actividad)}</span>`, false)
				} */
			});
    },
}
</script>

<style>
.alertify-notifier .ajs-message{
	background: rgb(8 8 8 / 95%)!important;
    color: #fff!important;
    text-align: left!important;
}
</style>