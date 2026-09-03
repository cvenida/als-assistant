import { defineStore } from 'pinia'
import { loginUser, registerUser } from '@/services/authService'
import router from '@/router'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
    loading: false,
    error: null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    currentUser: (state) => state.user,
    authError: (state) => state.error,
    isLoading: (state) => state.loading,
  },

  actions: {
    setSession(user, token) {
      this.user = user
      this.token = token
      this.error = null

      localStorage.setItem('user', JSON.stringify(user))
      localStorage.setItem('token', token)
    },

    clearSession() {
      this.user = null
      this.token = null
      this.error = null

      localStorage.removeItem('user')
      localStorage.removeItem('token')
    },

    async login(credentials) {
      this.loading = true
      this.error = null

      const { data } = await loginUser(credentials)

      if (!data.status) {
        throw new Error('Invalid email or password')
      }

      this.setSession(data.data.user, data.data.access_token)
      await router.push('/dashboard')
      this.loading = false
    },

    async signup(userData) {
      this.loading = true
      this.error = null

      try {
        const { data } = await registerUser(userData)

        if (!response.ok) {
          throw new Error('Failed to create account')
        }

        this.setSession(data.data.user, data.data.access_token)
        await router.push('/dashboard')
      } catch (error) {
        console.log(error)
      }
      this.loading = false
    },

    async logout() {
      try {
        await router.push('/login')
      } catch (err) {
        console.warn('Backend logout failed or token was already invalid')
      } finally {
        this.clearSession()
      }
    },
  },
})