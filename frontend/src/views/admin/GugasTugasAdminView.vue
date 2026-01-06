<script setup>
import { ref, onMounted, computed } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

// Tab state
const activeTab = ref('anggota')

// Data
const anggotaList = ref([])
const programKerjaList = ref([])
const laporanList = ref([])
const showModal = ref(false)
const modalType = ref('')
const editingItem = ref(null)

// Form states
const anggotaForm = ref({
  nama: '',
  jabatan: '',
  instansi: '',
  kategori: 'Anggota',
  nomorHp: '',
  email: ''
})

const programForm = ref({
  nama: '',
  deskripsi: '',
  tanggalMulai: '',
  tanggalSelesai: '',
  status: 'active'
})

const laporanForm = ref({
  programId: '',
  judul: '',
  deskripsi: '',
  tanggal: '',
  dokumentasi: ''
})

// Filters
const searchQuery = ref('')
const filterKategori = ref('')

const kategoriOptions = ['Pengarah', 'Ketua', 'Sekretariat', 'Anggota']

// Load data from localStorage
const loadData = () => {
  // Load anggota
  const savedAnggota = localStorage.getItem('bp_gugus_tugas_anggota')
  if (savedAnggota) {
    anggotaList.value = JSON.parse(savedAnggota)
  } else {
    anggotaList.value = [
      { id: 1, nama: 'Walikota Surabaya', jabatan: 'Pengarah', instansi: 'Pemerintah Kota Surabaya', kategori: 'Pengarah', nomorHp: '', email: '' },
      { id: 2, nama: 'Wakil Walikota Surabaya', jabatan: 'Pengarah', instansi: 'Pemerintah Kota Surabaya', kategori: 'Pengarah', nomorHp: '', email: '' },
      { id: 3, nama: 'Bunda PAUD Kota Surabaya', jabatan: 'Ketua', instansi: 'Pokja Bunda PAUD', kategori: 'Ketua', nomorHp: '', email: '' },
      { id: 4, nama: 'Kepala Dinas Pendidikan', jabatan: 'Sekretaris', instansi: 'Dinas Pendidikan Kota Surabaya', kategori: 'Sekretariat', nomorHp: '', email: '' },
      { id: 5, nama: 'Kabid PAUD Dikmas', jabatan: 'Wakil Sekretaris', instansi: 'Dinas Pendidikan Kota Surabaya', kategori: 'Sekretariat', nomorHp: '', email: '' },
      { id: 6, nama: 'Kepala Dinas Kesehatan', jabatan: 'Anggota', instansi: 'Dinas Kesehatan Kota Surabaya', kategori: 'Anggota', nomorHp: '', email: '' },
      { id: 7, nama: 'Kepala Dinas Sosial', jabatan: 'Anggota', instansi: 'Dinas Sosial Kota Surabaya', kategori: 'Anggota', nomorHp: '', email: '' },
      { id: 8, nama: 'Kepala DP3A', jabatan: 'Anggota', instansi: 'Dinas Pemberdayaan Perempuan dan Perlindungan Anak', kategori: 'Anggota', nomorHp: '', email: '' },
    ]
    localStorage.setItem('bp_gugus_tugas_anggota', JSON.stringify(anggotaList.value))
  }

  // Load program kerja
  const savedProgram = localStorage.getItem('bp_gugus_tugas_program')
  if (savedProgram) {
    programKerjaList.value = JSON.parse(savedProgram)
  } else {
    programKerjaList.value = [
      { id: 1, nama: 'Rapat Koordinasi Triwulan', deskripsi: 'Rapat koordinasi lintas OPD setiap 3 bulan', tanggalMulai: '2025-01-01', tanggalSelesai: '2025-12-31', status: 'active' },
      { id: 2, nama: 'Sosialisasi PAUD HI', deskripsi: 'Sosialisasi ke seluruh kecamatan tentang PAUD Holistik Integratif', tanggalMulai: '2025-02-01', tanggalSelesai: '2025-06-30', status: 'active' },
    ]
    localStorage.setItem('bp_gugus_tugas_program', JSON.stringify(programKerjaList.value))
  }

  // Load laporan
  const savedLaporan = localStorage.getItem('bp_gugus_tugas_laporan')
  if (savedLaporan) {
    laporanList.value = JSON.parse(savedLaporan)
  } else {
    laporanList.value = [
      { id: 1, programId: 1, judul: 'Laporan Rakor Triwulan I 2025', deskripsi: 'Hasil rapat koordinasi triwulan pertama tahun 2025', tanggal: '2025-03-31', dokumentasi: '' },
    ]
    localStorage.setItem('bp_gugus_tugas_laporan', JSON.stringify(laporanList.value))
  }
}

// Computed
const filteredAnggota = computed(() => {
  return anggotaList.value.filter(a => {
    const matchSearch = !searchQuery.value || 
      a.nama.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      a.instansi.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchKategori = !filterKategori.value || a.kategori === filterKategori.value
    return matchSearch && matchKategori
  })
})

const getProgramName = (programId) => {
  const program = programKerjaList.value.find(p => p.id === programId)
  return program?.nama || '-'
}

// Anggota CRUD
const openAnggotaModal = (item = null) => {
  editingItem.value = item
  modalType.value = 'anggota'
  if (item) {
    anggotaForm.value = { ...item }
  } else {
    anggotaForm.value = {
      nama: '',
      jabatan: '',
      instansi: '',
      kategori: 'Anggota',
      nomorHp: '',
      email: ''
    }
  }
  showModal.value = true
}

const saveAnggota = () => {
  if (editingItem.value) {
    const index = anggotaList.value.findIndex(a => a.id === editingItem.value.id)
    anggotaList.value[index] = { ...anggotaForm.value }
  } else {
    anggotaList.value.push({
      id: Date.now(),
      ...anggotaForm.value
    })
  }
  localStorage.setItem('bp_gugus_tugas_anggota', JSON.stringify(anggotaList.value))
  showModal.value = false
}

const deleteAnggota = (anggota) => {
  if (confirm('Apakah Anda yakin ingin menghapus anggota ini?')) {
    anggotaList.value = anggotaList.value.filter(a => a.id !== anggota.id)
    localStorage.setItem('bp_gugus_tugas_anggota', JSON.stringify(anggotaList.value))
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
      ...programForm.value
    })
  }
  localStorage.setItem('bp_gugus_tugas_program', JSON.stringify(programKerjaList.value))
  showModal.value = false
}

const deleteProgram = (program) => {
  if (confirm('Apakah Anda yakin ingin menghapus program kerja ini?')) {
    programKerjaList.value = programKerjaList.value.filter(p => p.id !== program.id)
    localStorage.setItem('bp_gugus_tugas_program', JSON.stringify(programKerjaList.value))
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
      programId: Number(laporanForm.value.programId)
    })
  }
  localStorage.setItem('bp_gugus_tugas_laporan', JSON.stringify(laporanList.value))
  showModal.value = false
}

const deleteLaporan = (laporan) => {
  if (confirm('Apakah Anda yakin ingin menghapus laporan ini?')) {
    laporanList.value = laporanList.value.filter(l => l.id !== laporan.id)
    localStorage.setItem('bp_gugus_tugas_laporan', JSON.stringify(laporanList.value))
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

const getKategoriColor = (kategori) => {
  const colors = {
    'Pengarah': 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
    'Ketua': 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
    'Sekretariat': 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
    'Anggota': 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400'
  }
  return colors[kategori] || 'bg-gray-100 text-gray-700'
}

onMounted(() => {
  loadData()
})
</script>

<template>
  <AdminLayout title="Kelola Gugus Tugas PAUD HI">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ anggotaList.length }}</p>
            <p class="text-sm text-gray-500">Total Anggota</p>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
          </div>
          <div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ anggotaList.filter(a => a.kategori === 'Anggota').length }}</p>
            <p class="text-sm text-gray-500">OPD Terlibat</p>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
          <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            @click="activeTab = 'anggota'"
            class="px-4 py-2.5 text-sm font-medium rounded-lg transition-colors"
            :class="activeTab === 'anggota' 
              ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400' 
              : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'"
          >
            👥 List Anggota
          </button>
          <button
            @click="activeTab = 'program'"
            class="px-4 py-2.5 text-sm font-medium rounded-lg transition-colors"
            :class="activeTab === 'program' 
              ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400' 
              : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'"
          >
            📁 Program Kerja
          </button>
          <button
            @click="activeTab = 'laporan'"
            class="px-4 py-2.5 text-sm font-medium rounded-lg transition-colors"
            :class="activeTab === 'laporan' 
              ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400' 
              : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'"
          >
            📊 Laporan Kegiatan
          </button>
        </nav>
      </div>

      <div class="p-6">
        <!-- Tab: List Anggota -->
        <div v-if="activeTab === 'anggota'">
          <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div class="flex flex-col md:flex-row gap-4 flex-1">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Cari nama atau instansi..."
                class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500"
              />
              <select
                v-model="filterKategori"
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500"
              >
                <option value="">Semua Kategori</option>
                <option v-for="kat in kategoriOptions" :key="kat" :value="kat">{{ kat }}</option>
              </select>
            </div>
            <button @click="openAnggotaModal()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm font-medium whitespace-nowrap">
              + Tambah Anggota
            </button>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Jabatan</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Instansi</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Kategori</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="anggota in filteredAnggota" :key="anggota.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                  <td class="px-4 py-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded-lg flex items-center justify-center" :class="getKategoriColor(anggota.kategori).split(' ').slice(0, 1).join(' ')">
                        <span class="font-bold" :class="getKategoriColor(anggota.kategori).split(' ').slice(1, 2).join(' ')">{{ anggota.nama.charAt(0) }}</span>
                      </div>
                      <span class="font-medium text-gray-900 dark:text-white">{{ anggota.nama }}</span>
                    </div>
                  </td>
                  <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">{{ anggota.jabatan }}</td>
                  <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">{{ anggota.instansi }}</td>
                  <td class="px-4 py-4">
                    <span :class="['px-2 py-1 text-xs rounded-full', getKategoriColor(anggota.kategori)]">
                      {{ anggota.kategori }}
                    </span>
                  </td>
                  <td class="px-4 py-4">
                    <div class="flex gap-2">
                      <button @click="openAnggotaModal(anggota)" class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                      <button @click="deleteAnggota(anggota)" class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                    </div>
                  </td>
                </tr>
                <tr v-if="filteredAnggota.length === 0">
                  <td colspan="5" class="px-4 py-12 text-center text-gray-500">
                    Tidak ada anggota yang ditemukan
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab: Program Kerja -->
        <div v-else-if="activeTab === 'program'">
          <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-gray-900 dark:text-white">Daftar Program Kerja Gugus Tugas</h3>
            <button @click="openProgramModal()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm font-medium">
              + Tambah Program
            </button>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama Program</th>
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
                  <td colspan="4" class="px-4 py-12 text-center text-gray-500">
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
            <button @click="openLaporanModal()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm font-medium">
              + Tambah Laporan
            </button>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Judul Laporan</th>
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
                  <td colspan="4" class="px-4 py-12 text-center text-gray-500">
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
            {{ editingItem ? 'Edit' : 'Tambah' }} 
            {{ modalType === 'anggota' ? 'Anggota' : modalType === 'program' ? 'Program Kerja' : 'Laporan Kegiatan' }}
          </h3>

          <!-- Anggota Form -->
          <form v-if="modalType === 'anggota'" @submit.prevent="saveAnggota" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama *</label>
              <input v-model="anggotaForm.nama" type="text" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jabatan *</label>
                <input v-model="anggotaForm.jabatan" type="text" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori *</label>
                <select v-model="anggotaForm.kategori" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                  <option v-for="kat in kategoriOptions" :key="kat" :value="kat">{{ kat }}</option>
                </select>
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Instansi *</label>
              <input v-model="anggotaForm.instansi" type="text" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No. HP</label>
                <input v-model="anggotaForm.nomorHp" type="tel" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                <input v-model="anggotaForm.email" type="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
              </div>
            </div>
            <div class="flex justify-end gap-3 pt-4">
              <button type="button" @click="showModal = false" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                Batal
              </button>
              <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                Simpan
              </button>
            </div>
          </form>

          <!-- Program Form -->
          <form v-else-if="modalType === 'program'" @submit.prevent="saveProgram" class="space-y-4">
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
              <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                Simpan
              </button>
            </div>
          </form>

          <!-- Laporan Form -->
          <form v-else-if="modalType === 'laporan'" @submit.prevent="saveLaporan" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Program Kerja</label>
              <select v-model="laporanForm.programId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="">Pilih Program (Opsional)</option>
                <option v-for="program in programKerjaList" :key="program.id" :value="program.id">
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
              <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                Simpan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
