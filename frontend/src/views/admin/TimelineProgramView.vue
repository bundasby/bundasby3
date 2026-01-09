<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

// State
const programs = ref([])
const loading = ref(true)
const showModal = ref(false)
const editingItem = ref(null)

// Form data
const form = ref({
  name: '',
  description: '',
  start_date: '',
  end_date: '',
  status: 'upcoming',
  color: '#3b82f6'
})

// Mock data
const mockPrograms = [
  { id: 1, name: 'Pendataan PAUD HI', description: 'Pendataan seluruh lembaga PAUD di Surabaya', start_date: '2026-01-01', end_date: '2026-02-28', status: 'active', color: '#3b82f6', progress: 45 },
  { id: 2, name: 'Pelatihan Guru PAUD', description: 'Pelatihan kompetensi guru PAUD', start_date: '2026-03-01', end_date: '2026-04-30', status: 'upcoming', color: '#10b981', progress: 0 },
  { id: 3, name: 'Lomba Apresiasi Bunda PAUD', description: 'Lomba tingkat kota untuk Bunda PAUD', start_date: '2026-05-01', end_date: '2026-06-30', status: 'upcoming', color: '#f59e0b', progress: 0 },
  { id: 4, name: 'Monitoring PAUD Negeri', description: 'Monitoring berkala PAUD Negeri Bung Karno', start_date: '2026-01-01', end_date: '2026-12-31', status: 'active', color: '#8b5cf6', progress: 20 },
  { id: 5, name: 'Vaksinasi Anak PAUD', description: 'Program vaksinasi rutin anak PAUD', start_date: '2026-02-01', end_date: '2026-03-31', status: 'active', color: '#ef4444', progress: 60 }
]

const statusOptions = [
  { value: 'upcoming', label: 'Akan Datang', color: 'bg-gray-100 text-gray-700' },
  { value: 'active', label: 'Sedang Berjalan', color: 'bg-green-100 text-green-700' },
  { value: 'completed', label: 'Selesai', color: 'bg-blue-100 text-blue-700' },
  { value: 'cancelled', label: 'Dibatalkan', color: 'bg-red-100 text-red-700' }
]

const getStatusClass = (status) => {
  return statusOptions.find(s => s.value === status)?.color || 'bg-gray-100 text-gray-700'
}

const getStatusLabel = (status) => {
  return statusOptions.find(s => s.value === status)?.label || status
}

const openModal = (item = null) => {
  if (item) {
    editingItem.value = item
    form.value = { ...item }
  } else {
    editingItem.value = null
    form.value = { name: '', description: '', start_date: '', end_date: '', status: 'upcoming', color: '#3b82f6' }
  }
  showModal.value = true
}

const saveItem = () => {
  if (editingItem.value) {
    const idx = programs.value.findIndex(p => p.id === editingItem.value.id)
    if (idx > -1) programs.value[idx] = { ...form.value, id: editingItem.value.id }
  } else {
    programs.value.push({ ...form.value, id: Date.now(), progress: 0 })
  }
  showModal.value = false
}

const deleteItem = (id) => {
  if (confirm('Yakin ingin menghapus program ini?')) {
    programs.value = programs.value.filter(p => p.id !== id)
  }
}

onMounted(() => {
  setTimeout(() => {
    programs.value = mockPrograms
    loading.value = false
  }, 500)
})
</script>

<template>
  <AdminLayout title="Timeline Program">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <p class="text-gray-600 dark:text-gray-400">Kelola timeline dan jadwal program kerja Bunda PAUD</p>
      </div>
      <button @click="openModal()" class="btn-primary flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Program
      </button>
    </div>

    <!-- Timeline View -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
      <div v-if="loading" class="text-center py-12">
        <div class="w-10 h-10 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin mx-auto"></div>
        <p class="mt-3 text-gray-500">Memuat data...</p>
      </div>

      <div v-else class="space-y-4">
        <div v-for="program in programs" :key="program.id" class="relative pl-8 pb-8 border-l-2 border-gray-200 dark:border-gray-700 last:border-0 last:pb-0">
          <!-- Timeline dot -->
          <div class="absolute left-0 top-0 w-4 h-4 rounded-full -translate-x-1/2" :style="{ backgroundColor: program.color }"></div>
          
          <!-- Content -->
          <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 ml-4">
            <div class="flex justify-between items-start">
              <div>
                <h3 class="font-semibold text-gray-900 dark:text-white">{{ program.name }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ program.description }}</p>
                <div class="flex items-center gap-4 mt-3">
                  <span class="text-xs text-gray-500">
                    📅 {{ program.start_date }} - {{ program.end_date }}
                  </span>
                  <span class="px-2 py-1 rounded-full text-xs font-medium" :class="getStatusClass(program.status)">
                    {{ getStatusLabel(program.status) }}
                  </span>
                </div>
                <!-- Progress bar -->
                <div v-if="program.status === 'active'" class="mt-3">
                  <div class="flex justify-between text-xs text-gray-500 mb-1">
                    <span>Progress</span>
                    <span>{{ program.progress }}%</span>
                  </div>
                  <div class="h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all" :style="{ width: program.progress + '%', backgroundColor: program.color }"></div>
                  </div>
                </div>
              </div>
              <div class="flex gap-2">
                <button @click="openModal(program)" class="p-2 text-gray-500 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
                <button @click="deleteItem(program.id)" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/50" @click="showModal = false"></div>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-lg mx-4 p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
          {{ editingItem ? 'Edit Program' : 'Tambah Program Baru' }}
        </h2>
        
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Program</label>
            <input v-model="form.name" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
            <textarea v-model="form.description" rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"></textarea>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai</label>
              <input v-model="form.start_date" type="date" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Selesai</label>
              <input v-model="form.end_date" type="date" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" />
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
              <select v-model="form.status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                <option v-for="opt in statusOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Warna</label>
              <input v-model="form.color" type="color" class="w-full h-10 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer" />
            </div>
          </div>
        </div>
        
        <div class="flex gap-3 mt-6">
          <button @click="showModal = false" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
            Batal
          </button>
          <button @click="saveItem" class="flex-1 btn-primary">
            Simpan
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
