<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const loading = ref(true)
const searchQuery = ref('')
const filterKategori = ref('')
const showModal = ref(false)
const editingItem = ref(null)

const form = ref({
  name: '',
  kategori: '',
  file_url: '',
  file_size: '',
  uploaded_at: '',
  status: 'active',
  notes: ''
})

// Mock data
const items = ref([
  { id: 1, name: 'SK Bunda PAUD Kecamatan 2026', kategori: 'sk', file_url: '/files/sk-bunda-2026.pdf', file_size: '2.4 MB', uploaded_at: '2026-01-05', status: 'active' },
  { id: 2, name: 'Laporan Kegiatan Januari 2026', kategori: 'laporan', file_url: '/files/laporan-jan-2026.pdf', file_size: '5.2 MB', uploaded_at: '2026-01-10', status: 'active' },
  { id: 3, name: 'Foto Kegiatan Sosialisasi', kategori: 'dokumentasi', file_url: '/files/foto-sosialisasi.zip', file_size: '15.8 MB', uploaded_at: '2026-01-08', status: 'active' },
  { id: 4, name: 'Proposal Program Kerja 2026', kategori: 'proposal', file_url: '/files/proposal-2026.docx', file_size: '1.1 MB', uploaded_at: '2026-01-03', status: 'active' },
  { id: 5, name: 'Data Profil Bunda PAUD', kategori: 'data', file_url: '/files/profil-bunda.xlsx', file_size: '856 KB', uploaded_at: '2026-01-02', status: 'active' }
])

const kategoriOptions = [
  { value: 'sk', label: 'Surat Keputusan', icon: '📜' },
  { value: 'laporan', label: 'Laporan', icon: '📊' },
  { value: 'dokumentasi', label: 'Dokumentasi', icon: '📷' },
  { value: 'proposal', label: 'Proposal', icon: '📝' },
  { value: 'data', label: 'Data', icon: '📁' },
  { value: 'lainnya', label: 'Lainnya', icon: '📎' }
]

const filteredItems = computed(() => {
  return items.value.filter(item => {
    const matchSearch = item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchKategori = !filterKategori.value || item.kategori === filterKategori.value
    return matchSearch && matchKategori
  })
})

const getKategoriIcon = (kat) => kategoriOptions.find(k => k.value === kat)?.icon || '📎'
const getKategoriLabel = (kat) => kategoriOptions.find(k => k.value === kat)?.label || kat
const getFileIcon = (name) => {
  if (name.endsWith('.pdf')) return '📄'
  if (name.endsWith('.docx') || name.endsWith('.doc')) return '📝'
  if (name.endsWith('.xlsx') || name.endsWith('.xls')) return '📊'
  if (name.endsWith('.zip') || name.endsWith('.rar')) return '🗜️'
  if (name.endsWith('.jpg') || name.endsWith('.png')) return '🖼️'
  return '📎'
}

const openModal = (item = null) => {
  if (item) {
    editingItem.value = item
    form.value = { ...item }
  } else {
    editingItem.value = null
    form.value = { name: '', kategori: '', file_url: '', file_size: '', uploaded_at: new Date().toISOString().split('T')[0], status: 'active', notes: '' }
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
  if (confirm('Yakin ingin menghapus berkas ini?')) {
    items.value = items.value.filter(i => i.id !== id)
  }
}

const downloadFile = (item) => {
  alert(`Download: ${item.name}`)
}

onMounted(() => {
  setTimeout(() => { loading.value = false }, 500)
})
</script>

<template>
  <AdminLayout title="Berkas">
    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Total Berkas</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ items.length }}</p>
      </div>
      <div v-for="kat in kategoriOptions.slice(0, 3)" :key="kat.value" class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">{{ kat.label }}</p>
        <p class="text-2xl font-bold text-primary-600">{{ items.filter(i => i.kategori === kat.value).length }}</p>
      </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-3 mb-6">
      <div class="relative flex-1 min-w-[200px]">
        <input v-model="searchQuery" type="text" placeholder="Cari berkas..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
        <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </div>
      <select v-model="filterKategori" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        <option value="">Semua Kategori</option>
        <option v-for="k in kategoriOptions" :key="k.value" :value="k.value">{{ k.icon }} {{ k.label }}</option>
      </select>
      <button @click="openModal()" class="btn-primary flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Upload Berkas
      </button>
    </div>

    <!-- Files Grid -->
    <div v-if="loading" class="text-center py-12">
      <div class="w-10 h-10 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin mx-auto"></div>
    </div>

    <div v-else class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div v-for="item in filteredItems" :key="item.id" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:shadow-lg transition-shadow">
        <div class="flex items-start gap-3">
          <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center text-2xl flex-shrink-0">
            {{ getFileIcon(item.file_url) }}
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="font-medium text-gray-900 dark:text-white truncate">{{ item.name }}</h3>
            <div class="flex items-center gap-2 mt-1">
              <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                {{ getKategoriIcon(item.kategori) }} {{ getKategoriLabel(item.kategori) }}
              </span>
              <span class="text-xs text-gray-500">{{ item.file_size }}</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">Diupload: {{ item.uploaded_at }}</p>
          </div>
        </div>
        <div class="flex justify-end gap-2 mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
          <button @click="downloadFile(item)" class="px-3 py-1.5 text-sm text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Download
          </button>
          <button @click="openModal(item)" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">Edit</button>
          <button @click="deleteItem(item.id)" class="px-3 py-1.5 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg">Hapus</button>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/50" @click="showModal = false"></div>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-lg mx-4 p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
          {{ editingItem ? 'Edit Berkas' : 'Upload Berkas Baru' }}
        </h2>
        
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Berkas</label>
            <input v-model="form.name" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
            <select v-model="form.kategori" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
              <option v-for="k in kategoriOptions" :key="k.value" :value="k.value">{{ k.icon }} {{ k.label }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Upload File</label>
            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center">
              <svg class="w-10 h-10 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
              </svg>
              <p class="text-sm text-gray-500">Klik atau drag file ke sini</p>
              <input type="file" class="hidden" />
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
