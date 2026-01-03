<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { articlesStorage, categoriesStorage, initializeData } from '@/services/localStorage'

const router = useRouter()
const route = useRoute()

const isEdit = computed(() => route.params.id !== 'new')
const loading = ref(false)
const saving = ref(false)

const form = ref({
  title: '',
  slug: '',
  excerpt: '',
  content: '',
  category: 'berita',
  thumbnail: '',
  is_published: true,
})

// Load categories from localStorage
const categories = ref([])

const loadCategories = () => {
  const cats = categoriesStorage.getAll()
  categories.value = cats.map(c => ({ value: c.id, label: c.name }))
}

// Image upload handling
const imagePreview = ref('')
const fileInput = ref(null)

const handleImageUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    // Create a data URL for preview and storage
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
      form.value.thumbnail = e.target.result // Store as base64
    }
    reader.readAsDataURL(file)
  }
}

const triggerFileInput = () => {
  fileInput.value.click()
}

const removeImage = () => {
  imagePreview.value = ''
  form.value.thumbnail = ''
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

onMounted(() => {
  initializeData()
  loadCategories()
  
  if (isEdit.value) {
    loading.value = true
    // Load existing article
    const article = articlesStorage.get(route.params.id)
    if (article) {
      form.value = {
        title: article.title,
        slug: article.slug,
        excerpt: article.excerpt,
        content: article.content,
        category: article.category,
        thumbnail: article.image || '',
        is_published: article.isPublished,
      }
      imagePreview.value = article.image || ''
    }
    loading.value = false
  }
})

const generateSlug = () => {
  form.value.slug = form.value.title
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/(^-|-$)/g, '')
}

const handleSubmit = async () => {
  if (!form.value.title.trim()) {
    alert('Judul berita harus diisi!')
    return
  }
  if (!form.value.excerpt.trim()) {
    alert('Ringkasan berita harus diisi!')
    return
  }
  if (!form.value.content.trim()) {
    alert('Isi berita harus diisi!')
    return
  }

  saving.value = true
  
  // Generate slug if empty
  if (!form.value.slug) {
    generateSlug()
  }

  const articleData = {
    title: form.value.title,
    slug: form.value.slug,
    excerpt: form.value.excerpt,
    content: form.value.content,
    category: form.value.category,
    image: form.value.thumbnail,
    isPublished: form.value.is_published,
    author: 'Admin'
  }

  try {
    if (isEdit.value) {
      articlesStorage.update(route.params.id, articleData)
    } else {
      articlesStorage.create(articleData)
    }
    
    alert(isEdit.value ? 'Berita berhasil diupdate!' : 'Berita berhasil dibuat!')
    router.push('/admin/articles')
  } catch (error) {
    alert('Terjadi kesalahan: ' + error.message)
  } finally {
    saving.value = false
  }
}

const goBack = () => {
  router.push('/admin/articles')
}
</script>

<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="flex">
      <!-- Sidebar -->
      <aside class="w-64 bg-white dark:bg-gray-800 min-h-screen border-r border-gray-200 dark:border-gray-700 fixed">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <router-link to="/admin/dashboard" class="flex items-center gap-3">
            <img src="/logo-bunda-paud.png" alt="Logo" class="h-10"/>
            <div>
              <h1 class="font-bold text-gray-900 dark:text-white">Admin Panel</h1>
              <p class="text-xs text-gray-500">Bunda PAUD Surabaya</p>
            </div>
          </router-link>
        </div>
        <nav class="p-4">
          <ul class="space-y-1">
            <li><router-link to="/admin/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">Dashboard</router-link></li>
            <li><router-link to="/admin/articles" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium bg-primary-50 dark:bg-primary-900/30 text-primary-600">Berita</router-link></li>
            <li><router-link to="/admin/galleries" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">Galeri</router-link></li>
            <li><router-link to="/admin/documents" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">Dokumen</router-link></li>
            <li><router-link to="/admin/complaints" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">Pengaduan</router-link></li>
          </ul>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 ml-64 p-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
          <div class="flex items-center gap-4">
            <button @click="goBack" class="p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg">
              <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            <div>
              <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ isEdit ? 'Edit Berita' : 'Tambah Berita Baru' }}
              </h1>
              <p class="text-gray-600 dark:text-gray-400">
                {{ isEdit ? 'Perbarui berita yang sudah ada' : 'Buat berita baru untuk website' }}
              </p>
            </div>
          </div>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex justify-center py-12">
          <div class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
        </div>

        <!-- Form -->
        <form v-else @submit.prevent="handleSubmit" class="space-y-6">
          <div class="grid lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
              <!-- Title -->
              <div class="card p-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Judul Berita <span class="text-red-500">*</span>
                </label>
                <input 
                  v-model="form.title"
                  @blur="!form.slug && generateSlug()"
                  type="text"
                  required
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                  placeholder="Contoh: Pelatihan Guru PAUD 2025"
                />
              </div>

              <!-- Excerpt -->
              <div class="card p-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Ringkasan Singkat <span class="text-red-500">*</span>
                </label>
                <textarea 
                  v-model="form.excerpt"
                  required
                  rows="3"
                  maxlength="200"
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                  placeholder="Tulis ringkasan berita dalam 1-2 kalimat..."
                ></textarea>
                <p class="mt-1 text-xs text-gray-500">{{ form.excerpt.length }}/200 karakter</p>
              </div>

              <!-- Content -->
              <div class="card p-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Isi Berita <span class="text-red-500">*</span>
                </label>
                <textarea 
                  v-model="form.content"
                  required
                  rows="15"
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                  placeholder="Tulis isi berita lengkap di sini..."
                ></textarea>
                <p class="mt-2 text-xs text-gray-500">
                  💡 Tips: Tulis berita dengan jelas dan informatif
                </p>
              </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
              <!-- Publish Status -->
              <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Status Publikasi</h3>
                <label class="flex items-center gap-3 cursor-pointer">
                  <input 
                    v-model="form.is_published" 
                    type="checkbox"
                    class="w-5 h-5 text-primary-600 rounded border-gray-300 focus:ring-primary-500"
                  />
                  <span class="text-gray-700 dark:text-gray-300">Tampilkan di Website</span>
                </label>
                <p class="mt-2 text-xs text-gray-500">
                  {{ form.is_published ? '✅ Berita akan langsung tampil di website' : '📝 Berita disimpan sebagai draft (tidak tampil)' }}
                </p>
              </div>

              <!-- Category -->
              <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Jenis Berita</h3>
                <select 
                  v-model="form.category"
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                >
                  <option v-for="cat in categories" :key="cat.value" :value="cat.value">{{ cat.label }}</option>
                </select>
              </div>

              <!-- Image Upload -->
              <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Foto Berita</h3>
                
                <!-- Hidden file input -->
                <input 
                  ref="fileInput"
                  type="file"
                  accept="image/*"
                  @change="handleImageUpload"
                  class="hidden"
                />
                
                <!-- Image Preview -->
                <div v-if="imagePreview || form.thumbnail" class="mb-4 relative">
                  <img 
                    :src="imagePreview || form.thumbnail" 
                    alt="Preview" 
                    class="w-full rounded-lg object-cover"
                    style="max-height: 200px"
                  />
                  <button 
                    type="button" 
                    @click="removeImage"
                    class="absolute top-2 right-2 bg-red-500 text-white p-1.5 rounded-full hover:bg-red-600 transition-colors"
                    title="Hapus Gambar"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
                
                <!-- Upload Button -->
                <button 
                  type="button"
                  @click="triggerFileInput"
                  class="w-full py-4 px-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-primary-500 hover:bg-primary-50 dark:hover:bg-primary-900/10 transition-colors text-center"
                >
                  <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <span class="text-sm text-gray-600 dark:text-gray-400">
                    {{ imagePreview ? 'Ganti Foto' : 'Pilih Foto' }}
                  </span>
                </button>
                <p class="mt-2 text-xs text-gray-500 text-center">Format: JPG, PNG, GIF (Max 5MB)</p>
              </div>

              <!-- Actions -->
              <div class="card p-6 space-y-3">
                <button 
                  type="submit"
                  :disabled="saving"
                  class="w-full btn-primary py-3 disabled:opacity-50 flex items-center justify-center gap-2"
                >
                  <svg v-if="saving" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ saving ? 'Menyimpan...' : (isEdit ? 'Simpan Perubahan' : 'Simpan Berita') }}
                </button>
                <button 
                  type="button"
                  @click="goBack"
                  class="w-full py-3 px-4 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600"
                >
                  Batal
                </button>
              </div>
            </div>
          </div>
        </form>
      </main>
    </div>
  </div>
</template>
