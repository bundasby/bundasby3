<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const loading = ref(true)
const searchQuery = ref('')
const filterKecamatan = ref('')
const showModal = ref(false)
const editingItem = ref(null)

const form = ref({
  name: '',
  kecamatan: '',
  kelurahan: '',
  sekolah: '',
  tahun: '2026',
  total_anak: 0,
  sudah_daftar: 0,
  belum_daftar: 0,
  status: 'monitoring',
  notes: ''
})

// Mock data
const items = ref([
  { id: 1, name: 'SDN Gayungan I', kecamatan: 'Gayungan', kelurahan: 'Gayungan', tahun: '2026', total_anak: 45, sudah_daftar: 42, belum_daftar: 3, status: 'baik', notes: '' },
  { id: 2, name: 'SDN Ketintang', kecamatan: 'Gayungan', kelurahan: 'Ketintang', tahun: '2026', total_anak: 52, sudah_daftar: 48, belum_daftar: 4, status: 'baik', notes: '' },
  { id: 3, name: 'SDN Menanggal', kecamatan: 'Gayungan', kelurahan: 'Menanggal', tahun: '2026', total_anak: 38, sudah_daftar: 30, belum_daftar: 8, status: 'perlu_perhatian', notes: 'Perlu follow up orang tua' },
  { id: 4, name: 'SDN Wonocolo', kecamatan: 'Wonocolo', kelurahan: 'Wonocolo', tahun: '2026', total_anak: 65, sudah_daftar: 62, belum_daftar: 3, status: 'baik', notes: '' },
  { id: 5, name: 'SDN Bendul Merisi', kecamatan: 'Wonocolo', kelurahan: 'Bendul Merisi', tahun: '2026', total_anak: 48, sudah_daftar: 35, belum_daftar: 13, status: 'kritis', notes: 'Butuh sosialisasi intensif' }
])

const kecamatanList = ['Gayungan', 'Wonocolo', 'Tenggilis Mejoyo', 'Rungkut', 'Sukolilo']

const statusOptions = [
  { value: 'baik', label: 'Baik', class: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' },
  { value: 'perlu_perhatian', label: 'Perlu Perhatian', class: 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' },
  { value: 'kritis', label: 'Kritis', class: 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' },
  { value: 'monitoring', label: 'Monitoring', class: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }
]

const filteredItems = computed(() => {
  return items.value.filter(item => {
    const matchSearch = item.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                        item.kecamatan.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchKec = !filterKecamatan.value || item.kecamatan === filterKecamatan.value
    return matchSearch && matchKec
  })
})

const stats = computed(() => ({
  total_anak: items.value.reduce((sum, i) => sum + i.total_anak, 0),
  sudah_daftar: items.value.reduce((sum, i) => sum + i.sudah_daftar, 0),
  belum_daftar: items.value.reduce((sum, i) => sum + i.belum_daftar, 0),
  persentase: Math.round((items.value.reduce((sum, i) => sum + i.sudah_daftar, 0) / items.value.reduce((sum, i) => sum + i.total_anak, 0)) * 100)
}))

const getStatusClass = (status) => statusOptions.find(s => s.value === status)?.class || ''
const getStatusLabel = (status) => statusOptions.find(s => s.value === status)?.label || status
const getProgressColor = (item) => {
  const pct = (item.sudah_daftar / item.total_anak) * 100
  if (pct >= 90) return 'bg-green-500'
  if (pct >= 70) return 'bg-yellow-500'
  return 'bg-red-500'
}

const openModal = (item = null) => {
  if (item) {
    editingItem.value = item
    form.value = { ...item }
  } else {
    editingItem.value = null
    form.value = { name: '', kecamatan: '', kelurahan: '', sekolah: '', tahun: '2026', total_anak: 0, sudah_daftar: 0, belum_daftar: 0, status: 'monitoring', notes: '' }
  }
  showModal.value = true
}

const saveItem = () => {
  form.value.belum_daftar = form.value.total_anak - form.value.sudah_daftar
  if (editingItem.value) {
    const idx = items.value.findIndex(i => i.id === editingItem.value.id)
    if (idx > -1) items.value[idx] = { ...form.value, id: editingItem.value.id }
  } else {
    items.value.push({ ...form.value, id: Date.now() })
  }
  showModal.value = false
}

const deleteItem = (id) => {
  if (confirm('Yakin ingin menghapus data ini?')) {
    items.value = items.value.filter(i => i.id !== id)
  }
}

onMounted(() => {
  setTimeout(() => { loading.value = false }, 500)
})
</script>

<template>
  <AdminLayout title="Monitor Transisi PAUD ke SD">
    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Total Anak PAUD</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total_anak.toLocaleString() }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Sudah Daftar SD</p>
        <p class="text-2xl font-bold text-green-600">{{ stats.sudah_daftar.toLocaleString() }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Belum Daftar</p>
        <p class="text-2xl font-bold text-red-600">{{ stats.belum_daftar.toLocaleString() }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Persentase</p>
        <p class="text-2xl font-bold" :class="stats.persentase >= 80 ? 'text-green-600' : 'text-yellow-600'">{{ stats.persentase }}%</p>
      </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-3 mb-6">
      <div class="relative flex-1 min-w-[200px]">
        <input v-model="searchQuery" type="text" placeholder="Cari sekolah..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
        <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </div>
      <select v-model="filterKecamatan" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        <option value="">Semua Kecamatan</option>
        <option v-for="kec in kecamatanList" :key="kec" :value="kec">{{ kec }}</option>
      </select>
      <button @click="openModal()" class="btn-primary flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Data
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
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Sekolah</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Kecamatan</th>
            <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Total</th>
            <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Progress</th>
            <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Status</th>
            <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-for="item in filteredItems" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
            <td class="px-4 py-3">
              <p class="font-medium text-gray-900 dark:text-white">{{ item.name }}</p>
              <p class="text-xs text-gray-500">{{ item.kelurahan }}</p>
            </td>
            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ item.kecamatan }}</td>
            <td class="px-4 py-3 text-center">
              <span class="font-medium text-gray-900 dark:text-white">{{ item.total_anak }}</span>
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <div class="flex-1 h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                  <div class="h-full rounded-full" :class="getProgressColor(item)" :style="{ width: (item.sudah_daftar / item.total_anak * 100) + '%' }"></div>
                </div>
                <span class="text-xs text-gray-500 w-16">{{ item.sudah_daftar }}/{{ item.total_anak }}</span>
              </div>
            </td>
            <td class="px-4 py-3 text-center">
              <span class="px-2 py-1 rounded-full text-xs font-medium" :class="getStatusClass(item.status)">
                {{ getStatusLabel(item.status) }}
              </span>
            </td>
            <td class="px-4 py-3 text-center">
              <div class="flex justify-center gap-1">
                <button @click="openModal(item)" class="p-1.5 text-gray-500 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
                <button @click="deleteItem(item.id)" class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded">
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
          {{ editingItem ? 'Edit Data' : 'Tambah Data Baru' }}
        </h2>
        
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Sekolah SD</label>
            <input v-model="form.name" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kecamatan</label>
              <select v-model="form.kecamatan" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option v-for="kec in kecamatanList" :key="kec" :value="kec">{{ kec }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kelurahan</label>
              <input v-model="form.kelurahan" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Anak PAUD</label>
              <input v-model.number="form.total_anak" type="number" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sudah Daftar SD</label>
              <input v-model.number="form.sudah_daftar" type="number" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select v-model="form.status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
              <option v-for="s in statusOptions" :key="s.value" :value="s.value">{{ s.label }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
            <textarea v-model="form.notes" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
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
