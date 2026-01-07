/**
 * Services Layer - Using LocalStorage
 * Semua data disimpan di localStorage browser, tidak memerlukan backend API
 */

import { 
  initializeData,
  settingsStorage,
  slidersStorage,
  articlesStorage,
  galleriesStorage,
  documentsStorage,
  complaintsStorage,
  authStorage,
  statisticsStorage,
  profilesStorage,
  usersStorage,
  rolesStorage
} from './localStorage'
import api, { checkApiAvailable, uploadFile } from './api'

// Initialize default data on first load
initializeData()

// ============================================
// ARTICLE SERVICE
// ============================================

export const articleService = {
  async getAll(params = {}) {
    const data = articlesStorage.getAll(params)
    return { success: true, data }
  },

  async getBySlug(slug) {
    const data = articlesStorage.getBySlug(slug)
    return { success: true, data }
  },

  async create(data) {
    const result = articlesStorage.create(data)
    return { success: true, data: result }
  },

  async update(id, data) {
    const result = articlesStorage.update(id, data)
    return { success: true, data: result }
  },

  async delete(id) {
    articlesStorage.delete(id)
    return { success: true }
  }
}

// ============================================
// GALLERY SERVICE
// ============================================

export const galleryService = {
  async getAll(params = {}) {
    const data = galleriesStorage.getAll(params)
    return { success: true, data }
  },

  async create(data) {
    const result = galleriesStorage.create(data)
    return { success: true, data: result }
  },

  async update(id, data) {
    const result = galleriesStorage.update(id, data)
    return { success: true, data: result }
  },

  async delete(id) {
    galleriesStorage.delete(id)
    return { success: true }
  }
}

// ============================================
// DOCUMENT SERVICE
// ============================================

export const documentService = {
  async getAll(params = {}) {
    const data = documentsStorage.getAll(params)
    return { success: true, data }
  },

  async create(data) {
    const result = documentsStorage.create(data)
    return { success: true, data: result }
  },

  async update(id, data) {
    const result = documentsStorage.update(id, data)
    return { success: true, data: result }
  },

  async delete(id) {
    documentsStorage.delete(id)
    return { success: true }
  }
}

// ============================================
// COMPLAINT SERVICE
// ============================================

export const complaintService = {
  async submit(data) {
    const result = complaintsStorage.create(data)
    return { success: true, data: result }
  },

  async getAll(params = {}) {
    const data = complaintsStorage.getAll()
    return { success: true, data }
  },

  async get(id) {
    const data = complaintsStorage.get(id)
    return { success: true, data }
  },

  async update(id, data) {
    const result = complaintsStorage.update(id, data)
    return { success: true, data: result }
  }
}

// ============================================
// STATISTICS SERVICE
// ============================================

export const statisticsService = {
  async get() {
    const data = statisticsStorage.get()
    return { success: true, data }
  }
}

// ============================================
// AUTH SERVICE
// ============================================

export const authService = {
  async login(credentials) {
    // Use usersStorage for authentication with role support
    const result = usersStorage.authenticate(credentials.email, credentials.password)
    if (result.success) {
      localStorage.setItem('auth_token', 'demo_token_' + Date.now())
      localStorage.setItem('demo_user', JSON.stringify(result.user))
      return { success: true, data: { user: result.user, token: 'demo_token' } }
    }
    throw new Error(result.message || 'Email atau password salah')
  },

  getUser() {
    try {
      const userStr = localStorage.getItem('demo_user')
      return userStr ? JSON.parse(userStr) : null
    } catch {
      return null
    }
  },

  async logout() {
    localStorage.removeItem('auth_token')
    localStorage.removeItem('demo_user')
    return { success: true }
  },

  isLoggedIn() {
    return !!localStorage.getItem('auth_token') && !!localStorage.getItem('demo_user')
  },

  // Helper methods for role checking
  hasRole(roleCode) {
    const user = this.getUser()
    return user?.role?.code === roleCode
  },

  hasAnyRole(roleCodes) {
    const user = this.getUser()
    return user?.role && roleCodes.includes(user.role.code)
  },

  isAdmin() {
    return this.hasAnyRole(['super_admin', 'admin'])
  },

  isSuperAdmin() {
    return this.hasRole('super_admin')
  },

  getRoleCode() {
    const user = this.getUser()
    return user?.role?.code || null
  }
}

// ============================================
// SLIDER SERVICE
// ============================================

export const sliderService = {
  async getActive() {
    const data = slidersStorage.getActive()
    return { success: true, data }
  },

  async getAll() {
    const data = slidersStorage.getAll()
    return { success: true, data }
  },

  async get(id) {
    const data = slidersStorage.get(id)
    return { success: true, data }
  },

  async create(data) {
    const result = slidersStorage.create(data)
    return { success: true, data: result }
  },

  async update(id, data) {
    const result = slidersStorage.update(id, data)
    return { success: true, data: result }
  },

  async delete(id) {
    slidersStorage.delete(id)
    return { success: true }
  },

  async toggleActive(id) {
    const result = slidersStorage.toggleActive(id)
    return { success: true, data: result }
  }
}

// ============================================
// PROFILE SERVICE
// ============================================

export const profileService = {
  async getActive() {
    const data = profilesStorage.getAll()
    return { success: true, data }
  },

  async get(key) {
    const data = profilesStorage.get(key)
    return { success: true, data }
  },

  async getAll() {
    const data = profilesStorage.getAll()
    return { success: true, data }
  },

  async create(data) {
    const result = profilesStorage.update(data.section_key, data)
    return { success: true, data: result }
  },

  async update(key, data) {
    const result = profilesStorage.update(key, data)
    return { success: true, data: result }
  }
}

// ============================================
// SETTINGS SERVICE (NEW)
// ============================================

export const settingsService = {
  get() {
    return settingsStorage.get()
  },

  save(data) {
    return settingsStorage.update(data)
  },

  update(data) {
    return settingsStorage.update(data)
  }
}

// ============================================
// USER SERVICE
// ============================================

export const userService = {
  async getAll(params = {}) {
    const data = usersStorage.getAll(params)
    return { success: true, data }
  },

  async get(id) {
    const data = usersStorage.get(id)
    return { success: true, data }
  },

  async create(data) {
    const result = usersStorage.create(data)
    return { success: true, data: result }
  },

  async update(id, data) {
    const result = usersStorage.update(id, data)
    return { success: true, data: result }
  },

  async delete(id) {
    usersStorage.delete(id)
    return { success: true }
  },

  async toggleActive(id) {
    const result = usersStorage.toggleActive(id)
    return { success: true, data: result }
  }
}

// ============================================
// ROLE SERVICE
// ============================================

export const roleService = {
  async getAll() {
    const data = rolesStorage.getAll()
    return { success: true, data }
  },

  async get(id) {
    const data = rolesStorage.get(id)
    return { success: true, data }
  },

  getByCode(code) {
    return rolesStorage.getByCode(code)
  }
}

// ============================================
// NEW SERVICES - Using API
// ============================================

// Category Service
export const categoryService = {
  async getAll() {
    const response = await api.get('/categories')
    return { success: true, data: response.data }
  },
  async create(data) {
    const response = await api.post('/categories', data)
    return { success: true, data: response.data }
  },
  async update(id, data) {
    const response = await api.put(`/categories/${id}`, data)
    return { success: true, data: response.data }
  },
  async delete(id) {
    await api.delete(`/categories/${id}`)
    return { success: true }
  }
}

// Peminjaman Service (Gedung)
export const peminjamanService = {
  async getAll(params = {}) {
    const response = await api.get('/peminjaman', { params })
    return { success: true, data: response.data }
  },
  async getApproved() {
    const response = await api.get('/peminjaman/approved')
    return { success: true, data: response.data }
  },
  async submit(data) {
    const response = await api.post('/peminjaman', data)
    return { success: true, data: response.data }
  },
  async get(id) {
    const response = await api.get(`/peminjaman/${id}`)
    return { success: true, data: response.data }
  },
  async update(id, data) {
    const response = await api.put(`/peminjaman/${id}`, data)
    return { success: true, data: response.data }
  },
  async delete(id) {
    await api.delete(`/peminjaman/${id}`)
    return { success: true }
  }
}

// Konsultasi Service
export const konsultasiService = {
  async getAll(params = {}) {
    const response = await api.get('/konsultasi', { params })
    return { success: true, data: response.data }
  },
  async getApproved() {
    const response = await api.get('/konsultasi/approved')
    return { success: true, data: response.data }
  },
  async submit(data) {
    const response = await api.post('/konsultasi', data)
    return { success: true, data: response.data }
  },
  async get(id) {
    const response = await api.get(`/konsultasi/${id}`)
    return { success: true, data: response.data }
  },
  async update(id, data) {
    const response = await api.put(`/konsultasi/${id}`, data)
    return { success: true, data: response.data }
  },
  async delete(id) {
    await api.delete(`/konsultasi/${id}`)
    return { success: true }
  }
}

// Infografis Service
export const infografisService = {
  async getAll(params = {}) {
    const response = await api.get('/infografis', { params })
    return { success: true, data: response.data }
  },
  async get(id) {
    const response = await api.get(`/infografis/${id}`)
    return { success: true, data: response.data }
  },
  async create(data) {
    const response = await api.post('/infografis', data)
    return { success: true, data: response.data }
  },
  async update(id, data) {
    const response = await api.put(`/infografis/${id}`, data)
    return { success: true, data: response.data }
  },
  async delete(id) {
    await api.delete(`/infografis/${id}`)
    return { success: true }
  }
}

// Materi Service
export const materiService = {
  async getAll(params = {}) {
    const response = await api.get('/materi', { params })
    return { success: true, data: response.data }
  },
  async get(id) {
    const response = await api.get(`/materi/${id}`)
    return { success: true, data: response.data }
  },
  async create(data) {
    const response = await api.post('/materi', data)
    return { success: true, data: response.data }
  },
  async update(id, data) {
    const response = await api.put(`/materi/${id}`, data)
    return { success: true, data: response.data }
  },
  async delete(id) {
    await api.delete(`/materi/${id}`)
    return { success: true }
  }
}

// Majalah Service
export const majalahService = {
  async getAll(params = {}) {
    const response = await api.get('/majalah', { params })
    return { success: true, data: response.data }
  },
  async get(id) {
    const response = await api.get(`/majalah/${id}`)
    return { success: true, data: response.data }
  },
  async create(data) {
    const response = await api.post('/majalah', data)
    return { success: true, data: response.data }
  },
  async update(id, data) {
    const response = await api.put(`/majalah/${id}`, data)
    return { success: true, data: response.data }
  },
  async delete(id) {
    await api.delete(`/majalah/${id}`)
    return { success: true }
  }
}

// Bunda TV Service
export const bundaTvService = {
  async getAll(params = {}) {
    const response = await api.get('/bunda-tv', { params })
    return { success: true, data: response.data }
  },
  async get(id) {
    const response = await api.get(`/bunda-tv/${id}`)
    return { success: true, data: response.data }
  },
  async create(data) {
    const response = await api.post('/bunda-tv', data)
    return { success: true, data: response.data }
  },
  async update(id, data) {
    const response = await api.put(`/bunda-tv/${id}`, data)
    return { success: true, data: response.data }
  },
  async delete(id) {
    await api.delete(`/bunda-tv/${id}`)
    return { success: true }
  }
}

// Peraturan Service
export const peraturanService = {
  async getAll(params = {}) {
    const response = await api.get('/peraturan', { params })
    return { success: true, data: response.data }
  },
  async get(id) {
    const response = await api.get(`/peraturan/${id}`)
    return { success: true, data: response.data }
  },
  async create(data) {
    const response = await api.post('/peraturan', data)
    return { success: true, data: response.data }
  },
  async update(id, data) {
    const response = await api.put(`/peraturan/${id}`, data)
    return { success: true, data: response.data }
  },
  async delete(id) {
    await api.delete(`/peraturan/${id}`)
    return { success: true }
  }
}

// NPK Service
export const npkService = {
  async getAll(params = {}) {
    const response = await api.get('/npk', { params })
    return { success: true, data: response.data }
  },
  async get(id) {
    const response = await api.get(`/npk/${id}`)
    return { success: true, data: response.data }
  },
  async create(data) {
    const response = await api.post('/npk', data)
    return { success: true, data: response.data }
  },
  async update(id, data) {
    const response = await api.put(`/npk/${id}`, data)
    return { success: true, data: response.data }
  },
  async delete(id) {
    await api.delete(`/npk/${id}`)
    return { success: true }
  }
}

// Laporan Service
export const laporanService = {
  async getAll(params = {}) {
    const response = await api.get('/laporan', { params })
    return { success: true, data: response.data }
  },
  async get(id) {
    const response = await api.get(`/laporan/${id}`)
    return { success: true, data: response.data }
  },
  async create(data) {
    const response = await api.post('/laporan', data)
    return { success: true, data: response.data }
  },
  async update(id, data) {
    const response = await api.put(`/laporan/${id}`, data)
    return { success: true, data: response.data }
  },
  async delete(id) {
    await api.delete(`/laporan/${id}`)
    return { success: true }
  }
}

// Mitra PAUD Service
export const mitraPaudService = {
  async getAll(params = {}) {
    const response = await api.get('/mitra-paud', { params })
    return { success: true, data: response.data }
  },
  async getApproved() {
    const response = await api.get('/mitra-paud/approved')
    return { success: true, data: response.data }
  },
  async submit(data) {
    const response = await api.post('/mitra-paud', data)
    return { success: true, data: response.data }
  },
  async get(id) {
    const response = await api.get(`/mitra-paud/${id}`)
    return { success: true, data: response.data }
  },
  async update(id, data) {
    const response = await api.put(`/mitra-paud/${id}`, data)
    return { success: true, data: response.data }
  },
  async delete(id) {
    await api.delete(`/mitra-paud/${id}`)
    return { success: true }
  }
}

// Gugus Tugas Service
export const gugusTugasService = {
  async getAll(params = {}) {
    const response = await api.get('/gugus-tugas', { params })
    return { success: true, data: response.data }
  },
  async get(id) {
    const response = await api.get(`/gugus-tugas/${id}`)
    return { success: true, data: response.data }
  },
  async create(data) {
    const response = await api.post('/gugus-tugas', data)
    return { success: true, data: response.data }
  },
  async update(id, data) {
    const response = await api.put(`/gugus-tugas/${id}`, data)
    return { success: true, data: response.data }
  },
  async delete(id) {
    await api.delete(`/gugus-tugas/${id}`)
    return { success: true }
  }
}

export {
  api,
  checkApiAvailable,
  uploadFile
}
