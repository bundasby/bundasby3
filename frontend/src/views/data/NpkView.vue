<script setup>
import { ref, computed } from 'vue'

const searchQuery = ref('')
const selectedYear = ref('')
const selectedCategory = ref('')

const npkList = ref([
  // Tahun 2026
  {
    id: 1,
    title: 'Panduan Wajib Belajar 1 Tahun Prasekolah',
    description: 'Pedoman penyelenggaraan program wajib belajar 1 tahun prasekolah di Kota Surabaya',
    year: 2026,
    date: '02 Jan 2026',
    category: 'Panduan',
    icon: '📘',
    downloadUrl: '#'
  },
  {
    id: 2,
    title: 'Pedoman Peran Bunda PAUD Kota Surabaya 2026',
    description: 'Panduan lengkap tugas, fungsi, dan peran Bunda PAUD tingkat Kecamatan dan Kelurahan',
    year: 2026,
    date: '01 Jan 2026',
    category: 'Pedoman',
    icon: '👩‍👧',
    downloadUrl: '#'
  },
  // Tahun 2025
  {
    id: 3,
    title: 'Buku Saku Gerakan 7 KAIH',
    description: 'Panduan implementasi Gerakan 7 Kebiasaan Anak Indonesia Hebat untuk orang tua dan guru PAUD',
    year: 2025,
    date: '20 Nov 2025',
    category: 'Buku Saku',
    icon: '📕',
    downloadUrl: '#'
  },
  {
    id: 4,
    title: 'SOP Pelaksanaan PAUD Holistik Integratif',
    description: 'Standar Operasional Prosedur penyelenggaraan PAUD HI di satuan pendidikan',
    year: 2025,
    date: '15 Okt 2025',
    category: 'SOP',
    icon: '📋',
    downloadUrl: '#'
  },
  {
    id: 5,
    title: 'Panduan Literasi Anak Usia Dini',
    description: 'Panduan pengembangan kemampuan literasi untuk anak usia 5-6 tahun',
    year: 2025,
    date: '10 Sep 2025',
    category: 'Panduan',
    icon: '📚',
    downloadUrl: '#'
  },
  {
    id: 6,
    title: 'Buku Saku Numerasi Anak Usia Dini',
    description: 'Panduan pengembangan kemampuan numerasi untuk orang tua dan pendidik PAUD',
    year: 2025,
    date: '10 Sep 2025',
    category: 'Buku Saku',
    icon: '🔢',
    downloadUrl: '#'
  },
  {
    id: 7,
    title: 'Kriteria Penilaian Kinerja Bunda PAUD',
    description: 'Indikator dan kriteria evaluasi kinerja Bunda PAUD Kecamatan dan Kelurahan',
    year: 2025,
    date: '01 Jun 2025',
    category: 'Kriteria',
    icon: '📊',
    downloadUrl: '#'
  },
  // Tahun 2024
  {
    id: 8,
    title: 'Panduan Capaian Pembelajaran Fase Fondasi',
    description: 'Pedoman implementasi kurikulum merdeka pada jenjang PAUD',
    year: 2024,
    date: '15 Mar 2024',
    category: 'Panduan',
    icon: '🎯',
    downloadUrl: '#'
  },
  {
    id: 9,
    title: 'Juknis Survei Lingkungan Belajar PAUD',
    description: 'Petunjuk teknis pelaksanaan survei lingkungan belajar di satuan PAUD',
    year: 2024,
    date: '10 Feb 2024',
    category: 'Juknis',
    icon: '📝',
    downloadUrl: '#'
  },
  {
    id: 10,
    title: 'Pedoman BOP PAUD ABK',
    description: 'Pedoman bantuan operasional penyelenggaraan PAUD untuk Anak Berkebutuhan Khusus',
    year: 2024,
    date: '05 Jan 2024',
    category: 'Pedoman',
    icon: '💝',
    downloadUrl: '#'
  },
  // Tahun 2023
  {
    id: 11,
    title: 'Panduan Forum Komunikasi PAUD-SD',
    description: 'Panduan penyelenggaraan forum komunikasi transisi PAUD ke SD',
    year: 2023,
    date: '20 Nov 2023',
    category: 'Panduan',
    icon: '🤝',
    downloadUrl: '#'
  },
  {
    id: 12,
    title: 'NPK Penyusunan Kurikulum Tingkat Satuan Pendidikan PAUD',
    description: 'Norma dan prosedur penyusunan KTSP untuk satuan PAUD',
    year: 2023,
    date: '15 Aug 2023',
    category: 'NPK',
    icon: '📄',
    downloadUrl: '#'
  }
])

const years = [...new Set(npkList.value.map(n => n.year))].sort((a, b) => b - a)
const categories = [...new Set(npkList.value.map(n => n.category))]

const filteredList = computed(() => {
  return npkList.value.filter(item => {
    const matchSearch = !searchQuery.value || 
      item.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchYear = !selectedYear.value || item.year === parseInt(selectedYear.value)
    const matchCategory = !selectedCategory.value || item.category === selectedCategory.value
    return matchSearch && matchYear && matchCategory
  })
})

const groupedByYear = computed(() => {
  const groups = {}
  filteredList.value.forEach(item => {
    if (!groups[item.year]) {
      groups[item.year] = []
    }
    groups[item.year].push(item)
  })
  return Object.entries(groups).sort((a, b) => b[0] - a[0])
})

const getCategoryColor = (category) => {
  const colors = {
    'Panduan': 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
    'Pedoman': 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
    'Buku Saku': 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
    'SOP': 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
    'Kriteria': 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400',
    'Juknis': 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
    'NPK': 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/30 dark:text-cyan-400'
  }
  return colors[category] || 'bg-gray-100 text-gray-700'
}
</script>

<template>
  <div>
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-emerald-600 via-teal-700 to-cyan-800 text-white py-16">
      <div class="section-container">
        <div class="max-w-3xl">
          <span class="inline-block px-4 py-1 bg-white/20 rounded-full text-sm font-medium mb-4">Tata Kelola</span>
          <h1 class="text-4xl md:text-5xl font-bold mb-4">📋 Norma, Prosedur, Kriteria (NPK)</h1>
          <p class="text-lg text-emerald-100">
            Kumpulan panduan, pedoman, SOP, dan standar pelaksanaan program PAUD di Kota Surabaya.
          </p>
        </div>
      </div>
    </section>

    <!-- Main Content -->
    <section class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
      <div class="section-container">
        <!-- Search & Filter -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8">
          <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
              <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input 
                v-model="searchQuery"
                type="text"
                placeholder="Cari dokumen NPK..."
                class="w-full pl-12 pr-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
              />
            </div>
            <select 
              v-model="selectedCategory"
              class="px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-emerald-500"
            >
              <option value="">Semua Kategori</option>
              <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
            </select>
            <select 
              v-model="selectedYear"
              class="px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-emerald-500"
            >
              <option value="">Semua Tahun</option>
              <option v-for="year in years" :key="year" :value="year">Tahun {{ year }}</option>
            </select>
          </div>
        </div>

        <!-- Category Stats -->
        <div class="flex flex-wrap gap-3 mb-8">
          <button 
            @click="selectedCategory = ''"
            :class="['px-4 py-2 rounded-full text-sm font-medium transition-all', !selectedCategory ? 'bg-emerald-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-emerald-500']"
          >
            Semua ({{ npkList.length }})
          </button>
          <button 
            v-for="cat in categories" 
            :key="cat"
            @click="selectedCategory = cat"
            :class="['px-4 py-2 rounded-full text-sm font-medium transition-all', selectedCategory === cat ? 'bg-emerald-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-emerald-500']"
          >
            {{ cat }} ({{ npkList.filter(n => n.category === cat).length }})
          </button>
        </div>

        <!-- Grouped List -->
        <div class="space-y-8">
          <div v-for="[year, items] in groupedByYear" :key="year">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
              <span class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                📅
              </span>
              NPK Tahun {{ year }}
            </h2>
            
            <div class="grid md:grid-cols-2 gap-4">
              <div 
                v-for="item in items" 
                :key="item.id"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-all group"
              >
                <div class="flex items-start gap-4">
                  <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-900/30 dark:to-teal-900/30 rounded-xl flex items-center justify-center text-2xl flex-shrink-0 group-hover:scale-110 transition-transform">
                    {{ item.icon }}
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                      <span :class="['px-2 py-1 text-xs font-medium rounded-full', getCategoryColor(item.category)]">
                        {{ item.category }}
                      </span>
                      <span class="text-xs text-gray-500">{{ item.date }}</span>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-1 group-hover:text-emerald-600 transition-colors text-sm">
                      {{ item.title }}
                    </h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                      {{ item.description }}
                    </p>
                    <a :href="item.downloadUrl" class="text-emerald-600 dark:text-emerald-400 text-sm font-medium hover:underline flex items-center gap-1">
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
        </div>

        <!-- Empty State -->
        <div v-if="filteredList.length === 0" class="text-center py-16">
          <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="text-gray-500">Tidak ada dokumen yang ditemukan.</p>
        </div>
      </div>
    </section>
  </div>
</template>
