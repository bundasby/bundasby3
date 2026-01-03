<script setup>
import { ref } from 'vue'

const materials = ref([
  {
    id: 1,
    title: 'Modul Pembelajaran Membaca untuk Anak',
    category: 'Literasi',
    icon: '📚',
    fileType: 'PDF',
    fileSize: '2.5 MB',
    description: 'Panduan lengkap metode pembelajaran membaca yang menyenangkan untuk anak usia dini.',
    downloadUrl: '#'
  },
  {
    id: 2,
    title: 'Lembar Kerja Mengenal Angka 1-20',
    category: 'Numerasi',
    icon: '🔢',
    fileType: 'PDF',
    fileSize: '1.8 MB',
    description: 'Kumpulan lembar kerja untuk mengenalkan konsep angka kepada anak.',
    downloadUrl: '#'
  },
  {
    id: 3,
    title: 'Template RPP PAUD Semester 1',
    category: 'Kurikulum',
    icon: '📋',
    fileType: 'DOCX',
    fileSize: '500 KB',
    description: 'Template Rencana Pelaksanaan Pembelajaran untuk semester ganjil.',
    downloadUrl: '#'
  },
  {
    id: 4,
    title: 'Kumpulan Lagu Anak PAUD',
    category: 'Seni',
    icon: '🎵',
    fileType: 'MP3',
    fileSize: '45 MB',
    description: 'Koleksi lagu-lagu edukatif untuk kegiatan pembelajaran.',
    downloadUrl: '#'
  },
  {
    id: 5,
    title: 'Panduan Stimulasi Motorik Halus',
    category: 'Perkembangan',
    icon: '✋',
    fileType: 'PDF',
    fileSize: '3.2 MB',
    description: 'Aktivitas untuk mengembangkan kemampuan motorik halus anak.',
    downloadUrl: '#'
  },
  {
    id: 6,
    title: 'Kartu Flashcard Huruf dan Gambar',
    category: 'Literasi',
    icon: '🃏',
    fileType: 'PDF',
    fileSize: '5.1 MB',
    description: 'Flashcard untuk belajar huruf alfabet dengan gambar menarik.',
    downloadUrl: '#'
  },
  {
    id: 7,
    title: 'Video Tutorial Kerajinan Tangan',
    category: 'Seni',
    icon: '🎨',
    fileType: 'MP4',
    fileSize: '120 MB',
    description: 'Tutorial membuat kerajinan tangan sederhana untuk anak PAUD.',
    downloadUrl: '#'
  },
  {
    id: 8,
    title: 'Panduan Penilaian Perkembangan Anak',
    category: 'Kurikulum',
    icon: '📊',
    fileType: 'PDF',
    fileSize: '1.2 MB',
    description: 'Instrumen penilaian perkembangan anak usia dini.',
    downloadUrl: '#'
  }
])

const categories = ['Semua', 'Literasi', 'Numerasi', 'Kurikulum', 'Seni', 'Perkembangan']
const activeCategory = ref('Semua')

const filteredMaterials = ref([...materials.value])

const filterByCategory = (category) => {
  activeCategory.value = category
  if (category === 'Semua') {
    filteredMaterials.value = [...materials.value]
  } else {
    filteredMaterials.value = materials.value.filter(item => item.category === category)
  }
}

const getFileTypeColor = (type) => {
  const colors = {
    'PDF': 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400',
    'DOCX': 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
    'MP3': 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400',
    'MP4': 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400'
  }
  return colors[type] || 'bg-gray-100 text-gray-600'
}
</script>

<template>
  <div>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-teal-600 to-cyan-700 text-white py-16">
      <div class="section-container text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Materi Pembelajaran</h1>
        <p class="text-lg text-teal-100 max-w-2xl mx-auto">
          Kumpulan materi dan modul pembelajaran untuk mendukung pendidikan anak usia dini.
        </p>
      </div>
    </section>

    <!-- Filter -->
    <section class="py-8 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
      <div class="section-container">
        <div class="flex flex-wrap gap-3 justify-center">
          <button
            v-for="category in categories"
            :key="category"
            @click="filterByCategory(category)"
            class="px-6 py-2 rounded-full text-sm font-medium transition-all duration-300"
            :class="activeCategory === category 
              ? 'bg-teal-600 text-white shadow-lg shadow-teal-500/30' 
              : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-gray-600'"
          >
            {{ category }}
          </button>
        </div>
      </div>
    </section>

    <!-- Materials List -->
    <section class="py-16 bg-white dark:bg-gray-900">
      <div class="section-container">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div 
            v-for="material in filteredMaterials" 
            :key="material.id"
            class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border border-gray-100 dark:border-gray-700"
          >
            <div class="flex items-start gap-4">
              <!-- Icon -->
              <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-teal-100 to-cyan-100 dark:from-teal-900/30 dark:to-cyan-900/30 rounded-2xl flex items-center justify-center text-3xl">
                {{ material.icon }}
              </div>

              <!-- Content -->
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-3 mb-2">
                  <h3 class="font-bold text-gray-900 dark:text-white line-clamp-2">
                    {{ material.title }}
                  </h3>
                  <span 
                    class="flex-shrink-0 px-2 py-1 text-xs font-medium rounded"
                    :class="getFileTypeColor(material.fileType)"
                  >
                    {{ material.fileType }}
                  </span>
                </div>
                <p class="text-sm text-primary-600 dark:text-primary-400 mb-2">{{ material.category }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">
                  {{ material.description }}
                </p>
                <div class="flex items-center justify-between">
                  <span class="text-xs text-gray-400">{{ material.fileSize }}</span>
                  <a 
                    :href="material.downloadUrl"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-teal-600 to-cyan-600 text-white text-sm font-medium rounded-lg hover:from-teal-700 hover:to-cyan-700 transition-all duration-300"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Unduh
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="filteredMaterials.length === 0" class="text-center py-16">
          <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <p class="text-gray-500 dark:text-gray-400">Tidak ada materi dalam kategori ini.</p>
        </div>
      </div>
    </section>
  </div>
</template>
