<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const loading = ref(true)
const showModal = ref(false)
const editingItem = ref(null)
const filterStatus = ref('')

const form = ref({
  name: '',
  description: '',
  target: '',
  start_date: '',
  end_date: '',
  progress: 0,
  status: 'pending'
})

// Mock data
const items = ref([
  { id: 1, name: 'Sosialisasi PAUD HI ke Masyarakat', description: 'Memberikan pemahaman tentang pentingnya PAUD Holistik Integratif', target: 'Orang tua di 5 kelurahan', start_date: '2026-01-01', end_date: '2026-03-31', progress: 45, status: 'active' },
  { id: 2, name: 'Pelatihan Guru PAUD', description: 'Meningkatkan kompetensi guru dalam penerapan kurikulum merdeka', target: '50 guru PAUD', start_date: '2026-02-01', end_date: '2026-02-28', progress: 0, status: 'pending' },
  { id: 3, name: 'Pendataan Anak Usia Dini', description: 'Mendata anak usia 0-6 tahun yang belum sekolah', target: '100% data anak', start_date: '2026-01-15', end_date: '2026-02-15', progress: 78, status: 'active' },
  { id: 4, name: 'Monitoring Lembaga PAUD', description: 'Melakukan kunjungan dan monitoring ke lembaga PAUD', target: '20 lembaga PAUD', start_date: '2026-01-01', end_date: '2026-12-31', progress: 15, status: 'active' },
  { id: 5, name: 'Vaksinasi Anak PAUD', description: 'Mendukung program vaksinasi anak usia dini', target: '500 anak', start_date: '2026-03-01', end_date: '2026-04-30', progress: 0, status: 'pending' }
])

const statusOptions = [
  { value: 'pending', label: 'Menunggu', class: 'bg-gray-100 text-gray-700' },
  { value: 'active', label: 'Berjalan', class: 'bg-blue-100 text-blue-700' },
  { value: 'completed', label: 'Selesai', class: 'bg-green-100 text-green-700' },
  { value: 'cancelled', label: 'Dibatalkan', class: 'bg-red-100 text-red-700' }
]

const filteredItems = computed(() => {
  if (!filterStatus.value) return items.value
  return items.value.filter(i => i.status === filterStatus.value)
})

const stats = computed(() => ({
  total: items.value.length,
  active: items.value.filter(i => i.status === 'active').length,
  completed: items.value.filter(i => i.status === 'completed').length,
  avgProgress: Math.round(items.value.reduce((sum, i) => sum + i.progress, 0) / items.value.length)
}))

const getStatusClass = (status) => statusOptions.find(s => s.value === status)?.class || ''
const getStatusLabel = (status) => statusOptions.find(s => s.value === status)?.label || status
const getProgressColor = (progress) => {
  if (progress >= 75) return 'bg-green-500'
  if (progress >= 50) return 'bg-blue-500'
  if (progress >= 25) return 'bg-yellow-500'
  return 'bg-gray-300'
}

const openModal = (item = null) => {
  if (item) {
    editingItem.value = item
    form.value = { ...item }
  } else {
    editingItem.value = null
    form.value = { name: '', description: '', target: '', start_date: '', end_date: '', progress: 0, status: 'pending' }
  }
  showModal.value = true
}

const saveItem = () => {
  if (editingItem.value) {
    const idx = items.value.findIndex(i => i.id === editingItem.value.id)
    if (idx > -1) items.value[idx] = { ...form.value, id: editingItem.value.id }
  } else {
    items.value.push({ ...form.value, id: Date.now() })
  }
  showModal.value = false
}

const deleteItem = (id) => {
  if (confirm('Yakin ingin menghapus program kerja ini?')) {
    items.value = items.value.filter(i => i.id !== id)
  }
}

onMounted(() => {
  setTimeout(() => { loading.value = false }, 500)
})
</script>

<template>
  <AdminLayout title="Program Kerja Bunda PAUD">
    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Total Program</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Sedang Berjalan</p>
        <p class="text-2xl font-bold text-blue-600">{{ stats.active }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Selesai</p>
        <p class="text-2xl font-bold text-green-600">{{ stats.completed }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Rata-rata Progress</p>
        <p class="text-2xl font-bold text-primary-600">{{ stats.avgProgress }}%</p>
      </div>
    </div>

    <!-- Filters -->
    <div class="flex justify-between items-center mb-6">
      <div class="flex gap-2">
        <button @click="filterStatus = ''" class="px-4 py-2 rounded-lg text-sm font-medium" :class="!filterStatus ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600'">
          Semua
        </button>
        <button v-for="s in statusOptions" :key="s.value" @click="filterStatus = s.value" class="px-4 py-2 rounded-lg text-sm font-medium" :class="filterStatus === s.value ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600'">
          {{ s.label }}
        </button>
      </div>
      <button @click="openModal()" class="btn-primary flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Program
      </button>
    </div>

    <!-- Programs List -->
    <div v-if="loading" class="text-center py-12">
      <div class="w-10 h-10 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin mx-auto"></div>
    </div>

    <div v-else class="space-y-4">
      <div v-for="item in filteredItems" :key="item.id" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <div class="flex justify-between items-start">
          <div class="flex-1">
            <div class="flex items-center gap-3 mb-2">
              <h3 class="font-semibold text-gray-900 dark:text-white">{{ item.name }}</h3>
              <span class="px-2 py-0.5 rounded-full text-xs font-medium" :class="getStatusClass(item.status)">
                {{ getStatusLabel(item.status) }}
              </span>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">{{ item.description }}</p>
            <div class="flex flex-wrap gap-4 text-sm text-gray-500">
              <span>🎯 Target: {{ item.target }}</span>
              <span>📅 {{ item.start_date }} - {{ item.end_date }}</span>
            </div>
          </div>
          <div class="flex gap-2 ml-4">
            <button @click="openModal(item)" class="p-2 text-gray-500 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
            </button>
            <button @click="deleteItem(item.id)" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </div>
        </div>
        
        <!-- Progress Bar -->
        <div class="mt-4">
          <div class="flex justify-between text-sm mb-1">
            <span class="text-gray-500">Progress</span>
            <span class="font-medium" :class="item.progress >= 75 ? 'text-green-600' : 'text-gray-600'">{{ item.progress }}%</span>
          </div>
          <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-all" :class="getProgressColor(item.progress)" :style="{ width: item.progress + '%' }"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/50" @click="showModal = false"></div>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-lg mx-4 p-6 max-h-[90vh] overflow-y-auto">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
          {{ editingItem ? 'Edit Program Kerja' : 'Tambah Program Kerja' }}
        </h2>
        
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Program</label>
            <input v-model="form.name" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
            <textarea v-model="form.description" rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target</label>
            <input v-model="form.target" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="50 guru PAUD" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mulai</label>
              <input v-model="form.start_date" type="date" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Selesai</label>
              <input v-model="form.end_date" type="date" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Progress (%)</label>
              <input v-model.number="form.progress" type="number" min="0" max="100" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
              <select v-model="form.status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option v-for="s in statusOptions" :key="s.value" :value="s.value">{{ s.label }}</option>
              </select>
            </div>
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
