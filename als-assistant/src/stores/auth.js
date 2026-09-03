import { defineStore } from 'pinia'
import { loginUser } from '@/services/authService'
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

    // Sign Up Action
    // async signup(userData) {
    //   this.loading = true
    //   this.error = null

    //   try {
    //     // Replace with your actual API endpoint (e.g., await axios.post('/api/signup', userData))
    //     const response = await fetch('/api/signup', {
    //       method: 'POST',
    //       headers: { 'Content-Type': 'application/json' },
    //       body: JSON.stringify(userData),
    //     })

    //     if (!response.ok) {
    //       throw new Error('Failed to create account')
    //     }

    //     const data = await response.json()
    //     this.setSession(data.user, data.token)
    //     return true
    //   } catch (err: any) {
    //     this.error = err.message || 'Signup failed. Please try again.'
    //     return false
    //   } finally {
    //     this.loading = false
    //   }
    // },

    async logout() {
      try {
        // await fetch('/api/logout', {
        //   method: 'POST',
        //   headers: {
        //     Authorization: `Bearer ${this.token}`,
        //   },
        // })

        await router.push('/login')
      } catch (err) {
        console.warn('Backend logout failed or token was already invalid')
      } finally {
        this.clearSession()
      }
    },
  },
})