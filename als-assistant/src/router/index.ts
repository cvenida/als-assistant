import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/pages/LoginView.vue'
import Register from '@/pages/RegisterView.vue'
import Dashboard from '@/pages/DashboardView.vue'
import StudentsView from '@/pages/StudentsView.vue'
import CoursesView from '@/pages/CoursesView.vue'

import { useAuthStore } from '@/stores/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      redirect: '/login',
    },
    {
      path: '/login',
      name: 'login',
      component: Login,
      beforeEnter: (to, from, next) => {
        const authStore = useAuthStore()
        if (authStore.isAuthenticated) {
          next('/dashboard')
        } else {
          next()
        }
      },
    },
    {
      path: '/register',
      component: Register,
      beforeEnter: (to, from, next) => {
        const authStore = useAuthStore()
        if (authStore.isAuthenticated) {
          next('/dashboard')
        } else {
          next()
        }
      },
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
