<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import { profileService } from '@/services'

const profiles = ref([])
const loading = ref(true)
const showModal = ref(false)
const editingItem = ref(null)
const form = ref({
  section_key: '',
  title: '',
  content: '',
  image: '',
  is_active: true
})

// Rich Text Editor Toolbar (Simple)
// Note: In a real app we might use Quill or Tiptap, but for now a simple textarea with basic HTML support or just plain text
// For Visi Misi usually we need HTML (lists, etc). 
// Let's use a simple textarea but allow HTML tags.

const fetchProfiles = async () => {
  loading.value = true
  try {
    const response = await profileService.getAll()
    if (response.success) {
      profiles.value = response.data
    }
  } catch (error) {
    console.error('Failed to fetch profiles:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchProfiles()
})

const openEditModal = (item) => {
  editingItem.value = item
  form.value = { 
    section_key: item.section_key,
    title: item.title,
    content: item.content,
    image: item.image || '',
    is_active: item.is_active
  }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  editingItem.value = null
  form.value = { section_key: '', title: '', content: '', image: '', is_active: true }
}

const saveProfile = async () => {
  try {
    if (editingItem.value) {
      await profileService.update(editingItem.value.section_key, form.value)
      alert('Profil berhasil diperbarui!')
    } else {
      // Create new (optional, usually we stick to fixed keys)
      await profileService.create(form.value)
      alert('Profil berhasil dibuat!')
    }
    closeModal()
    fetchProfiles()
  } catch (error) {
    console.error('Error saving profile:', error)
    alert('Gagal menyimpan profil.')
  }
}

const getSectionName = (key) => {
  const map = {
    'visi_misi': 'Visi & Misi',
    'sambutan': 'Sambutan Bunda PAUD',
    'arti_logo': 'Arti Logo',
    'tugas_fungsi': 'Tugas & Fungsi',
    'struktur_organisasi': 'Struktur Organisasi'
  }
  return map[key] || key
}
</script>

<template>
  <AdminLayout title="Kelola Profil">
    <div class="flex items-center justify-between mb-8">
      <p class="text-gray-600 dark:text-gray-400">Kelola konten halaman profil (Visi Misi, Sambutan, dll)</p>
      <!-- Add button is hidden because keys are usually fixed, or we can enable it for custom sections -->
    </div>

    <div v-if="loading" class="flex justify-center py-12">
      <div class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
    </div>

    <!-- Grid Layout -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="item in profiles" :key="item.id" class="card group hover:border-primary-500 transition-colors">
        <div class="p-6">
          <div class="flex items-start justify-between mb-4">
            <div class="p-3 rounded-lg bg-primary-100 text-primary-600 dark:bg-primary-900/30">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
              </svg>
            </div>
            <span :class="item.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" class="px-2.5 py-0.5 rounded-full text-xs font-medium">
              {{ item.is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
          </div>

          <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ item.title }}</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-2">
            {{ item.content ? item.content.replace(/<[^>]*>?/gm, '') : 'Belum ada konten' }}
          </p>

          <div class="flex items-center justify-end pt-4 border-t border-gray-100 dark:border-gray-700">
            <button @click="openEditModal(item)" class="text-primary-600 hover:text-primary-700 font-medium text-sm flex items-center gap-1">
              Edit Konten
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" @click.self="closeModal">
      <div class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-xl">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit {{ form.title }}</h3>
          <button @click="closeModal" class="text-gray-400 hover:text-gray-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <div class="p-6 space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Judul</label>
            <input v-model="form.title" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"/>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Konten (HTML Support)</label>
            <textarea v-model="form.content" rows="10" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-mono text-sm focus:ring-2 focus:ring-primary-500" placeholder="<p>Tulis konten di sini...</p>"></textarea>
            <p class="text-xs text-gray-500 mt-2">Anda bisa menggunakan tag HTML seperti &lt;p&gt;, &lt;b&gt;, &lt;ul&gt;, &lt;li&gt;.</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">URL Gambar (Opsional)</label>
            <input v-model="form.image" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"/>
            <div v-if="form.image" class="mt-2">
              <img :src="form.image" class="h-32 object-contain rounded-lg border border-gray-200"/>
            </div>
          </div>

          <div class="flex items-center gap-3">
             <input type="checkbox" id="is_active" v-model="form.is_active" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
             <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">Tampilkan di website (Aktif)</label>
          </div>
        </div>

        <div class="p-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-3">
          <button @click="closeModal" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">Batal</button>
          <button @click="saveProfile" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium">Simpan Perubahan</button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
