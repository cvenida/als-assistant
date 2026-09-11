import { defineStore } from 'pinia'
import {
  getCourses,
  getCourse,
  createCourse,
  updateCourse,
  deleteCourse,
} from '@/services/courseService'

export const useCourseStore = defineStore('course', {
  state: () => ({
    courses: [],
    currentCourse: null,
    loading: false,
    error: null,
  }),

  getters: {
    allCourses: (state) => state.courses,
    selectedCourse: (state) => state.currentCourse,
    isLoading: (state) => state.loading,
    courseError: (state) => state.error,
  },

  actions: {
    async fetchCourses() {
      this.loading = true
      this.error = null

      try {
        const { data } = await getCourses()

        this.courses = data
      } catch (err) {
        console.log(err)
        this.error = err.response?.data?.message || err.message
      }

      this.loading = false
    },

    async fetchCourseById(id) {
      this.loading = true
      this.error = null

      try {
        const { data } = await getCourse(id)

        if (!data.status) {
          throw new Error(data.message || 'Failed to fetch course details')
        }

        this.currentCourse = data.data.course
      } catch (err) {
        console.log(err)
        this.error = err.response?.data?.message || err.message
      }

      this.loading = false
    },

    async addCourse(payload) {
      this.loading = true
      this.error = null

      try {
        const { data } = await createCourse(payload)

        if (!data.status) {
          throw new Error(data.message || 'Failed to create course')
        }

        this.courses.push(data.data.course)
        return data
      } catch (err) {
        console.log(err)
        this.error = err.response?.data?.message || err.message
      }

      this.loading = false
    },

    async editCourse(id, payload) {
      this.loading = true
      this.error = null

      try {
        const { data } = await updateCourse(id, payload)

        if (!data.status) {
          throw new Error(data.message || 'Failed to update course')
        }

        const index = this.courses.findIndex((c) => c.id === id)
        if (index !== -1) {
          this.courses[index] = data.data.course
        }

        if (this.currentCourse?.id === id) {
          this.currentCourse = data.data.course
        }

        return data
      } catch (err) {
        console.log(err)
        this.error = err.response?.data?.message || err.message
      }

      this.loading = false
    },

    async removeCourse(id) {
      this.loading = true
      this.error = null

      try {
        const { data } = await deleteCourse(id)

        if (!data.status) {
          throw new Error(data.message || 'Failed to delete course')
        }

        this.courses = this.courses.filter((c) => c.id !== id)
        if (this.currentCourse?.id === id) {
          this.currentCourse = null
        }
      } catch (err) {
        console.log(err)
        this.error = err.response?.data?.message || err.message
      }

      this.loading = false
    },
  },
})