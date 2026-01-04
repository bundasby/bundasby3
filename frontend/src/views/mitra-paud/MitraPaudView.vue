<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import MitraPaudSidebar from '@/components/layout/MitraPaudSidebar.vue'

const route = useRoute()

// Tab state
const activeTab = ref('pengertian')

// Form for pendaftaran
const form = ref({
  // Responden
  namaResponden: '',
  jabatanResponden: '',
  nomorHpResponden: '',
  
  // Organisasi
  namaOrganisasi: '',
  kategori: '',
  alamatKantor: '',
  teleponOrganisasi: '',
  emailOrganisasi: '',
  websiteSosmed: '',
  namaDirektur: '',
  tujuanOrganisasi: '',
  
  // Kolaborasi
  bentukKolaborasi: [],
  kolaborasiLainnya: '',
  catatanKolaborasi: '',
  
  // Files
  logoFile: null,
  suratKesanggupanFile: null,
  linkDokumenPendukung: ''
})

const loading = ref(false)
const submitted = ref(false)

const kategoriOptions = [
  'Organisasi Masyarakat & Kader',
  'Organisasi Berbasis Keagamaan',
  'Organisasi Profesi & Seni',
  'Pemerintah & Kesehatan',
  'Dunia Usaha & Industri (DUDI)',
  'Akademisi & Unsur Civil Society'
]

const bentukKolaborasiOptions = [
  'Pendidikan dan Pelatihan',
  'Kesehatan dan Gizi',
  'Perlindungan Anak',
  'Pemberdayaan Masyarakat',
  'CSR dan Pendanaan',
  'Teknologi dan Inovasi',
  'Media dan Komunikasi'
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
      { id: 1, namaLembaga: 'Dinas Pendidikan Kota Surabaya', kategori: 'Pemerintah & Kesehatan', jenisDukungan: ['Advokasi', 'Sosialisasi'], status: 'approved' },
      { id: 2, namaLembaga: 'HIMPAUDI Kota Surabaya', kategori: 'Organisasi Profesi & Seni', jenisDukungan: ['Pelatihan Guru'], status: 'approved' },
      { id: 3, namaLembaga: 'Universitas Negeri Surabaya', kategori: 'Akademisi & Unsur Civil Society', jenisDukungan: ['Gizi & Stunting'], status: 'approved' },
      { id: 4, namaLembaga: 'Bank Jatim', kategori: 'Dunia Usaha & Industri (DUDI)', jenisDukungan: ['Bantuan Sarana'], status: 'approved' },
      { id: 5, namaLembaga: 'IGTKI Kota Surabaya', kategori: 'Organisasi Profesi & Seni', jenisDukungan: ['Pelatihan Guru'], status: 'approved' },
      { id: 6, namaLembaga: 'Dinas Kesehatan Kota Surabaya', kategori: 'Pemerintah & Kesehatan', jenisDukungan: ['Gizi & Stunting'], status: 'approved' },
    ]
  }
}


const filteredMitra = computed(() => {
  return mitraList.value.filter(m => {
    const matchSearch = m.namaLembaga.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchJenis = !filterJenis.value || m.kategori === filterJenis.value
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
    namaResponden: '',
    jabatanResponden: '',
    nomorHpResponden: '',
    namaOrganisasi: '',
    kategori: '',
    alamatKantor: '',
    teleponOrganisasi: '',
    emailOrganisasi: '',
    websiteSosmed: '',
    namaDirektur: '',
    tujuanOrganisasi: '',
    bentukKolaborasi: [],
    kolaborasiLainnya: '',
    catatanKolaborasi: '',
    logoFile: null,
    suratKesanggupanFile: null,
    linkDokumenPendukung: ''
  }
  submitted.value = false
}

const scrollToForm = () => {
  const el = document.getElementById('form-pendaftaran')
  if (el) {
    el.scrollIntoView({ behavior: 'smooth' })
  }
}

onMounted(() => {
  loadMitra()
  // Handle query param for tab
  if (route.query.tab === 'pendaftaran') {
    activeTab.value = 'pengertian'
    setTimeout(() => scrollToForm(), 500)
  } else if (route.query.tab) {
    activeTab.value = route.query.tab
  }
})
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Hero -->
    <section class="bg-gradient-to-br from-teal-600 to-green-700 text-white py-16">
      <div class="section-container text-center">
        <h1 class="text-3xl md:text-5xl font-bold mb-6 leading-tight">Sinergi Bareng Bunda PAUD:<br>Bangun Generasi Emas Surabaya</h1>
        <p class="text-teal-100 text-lg max-w-3xl mx-auto mb-8">
          Mewujudkan PAUD Holistik Integratif (HI) dan mendukung Gerakan Transisi PAUD-SD yang Menyenangkan melalui kolaborasi Pentahelix.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
          <button @click="scrollToForm()" class="bg-white text-teal-700 px-6 py-3 rounded-lg font-bold shadow-lg hover:bg-gray-100 transition-colors">
            Daftar Menjadi Mitra
          </button>
          <a href="#" class="bg-teal-800 text-white px-6 py-3 rounded-lg font-bold hover:bg-teal-900 transition-colors border border-teal-500">
            Unduh Pedoman Mitra
          </a>
        </div>
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
              <!-- Section 2: Siapa Mitra PAUD? -->
              <div class="mb-12">
                <div class="card p-8 bg-teal-50 dark:bg-teal-900/10 mb-8 border-l-4 border-teal-500">
                  <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Siapa Mitra PAUD?</h2>
                  <p class="text-gray-700 dark:text-gray-300 text-lg leading-relaxed">
                    <strong>Mitra PAUD</strong> adalah organisasi, lembaga, atau individu yang berkolaborasi dalam mendukung layanan PAUD berkualitas.
                  </p>
                </div>

                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 text-center">Keuntungan Menjadi Mitra</h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                  <!-- Publikasi -->
                  <div class="card p-6 text-center hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 mx-auto bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mb-4">
                      <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                      </svg>
                    </div>
                    <h4 class="font-bold text-gray-900 dark:text-white mb-2">Publikasi & KIE</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Menampilkan e-book, media promosi, dan artikel kegiatan di website resmi.</p>
                  </div>
                  <!-- Kalender -->
                  <div class="card p-6 text-center hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 mx-auto bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mb-4">
                      <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                    </div>
                    <h4 class="font-bold text-gray-900 dark:text-white mb-2">Kalender Kegiatan</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Mempromosikan kelas, webinar, atau aksi sosial melalui kalender website.</p>
                  </div>
                  <!-- Apresiasi -->
                  <div class="card p-6 text-center hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 mx-auto bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center mb-4">
                      <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                      </svg>
                    </div>
                    <h4 class="font-bold text-gray-900 dark:text-white mb-2">Apresiasi</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pengakuan resmi sebagai penggerak pendidikan di Kota Surabaya.</p>
                  </div>
                  <!-- Jejaring -->
                  <div class="card p-6 text-center hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 mx-auto bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mb-4">
                      <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                      </svg>
                    </div>
                    <h4 class="font-bold text-gray-900 dark:text-white mb-2">Jejaring</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Akses kolaborasi dengan Kader Surabaya Hebat (KSH) dan perangkat daerah.</p>
                  </div>
                </div>
              </div>

              <!-- Section 3: Kategori Mitra -->
              <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Kategori Mitra</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                  <!-- Box 1 -->
                  <div class="card p-6 border-t-4 border-pink-500">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Organisasi Masyarakat & Kader</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">TP PKK, Dharma Wanita, Persit, Jalasenastri, Bhayangkari, dan Kader Surabaya Hebat.</p>
                  </div>
                  <!-- Box 2 -->
                  <div class="card p-6 border-t-4 border-green-500">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Organisasi Berbasis Keagamaan</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Muslimat NU, Aisyiyah, Persistri, WKRI, PGI, PHDI, Walubi, dan lembaga keagamaan lainnya.</p>
                  </div>
                  <!-- Box 3 -->
                  <div class="card p-6 border-t-4 border-purple-500">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Organisasi Profesi & Seni</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">IGTKI-PGRI, HIMPAUDI, GOPKI, IGRA, dan lembaga seni/budaya.</p>
                  </div>
                  <!-- Box 4 -->
                  <div class="card p-6 border-t-4 border-blue-500">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Pemerintah & Kesehatan</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Dinas Pendidikan, Dinkes, DP3APPKB, RSUD Kota/Provinsi, Kecamatan, dan Kelurahan.</p>
                  </div>
                  <!-- Box 5 -->
                  <div class="card p-6 border-t-4 border-orange-500">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Dunia Usaha & Industri (DUDI)</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">BUMN/BUMD, Pengelola CSR, Perbankan, Penerbit Buku, dan Produsen APE.</p>
                  </div>
                  <!-- Box 6 -->
                  <div class="card p-6 border-t-4 border-teal-500">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Akademisi & Unsur Civil Society</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Perguruan Tinggi (UNESA, UNAIR, ITS), Lembaga Penelitian, dan NGO/CSO terkait PAUD HI.</p>
                  </div>
                </div>
              </div>

              <!-- Section 4: Alur Pendaftaran -->
              <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 text-center pt-8 border-t border-gray-200 dark:border-gray-800">Alur Kegiatan Mitra</h2>
                <div class="grid md:grid-cols-3 gap-8 relative">
                  <!-- Connector Line (Desktop) -->
                  <div class="hidden md:block absolute top-1/2 left-0 w-full h-1 bg-gray-200 dark:bg-gray-700 -z-10 transform -translate-y-1/2"></div>
                  
                  <!-- Step 1 -->
                  <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-700 relative z-10 text-center">
                    <div class="w-12 h-12 mx-auto bg-teal-600 text-white rounded-full flex items-center justify-center font-bold text-xl mb-4 shadow-lg shadow-teal-500/30">1</div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Registrasi & Komitmen</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Isi formulir pendaftaran dan unggah Surat Pernyataan Kesanggupan Mitra.</p>
                  </div>
                  <!-- Step 2 -->
                  <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-700 relative z-10 text-center">
                    <div class="w-12 h-12 mx-auto bg-teal-600 text-white rounded-full flex items-center justify-center font-bold text-xl mb-4 shadow-lg shadow-teal-500/30">2</div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Rencana Kerja</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Sampaikan rencana kegiatan (Advokasi, Penguatan Ekosistem, atau Alat Bantu).</p>
                  </div>
                  <!-- Step 3 -->
                  <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-700 relative z-10 text-center">
                    <div class="w-12 h-12 mx-auto bg-teal-600 text-white rounded-full flex items-center justify-center font-bold text-xl mb-4 shadow-lg shadow-teal-500/30">3</div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Dokumentasi & Pelaporan</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Sampaikan laporan pelaksanaan kegiatan untuk dipublikasikan sebagai inspirasi.</p>
                  </div>
                </div>
              </div>

              <!-- Section 5: Formulir Pendaftaran -->
              <div id="form-pendaftaran" class="mb-12 scroll-mt-24">
                <div class="card p-8 border border-teal-200 dark:border-teal-800 shadow-xl shadow-teal-900/5">
                  <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Formulir Pendaftaran Mitra</h2>
                    <p class="text-gray-600 dark:text-gray-400">Bergabunglah bersama kami membangun pendidikan Surabaya</p>
                  </div>

                  <!-- Success Message -->
                  <div v-if="submitted" class="text-center py-12">
                    <div class="w-20 h-20 mx-auto mb-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                      <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Pendaftaran Berhasil!</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                      Terima kasih telah mendaftar. Tim kami akan segera menghubungi Anda.
                    </p>
                    <button @click="resetForm" class="btn-primary">Kirim Lagi</button>
                  </div>

                  <!-- Form Input -->
                  <form v-else @submit.prevent="handleSubmit" class="space-y-6 max-w-3xl mx-auto">
                    
                    <!-- Section: Data Responden -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                      <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Responden</h3>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                      <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Responden *</label>
                        <input v-model="form.namaResponden" type="text" required class="form-input w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500" placeholder="Nama lengkap">
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jabatan Responden *</label>
                        <input v-model="form.jabatanResponden" type="text" required class="form-input w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500" placeholder="Jabatan di organisasi">
                      </div>
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nomor HP Responden (terhubung dengan WA) *</label>
                      <input v-model="form.nomorHpResponden" type="tel" required class="form-input w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500" placeholder="08xxxxxxxxxx">
                    </div>

                    <!-- Section: Data Organisasi -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 pt-6">
                      <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Organisasi/Perusahaan</h3>
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Organisasi/Perusahaan *</label>
                      <input v-model="form.namaOrganisasi" type="text" required class="form-input w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500" placeholder="Nama resmi organisasi">
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kategori Mitra *</label>
                      <select v-model="form.kategori" required class="form-select w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Pilih Kategori</option>
                        <option v-for="opt in kategoriOptions" :key="opt" :value="opt">{{ opt }}</option>
                      </select>
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat Kantor *</label>
                      <textarea v-model="form.alamatKantor" rows="2" required class="form-textarea w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500" placeholder="Alamat lengkap kantor"></textarea>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                      <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nomor Telepon Organisasi *</label>
                        <input v-model="form.teleponOrganisasi" type="tel" required class="form-input w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500" placeholder="021-xxxxxxx">
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Organisasi *</label>
                        <input v-model="form.emailOrganisasi" type="email" required class="form-input w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500" placeholder="email@organisasi.com">
                      </div>
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tautan Website atau Media Sosial *</label>
                      <input v-model="form.websiteSosmed" type="url" required class="form-input w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500" placeholder="https://...">
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Direktur/Ketua/Koordinator *</label>
                      <input v-model="form.namaDirektur" type="text" required class="form-input w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500" placeholder="Nama pimpinan organisasi">
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tujuan/Orientasi Organisasi *</label>
                      <textarea v-model="form.tujuanOrganisasi" rows="3" required class="form-textarea w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500" placeholder="Jelaskan visi, misi, dan tujuan organisasi"></textarea>
                    </div>

                    <!-- Section: Kolaborasi -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 pt-6">
                      <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Bentuk Kolaborasi</h3>
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Bentuk Kolaborasi yang Diharapkan (Boleh lebih dari satu) *</label>
                      <div class="grid md:grid-cols-2 gap-3">
                        <label v-for="kolaborasi in bentukKolaborasiOptions" :key="kolaborasi" class="flex items-center gap-3 p-3 border rounded-lg bg-gray-50 dark:bg-gray-800 cursor-pointer hover:bg-teal-50 dark:hover:bg-teal-900/20">
                          <input type="checkbox" :value="kolaborasi" v-model="form.bentukKolaborasi" class="text-teal-600 focus:ring-teal-500 rounded">
                          <span class="text-sm text-gray-700 dark:text-gray-300">{{ kolaborasi }}</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border rounded-lg bg-gray-50 dark:bg-gray-800 cursor-pointer hover:bg-teal-50 dark:hover:bg-teal-900/20">
                          <input type="checkbox" value="Lainnya" v-model="form.bentukKolaborasi" class="text-teal-600 focus:ring-teal-500 rounded">
                          <span class="text-sm text-gray-700 dark:text-gray-300">Lainnya</span>
                        </label>
                      </div>
                      <div v-if="form.bentukKolaborasi.includes('Lainnya')" class="mt-3">
                        <input v-model="form.kolaborasiLainnya" type="text" class="form-input w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500" placeholder="Sebutkan bentuk kolaborasi lainnya...">
                      </div>
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan Bentuk Kolaborasi</label>
                      <textarea v-model="form.catatanKolaborasi" rows="3" class="form-textarea w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500" placeholder="Jelaskan detail rencana kolaborasi Anda..."></textarea>
                    </div>

                    <!-- Section: Upload Dokumen -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 pt-6">
                      <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Upload Dokumen</h3>
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo Organisasi/Perusahaan *</label>
                      <p class="text-xs text-gray-500 mb-2">Format PNG, latar belakang transparan, maksimum 1 MB. Logo akan ditampilkan di website dan kegiatan Bunda PAUD.</p>
                      <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <div class="space-y-1 text-center">
                          <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                          <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                            <label for="logo-upload" class="relative cursor-pointer rounded-md font-medium text-teal-600 hover:text-teal-500">
                              <span>Upload Logo</span>
                              <input id="logo-upload" name="logo-upload" type="file" accept=".png" class="sr-only">
                            </label>
                          </div>
                          <p class="text-xs text-gray-500">PNG, max 1MB</p>
                        </div>
                      </div>
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload Surat Pernyataan Kesanggupan *</label>
                      <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <div class="space-y-1 text-center">
                          <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                          <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                            <label for="surat-upload" class="relative cursor-pointer rounded-md font-medium text-teal-600 hover:text-teal-500">
                              <span>Upload Surat</span>
                              <input id="surat-upload" name="surat-upload" type="file" accept=".pdf,.doc,.docx" class="sr-only">
                            </label>
                          </div>
                          <p class="text-xs text-gray-500">PDF, DOC, DOCX up to 10MB</p>
                        </div>
                      </div>
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Link Dokumen Pendukung (Opsional)</label>
                      <input v-model="form.linkDokumenPendukung" type="url" class="form-input w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500" placeholder="https://drive.google.com/...">
                    </div>

                    <button type="submit" :disabled="loading" class="w-full btn-primary py-4 text-lg font-bold shadow-lg shadow-teal-500/30">
                      <span v-if="loading">Mengirim pendaftaran...</span>
                      <span v-else>SUBMIT PENDAFTARAN</span>
                    </button>
                  </form>
                </div>
              </div>

              <!-- Section 6: Footer Download Center -->
              <div class="bg-gray-800 text-white rounded-xl p-8 mb-8">
                <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                  </svg>
                  Download Center
                </h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                  <a href="#" class="flex items-center gap-3 p-4 bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors">
                    <svg class="w-8 h-8 text-teal-400" fill="currentColor" viewBox="0 0 20 20"><path d="M4 18h12v2H4zM4 2h12v12H4zM11 6v6h-2V6H6l4-4 4 4z"/></svg>
                    <span class="text-sm font-medium">Template Surat Pernyataan</span>
                  </a>
                  <a href="#" class="flex items-center gap-3 p-4 bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors">
                    <svg class="w-8 h-8 text-teal-400" fill="currentColor" viewBox="0 0 20 20"><path d="M4 18h12v2H4zM4 2h12v12H4zM11 6v6h-2V6H6l4-4 4 4z"/></svg>
                    <span class="text-sm font-medium">POS Mitra Advokasi</span>
                  </a>
                  <a href="#" class="flex items-center gap-3 p-4 bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors">
                    <svg class="w-8 h-8 text-teal-400" fill="currentColor" viewBox="0 0 20 20"><path d="M4 18h12v2H4zM4 2h12v12H4zM11 6v6h-2V6H6l4-4 4 4z"/></svg>
                    <span class="text-sm font-medium">POS Mitra Penguatan Ekosistem</span>
                  </a>
                  <a href="#" class="flex items-center gap-3 p-4 bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors">
                    <svg class="w-8 h-8 text-teal-400" fill="currentColor" viewBox="0 0 20 20"><path d="M4 18h12v2H4zM4 2h12v12H4zM11 6v6h-2V6H6l4-4 4 4z"/></svg>
                    <span class="text-sm font-medium">POS Mitra Alat Bantu</span>
                  </a>
                </div>
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
                    <option value="">Semua Kategori</option>
                    <option v-for="kat in kategoriOptions" :key="kat" :value="kat">{{ kat }}</option>
                  </select>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                  <div class="p-4 bg-teal-50 dark:bg-teal-900/20 rounded-lg text-center">
                    <p class="text-2xl font-bold text-teal-600">{{ filteredMitra.length }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Mitra</p>
                  </div>
                  <div class="p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg text-center">
                    <p class="text-2xl font-bold text-orange-600">{{ mitraList.filter(m => m.kategori.includes('Pemerintah') && m.status === 'approved').length }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pemerintah</p>
                  </div>
                  <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg text-center">
                    <p class="text-2xl font-bold text-green-600">{{ mitraList.filter(m => m.kategori.includes('Masyarakat') && m.status === 'approved').length }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Ormas</p>
                  </div>
                  <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-center">
                    <p class="text-2xl font-bold text-blue-600">{{ mitraList.filter(m => m.kategori.includes('Usaha') && m.status === 'approved').length }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">DUDI</p>
                  </div>
                </div>

                <!-- Mitra List -->
                <div class="space-y-4">
                  <div
                    v-for="mitra in filteredMitra"
                    :key="mitra.id"
                    class="flex flex-col md:flex-row md:items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg gap-4"
                  >
                    <div class="flex items-start gap-4">
                      <div class="w-12 h-12 bg-teal-100 dark:bg-teal-900/30 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                      </div>
                      <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ mitra.namaLembaga }}</h3>
                        <p class="text-sm text-gray-500">{{ mitra.kategori }}</p>
                        <div class="flex flex-wrap gap-2 mt-2">
                          <span v-for="dukungan in mitra.jenisDukungan" :key="dukungan" class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded text-gray-600 dark:text-gray-300">
                            {{ dukungan }}
                          </span>
                        </div>
                      </div>
                    </div>
                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700 self-start md:self-center">
                      Approved
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
                <button @click="activeTab = 'pengertian'; scrollToForm()" class="btn-primary">
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
