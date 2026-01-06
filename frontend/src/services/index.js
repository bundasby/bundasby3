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
  profilesStorage
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
    const result = authStorage.login(credentials.email, credentials.password)
    if (result.success) {
      localStorage.setItem('auth_token', 'demo_token_' + Date.now())
      return { success: true, data: { user: result.user, token: 'demo_token' } }
    }
    return { success: false, message: result.message }
  },

  async getUser() {
    const user = authStorage.getUser()
    return { success: true, data: user }
  },

  async logout() {
    authStorage.logout()
    return { success: true }
  },

  isLoggedIn() {
    return authStorage.isLoggedIn()
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

export {
  api,
  checkApiAvailable,
  uploadFile
}
