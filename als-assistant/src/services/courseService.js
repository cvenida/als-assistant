import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

const { VITE_API_BASE_URL } = import.meta.env

const getAuthHeaders = () => {
  const authStore = useAuthStore()
  return {
    headers: {
      Authorization: `Bearer ${authStore.token}`,
      'Content-Type': 'application/json',
      Accept: 'application/json',
    },
  }
}

// Course Endpoints
export const getCourses = async () => {
  return await axios.get(`${VITE_API_BASE_URL}/courses`)
}

export const getCourse = async (id) => {
  return await axios.get(`${VITE_API_BASE_URL}/courses/${id}`)
}

export const createCourse = async (payload) => {
  return await axios.post(`${VITE_API_BASE_URL}/courses`, payload)
}

export const updateCourse = async (id, payload) => {
  return await axios.put(`${VITE_API_BASE_URL}/courses/${id}`, payload)
}

export const deleteCourse = async (id) => {
  return await axios.delete(`${VITE_API_BASE_URL}/courses/${id}`)
}

// Question Endpoints
export const getQuestions = async () => {
  return await axios.get(`${VITE_API_BASE_URL}/questions`)
}

export const getQuestion = async (id) => {
  return await axios.get(`${VITE_API_BASE_URL}/questions/${id}`)
}

export const createQuestion = async (payload) => {
  return await axios.post(`${VITE_API_BASE_URL}/questions`, payload)
}

export const updateQuestion = async (id, payload) => {
  return await axios.put(`${VITE_API_BASE_URL}/questions/${id}`, payload)
}

export const deleteQuestion = async (id) => {
  return await axios.delete(`${VITE_API_BASE_URL}/questions/${id}`)
}

// Course Application Endpoints
export const getCourseApplications = async () => {
  return await axios.get(`${VITE_API_BASE_URL}/course-applications`)
}

export const getCourseApplication = async (id) => {
  return await axios.get(`${VITE_API_BASE_URL}/course-applications/${id}`)
}

export const applyForCourse = async (payload) => {
  return await axios.post(`${VITE_API_BASE_URL}/course-applications`, payload)
}

export const updateApplicationStatus = async (id, payload) => {
  return await axios.put(`${VITE_API_BASE_URL}/course-applications/${id}/status`, payload)
}

export const deleteCourseApplication = async (id) => {
  return await axios.delete(`${VITE_API_BASE_URL}/course-applications/${id}`)
}