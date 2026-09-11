import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

const { VITE_API_BASE_URL } = import.meta.env

export const getActivities = async () => {
  return await axios.get(`${VITE_API_BASE_URL}/activities`)
}

export const getActivity = async (id) => {
  return await axios.get(`${VITE_API_BASE_URL}/activities/${id}`)
}

export const createActivity = async (payload) => {
  return await axios.post(`${VITE_API_BASE_URL}/activities`, payload)
}

export const updateActivity = async (id, payload) => {
  return await axios.put(`${VITE_API_BASE_URL}/activities/${id}`, payload)
}

export const deleteActivity = async (id) => {
  return await axios.delete(`${VITE_API_BASE_URL}/activities/${id}`)
}