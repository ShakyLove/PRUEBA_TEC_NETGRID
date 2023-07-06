import { createRouter, createWebHistory } from 'vue-router'
import LoginComponent from '../components/LoginComponent.vue'
import HomeComponent from '../components/home/HomeComponent.vue'
import CreateProject from '../components/home/CreateProject.vue'
import ProjectComponent from '../components/Project/ProjectComponent.vue'

const routes = [
  {
    path: '/login',
    name: 'login',
    component: LoginComponent
  },
  {
    path: '/home',
    name: 'home',
    component: HomeComponent
  },
  {
    path: '/modal',
    name: 'modal',
    component: CreateProject
  },
  {
    path: '/project/:id',
    name: 'project',
    component: ProjectComponent
  },
  { path: '/', redirect: '/login' }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
