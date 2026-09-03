import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/pages/LoginView.vue'
import Register from '@/pages/RegisterView.vue'
import Dashboard from '@/pages/DashboardView.vue'
import StudentsView from '@/pages/StudentsView.vue'
import CoursesView from '@/pages/CoursesView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      redirect: '/login',
    },
    {
      path: '/login',
      component: Login,
    },
    {
      path: '/register',
      component: Register,
    },
    {
      path: '/dashboard',
      component: Dashboard,
    },
    {
      path: '/students',
      component: StudentsView,
    },
    {
      path: '/courses',
      component: CoursesView,
    },
  ],
})

export default router
