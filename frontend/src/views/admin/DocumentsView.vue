<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const loading = ref(true)
const showModal = ref(false)
const editingDoc = ref(null)
const uploadForm = ref({ title: '', category: 'regulasi', file_url: '', file_type: 'PDF' })

const documents = ref([
  { id: 1, title: 'SK Pengangkatan Pokja 2025-2030', category: 'regulasi', file_type: 'PDF', file_url: '', file_size: 2500000, is_active: true },
  { id: 2, title: 'Laporan Konferensi 2025', category: 'kegiatan', file_type: 'PDF', file_url: '', file_size: 5200000, is_active: true },
  { id: 3, title: 'RAB 2025', category: 'anggaran', file_type: 'XLSX', file_url: '', file_size: 1800000, is_active: true },
  { id: 4, title: 'Laporan Monitoring MBG Q4', category: 'monitoring', file_type: 'PDF', file_url: '', file_size: 3100000, is_active: true },
  { id: 5, title: 'Draft Pedoman PAUD HI', category: 'regulasi', file_type: 'DOCX', file_url: '', file_size: 4700000, is_active: false },
])

onMounted(() => setTimeout(() => loading.value = false, 500))

const formatSize = (bytes) => {
  if (bytes < 1024) return bytes + ' B'
  if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB'
  return (bytes / (1024 * 1024)).toFixed(1) + ' MB'
}

const getCategoryLabel = (cat) => {
  const labels = { kegiatan: 'Kegiatan', regulasi: 'Regulasi', anggaran: 'Anggaran', monitoring: 'Monitoring' }
  return labels[cat] || cat
}

const getFileIcon = (type) => {
  if (type === 'PDF') return 'text-red-500'
  if (type === 'XLSX') return 'text-green-600'
  if (type === 'DOCX') return 'text-blue-600'
  return 'text-gray-500'
}

const toggleActive = (doc) => doc.is_active = !doc.is_active

const deleteDocument = (id) => {
  if (confirm('Yakin ingin menghapus dokumen ini?')) {
    documents.value = documents.value.filter(d => d.id !== id)
  }
}

const openUploadModal = () => {
  editingDoc.value = null
  uploadForm.value = { title: '', category: 'regulasi', file_url: '', file_type: 'PDF' }
  showModal.value = true
}

const editDocument = (doc) => {
  editingDoc.value = doc
  uploadForm.value = { title: doc.title, category: doc.category, file_url: doc.file_url || '', file_type: doc.file_type }
  showModal.value = true
}

const convertGoogleDriveUrl = (url) => {
  const match = url.match(/\/d\/([a-zA-Z0-9_-]+)/)
  if (match) {
    return `https://drive.google.com/uc?export=download&id=${match[1]}`
  }
  return url
}

const handleSubmit = () => {
  const fileUrl = convertGoogleDriveUrl(uploadForm.value.file_url)
  
  if (editingDoc.value) {
    editingDoc.value.title = uploadForm.value.title
    editingDoc.value.category = uploadForm.value.category
    editingDoc.value.file_type = uploadForm.value.file_type
    editingDoc.value.file_url = fileUrl
    alert('Dokumen berhasil diupdate!')
  } else {
    const newId = Math.max(...documents.value.map(d => d.id)) + 1
    documents.value.push({
      id: newId,
      title: uploadForm.value.title,
      category: uploadForm.value.category,
      file_type: uploadForm.value.file_type,
      file_url: fileUrl,
      file_size: 0,
      is_active: true
    })
    alert('Dokumen berhasil ditambahkan!')
  }
  showModal.value = false
}
</script>

<template>
  <AdminLayout title="Kelola Dokumen">
    <div class="flex items-center justify-between mb-8">
      <p class="text-gray-600 dark:text-gray-400">Upload dan manage dokumen</p>
      <button @click="openUploadModal" class="btn-primary flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
        </svg>
        Upload Dokumen
      </button>
    </div>

    <div v-if="loading" class="flex justify-center py-12">
      <div class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
    </div>

    <!-- Documents List -->
    <div v-else class="space-y-3">
      <div v-for="doc in documents" :key="doc.id" class="card p-4 flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
          <svg class="w-6 h-6" :class="getFileIcon(doc.file_type)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
        
        <div class="flex-1">
          <p class="font-medium text-gray-900 dark:text-white">{{ doc.title }}</p>
          <p class="text-sm text-gray-500">{{ doc.file_type }} • {{ formatSize(doc.file_size) }} • {{ getCategoryLabel(doc.category) }}</p>
        </div>
        
        <button @click="toggleActive(doc)" class="px-3 py-1 text-xs font-medium rounded-full" :class="doc.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'">
          {{ doc.is_active ? 'Aktif' : 'Hidden' }}
        </button>
        
        <div class="flex gap-2">
          <button @click="editDocument(doc)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
          <button @click="deleteDocument(doc.id)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.self="showModal = false">
      <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4 relative">
        <button @click="showModal = false" class="absolute top-4 right-4 p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pr-8">
          {{ editingDoc ? 'Edit Dokumen' : 'Upload Dokumen' }}
        </h3>

        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul</label>
            <input v-model="uploadForm.title" type="text" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
            <select v-model="uploadForm.category" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
              <option value="regulasi">Regulasi</option>
              <option value="kegiatan">Kegiatan</option>
              <option value="anggaran">Anggaran</option>
              <option value="monitoring">Monitoring</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe File</label>
            <select v-model="uploadForm.file_type" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
              <option value="PDF">PDF</option>
              <option value="DOCX">DOCX</option>
              <option value="XLSX">XLSX</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link File (Google Drive / URL)</label>
            <input v-model="uploadForm.file_url" type="url" required placeholder="https://drive.google.com/file/d/.../view" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
            <p class="text-xs text-gray-500 mt-1">Paste link Google Drive (open access) atau URL file lainnya</p>
          </div>
          <div class="flex gap-3 pt-4">
            <button type="button" @click="showModal = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
            <button type="submit" class="flex-1 btn-primary">{{ editingDoc ? 'Update' : 'Upload' }}</button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
