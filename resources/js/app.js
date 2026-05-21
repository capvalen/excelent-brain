require('./bootstrap');
import vue from 'vue';
window.vue = vue;

import App from './components/App.vue'

// Importaciones Axios
import VueAxios from 'vue-axios'
import axios from 'axios'

// --- INICIO FIX 429 TOO MANY REQUESTS ---
// Cache específico solo para /api/user para evitar error 429 (Too Many Requests)
const originalGet = axios.get;
let userPromise = null;
let userCache = null;
let userCacheTime = null;

axios.get = function(url, config) {
    if (url === '/api/user' || url.endsWith('/api/user')) {
        // Si hay caché válido (menor a 60 segs), retornar clon para evitar mutaciones
        if (userCache && (Date.now() - userCacheTime < 60000)) {
            return Promise.resolve({ 
                ...userCache, 
                data: JSON.parse(JSON.stringify(userCache.data)) 
            });
        }
        // Si ya hay una petición en curso, reutilizar la promesa
        if (userPromise) {
            return userPromise.then(res => ({
                ...res,
                data: JSON.parse(JSON.stringify(res.data))
            }));
        }
        
        userPromise = originalGet.call(this, url, config).then(response => {
            userCache = { ...response, data: JSON.parse(JSON.stringify(response.data)) };
            userCacheTime = Date.now();
            userPromise = null;
            return response;
        }).catch(error => {
            userPromise = null;
            throw error;
        });
        
        return userPromise;
    }
    
    // Dejar pasar normalmente todas las demás peticiones GET
    return originalGet.call(this, url, config);
};

// Limpiar caché al cerrar sesión
axios.interceptors.request.use(config => {
    if (config.url === '/api/logout' || config.url?.endsWith('/api/logout')) {
        userCache = null;
        userCacheTime = null;
        userPromise = null;
    }
    return config;
});
// --- FIN FIX 429 TOO MANY REQUESTS ---

// importamos y configuramos el router
import VueRouter from 'vue-router'
import { routes } from './routes'
import Vue from 'vue'

//import Sweetalert
import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

//importando los css de alertify
import 'alertifyjs/build/css/alertify.min.css';
import 'alertifyjs/build/css/themes/semantic.css';


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

// Purge any corrupted token stored as the literal string "undefined" or "null"
const _rawToken = localStorage.getItem('token')
if (_rawToken === 'undefined' || _rawToken === 'null') {
    localStorage.removeItem('token')
}

function loggedIn(){
    const t = localStorage.getItem('token')
    return t && t !== 'undefined' && t !== 'null' ? t : null
}

// Expose the Sanctum token so Vue templates can append ?token=$token to PDF URLs.
// Defined as a getter so it reads localStorage fresh on every access (not frozen at load time).
Object.defineProperty(Vue.prototype, '$token', {
    get() { return localStorage.getItem('token') }
})

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


