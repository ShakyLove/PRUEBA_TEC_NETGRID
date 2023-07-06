<template>
    <NavComponent></NavComponent>
    <div class="m-0">
        <div class="container">
            <div class="border rounded bg-white shadow-sm p-2 mt-3">
                <div class="mt-3 col-md-12">
                    <div v-if="projectData != null">
                        <div class="d-flex justify-content-between align-items-center text-center">
                            <h6>{{ projectData.title }}</h6>
                            <div>
                                <a v-if="role == 1" class="m-1 btfun eliminar"
                                    @click="confirmDeleteProject(projectData.id)">
                                    <i class="feather-trash"></i>
                                </a>
                            </div>
                        </div>
                        <p class="mt-4">{{ projectData.detail }}</p>
                        <div class="text-end" id="label">
                            <p class="m-1"><b>Fecha de inicio:</b> {{ projectData.date_init }}</p>
                            <p class="m-1"><b>Fecha de Finalización:</b> {{ projectData.date_finish }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="col-12 row flex-end justify-content-end">
                        <div class="col-2 d-flex align-items-end justify-content-end">
                            <button v-if="role == 1" style="width: 200px; background-color: #0D7D64; color:#fff"
                                class="btn btn-white" @click="openModal">
                                Crear tarea
                            </button>
                        </div>
                        <div class="col-5">
                            <select name="select" class="form-control" @change="state($event)">
                                <option v-for="option in stateOptions" :value="option.id" :key="option.id">{{ option.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4" v-if="tasks.length == 0">
                        <h6>Aún no se han creado tareas...</h6>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6 task" v-for="item in tasks" :key="item.id">
                            <div class="card m-1">
                                <div class="card-header d-flex">
                                    <h6 class="card-title">{{ item.title }} -</h6>
                                    <p v-if="item.state_id == 3" class="badge bg-success ml-1"
                                        style="color: white; font-size: 14px">
                                        {{ item.name }}
                                    </p>
                                    <p v-if="item.state_id == 1" class="badge bg-info ml-1"
                                        style="color: white; font-size: 14px">
                                        {{ item.name }}
                                    </p>
                                    <p v-if="item.state_id == 2" class="badge bg-warning ml-1"
                                        style="color: white; font-size: 14px">
                                        {{ item.name }}
                                    </p>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        {{ item.detail }}
                                    </p>
                                    <div class="dbtn">
                                        <a href="#" class="btn btn-info mt-3 mr-2 ml-2" @click="openEditModal(item)">
                                            Editar
                                        </a>
                                        <a v-if="role == 1" href="#" class="btn btn-danger mt-3 mr-2 ml-2"
                                            @click="deleteTask(item.id)">
                                            Eliminar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div v-if="showModal" class="modal-overlay">
                <div class="modal-container">
                    <div class="modal-header">
                        <h3 class="modal-title">{{ isEditMode ? 'Editar Tarea' : 'Crear Tarea' }}</h3>
                        <a @click="closeModal" class="close-modal-btn">×</a>
                    </div>
                    <CreateTask :projectId="projectData.id" :task="selectedTask" :editMode="isEditMode"
                        @task-created="infoProject(projectData.id)" @task-updated="infoProject(projectData.id)">
                    </CreateTask>
                </div>
            </div>
        </div>
    </div>
</template>
    
<script>
import NavComponent from '../home/NavComponent.vue';
import CreateTask from './CreateTask.vue';
import axios from 'axios';
import jwtDecode from 'jwt-decode';

export default {
    name: 'ProjectComponent',
    components: {
        NavComponent,
        CreateTask
    },
    data() {
        return {
            showModal: false,
            projectData: null,
            tasks: [],
            selectedTask: null,
            editMode: false,
            role: null,
            stateOptions: [],
        };
    },
    computed: {
        isEditMode() {
            return this.editMode && this.selectedTask !== null;
        }
    },
    mounted() {
        this.fetchStateOptions();
        const projectId = this.$route.params.id;
        this.infoProject(projectId);
        this.role = jwtDecode(localStorage.getItem('userToken')).role;
    },
    methods: {
        infoProject(projectId) {
            const data = {}
            axios
                .post(`http://127.0.0.1:8000/api/info_project/${projectId}`, data)
                .then(response => {
                    this.projectData = response.data.data.project;
                    this.tasks = response.data.data.tasks;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        state(event) {
            const data = {
                status: event.target.value
            };
            const projectId = this.$route.params.id;
            axios
                .post(`http://127.0.0.1:8000/api/info_project/${projectId}`, data)
                .then(res => {
                    this.tasks = res.data.data.tasks;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        deleteProject(projectId) {
            axios
                .delete(`http://127.0.0.1:8000/api/project/${projectId}`)
                .then(response => {
                    console.log(response);
                    this.$swal({
                        title: response.data.message,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2500
                    });
                    this.$router.push('/home');
                })
                .catch(error => {
                    console.error(error);
                });
        },
        confirmDeleteProject(projectId) {
            this.$swal({
                title: '¿Estás seguro?',
                text: 'Una vez eliminado, no podrás recuperar este proyecto',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(result => {
                if (result.isConfirmed) {
                    this.deleteProject(projectId);
                }
            });
        },
        deleteTask(taskId) {
            this.$swal({
                title: '¿Estás seguro de eliminar esta tarea?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(result => {
                if (result.isConfirmed) {
                    axios
                        .delete(`http://127.0.0.1:8000/api/task/${taskId}`)
                        .then(response => {
                            this.$swal({
                                title: response.data.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2500
                            });
                            this.infoProject(this.$route.params.id);
                        })
                        .catch(error => {
                            console.error(error);
                        });
                }
            });
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
        openModal() {
            this.editMode = false;
            this.selectedTask = null;
            this.showModal = true;
        },
        openEditModal(task) {
            this.editMode = true;
            this.selectedTask = task;
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
        }
    }
};
</script>
    
<style scoped>
.bg-navbar {
    background-color: #343A40;
}

h6 {
    color: #000;
    font-weight: bold;
}

p {
    color: #000;
}

.dbtn {
    display: flex;
    justify-content: end;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-container {
    background-color: #fff;
    padding: 20px;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    width: 50%;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    margin: 0;
}

.close-modal-btn {
    border: none;
    background-color: transparent;
    font-size: 20px;
    cursor: pointer;
}

.modal-content {
    margin-top: 20px;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 20px;
}

.open-modal-btn {
    padding: 10px 20px;
    background-color: #035284;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btfun {
    cursor: pointer;
    font-size: 20px;
}
</style>
  