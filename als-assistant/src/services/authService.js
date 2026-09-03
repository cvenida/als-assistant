import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

const { VITE_API_BASE_URL } = import.meta.env


export const loginUser = async(payload) => {
  return await axios.post(`${VITE_API_BASE_URL}/login`, payload);
}