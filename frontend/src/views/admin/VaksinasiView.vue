<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const loading = ref(true)
const searchQuery = ref('')
const filterKecamatan = ref('')
const showModal = ref(false)
const editingItem = ref(null)

const form = ref({
  nama_anak: '',
  usia: '',
  jenis_kelamin: '',
  sekolah: '',
  kecamatan: '',
  jenis_vaksin: '',
  tanggal: '',
  status: 'sudah'
})

// Mock data
const items = ref([
  { id: 1, nama_anak: 'Ahmad Faruq', usia: '5 tahun', jenis_kelamin: 'L', sekolah: 'TK Negeri Pembina', kecamatan: 'Gayungan', jenis_vaksin: 'DPT', tanggal: '2026-01-15', status: 'sudah' },
  { id: 2, nama_anak: 'Siti Fatimah', usia: '4 tahun', jenis_kelamin: 'P', sekolah: 'KB Melati', kecamatan: 'Gayungan', jenis_vaksin: 'Campak', tanggal: '2026-01-20', status: 'sudah' },
  { id: 3, nama_anak: 'Budi Santoso', usia: '5 tahun', jenis_kelamin: 'L', sekolah: 'TK Islam Al-Hikmah', kecamatan: 'Wonocolo', jenis_vaksin: 'Polio', tanggal: '', status: 'belum' },
  { id: 4, nama_anak: 'Anisa Putri', usia: '6 tahun', jenis_kelamin: 'P', sekolah: 'TK Mawar', kecamatan: 'Rungkut', jenis_vaksin: 'MR', tanggal: '2026-01-10', status: 'sudah' },
  { id: 5, nama_anak: 'Rizky Pratama', usia: '4 tahun', jenis_kelamin: 'L', sekolah: 'KB Tunas Bangsa', kecamatan: 'Sukolilo', jenis_vaksin: 'DPT', tanggal: '', status: 'jadwal' }
])

const kecamatanList = ['Gayungan', 'Wonocolo', 'Rungkut', 'Sukolilo', 'Tenggilis Mejoyo']
const vaksinOptions = ['DPT', 'Polio', 'Campak', 'MR', 'BCG', 'Hepatitis B']
const statusOptions = [
  { value: 'sudah', label: 'Sudah Vaksin', class: 'bg-green-100 text-green-700' },
  { value: 'belum', label: 'Belum Vaksin', class: 'bg-red-100 text-red-700' },
  { value: 'jadwal', label: 'Dijadwalkan', class: 'bg-yellow-100 text-yellow-700' }
]

const filteredItems = computed(() => {
  return items.value.filter(i => {
    const matchSearch = i.nama_anak.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                        i.sekolah.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchKec = !filterKecamatan.value || i.kecamatan === filterKecamatan.value
    return matchSearch && matchKec
  })
})

const stats = computed(() => ({
  total: items.value.length,
  sudah: items.value.filter(i => i.status === 'sudah').length,
  belum: items.value.filter(i => i.status === 'belum').length,
  jadwal: items.value.filter(i => i.status === 'jadwal').length
}))

const getStatusClass = (status) => statusOptions.find(s => s.value === status)?.class || ''
const getStatusLabel = (status) => statusOptions.find(s => s.value === status)?.label || status

const openModal = (item = null) => {
  if (item) {
    editingItem.value = item
    form.value = { ...item }
  } else {
    editingItem.value = null
    form.value = { nama_anak: '', usia: '', jenis_kelamin: '', sekolah: '', kecamatan: '', jenis_vaksin: '', tanggal: '', status: 'belum' }
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
  if (confirm('Yakin ingin menghapus data vaksinasi ini?')) {
    items.value = items.value.filter(i => i.id !== id)
  }
}

onMounted(() => {
  setTimeout(() => { loading.value = false }, 500)
})
</script>

<template>
  <AdminLayout title="Data Vaksinasi">
    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Total Anak</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Sudah Vaksin</p>
        <p class="text-2xl font-bold text-green-600">{{ stats.sudah }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Belum Vaksin</p>
        <p class="text-2xl font-bold text-red-600">{{ stats.belum }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Dijadwalkan</p>
        <p class="text-2xl font-bold text-yellow-600">{{ stats.jadwal }}</p>
      </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-3 mb-6">
      <div class="relative flex-1 min-w-[200px]">
        <input v-model="searchQuery" type="text" placeholder="Cari nama anak/sekolah..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
        <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </div>
      <select v-model="filterKecamatan" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        <option value="">Semua Kecamatan</option>
        <option v-for="k in kecamatanList" :key="k" :value="k">{{ k }}</option>
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
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Nama Anak</th>
            <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Usia</th>
            <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">L/P</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Sekolah</th>
            <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Vaksin</th>
            <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Tanggal</th>
            <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Status</th>
            <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-for="item in filteredItems" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ item.nama_anak }}</td>
            <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ item.usia }}</td>
            <td class="px-4 py-3 text-center">
              <span :class="item.jenis_kelamin === 'L' ? 'text-blue-600' : 'text-pink-600'">{{ item.jenis_kelamin }}</span>
            </td>
            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ item.sekolah }}</td>
            <td class="px-4 py-3 text-center">
              <span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded text-xs font-medium">{{ item.jenis_vaksin }}</span>
            </td>
            <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ item.tanggal || '-' }}</td>
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
          {{ editingItem ? 'Edit Data' : 'Tambah Data Vaksinasi' }}
        </h2>
        
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Anak</label>
            <input v-model="form.nama_anak" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Usia</label>
              <input v-model="form.usia" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="5 tahun" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Kelamin</label>
              <select v-model="form.jenis_kelamin" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
              </select>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sekolah</label>
              <input v-model="form.sekolah" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kecamatan</label>
              <select v-model="form.kecamatan" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option v-for="k in kecamatanList" :key="k" :value="k">{{ k }}</option>
              </select>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Vaksin</label>
              <select v-model="form.jenis_vaksin" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option v-for="v in vaksinOptions" :key="v" :value="v">{{ v }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Vaksin</label>
              <input v-model="form.tanggal" type="date" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select v-model="form.status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
              <option v-for="s in statusOptions" :key="s.value" :value="s.value">{{ s.label }}</option>
            </select>
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
