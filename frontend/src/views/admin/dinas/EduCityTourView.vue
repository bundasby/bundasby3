<script setup>
import { ref, computed } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

// State
const activeTab = ref('jadwal')
const searchQuery = ref('')
const showModal = ref(false)
const editingItem = ref(null)
const saving = ref(false)

// Mock data Edu City Tour
const tourList = ref([
  { id: 1, nama: 'Kunjungan Museum Surabaya', tanggal: '2026-02-20', waktuBerangkat: '07:00', waktuPulang: '15:00', sekolah: ['TK Aisyiyah 1', 'KB Tunas Harapan', 'PAUD Ceria'], totalAnak: 75, bus: 2, status: 'upcoming', lokasi: 'Museum Surabaya' },
  { id: 2, nama: 'Wisata Edukasi Kebun Binatang', tanggal: '2026-01-25', waktuBerangkat: '06:30', waktuPulang: '14:00', sekolah: ['TK Pembina', 'KB Melati'], totalAnak: 50, bus: 1, status: 'ongoing', lokasi: 'KBS (Kebun Binatang Surabaya)' },
  { id: 3, nama: 'Kunjungan Perpustakaan Kota', tanggal: '2026-01-15', waktuBerangkat: '08:00', waktuPulang: '12:00', sekolah: ['TK Dharma Wanita', 'PAUD Ananda', 'KB Bintang Kecil'], totalAnak: 60, bus: 2, status: 'completed', lokasi: 'Perpustakaan Kota Surabaya' },
  { id: 4, nama: 'Field Trip PDAM', tanggal: '2026-03-10', waktuBerangkat: '07:30', waktuPulang: '13:00', sekolah: ['TK Aisyiyah 2', 'KB Pelangi'], totalAnak: 40, bus: 1, status: 'upcoming', lokasi: 'PDAM Surya Sembada' }
])

// Form
const form = ref({
  nama: '',
  tanggal: '',
  waktuBerangkat: '',
  waktuPulang: '',
  lokasi: '',
  bus: 1
})

const tabs = [
  { id: 'jadwal', label: 'Jadwal Tour', icon: '🚌' },
  { id: 'pendaftaran', label: 'Pendaftaran', icon: '📝' },
  { id: 'dokumentasi', label: 'Dokumentasi', icon: '📸' },
  { id: 'laporan', label: 'Laporan', icon: '📊' }
]

const stats = computed(() => ({
  total: tourList.value.length,
  upcoming: tourList.value.filter(t => t.status === 'upcoming').length,
  totalAnak: tourList.value.reduce((sum, t) => sum + t.totalAnak, 0),
  totalSekolah: [...new Set(tourList.value.flatMap(t => t.sekolah))].length
}))

const filteredTours = computed(() => {
  if (!searchQuery.value) return tourList.value
  const query = searchQuery.value.toLowerCase()
  return tourList.value.filter(t => 
    t.nama.toLowerCase().includes(query) ||
    t.lokasi.toLowerCase().includes(query)
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
    form.value = { nama: '', tanggal: '', waktuBerangkat: '', waktuPulang: '', lokasi: '', bus: 1 }
  }
  showModal.value = true
}

const saveItem = async () => {
  saving.value = true
  await new Promise(r => setTimeout(r, 800))
  
  if (editingItem.value) {
    const idx = tourList.value.findIndex(t => t.id === editingItem.value.id)
    if (idx !== -1) tourList.value[idx] = { ...tourList.value[idx], ...form.value }
  } else {
    tourList.value.push({
      id: Date.now(),
      ...form.value,
      sekolah: [],
      totalAnak: 0,
      status: 'upcoming'
    })
  }
  
  saving.value = false
  showModal.value = false
}

const deleteItem = (id) => {
  if (confirm('Hapus jadwal tour ini?')) {
    tourList.value = tourList.value.filter(t => t.id !== id)
  }
}
</script>

<template>
  <AdminLayout title="Edu City Tour">
    <div class="space-y-6">
      <!-- Banner -->
      <div class="bg-gradient-to-r from-green-500 to-teal-500 rounded-2xl p-6 text-white">
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center text-3xl">🚌</div>
          <div>
            <h2 class="text-2xl font-bold">Edu City Tour</h2>
            <p class="text-green-100">Program kunjungan wisata edukasi untuk anak PAUD</p>
          </div>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
          <p class="text-sm text-gray-500 dark:text-gray-400">Total Tour</p>
          <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
          <p class="text-sm text-gray-500 dark:text-gray-400">Akan Datang</p>
          <p class="text-2xl font-bold text-blue-600">{{ stats.upcoming }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
          <p class="text-sm text-gray-500 dark:text-gray-400">Total Anak</p>
          <p class="text-2xl font-bold text-green-600">{{ stats.totalAnak }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
          <p class="text-sm text-gray-500 dark:text-gray-400">Sekolah Terlibat</p>
          <p class="text-2xl font-bold text-purple-600">{{ stats.totalSekolah }}</p>
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
        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
          <div class="relative flex-1 max-w-md">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input v-model="searchQuery" type="text" placeholder="Cari tour..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
          </div>
          <button @click="openModal()" class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Jadwal
          </button>
        </div>

        <!-- Tour Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
          <div v-for="tour in filteredTours" :key="tour.id" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
              <div class="flex items-start justify-between mb-4">
                <div>
                  <h3 class="font-semibold text-gray-900 dark:text-white">{{ tour.nama }}</h3>
                  <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                    {{ tour.lokasi }}
                  </p>
                </div>
                <span class="px-2 py-1 text-xs font-medium rounded-full" :class="[getStatusBadge(tour.status).bg, getStatusBadge(tour.status).text]">
                  {{ getStatusBadge(tour.status).label }}
                </span>
              </div>
              
              <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                  <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal</p>
                  <p class="font-medium text-gray-900 dark:text-white">{{ tour.tanggal }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                  <p class="text-xs text-gray-500 dark:text-gray-400">Waktu</p>
                  <p class="font-medium text-gray-900 dark:text-white">{{ tour.waktuBerangkat }} - {{ tour.waktuPulang }}</p>
                </div>
              </div>

              <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-4">
                <span class="flex items-center gap-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                  {{ tour.totalAnak }} anak
                </span>
                <span class="flex items-center gap-1">
                  🏫 {{ tour.sekolah.length }} sekolah
                </span>
                <span class="flex items-center gap-1">
                  🚌 {{ tour.bus }} bus
                </span>
              </div>

              <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center gap-2">
                <button @click="openModal(tour)" class="flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600">Edit</button>
                <button class="px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg text-sm font-medium hover:bg-blue-100 dark:hover:bg-blue-900/30">Detail</button>
                <button @click="deleteItem(tour.id)" class="px-3 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg text-sm font-medium">Hapus</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Other Tabs Placeholder -->
      <div v-if="activeTab !== 'jadwal'" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
        <div class="text-gray-400 dark:text-gray-500">
          <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
          </svg>
          <p class="text-lg font-medium">Fitur {{ tabs.find(t => t.id === activeTab)?.label }}</p>
          <p class="text-sm mt-2">Akan segera tersedia</p>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black/50" @click="showModal = false"></div>
      <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-lg">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ editingItem ? 'Edit' : 'Tambah' }} Jadwal Tour</h2>
        </div>
        <div class="p-6 space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Tour</label>
            <input v-model="form.nama" type="text" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lokasi Tujuan</label>
            <input v-model="form.lokasi" type="text" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
          </div>
          <div class="grid grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
              <input v-model="form.tanggal" type="date" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Berangkat</label>
              <input v-model="form.waktuBerangkat" type="time" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pulang</label>
              <input v-model="form.waktuPulang" type="time" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah Bus</label>
            <input v-model="form.bus" type="number" min="1" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
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
