<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import { useSliderStore } from '@/stores/slider'

const sliderStore = useSliderStore()

// Fetch slides on mount
onMounted(async () => {
  await sliderStore.fetchAllSlides()
})

// Form state
const showForm = ref(false)
const editingSlide = ref(null)
const formData = ref({
  image: '',
  title: '',
  caption: ''
})

// Open add form
const openAddForm = () => {
  editingSlide.value = null
  formData.value = { image: '', title: '', caption: '' }
  showForm.value = true
}

// Open edit form
const openEditForm = (slide) => {
  editingSlide.value = slide
  formData.value = {
    image: slide.image,
    title: slide.title,
    caption: slide.caption
  }
  showForm.value = true
}

// Save slide
const saveSlide = async () => {
  if (!formData.value.image) {
    alert('URL gambar harus diisi!')
    return
  }
  
  if (editingSlide.value) {
    await sliderStore.updateSlide(editingSlide.value.id, formData.value)
  } else {
    await sliderStore.addSlide(formData.value)
  }
  
  showForm.value = false
  formData.value = { image: '', title: '', caption: '' }
  editingSlide.value = null
}

// Delete slide
const deleteSlide = async (id) => {
  if (confirm('Yakin ingin menghapus slide ini?')) {
    await sliderStore.deleteSlide(id)
  }
}

// Handle file upload
const handleFileUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    const reader = new FileReader()
    reader.onload = (e) => {
      formData.value.image = e.target.result
    }
    reader.readAsDataURL(file)
  }
}
</script>

<template>
  <AdminLayout title="Kelola Slider Beranda">
    <!-- Add Button -->
    <div class="mb-6">
      <button 
        @click="openAddForm"
        class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Tambah Slide
      </button>
    </div>

    <!-- Info -->
    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
      <p class="text-sm text-blue-800 dark:text-blue-200">
        <strong>Tips:</strong> Untuk gambar, Anda bisa menggunakan URL gambar dari internet atau upload file. 
        Ukuran yang disarankan adalah 1200x450 pixel.
      </p>
    </div>

    <!-- Slides Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div 
        v-for="slide in sliderStore.slides" 
        :key="slide.id"
        class="card overflow-hidden"
        :class="{ 'opacity-50': !(slide.active || slide.is_active) }"
      >
        <!-- Image Preview -->
        <div class="relative h-48 bg-gray-100 dark:bg-gray-700">
          <img 
            :src="slide.image" 
            :alt="slide.title"
            class="w-full h-full object-cover"
            @error="$event.target.src = 'https://placehold.co/600x300/cccccc/666666?text=Gambar+Error'"
          />
          <div class="absolute top-2 right-2 flex gap-2">
            <span 
              class="px-2 py-1 text-xs font-medium rounded"
              :class="slide.active ? 'bg-green-500 text-white' : 'bg-gray-500 text-white'"
            >
              {{ slide.active ? 'Aktif' : 'Nonaktif' }}
            </span>
          </div>
        </div>
        
        <!-- Info -->
        <div class="p-4">
          <h3 class="font-bold text-gray-900 dark:text-white mb-1">{{ slide.title || 'Tanpa Judul' }}</h3>
          <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ slide.caption || 'Tidak ada caption' }}</p>
          
          <!-- Actions -->
          <div class="flex gap-2">
            <button 
              @click="openEditForm(slide)"
              class="px-3 py-1 text-sm bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors"
            >
              Edit
            </button>
            <button 
              @click="sliderStore.toggleActive(slide.id)"
              class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
            >
              {{ slide.active ? 'Nonaktifkan' : 'Aktifkan' }}
            </button>
            <button 
              @click="deleteSlide(slide.id)"
              class="px-3 py-1 text-sm bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors"
            >
              Hapus
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="sliderStore.slides.length === 0" class="text-center py-12">
      <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
      </svg>
      <p class="text-gray-600 dark:text-gray-400">Belum ada slide. Klik "Tambah Slide" untuk menambahkan.</p>
    </div>

    <!-- Form Modal -->
    <div v-if="showForm" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
      <div class="bg-white dark:bg-gray-800 rounded-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white">
            {{ editingSlide ? 'Edit Slide' : 'Tambah Slide Baru' }}
          </h2>
        </div>
        
        <div class="p-6 space-y-4">
          <!-- Image URL -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              URL Gambar <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="formData.image"
              type="text"
              placeholder="https://example.com/gambar.jpg"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
          </div>
          
          <!-- File Upload -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Atau Upload File
            </label>
            <input 
              type="file"
              accept="image/*"
              @change="handleFileUpload"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
            />
          </div>
          
          <!-- Preview -->
          <div v-if="formData.image" class="mt-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preview</label>
            <img :src="formData.image" class="w-full h-32 object-cover rounded-lg" />
          </div>
          
          <!-- Title -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Judul (Opsional)
            </label>
            <input 
              v-model="formData.title"
              type="text"
              placeholder="Judul slide"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
          </div>
          
          <!-- Caption -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Caption (Opsional)
            </label>
            <textarea 
              v-model="formData.caption"
              rows="2"
              placeholder="Deskripsi singkat"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            ></textarea>
          </div>
        </div>
        
        <div class="p-6 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
          <button 
            @click="showForm = false"
            class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
          >
            Batal
          </button>
          <button 
            @click="saveSlide"
            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
          >
            Simpan
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
