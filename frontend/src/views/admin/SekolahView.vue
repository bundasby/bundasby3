<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const loading = ref(true)
const searchQuery = ref('')
const filterStatus = ref('')
const showModal = ref(false)
const editingItem = ref(null)

const form = ref({
  name: '',
  npsn: '',
  jenis: '',
  alamat: '',
  kepala_sekolah: '',
  jumlah_siswa: 0,
  jumlah_guru: 0,
  status_ijop: 'aktif',
  akreditasi: ''
})

// Mock data
const items = ref([
  { id: 1, name: 'TK Negeri Pembina', npsn: '20521234', jenis: 'TK', alamat: 'Jl. Gayungsari No. 10', kepala_sekolah: 'Ibu Sri Lestari, S.Pd', jumlah_siswa: 120, jumlah_guru: 8, status_ijop: 'aktif', akreditasi: 'A' },
  { id: 2, name: 'KB Melati', npsn: '20524567', jenis: 'KB', alamat: 'Jl. Ketintang Baru No. 5', kepala_sekolah: 'Ibu Dewi Hartati', jumlah_siswa: 45, jumlah_guru: 4, status_ijop: 'aktif', akreditasi: 'B' },
  { id: 3, name: 'TK Islam Al-Hikmah', npsn: '20527890', jenis: 'TK', alamat: 'Jl. Wonocolo No. 15', kepala_sekolah: 'Ibu Siti Aminah, S.Pd.I', jumlah_siswa: 85, jumlah_guru: 6, status_ijop: 'aktif', akreditasi: 'A' },
  { id: 4, name: 'TPA Cahaya Bunda', npsn: '20521357', jenis: 'TPA', alamat: 'Jl. Bendul Merisi No. 8', kepala_sekolah: 'Ibu Ratna Dewi', jumlah_siswa: 30, jumlah_guru: 3, status_ijop: 'proses', akreditasi: '-' },
  { id: 5, name: 'SPS Tunas Bangsa', npsn: '20528024', jenis: 'SPS', alamat: 'Jl. Rungkut Kidul No. 20', kepala_sekolah: 'Ibu Maya Sari', jumlah_siswa: 25, jumlah_guru: 2, status_ijop: 'tidak_aktif', akreditasi: '-' }
])

const jenisOptions = ['TK', 'KB', 'TPA', 'SPS']
const statusOptions = [
  { value: 'aktif', label: 'Ijop Aktif', class: 'bg-green-100 text-green-700' },
  { value: 'proses', label: 'Dalam Proses', class: 'bg-yellow-100 text-yellow-700' },
  { value: 'tidak_aktif', label: 'Tidak Aktif', class: 'bg-red-100 text-red-700' }
]

const filteredItems = computed(() => {
  return items.value.filter(i => {
    const matchSearch = i.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                        i.npsn.includes(searchQuery.value)
    const matchStatus = !filterStatus.value || i.status_ijop === filterStatus.value
    return matchSearch && matchStatus
  })
})

const stats = computed(() => ({
  total: items.value.length,
  tk: items.value.filter(i => i.jenis === 'TK').length,
  kb: items.value.filter(i => i.jenis === 'KB').length,
  siswa: items.value.reduce((sum, i) => sum + i.jumlah_siswa, 0)
}))

const getStatusClass = (status) => statusOptions.find(s => s.value === status)?.class || ''
const getStatusLabel = (status) => statusOptions.find(s => s.value === status)?.label || status

const openModal = (item = null) => {
  if (item) {
    editingItem.value = item
    form.value = { ...item }
  } else {
    editingItem.value = null
    form.value = { name: '', npsn: '', jenis: '', alamat: '', kepala_sekolah: '', jumlah_siswa: 0, jumlah_guru: 0, status_ijop: 'aktif', akreditasi: '' }
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
  if (confirm('Yakin ingin menghapus data sekolah ini?')) {
    items.value = items.value.filter(i => i.id !== id)
  }
}

onMounted(() => {
  setTimeout(() => { loading.value = false }, 500)
})
</script>

<template>
  <AdminLayout title="Data Sekolah PAUD">
    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Total Sekolah</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">TK</p>
        <p class="text-2xl font-bold text-blue-600">{{ stats.tk }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">KB</p>
        <p class="text-2xl font-bold text-green-600">{{ stats.kb }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Total Siswa</p>
        <p class="text-2xl font-bold text-primary-600">{{ stats.siswa.toLocaleString() }}</p>
      </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-3 mb-6">
      <div class="relative flex-1 min-w-[200px]">
        <input v-model="searchQuery" type="text" placeholder="Cari nama/NPSN..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
        <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </div>
      <select v-model="filterStatus" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        <option value="">Semua Status Ijop</option>
        <option v-for="s in statusOptions" :key="s.value" :value="s.value">{{ s.label }}</option>
      </select>
      <button @click="openModal()" class="btn-primary flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Sekolah
      </button>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
      <div v-if="loading" class="text-center py-12">
        <div class="w-10 h-10 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin mx-auto"></div>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Sekolah</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">NPSN</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Jenis</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Siswa</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Guru</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Akreditasi</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Status Ijop</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="item in filteredItems" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
              <td class="px-4 py-3">
                <p class="font-medium text-gray-900 dark:text-white">{{ item.name }}</p>
                <p class="text-xs text-gray-500">{{ item.alamat }}</p>
              </td>
              <td class="px-4 py-3 text-center text-sm text-gray-600 dark:text-gray-400 font-mono">{{ item.npsn }}</td>
              <td class="px-4 py-3 text-center">
                <span class="px-2 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-700">{{ item.jenis }}</span>
              </td>
              <td class="px-4 py-3 text-center font-medium text-gray-900 dark:text-white">{{ item.jumlah_siswa }}</td>
              <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ item.jumlah_guru }}</td>
              <td class="px-4 py-3 text-center">
                <span v-if="item.akreditasi !== '-'" class="px-2 py-1 rounded-full text-xs font-bold" :class="item.akreditasi === 'A' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'">
                  {{ item.akreditasi }}
                </span>
                <span v-else class="text-gray-400">-</span>
              </td>
              <td class="px-4 py-3 text-center">
                <span class="px-2 py-1 rounded-full text-xs font-medium" :class="getStatusClass(item.status_ijop)">
                  {{ getStatusLabel(item.status_ijop) }}
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
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/50" @click="showModal = false"></div>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-lg mx-4 p-6 max-h-[90vh] overflow-y-auto">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
          {{ editingItem ? 'Edit Sekolah' : 'Tambah Sekolah' }}
        </h2>
        
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Sekolah</label>
            <input v-model="form.name" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NPSN</label>
              <input v-model="form.npsn" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis</label>
              <select v-model="form.jenis" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option v-for="j in jenisOptions" :key="j" :value="j">{{ j }}</option>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
            <input v-model="form.alamat" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kepala Sekolah</label>
            <input v-model="form.kepala_sekolah" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah Siswa</label>
              <input v-model.number="form.jumlah_siswa" type="number" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah Guru</label>
              <input v-model.number="form.jumlah_guru" type="number" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status Ijop</label>
              <select v-model="form.status_ijop" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option v-for="s in statusOptions" :key="s.value" :value="s.value">{{ s.label }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Akreditasi</label>
              <input v-model="form.akreditasi" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="A/B/C/-" />
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
