<template>
    <div class="bg-white">
        <div class="container">
            <div class="row justify-content-center align-items-center d-flex vh-100">
                <div class="img-work col-md-7">
                    <img src="../assets/img/task2.png" alt="" />
                </div>
                <div class="col-md-4 mx-auto">
                    <div v-if="forms" class="osahan-login py-4">
                        <div class="text-center mb-4">
                            <h5 class="font-weight-bold mt-3">Bienvenido</h5>
                            <p class="text-muted">
                                Controla, organiza, supervisa y colabora eficientemente en tus proyectos para lograr
                                resultados exitosos y maximizar la productividad.
                            </p>
                        </div>
                        <form @submit.prevent="login">
                            <div class="form-group">
                                <label class="mb-1">Usuario</label>
                                <div class="position-relative icon-form-control">
                                    <i class="feather-user position-absolute"></i>
                                    <input type="text" class="form-control" v-model="loginForm.username" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="mb-1">Contraseña</label>
                                <div class="position-relative icon-form-control">
                                    <i class="feather-unlock position-absolute"></i>
                                    <input type="password" class="form-control" v-model="loginForm.password" />
                                </div>
                            </div>
                            <button class="btn btn-info btn-block text-uppercase btSuc" type="submit">Ingresar</button>
                            <div class="py-3 d-flex align-item-center">
                                <span class="ml-auto">
                                    Eres nuevo?
                                    <a class="font-weight-bold" href="" @click.prevent="forms = false">Crear cuenta</a>
                                </span>
                            </div>
                        </form>
                    </div>
                    <div v-else>
                        <RegisterComponent></RegisterComponent>
                        <div class="py-3 d-flex align-item-center">
                            <span class="ml-auto">
                                Ya tienes una cuenta?
                                <a @click.prevent="forms = true" class="font-weight-bold" href="">Iniciar Sesión</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import RegisterComponent from './RegisterComponent';

export default {
    name: 'LoginComponent',
    components: {
        RegisterComponent
    },
    data() {
        return {
            forms: true,
            loginForm: {
                username: '',
                password: ''
            }
        };
    },
    methods: {
        login() {
            axios.defaults.baseURL = 'http://127.0.0.1:8000/api/';

            axios.post('login', this.loginForm)
                .then(response => {
                    localStorage.setItem('userToken', response.data.data.token);

                    // Verificar si el token existe en el localStorage
                    const userToken = localStorage.getItem('userToken');
                    if (userToken) {
                        this.$router.push('/home');
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        }
    }
};
</script>

<style scoped>
.img-work img {
    width: 100%;
}

a {
    color: #0672CA;
    text-decoration: none;
    background-color: transparent;
}

.btSuc {
    background-color: #02426A;
}
</style>
