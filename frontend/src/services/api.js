import axios from 'axios'

// Base API configuration
const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1',
  timeout: 30000,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
})

// Request interceptor - add auth token
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

// Response interceptor - handle errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Token expired or invalid
      localStorage.removeItem('auth_token')
      localStorage.removeItem('demo_user')
    }
    return Promise.reject(error)
  }
)

/**
 * Upload file to backend
 * @param {File} file - File to upload
 * @param {string} folder - Folder name (default: 'uploads')
 * @returns {Promise} - Upload response with file URL
 */
export const uploadFile = async (file, folder = 'uploads') => {
  const formData = new FormData()
  formData.append('file', file)
  formData.append('folder', folder)

  const response = await api.post('/upload', formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
  
  return response.data
}

/**
 * Convert File to Base64 (fallback untuk localStorage)
 * @param {File} file 
 * @returns {Promise<string>}
 */
export const fileToBase64 = (file) => {
  return new Promise((resolve, reject) => {
    const reader = new FileReader()
    reader.readAsDataURL(file)
    reader.onload = () => resolve(reader.result)
    reader.onerror = (error) => reject(error)
  })
}

/**
 * Check if API is available
 */
export const checkApiAvailable = async () => {
  try {
    await api.get('/statistics', { timeout: 3000 })
    return true
  } catch {
    return false
  }
}

export default api

