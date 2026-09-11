import { defineStore } from 'pinia'
import {
  getCourseApplications,
  applyForCourse,
  updateApplicationStatus,
  deleteCourseApplication,
} from '@/services/courseService'

export const useApplicationStore = defineStore('application', {
  state: () => ({
    applications: [],
    loading: false,
    error: null,
  }),

  getters: {
    allApplications: (state) => state.applications,
    isLoading: (state) => state.loading,
    applicationError: (state) => state.error,
  },

  actions: {
    async fetchApplications() {
      this.loading = true
      this.error = null

      try {
        const { data } = await getCourseApplications()

        if (!data.status) {
          throw new Error(data.message || 'Failed to fetch applications')
        }

        this.applications = data.data.applications
      } catch (err) {
        console.log(err)
        this.error = err.response?.data?.message || err.message
      }

      this.loading = false
    },

    async submitApplication(payload) {
      this.loading = true
      this.error = null

      try {
        const { data } = await applyForCourse(payload)

        if (!data.status) {
          throw new Error(data.message || 'Failed to submit application')
        }

        this.applications.push(data.data.application)
        return data
      } catch (err) {
        console.log(err)
        this.error = err.response?.data?.message || err.message
      }

      this.loading = false
    },

    async changeStatus(id, status) {
      this.loading = true
      this.error = null

      try {
        const { data } = await updateApplicationStatus(id, { status })

        if (!data.status) {
          throw new Error(data.message || 'Failed to update application status')
        }

        const index = this.applications.findIndex((a) => a.application_id === id)
        if (index !== -1) {
          this.applications[index] = data.data.application
        }

        return data
      } catch (err) {
        console.log(err)
        this.error = err.response?.data?.message || err.message
      }

      this.loading = false
    },

    async removeApplication(id) {
      this.loading = true
      this.error = null

      try {
        const { data } = await deleteCourseApplication(id)

        if (!data.status) {
          throw new Error(data.message || 'Failed to delete application')
        }

        this.applications = this.applications.filter((a) => a.application_id !== id)
      } catch (err) {
        console.log(err)
        this.error = err.response?.data?.message || err.message
      }

      this.loading = false
    },
  },
})