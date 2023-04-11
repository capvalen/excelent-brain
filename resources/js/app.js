require('./bootstrap');
import vue from 'vue';
window.vue = vue;

import App from './components/App.vue'

// Importaciones Axios
import VueAxios from 'vue-axios'
import axios from 'axios'

// importamos y configuramos el router
import VueRouter from 'vue-router'
import { routes } from './routes'
import Vue from 'vue'

//import Sweetalert
import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'


Vue.use(VueSweetalert2)
Vue.use(VueRouter)
Vue.use(VueAxios, axios)

// Vue.component('edit-modal', require('./components/profesional/components/home/EditModal.vue').default);
Vue.component('info-modal', require('./components/profesional/components/consultas/InfoModal.vue').default);
Vue.component('table-citas', require('./components/recepcionista/components/citas/Table.vue').default);
Vue.component('modal-cita', require('./components/recepcionista/components/citas/ModalCita.vue').default);
Vue.component('modal-patient', require('./components/recepcionista/components/citas/ModalPatient.vue').default);
Vue.component('modal-estado', require('./components/recepcionista/components/citas/ModalEstadoCita.vue').default);
// Vue.component('storie-modal', require('./components/profesional/components/historia/StorieModal.vue').default);

function loggedIn(){
    return localStorage.getItem('token')
}

const router = new VueRouter ({
  mode: 'history',
  routes: routes,
})


router.beforeEach((to, from, next) => {
    var element = document.getElementsByClassName('modal-backdrop')
    if(element.length != 0){
        element[0].classList.remove("show")
        element[0].classList.remove("fade")
        element[0].classList.remove("modal-backdrop")
    }
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (!loggedIn()) {
            next({
            path: '/login',
            query: { redirect: to.fullPath }
            })
        } else {
            next()
        }
    } else if(to.matched.some(record => record.meta.guest)){
        if (loggedIn()) {
            next({
            path: '/',
            query: { redirect: to.fullPath }
            })
        } else {
            next()
        }
    }else{
        next() // make sure to always call next()!
    }
  })

const app = new Vue({
  el: '#app',
  router: router,
  render: h => h (App)
})


