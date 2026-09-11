import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/pages/LoginView.vue'
import Register from '@/pages/RegisterView.vue'
import Dashboard from '@/pages/teacher/DashboardView.vue'
import StudentDashboard from '@/pages/student/DashboardView.vue'
import StudentsView from '@/pages/teacher/StudentsView.vue'
import CoursesView from '@/pages/teacher/CoursesView.vue'
import { USER_TYPE } from '@/shared/constants'
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
          const userType = authStore.currentUser?.type

          if (userType == USER_TYPE.TEACHER) {
            return next('/dashboard')
          }
          return next('/student/dashboard')
        }
        next()
      },
    },
    {
      path: '/register',
      name: 'register',
      component: Register,
      beforeEnter: (to, from, next) => {
        const authStore = useAuthStore()
        if (authStore.isAuthenticated) {
          const userType = authStore.currentUser?.type

          if (userType == USER_TYPE.TEACHER) {
            return next('/dashboard')
          }
          return next('/student/dashboard')
        }
        next()
      },
    },
    // Teacher Routes
    {
      path: '/',
      beforeEnter: (to, from, next) => {
        const authStore = useAuthStore()
        if (!authStore.isAuthenticated) {
          return next('/login')
        }
        if (authStore.currentUser?.type !== USER_TYPE.TEACHER) {
          return next('/student/dashboard')
        }
        next()
      },
      children: [
        {
          path: 'dashboard',
          name: 'teacher-dashboard',
          component: Dashboard,
        },
        {
          path: 'students',
          name: 'teacher-students',
          component: StudentsView,
        },
        {
          path: 'courses',
          name: 'teacher-courses',
          component: CoursesView,
        },
      ],
    },

    // Student Routes
    {
      path: '/student',
      beforeEnter: (to, from, next) => {
        const authStore = useAuthStore()
        if (!authStore.isAuthenticated) {
          return next('/login')
        }
        if (authStore.currentUser?.type === USER_TYPE.TEACHER) {
          return next('/dashboard')
        }
        next()
      },
      children: [
        {
          path: '',
          redirect: '/student/dashboard',
        },
        {
          path: 'dashboard',
          name: 'student-dashboard',
          component: StudentDashboard,
        },
      ],
    },
  ],
})

export default router