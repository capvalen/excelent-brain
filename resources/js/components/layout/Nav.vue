<template>
     <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" @click="navbarMenu()" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>

        <ul class="navbar-nav ml-auto">
            <!-- Nav Item - User Information -->
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								<span class="mr-2 d-none d-lg-inline text-gray-600 small text-capitalize">{{ professional.name }}</span>
									<img
									v-if="checkImage(professional.imagen)"
									class="img-profile rounded"
									:src="professional.imagen" 
									>
									<img v-else src="https://excelentemente.org/logo/logoexcel.jpeg" width="50">
							</a>
							<ul class="dropdown-menu">
								<li>
									<a @click="logout" class="dropdown-item" href="#" data-bs-toggle="modal" data-target="#logoutModal">
											<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Salir
									</a>
								</li>
							</ul>
						</li>
           
        </ul>
    </nav>
</template>
<script>
export default {
    data(){
        return{
            token: localStorage.getItem('token'),
            name:''
        }
    },
    methods:{
        logout(){
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
            this.axios.post('/api/logout')
            .then((res) => {
                localStorage.removeItem('token')
                this.$router.push('/login')
            }).catch((err) => {
               console.log(err)
            });
        },
        navbarMenu() {
            document.getElementById("accordionSidebar").classList.toggle("toggled")
        },
        checkImage(url){
            var request = new XMLHttpRequest()
            request.open('GET', url, true)
            request.send()
            request.onload = function(){
                status = request.status
                if(request.status == 200){
                    return true
                }else{
                    return false
                }
            }
        }
    },
    props:{
        idUser: String,
        rolUser: String,
        professional: Object,
    },
}
</script>

<style scoped>
.img-profile {
    object-fit: contain;
}
</style>