<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

// State
const activities = ref([])
const loading = ref(true)
const showModal = ref(false)
const editingItem = ref(null)
const searchQuery = ref('')
const filterStatus = ref('')
const filterCategory = ref('')

// Form data
const form = ref({
  title: '',
  description: '',
  date: '',
  time: '',
  location: '',
  category: '',
  status: 'upcoming',
  participants: 0,
  notes: ''
})

// Mock data
const mockActivities = [
  { id: 1, title: 'Rapat Koordinasi Bulanan', description: 'Koordinasi program Bunda PAUD tingkat kota', date: '2026-01-15', time: '09:00', location: 'Grha Bunda PAUD', category: 'rapat', status: 'completed', participants: 45 },
  { id: 2, title: 'Pelatihan Guru PAUD', description: 'Pelatihan kompetensi pedagogik', date: '2026-01-20', time: '08:00', location: 'Gedung Dinas Pendidikan', category: 'pelatihan', status: 'active', participants: 120 },
  { id: 3, title: 'Kunjungan PAUD Percontohan', description: 'Studi banding ke PAUD unggulan', date: '2026-01-25', time: '10:00', location: 'TK Negeri Pembina', category: 'kunjungan', status: 'upcoming', participants: 30 },
  { id: 4, title: 'Sosialisasi Kurikulum Merdeka', description: 'Pengenalan kurikulum merdeka untuk PAUD', date: '2026-02-01', time: '13:00', location: 'Online via Zoom', category: 'sosialisasi', status: 'upcoming', participants: 200 },
  { id: 5, title: 'Lomba Kreativitas Anak PAUD', description: 'Lomba mewarnai dan menari tingkat kota', date: '2026-02-14', time: '08:00', location: 'Balai Pemuda Surabaya', category: 'lomba', status: 'upcoming', participants: 500 }
]

const categories = [
  { value: 'rapat', label: 'Rapat', icon: '📋' },
  { value: 'pelatihan', label: 'Pelatihan', icon: '📚' },
  { value: 'kunjungan', label: 'Kunjungan', icon: '🚌' },
  { value: 'sosialisasi', label: 'Sosialisasi', icon: '📢' },
  { value: 'lomba', label: 'Lomba', icon: '🏆' },
  { value: 'lainnya', label: 'Lainnya', icon: '📌' }
]

const statusOptions = [
  { value: 'upcoming', label: 'Akan Datang', class: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' },
  { value: 'active', label: 'Sedang Berjalan', class: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' },
  { value: 'completed', label: 'Selesai', class: 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' },
  { value: 'cancelled', label: 'Dibatalkan', class: 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }
]

const filteredActivities = computed(() => {
  return activities.value.filter(a => {
    const matchSearch = a.title.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
                        a.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchStatus = !filterStatus.value || a.status === filterStatus.value
    const matchCategory = !filterCategory.value || a.category === filterCategory.value
    return matchSearch && matchStatus && matchCategory
  })
})

const getCategoryIcon = (cat) => categories.find(c => c.value === cat)?.icon || '📌'
const getCategoryLabel = (cat) => categories.find(c => c.value === cat)?.label || cat
const getStatusClass = (status) => statusOptions.find(s => s.value === status)?.class || ''
const getStatusLabel = (status) => statusOptions.find(s => s.value === status)?.label || status

const openModal = (item = null) => {
  if (item) {
    editingItem.value = item
    form.value = { ...item }
  } else {
    editingItem.value = null
    form.value = { title: '', description: '', date: '', time: '', location: '', category: '', status: 'upcoming', participants: 0, notes: '' }
  }
  showModal.value = true
}

const saveItem = () => {
  if (editingItem.value) {
    const idx = activities.value.findIndex(a => a.id === editingItem.value.id)
    if (idx > -1) activities.value[idx] = { ...form.value, id: editingItem.value.id }
  } else {
    activities.value.push({ ...form.value, id: Date.now() })
  }
  showModal.value = false
}

const deleteItem = (id) => {
  if (confirm('Yakin ingin menghapus kegiatan ini?')) {
    activities.value = activities.value.filter(a => a.id !== id)
  }
}

onMounted(() => {
  setTimeout(() => {
    activities.value = mockActivities
    loading.value = false
  }, 500)
})
</script>

<template>
  <AdminLayout title="Kelola Kegiatan">
    <!-- Header & Filters -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
      <div class="flex flex-wrap gap-3">
        <div class="relative">
          <input v-model="searchQuery" type="text" placeholder="Cari kegiatan..." class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white w-64" />
          <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <select v-model="filterStatus" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
          <option value="">Semua Status</option>
          <option v-for="s in statusOptions" :key="s.value" :value="s.value">{{ s.label }}</option>
        </select>
        <select v-model="filterCategory" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
          <option value="">Semua Kategori</option>
          <option v-for="c in categories" :key="c.value" :value="c.value">{{ c.icon }} {{ c.label }}</option>
        </select>
      </div>
      <button @click="openModal()" class="btn-primary flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Kegiatan
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Total Kegiatan</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ activities.length }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Akan Datang</p>
        <p class="text-2xl font-bold text-blue-600">{{ activities.filter(a => a.status === 'upcoming').length }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Sedang Berjalan</p>
        <p class="text-2xl font-bold text-green-600">{{ activities.filter(a => a.status === 'active').length }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Selesai</p>
        <p class="text-2xl font-bold text-gray-600">{{ activities.filter(a => a.status === 'completed').length }}</p>
      </div>
    </div>

    <!-- Activities Grid -->
    <div v-if="loading" class="text-center py-12">
      <div class="w-10 h-10 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin mx-auto"></div>
    </div>

    <div v-else class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div v-for="activity in filteredActivities" :key="activity.id" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-shadow">
        <div class="p-4">
          <div class="flex items-start justify-between mb-3">
            <span class="text-2xl">{{ getCategoryIcon(activity.category) }}</span>
            <span class="px-2 py-1 rounded-full text-xs font-medium" :class="getStatusClass(activity.status)">
              {{ getStatusLabel(activity.status) }}
            </span>
          </div>
          <h3 class="font-semibold text-gray-900 dark:text-white mb-2">{{ activity.title }}</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-3">{{ activity.description }}</p>
          <div class="space-y-1 text-sm text-gray-500">
            <p>📅 {{ activity.date }} • {{ activity.time }}</p>
            <p>📍 {{ activity.location }}</p>
            <p>👥 {{ activity.participants }} peserta</p>
          </div>
        </div>
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 flex justify-end gap-2">
          <button @click="openModal(activity)" class="px-3 py-1.5 text-sm text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg">
            Edit
          </button>
          <button @click="deleteItem(activity.id)" class="px-3 py-1.5 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg">
            Hapus
          </button>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/50" @click="showModal = false"></div>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-lg mx-4 p-6 max-h-[90vh] overflow-y-auto">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
          {{ editingItem ? 'Edit Kegiatan' : 'Tambah Kegiatan Baru' }}
        </h2>
        
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Kegiatan</label>
            <input v-model="form.title" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
            <textarea v-model="form.description" rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
              <input v-model="form.date" type="date" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Waktu</label>
              <input v-model="form.time" type="time" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lokasi</label>
            <input v-model="form.location" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
              <select v-model="form.category" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option v-for="c in categories" :key="c.value" :value="c.value">{{ c.icon }} {{ c.label }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
              <select v-model="form.status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option v-for="s in statusOptions" :key="s.value" :value="s.value">{{ s.label }}</option>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah Peserta</label>
            <input v-model.number="form.participants" type="number" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
          </div>
        </div>
        
        <div class="flex gap-3 mt-6">
          <button @click="showModal = false" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Batal</button>
          <button @click="saveItem" class="flex-1 btn-primary">Simpan</button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
