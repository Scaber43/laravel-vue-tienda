import axios from 'axios'

const API_VERSION = import.meta.env.VITE_API_VERSION || 'v1'

const api = axios.create({
    baseURL: `/api/${API_VERSION}`,
})

api.interceptors.request.use((config) => {
    const token = localStorage.getItem('token')
    if (token) {
        config.headers.Authorization = `Bearer ${token}`
    }
    return config
})

export default api
