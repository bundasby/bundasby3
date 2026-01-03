<script setup>
import { ref, computed } from 'vue'

const searchQuery = ref('')
const selectedYear = ref('')

const peraturanList = ref([
  // Tahun 2026
  {
    id: 1,
    title: 'Peraturan Walikota Surabaya Nomor 15 Tahun 2026',
    description: 'Tentang Penyelenggaraan Pendidikan Anak Usia Dini Holistik Integratif di Kota Surabaya',
    year: 2026,
    date: '02 Jan 2026',
    type: 'Peraturan Walikota',
    size: '2.5 MB',
    downloadUrl: '#'
  },
  {
    id: 2,
    title: 'Surat Edaran Dinas Pendidikan Kota Surabaya Nomor 421/2026',
    description: 'Optimalisasi Pelaksanaan Gerakan 7 Kebiasaan Anak Indonesia Hebat di Satuan PAUD',
    year: 2026,
    date: '01 Jan 2026',
    type: 'Surat Edaran',
    size: '1.2 MB',
    downloadUrl: '#'
  },
  // Tahun 2025
  {
    id: 3,
    title: 'Peraturan Walikota Surabaya Nomor 22 Tahun 2025',
    description: 'Tentang Standar Pelayanan Minimal Pendidikan Anak Usia Dini',
    year: 2025,
    date: '15 Nov 2025',
    type: 'Peraturan Walikota',
    size: '3.1 MB',
    downloadUrl: '#'
  },
  {
    id: 4,
    title: 'Surat Keputusan Kepala Dinas Pendidikan Nomor 089 Tahun 2025',
    description: 'Penetapan Satuan PAUD Pelaksana Program Wajib Belajar 1 Tahun Prasekolah',
    year: 2025,
    date: '10 Okt 2025',
    type: 'Surat Keputusan',
    size: '1.8 MB',
    downloadUrl: '#'
  },
  {
    id: 5,
    title: 'Pedoman Peran Bunda PAUD Kota Surabaya 2025',
    description: 'Panduan lengkap tugas dan fungsi Bunda PAUD Kecamatan dan Kelurahan',
    year: 2025,
    date: '20 Jun 2025',
    type: 'Pedoman',
    size: '4.2 MB',
    downloadUrl: '#'
  },
  // Tahun 2024
  {
    id: 6,
    title: 'Peraturan Walikota Surabaya Nomor 18 Tahun 2024',
    description: 'Tentang Pengelolaan Dana Bantuan Operasional Penyelenggaraan PAUD',
    year: 2024,
    date: '05 Mar 2024',
    type: 'Peraturan Walikota',
    size: '2.8 MB',
    downloadUrl: '#'
  },
  {
    id: 7,
    title: 'Surat Edaran Wali Kota Surabaya Nomor 420/SE/2024',
    description: 'Pelaksanaan Pembelajaran Tatap Muka di Satuan PAUD',
    year: 2024,
    date: '15 Jan 2024',
    type: 'Surat Edaran',
    size: '890 KB',
    downloadUrl: '#'
  },
  // Tahun 2023
  {
    id: 8,
    title: 'Keputusan Kepala Dinas Pendidikan Nomor 045 Tahun 2023',
    description: 'Tentang Petunjuk Teknis Survei Lingkungan Belajar PAUD Kota Surabaya',
    year: 2023,
    date: '20 Sep 2023',
    type: 'Keputusan',
    size: '1.5 MB',
    downloadUrl: '#'
  }
])

const years = [...new Set(peraturanList.value.map(p => p.year))].sort((a, b) => b - a)

const filteredList = computed(() => {
  return peraturanList.value.filter(item => {
    const matchSearch = !searchQuery.value || 
      item.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchYear = !selectedYear.value || item.year === parseInt(selectedYear.value)
    return matchSearch && matchYear
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

const getTypeColor = (type) => {
  const colors = {
    'Peraturan Walikota': 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
    'Surat Edaran': 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
    'Surat Keputusan': 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
    'Keputusan': 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
    'Pedoman': 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400'
  }
  return colors[type] || 'bg-gray-100 text-gray-700'
}
</script>

<template>
  <div>
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white py-16">
      <div class="section-container">
        <div class="max-w-3xl">
          <span class="inline-block px-4 py-1 bg-white/20 rounded-full text-sm font-medium mb-4">Tata Kelola</span>
          <h1 class="text-4xl md:text-5xl font-bold mb-4">📜 Peraturan</h1>
          <p class="text-lg text-blue-100">
            Kumpulan peraturan, keputusan, dan surat edaran terkait penyelenggaraan PAUD di Kota Surabaya.
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
                placeholder="Cari peraturan..."
                class="w-full pl-12 pr-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              />
            </div>
            <select 
              v-model="selectedYear"
              class="px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Semua Tahun</option>
              <option v-for="year in years" :key="year" :value="year">Tahun {{ year }}</option>
            </select>
          </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
          <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="text-2xl font-bold text-blue-600">{{ peraturanList.length }}</div>
            <div class="text-sm text-gray-500">Total Dokumen</div>
          </div>
          <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="text-2xl font-bold text-green-600">{{ peraturanList.filter(p => p.year === 2026).length }}</div>
            <div class="text-sm text-gray-500">Tahun 2026</div>
          </div>
          <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="text-2xl font-bold text-purple-600">{{ peraturanList.filter(p => p.year === 2025).length }}</div>
            <div class="text-sm text-gray-500">Tahun 2025</div>
          </div>
          <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="text-2xl font-bold text-orange-600">{{ peraturanList.filter(p => p.year <= 2024).length }}</div>
            <div class="text-sm text-gray-500">Tahun Sebelumnya</div>
          </div>
        </div>

        <!-- Grouped List -->
        <div class="space-y-8">
          <div v-for="[year, items] in groupedByYear" :key="year">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
              <span class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400">
                📅
              </span>
              Peraturan Tahun {{ year }}
            </h2>
            
            <div class="space-y-4">
              <div 
                v-for="item in items" 
                :key="item.id"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-all group"
              >
                <div class="flex items-start gap-4">
                  <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                      <span :class="['px-2 py-1 text-xs font-medium rounded-full', getTypeColor(item.type)]">
                        {{ item.type }}
                      </span>
                      <span class="text-xs text-gray-500">{{ item.date }}</span>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-1 group-hover:text-blue-600 transition-colors">
                      {{ item.title }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                      {{ item.description }}
                    </p>
                    <div class="flex items-center gap-4">
                      <span class="text-xs text-gray-500 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                        </svg>
                        {{ item.size }}
                      </span>
                      <a :href="item.downloadUrl" class="text-blue-600 dark:text-blue-400 text-sm font-medium hover:underline flex items-center gap-1">
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
        </div>

        <!-- Empty State -->
        <div v-if="filteredList.length === 0" class="text-center py-16">
          <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="text-gray-500">Tidak ada peraturan yang ditemukan.</p>
        </div>
      </div>
    </section>
  </div>
</template>
