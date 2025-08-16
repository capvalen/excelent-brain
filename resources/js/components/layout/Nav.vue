<template>
     <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
		<img src="/img/logo-reporte.png" class="img-fluid">

        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" @click="navbarMenu()" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>

        <ul class="navbar-nav ml-auto">
            <!-- Nav Item - User Information -->
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								<span v-if="nombreUser" class="mr-2 d-none d-lg-inline text-gray-600 small text-capitalize"><i class="far fa-user-circle"></i> {{ nombreUser }}</span>
								<span v-else class="mr-2 d-none d-lg-inline text-gray-600 small text-capitalize"><i class="far fa-user-circle"></i> {{ professional.name }}</span>
									<img v-if="checkImage(professional.imagen)" class="img-profile rounded" :src="professional.imagen">
									<img v-else src="/img/logoexcel.jpg" width="50">
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
        professional: Object, nombreUser: String
    },
}
</script>

<style scoped>
.img-profile {
    object-fit: contain;
}
.dropdown-toggle::after{
	font-family: 'Font Awesome 7 Free'!important;
}
</style>