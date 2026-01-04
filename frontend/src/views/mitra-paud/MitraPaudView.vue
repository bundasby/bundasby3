<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import MitraPaudSidebar from '@/components/layout/MitraPaudSidebar.vue'

const route = useRoute()

// Tab state
const activeTab = ref('pengertian')

// Form for pendaftaran
const form = ref({
  namaLembaga: '',
  jenis: '',
  alamat: '',
  penanggungJawab: '',
  jabatan: '',
  telepon: '',
  email: '',
  website: '',
  bidangKerjasama: '',
  deskripsi: ''
})

const loading = ref(false)
const submitted = ref(false)

const jenisOptions = [
  'Lembaga Pemerintah',
  'Organisasi Masyarakat',
  'Perguruan Tinggi',
  'Dunia Usaha/Industri',
  'Lembaga Swadaya Masyarakat',
  'Media',
  'Lainnya'
]

const bidangOptions = [
  'Pendidikan dan Pelatihan',
  'Kesehatan dan Gizi',
  'Perlindungan Anak',
  'Pemberdayaan Masyarakat',
  'CSR dan Pendanaan',
  'Teknologi dan Inovasi',
  'Media dan Komunikasi',
  'Lainnya'
]

// Mitra list
const mitraList = ref([])
const searchQuery = ref('')
const filterJenis = ref('')

// Load mitra from localStorage
const loadMitra = () => {
  const saved = localStorage.getItem('bp_mitra_paud')
  if (saved) {
    mitraList.value = JSON.parse(saved)
  } else {
    // Default demo data
    mitraList.value = [
      { id: 1, namaLembaga: 'Dinas Pendidikan Kota Surabaya', jenis: 'Lembaga Pemerintah', bidangKerjasama: 'Pendidikan dan Pelatihan', status: 'approved' },
      { id: 2, namaLembaga: 'HIMPAUDI Kota Surabaya', jenis: 'Organisasi Masyarakat', bidangKerjasama: 'Pendidikan dan Pelatihan', status: 'approved' },
      { id: 3, namaLembaga: 'Universitas Negeri Surabaya', jenis: 'Perguruan Tinggi', bidangKerjasama: 'Pendidikan dan Pelatihan', status: 'approved' },
      { id: 4, namaLembaga: 'Bank Jatim', jenis: 'Dunia Usaha/Industri', bidangKerjasama: 'CSR dan Pendanaan', status: 'approved' },
      { id: 5, namaLembaga: 'IGTKI Kota Surabaya', jenis: 'Organisasi Masyarakat', bidangKerjasama: 'Pendidikan dan Pelatihan', status: 'approved' },
      { id: 6, namaLembaga: 'Dinas Kesehatan Kota Surabaya', jenis: 'Lembaga Pemerintah', bidangKerjasama: 'Kesehatan dan Gizi', status: 'approved' },
    ]
  }
}

onMounted(() => {
  loadMitra()
  // Handle query param for tab
  if (route.query.tab) {
    activeTab.value = route.query.tab
  }
})

const filteredMitra = computed(() => {
  return mitraList.value.filter(m => {
    const matchSearch = m.namaLembaga.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchJenis = !filterJenis.value || m.jenis === filterJenis.value
    const isApproved = m.status === 'approved'
    return matchSearch && matchJenis && isApproved
  })
})

const handleSubmit = async () => {
  loading.value = true
  await new Promise(resolve => setTimeout(resolve, 1500))
  
  // Save to localStorage
  const newMitra = {
    id: Date.now(),
    ...form.value,
    status: 'pending',
    createdAt: new Date().toISOString()
  }
  mitraList.value.push(newMitra)
  localStorage.setItem('bp_mitra_paud', JSON.stringify(mitraList.value))
  
  submitted.value = true
  loading.value = false
}

const resetForm = () => {
  form.value = {
    namaLembaga: '',
    jenis: '',
    alamat: '',
    penanggungJawab: '',
    jabatan: '',
    telepon: '',
    email: '',
    website: '',
    bidangKerjasama: '',
    deskripsi: ''
  }
  submitted.value = false
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Hero -->
    <section class="bg-gradient-to-br from-teal-600 to-green-700 text-white py-16">
      <div class="section-container text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Mitra PAUD</h1>
        <p class="text-teal-100 max-w-2xl mx-auto">
          Jaringan kemitraan untuk pengembangan PAUD berkualitas di Kota Surabaya
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
            <!-- Tabs -->
            <div class="flex flex-wrap gap-2 mb-6">
              <button
                @click="activeTab = 'pengertian'"
                class="px-5 py-2.5 rounded-lg font-medium transition-all"
                :class="activeTab === 'pengertian' 
                  ? 'bg-teal-600 text-white shadow-lg' 
                  : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100'"
              >
                📖 Pengertian Mitra PAUD
              </button>
              <button
                @click="activeTab = 'pendaftaran'"
                class="px-5 py-2.5 rounded-lg font-medium transition-all"
                :class="activeTab === 'pendaftaran' 
                  ? 'bg-teal-600 text-white shadow-lg' 
                  : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100'"
              >
                📝 Pendaftaran Mitra
              </button>
              <button
                @click="activeTab = 'list'"
                class="px-5 py-2.5 rounded-lg font-medium transition-all"
                :class="activeTab === 'list' 
                  ? 'bg-teal-600 text-white shadow-lg' 
                  : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100'"
              >
                📋 List Mitra PAUD
              </button>
            </div>

            <!-- Tab: Pengertian Mitra PAUD -->
            <div v-if="activeTab === 'pengertian'">
              <!-- Intro -->
              <div class="card p-8 mb-8 text-center">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Siapa Mitra PAUD?</h2>
                <p class="text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                  Mitra PAUD adalah organisasi, lembaga, atau individu yang berkolaborasi dengan Bunda PAUD dalam mendukung penyelenggaraan layanan PAUD berkualitas. Mitra PAUD berperan penting dalam memperluas jangkauan dan meningkatkan kualitas pendidikan anak usia dini.
                </p>
              </div>

              <!-- Categories -->
              <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Kategori Mitra PAUD</h2>
              <div class="grid md:grid-cols-2 gap-6 mb-8">
                <div class="card p-6">
                  <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                      <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                      </svg>
                    </span>
                    Lembaga Pemerintah
                  </h3>
                  <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                    <li>• Dinas Pendidikan</li>
                    <li>• Dinas Kesehatan</li>
                    <li>• Dinas Sosial</li>
                    <li>• Dinas Pemberdayaan Perempuan</li>
                    <li>• Kecamatan dan Kelurahan</li>
                  </ul>
                </div>

                <div class="card p-6">
                  <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                      <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                      </svg>
                    </span>
                    Organisasi Masyarakat
                  </h3>
                  <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                    <li>• HIMPAUDI</li>
                    <li>• IGTKI</li>
                    <li>• Forum PAUD</li>
                    <li>• PKK</li>
                    <li>• Posyandu</li>
                  </ul>
                </div>

                <div class="card p-6">
                  <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                      <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                      </svg>
                    </span>
                    Akademisi & Perguruan Tinggi
                  </h3>
                  <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                    <li>• Universitas Negeri Surabaya</li>
                    <li>• Universitas Airlangga</li>
                    <li>• ITS</li>
                    <li>• Perguruan Tinggi Swasta</li>
                    <li>• Lembaga Penelitian</li>
                  </ul>
                </div>

                <div class="card p-6">
                  <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-pink-100 dark:bg-pink-900/30 rounded-lg flex items-center justify-center">
                      <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                      </svg>
                    </span>
                    Dunia Usaha & Industri
                  </h3>
                  <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                    <li>• CSR Perusahaan</li>
                    <li>• Bank dan Lembaga Keuangan</li>
                    <li>• Penerbit Buku Anak</li>
                    <li>• Produsen APE</li>
                    <li>• Media Massa</li>
                  </ul>
                </div>
              </div>

              <!-- CTA -->
              <div class="card p-8 bg-gradient-to-r from-teal-50 to-green-50 dark:from-teal-900/20 dark:to-green-900/20 text-center">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Tertarik Menjadi Mitra PAUD?</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                  Bergabunglah dalam gerakan untuk mewujudkan layanan PAUD berkualitas
                </p>
                <button @click="activeTab = 'pendaftaran'" class="btn-primary">
                  Daftar Sekarang
                </button>
              </div>
            </div>

            <!-- Tab: Pendaftaran Mitra -->
            <div v-else-if="activeTab === 'pendaftaran'">
              <!-- Success State -->
              <div v-if="submitted" class="card p-8 text-center">
                <div class="w-20 h-20 mx-auto mb-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                  <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Pendaftaran Berhasil!</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                  Terima kasih telah mendaftar sebagai Mitra PAUD. Tim kami akan menghubungi Anda untuk proses verifikasi.
                </p>
                <button @click="resetForm" class="btn-primary">
                  Daftar Lembaga Lain
                </button>
              </div>

              <!-- Form -->
              <div v-else class="card p-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Form Pendaftaran Mitra</h2>
                
                <form @submit.prevent="handleSubmit" class="space-y-6">
                  <!-- Informasi Lembaga -->
                  <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Informasi Lembaga</h3>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Lembaga/Organisasi *</label>
                    <input 
                      v-model="form.namaLembaga"
                      type="text"
                      required
                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                      placeholder="Masukkan nama lembaga"
                    />
                  </div>

                  <div class="grid md:grid-cols-2 gap-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Lembaga *</label>
                      <select 
                        v-model="form.jenis"
                        required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                      >
                        <option value="">Pilih jenis lembaga</option>
                        <option v-for="jenis in jenisOptions" :key="jenis" :value="jenis">{{ jenis }}</option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bidang Kerjasama *</label>
                      <select 
                        v-model="form.bidangKerjasama"
                        required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                      >
                        <option value="">Pilih bidang kerjasama</option>
                        <option v-for="bidang in bidangOptions" :key="bidang" :value="bidang">{{ bidang }}</option>
                      </select>
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat *</label>
                    <textarea 
                      v-model="form.alamat"
                      rows="2"
                      required
                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                      placeholder="Alamat lengkap lembaga"
                    ></textarea>
                  </div>

                  <!-- Kontak Person -->
                  <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4 pt-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Penanggung Jawab</h3>
                  </div>

                  <div class="grid md:grid-cols-2 gap-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Penanggung Jawab *</label>
                      <input 
                        v-model="form.penanggungJawab"
                        type="text"
                        required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                        placeholder="Nama lengkap"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jabatan *</label>
                      <input 
                        v-model="form.jabatan"
                        type="text"
                        required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                        placeholder="Jabatan di lembaga"
                      />
                    </div>
                  </div>

                  <div class="grid md:grid-cols-2 gap-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Telepon *</label>
                      <input 
                        v-model="form.telepon"
                        type="tel"
                        required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                        placeholder="08xxxxxxxxxx"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                      <input 
                        v-model="form.email"
                        type="email"
                        required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                        placeholder="email@lembaga.com"
                      />
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi & Rencana Kontribusi</label>
                    <textarea 
                      v-model="form.deskripsi"
                      rows="4"
                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                      placeholder="Jelaskan profil lembaga dan rencana kontribusi untuk PAUD..."
                    ></textarea>
                  </div>

                  <button 
                    type="submit"
                    :disabled="loading"
                    class="w-full btn-primary py-4 disabled:opacity-50"
                  >
                    <span v-if="loading" class="flex items-center justify-center gap-2">
                      <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      Mengirim...
                    </span>
                    <span v-else>Daftar Sebagai Mitra</span>
                  </button>
                </form>
              </div>
            </div>

            <!-- Tab: List Mitra PAUD -->
            <div v-else-if="activeTab === 'list'">
              <div class="card p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Daftar Mitra PAUD Kota Surabaya</h2>
                
                <!-- Filter & Search -->
                <div class="grid md:grid-cols-2 gap-4 mb-6">
                  <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Cari nama mitra..."
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                  />
                  <select
                    v-model="filterJenis"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                  >
                    <option value="">Semua Jenis</option>
                    <option v-for="jenis in jenisOptions" :key="jenis" :value="jenis">{{ jenis }}</option>
                  </select>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                  <div class="p-4 bg-teal-50 dark:bg-teal-900/20 rounded-lg text-center">
                    <p class="text-2xl font-bold text-teal-600">{{ filteredMitra.length }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Mitra</p>
                  </div>
                  <div class="p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg text-center">
                    <p class="text-2xl font-bold text-orange-600">{{ mitraList.filter(m => m.jenis === 'Lembaga Pemerintah' && m.status === 'approved').length }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pemerintah</p>
                  </div>
                  <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg text-center">
                    <p class="text-2xl font-bold text-green-600">{{ mitraList.filter(m => m.jenis === 'Organisasi Masyarakat' && m.status === 'approved').length }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Ormas</p>
                  </div>
                  <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-center">
                    <p class="text-2xl font-bold text-blue-600">{{ mitraList.filter(m => m.jenis === 'Dunia Usaha/Industri' && m.status === 'approved').length }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Swasta</p>
                  </div>
                </div>

                <!-- Mitra List -->
                <div class="space-y-4">
                  <div
                    v-for="mitra in filteredMitra"
                    :key="mitra.id"
                    class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg"
                  >
                    <div class="flex items-center gap-4">
                      <div class="w-12 h-12 bg-teal-100 dark:bg-teal-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                      </div>
                      <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ mitra.namaLembaga }}</h3>
                        <p class="text-sm text-gray-500">{{ mitra.bidangKerjasama }}</p>
                      </div>
                    </div>
                    <span class="px-3 py-1 text-xs font-medium rounded-full" :class="{
                      'bg-orange-100 text-orange-700': mitra.jenis === 'Lembaga Pemerintah',
                      'bg-green-100 text-green-700': mitra.jenis === 'Organisasi Masyarakat',
                      'bg-blue-100 text-blue-700': mitra.jenis === 'Perguruan Tinggi',
                      'bg-pink-100 text-pink-700': mitra.jenis === 'Dunia Usaha/Industri',
                      'bg-gray-100 text-gray-700': !['Lembaga Pemerintah', 'Organisasi Masyarakat', 'Perguruan Tinggi', 'Dunia Usaha/Industri'].includes(mitra.jenis)
                    }">
                      {{ mitra.jenis }}
                    </span>
                  </div>

                  <div v-if="filteredMitra.length === 0" class="text-center py-12 text-gray-500">
                    Tidak ada mitra yang ditemukan.
                  </div>
                </div>
              </div>

              <!-- CTA -->
              <div class="card p-6 bg-gradient-to-r from-teal-50 to-green-50 dark:from-teal-900/20 dark:to-green-900/20 text-center">
                <p class="text-gray-600 dark:text-gray-400 mb-4">Ingin bergabung sebagai Mitra PAUD?</p>
                <button @click="activeTab = 'pendaftaran'" class="btn-primary">
                  Daftar Sekarang
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
