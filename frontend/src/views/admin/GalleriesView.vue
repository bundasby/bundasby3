<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const loading = ref(true)
const showModal = ref(false)
const editingGallery = ref(null)
const uploadForm = ref({ title: '', category: '', image_url: '' })

const galleries = ref([
  { id: 1, title: 'Pembukaan Konferensi 2025', category: 'Konferensi 2025', image_url: 'https://placehold.co/300x200/2563eb/ffffff?text=Konf+1', is_active: true },
  { id: 2, title: 'Sambutan Bunda PAUD', category: 'Konferensi 2025', image_url: 'https://placehold.co/300x200/2563eb/ffffff?text=Konf+2', is_active: true },
  { id: 3, title: 'Workshop Guru PAUD', category: 'Pelatihan Guru', image_url: 'https://placehold.co/300x200/16a34a/ffffff?text=Pelatihan+1', is_active: true },
  { id: 4, title: 'Pembagian MBG', category: 'Program MBG', image_url: 'https://placehold.co/300x200/f59e0b/ffffff?text=MBG+1', is_active: true },
  { id: 5, title: 'Kegiatan Belajar', category: 'PAUD', image_url: 'https://placehold.co/300x200/8b5cf6/ffffff?text=PAUD+1', is_active: false },
])

onMounted(() => setTimeout(() => loading.value = false, 500))

const toggleActive = (gallery) => gallery.is_active = !gallery.is_active

const deleteGallery = (id) => {
  if (confirm('Yakin ingin menghapus gambar ini?')) {
    galleries.value = galleries.value.filter(g => g.id !== id)
  }
}

const openUploadModal = () => {
  editingGallery.value = null
  uploadForm.value = { title: '', category: '', image_url: '' }
  showModal.value = true
}

const editGallery = (gallery) => {
  editingGallery.value = gallery
  uploadForm.value = { title: gallery.title, category: gallery.category, image_url: gallery.image_url }
  showModal.value = true
}

const convertGoogleDriveUrl = (url) => {
  const match = url.match(/\/d\/([a-zA-Z0-9_-]+)/)
  if (match) {
    return `https://drive.google.com/uc?export=view&id=${match[1]}`
  }
  return url
}

const handleSubmit = () => {
  const imageUrl = convertGoogleDriveUrl(uploadForm.value.image_url)
  
  if (editingGallery.value) {
    editingGallery.value.title = uploadForm.value.title
    editingGallery.value.category = uploadForm.value.category
    editingGallery.value.image_url = imageUrl
    alert('Galeri berhasil diupdate!')
  } else {
    const newId = Math.max(...galleries.value.map(g => g.id)) + 1
    galleries.value.push({
      id: newId,
      title: uploadForm.value.title,
      category: uploadForm.value.category,
      image_url: imageUrl,
      is_active: true
    })
    alert('Gambar berhasil diupload!')
  }
  showModal.value = false
}
</script>

<template>
  <AdminLayout title="Kelola Galeri">
    <div class="flex items-center justify-between mb-8">
      <p class="text-gray-600 dark:text-gray-400">Manage foto dan gambar kegiatan</p>
      <button @click="openUploadModal" class="btn-primary flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Upload Gambar
      </button>
    </div>

    <div v-if="loading" class="flex justify-center py-12">
      <div class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
    </div>

    <!-- Gallery Grid -->
    <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <div v-for="gallery in galleries" :key="gallery.id" class="card overflow-hidden group">
        <div class="relative">
          <img :src="gallery.image_url" :alt="gallery.title" class="w-full h-40 object-cover"/>
          <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
            <button @click="editGallery(gallery)" class="p-2 bg-white rounded-lg text-blue-600 hover:bg-blue-50">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
            </button>
            <button @click="deleteGallery(gallery.id)" class="p-2 bg-white rounded-lg text-red-600 hover:bg-red-50">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
            </button>
          </div>
        </div>
        <div class="p-3">
          <p class="font-medium text-gray-900 dark:text-white text-sm truncate">{{ gallery.title }}</p>
          <div class="flex items-center justify-between mt-2">
            <span class="text-xs text-gray-500">{{ gallery.category }}</span>
            <button @click="toggleActive(gallery)" class="text-xs px-2 py-1 rounded" :class="gallery.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'">
              {{ gallery.is_active ? 'Aktif' : 'Hidden' }}
            </button>
          </div>
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
          {{ editingGallery ? 'Edit Galeri' : 'Upload Gambar' }}
        </h3>

        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul</label>
            <input v-model="uploadForm.title" type="text" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
            <input v-model="uploadForm.category" type="text" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link Gambar (Google Drive / URL)</label>
            <input v-model="uploadForm.image_url" type="url" required placeholder="https://drive.google.com/file/d/.../view" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
            <p class="text-xs text-gray-500 mt-1">Paste link Google Drive (open access) atau URL gambar lainnya</p>
          </div>
          <div class="flex gap-3 pt-4">
            <button type="button" @click="showModal = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
            <button type="submit" class="flex-1 btn-primary">{{ editingGallery ? 'Update' : 'Upload' }}</button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
