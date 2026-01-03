<script setup>
import { ref, computed } from 'vue'

const searchQuery = ref('')
const selectedYear = ref('')
const selectedType = ref('')

const laporanList = ref([
  // Tahun 2026
  {
    id: 1,
    title: 'Laporan Kinerja Bunda PAUD Kota Surabaya Semester 1 Tahun 2026',
    description: 'Rangkuman pencapaian program kerja dan kegiatan Bunda PAUD periode Januari - Juni 2026',
    year: 2026,
    date: '01 Jan 2026',
    type: 'Laporan Kinerja',
    status: 'Draft',
    size: '5.2 MB',
    downloadUrl: '#'
  },
  // Tahun 2025
  {
    id: 2,
    title: 'Laporan Tahunan Bunda PAUD Kota Surabaya 2025',
    description: 'Laporan capaian program kerja, kegiatan, dan evaluasi Bunda PAUD sepanjang tahun 2025',
    year: 2025,
    date: '31 Des 2025',
    type: 'Laporan Tahunan',
    status: 'Final',
    size: '12.5 MB',
    downloadUrl: '#'
  },
  {
    id: 3,
    title: 'Laporan Pelaksanaan Gebyar PAUD 2025',
    description: 'Dokumentasi dan evaluasi kegiatan Gebyar PAUD tingkat Kota Surabaya',
    year: 2025,
    date: '20 Okt 2025',
    type: 'Laporan Kegiatan',
    status: 'Final',
    size: '8.3 MB',
    downloadUrl: '#'
  },
  {
    id: 4,
    title: 'Laporan Implementasi Gerakan 7 KAIH',
    description: 'Hasil monitoring dan evaluasi pelaksanaan Gerakan 7 Kebiasaan Anak Indonesia Hebat',
    year: 2025,
    date: '15 Sep 2025',
    type: 'Laporan Monev',
    status: 'Final',
    size: '4.7 MB',
    downloadUrl: '#'
  },
  {
    id: 5,
    title: 'Laporan Survei Lingkungan Belajar PAUD 2025',
    description: 'Hasil survei dan analisis kondisi lingkungan belajar satuan PAUD di Kota Surabaya',
    year: 2025,
    date: '30 Jun 2025',
    type: 'Laporan Survei',
    status: 'Final',
    size: '6.1 MB',
    downloadUrl: '#'
  },
  // Tahun 2024
  {
    id: 6,
    title: 'Laporan Tahunan Bunda PAUD Kota Surabaya 2024',
    description: 'Laporan capaian program kerja dan evaluasi kinerja Bunda PAUD tahun 2024',
    year: 2024,
    date: '31 Des 2024',
    type: 'Laporan Tahunan',
    status: 'Final',
    size: '11.2 MB',
    downloadUrl: '#'
  },
  {
    id: 7,
    title: 'Laporan Rapor Pendidikan PAUD 2024',
    description: 'Analisis hasil rapor pendidikan satuan PAUD di Kota Surabaya',
    year: 2024,
    date: '20 Nov 2024',
    type: 'Laporan Analisis',
    status: 'Final',
    size: '7.8 MB',
    downloadUrl: '#'
  },
  {
    id: 8,
    title: 'Laporan Pelaksanaan PAUD HI 2024',
    description: 'Evaluasi implementasi PAUD Holistik Integratif di satuan pendidikan',
    year: 2024,
    date: '15 Aug 2024',
    type: 'Laporan Monev',
    status: 'Final',
    size: '5.5 MB',
    downloadUrl: '#'
  },
  // Tahun 2023
  {
    id: 9,
    title: 'Laporan Tahunan Bunda PAUD Kota Surabaya 2023',
    description: 'Laporan kinerja dan capaian program Bunda PAUD tahun 2023',
    year: 2023,
    date: '31 Des 2023',
    type: 'Laporan Tahunan',
    status: 'Final',
    size: '10.8 MB',
    downloadUrl: '#'
  }
])

const years = [...new Set(laporanList.value.map(l => l.year))].sort((a, b) => b - a)
const types = [...new Set(laporanList.value.map(l => l.type))]

const filteredList = computed(() => {
  return laporanList.value.filter(item => {
    const matchSearch = !searchQuery.value || 
      item.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchYear = !selectedYear.value || item.year === parseInt(selectedYear.value)
    const matchType = !selectedType.value || item.type === selectedType.value
    return matchSearch && matchYear && matchType
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

const getStatusColor = (status) => {
  return status === 'Final' 
    ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
    : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400'
}

const getTypeIcon = (type) => {
  const icons = {
    'Laporan Tahunan': '📊',
    'Laporan Kinerja': '📈',
    'Laporan Kegiatan': '🎉',
    'Laporan Monev': '🔍',
    'Laporan Survei': '📋',
    'Laporan Analisis': '📉'
  }
  return icons[type] || '📄'
}
</script>

<template>
  <div>
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-purple-600 via-violet-700 to-indigo-800 text-white py-16">
      <div class="section-container">
        <div class="max-w-3xl">
          <span class="inline-block px-4 py-1 bg-white/20 rounded-full text-sm font-medium mb-4">Tata Kelola</span>
          <h1 class="text-4xl md:text-5xl font-bold mb-4">📥 Laporan</h1>
          <p class="text-lg text-purple-100">
            Kumpulan laporan kinerja, kegiatan, dan evaluasi program Bunda PAUD Kota Surabaya.
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
                placeholder="Cari laporan..."
                class="w-full pl-12 pr-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
              />
            </div>
            <select 
              v-model="selectedType"
              class="px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-purple-500"
            >
              <option value="">Semua Jenis</option>
              <option v-for="type in types" :key="type" :value="type">{{ type }}</option>
            </select>
            <select 
              v-model="selectedYear"
              class="px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-purple-500"
            >
              <option value="">Semua Tahun</option>
              <option v-for="year in years" :key="year" :value="year">Tahun {{ year }}</option>
            </select>
          </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
          <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="text-2xl font-bold text-purple-600">{{ laporanList.length }}</div>
            <div class="text-sm text-gray-500">Total Laporan</div>
          </div>
          <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="text-2xl font-bold text-green-600">{{ laporanList.filter(l => l.status === 'Final').length }}</div>
            <div class="text-sm text-gray-500">Final</div>
          </div>
          <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="text-2xl font-bold text-blue-600">{{ laporanList.filter(l => l.type === 'Laporan Tahunan').length }}</div>
            <div class="text-sm text-gray-500">Laporan Tahunan</div>
          </div>
          <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="text-2xl font-bold text-orange-600">{{ years.length }}</div>
            <div class="text-sm text-gray-500">Tahun Laporan</div>
          </div>
        </div>

        <!-- Grouped List -->
        <div class="space-y-8">
          <div v-for="[year, items] in groupedByYear" :key="year">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
              <span class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center text-purple-600 dark:text-purple-400">
                📅
              </span>
              Laporan Tahun {{ year }}
            </h2>
            
            <div class="space-y-4">
              <div 
                v-for="item in items" 
                :key="item.id"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-all group"
              >
                <div class="flex items-start gap-4">
                  <div class="w-14 h-14 bg-gradient-to-br from-purple-100 to-violet-100 dark:from-purple-900/30 dark:to-violet-900/30 rounded-xl flex items-center justify-center text-2xl flex-shrink-0 group-hover:scale-110 transition-transform">
                    {{ getTypeIcon(item.type) }}
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                      <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                        {{ item.type }}
                      </span>
                      <span :class="['px-2 py-1 text-xs font-medium rounded-full', getStatusColor(item.status)]">
                        {{ item.status }}
                      </span>
                      <span class="text-xs text-gray-500">{{ item.date }}</span>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-1 group-hover:text-purple-600 transition-colors">
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
                      <a 
                        v-if="item.status === 'Final'"
                        :href="item.downloadUrl" 
                        class="text-purple-600 dark:text-purple-400 text-sm font-medium hover:underline flex items-center gap-1"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Unduh Laporan
                      </a>
                      <span v-else class="text-yellow-600 text-sm">Menunggu finalisasi</span>
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
          <p class="text-gray-500">Tidak ada laporan yang ditemukan.</p>
        </div>
      </div>
    </section>
  </div>
</template>
