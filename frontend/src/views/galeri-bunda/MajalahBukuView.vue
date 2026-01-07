<script setup>
import { ref, onMounted, computed } from 'vue'
import { majalahService } from '@/services'

const loading = ref(true)
const publications = ref([])

// Default data for fallback
const defaultPublications = [
  {
    id: 1,
    title: 'Panduan Parenting Anak Usia Dini',
    type: 'Buku',
    cover: 'https://placehold.co/400x600/2563eb/ffffff?text=Panduan+Parenting',
    author: 'Tim Bunda PAUD Surabaya',
    year: '2025',
    pages: 120,
    description: 'Panduan lengkap untuk orang tua dalam mendampingi tumbuh kembang anak usia dini.',
    downloadUrl: '#'
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
    downloadUrl: '#'
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
    downloadUrl: '#'
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
    downloadUrl: '#'
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
    downloadUrl: '#'
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
    downloadUrl: '#'
  }
]

const types = ['Semua', 'Majalah', 'Buku']
const activeType = ref('Semua')

// Load data from API
const loadData = async () => {
  loading.value = true
  try {
    const response = await majalahService.getAll()
    if (response.success && response.data && response.data.length > 0) {
      publications.value = response.data.map(item => ({
        id: item.id,
        title: item.title,
        type: item.type || 'Buku',
        cover: item.cover_image || `https://placehold.co/400x600/2563eb/ffffff?text=${encodeURIComponent(item.title)}`,
        author: item.author || 'Tim Bunda PAUD',
        year: item.year || new Date().getFullYear().toString(),
        pages: item.pages || 0,
        description: item.description || '',
        downloadUrl: item.file_url || '#'
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
              <div class="flex items-center justify-between">
                <span class="text-xs text-gray-400">{{ pub.pages }} halaman</span>
                <a 
                  :href="pub.downloadUrl"
                  class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-300"
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

        <!-- Empty State -->
        <div v-if="filteredPublications.length === 0" class="text-center py-16">
          <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
          <p class="text-gray-500 dark:text-gray-400">Tidak ada publikasi dalam kategori ini.</p>
        </div>
      </div>
    </section>
  </div>
</template>
