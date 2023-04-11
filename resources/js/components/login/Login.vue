<template>
<div class="container">

        <!-- Outer Row -->
        <div class="row">

            <div class="col-xl-12 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <img src="http://excelentemente.org/consultorio-online/public/img/logoem.png" width="100%" alt="Logo">
                                        <h1 class="h4 text-gray-900 mb-4">Bienvenido al Consultorio Online</h1>
                                    </div>
                                    <form class="user" v-on:submit.prevent>
                                        <div class="form-group">
                                            <input type="email" v-model="formData.email" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Ingrese su correo">
                                            <p class="text-danger" v-text="errors.email"></p>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" v-model="formData.password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Ingrese su contraseña">
                                            <p class="text-danger" v-text="errors.password"></p>
                                        </div>
                                        <button @click="login" class="btn btn-warning btn-user btn-block">Login</button>
                                        <hr>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            formData:{
            email:'',
            password:'',
            device_name:'browser'
            },
            errors:{}
        }
    },
    methods: {
        login(){
            this.axios.post('api/login', this.formData)
            .then((res) => {
                localStorage.setItem('token',res.data.token)
								//console.log(res.data.rol);
                this.$router.push({path:`${res.data.rol}/home`})
            }).catch((err) => {
                console.log(err)
                this.errors = err.response.data.errors
            });
        }
    }
}
</script>
