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
  },

  actions: {
    setSession(user, token) {
      this.user = user
      this.token = token
      this.error = null
    },

    clearSession() {
      this.user = null
      this.token = null
      this.error = null
    },

    async login(credentials) {
      this.loading = true
      this.error = null

      try {
        const { data } = await loginUser(credentials)

        if (!data.status) {
          throw new Error(data.message || 'Invalid credentials')
        }

        this.setSession(data.data.user, data.data.access_token)
        await router.push('/dashboard')
      } catch (err) {
        this.error = err.response?.data?.message || err.message || 'Login failed'
        throw this.error
      } finally {
        this.loading = false
      }
    },

    async logout() {
      this.clearSession()
      await router.push('/login')
    },
  },

  persist: {
    key: 'auth',
    storage: localStorage,
  },
})