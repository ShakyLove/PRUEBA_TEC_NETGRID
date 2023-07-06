<template>
    <div>
        <form @submit.prevent="submitForm">
            <div v-if="role == 1" class="form-group">
                <label class="mb-1">Título</label>
                <div class="position-relative icon-form-control">
                    <input type="text" class="form-control" v-model="taskForm.title" required>
                </div>
            </div>

            <div v-if="role == 1" class="form-group">
                <label class="mb-1">Descripción</label>
                <div class="position-relative icon-form-control">
                    <textarea v-model="taskForm.detail" class="form-control p-3 shadow-none" rows="3"></textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="mb-1">Estado</label>
                <div class="position-relative icon-form-control">
                    <select name="select" class="form-control" v-model="taskForm.state" required>
                        <option v-for="option in stateOptions" :value="option.id" :key="option.id">{{ option.name }}
                        </option>
                    </select>
                </div>
            </div>

            <button class="btn btn-info btn-block text-uppercase" type="submit" :disabled="isInvalidForm">Guardar</button>
        </form>
    </div>
</template>
  
<script>
import axios from 'axios';
import jwtDecode from 'jwt-decode';

export default {
    name: 'CreateTask',
    props: {
        projectId: {
            type: Number,
            required: true
        },
        task: {
            type: Object,
            default: null
        },
        editMode: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            taskForm: {
                title: '',
                detail: '',
                state: ''
            },
            stateOptions: [],
            role: null
        };
    },
    computed: {
        isInvalidForm() {
            return (
                !this.taskForm.title ||
                !this.taskForm.detail ||
                !this.taskForm.state
            );
        }
    },
    mounted() {
        this.fetchStateOptions();
        if (this.editMode && this.task !== null) {
            this.taskForm.title = this.task.title;
            this.taskForm.detail = this.task.detail;
            this.taskForm.state = this.task.state_id;
        }
        this.role = jwtDecode(localStorage.getItem('userToken')).role;
    },
    methods: {
        submitForm() {
            if (this.editMode) {
                const data = {
                    title: this.taskForm.title,
                    detail: this.taskForm.detail,
                    state_id: this.taskForm.state,
                    project_id: this.projectId
                };

                axios
                    .put(`http://127.0.0.1:8000/api/task/${this.task.id}`, data)
                    .then(response => {
                        this.$swal({
                            title: response.data.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2500
                        });
                        this.$emit('task-updated');
                        this.closeForm();
                    })
                    .catch(error => {
                        console.error(error);
                    });
            } else {
                const data = {
                    title: this.taskForm.title,
                    detail: this.taskForm.detail,
                    state: this.taskForm.state,
                    project: this.projectId
                };

                axios
                    .post('http://127.0.0.1:8000/api/task', data)
                    .then(response => {
                        console.log(response);
                        this.$swal({
                            title: response.data.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2500
                        });
                        this.$emit('task-created');
                        this.closeForm();
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        },
        fetchStateOptions() {
            axios
                .get('http://127.0.0.1:8000/api/status')
                .then(response => {
                    this.stateOptions = response.data.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        closeForm() {
            this.$emit('close-modal');
        }
    }
};
</script>
  
<style></style>
  