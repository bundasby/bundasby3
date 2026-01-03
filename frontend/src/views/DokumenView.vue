<script setup>
import { ref } from 'vue'

const activeCategory = ref('semua')

const categories = [
  { id: 'semua', name: 'Semua' },
  { id: 'kegiatan', name: 'Dokumen Kegiatan' },
  { id: 'regulasi', name: 'Regulasi' },
  { id: 'anggaran', name: 'Anggaran & Keuangan' },
  { id: 'monitoring', name: 'Monitoring & Evaluasi' }
]

const documents = ref([
  {
    id: 1,
    title: 'SK Pengangkatan Pokja Bunda PAUD 2025-2030',
    category: 'regulasi',
    fileType: 'PDF',
    fileSize: '2.5 MB',
    date: '2025-01-15'
  },
  {
    id: 2,
    title: 'Laporan Kegiatan Konferensi Bunda PAUD 2025',
    category: 'kegiatan',
    fileType: 'PDF',
    fileSize: '5.2 MB',
    date: '2025-10-20'
  },
  {
    id: 3,
    title: 'Rencana Anggaran Biaya 2025',
    category: 'anggaran',
    fileType: 'XLSX',
    fileSize: '1.8 MB',
    date: '2025-01-10'
  },
  {
    id: 4,
    title: 'Laporan Monitoring Program MBG Q4 2025',
    category: 'monitoring',
    fileType: 'PDF',
    fileSize: '3.1 MB',
    date: '2025-12-15'
  },
  {
    id: 5,
    title: 'Pedoman Penyelenggaraan PAUD Holistik Integratif',
    category: 'regulasi',
    fileType: 'PDF',
    fileSize: '4.7 MB',
    date: '2025-03-01'
  },
  {
    id: 6,
    title: 'Dokumentasi Pelatihan Guru PAUD',
    category: 'kegiatan',
    fileType: 'ZIP',
    fileSize: '25 MB',
    date: '2025-08-10'
  }
])

const filteredDocuments = ref([])

const filterDocuments = () => {
  if (activeCategory.value === 'semua') {
    filteredDocuments.value = documents.value
  } else {
    filteredDocuments.value = documents.value.filter(doc => doc.category === activeCategory.value)
  }
}

filterDocuments()

const setCategory = (categoryId) => {
  activeCategory.value = categoryId
  filterDocuments()
}

const getFileIcon = (fileType) => {
  switch (fileType) {
    case 'PDF': return 'pdf'
    case 'XLSX': return 'excel'
    case 'ZIP': return 'zip'
    default: return 'file'
  }
}

const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString('id-ID', { 
    day: 'numeric', 
    month: 'long', 
    year: 'numeric' 
  })
}
</script>

<template>
  <div>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-primary-600 to-primary-800 text-white py-16">
      <div class="section-container text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Dokumen</h1>
        <p class="text-lg text-blue-100 max-w-2xl mx-auto">
          Kumpulan dokumen resmi, regulasi, dan laporan kegiatan Bunda PAUD Kota Surabaya.
        </p>
      </div>
    </section>

    <!-- Filter & Documents -->
    <section class="py-16 bg-white dark:bg-gray-900">
      <div class="section-container">
        <!-- Category Tabs -->
        <div class="flex flex-wrap gap-2 mb-8">
          <button
            v-for="category in categories"
            :key="category.id"
            @click="setCategory(category.id)"
            class="px-4 py-2 rounded-full text-sm font-medium transition-colors"
            :class="activeCategory === category.id 
              ? 'bg-primary-600 text-white' 
              : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700'"
          >
            {{ category.name }}
          </button>
        </div>

        <!-- Documents Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="doc in filteredDocuments" 
            :key="doc.id"
            class="card p-6 hover:border-primary-300 dark:hover:border-primary-600 border-2 border-transparent transition-colors"
          >
            <!-- File Icon -->
            <div class="flex items-start gap-4 mb-4">
              <div 
                class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0"
                :class="{
                  'bg-red-100 text-red-600': doc.fileType === 'PDF',
                  'bg-green-100 text-green-600': doc.fileType === 'XLSX',
                  'bg-yellow-100 text-yellow-600': doc.fileType === 'ZIP'
                }"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-1 line-clamp-2">
                  {{ doc.title }}
                </h3>
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                  <span class="font-medium">{{ doc.fileType }}</span>
                  <span>•</span>
                  <span>{{ doc.fileSize }}</span>
                </div>
              </div>
            </div>

            <!-- Date & Download -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
              <span class="text-sm text-gray-500 dark:text-gray-400">
                {{ formatDate(doc.date) }}
              </span>
              <button class="inline-flex items-center gap-1 text-sm text-primary-600 dark:text-primary-400 font-medium hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Unduh
              </button>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="filteredDocuments.length === 0" class="text-center py-12">
          <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <p class="text-gray-500 dark:text-gray-400">Tidak ada dokumen dalam kategori ini.</p>
        </div>
      </div>
    </section>
  </div>
</template>
