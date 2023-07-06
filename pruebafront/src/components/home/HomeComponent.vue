<template>
    <NavComponent></NavComponent>
    <div class="m-0">
        <div class="container">
            <div class="border rounded bg-white shadow-sm p-2 mt-3">
                <h6 class="title row justify-content-center align-items-center">
                    Listado de proyectos
                </h6>
            </div>

            <div class="border rounded bg-white shadow-sm p-2 mt-3">
                <div class="mt-3 col-md-12">
                    <div class="col-12 row">
                        <div class="col-5">
                            <label class="form-label">Fecha de inicio</label>
                            <input type="date" class="form-control" v-model="dateInit" @change="handleDateInitChange" />
                        </div>
                        <div class="col-5">
                            <label class="form-label">Fecha de finalización</label>
                            <input type="date" class="form-control" v-model="dateFinish" @change="handleDateFinishChange"/>
                        </div>
                        <div class="col-2 d-flex align-items-end justify-content-end">
                            <button v-if="role == 1" style="width: 200px; background-color: #0D7D64; color:#fff"
                                class="btn btn-white" @click="openModal">
                                Crear proyecto
                            </button>
                        </div>
                    </div>
                    <div class="mt-4" v-if="projectList.length == 0">
                        <h6>Aún no se han creado proyectos...</h6>
                    </div>
                    <div class="row mt-4">
                        <div v-for="item in projectList" :key="item.id" class="col-md-4 infocard">
                            <div class="card m-1">
                                <div class="card-header">
                                    <h6 class="card-title">{{ item.title }}</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ item.detail }}</p>
                                    <hr />
                                    <div class="row" id="label">
                                        <p class="m-1">Fecha de inicio: {{ item.date_init }}</p>
                                        <p class="m-1">
                                            Fecha de Finalización: {{ item.date_finish }}
                                        </p>
                                    </div>
                                    <div class="dbtn">
                                        <a style="background-color: #035284; color: #fff" href="#"
                                            class="btn btn-white mt-3 mr-1 ml-1" @click="viewProject(item.id)">
                                            Ver proyecto
                                        </a>
                                        <a v-if="role == 1" href="#" class="btn btn-info mt-3 mr-1 ml-1"
                                            @click="openEditModal(item)">
                                            Editar
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
                        <h3 class="modal-title">
                            {{ isEditMode ? 'Editar Proyecto' : 'Crear Proyecto' }}
                        </h3>
                        <a @click="closeModal" class="close-modal-btn">×</a>
                    </div>
                    <CreateProject :project="selectedProject" :editMode="isEditMode" @project-created="handleProjectCreated"
                        @project-updated="handleProjectUpdated"></CreateProject>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script>
import NavComponent from './NavComponent.vue';
import CreateProject from './CreateProject.vue';
import axios from 'axios';
import router from '../../router';
import jwtDecode from 'jwt-decode';

export default {
    name: 'HomeComponent',
    components: {
        NavComponent,
        CreateProject
    },
    data() {
        return {
            showModal: false,
            projectList: [],
            selectedProject: null,
            editMode: false,
            role: null,
            dateInit: '',
            dateFinish: ''
        };
    },
    computed: {
        isEditMode() {
            return this.editMode && this.selectedProject !== null;
        }
    },
    mounted() {
        this.fetchProjectList();
        this.role = jwtDecode(localStorage.getItem('userToken')).role;
    },
    methods: {
        fetchProjectList() {
            const data = this.getApiData();
            this.callApi(data);
        },
        handleDateInitChange() {
            const data = this.getApiData();
            data.date_init = this.dateInit;
            this.callApi(data);
        },
        handleDateFinishChange() {
            const data = this.getApiData();
            data.date_finish = this.dateFinish;
            this.callApi(data);
        },
        getApiData() {
            return {
                role: jwtDecode(localStorage.getItem('userToken')).role,
                user_id: jwtDecode(localStorage.getItem('userToken')).id,
            };
        },
        callApi(data) {
            axios
                .post('http://127.0.0.1:8000/api/list', data)
                .then(response => {
                    this.projectList = response.data.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        openModal() {
            this.editMode = false;
            this.selectedProject = null;
            this.showModal = true;
        },
        openEditModal(project) {
            this.editMode = true;
            this.selectedProject = project;
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
            this.selectedProject = null;
        },
        handleProjectCreated() {
            this.showModal = false;
            this.selectedProject = null;
            this.fetchProjectList();
        },
        handleProjectUpdated() {
            this.showModal = false;
            this.selectedProject = null;
            this.fetchProjectList();
        },
        viewProject(id) {
            router.push({ name: 'project', params: { id: id } });
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
</style>
  