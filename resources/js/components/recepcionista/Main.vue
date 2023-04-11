<template>
   <div id="wrapper">
        <side-bar :rolUser="rol"></side-bar>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav-bar v-if="rolUser" :rolUser="rol"></nav-bar>
                <div class="container-fluid">
                    {{this.currentUser.name}}
                    <router-view  :rolUser="rol"></router-view>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import SideBar from '../layout/Sidebar.vue'
import NavBar from '../layout/Nav.vue'

export default{
    name:'mainprofesional',
    data(){
        return {
           currentUser:{},
           token: localStorage.getItem('token'),
        }
    },
    components: { NavBar, SideBar },
    props:{
        rolUser: String
    },
    mounted(){
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        this.axios.get('/api/user')
        .then((res) => {
            console.log(res)
            this.currentUser = res.data
        }).catch((err) => {
            console.log(err)
        });
    }
}
</script>
