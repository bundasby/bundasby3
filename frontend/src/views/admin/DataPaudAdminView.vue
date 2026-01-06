<script setup>
import { ref, onMounted, computed } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const activeTab = ref('dashboard')
const loading = ref(false)
const showModal = ref(false)
const modalType = ref('')
const editingItem = ref(null)

// Data refs
const sekolahList = ref([])
const akreditasiList = ref([])
const ijinOperasionalList = ref([])
const profilingList = ref([])
const gerakan7kaihList = ref([])
const digitalisasiList = ref([])
const sulingjarList = ref([])
const raporList = ref([])
const apkList = ref([])
const ikdList = ref([])
const paudHiList = ref([])
const revitalisasiList = ref([])

// Form refs
const sekolahForm = ref({ npsn: '', nama: '', jenis: 'TK', alamat: '', kecamatan: '', kelurahan: '', status: 'active' })

const tabs = [
  { id: 'dashboard', label: '📊 Dashboard', icon: 'chart' },
  { id: 'sekolah', label: '🏫 Daftar Sekolah', icon: 'school' },
  { id: 'akreditasi', label: '🏅 Akreditasi', icon: 'medal' },
  { id: 'ijin', label: '📄 Ijin Operasional', icon: 'doc' },
  { id: 'profiling', label: '📋 Profiling', icon: 'profile' },
  { id: 'gerakan7kaih', label: '⭐ Gerakan 7 KAIH', icon: 'star' },
  { id: 'digitalisasi', label: '💻 Digitalisasi', icon: 'laptop' },
  { id: 'sulingjar', label: '📝 Sulingjar', icon: 'survey' },
  { id: 'rapor', label: '📑 Rapor Pendidikan', icon: 'report' },
  { id: 'apk', label: '📈 APK/AKS/ATS', icon: 'graph' },
  { id: 'ikd', label: '🎯 IKD PAUD', icon: 'target' },
  { id: 'paudhi', label: '🤝 PAUD HI', icon: 'handshake' },
  { id: 'revitalisasi', label: '🔄 Revitalisasi', icon: 'refresh' },
]

const kecamatanList = [
  'Asemrowo', 'Benowo', 'Bubutan', 'Bulak', 'Dukuh Pakis', 'Gayungan', 'Genteng',
  'Gubeng', 'Gunung Anyar', 'Jambangan', 'Karang Pilang', 'Kenjeran', 'Krembangan',
  'Lakarsantri', 'Mulyorejo', 'Pabean Cantian', 'Pakal', 'Rungkut', 'Sambikerep',
  'Sawahan', 'Semampir', 'Simokerto', 'Sukolilo', 'Sukomanunggal', 'Tambaksari',
  'Tandes', 'Tegalsari', 'Tenggilis Mejoyo', 'Wiyung', 'Wonocolo', 'Wonokromo'
]

const jenisSekolah = ['TK', 'KB', 'TPA', 'SPS', 'PAUD']

// Load data
const loadData = () => {
  // Default dummy data
  const defaultSekolah = [
    { id: 1, npsn: '20532001', nama: 'TK Negeri Pembina Surabaya', jenis: 'TK', alamat: 'Jl. Raya Darmo No. 123', kecamatan: 'Wonokromo', status: 'active', akreditasi: 'A' },
    { id: 2, npsn: '20532002', nama: 'KB Tunas Bangsa', jenis: 'KB', alamat: 'Jl. Kapas Krampung No. 45', kecamatan: 'Simokerto', status: 'active', akreditasi: 'B' },
    { id: 3, npsn: '20532003', nama: 'TPA Ceria Mandiri', jenis: 'TPA', alamat: 'Jl. Margorejo Indah No. 67', kecamatan: 'Wonocolo', status: 'active', akreditasi: 'B' },
    { id: 4, npsn: '20532004', nama: 'TK ABA Surabaya', jenis: 'TK', alamat: 'Jl. Ahmad Yani No. 100', kecamatan: 'Gayungan', status: 'active', akreditasi: 'A' },
    { id: 5, npsn: '20532005', nama: 'KB Melati Indah', jenis: 'KB', alamat: 'Jl. Rungkut Asri No. 22', kecamatan: 'Rungkut', status: 'active', akreditasi: 'B' },
    { id: 6, npsn: '20532006', nama: 'PAUD Terpadu Harapan', jenis: 'PAUD', alamat: 'Jl. Mulyosari No. 15', kecamatan: 'Mulyorejo', status: 'active', akreditasi: 'A' },
    { id: 7, npsn: '20532007', nama: 'SPS Bintang Kecil', jenis: 'SPS', alamat: 'Jl. Sukomanunggal No. 89', kecamatan: 'Sukomanunggal', status: 'active', akreditasi: 'C' },
    { id: 8, npsn: '20532008', nama: 'TK Islam Sunan Ampel', jenis: 'TK', alamat: 'Jl. Ampel Suci No. 10', kecamatan: 'Semampir', status: 'active', akreditasi: 'A' },
    { id: 9, npsn: '20532009', nama: 'KB Pelangi Nusantara', jenis: 'KB', alamat: 'Jl. Tenggilis Mejoyo No. 33', kecamatan: 'Tenggilis Mejoyo', status: 'active', akreditasi: null },
    { id: 10, npsn: '20532010', nama: 'TPA Ananda Sejahtera', jenis: 'TPA', alamat: 'Jl. Wiyung Indah No. 77', kecamatan: 'Wiyung', status: 'inactive', akreditasi: 'B' },
  ]

  // Sekolah - check if data exists and has items
  const savedSekolah = localStorage.getItem('bp_data_paud_sekolah')
  const parsedSekolah = savedSekolah ? JSON.parse(savedSekolah) : []
  if (parsedSekolah.length === 0) {
    sekolahList.value = defaultSekolah
    localStorage.setItem('bp_data_paud_sekolah', JSON.stringify(defaultSekolah))
  } else {
    sekolahList.value = parsedSekolah
  }

  // Default Akreditasi data
  const defaultAkreditasi = [
    { id: 1, sekolahId: 1, npsn: '20532001', nama: 'TK Negeri Pembina Surabaya', peringkat: 'A', nilai: 95, tanggal: '2024-06-15', berlaku: '2029-06-15' },
    { id: 2, sekolahId: 2, npsn: '20532002', nama: 'KB Tunas Bangsa', peringkat: 'B', nilai: 85, tanggal: '2024-03-20', berlaku: '2029-03-20' },
    { id: 3, sekolahId: 4, npsn: '20532004', nama: 'TK ABA Surabaya', peringkat: 'A', nilai: 92, tanggal: '2023-11-10', berlaku: '2028-11-10' },
  ]
  akreditasiList.value = loadOrDefault('bp_data_paud_akreditasi', defaultAkreditasi)

  // Default Ijin Operasional
  const defaultIjin = [
    { id: 1, npsn: '20532001', nama: 'TK Negeri Pembina Surabaya', noSK: '421.1/123/2024', tanggalSK: '2024-01-15', berlaku: '2029-01-15', status: 'active' },
    { id: 2, npsn: '20532002', nama: 'KB Tunas Bangsa', noSK: '421.1/124/2024', tanggalSK: '2024-02-20', berlaku: '2029-02-20', status: 'active' },
    { id: 3, npsn: '20532003', nama: 'TPA Ceria Mandiri', noSK: '421.1/125/2023', tanggalSK: '2023-06-10', berlaku: '2028-06-10', status: 'active' },
  ]
  ijinOperasionalList.value = loadOrDefault('bp_data_paud_ijin', defaultIjin)

  // Default Profiling
  const defaultProfiling = [
    { id: 1, npsn: '20532001', nama: 'TK Negeri Pembina Surabaya', siswa: 120, guru: 8, ruangKelas: 6, tahun: 2025 },
    { id: 2, npsn: '20532002', nama: 'KB Tunas Bangsa', siswa: 45, guru: 3, ruangKelas: 2, tahun: 2025 },
    { id: 3, npsn: '20532004', nama: 'TK ABA Surabaya', siswa: 95, guru: 6, ruangKelas: 5, tahun: 2025 },
  ]
  profilingList.value = loadOrDefault('bp_data_paud_profiling', defaultProfiling)

  // Default Gerakan 7 KAIH
  const defaultGerakan = [
    { id: 1, npsn: '20532001', nama: 'TK Negeri Pembina Surabaya', implementasi: 7, status: 'Lengkap', tahun: 2025 },
    { id: 2, npsn: '20532002', nama: 'KB Tunas Bangsa', implementasi: 5, status: 'Sebagian', tahun: 2025 },
    { id: 3, npsn: '20532006', nama: 'PAUD Terpadu Harapan', implementasi: 7, status: 'Lengkap', tahun: 2025 },
  ]
  gerakan7kaihList.value = loadOrDefault('bp_data_paud_gerakan7kaih', defaultGerakan)

  // Default Digitalisasi
  const defaultDigital = [
    { id: 1, npsn: '20532001', nama: 'TK Negeri Pembina Surabaya', platform: 'Dapodik', status: 'Aktif', tahun: 2025 },
    { id: 2, npsn: '20532004', nama: 'TK ABA Surabaya', platform: 'Dapodik', status: 'Aktif', tahun: 2025 },
    { id: 3, npsn: '20532006', nama: 'PAUD Terpadu Harapan', platform: 'Dapodik', status: 'Proses', tahun: 2025 },
  ]
  digitalisasiList.value = loadOrDefault('bp_data_paud_digitalisasi', defaultDigital)

  // Default Sulingjar
  const defaultSulingjar = [
    { id: 1, npsn: '20532001', nama: 'TK Negeri Pembina Surabaya', indeks: 85.5, kategori: 'Baik', tahun: 2024 },
    { id: 2, npsn: '20532002', nama: 'KB Tunas Bangsa', indeks: 78.2, kategori: 'Cukup', tahun: 2024 },
    { id: 3, npsn: '20532004', nama: 'TK ABA Surabaya', indeks: 88.1, kategori: 'Baik', tahun: 2024 },
  ]
  sulingjarList.value = loadOrDefault('bp_data_paud_sulingjar', defaultSulingjar)

  // Default Rapor
  const defaultRapor = [
    { id: 1, npsn: '20532001', nama: 'TK Negeri Pembina Surabaya', nilai: 'A', kategori: 'Sangat Baik', tahun: 2024 },
    { id: 2, npsn: '20532002', nama: 'KB Tunas Bangsa', nilai: 'B', kategori: 'Baik', tahun: 2024 },
  ]
  raporList.value = loadOrDefault('bp_data_paud_rapor', defaultRapor)

  // Default APK/AKS/ATS
  const defaultApk = [
    { id: 1, kecamatan: 'Wonokromo', apk: 85.5, aks: 78.2, ats: 2.1, tahun: 2024 },
    { id: 2, kecamatan: 'Simokerto', apk: 82.3, aks: 75.8, ats: 3.2, tahun: 2024 },
    { id: 3, kecamatan: 'Gayungan', apk: 90.1, aks: 85.5, ats: 1.5, tahun: 2024 },
  ]
  apkList.value = loadOrDefault('bp_data_paud_apk', defaultApk)

  // Default IKD
  const defaultIkd = [
    { id: 1, npsn: '20532001', nama: 'TK Negeri Pembina Surabaya', skor: 95, kategori: 'Sangat Baik', tahun: 2024 },
    { id: 2, npsn: '20532002', nama: 'KB Tunas Bangsa', skor: 82, kategori: 'Baik', tahun: 2024 },
  ]
  ikdList.value = loadOrDefault('bp_data_paud_ikd', defaultIkd)

  // Default PAUD HI
  const defaultPaudHi = [
    { id: 1, npsn: '20532001', nama: 'TK Negeri Pembina Surabaya', statusHI: 'Terintegrasi', layanan: 5, tahun: 2024 },
    { id: 2, npsn: '20532006', nama: 'PAUD Terpadu Harapan', statusHI: 'Terintegrasi', layanan: 6, tahun: 2024 },
  ]
  paudHiList.value = loadOrDefault('bp_data_paud_hi', defaultPaudHi)

  // Default Revitalisasi
  const defaultRevit = [
    { id: 1, npsn: '20532007', nama: 'SPS Bintang Kecil', program: 'Renovasi Gedung', status: 'Proses', tahunMulai: 2024, tahunSelesai: 2025 },
    { id: 2, npsn: '20532010', nama: 'TPA Ananda Sejahtera', program: 'Pengadaan APE', status: 'Selesai', tahunMulai: 2024, tahunSelesai: 2024 },
  ]
  revitalisasiList.value = loadOrDefault('bp_data_paud_revitalisasi', defaultRevit)
}

// Helper function to load data or use default
const loadOrDefault = (key, defaultData) => {
  const saved = localStorage.getItem(key)
  const parsed = saved ? JSON.parse(saved) : []
  if (parsed.length === 0) {
    localStorage.setItem(key, JSON.stringify(defaultData))
    return defaultData
  }
  return parsed
}

// Reset to default dummy data
const resetToDefaultData = () => {
  if (confirm('Reset semua data ke data default? Data yang sudah diubah akan hilang.')) {
    localStorage.removeItem('bp_data_paud_sekolah')
    loadData()
  }
}

// Computed stats
const totalSekolah = computed(() => sekolahList.value.length)
const sekolahAktif = computed(() => sekolahList.value.filter(s => s.status === 'active').length)
const sekolahTerakreditasi = computed(() => sekolahList.value.filter(s => s.akreditasi).length)

const getSekolahName = (id) => {
  const sekolah = sekolahList.value.find(s => s.id === id)
  return sekolah?.nama || '-'
}

// CRUD Sekolah
const openSekolahModal = (item = null) => {
  editingItem.value = item
  modalType.value = 'sekolah'
  if (item) {
    sekolahForm.value = { ...item }
  } else {
    sekolahForm.value = { npsn: '', nama: '', jenis: 'TK', alamat: '', kecamatan: '', kelurahan: '', status: 'active' }
  }
  showModal.value = true
}

const saveSekolah = () => {
  if (editingItem.value) {
    const index = sekolahList.value.findIndex(s => s.id === editingItem.value.id)
    sekolahList.value[index] = { ...sekolahForm.value }
  } else {
    sekolahList.value.push({ id: Date.now(), ...sekolahForm.value, createdAt: new Date().toISOString() })
  }
  localStorage.setItem('bp_data_paud_sekolah', JSON.stringify(sekolahList.value))
  showModal.value = false
}

const deleteSekolah = (item) => {
  if (confirm('Hapus sekolah ini?')) {
    sekolahList.value = sekolahList.value.filter(s => s.id !== item.id)
    localStorage.setItem('bp_data_paud_sekolah', JSON.stringify(sekolahList.value))
  }
}

const formatDate = (dateStr) => {
  if (!dateStr) return '-'
  return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
}

onMounted(() => loadData())
</script>

<template>
  <AdminLayout title="Data PAUD">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
          </div>
          <div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ totalSekolah }}</p>
            <p class="text-sm text-gray-500">Total Lembaga PAUD</p>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ sekolahAktif }}</p>
            <p class="text-sm text-gray-500">Lembaga Aktif</p>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
          </div>
          <div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ sekolahTerakreditasi }}</p>
            <p class="text-sm text-gray-500">Terakreditasi</p>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </div>
          <div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">31</p>
            <p class="text-sm text-gray-500">Kecamatan</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
      <div class="border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
        <nav class="flex space-x-1 p-2 min-w-max">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            @click="activeTab = tab.id"
            class="px-4 py-2.5 text-sm font-medium rounded-lg transition-colors whitespace-nowrap"
            :class="activeTab === tab.id 
              ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' 
              : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'"
          >
            {{ tab.label }}
          </button>
        </nav>
      </div>

      <div class="p-6">
        <!-- Tab: Dashboard -->
        <div v-if="activeTab === 'dashboard'">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ringkasan Data PAUD Kota Surabaya</h3>
          <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white">
              <h4 class="text-lg font-medium mb-2">Distribusi per Jenis</h4>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span>TK</span><span>{{ sekolahList.filter(s => s.jenis === 'TK').length }}</span></div>
                <div class="flex justify-between"><span>KB</span><span>{{ sekolahList.filter(s => s.jenis === 'KB').length }}</span></div>
                <div class="flex justify-between"><span>TPA</span><span>{{ sekolahList.filter(s => s.jenis === 'TPA').length }}</span></div>
                <div class="flex justify-between"><span>SPS</span><span>{{ sekolahList.filter(s => s.jenis === 'SPS').length }}</span></div>
              </div>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
              <h4 class="text-lg font-medium mb-2">Status Akreditasi</h4>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span>Akreditasi A</span><span>{{ sekolahList.filter(s => s.akreditasi === 'A').length }}</span></div>
                <div class="flex justify-between"><span>Akreditasi B</span><span>{{ sekolahList.filter(s => s.akreditasi === 'B').length }}</span></div>
                <div class="flex justify-between"><span>Akreditasi C</span><span>{{ sekolahList.filter(s => s.akreditasi === 'C').length }}</span></div>
                <div class="flex justify-between"><span>Belum Akreditasi</span><span>{{ sekolahList.filter(s => !s.akreditasi).length }}</span></div>
              </div>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white">
              <h4 class="text-lg font-medium mb-2">Data Lainnya</h4>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span>Profiling PAUD</span><span>{{ profilingList.length }}</span></div>
                <div class="flex justify-between"><span>PAUD HI</span><span>{{ paudHiList.length }}</span></div>
                <div class="flex justify-between"><span>Revitalisasi</span><span>{{ revitalisasiList.length }}</span></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tab: Daftar Sekolah -->
        <div v-else-if="activeTab === 'sekolah'">
          <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-gray-900 dark:text-white">Daftar Lembaga PAUD</h3>
            <button @click="openSekolahModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
              + Tambah Sekolah
            </button>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NPSN</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lembaga</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kecamatan</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Akreditasi</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="sekolah in sekolahList" :key="sekolah.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                  <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">{{ sekolah.npsn }}</td>
                  <td class="px-4 py-4">
                    <p class="font-medium text-gray-900 dark:text-white">{{ sekolah.nama }}</p>
                    <p class="text-sm text-gray-500">{{ sekolah.alamat }}</p>
                  </td>
                  <td class="px-4 py-4"><span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded">{{ sekolah.jenis }}</span></td>
                  <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">{{ sekolah.kecamatan }}</td>
                  <td class="px-4 py-4">
                    <span :class="['px-2 py-1 text-xs rounded', sekolah.akreditasi === 'A' ? 'bg-green-100 text-green-700' : sekolah.akreditasi === 'B' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700']">
                      {{ sekolah.akreditasi || '-' }}
                    </span>
                  </td>
                  <td class="px-4 py-4">
                    <span :class="['px-2 py-1 text-xs rounded', sekolah.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700']">
                      {{ sekolah.status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                  </td>
                  <td class="px-4 py-4">
                    <div class="flex gap-2">
                      <button @click="openSekolahModal(sekolah)" class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                      <button @click="deleteSekolah(sekolah)" class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                    </div>
                  </td>
                </tr>
                <tr v-if="sekolahList.length === 0">
                  <td colspan="7" class="px-4 py-12 text-center text-gray-500">Belum ada data lembaga PAUD</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab: Akreditasi -->
        <div v-else-if="activeTab === 'akreditasi'">
          <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Data Akreditasi PAUD</h3>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NPSN</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lembaga</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peringkat</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Berlaku Hingga</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="item in akreditasiList" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                  <td class="px-4 py-4 text-sm">{{ item.npsn }}</td>
                  <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">{{ item.nama }}</td>
                  <td class="px-4 py-4"><span :class="['px-2 py-1 text-xs rounded', item.peringkat === 'A' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700']">{{ item.peringkat }}</span></td>
                  <td class="px-4 py-4 text-sm">{{ item.nilai }}</td>
                  <td class="px-4 py-4 text-sm">{{ formatDate(item.tanggal) }}</td>
                  <td class="px-4 py-4 text-sm">{{ formatDate(item.berlaku) }}</td>
                </tr>
                <tr v-if="akreditasiList.length === 0"><td colspan="6" class="px-4 py-12 text-center text-gray-500">Belum ada data</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab: Ijin Operasional -->
        <div v-else-if="activeTab === 'ijin'">
          <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Ijin Operasional</h3>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NPSN</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lembaga</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. SK</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal SK</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Berlaku Hingga</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="item in ijinOperasionalList" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                  <td class="px-4 py-4 text-sm">{{ item.npsn }}</td>
                  <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">{{ item.nama }}</td>
                  <td class="px-4 py-4 text-sm">{{ item.noSK }}</td>
                  <td class="px-4 py-4 text-sm">{{ formatDate(item.tanggalSK) }}</td>
                  <td class="px-4 py-4 text-sm">{{ formatDate(item.berlaku) }}</td>
                  <td class="px-4 py-4"><span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Aktif</span></td>
                </tr>
                <tr v-if="ijinOperasionalList.length === 0"><td colspan="6" class="px-4 py-12 text-center text-gray-500">Belum ada data</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab: Profiling -->
        <div v-else-if="activeTab === 'profiling'">
          <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Profiling PAUD</h3>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NPSN</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lembaga</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Siswa</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Guru</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ruang Kelas</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="item in profilingList" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                  <td class="px-4 py-4 text-sm">{{ item.npsn }}</td>
                  <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">{{ item.nama }}</td>
                  <td class="px-4 py-4 text-sm">{{ item.siswa }}</td>
                  <td class="px-4 py-4 text-sm">{{ item.guru }}</td>
                  <td class="px-4 py-4 text-sm">{{ item.ruangKelas }}</td>
                  <td class="px-4 py-4 text-sm">{{ item.tahun }}</td>
                </tr>
                <tr v-if="profilingList.length === 0"><td colspan="6" class="px-4 py-12 text-center text-gray-500">Belum ada data</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab: Gerakan 7 KAIH -->
        <div v-else-if="activeTab === 'gerakan7kaih'">
          <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Gerakan 7 KAIH</h3>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NPSN</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lembaga</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Implementasi (dari 7)</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="item in gerakan7kaihList" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                  <td class="px-4 py-4 text-sm">{{ item.npsn }}</td>
                  <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">{{ item.nama }}</td>
                  <td class="px-4 py-4 text-sm">{{ item.implementasi }}/7</td>
                  <td class="px-4 py-4"><span :class="['px-2 py-1 text-xs rounded', item.status === 'Lengkap' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700']">{{ item.status }}</span></td>
                  <td class="px-4 py-4 text-sm">{{ item.tahun }}</td>
                </tr>
                <tr v-if="gerakan7kaihList.length === 0"><td colspan="5" class="px-4 py-12 text-center text-gray-500">Belum ada data</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab: Digitalisasi -->
        <div v-else-if="activeTab === 'digitalisasi'">
          <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Digitalisasi PAUD</h3>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NPSN</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lembaga</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Platform</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="item in digitalisasiList" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                  <td class="px-4 py-4 text-sm">{{ item.npsn }}</td>
                  <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">{{ item.nama }}</td>
                  <td class="px-4 py-4 text-sm">{{ item.platform }}</td>
                  <td class="px-4 py-4"><span :class="['px-2 py-1 text-xs rounded', item.status === 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700']">{{ item.status }}</span></td>
                  <td class="px-4 py-4 text-sm">{{ item.tahun }}</td>
                </tr>
                <tr v-if="digitalisasiList.length === 0"><td colspan="5" class="px-4 py-12 text-center text-gray-500">Belum ada data</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab: Sulingjar -->
        <div v-else-if="activeTab === 'sulingjar'">
          <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Survei Lingkungan Belajar (Sulingjar)</h3>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NPSN</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lembaga</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Indeks</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="item in sulingjarList" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                  <td class="px-4 py-4 text-sm">{{ item.npsn }}</td>
                  <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">{{ item.nama }}</td>
                  <td class="px-4 py-4 text-sm font-medium">{{ item.indeks }}</td>
                  <td class="px-4 py-4"><span :class="['px-2 py-1 text-xs rounded', item.kategori === 'Baik' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700']">{{ item.kategori }}</span></td>
                  <td class="px-4 py-4 text-sm">{{ item.tahun }}</td>
                </tr>
                <tr v-if="sulingjarList.length === 0"><td colspan="5" class="px-4 py-12 text-center text-gray-500">Belum ada data</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab: Rapor Pendidikan -->
        <div v-else-if="activeTab === 'rapor'">
          <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Rapor Pendidikan</h3>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NPSN</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lembaga</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="item in raporList" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                  <td class="px-4 py-4 text-sm">{{ item.npsn }}</td>
                  <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">{{ item.nama }}</td>
                  <td class="px-4 py-4"><span :class="['px-2 py-1 text-xs rounded', item.nilai === 'A' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700']">{{ item.nilai }}</span></td>
                  <td class="px-4 py-4 text-sm">{{ item.kategori }}</td>
                  <td class="px-4 py-4 text-sm">{{ item.tahun }}</td>
                </tr>
                <tr v-if="raporList.length === 0"><td colspan="5" class="px-4 py-12 text-center text-gray-500">Belum ada data</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab: APK/AKS/ATS -->
        <div v-else-if="activeTab === 'apk'">
          <h3 class="font-semibold text-gray-900 dark:text-white mb-4">APK / AKS / ATS per Kecamatan</h3>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kecamatan</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">APK (%)</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">AKS (%)</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ATS (%)</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="item in apkList" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                  <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">{{ item.kecamatan }}</td>
                  <td class="px-4 py-4 text-sm">{{ item.apk }}%</td>
                  <td class="px-4 py-4 text-sm">{{ item.aks }}%</td>
                  <td class="px-4 py-4 text-sm">{{ item.ats }}%</td>
                  <td class="px-4 py-4 text-sm">{{ item.tahun }}</td>
                </tr>
                <tr v-if="apkList.length === 0"><td colspan="5" class="px-4 py-12 text-center text-gray-500">Belum ada data</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab: IKD PAUD -->
        <div v-else-if="activeTab === 'ikd'">
          <h3 class="font-semibold text-gray-900 dark:text-white mb-4">IKD PAUD (Indeks Kualitas Dapodik)</h3>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NPSN</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lembaga</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Skor</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="item in ikdList" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                  <td class="px-4 py-4 text-sm">{{ item.npsn }}</td>
                  <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">{{ item.nama }}</td>
                  <td class="px-4 py-4 text-sm font-bold">{{ item.skor }}</td>
                  <td class="px-4 py-4"><span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">{{ item.kategori }}</span></td>
                  <td class="px-4 py-4 text-sm">{{ item.tahun }}</td>
                </tr>
                <tr v-if="ikdList.length === 0"><td colspan="5" class="px-4 py-12 text-center text-gray-500">Belum ada data</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab: PAUD HI -->
        <div v-else-if="activeTab === 'paudhi'">
          <h3 class="font-semibold text-gray-900 dark:text-white mb-4">PAUD Holistik Integratif</h3>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NPSN</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lembaga</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status HI</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Layanan</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="item in paudHiList" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                  <td class="px-4 py-4 text-sm">{{ item.npsn }}</td>
                  <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">{{ item.nama }}</td>
                  <td class="px-4 py-4"><span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">{{ item.statusHI }}</span></td>
                  <td class="px-4 py-4 text-sm">{{ item.layanan }} Layanan</td>
                  <td class="px-4 py-4 text-sm">{{ item.tahun }}</td>
                </tr>
                <tr v-if="paudHiList.length === 0"><td colspan="5" class="px-4 py-12 text-center text-gray-500">Belum ada data</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab: Revitalisasi -->
        <div v-else-if="activeTab === 'revitalisasi'">
          <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Revitalisasi PAUD</h3>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NPSN</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lembaga</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Program</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="item in revitalisasiList" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                  <td class="px-4 py-4 text-sm">{{ item.npsn }}</td>
                  <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">{{ item.nama }}</td>
                  <td class="px-4 py-4 text-sm">{{ item.program }}</td>
                  <td class="px-4 py-4"><span :class="['px-2 py-1 text-xs rounded', item.status === 'Selesai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700']">{{ item.status }}</span></td>
                  <td class="px-4 py-4 text-sm">{{ item.tahunMulai }} - {{ item.tahunSelesai }}</td>
                </tr>
                <tr v-if="revitalisasiList.length === 0"><td colspan="5" class="px-4 py-12 text-center text-gray-500">Belum ada data</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Sekolah -->
    <div v-if="showModal && modalType === 'sekolah'" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/50" @click="showModal = false"></div>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
            {{ editingItem ? 'Edit' : 'Tambah' }} Lembaga PAUD
          </h3>

          <form @submit.prevent="saveSekolah" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NPSN *</label>
              <input v-model="sekolahForm.npsn" type="text" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lembaga *</label>
              <input v-model="sekolahForm.nama" type="text" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis *</label>
              <select v-model="sekolahForm.jenis" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option v-for="jenis in jenisSekolah" :key="jenis" :value="jenis">{{ jenis }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
              <input v-model="sekolahForm.alamat" type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kecamatan</label>
              <select v-model="sekolahForm.kecamatan" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="">Pilih Kecamatan</option>
                <option v-for="kec in kecamatanList" :key="kec" :value="kec">{{ kec }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
              <select v-model="sekolahForm.status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="active">Aktif</option>
                <option value="inactive">Tidak Aktif</option>
              </select>
            </div>
            <div class="flex justify-end gap-3 pt-4">
              <button type="button" @click="showModal = false" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">Batal</button>
              <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
