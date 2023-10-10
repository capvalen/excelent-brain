<template>
		<ul
		class="navbar-nav sidebar sidebar-dark accordion d-print-none"
		:class="{ 'bg-warning': rolUser === 'profesional',
		'bg-dark': rolUser === 'administrador',
		'bg-success': rolUser === 'recepcionista',
		'bg-danger': rolUser === 'interno'}"
		id="accordionSidebar">

				<!-- Rol del admin -->
				<div v-if="rolUser === 'administrador'">
						<!-- Sidebar - Brand -->
						<a 
						class="sidebar-brand d-flex align-items-center justify-content-center" 
						href="/administrador/home"
						>
								<div class="sidebar-brand-icon">
										<i class="fas fa-user-md"></i>
								</div>
								<div class="sidebar-brand-text mx-3">{{ rolUser }}</div>
						</a>

						<!-- Divider -->
						<hr class="sidebar-divider my-0">

						<!-- Nav Item - Dashboard -->
						<li class="nav-item nav__list active" @click="activeSidebar()">
								<router-link  to="/administrador/home" class="nav-link">
										<i class="fas fa-home"></i>
										<span>Inicio</span>
								</router-link>
						</li>

						<!-- Divider -->
						<hr class="sidebar-divider">

						<!-- Heading -->
						<div class="sidebar-heading">
								Secciones
						</div>

						<!-- Nav Item - Dashboard -->
						<li class="nav-item nav__list" @click="activeSidebar()">
								<router-link to="/administrador/reportes/total" class="nav-link">
										<i class="fas fa-home"></i>
										<span>Reportes</span>
								</router-link>
						</li>
						<li class="nav-item nav__list" @click="activeSidebar()">
								<router-link to="/administrador/reportes-avanzados" class="nav-link">
										<i class="fas fa-home"></i>
										<span>Reportes Complementarios</span>
								</router-link>
						</li>
						<!-- Nav Item - Dashboard -->
						<li class="nav-item nav__list" @click="activeSidebar()">
								<router-link  to="/administrador/admistracion-profesionales" class="nav-link">
										<i class="fas fa-home"></i>
										<span>Admin. Profesionales</span>
								</router-link>
						</li>
						<li class="nav-item nav__list" @click="activeSidebar()">
							<router-link  to="/administrador/usuarios-simples" class="nav-link">
									<i class="fas fa-file-alt"></i>
									<span>Usuarios</span>
							</router-link>
						</li>
				</div>

				<!-- Rol del profesional -->
				<div v-if="rolUser === 'profesional'">
						<!-- Sidebar - Brand -->
						<a 
						class="sidebar-brand d-flex align-items-center justify-content-center" 
						href="/profesional/home"
						>
								<div class="sidebar-brand-icon">
										<i class="fas fa-user-md"></i>
								</div>
								<div class="sidebar-brand-text mx-3">{{ rolUser }}</div>
						</a>

						<!-- Nav Item - Dashboard -->
						<li class="nav-item nav__list active" @click="activeSidebar()">
								<router-link  to="/profesional/home" class="nav-link">
										<i class="fas fa-home"></i>
										<span>Inicio</span>
								</router-link>
						</li>

						<!-- Divider -->
						<hr class="sidebar-divider">

						<!-- Heading -->
						<div class="sidebar-heading">
								Secciones
						</div>

						<!-- Nav Item - Dashboard -->
						<li class="nav-item nav__list" @click="activeSidebar()">
								<router-link  to="/profesional/consultas" class="nav-link">
										<i class="fas fa-home"></i>
										<span>Mis Consultas</span>
								</router-link>
						</li>

						<!-- Nav Item - Dashboard -->
						<li class="nav-item nav__list" @click="activeSidebar()">
								<router-link  to="/profesional/historias" class="nav-link">
										<i class="fas fa-home"></i>
										<span>Historias Clínicas</span>
								</router-link>
						</li>

						<!-- Nav Item - Dashboard -->
						<li class="nav-item nav__list" @click="activeSidebar()">
								<router-link  to="/profesional/recursos" class="nav-link">
										<i class="fas fa-home"></i>
										<span>Recursos</span>
								</router-link>
						</li>

						<!-- Nav Item - Dashboard -->
						<li class="nav-item nav__list" @click="activeSidebar()">
								<router-link :to="{name:'recetas',params:{patientId:0}}" class="nav-link">
										<i class="fas fa-home"></i>
										<span>Recetas</span>
								</router-link>
						</li>

						<!-- <li class="nav-item nav__list" @click="activeSidebar()">
								<router-link  to="/profesional/kurame" class="nav-link">
										<i class="fas fa-home"></i>
										<span>Kurame</span>
								</router-link>
						</li> -->

						<!-- Nav Item - Dashboard -->
						<!-- <li class="nav-item">
								<router-link  to="/profesional/examenes" class="nav-link">
										<i class="fas fa-home"></i>
										<span>Exámenes</span>
								</router-link>
						</li> -->
				</div>

				<!-- Rol del interno -->
				<div v-if="rolUser === 'interno'">
						<!-- Sidebar - Brand -->
						<a 
						class="sidebar-brand d-flex align-items-center justify-content-center" 
						href="/interno/home"
						>
								<div class="sidebar-brand-icon">
										<i class="fas fa-user-md"></i>
								</div>
								<div class="sidebar-brand-text mx-3">{{ rolUser }}</div>
						</a>

						<!-- Nav Item - Dashboard -->
						<li class="nav-item nav__list active" @click="activeSidebar()">
								<router-link  to="/interno/home" class="nav-link">
										<i class="fas fa-home"></i>
										<span>Inicio</span>
								</router-link>
						</li>

						<!-- Divider -->
						<hr class="sidebar-divider">

						<!-- Heading -->
						<div class="sidebar-heading">
								Secciones
						</div>

						<!-- Nav Item - Dashboard -->
						<li class="nav-item nav__list" @click="activeSidebar()">
								<router-link  to="/interno/recursos" class="nav-link">
										<i class="fas fa-home"></i>
										<span>Recursos</span>
								</router-link>
						</li>
						<li class="nav-item nav__list" @click="activeSidebar()">
								<router-link  to="/interno/pacientes" class="nav-link">
										<i class="fas fa-user-nurse"></i>
										<span>Triaje</span>
								</router-link>
						</li>

						<!-- <li class="nav-item nav__list" @click="activeSidebar()">
								<router-link  to="/profesional/kurame" class="nav-link">
										<i class="fas fa-home"></i>
										<span>Kurame</span>
								</router-link>
						</li> -->

						<!-- Nav Item - Dashboard -->
						<!-- <li class="nav-item">
								<router-link  to="/profesional/examenes" class="nav-link">
										<i class="fas fa-home"></i>
										<span>Exámenes</span>
								</router-link>
						</li> -->
				</div>

				<!-- Rol del recepcionista -->
				<div v-if="rolUser === 'recepcionista'">
						<!-- Sidebar - Brand -->
						<a 
						class="sidebar-brand d-flex align-items-center justify-content-center" 
						href="/recepcionista/home"
						>
								<div class="sidebar-brand-icon">
										<i class="fas fa-user-md"></i>
								</div>
								<div class="sidebar-brand-text mx-3">{{ rolUser }}</div>
						</a>
				
						<!-- Nav Item - Dashboard -->
						<li class="nav-item nav__list active" @click="activeSidebar()">
								<router-link  to="/recepcionista/home" class="nav-link">
										<i class="fas fa-home"></i>
										<span>Citas</span>
								</router-link>
						</li>

						<!-- Divider -->
						<hr class="sidebar-divider">

						<!-- Heading -->
						<div class="sidebar-heading">
								Secciones
						</div>

						<li class="nav-item nav__list" @click="activeSidebar()">
								<router-link  to="/recepcionista/pagos" class="nav-link">
										<i class="fas fa-file-alt"></i>
										<span>Pagos</span>
								</router-link>
						</li>

						<!-- <li class="nav-item nav__list" @click="activeSidebar()">
								<router-link  to="/recepcionista/adicionales" class="nav-link">
										<i class="fas fa-hospital-user"></i>
										<span>Adicionales</span>
								</router-link>
						</li> -->
						<li class="nav-item nav__list" @click="activeSidebar()">
								<router-link  to="/recepcionista/cartera" class="nav-link">
										<i class="fas fa-hospital-user"></i>
										<span>Cartera de clientes</span>
								</router-link>
						</li>

						<li class="nav-item nav__list" @click="activeSidebar()">
								<router-link  to="/recepcionista/pacientes" class="nav-link">
										<i class="fas fa-hospital-user"></i>
										<span>Pacientes</span>
								</router-link>
						</li>

						<li class="nav-item nav__list" @click="activeSidebar()">
								<router-link  to="/recepcionista/recordatorios" class="nav-link">
										<i class="fas fa-birthday-cake"></i>
										<span>Gestor de datos</span>
								</router-link>
						</li>
						<!-- <li class="nav-item nav__list" @click="activeSidebar()">
								<router-link  to="/recepcionista/continuantes" class="nav-link">
										<i class="fas fa-file-alt"></i>
										<span>Continuantes</span>
								</router-link>
						</li> -->

						<!-- <li class="nav-item nav__list" @click="activeSidebar()">
							<router-link  to="/recepcionista/reportes" class="nav-link">
									<i class="fas fa-file-alt"></i>
									<span>Reportes</span>
							</router-link>
						</li> -->
						<li class="nav-item nav__list" @click="activeSidebar()">
							<router-link  to="/recepcionista/reportes-avanzados" class="nav-link">
									<i class="fas fa-file-alt"></i>
									<span>Reportes</span>
							</router-link>
						</li>
						
				</div>

				<!-- Divider -->
				<hr class="sidebar-divider d-none d-md-block">

				<!-- Sidebar Toggler (Sidebar) -->
				<div class="text-center d-none d-md-inline">
						<button class="rounded-circle border-0" id="sidebarToggle" @click="sidebarMenu()"></button>
				</div>
		</ul>
</template>

<script>
export default {
		name: 'sidebar',
		props: {
				rolUser:{
						type: String
				}
		},
		methods: {
				sidebarMenu () {
						event.target.closest("ul").classList.toggle("toggled")
				},

				activeSidebar () {
						if (!event.target.closest(".nav__list").matches(".nav__list.active")) {
								document.querySelector(".nav__list.active").classList.remove("active")
								event.target.closest(".nav__list").classList.toggle("active");
						}
				}
		}
}
</script>
