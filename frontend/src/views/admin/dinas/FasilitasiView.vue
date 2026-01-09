<script setup>
import { ref, computed } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

// State
const activeTab = ref('semua')
const searchQuery = ref('')
const showModal = ref(false)
const editingItem = ref(null)
const saving = ref(false)

// Mock data fasilitasi
const fasilitasiList = ref([
  { id: 1, nama: 'Bantuan APE (Alat Permainan Edukatif)', lembaga: 'TK Aisyiyah 1', kecamatan: 'Genteng', jenis: 'Sarana Prasarana', jumlahBantuan: 'Rp 5.000.000', status: 'completed', tanggalPengajuan: '2026-01-05', tanggalSelesai: '2026-01-15' },
  { id: 2, nama: 'Pelatihan Guru PAUD', lembaga: 'KB Tunas Harapan', kecamatan: 'Tegalsari', jenis: 'Pengembangan SDM', jumlahBantuan: '2 orang', status: 'processing', tanggalPengajuan: '2026-01-10', tanggalSelesai: null },
  { id: 3, nama: 'Renovasi Ruang Kelas', lembaga: 'PAUD Ceria', kecamatan: 'Sawahan', jenis: 'Infrastruktur', jumlahBantuan: 'Rp 15.000.000', status: 'pending', tanggalPengajuan: '2026-01-12', tanggalSelesai: null },
  { id: 4, nama: 'Bantuan Buku Pembelajaran', lembaga: 'TK Pembina', kecamatan: 'Wonokromo', jenis: 'Media Pembelajaran', jumlahBantuan: '100 buku', status: 'completed', tanggalPengajuan: '2025-12-20', tanggalSelesai: '2026-01-08' },
  { id: 5, nama: 'Pengadaan Komputer', lembaga: 'KB Melati', kecamatan: 'Rungkut', jenis: 'Sarana Prasarana', jumlahBantuan: '3 unit', status: 'rejected', tanggalPengajuan: '2026-01-02', tanggalSelesai: null, alasanTolak: 'Kuota habis' }
])

// Form
const form = ref({
  nama: '',
  lembaga: '',
  kecamatan: '',
  jenis: '',
  jumlahBantuan: '',
  catatan: ''
})

const tabs = [
  { id: 'semua', label: 'Semua', count: fasilitasiList.value.length },
  { id: 'pending', label: 'Menunggu', count: fasilitasiList.value.filter(f => f.status === 'pending').length },
  { id: 'processing', label: 'Diproses', count: fasilitasiList.value.filter(f => f.status === 'processing').length },
  { id: 'completed', label: 'Selesai', count: fasilitasiList.value.filter(f => f.status === 'completed').length },
  { id: 'rejected', label: 'Ditolak', count: fasilitasiList.value.filter(f => f.status === 'rejected').length }
]

const jenisOptions = ['Sarana Prasarana', 'Pengembangan SDM', 'Media Pembelajaran', 'Infrastruktur', 'Lainnya']

const stats = computed(() => ({
  total: fasilitasiList.value.length,
  pending: fasilitasiList.value.filter(f => f.status === 'pending').length,
  processing: fasilitasiList.value.filter(f => f.status === 'processing').length,
  completed: fasilitasiList.value.filter(f => f.status === 'completed').length
}))

const filteredFasilitasi = computed(() => {
  let result = fasilitasiList.value
  
  if (activeTab.value !== 'semua') {
    result = result.filter(f => f.status === activeTab.value)
  }
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter(f => 
      f.nama.toLowerCase().includes(query) ||
      f.lembaga.toLowerCase().includes(query) ||
      f.kecamatan.toLowerCase().includes(query)
    )
  }
  
  return result
})

const getStatusBadge = (status) => {
  const badges = {
    pending: { bg: 'bg-gray-100 dark:bg-gray-700', text: 'text-gray-600 dark:text-gray-400', label: 'Menunggu' },
    processing: { bg: 'bg-blue-100 dark:bg-blue-900/30', text: 'text-blue-600 dark:text-blue-400', label: 'Diproses' },
    completed: { bg: 'bg-green-100 dark:bg-green-900/30', text: 'text-green-600 dark:text-green-400', label: 'Selesai' },
    rejected: { bg: 'bg-red-100 dark:bg-red-900/30', text: 'text-red-600 dark:text-red-400', label: 'Ditolak' }
  }
  return badges[status] || badges.pending
}

const updateStatus = (id, newStatus) => {
  const idx = fasilitasiList.value.findIndex(f => f.id === id)
  if (idx !== -1) {
    fasilitasiList.value[idx].status = newStatus
    if (newStatus === 'completed') {
      fasilitasiList.value[idx].tanggalSelesai = new Date().toISOString().split('T')[0]
    }
  }
}

const openModal = (item = null) => {
  if (item) {
    editingItem.value = item
    form.value = { ...item }
  } else {
    editingItem.value = null
    form.value = { nama: '', lembaga: '', kecamatan: '', jenis: '', jumlahBantuan: '', catatan: '' }
  }
  showModal.value = true
}

const saveItem = async () => {
  saving.value = true
  await new Promise(r => setTimeout(r, 800))
  
  if (editingItem.value) {
    const idx = fasilitasiList.value.findIndex(f => f.id === editingItem.value.id)
    if (idx !== -1) fasilitasiList.value[idx] = { ...fasilitasiList.value[idx], ...form.value }
  } else {
    fasilitasiList.value.push({
      id: Date.now(),
      ...form.value,
      status: 'pending',
      tanggalPengajuan: new Date().toISOString().split('T')[0],
      tanggalSelesai: null
    })
  }
  
  saving.value = false
  showModal.value = false
}
</script>

<template>
  <AdminLayout title="Fasilitasi Program">
    <div class="space-y-6">
      <!-- Stats Cards -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
              <span class="text-xl">📋</span>
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">Total</p>
              <p class="text-xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
            </div>
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
              <span class="text-xl">⏳</span>
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">Menunggu</p>
              <p class="text-xl font-bold text-amber-600">{{ stats.pending }}</p>
            </div>
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
              <span class="text-xl">🔄</span>
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">Diproses</p>
              <p class="text-xl font-bold text-blue-600">{{ stats.processing }}</p>
            </div>
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
              <span class="text-xl">✅</span>
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">Selesai</p>
              <p class="text-xl font-bold text-green-600">{{ stats.completed }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabs & Actions -->
      <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-1.5">
          <div class="flex flex-wrap gap-1">
            <button
              v-for="tab in tabs"
              :key="tab.id"
              @click="activeTab = tab.id"
              class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors flex items-center gap-1"
              :class="activeTab === tab.id ? 'bg-primary-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'"
            >
              {{ tab.label }}
              <span class="px-1.5 py-0.5 rounded-full text-xs" :class="activeTab === tab.id ? 'bg-white/20' : 'bg-gray-200 dark:bg-gray-600'">{{ tab.count }}</span>
            </button>
          </div>
        </div>
        
        <div class="flex gap-3">
          <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input v-model="searchQuery" type="text" placeholder="Cari fasilitasi..." class="w-64 pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
          </div>
          <button @click="openModal()" class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah
          </button>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Jenis Bantuan</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Lembaga</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="(item, index) in filteredFasilitasi" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-6 py-4 text-sm text-gray-500">{{ index + 1 }}</td>
                <td class="px-6 py-4">
                  <p class="font-medium text-gray-900 dark:text-white">{{ item.nama }}</p>
                </td>
                <td class="px-6 py-4">
                  <p class="text-gray-900 dark:text-white">{{ item.lembaga }}</p>
                  <p class="text-xs text-gray-500">{{ item.kecamatan }}</p>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ item.jenis }}</td>
                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ item.jumlahBantuan }}</td>
                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ item.tanggalPengajuan }}</td>
                <td class="px-6 py-4">
                  <span class="px-2 py-1 text-xs font-medium rounded-full" :class="[getStatusBadge(item.status).bg, getStatusBadge(item.status).text]">
                    {{ getStatusBadge(item.status).label }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-center gap-1">
                    <button v-if="item.status === 'pending'" @click="updateStatus(item.id, 'processing')" class="px-2 py-1 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded" title="Proses">Proses</button>
                    <button v-if="item.status === 'processing'" @click="updateStatus(item.id, 'completed')" class="px-2 py-1 text-xs bg-green-600 hover:bg-green-700 text-white rounded" title="Selesai">Selesai</button>
                    <button @click="openModal(item)" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded" title="Edit">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </button>
                  </div>
                </td>
              </tr>
              
              <tr v-if="filteredFasilitasi.length === 0">
                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                  <p class="font-medium">Tidak ada data</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black/50" @click="showModal = false"></div>
      <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-lg">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ editingItem ? 'Edit' : 'Tambah' }} Fasilitasi</h2>
        </div>
        <div class="p-6 space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Bantuan</label>
            <input v-model="form.nama" type="text" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lembaga</label>
              <input v-model="form.lembaga" type="text" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kecamatan</label>
              <input v-model="form.kecamatan" type="text" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis</label>
              <select v-model="form.jenis" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                <option v-for="jenis in jenisOptions" :key="jenis" :value="jenis">{{ jenis }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah Bantuan</label>
              <input v-model="form.jumlahBantuan" type="text" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
            <textarea v-model="form.catatan" rows="2" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white"></textarea>
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
