<script setup>
import { ref, onMounted, computed } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

// Tab state
const activeTab = ref('list')

// Data
const mitraList = ref([])
const programKerjaList = ref([])
const laporanList = ref([])
const loading = ref(false)
const showModal = ref(false)
const modalType = ref('')
const editingItem = ref(null)

// Form states
const programForm = ref({
  mitraId: '',
  nama: '',
  deskripsi: '',
  tanggalMulai: '',
  tanggalSelesai: '',
  status: 'active'
})

const laporanForm = ref({
  mitraId: '',
  programId: '',
  judul: '',
  deskripsi: '',
  tanggal: '',
  dokumentasi: ''
})

// Filters
const searchQuery = ref('')
const filterStatus = ref('')
const filterKategori = ref('')

const kategoriOptions = [
  'Organisasi Masyarakat & Kader',
  'Organisasi Berbasis Keagamaan',
  'Organisasi Profesi & Seni',
  'Pemerintah & Kesehatan',
  'Dunia Usaha & Industri (DUDI)',
  'Akademisi & Unsur Civil Society'
]

// Load data from localStorage
const loadData = () => {
  // Load mitra
  const savedMitra = localStorage.getItem('bp_mitra_paud')
  if (savedMitra) {
    mitraList.value = JSON.parse(savedMitra)
  } else {
    mitraList.value = [
      { id: 1, namaOrganisasi: 'Dinas Pendidikan Kota Surabaya', kategori: 'Pemerintah & Kesehatan', bentukKolaborasi: ['Advokasi', 'Sosialisasi'], status: 'approved', createdAt: '2025-01-01' },
      { id: 2, namaOrganisasi: 'HIMPAUDI Kota Surabaya', kategori: 'Organisasi Profesi & Seni', bentukKolaborasi: ['Pendidikan dan Pelatihan'], status: 'approved', createdAt: '2025-01-02' },
      { id: 3, namaOrganisasi: 'Universitas Negeri Surabaya', kategori: 'Akademisi & Unsur Civil Society', bentukKolaborasi: ['Kesehatan dan Gizi'], status: 'approved', createdAt: '2025-01-03' },
      { id: 4, namaOrganisasi: 'PT Telkom Indonesia', kategori: 'Dunia Usaha & Industri (DUDI)', bentukKolaborasi: ['CSR dan Pendanaan', 'Teknologi dan Inovasi'], status: 'pending', createdAt: '2025-01-04' },
      { id: 5, namaOrganisasi: 'Yayasan Cinta Anak', kategori: 'Akademisi & Unsur Civil Society', bentukKolaborasi: ['Perlindungan Anak'], status: 'pending', createdAt: '2025-01-05' },
    ]
    localStorage.setItem('bp_mitra_paud', JSON.stringify(mitraList.value))
  }

  // Load program kerja
  const savedProgram = localStorage.getItem('bp_mitra_program_kerja')
  if (savedProgram) {
    programKerjaList.value = JSON.parse(savedProgram)
  } else {
    programKerjaList.value = [
      { id: 1, mitraId: 1, nama: 'Sosialisasi PAUD HI', deskripsi: 'Kegiatan sosialisasi PAUD Holistik Integratif ke seluruh kecamatan', tanggalMulai: '2025-01-15', tanggalSelesai: '2025-03-15', status: 'active' },
      { id: 2, mitraId: 2, nama: 'Pelatihan Guru PAUD', deskripsi: 'Workshop peningkatan kompetensi guru PAUD', tanggalMulai: '2025-02-01', tanggalSelesai: '2025-02-28', status: 'completed' },
    ]
    localStorage.setItem('bp_mitra_program_kerja', JSON.stringify(programKerjaList.value))
  }

  // Load laporan
  const savedLaporan = localStorage.getItem('bp_mitra_laporan')
  if (savedLaporan) {
    laporanList.value = JSON.parse(savedLaporan)
  } else {
    laporanList.value = [
      { id: 1, mitraId: 1, programId: 1, judul: 'Laporan Sosialisasi Bulan Januari', deskripsi: 'Telah dilaksanakan sosialisasi di 10 kecamatan', tanggal: '2025-01-31', dokumentasi: '' },
    ]
    localStorage.setItem('bp_mitra_laporan', JSON.stringify(laporanList.value))
  }
}

// Computed
const approvedMitra = computed(() => {
  return mitraList.value.filter(m => {
    const matchStatus = m.status === 'approved'
    const matchSearch = !searchQuery.value || m.namaOrganisasi?.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchKategori = !filterKategori.value || m.kategori === filterKategori.value
    return matchStatus && matchSearch && matchKategori
  })
})

const pendingMitra = computed(() => {
  return mitraList.value.filter(m => m.status === 'pending')
})

const getMitraName = (mitraId) => {
  const mitra = mitraList.value.find(m => m.id === mitraId)
  return mitra?.namaOrganisasi || mitra?.namaLembaga || '-'
}

const getProgramName = (programId) => {
  const program = programKerjaList.value.find(p => p.id === programId)
  return program?.nama || '-'
}

// Actions
const approveMitra = (mitra) => {
  mitra.status = 'approved'
  mitra.approvedAt = new Date().toISOString()
  localStorage.setItem('bp_mitra_paud', JSON.stringify(mitraList.value))
}

const rejectMitra = (mitra) => {
  if (confirm('Apakah Anda yakin ingin menolak pendaftaran ini?')) {
    mitra.status = 'rejected'
    mitra.rejectedAt = new Date().toISOString()
    localStorage.setItem('bp_mitra_paud', JSON.stringify(mitraList.value))
  }
}

const deleteMitra = (mitra) => {
  if (confirm('Apakah Anda yakin ingin menghapus mitra ini?')) {
    mitraList.value = mitraList.value.filter(m => m.id !== mitra.id)
    localStorage.setItem('bp_mitra_paud', JSON.stringify(mitraList.value))
  }
}

// Program Kerja CRUD
const openProgramModal = (item = null) => {
  editingItem.value = item
  modalType.value = 'program'
  if (item) {
    programForm.value = { ...item }
  } else {
    programForm.value = {
      mitraId: '',
      nama: '',
      deskripsi: '',
      tanggalMulai: '',
      tanggalSelesai: '',
      status: 'active'
    }
  }
  showModal.value = true
}

const saveProgram = () => {
  if (editingItem.value) {
    const index = programKerjaList.value.findIndex(p => p.id === editingItem.value.id)
    programKerjaList.value[index] = { ...programForm.value }
  } else {
    programKerjaList.value.push({
      id: Date.now(),
      ...programForm.value,
      mitraId: Number(programForm.value.mitraId)
    })
  }
  localStorage.setItem('bp_mitra_program_kerja', JSON.stringify(programKerjaList.value))
  showModal.value = false
}

const deleteProgram = (program) => {
  if (confirm('Apakah Anda yakin ingin menghapus program kerja ini?')) {
    programKerjaList.value = programKerjaList.value.filter(p => p.id !== program.id)
    localStorage.setItem('bp_mitra_program_kerja', JSON.stringify(programKerjaList.value))
  }
}

// Laporan CRUD
const openLaporanModal = (item = null) => {
  editingItem.value = item
  modalType.value = 'laporan'
  if (item) {
    laporanForm.value = { ...item }
  } else {
    laporanForm.value = {
      mitraId: '',
      programId: '',
      judul: '',
      deskripsi: '',
      tanggal: '',
      dokumentasi: ''
    }
  }
  showModal.value = true
}

const saveLaporan = () => {
  if (editingItem.value) {
    const index = laporanList.value.findIndex(l => l.id === editingItem.value.id)
    laporanList.value[index] = { ...laporanForm.value }
  } else {
    laporanList.value.push({
      id: Date.now(),
      ...laporanForm.value,
      mitraId: Number(laporanForm.value.mitraId),
      programId: Number(laporanForm.value.programId)
    })
  }
  localStorage.setItem('bp_mitra_laporan', JSON.stringify(laporanList.value))
  showModal.value = false
}

const deleteLaporan = (laporan) => {
  if (confirm('Apakah Anda yakin ingin menghapus laporan ini?')) {
    laporanList.value = laporanList.value.filter(l => l.id !== laporan.id)
    localStorage.setItem('bp_mitra_laporan', JSON.stringify(laporanList.value))
  }
}

const formatDate = (dateStr) => {
  if (!dateStr) return '-'
  return new Date(dateStr).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

onMounted(() => {
  loadData()
})
</script>

<template>
  <AdminLayout title="Kelola Mitra PAUD">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-teal-100 dark:bg-teal-900/30 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ approvedMitra.length }}</p>
            <p class="text-sm text-gray-500">Mitra Aktif</p>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ pendingMitra.length }}</p>
            <p class="text-sm text-gray-500">Menunggu Persetujuan</p>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
          </div>
          <div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ programKerjaList.length }}</p>
            <p class="text-sm text-gray-500">Program Kerja</p>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ laporanList.length }}</p>
            <p class="text-sm text-gray-500">Laporan Kegiatan</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
      <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="flex space-x-1 p-2">
          <button
            @click="activeTab = 'list'"
            class="px-4 py-2.5 text-sm font-medium rounded-lg transition-colors"
            :class="activeTab === 'list' 
              ? 'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400' 
              : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'"
          >
            📋 List Mitra PAUD
          </button>
          <button
            @click="activeTab = 'pending'"
            class="px-4 py-2.5 text-sm font-medium rounded-lg transition-colors flex items-center gap-2"
            :class="activeTab === 'pending' 
              ? 'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400' 
              : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'"
          >
            ✅ Acc Pendaftaran
            <span v-if="pendingMitra.length > 0" class="bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full">
              {{ pendingMitra.length }}
            </span>
          </button>
          <button
            @click="activeTab = 'program'"
            class="px-4 py-2.5 text-sm font-medium rounded-lg transition-colors"
            :class="activeTab === 'program' 
              ? 'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400' 
              : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'"
          >
            📁 Program Kerja
          </button>
          <button
            @click="activeTab = 'laporan'"
            class="px-4 py-2.5 text-sm font-medium rounded-lg transition-colors"
            :class="activeTab === 'laporan' 
              ? 'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400' 
              : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'"
          >
            📊 Laporan Kegiatan
          </button>
        </nav>
      </div>

      <div class="p-6">
        <!-- Tab: List Mitra PAUD -->
        <div v-if="activeTab === 'list'">
          <!-- Filters -->
          <div class="flex flex-col md:flex-row gap-4 mb-6">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari nama mitra..."
              class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500"
            />
            <select
              v-model="filterKategori"
              class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500"
            >
              <option value="">Semua Kategori</option>
              <option v-for="kat in kategoriOptions" :key="kat" :value="kat">{{ kat }}</option>
            </select>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama Organisasi</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Kategori</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Bentuk Kolaborasi</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tgl Bergabung</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="mitra in approvedMitra" :key="mitra.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                  <td class="px-4 py-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/30 rounded-lg flex items-center justify-center">
                        <span class="text-teal-600 font-bold">{{ (mitra.namaOrganisasi || mitra.namaLembaga || 'M').charAt(0) }}</span>
                      </div>
                      <span class="font-medium text-gray-900 dark:text-white">{{ mitra.namaOrganisasi || mitra.namaLembaga }}</span>
                    </div>
                  </td>
                  <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">{{ mitra.kategori }}</td>
                  <td class="px-4 py-4">
                    <div class="flex flex-wrap gap-1">
                      <span 
                        v-for="kolaborasi in (mitra.bentukKolaborasi || mitra.jenisDukungan || []).slice(0, 2)" 
                        :key="kolaborasi"
                        class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded"
                      >
                        {{ kolaborasi }}
                      </span>
                      <span 
                        v-if="(mitra.bentukKolaborasi || mitra.jenisDukungan || []).length > 2"
                        class="text-xs text-gray-500"
                      >
                        +{{ (mitra.bentukKolaborasi || mitra.jenisDukungan || []).length - 2 }} lainnya
                      </span>
                    </div>
                  </td>
                  <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">{{ formatDate(mitra.createdAt || mitra.approvedAt) }}</td>
                  <td class="px-4 py-4">
                    <button @click="deleteMitra(mitra)" class="text-red-600 hover:text-red-800 text-sm font-medium">
                      Hapus
                    </button>
                  </td>
                </tr>
                <tr v-if="approvedMitra.length === 0">
                  <td colspan="5" class="px-4 py-12 text-center text-gray-500">
                    Belum ada mitra yang terdaftar
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab: Acc Pendaftaran -->
        <div v-else-if="activeTab === 'pending'">
          <div v-if="pendingMitra.length === 0" class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <p class="text-gray-500 dark:text-gray-400">Tidak ada pendaftaran yang menunggu persetujuan</p>
          </div>

          <div v-else class="space-y-4">
            <div
              v-for="mitra in pendingMitra"
              :key="mitra.id"
              class="border border-gray-200 dark:border-gray-700 rounded-xl p-6"
            >
              <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                <div class="flex-1">
                  <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center">
                      <span class="text-yellow-600 font-bold text-lg">{{ (mitra.namaOrganisasi || 'M').charAt(0) }}</span>
                    </div>
                    <div>
                      <h3 class="font-semibold text-gray-900 dark:text-white">{{ mitra.namaOrganisasi }}</h3>
                      <p class="text-sm text-gray-500">{{ mitra.kategori }}</p>
                    </div>
                  </div>

                  <div class="grid md:grid-cols-2 gap-4 text-sm">
                    <div>
                      <span class="text-gray-500">Responden:</span>
                      <span class="ml-2 text-gray-900 dark:text-white">{{ mitra.namaResponden || '-' }}</span>
                    </div>
                    <div>
                      <span class="text-gray-500">Jabatan:</span>
                      <span class="ml-2 text-gray-900 dark:text-white">{{ mitra.jabatanResponden || '-' }}</span>
                    </div>
                    <div>
                      <span class="text-gray-500">No. HP:</span>
                      <span class="ml-2 text-gray-900 dark:text-white">{{ mitra.nomorHpResponden || '-' }}</span>
                    </div>
                    <div>
                      <span class="text-gray-500">Email:</span>
                      <span class="ml-2 text-gray-900 dark:text-white">{{ mitra.emailOrganisasi || '-' }}</span>
                    </div>
                  </div>

                  <div class="mt-4">
                    <span class="text-gray-500 text-sm">Bentuk Kolaborasi:</span>
                    <div class="flex flex-wrap gap-2 mt-2">
                      <span 
                        v-for="kolaborasi in (mitra.bentukKolaborasi || [])" 
                        :key="kolaborasi"
                        class="text-xs bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400 px-3 py-1 rounded-full"
                      >
                        {{ kolaborasi }}
                      </span>
                    </div>
                  </div>
                </div>

                <div class="flex gap-2">
                  <button
                    @click="approveMitra(mitra)"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium"
                  >
                    ✓ Setujui
                  </button>
                  <button
                    @click="rejectMitra(mitra)"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium"
                  >
                    ✕ Tolak
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tab: Program Kerja -->
        <div v-else-if="activeTab === 'program'">
          <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-gray-900 dark:text-white">Daftar Program Kerja Mitra</h3>
            <button @click="openProgramModal()" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-medium">
              + Tambah Program
            </button>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama Program</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Mitra</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Periode</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="program in programKerjaList" :key="program.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                  <td class="px-4 py-4">
                    <div>
                      <p class="font-medium text-gray-900 dark:text-white">{{ program.nama }}</p>
                      <p class="text-sm text-gray-500 truncate max-w-xs">{{ program.deskripsi }}</p>
                    </div>
                  </td>
                  <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">{{ getMitraName(program.mitraId) }}</td>
                  <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">
                    {{ formatDate(program.tanggalMulai) }} - {{ formatDate(program.tanggalSelesai) }}
                  </td>
                  <td class="px-4 py-4">
                    <span :class="[
                      'px-2 py-1 text-xs rounded-full',
                      program.status === 'active' ? 'bg-green-100 text-green-700' : 
                      program.status === 'completed' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700'
                    ]">
                      {{ program.status === 'active' ? 'Aktif' : program.status === 'completed' ? 'Selesai' : 'Draft' }}
                    </span>
                  </td>
                  <td class="px-4 py-4">
                    <div class="flex gap-2">
                      <button @click="openProgramModal(program)" class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                      <button @click="deleteProgram(program)" class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                    </div>
                  </td>
                </tr>
                <tr v-if="programKerjaList.length === 0">
                  <td colspan="5" class="px-4 py-12 text-center text-gray-500">
                    Belum ada program kerja
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab: Laporan Kegiatan -->
        <div v-else-if="activeTab === 'laporan'">
          <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-gray-900 dark:text-white">Daftar Laporan Kegiatan</h3>
            <button @click="openLaporanModal()" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-medium">
              + Tambah Laporan
            </button>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Judul Laporan</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Mitra</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Program</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tanggal</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="laporan in laporanList" :key="laporan.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                  <td class="px-4 py-4">
                    <div>
                      <p class="font-medium text-gray-900 dark:text-white">{{ laporan.judul }}</p>
                      <p class="text-sm text-gray-500 truncate max-w-xs">{{ laporan.deskripsi }}</p>
                    </div>
                  </td>
                  <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">{{ getMitraName(laporan.mitraId) }}</td>
                  <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">{{ getProgramName(laporan.programId) }}</td>
                  <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">{{ formatDate(laporan.tanggal) }}</td>
                  <td class="px-4 py-4">
                    <div class="flex gap-2">
                      <button @click="openLaporanModal(laporan)" class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                      <button @click="deleteLaporan(laporan)" class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                    </div>
                  </td>
                </tr>
                <tr v-if="laporanList.length === 0">
                  <td colspan="5" class="px-4 py-12 text-center text-gray-500">
                    Belum ada laporan kegiatan
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/50" @click="showModal = false"></div>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
            {{ editingItem ? 'Edit' : 'Tambah' }} {{ modalType === 'program' ? 'Program Kerja' : 'Laporan Kegiatan' }}
          </h3>

          <!-- Program Form -->
          <form v-if="modalType === 'program'" @submit.prevent="saveProgram" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mitra *</label>
              <select v-model="programForm.mitraId" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="">Pilih Mitra</option>
                <option v-for="mitra in approvedMitra" :key="mitra.id" :value="mitra.id">
                  {{ mitra.namaOrganisasi || mitra.namaLembaga }}
                </option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Program *</label>
              <input v-model="programForm.nama" type="text" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
              <textarea v-model="programForm.deskripsi" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai</label>
                <input v-model="programForm.tanggalMulai" type="date" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Selesai</label>
                <input v-model="programForm.tanggalSelesai" type="date" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
              <select v-model="programForm.status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="active">Aktif</option>
                <option value="completed">Selesai</option>
                <option value="draft">Draft</option>
              </select>
            </div>
            <div class="flex justify-end gap-3 pt-4">
              <button type="button" @click="showModal = false" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                Batal
              </button>
              <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">
                Simpan
              </button>
            </div>
          </form>

          <!-- Laporan Form -->
          <form v-else-if="modalType === 'laporan'" @submit.prevent="saveLaporan" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mitra *</label>
              <select v-model="laporanForm.mitraId" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="">Pilih Mitra</option>
                <option v-for="mitra in approvedMitra" :key="mitra.id" :value="mitra.id">
                  {{ mitra.namaOrganisasi || mitra.namaLembaga }}
                </option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Program Kerja</label>
              <select v-model="laporanForm.programId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="">Pilih Program (Opsional)</option>
                <option v-for="program in programKerjaList.filter(p => p.mitraId == laporanForm.mitraId)" :key="program.id" :value="program.id">
                  {{ program.nama }}
                </option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Laporan *</label>
              <input v-model="laporanForm.judul" type="text" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
              <textarea v-model="laporanForm.deskripsi" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
              <input v-model="laporanForm.tanggal" type="date" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link Dokumentasi</label>
              <input v-model="laporanForm.dokumentasi" type="url" placeholder="https://..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
            <div class="flex justify-end gap-3 pt-4">
              <button type="button" @click="showModal = false" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                Batal
              </button>
              <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">
                Simpan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
