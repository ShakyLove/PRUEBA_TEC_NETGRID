<template>
  <div>
    <form @submit.prevent="submitForm">
      <div class="form-group">
        <label class="mb-1">Titulo</label>
        <div class="position-relative icon-form-control">
          <input type="text" class="form-control" v-model="projectForm.title" required>
        </div>
      </div>

      <div class="form-group">
        <label class="mb-1">Descripción</label>
        <div class="position-relative icon-form-control">
          <textarea v-model="projectForm.detail" class="form-control p-3 shadow-none" rows="5"></textarea>
        </div>
      </div>

      <div class="form-group">
        <label class="mb-1">Responsable</label>
        <div class="position-relative icon-form-control">
          <select name="select" class="form-control" v-model="projectForm.user">
            <option v-for="option in listOptions" :value="option.id" :key="option.id">
              {{ option.first_name }} {{ option.last_name }}
            </option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="col">
          <div class="form-group">
            <label class="mb-1">Fecha de inicio</label>
            <div class="position-relative icon-form-control">
              <input type="date" class="form-control" v-model="projectForm.dateInit" required>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label class="mb-1">Fecha de finalización</label>
            <div class="position-relative icon-form-control">
              <input type="date" class="form-control" v-model="projectForm.dateFinish" required>
            </div>
          </div>
        </div>
      </div>

      <button class="btn btn-info btn-block text-uppercase" type="submit" :disabled="isInvalidForm">
        Guardar
      </button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'CreateProject',
  props: {
    project: {
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
      projectForm: {
        title: '',
        detail: '',
        user: '',
        dateInit: '',
        dateFinish: ''
      },
      listOptions: []
    };
  },
  computed: {
    isInvalidForm() {
      return (
        !this.projectForm.title ||
        !this.projectForm.detail ||
        !this.projectForm.user ||
        !this.projectForm.dateInit ||
        !this.projectForm.dateFinish
      );
    }
  },
  mounted() {
    this.listUsers();
    if (this.editMode && this.project !== null) {
      this.projectForm.title = this.project.title;
      this.projectForm.detail = this.project.detail;
      this.projectForm.user = this.project.user_id;
      this.projectForm.dateInit = this.project.date_init;
      this.projectForm.dateFinish = this.project.date_finish;
    }
  },
  methods: {
    submitForm() {
      const data = {
        title: this.projectForm.title,
        detail: this.projectForm.detail,
        user_id: this.projectForm.user,
        date_init: this.projectForm.dateInit,
        date_finish: this.projectForm.dateFinish
      };

      if (this.editMode) {
        axios
          .put(`http://127.0.0.1:8000/api/project/${this.project.id}`, data)
          .then(response => {
            this.$swal({
              title: response.data.message,
              icon: 'success',
              showConfirmButton: false,
              timer: 2500
            });

            this.$emit('project-updated');
          })
          .catch(error => {
            console.error(error);
          });
      } else {
        axios
          .post('http://127.0.0.1:8000/api/project', data)
          .then(response => {
            this.$swal({
              title: response.data.message,
              icon: 'success',
              showConfirmButton: false,
              timer: 2500
            });

            this.$emit('project-created');
          })
          .catch(error => {
            console.error(error);
          });
      }
    },
    listUsers() {
      axios
        .get('http://127.0.0.1:8000/api/user')
        .then(response => {
          this.listOptions = response.data.data;
        })
        .catch(error => {
          console.error(error);
        });
    },
  }
};
</script>

<style></style>
