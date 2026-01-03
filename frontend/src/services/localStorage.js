/**
 * LocalStorage-based data management for Bunda PAUD website
 * Semua data disimpan di localStorage browser
 */

// Storage keys
const KEYS = {
  SETTINGS: 'bp_settings',
  ARTICLES: 'bp_articles',
  GALLERIES: 'bp_galleries',
  DOCUMENTS: 'bp_documents',
  SLIDERS: 'bp_sliders',
  COMPLAINTS: 'bp_complaints',
  BUNDA_PAUD: 'bp_bunda_paud',
  AUTH: 'bp_auth',
  PROFILES: 'bp_profiles',
  CATEGORIES: 'bp_categories',
  PEMINJAMAN: 'bp_peminjaman',
  KONSULTASI: 'bp_konsultasi'
}

// Helper functions
const generateId = () => Date.now().toString(36) + Math.random().toString(36).substr(2)
const generateSlug = (title) => title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '')

// Get data from localStorage or return default
const getData = (key, defaultData = []) => {
  try {
    const data = localStorage.getItem(key)
    return data ? JSON.parse(data) : defaultData
  } catch {
    return defaultData
  }
}

// Save data to localStorage
const saveData = (key, data) => {
  localStorage.setItem(key, JSON.stringify(data))
}

// ============================================
// DEFAULT DATA
// ============================================

const DEFAULT_SETTINGS = {
  siteName: 'Bunda PAUD Kota Surabaya',
  email: 'bundapaud@surabaya.go.id',
  phone: '(031) 5320-1508',
  address: 'Jl. Jimerto No.25-27, Ketabang, Kec. Genteng, Kota Surabaya, Jawa Timur 60272',
  whatsappNumber: '6281234567890',
  whatsappMessage: 'Halo, saya ingin bertanya tentang program Bunda PAUD Kota Surabaya',
  hours: 'Senin - Jumat: 08.00 - 16.00 WIB',
  googleMapsUrl: 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.634897788675!2d112.73694!3d-7.2875897!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fbf4a123456%3A0x1234567890abcdef!2sGrha%20Bunda%20PAUD!5e0!3m2!1sid!2sid!4v1234567890'
}

const DEFAULT_SLIDERS = [
  {
    id: '1',
    title: 'Selamat Datang di Bunda PAUD Kota Surabaya',
    subtitle: 'Membangun Generasi Emas Indonesia melalui Pendidikan Anak Usia Dini yang Berkualitas',
    image: 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=1920&h=600&fit=crop',
    buttonText: 'Pelajari Lebih Lanjut',
    buttonLink: '/profil/tentang',
    isActive: true,
    order: 1
  },
  {
    id: '2',
    title: 'PAUD Holistik Integratif',
    subtitle: 'Layanan pendidikan, kesehatan, gizi, pengasuhan, dan perlindungan anak secara menyeluruh',
    image: 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=1920&h=600&fit=crop',
    buttonText: 'Lihat Program',
    buttonLink: '/layanan/program-prioritas',
    isActive: true,
    order: 2
  },
  {
    id: '3',
    title: 'Gerakan 7 KAIH',
    subtitle: '7 Kebaikan untuk Anak Indonesia Hebat - Mari bergabung dalam gerakan nasional untuk anak-anak kita',
    image: 'https://images.unsplash.com/photo-1544776193-352d25ca82cd?w=1920&h=600&fit=crop',
    buttonText: 'Ikuti Gerakan',
    buttonLink: '/data/gerakan-7-kaih',
    isActive: true,
    order: 3
  }
]

const DEFAULT_ARTICLES = [
  {
    id: '1',
    title: 'Workshop Peningkatan Kompetensi Guru PAUD 2025',
    slug: 'workshop-peningkatan-kompetensi-guru-paud-2025',
    excerpt: 'Bunda PAUD Kota Surabaya mengadakan workshop untuk meningkatkan kompetensi guru PAUD dalam menghadapi tantangan pendidikan modern.',
    content: '<p>Workshop Peningkatan Kompetensi Guru PAUD 2025 telah sukses diselenggarakan di Grha Bunda PAUD Kota Surabaya.</p><p>Kegiatan ini diikuti oleh lebih dari 200 guru PAUD dari seluruh kecamatan di Surabaya.</p>',
    image: 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&h=400&fit=crop',
    category: 'berita',
    author: 'Admin',
    publishedAt: '2025-12-28',
    isPublished: true
  },
  {
    id: '2',
    title: 'Sosialisasi Program PAUD Holistik Integratif',
    slug: 'sosialisasi-program-paud-holistik-integratif',
    excerpt: 'Program PAUD HI terus digalakkan untuk memastikan tumbuh kembang anak secara optimal.',
    content: '<p>Sosialisasi Program PAUD Holistik Integratif dilaksanakan untuk meningkatkan pemahaman masyarakat tentang pentingnya pendidikan anak usia dini yang menyeluruh.</p>',
    image: 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=800&h=400&fit=crop',
    category: 'pengumuman',
    author: 'Admin',
    publishedAt: '2025-12-25',
    isPublished: true
  },
  {
    id: '3',
    title: 'Penerimaan Peserta Didik Baru PAUD 2025/2026',
    slug: 'penerimaan-peserta-didik-baru-paud-2025-2026',
    excerpt: 'Pendaftaran PPDB PAUD tahun ajaran 2025/2026 akan segera dibuka. Simak informasi lengkapnya di sini.',
    content: '<p>Pemerintah Kota Surabaya melalui Dinas Pendidikan membuka pendaftaran peserta didik baru untuk jenjang PAUD tahun ajaran 2025/2026.</p>',
    image: 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&h=400&fit=crop',
    category: 'pengumuman',
    author: 'Admin',
    publishedAt: '2025-12-20',
    isPublished: true
  }
]

const DEFAULT_GALLERIES = [
  {
    id: '1',
    title: 'Peringatan Hari Anak Nasional 2025',
    description: 'Dokumentasi kegiatan peringatan Hari Anak Nasional di Surabaya',
    image: 'https://images.unsplash.com/photo-1544776193-352d25ca82cd?w=600&h=400&fit=crop',
    category: 'kegiatan',
    createdAt: '2025-07-23'
  },
  {
    id: '2',
    title: 'Festival PAUD Kreatif',
    description: 'Kreativitas anak-anak PAUD Surabaya dalam Festival PAUD Kreatif',
    image: 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=600&h=400&fit=crop',
    category: 'kegiatan',
    createdAt: '2025-06-15'
  },
  {
    id: '3',
    title: 'Kunjungan Kerja Kemendikbud',
    description: 'Kunjungan kerja pejabat Kemendikbud ke Grha Bunda PAUD Surabaya',
    image: 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=600&h=400&fit=crop',
    category: 'kunjungan',
    createdAt: '2025-05-10'
  }
]

const DEFAULT_DOCUMENTS = [
  {
    id: '1',
    title: 'Panduan PAUD Holistik Integratif 2025',
    description: 'Buku panduan pelaksanaan PAUD Holistik Integratif',
    category: 'panduan',
    fileUrl: '#',
    fileSize: '2.5 MB',
    fileType: 'PDF',
    createdAt: '2025-01-15'
  },
  {
    id: '2',
    title: 'Peraturan Walikota tentang PAUD',
    description: 'Peraturan Walikota Surabaya tentang penyelenggaraan PAUD',
    category: 'peraturan',
    fileUrl: '#',
    fileSize: '1.2 MB',
    fileType: 'PDF',
    createdAt: '2024-12-01'
  }
]

const DEFAULT_COMPLAINTS = []

const DEFAULT_CATEGORIES = [
  { id: 'berita', name: 'Berita', color: 'teal', order: 1 },
  { id: 'kegiatan', name: 'Kegiatan', color: 'blue', order: 2 },
  { id: 'pengumuman', name: 'Pengumuman', color: 'amber', order: 3 },
  { id: 'edukasi', name: 'Edukasi', color: 'green', order: 4 }
]

const DEFAULT_STATISTICS = {
  total_paud: 850,
  total_guru: 3200,
  total_siswa: 45000,
  total_kecamatan: 31,
  stunting_rate: '12%',
  mbg_penerima: 35000
}

// ============================================
// INITIALIZE DEFAULT DATA
// ============================================

export const initializeData = () => {
  // Only initialize if not exists
  if (!localStorage.getItem(KEYS.SETTINGS)) {
    saveData(KEYS.SETTINGS, DEFAULT_SETTINGS)
  }
  if (!localStorage.getItem(KEYS.SLIDERS)) {
    saveData(KEYS.SLIDERS, DEFAULT_SLIDERS)
  }
  if (!localStorage.getItem(KEYS.ARTICLES)) {
    saveData(KEYS.ARTICLES, DEFAULT_ARTICLES)
  }
  if (!localStorage.getItem(KEYS.GALLERIES)) {
    saveData(KEYS.GALLERIES, DEFAULT_GALLERIES)
  }
  if (!localStorage.getItem(KEYS.DOCUMENTS)) {
    saveData(KEYS.DOCUMENTS, DEFAULT_DOCUMENTS)
  }
  if (!localStorage.getItem(KEYS.COMPLAINTS)) {
    saveData(KEYS.COMPLAINTS, DEFAULT_COMPLAINTS)
  }
  if (!localStorage.getItem(KEYS.CATEGORIES)) {
    saveData(KEYS.CATEGORIES, DEFAULT_CATEGORIES)
  }
}

// ============================================
// SETTINGS SERVICE
// ============================================

export const settingsStorage = {
  get() {
    return getData(KEYS.SETTINGS, DEFAULT_SETTINGS)
  },
  
  update(newSettings) {
    const current = this.get()
    const updated = { ...current, ...newSettings }
    saveData(KEYS.SETTINGS, updated)
    return updated
  }
}

// ============================================
// SLIDERS SERVICE
// ============================================

export const slidersStorage = {
  getAll() {
    return getData(KEYS.SLIDERS, DEFAULT_SLIDERS)
  },
  
  getActive() {
    return this.getAll().filter(s => s.isActive).sort((a, b) => a.order - b.order)
  },
  
  get(id) {
    return this.getAll().find(s => s.id === id)
  },
  
  create(data) {
    const sliders = this.getAll()
    const newSlider = {
      ...data,
      id: generateId(),
      order: sliders.length + 1,
      isActive: data.isActive ?? true
    }
    sliders.push(newSlider)
    saveData(KEYS.SLIDERS, sliders)
    return newSlider
  },
  
  update(id, data) {
    const sliders = this.getAll()
    const index = sliders.findIndex(s => s.id === id)
    if (index !== -1) {
      sliders[index] = { ...sliders[index], ...data }
      saveData(KEYS.SLIDERS, sliders)
      return sliders[index]
    }
    return null
  },
  
  delete(id) {
    const sliders = this.getAll().filter(s => s.id !== id)
    saveData(KEYS.SLIDERS, sliders)
    return true
  },
  
  toggleActive(id) {
    const sliders = this.getAll()
    const index = sliders.findIndex(s => s.id === id)
    if (index !== -1) {
      sliders[index].isActive = !sliders[index].isActive
      saveData(KEYS.SLIDERS, sliders)
      return sliders[index]
    }
    return null
  }
}

// ============================================
// ARTICLES SERVICE
// ============================================

export const articlesStorage = {
  getAll(params = {}) {
    let articles = getData(KEYS.ARTICLES, DEFAULT_ARTICLES)
    
    // Filter published only for public
    if (params.published) {
      articles = articles.filter(a => a.isPublished)
    }
    
    // Filter by category
    if (params.category) {
      articles = articles.filter(a => a.category === params.category)
    }
    
    // Search
    if (params.search) {
      const search = params.search.toLowerCase()
      articles = articles.filter(a => 
        a.title.toLowerCase().includes(search) || 
        a.excerpt.toLowerCase().includes(search)
      )
    }
    
    // Sort by date (newest first)
    articles.sort((a, b) => new Date(b.publishedAt) - new Date(a.publishedAt))
    
    return articles
  },
  
  getBySlug(slug) {
    return this.getAll().find(a => a.slug === slug)
  },
  
  get(id) {
    return this.getAll().find(a => a.id === id)
  },
  
  create(data) {
    const articles = getData(KEYS.ARTICLES, [])
    const newArticle = {
      ...data,
      id: generateId(),
      slug: generateSlug(data.title),
      publishedAt: data.publishedAt || new Date().toISOString().split('T')[0],
      isPublished: data.isPublished ?? true
    }
    articles.push(newArticle)
    saveData(KEYS.ARTICLES, articles)
    return newArticle
  },
  
  update(id, data) {
    const articles = getData(KEYS.ARTICLES, [])
    const index = articles.findIndex(a => a.id === id)
    if (index !== -1) {
      articles[index] = { 
        ...articles[index], 
        ...data,
        slug: data.title ? generateSlug(data.title) : articles[index].slug
      }
      saveData(KEYS.ARTICLES, articles)
      return articles[index]
    }
    return null
  },
  
  delete(id) {
    const articles = getData(KEYS.ARTICLES, []).filter(a => a.id !== id)
    saveData(KEYS.ARTICLES, articles)
    return true
  }
}

// ============================================
// GALLERIES SERVICE
// ============================================

export const galleriesStorage = {
  getAll(params = {}) {
    let galleries = getData(KEYS.GALLERIES, DEFAULT_GALLERIES)
    
    if (params.category) {
      galleries = galleries.filter(g => g.category === params.category)
    }
    
    return galleries
  },
  
  get(id) {
    return this.getAll().find(g => g.id === id)
  },
  
  create(data) {
    const galleries = getData(KEYS.GALLERIES, [])
    const newGallery = {
      ...data,
      id: generateId(),
      createdAt: new Date().toISOString().split('T')[0]
    }
    galleries.push(newGallery)
    saveData(KEYS.GALLERIES, galleries)
    return newGallery
  },
  
  update(id, data) {
    const galleries = getData(KEYS.GALLERIES, [])
    const index = galleries.findIndex(g => g.id === id)
    if (index !== -1) {
      galleries[index] = { ...galleries[index], ...data }
      saveData(KEYS.GALLERIES, galleries)
      return galleries[index]
    }
    return null
  },
  
  delete(id) {
    const galleries = getData(KEYS.GALLERIES, []).filter(g => g.id !== id)
    saveData(KEYS.GALLERIES, galleries)
    return true
  }
}

// ============================================
// DOCUMENTS SERVICE
// ============================================

export const documentsStorage = {
  getAll(params = {}) {
    let documents = getData(KEYS.DOCUMENTS, DEFAULT_DOCUMENTS)
    
    if (params.category) {
      documents = documents.filter(d => d.category === params.category)
    }
    
    return documents
  },
  
  get(id) {
    return this.getAll().find(d => d.id === id)
  },
  
  create(data) {
    const documents = getData(KEYS.DOCUMENTS, [])
    const newDocument = {
      ...data,
      id: generateId(),
      createdAt: new Date().toISOString().split('T')[0]
    }
    documents.push(newDocument)
    saveData(KEYS.DOCUMENTS, documents)
    return newDocument
  },
  
  update(id, data) {
    const documents = getData(KEYS.DOCUMENTS, [])
    const index = documents.findIndex(d => d.id === id)
    if (index !== -1) {
      documents[index] = { ...documents[index], ...data }
      saveData(KEYS.DOCUMENTS, documents)
      return documents[index]
    }
    return null
  },
  
  delete(id) {
    const documents = getData(KEYS.DOCUMENTS, []).filter(d => d.id !== id)
    saveData(KEYS.DOCUMENTS, documents)
    return true
  }
}

// ============================================
// COMPLAINTS SERVICE
// ============================================

export const complaintsStorage = {
  getAll() {
    return getData(KEYS.COMPLAINTS, [])
  },
  
  get(id) {
    return this.getAll().find(c => c.id === id)
  },
  
  create(data) {
    const complaints = getData(KEYS.COMPLAINTS, [])
    const newComplaint = {
      ...data,
      id: generateId(),
      status: 'pending',
      createdAt: new Date().toISOString()
    }
    complaints.push(newComplaint)
    saveData(KEYS.COMPLAINTS, complaints)
    return newComplaint
  },
  
  update(id, data) {
    const complaints = getData(KEYS.COMPLAINTS, [])
    const index = complaints.findIndex(c => c.id === id)
    if (index !== -1) {
      complaints[index] = { ...complaints[index], ...data }
      saveData(KEYS.COMPLAINTS, complaints)
      return complaints[index]
    }
    return null
  }
}

// ============================================
// AUTH SERVICE (Demo Mode)
// ============================================

export const authStorage = {
  login(email, password) {
    // Demo login - accept any credentials
    if (email && password) {
      const user = {
        id: '1',
        name: 'Admin',
        email: email,
        role: 'admin'
      }
      saveData(KEYS.AUTH, { user, isLoggedIn: true })
      localStorage.setItem('demo_user', JSON.stringify(user))
      return { success: true, user }
    }
    return { success: false, message: 'Email dan password harus diisi' }
  },
  
  logout() {
    localStorage.removeItem(KEYS.AUTH)
    localStorage.removeItem('demo_user')
    localStorage.removeItem('auth_token')
    return { success: true }
  },
  
  getUser() {
    const auth = getData(KEYS.AUTH, { user: null, isLoggedIn: false })
    return auth.user
  },
  
  isLoggedIn() {
    const auth = getData(KEYS.AUTH, { isLoggedIn: false })
    return auth.isLoggedIn || !!localStorage.getItem('demo_user')
  }
}

// ============================================
// STATISTICS SERVICE
// ============================================

export const statisticsStorage = {
  get() {
    return DEFAULT_STATISTICS
  }
}

// ============================================
// PROFILES SERVICE
// ============================================

export const profilesStorage = {
  getAll() {
    return getData(KEYS.PROFILES, {})
  },
  
  get(key) {
    const profiles = this.getAll()
    return profiles[key] || null
  },
  
  update(key, data) {
    const profiles = this.getAll()
    profiles[key] = {
      ...profiles[key],
      ...data,
      updatedAt: new Date().toISOString()
    }
    saveData(KEYS.PROFILES, profiles)
    return profiles[key]
  }
}

// ============================================
// CATEGORIES SERVICE
// ============================================

export const categoriesStorage = {
  getAll() {
    return getData(KEYS.CATEGORIES, DEFAULT_CATEGORIES).sort((a, b) => a.order - b.order)
  },
  
  get(id) {
    return this.getAll().find(c => c.id === id)
  },
  
  create(data) {
    const categories = getData(KEYS.CATEGORIES, [])
    const id = data.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '')
    const newCategory = {
      id,
      name: data.name,
      color: data.color || 'gray',
      order: categories.length + 1
    }
    categories.push(newCategory)
    saveData(KEYS.CATEGORIES, categories)
    return newCategory
  },
  
  update(id, data) {
    const categories = getData(KEYS.CATEGORIES, [])
    const index = categories.findIndex(c => c.id === id)
    if (index !== -1) {
      categories[index] = { ...categories[index], ...data }
      saveData(KEYS.CATEGORIES, categories)
      return categories[index]
    }
    return null
  },
  
  delete(id) {
    const categories = getData(KEYS.CATEGORIES, []).filter(c => c.id !== id)
    saveData(KEYS.CATEGORIES, categories)
    return true
  }
}

// ============================================
// PEMINJAMAN SERVICE
// ============================================

export const peminjamanStorage = {
  getAll(params = {}) {
    let data = getData(KEYS.PEMINJAMAN, [])
    
    // Filter by status
    if (params.status) {
      data = data.filter(p => p.status === params.status)
    }
    
    // Sort by date (newest first)
    data.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt))
    
    return data
  },
  
  get(id) {
    return this.getAll().find(p => p.id === id)
  },
  
  // Find by email or phone (for status check)
  findByContact(email, telepon) {
    const all = this.getAll()
    return all.filter(p => 
      (email && p.email.toLowerCase() === email.toLowerCase()) ||
      (telepon && p.telepon === telepon)
    )
  },
  
  // Get approved bookings for calendar
  getApproved() {
    return this.getAll({ status: 'approved' })
  },
  
  create(data) {
    const all = getData(KEYS.PEMINJAMAN, [])
    const newPeminjaman = {
      ...data,
      id: generateId(),
      status: 'pending',
      createdAt: new Date().toISOString(),
      updatedAt: new Date().toISOString()
    }
    all.push(newPeminjaman)
    saveData(KEYS.PEMINJAMAN, all)
    return newPeminjaman
  },
  
  update(id, data) {
    const all = getData(KEYS.PEMINJAMAN, [])
    const index = all.findIndex(p => p.id === id)
    if (index !== -1) {
      all[index] = { 
        ...all[index], 
        ...data,
        updatedAt: new Date().toISOString()
      }
      saveData(KEYS.PEMINJAMAN, all)
      return all[index]
    }
    return null
  },
  
  updateStatus(id, status, catatan = '') {
    return this.update(id, { status, catatan })
  },
  
  delete(id) {
    const all = getData(KEYS.PEMINJAMAN, []).filter(p => p.id !== id)
    saveData(KEYS.PEMINJAMAN, all)
    return true
  }
}

// ============================================
// KONSULTASI SERVICE
// ============================================

export const konsultasiStorage = {
  getAll(params = {}) {
    let data = getData(KEYS.KONSULTASI, [])
    if (params.status) {
      data = data.filter(k => k.status === params.status)
    }
    data.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt))
    return data
  },
  
  get(id) {
    return this.getAll().find(k => k.id === id)
  },
  
  findByContact(email, telepon) {
    const all = this.getAll()
    return all.filter(k => 
      (email && k.email.toLowerCase() === email.toLowerCase()) ||
      (telepon && k.telepon === telepon)
    )
  },
  
  getApproved() {
    return this.getAll({ status: 'approved' })
  },
  
  create(data) {
    const all = getData(KEYS.KONSULTASI, [])
    const newKonsultasi = {
      ...data,
      id: generateId(),
      status: 'pending',
      createdAt: new Date().toISOString(),
      updatedAt: new Date().toISOString()
    }
    all.push(newKonsultasi)
    saveData(KEYS.KONSULTASI, all)
    return newKonsultasi
  },
  
  update(id, data) {
    const all = getData(KEYS.KONSULTASI, [])
    const index = all.findIndex(k => k.id === id)
    if (index !== -1) {
      all[index] = { ...all[index], ...data, updatedAt: new Date().toISOString() }
      saveData(KEYS.KONSULTASI, all)
      return all[index]
    }
    return null
  },
  
  updateStatus(id, status, catatan = '') {
    return this.update(id, { status, catatan })
  },
  
  delete(id) {
    const all = getData(KEYS.KONSULTASI, []).filter(k => k.id !== id)
    saveData(KEYS.KONSULTASI, all)
    return true
  }
}

export default {
  initializeData,
  settings: settingsStorage,
  sliders: slidersStorage,
  articles: articlesStorage,
  galleries: galleriesStorage,
  documents: documentsStorage,
  complaints: complaintsStorage,
  auth: authStorage,
  statistics: statisticsStorage,
  profiles: profilesStorage,
  categories: categoriesStorage,
  peminjaman: peminjamanStorage,
  konsultasi: konsultasiStorage
}

