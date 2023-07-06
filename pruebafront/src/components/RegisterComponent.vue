<template>
    <div class="osahan-login">
        <div class="text-center mb-4">
            <h5 class="font-weight-bold mt-3">Crear cuenta</h5>
        </div>
        <form @submit.prevent="createAccount">
            <div class="form-row">
                <div class="col">
                    <div class="form-group">
                        <label class="mb-1">Nombres</label>
                        <div class="position-relative icon-form-control">
                            <i class="feather-user position-absolute"></i>
                            <input type="text" class="form-control" v-model="registerForm.firstName" required>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label class="mb-1">Apellidos</label>
                        <div class="position-relative icon-form-control">
                            <i class="feather-user position-absolute"></i>
                            <input type="text" class="form-control" v-model="registerForm.lastName" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="mb-1">Usuario</label>
                <div class="position-relative icon-form-control">
                    <input type="text" class="form-control" v-model="registerForm.username" required>
                </div>
            </div>
            <div class="form-group">
                <label class="mb-1">Contraseña</label>
                <div class="position-relative icon-form-control">
                    <i class="feather-unlock position-absolute"></i>
                    <input autocomplete="off" type="password" class="form-control" v-model="registerForm.password" required>
                </div>
            </div>
            <button class="btn btn-info btn-block text-uppercase" type="submit" :disabled="isInvalidForm">Crear
                cuenta</button>
        </form>
    </div>
</template>
  
<script>
import axios from 'axios';

export default {
    name: 'RegisterComponent',
    data() {
        return {
            registerForm: {
                firstName: '',
                lastName: '',
                username: '',
                password: ''
            }
        };
    },
    computed: {
        isInvalidForm() {
            return (
                !this.registerForm.firstName ||
                !this.registerForm.lastName ||
                !this.registerForm.username ||
                !this.registerForm.password
            );
        }
    },
    methods: {
        createAccount() {
            const data = {
                firstName: this.registerForm.firstName,
                lastName: this.registerForm.lastName,
                username: this.registerForm.username,
                password: this.registerForm.password
            };

            axios.post('http://127.0.0.1:8000/api/user', data)
                .then(response => {
                    this.$swal({
                        title: response.data.message,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2500
                    });
                })
                .catch(error => {
                    console.error(error);
                });
        }
    }
};
</script>
  
<style></style>
  