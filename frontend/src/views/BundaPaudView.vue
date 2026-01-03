<script setup>
import { ref, computed } from 'vue'
import MitraPaudSidebar from '@/components/layout/MitraPaudSidebar.vue'
import { generateKelurahanBundaPaud } from '@/data/kelurahanData.js'

const searchQuery = ref('')
const selectedKecamatan = ref('all')
const selectedType = ref('all')
const selectedKelurahan = ref('all')

// Array warna untuk variasi avatar
const colors = [
  'ec4899', 'f43f5e', 'a855f7', 'd946ef', 'f472b6', 'fb7185', 'c084fc', 'e879f9',
  'fb923c', 'f97316', 'fbbf24', 'facc15', '4ade80', '2dd4bf', '22d3ee', '38bdf8',
  '6366f1', '8b5cf6', '14b8a6', '10b981', '3b82f6', '0ea5e9', 'ef4444', '84cc16',
  'ec4899', 'f43f5e', 'a855f7', 'd946ef', 'f472b6', 'fb7185', 'c084fc'
]

// Dummy female photo URLs using pravatar.cc
const getPhotoUrl = (index) => {
  const avatarId = (index % 70) + 1
  return `https://i.pravatar.cc/150?img=${avatarId}`
}

// Data Bunda PAUD Kecamatan (31 Kecamatan)
const bundaPaudKecamatan = [
  // Surabaya Pusat
  { id: 1, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Tegalsari', kecamatan: 'Tegalsari', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(0) },
  { id: 2, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Genteng', kecamatan: 'Genteng', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(1) },
  { id: 3, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Bubutan', kecamatan: 'Bubutan', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(2) },
  { id: 4, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Simokerto', kecamatan: 'Simokerto', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(3) },
  
  // Surabaya Utara
  { id: 5, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Pabean Cantikan', kecamatan: 'Pabean Cantikan', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(4) },
  { id: 6, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Semampir', kecamatan: 'Semampir', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(5) },
  { id: 7, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Krembangan', kecamatan: 'Krembangan', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(6) },
  { id: 8, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Kenjeran', kecamatan: 'Kenjeran', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(7) },
  { id: 9, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Bulak', kecamatan: 'Bulak', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(8) },
  
  // Surabaya Timur
  { id: 10, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Tambaksari', kecamatan: 'Tambaksari', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(9) },
  { id: 11, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Gubeng', kecamatan: 'Gubeng', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(10) },
  { id: 12, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Rungkut', kecamatan: 'Rungkut', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(11) },
  { id: 13, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Tenggilis Mejoyo', kecamatan: 'Tenggilis Mejoyo', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(12) },
  { id: 14, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Gunung Anyar', kecamatan: 'Gunung Anyar', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(13) },
  { id: 15, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Sukolilo', kecamatan: 'Sukolilo', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(14) },
  { id: 16, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Mulyorejo', kecamatan: 'Mulyorejo', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(15) },
  
  // Surabaya Selatan
  { id: 17, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Sawahan', kecamatan: 'Sawahan', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(16) },
  { id: 18, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Wonokromo', kecamatan: 'Wonokromo', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(17) },
  { id: 19, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Karang Pilang', kecamatan: 'Karang Pilang', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(18) },
  { id: 20, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Dukuh Pakis', kecamatan: 'Dukuh Pakis', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(19) },
  { id: 21, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Wiyung', kecamatan: 'Wiyung', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(20) },
  { id: 22, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Wonocolo', kecamatan: 'Wonocolo', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(21) },
  { id: 23, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Gayungan', kecamatan: 'Gayungan', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(22) },
  { id: 24, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Jambangan', kecamatan: 'Jambangan', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(23) },
  
  // Surabaya Barat
  { id: 25, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Tandes', kecamatan: 'Tandes', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(24) },
  { id: 26, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Sukomanunggal', kecamatan: 'Sukomanunggal', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(25) },
  { id: 27, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Asem Rowo', kecamatan: 'Asem Rowo', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(26) },
  { id: 28, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Benowo', kecamatan: 'Benowo', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(27) },
  { id: 29, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Pakal', kecamatan: 'Pakal', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(28) },
  { id: 30, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Lakarsantri', kecamatan: 'Lakarsantri', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(29) },
  { id: 31, name: '[Nama Bunda PAUD]', position: 'Bunda PAUD Kecamatan', area: 'Sambikerep', kecamatan: 'Sambikerep', kelurahan: '', type: 'kecamatan', photo: getPhotoUrl(30) },
]

// Generate kelurahan with colorful photos
const kelurahanBundaPaud = generateKelurahanBundaPaud().map((item, index) => ({
  ...item,
  photo: getPhotoUrl(31 + index)
}))

// Combine Kecamatan + Kelurahan data (31 Kecamatan + 154 Kelurahan = 185 total)
const bundaPaudList = ref([...bundaPaudKecamatan, ...kelurahanBundaPaud])

const kecamatanList = computed(() => {
  const unique = [...new Set(bundaPaudList.value.map(b => b.kecamatan))]
  return unique.sort()
})

const kelurahanList = computed(() => {
  if (selectedKecamatan.value === 'all') {
    const unique = [...new Set(bundaPaudList.value.filter(b => b.kelurahan).map(b => b.kelurahan))]
    return unique.sort()
  }
  const unique = [...new Set(bundaPaudList.value.filter(b => b.kecamatan === selectedKecamatan.value && b.kelurahan).map(b => b.kelurahan))]
  return unique.sort()
})

const filteredList = computed(() => {
  let list = bundaPaudList.value
  
  if (selectedType.value !== 'all') {
    list = list.filter(b => b.type === selectedType.value)
  }
  
  if (selectedKecamatan.value !== 'all') {
    list = list.filter(b => b.kecamatan === selectedKecamatan.value)
  }
  
  if (selectedKelurahan.value !== 'all') {
    list = list.filter(b => b.kelurahan === selectedKelurahan.value)
  }
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    list = list.filter(b => 
      b.name.toLowerCase().includes(query) || 
      b.area.toLowerCase().includes(query) ||
      b.kelurahan.toLowerCase().includes(query)
    )
  }
  
  // Sort: kecamatan first, then kelurahan
  list = [...list].sort((a, b) => {
    if (a.type === 'kecamatan' && b.type === 'kelurahan') return -1
    if (a.type === 'kelurahan' && b.type === 'kecamatan') return 1
    return a.area.localeCompare(b.area)
  })
  
  return list
})

const totalKecamatan = computed(() => bundaPaudList.value.filter(b => b.type === 'kecamatan').length)
const totalKelurahan = computed(() => bundaPaudList.value.filter(b => b.type === 'kelurahan').length)
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Hero -->
    <section class="bg-gradient-to-br from-primary-600 to-primary-800 text-white py-16">
      <div class="section-container text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Bunda PAUD Kecamatan & Kelurahan</h1>
        <p class="text-primary-100 max-w-2xl mx-auto">
          Daftar Bunda PAUD di seluruh kecamatan dan kelurahan Kota Surabaya
        </p>
      </div>
    </section>

    <!-- Content with Sidebar -->
    <section class="py-12 bg-white dark:bg-gray-900">
      <div class="section-container">
        <div class="grid lg:grid-cols-4 gap-8">
          <!-- Sidebar -->
          <div class="lg:col-span-1">
            <MitraPaudSidebar />
          </div>

          <!-- Main Content -->
          <div class="lg:col-span-3">
      <!-- Filter -->
      <div class="card p-6 mb-8">
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cari Nama/Wilayah</label>
            <input 
              v-model="searchQuery"
              type="text"
              placeholder="Ketik nama atau wilayah..."
              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter Tipe</label>
            <select 
              v-model="selectedType"
              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
            >
              <option value="all">Semua Tipe</option>
              <option value="kecamatan">Bunda PAUD Kecamatan</option>
              <option value="kelurahan">Bunda PAUD Kelurahan</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter Kecamatan</label>
            <select 
              v-model="selectedKecamatan"
              @change="selectedKelurahan = 'all'"
              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
            >
              <option value="all">Semua Kecamatan</option>
              <option v-for="kec in kecamatanList" :key="kec" :value="kec">{{ kec }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter Kelurahan</label>
            <select 
              v-model="selectedKelurahan"
              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
              :disabled="kelurahanList.length === 0"
            >
              <option value="all">Semua Kelurahan</option>
              <option v-for="kel in kelurahanList" :key="kel" :value="kel">{{ kel }}</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Stats -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="card p-4 text-center">
          <p class="text-2xl font-bold text-primary-600">{{ totalKecamatan }}</p>
          <p class="text-sm text-gray-600 dark:text-gray-400">Bunda PAUD Kec.</p>
        </div>
        <div class="card p-4 text-center">
          <p class="text-2xl font-bold text-green-600">{{ totalKelurahan }}</p>
          <p class="text-sm text-gray-600 dark:text-gray-400">Bunda PAUD Kel.</p>
        </div>
        <div class="card p-4 text-center">
          <p class="text-2xl font-bold text-amber-600">{{ bundaPaudList.length }}</p>
          <p class="text-sm text-gray-600 dark:text-gray-400">Total Bunda PAUD</p>
        </div>
        <div class="card p-4 text-center">
          <p class="text-2xl font-bold text-purple-600">{{ filteredList.length }}</p>
          <p class="text-sm text-gray-600 dark:text-gray-400">Ditampilkan</p>
        </div>
      </div>

      <!-- Grid -->
      <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <div v-for="bunda in filteredList" :key="bunda.id" class="card p-6 text-center hover:shadow-lg transition-shadow group">
          <div class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden ring-4 ring-primary-100 dark:ring-primary-900/30 group-hover:ring-primary-300 transition-all">
            <img :src="bunda.photo" :alt="bunda.name" class="w-full h-full object-cover"/>
          </div>
          <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">{{ bunda.name }}</h3>
          <p class="text-primary-600 dark:text-primary-400 text-xs font-medium mb-2">{{ bunda.position }}</p>
          <p class="text-gray-500 text-xs">{{ bunda.area }}</p>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="filteredList.length === 0" class="text-center py-12">
        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-gray-600 dark:text-gray-400">Tidak ada data yang ditemukan</p>
      </div>

      <!-- Note -->
      <div class="mt-8 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
        <p class="text-sm text-amber-700 dark:text-amber-400">
          <strong>Catatan:</strong> Data nama Bunda PAUD akan diperbarui sesuai dengan SK Pengangkatan terbaru dari masing-masing kecamatan/kelurahan.
        </p>
      </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
