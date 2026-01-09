<script setup>
import { ref, computed } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

// State
const activeTab = ref('jadwal')
const searchQuery = ref('')
const showModal = ref(false)
const editingItem = ref(null)
const saving = ref(false)

// Mock data pelatihan
const pelatihanList = ref([
  { id: 1, judul: 'Pelatihan Kurikulum Merdeka PAUD', tanggal: '2026-02-15', waktu: '08:00 - 16:00', lokasi: 'Grha Bunda PAUD', peserta: 50, kapasitas: 60, status: 'upcoming', narasumber: 'Dr. Siti Aminah, M.Pd' },
  { id: 2, judul: 'Workshop Pembelajaran Berbasis Proyek', tanggal: '2026-01-20', waktu: '09:00 - 15:00', lokasi: 'Aula Dispendik', peserta: 45, kapasitas: 50, status: 'ongoing', narasumber: 'Prof. Budi Santoso' },
  { id: 3, judul: 'Seminar PAUD Holistik Integratif', tanggal: '2026-01-10', waktu: '08:00 - 12:00', lokasi: 'Hotel Surabaya Plaza', peserta: 100, kapasitas: 100, status: 'completed', narasumber: 'Dr. Maya Indah, S.Psi' },
  { id: 4, judul: 'Pelatihan Asesmen Perkembangan Anak', tanggal: '2026-03-05', waktu: '08:00 - 16:00', lokasi: 'Grha Bunda PAUD', peserta: 30, kapasitas: 40, status: 'upcoming', narasumber: 'Dra. Endang Suryani' }
])

const pesertaList = ref([
  { id: 1, nama: 'Siti Nurhaliza', lembaga: 'TK Aisyiyah 1', email: 'siti@tk-aisyiyah.sch.id', telepon: '081234567890', pelatihanId: 1, status: 'confirmed' },
  { id: 2, nama: 'Dewi Sartika', lembaga: 'KB Tunas Harapan', email: 'dewi@tunas.sch.id', telepon: '081234567891', pelatihanId: 1, status: 'confirmed' },
  { id: 3, nama: 'Rina Marlina', lembaga: 'PAUD Ceria', email: 'rina@ceria.sch.id', telepon: '081234567892', pelatihanId: 2, status: 'pending' }
])

// Form
const form = ref({
  judul: '',
  tanggal: '',
  waktu: '',
  lokasi: '',
  kapasitas: 50,
  narasumber: '',
  deskripsi: ''
})

const tabs = [
  { id: 'jadwal', label: 'Jadwal Pelatihan', icon: '📅' },
  { id: 'peserta', label: 'Daftar Peserta', icon: '👥' },
  { id: 'sertifikat', label: 'Sertifikat', icon: '📜' },
  { id: 'laporan', label: 'Laporan', icon: '📊' }
]

const stats = computed(() => ({
  total: pelatihanList.value.length,
  upcoming: pelatihanList.value.filter(p => p.status === 'upcoming').length,
  ongoing: pelatihanList.value.filter(p => p.status === 'ongoing').length,
  completed: pelatihanList.value.filter(p => p.status === 'completed').length,
  totalPeserta: pelatihanList.value.reduce((sum, p) => sum + p.peserta, 0)
}))

const filteredPelatihan = computed(() => {
  if (!searchQuery.value) return pelatihanList.value
  const query = searchQuery.value.toLowerCase()
  return pelatihanList.value.filter(p => 
    p.judul.toLowerCase().includes(query) ||
    p.lokasi.toLowerCase().includes(query) ||
    p.narasumber.toLowerCase().includes(query)
  )
})

const getStatusBadge = (status) => {
  const badges = {
    upcoming: { bg: 'bg-blue-100 dark:bg-blue-900/30', text: 'text-blue-600 dark:text-blue-400', label: 'Akan Datang' },
    ongoing: { bg: 'bg-amber-100 dark:bg-amber-900/30', text: 'text-amber-600 dark:text-amber-400', label: 'Berlangsung' },
    completed: { bg: 'bg-green-100 dark:bg-green-900/30', text: 'text-green-600 dark:text-green-400', label: 'Selesai' }
  }
  return badges[status] || badges.upcoming
}

const openModal = (item = null) => {
  if (item) {
    editingItem.value = item
    form.value = { ...item }
  } else {
    editingItem.value = null
    form.value = { judul: '', tanggal: '', waktu: '', lokasi: '', kapasitas: 50, narasumber: '', deskripsi: '' }
  }
  showModal.value = true
}

const saveItem = async () => {
  saving.value = true
  await new Promise(r => setTimeout(r, 800))
  
  if (editingItem.value) {
    const idx = pelatihanList.value.findIndex(p => p.id === editingItem.value.id)
    if (idx !== -1) pelatihanList.value[idx] = { ...pelatihanList.value[idx], ...form.value }
  } else {
    pelatihanList.value.push({
      id: Date.now(),
      ...form.value,
      peserta: 0,
      status: 'upcoming'
    })
  }
  
  saving.value = false
  showModal.value = false
}

const deleteItem = (id) => {
  if (confirm('Hapus pelatihan ini?')) {
    pelatihanList.value = pelatihanList.value.filter(p => p.id !== id)
  }
}
</script>

<template>
  <AdminLayout title="Pelatihan PAUD">
    <div class="space-y-6">
      <!-- Stats Cards -->
      <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
          <p class="text-sm text-gray-500 dark:text-gray-400">Total Pelatihan</p>
          <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
          <p class="text-sm text-gray-500 dark:text-gray-400">Akan Datang</p>
          <p class="text-2xl font-bold text-blue-600">{{ stats.upcoming }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
          <p class="text-sm text-gray-500 dark:text-gray-400">Berlangsung</p>
          <p class="text-2xl font-bold text-amber-600">{{ stats.ongoing }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
          <p class="text-sm text-gray-500 dark:text-gray-400">Selesai</p>
          <p class="text-2xl font-bold text-green-600">{{ stats.completed }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
          <p class="text-sm text-gray-500 dark:text-gray-400">Total Peserta</p>
          <p class="text-2xl font-bold text-purple-600">{{ stats.totalPeserta }}</p>
        </div>
      </div>

      <!-- Tabs -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-1.5">
        <div class="flex flex-wrap gap-1">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            @click="activeTab = tab.id"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2"
            :class="activeTab === tab.id ? 'bg-primary-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'"
          >
            <span>{{ tab.icon }}</span>
            {{ tab.label }}
          </button>
        </div>
      </div>

      <!-- Jadwal Tab -->
      <div v-if="activeTab === 'jadwal'" class="space-y-4">
        <!-- Actions -->
        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
          <div class="relative flex-1 max-w-md">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input v-model="searchQuery" type="text" placeholder="Cari pelatihan..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
          </div>
          <button @click="openModal()" class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Pelatihan
          </button>
        </div>

        <!-- Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div v-for="item in filteredPelatihan" :key="item.id" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
              <div class="flex items-start justify-between mb-4">
                <div>
                  <h3 class="font-semibold text-gray-900 dark:text-white">{{ item.judul }}</h3>
                  <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ item.narasumber }}</p>
                </div>
                <span class="px-2 py-1 text-xs font-medium rounded-full" :class="[getStatusBadge(item.status).bg, getStatusBadge(item.status).text]">
                  {{ getStatusBadge(item.status).label }}
                </span>
              </div>
              
              <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                  {{ item.tanggal }} • {{ item.waktu }}
                </div>
                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                  {{ item.lokasi }}
                </div>
                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                  {{ item.peserta }} / {{ item.kapasitas }} peserta
                </div>
              </div>

              <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center gap-2">
                <button @click="openModal(item)" class="flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600">Edit</button>
                <button @click="deleteItem(item.id)" class="px-3 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg text-sm font-medium">Hapus</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Peserta Tab -->
      <div v-if="activeTab === 'peserta'" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Lembaga</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Telepon</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="peserta in pesertaList" :key="peserta.id">
                <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">{{ peserta.nama }}</td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ peserta.lembaga }}</td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ peserta.email }}</td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ peserta.telepon }}</td>
                <td class="px-6 py-4">
                  <span :class="peserta.status === 'confirmed' ? 'bg-green-100 text-green-600' : 'bg-amber-100 text-amber-600'" class="px-2 py-1 text-xs font-medium rounded-full">
                    {{ peserta.status === 'confirmed' ? 'Terdaftar' : 'Pending' }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Sertifikat Tab -->
      <div v-if="activeTab === 'sertifikat'" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
        <div class="text-gray-400 dark:text-gray-500">
          <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <p class="text-lg font-medium">Fitur Sertifikat</p>
          <p class="text-sm mt-2">Generate dan kelola sertifikat pelatihan</p>
        </div>
      </div>

      <!-- Laporan Tab -->
      <div v-if="activeTab === 'laporan'" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
        <div class="text-gray-400 dark:text-gray-500">
          <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <p class="text-lg font-medium">Fitur Laporan</p>
          <p class="text-sm mt-2">Lihat statistik dan laporan pelatihan</p>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black/50" @click="showModal = false"></div>
      <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-lg">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ editingItem ? 'Edit' : 'Tambah' }} Pelatihan</h2>
        </div>
        <div class="p-6 space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Pelatihan</label>
            <input v-model="form.judul" type="text" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
              <input v-model="form.tanggal" type="date" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Waktu</label>
              <input v-model="form.waktu" type="text" placeholder="08:00 - 16:00" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lokasi</label>
            <input v-model="form.lokasi" type="text" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Narasumber</label>
              <input v-model="form.narasumber" type="text" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kapasitas</label>
              <input v-model="form.kapasitas" type="number" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
          </div>
        </div>
        <div class="p-6 border-t border-gray-200 dark:border-gray-700 flex gap-3 justify-end">
          <button @click="showModal = false" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Batal</button>
          <button @click="saveItem" :disabled="saving" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium disabled:opacity-50">
            {{ saving ? 'Menyimpan...' : 'Simpan' }}
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
