<script setup>
import { ref, computed } from 'vue'
import DataLayout from '@/components/layout/DataLayout.vue'

const searchQuery = ref('')
const selectedKecamatan = ref('')
const selectedKelurahan = ref('')
const selectedJenjang = ref('')
const currentPage = ref(1)
const itemsPerPage = 10

// Sample data sekolah PAUD
const sekolahList = ref([
  { id: 1, npsn: '20560428', nama: 'TK Kemala Bhayangkari 5', alamat: 'Jl. Embong Sawo No 20', kecamatan: 'Genteng', kelurahan: 'Embong Kaliasin', jenjang: 'TK', telepon: '081344069438' },
  { id: 2, npsn: '20560430', nama: 'TK Putra Bhakti', alamat: 'Jl. Embong Blimbing No 38', kecamatan: 'Genteng', kelurahan: 'Embong Kaliasin', jenjang: 'TK', telepon: '081703319000' },
  { id: 3, npsn: '20560431', nama: 'TK Among Siswa', alamat: 'Jl. Keputran Kejambon II/5', kecamatan: 'Genteng', kelurahan: 'Embong Kaliasin', jenjang: 'TK', telepon: '085100176089' },
  { id: 4, npsn: '20560432', nama: 'TK Anita', alamat: 'Keputran Pasar Kecil IV/37', kecamatan: 'Genteng', kelurahan: 'Embong Kaliasin', jenjang: 'TK', telepon: '082257316644' },
  { id: 5, npsn: '20560433', nama: 'TK Dharma Putra', alamat: 'Keputran Panjunan II/25', kecamatan: 'Genteng', kelurahan: 'Embong Kaliasin', jenjang: 'TK', telepon: '085810344957' },
  { id: 6, npsn: '69763366', nama: 'TK Happy Family School', alamat: 'Jl. Embong Sawo 2 Kav 3-7', kecamatan: 'Genteng', kelurahan: 'Embong Kaliasin', jenjang: 'TK', telepon: '0315327799' },
  { id: 7, npsn: '69763604', nama: 'KB Happy Family School', alamat: 'Embong Sawo No. 2 Kavling 3-7', kecamatan: 'Genteng', kelurahan: 'Embong Kaliasin', jenjang: 'KB', telepon: '0315327799' },
  { id: 8, npsn: '20560447', nama: 'TK Manguni', alamat: 'Simpang Dukuh No.5', kecamatan: 'Genteng', kelurahan: 'Embong Kaliasin', jenjang: 'TK', telepon: '0313821612' },
  { id: 9, npsn: '20560484', nama: 'TK Islam Terpadu Wildani', alamat: 'Jl. Keputran Kejambon 1/72', kecamatan: 'Genteng', kelurahan: 'Embong Kaliasin', jenjang: 'TK', telepon: '0315320941' },
  { id: 10, npsn: '20560501', nama: 'TK Negeri Pembina', alamat: 'Jl. Raya Kendangsari No.1', kecamatan: 'Tenggilis Mejoyo', kelurahan: 'Kendangsari', jenjang: 'TK', telepon: '0318490123' },
  { id: 11, npsn: '20560502', nama: 'TK Al-Ikhlas', alamat: 'Jl. Semolowaru No.45', kecamatan: 'Sukolilo', kelurahan: 'Semolowaru', jenjang: 'TK', telepon: '0315991234' },
  { id: 12, npsn: '20560503', nama: 'KB Tunas Harapan', alamat: 'Jl. Mulyorejo No.12', kecamatan: 'Mulyorejo', kelurahan: 'Mulyorejo', jenjang: 'KB', telepon: '0315921234' },
  { id: 13, npsn: '20560504', nama: 'TK Dharma Wanita', alamat: 'Jl. Gubeng Jaya No.8', kecamatan: 'Gubeng', kelurahan: 'Gubeng', jenjang: 'TK', telepon: '0315031234' },
  { id: 14, npsn: '20560505', nama: 'PAUD Ceria', alamat: 'Jl. Rungkut Asri No.22', kecamatan: 'Rungkut', kelurahan: 'Rungkut Kidul', jenjang: 'PAUD', telepon: '0318711234' },
  { id: 15, npsn: '20560506', nama: 'TK Aisyiyah', alamat: 'Jl. Wonokromo No.15', kecamatan: 'Wonokromo', kelurahan: 'Wonokromo', jenjang: 'TK', telepon: '0318411234' },
  { id: 16, npsn: '20560507', nama: 'KB Pelangi', alamat: 'Jl. Sawahan No.33', kecamatan: 'Sawahan', kelurahan: 'Sawahan', jenjang: 'KB', telepon: '0315321234' },
  { id: 17, npsn: '20560508', nama: 'TK Kartini', alamat: 'Jl. Genteng Kali No.5', kecamatan: 'Genteng', kelurahan: 'Genteng', jenjang: 'TK', telepon: '0315341234' },
  { id: 18, npsn: '20560509', nama: 'SPS Bintang Kecil', alamat: 'Jl. Tegalsari No.10', kecamatan: 'Tegalsari', kelurahan: 'Tegalsari', jenjang: 'SPS', telepon: '0315471234' },
  { id: 19, npsn: '20560510', nama: 'TPA Kasih Ibu', alamat: 'Jl. Tambaksari No.88', kecamatan: 'Tambaksari', kelurahan: 'Tambaksari', jenjang: 'TPA', telepon: '0315701234' },
  { id: 20, npsn: '20560511', nama: 'TK Harapan Bangsa', alamat: 'Jl. Simokerto No.25', kecamatan: 'Simokerto', kelurahan: 'Simokerto', jenjang: 'TK', telepon: '0315901234' },
])

// Get unique values for filters
const kecamatanList = computed(() => [...new Set(sekolahList.value.map(s => s.kecamatan))].sort())
const kelurahanList = computed(() => {
  if (!selectedKecamatan.value) return [...new Set(sekolahList.value.map(s => s.kelurahan))].sort()
  return [...new Set(sekolahList.value.filter(s => s.kecamatan === selectedKecamatan.value).map(s => s.kelurahan))].sort()
})
const jenjangList = ['TK', 'KB', 'PAUD', 'TPA', 'SPS']

// Filtered data
const filteredList = computed(() => {
  return sekolahList.value.filter(item => {
    const matchSearch = !searchQuery.value || 
      item.nama.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.npsn.includes(searchQuery.value) ||
      item.alamat.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchKecamatan = !selectedKecamatan.value || item.kecamatan === selectedKecamatan.value
    const matchKelurahan = !selectedKelurahan.value || item.kelurahan === selectedKelurahan.value
    const matchJenjang = !selectedJenjang.value || item.jenjang === selectedJenjang.value
    return matchSearch && matchKecamatan && matchKelurahan && matchJenjang
  })
})

// Pagination
const totalPages = computed(() => Math.ceil(filteredList.value.length / itemsPerPage))
const paginatedList = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return filteredList.value.slice(start, start + itemsPerPage)
})

// Stats
const stats = computed(() => ({
  total: sekolahList.value.length,
  tk: sekolahList.value.filter(s => s.jenjang === 'TK').length,
  kb: sekolahList.value.filter(s => s.jenjang === 'KB').length,
  paud: sekolahList.value.filter(s => s.jenjang === 'PAUD').length,
  tpa: sekolahList.value.filter(s => s.jenjang === 'TPA').length,
  sps: sekolahList.value.filter(s => s.jenjang === 'SPS').length
}))

const getJenjangColor = (jenjang) => {
  const colors = {
    'TK': 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
    'KB': 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
    'PAUD': 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
    'TPA': 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
    'SPS': 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400'
  }
  return colors[jenjang] || 'bg-gray-100 text-gray-700'
}

const resetFilters = () => {
  searchQuery.value = ''
  selectedKecamatan.value = ''
  selectedKelurahan.value = ''
  selectedJenjang.value = ''
  currentPage.value = 1
}

// Watch for filter changes to reset pagination
const onFilterChange = () => {
  currentPage.value = 1
}
</script>

<template>
  <DataLayout 
    title="Daftar Sekolah PAUD" 
    subtitle="Database lengkap satuan Pendidikan Anak Usia Dini di Kota Surabaya"
    icon="🏫"
  >
    <div class="space-y-6">
      <!-- Stats -->
      <div class="grid grid-cols-3 md:grid-cols-6 gap-2">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-3 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-xl font-bold text-gray-800 dark:text-white">{{ stats.total }}</div>
          <div class="text-xs text-gray-500">Total</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-3 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-xl font-bold text-blue-600">{{ stats.tk }}</div>
          <div class="text-xs text-gray-500">TK</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-3 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-xl font-bold text-green-600">{{ stats.kb }}</div>
          <div class="text-xs text-gray-500">KB</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-3 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-xl font-bold text-purple-600">{{ stats.paud }}</div>
          <div class="text-xs text-gray-500">PAUD</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-3 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-xl font-bold text-orange-600">{{ stats.tpa }}</div>
          <div class="text-xs text-gray-500">TPA</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-3 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-xl font-bold text-pink-600">{{ stats.sps }}</div>
          <div class="text-xs text-gray-500">SPS</div>
        </div>
      </div>

      <!-- Search & Filters -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
        <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-3">
          <!-- Search -->
          <div class="lg:col-span-2 relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input 
              v-model="searchQuery"
              @input="onFilterChange"
              type="text" 
              placeholder="Cari nama sekolah, NPSN, atau alamat..."
              class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 text-sm"
            />
          </div>
          <!-- Kecamatan -->
          <select 
            v-model="selectedKecamatan"
            @change="onFilterChange"
            class="px-3 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 text-sm"
          >
            <option value="">Semua Kecamatan</option>
            <option v-for="kec in kecamatanList" :key="kec" :value="kec">{{ kec }}</option>
          </select>
          <!-- Kelurahan -->
          <select 
            v-model="selectedKelurahan"
            @change="onFilterChange"
            class="px-3 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 text-sm"
          >
            <option value="">Semua Kelurahan</option>
            <option v-for="kel in kelurahanList" :key="kel" :value="kel">{{ kel }}</option>
          </select>
          <!-- Jenjang -->
          <select 
            v-model="selectedJenjang"
            @change="onFilterChange"
            class="px-3 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 text-sm"
          >
            <option value="">Semua Jenjang</option>
            <option v-for="j in jenjangList" :key="j" :value="j">{{ j }}</option>
          </select>
        </div>
        <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
          <span class="text-sm text-gray-500">Menampilkan {{ filteredList.length }} dari {{ sekolahList.length }} sekolah</span>
          <button @click="resetFilters" class="text-sm text-blue-600 hover:underline">Reset Filter</button>
        </div>
      </div>

      <!-- School List -->
      <div class="space-y-3">
        <div 
          v-for="sekolah in paginatedList" 
          :key="sekolah.id"
          class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 hover:shadow-md transition-all"
        >
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
              {{ sekolah.jenjang }}
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex flex-wrap items-center gap-2 mb-1">
                <span class="text-xs text-gray-500 font-mono">({{ sekolah.npsn }})</span>
                <span :class="['px-2 py-0.5 text-xs font-medium rounded-full', getJenjangColor(sekolah.jenjang)]">
                  {{ sekolah.jenjang }}
                </span>
              </div>
              <h3 class="font-bold text-gray-900 dark:text-white text-base mb-1">{{ sekolah.nama }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400 flex items-start gap-1">
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>{{ sekolah.alamat }} Kec. {{ sekolah.kecamatan }}, {{ sekolah.kelurahan }}</span>
              </p>
              <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-1 mt-1">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <span>{{ sekolah.telepon }}</span>
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="filteredList.length === 0" class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl">
        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-gray-500 text-sm">Tidak ada sekolah yang ditemukan.</p>
      </div>

      <!-- Pagination -->
      <div v-if="totalPages > 1" class="flex justify-center gap-2">
        <button 
          @click="currentPage--" 
          :disabled="currentPage === 1"
          class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 dark:hover:bg-gray-700 text-sm"
        >
          ‹ Prev
        </button>
        <template v-for="page in totalPages" :key="page">
          <button 
            v-if="page === 1 || page === totalPages || (page >= currentPage - 1 && page <= currentPage + 1)"
            @click="currentPage = page"
            class="px-3 py-2 rounded-lg border text-sm"
            :class="currentPage === page 
              ? 'bg-blue-600 text-white border-blue-600' 
              : 'border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700'"
          >
            {{ page }}
          </button>
          <span v-else-if="page === currentPage - 2 || page === currentPage + 2" class="px-2 text-gray-400">...</span>
        </template>
        <button 
          @click="currentPage++" 
          :disabled="currentPage === totalPages"
          class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 dark:hover:bg-gray-700 text-sm"
        >
          Next ›
        </button>
      </div>
    </div>
  </DataLayout>
</template>
