<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const loading = ref(true)
const showModal = ref(false)
const editingItem = ref(null)
const searchQuery = ref('')
const filterKategori = ref('')
const filterStatus = ref('')

const form = ref({
  name: '',
  kecamatan: '',
  kelurahan: '',
  kategori: '',
  tahap: '',
  nilai: 0,
  status: 'pending',
  notes: ''
})

// Mock data
const items = ref([
  { id: 1, name: 'Bunda PAUD Gayungan', kecamatan: 'Gayungan', kelurahan: 'Gayungan', kategori: 'kecamatan', tahap: 'tahap_2', nilai: 92, status: 'lolos', notes: '' },
  { id: 2, name: 'Bunda PAUD Ketintang', kecamatan: 'Gayungan', kelurahan: 'Ketintang', kategori: 'kelurahan', tahap: 'tahap_1', nilai: 85, status: 'proses', notes: '' },
  { id: 3, name: 'Bunda PAUD Wonocolo', kecamatan: 'Wonocolo', kelurahan: 'Wonocolo', kategori: 'kecamatan', tahap: 'tahap_2', nilai: 88, status: 'lolos', notes: '' },
  { id: 4, name: 'Bunda PAUD Bendul Merisi', kecamatan: 'Wonocolo', kelurahan: 'Bendul Merisi', kategori: 'kelurahan', tahap: 'tahap_1', nilai: 75, status: 'tidak_lolos', notes: 'Perlu perbaikan dokumentasi' },
  { id: 5, name: 'Bunda PAUD Rungkut', kecamatan: 'Rungkut', kelurahan: 'Rungkut', kategori: 'kecamatan', tahap: 'tahap_1', nilai: 0, status: 'pending', notes: '' }
])

const kategoriOptions = [
  { value: 'kecamatan', label: 'Tingkat Kecamatan', icon: '🏛️' },
  { value: 'kelurahan', label: 'Tingkat Kelurahan', icon: '🏠' }
]

const tahapOptions = [
  { value: 'tahap_1', label: 'Tahap 1 - Seleksi Administrasi' },
  { value: 'tahap_2', label: 'Tahap 2 - Penilaian Lapangan' },
  { value: 'final', label: 'Final' }
]

const statusOptions = [
  { value: 'pending', label: 'Menunggu', class: 'bg-gray-100 text-gray-700' },
  { value: 'proses', label: 'Proses Penilaian', class: 'bg-blue-100 text-blue-700' },
  { value: 'lolos', label: 'Lolos', class: 'bg-green-100 text-green-700' },
  { value: 'tidak_lolos', label: 'Tidak Lolos', class: 'bg-red-100 text-red-700' }
]

const filteredItems = computed(() => {
  return items.value.filter(item => {
    const matchSearch = item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchKategori = !filterKategori.value || item.kategori === filterKategori.value
    const matchStatus = !filterStatus.value || item.status === filterStatus.value
    return matchSearch && matchKategori && matchStatus
  })
})

const stats = computed(() => ({
  total: items.value.length,
  lolos: items.value.filter(i => i.status === 'lolos').length,
  proses: items.value.filter(i => i.status === 'proses').length,
  pending: items.value.filter(i => i.status === 'pending').length
}))

const getStatusClass = (status) => statusOptions.find(s => s.value === status)?.class || ''
const getStatusLabel = (status) => statusOptions.find(s => s.value === status)?.label || status
const getKategoriIcon = (kat) => kategoriOptions.find(k => k.value === kat)?.icon || '📍'
const getTahapLabel = (tahap) => tahapOptions.find(t => t.value === tahap)?.label || tahap

const getNilaiColor = (nilai) => {
  if (nilai >= 85) return 'text-green-600'
  if (nilai >= 70) return 'text-yellow-600'
  if (nilai > 0) return 'text-red-600'
  return 'text-gray-400'
}

const openModal = (item = null) => {
  if (item) {
    editingItem.value = item
    form.value = { ...item }
  } else {
    editingItem.value = null
    form.value = { name: '', kecamatan: '', kelurahan: '', kategori: '', tahap: '', nilai: 0, status: 'pending', notes: '' }
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
  if (confirm('Yakin ingin menghapus peserta ini?')) {
    items.value = items.value.filter(i => i.id !== id)
  }
}

onMounted(() => {
  setTimeout(() => { loading.value = false }, 500)
})
</script>

<template>
  <AdminLayout title="Lomba Apresiasi Bunda PAUD">
    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Total Peserta</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Lolos</p>
        <p class="text-2xl font-bold text-green-600">{{ stats.lolos }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Proses Penilaian</p>
        <p class="text-2xl font-bold text-blue-600">{{ stats.proses }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Menunggu</p>
        <p class="text-2xl font-bold text-gray-600">{{ stats.pending }}</p>
      </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-3 mb-6">
      <div class="relative flex-1 min-w-[200px]">
        <input v-model="searchQuery" type="text" placeholder="Cari peserta..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
        <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </div>
      <select v-model="filterKategori" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        <option value="">Semua Kategori</option>
        <option v-for="k in kategoriOptions" :key="k.value" :value="k.value">{{ k.icon }} {{ k.label }}</option>
      </select>
      <select v-model="filterStatus" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        <option value="">Semua Status</option>
        <option v-for="s in statusOptions" :key="s.value" :value="s.value">{{ s.label }}</option>
      </select>
      <button @click="openModal()" class="btn-primary flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Peserta
      </button>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
      <div v-if="loading" class="text-center py-12">
        <div class="w-10 h-10 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin mx-auto"></div>
      </div>

      <table v-else class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Peserta</th>
            <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Kategori</th>
            <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Tahap</th>
            <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Nilai</th>
            <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Status</th>
            <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-for="item in filteredItems" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
            <td class="px-4 py-3">
              <p class="font-medium text-gray-900 dark:text-white">{{ item.name }}</p>
              <p class="text-xs text-gray-500">{{ item.kecamatan }} / {{ item.kelurahan }}</p>
            </td>
            <td class="px-4 py-3 text-center">
              <span class="text-xl">{{ getKategoriIcon(item.kategori) }}</span>
            </td>
            <td class="px-4 py-3 text-center text-sm text-gray-600 dark:text-gray-400">
              {{ getTahapLabel(item.tahap) }}
            </td>
            <td class="px-4 py-3 text-center">
              <span class="text-xl font-bold" :class="getNilaiColor(item.nilai)">
                {{ item.nilai || '-' }}
              </span>
            </td>
            <td class="px-4 py-3 text-center">
              <span class="px-2 py-1 rounded-full text-xs font-medium" :class="getStatusClass(item.status)">
                {{ getStatusLabel(item.status) }}
              </span>
            </td>
            <td class="px-4 py-3 text-center">
              <div class="flex justify-center gap-1">
                <button @click="openModal(item)" class="p-1.5 text-gray-500 hover:text-primary-600 hover:bg-primary-50 rounded">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
                <button @click="deleteItem(item.id)" class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/50" @click="showModal = false"></div>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-lg mx-4 p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
          {{ editingItem ? 'Edit Peserta' : 'Tambah Peserta Baru' }}
        </h2>
        
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Peserta</label>
            <input v-model="form.name" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kecamatan</label>
              <input v-model="form.kecamatan" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kelurahan</label>
              <input v-model="form.kelurahan" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
              <select v-model="form.kategori" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option v-for="k in kategoriOptions" :key="k.value" :value="k.value">{{ k.label }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahap</label>
              <select v-model="form.tahap" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option v-for="t in tahapOptions" :key="t.value" :value="t.value">{{ t.label }}</option>
              </select>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nilai</label>
              <input v-model.number="form.nilai" type="number" min="0" max="100" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
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
