<script setup>
import { ref, onMounted, computed } from 'vue'
import { majalahStorage, initializeData } from '@/services/localStorage'
import PdfViewer from '@/components/common/PdfViewer.vue'

const loading = ref(true)
const publications = ref([])

// PDF Viewer state
const showPdfViewer = ref(false)
const selectedBook = ref(null)

// Open PDF Viewer
const openPdfViewer = (book) => {
  if (!book.file_url) {
    alert('File PDF belum tersedia untuk buku ini.')
    return
  }
  selectedBook.value = book
  showPdfViewer.value = true
}

const closePdfViewer = () => {
  showPdfViewer.value = false
  selectedBook.value = null
}

// Default data for fallback
const defaultPublications = [
  {
    id: 1,
    title: 'Panduan Parenting Anak Usia Dini',
    type: 'Buku',
    cover: 'https://placehold.co/400x600/2563eb/ffffff?text=Panduan+Parenting',
    author: 'Tim Bunda PAUD Surabaya',
    year: '2025',
    pages: 46,
    description: 'Panduan lengkap untuk orang tua dalam mendampingi tumbuh kembang anak usia dini.',
    downloadUrl: 'https://drive.google.com/file/d/1LRQ80Yll3Isb0ZuY9p9wntkjTMBz1Pha/view',
    file_url: 'https://drive.google.com/file/d/1LRQ80Yll3Isb0ZuY9p9wntkjTMBz1Pha/view?usp=sharing'
  },
  {
    id: 2,
    title: 'Majalah Bunda PAUD Edisi 12',
    type: 'Majalah',
    cover: 'https://placehold.co/400x600/16a34a/ffffff?text=Majalah+Edisi+12',
    author: 'Redaksi Bunda PAUD',
    year: 'Desember 2025',
    pages: 48,
    description: 'Edisi terbaru dengan tema "Menyambut Tahun Ajaran Baru dengan Semangat".',
    downloadUrl: '#',
    file_url: ''
  },
  {
    id: 3,
    title: 'Aktivitas Kreatif untuk PAUD',
    type: 'Buku',
    cover: 'https://placehold.co/400x600/f59e0b/ffffff?text=Aktivitas+Kreatif',
    author: 'Tim Kreatif PAUD',
    year: '2025',
    pages: 80,
    description: 'Kumpulan ide aktivitas kreatif yang dapat dilakukan di rumah dan sekolah.',
    downloadUrl: '#',
    file_url: ''
  },
  {
    id: 4,
    title: 'Majalah Bunda PAUD Edisi 11',
    type: 'Majalah',
    cover: 'https://placehold.co/400x600/8b5cf6/ffffff?text=Majalah+Edisi+11',
    author: 'Redaksi Bunda PAUD',
    year: 'November 2025',
    pages: 48,
    description: 'Edisi dengan tema "Membangun Karakter Anak Sejak Dini".',
    downloadUrl: '#',
    file_url: ''
  },
  {
    id: 5,
    title: 'Nutrisi Seimbang untuk Anak',
    type: 'Buku',
    cover: 'https://placehold.co/400x600/ec4899/ffffff?text=Nutrisi+Anak',
    author: 'Dr. Nutrisia Sehat',
    year: '2025',
    pages: 96,
    description: 'Panduan nutrisi dan resep makanan sehat untuk anak usia dini.',
    downloadUrl: '#',
    file_url: ''
  },
  {
    id: 6,
    title: 'Majalah Bunda PAUD Edisi 10',
    type: 'Majalah',
    cover: 'https://placehold.co/400x600/14b8a6/ffffff?text=Majalah+Edisi+10',
    author: 'Redaksi Bunda PAUD',
    year: 'Oktober 2025',
    pages: 48,
    description: 'Edisi spesial Hari Anak dengan berbagai artikel menarik.',
    downloadUrl: '#',
    file_url: ''
  }
]

const types = ['Semua', 'Majalah', 'Buku']
const activeType = ref('Semua')

// Load data from localStorage (consistent with admin panel)
const loadData = () => {
  loading.value = true
  try {
    initializeData()
    const data = majalahStorage.getAll()
    console.log('Majalah localStorage data:', data) // Debug log
    if (data && data.length > 0) {
      publications.value = data.map(item => ({
        id: item.id,
        title: item.title,
        type: item.category === 'majalah' ? 'Majalah' : 'Buku',
        // LocalStorage uses coverUrl
        cover: item.coverUrl || `https://placehold.co/400x600/2563eb/ffffff?text=${encodeURIComponent(item.title)}`,
        author: item.author || 'Tim Bunda PAUD',
        year: item.year || new Date().getFullYear().toString(),
        pages: item.pages || 0,
        description: item.description || '',
        // LocalStorage uses fileUrl for PDF link
        downloadUrl: item.fileUrl || '#',
        file_url: item.fileUrl || '' // For PDF viewer
      }))
    } else {
      publications.value = defaultPublications
    }
  } catch (error) {
    console.error('Failed to load publications:', error)
    publications.value = defaultPublications
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadData()
})

// Computed filtered list
const filteredPublications = computed(() => {
  if (activeType.value === 'Semua') {
    return publications.value
  }
  return publications.value.filter(item => item.type === activeType.value)
})

const filterByType = (type) => {
  activeType.value = type
}
</script>

<template>
  <div>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-purple-600 to-pink-700 text-white py-16">
      <div class="section-container text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Majalah & Buku</h1>
        <p class="text-lg text-purple-100 max-w-2xl mx-auto">
          Koleksi publikasi resmi dari Bunda PAUD Kota Surabaya yang dapat diunduh secara gratis.
        </p>
      </div>
    </section>

    <!-- Filter -->
    <section class="py-8 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
      <div class="section-container">
        <div class="flex flex-wrap gap-3 justify-center">
          <button
            v-for="type in types"
            :key="type"
            @click="filterByType(type)"
            class="px-6 py-2 rounded-full text-sm font-medium transition-all duration-300"
            :class="activeType === type 
              ? 'bg-purple-600 text-white shadow-lg shadow-purple-500/30' 
              : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-gray-600'"
          >
            {{ type }}
          </button>
        </div>
      </div>
    </section>

    <!-- Publications Grid -->
    <section class="py-16 bg-white dark:bg-gray-900">
      <div class="section-container">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          <div 
            v-for="pub in filteredPublications" 
            :key="pub.id"
            class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden"
          >
            <!-- Cover -->
            <div class="relative overflow-hidden">
              <img 
                :src="pub.cover" 
                :alt="pub.title"
                class="w-full aspect-[2/3] object-cover transition-transform duration-500 group-hover:scale-105"
              />
              <div class="absolute top-4 left-4">
                <span 
                  class="px-3 py-1 text-xs font-semibold rounded-full"
                  :class="pub.type === 'Majalah' ? 'bg-green-500 text-white' : 'bg-blue-500 text-white'"
                >
                  {{ pub.type }}
                </span>
              </div>
            </div>

            <!-- Content -->
            <div class="p-6">
              <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-2 line-clamp-2">
                {{ pub.title }}
              </h3>
              <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                {{ pub.author }} • {{ pub.year }}
              </p>
              <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">
                {{ pub.description }}
              </p>
              <div class="flex items-center justify-between gap-2">
                <span class="text-xs text-gray-400">{{ pub.pages }} halaman</span>
                <div class="flex gap-2">
                  <!-- Baca Online Button -->
                  <button 
                    v-if="pub.file_url"
                    @click="openPdfViewer(pub)"
                    class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-cyan-700 transition-all duration-300"
                    title="Baca Online"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Baca
                  </button>
                  <!-- Download Button -->
                  <a 
                    v-if="pub.downloadUrl && pub.downloadUrl !== '#'"
                    :href="pub.downloadUrl"
                    target="_blank"
                    class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-300"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Unduh
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="filteredPublications.length === 0" class="text-center py-16">
          <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
          <p class="text-gray-500 dark:text-gray-400">Tidak ada publikasi dalam kategori ini.</p>
        </div>
      </div>
    </section>

    <!-- PDF Viewer Modal -->
    <PdfViewer 
      v-if="showPdfViewer && selectedBook"
      :url="selectedBook.file_url"
      :title="selectedBook.title"
      @close="closePdfViewer"
    />
  </div>
</template>


