<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import { konsultasiStorage } from '@/services/localStorage'

const konsultasi = ref([])
const loading = ref(true)
const selectedItem = ref(null)
const showModal = ref(false)
const catatan = ref('')
const filterStatus = ref('')
const searchQuery = ref('')

const narasumberLabels = {
  'ns1': 'Tim Ahli Kurikulum',
  'ns2': 'Tim Akreditasi',
  'ns3': 'Tim PAUD HI',
  'ns4': 'Tim Pendampingan'
}

onMounted(() => {
  loadData()
})

const loadData = () => {
  loading.value = true
  konsultasi.value = konsultasiStorage.getAll()
  loading.value = false
}

const filteredKonsultasi = computed(() => {
  return konsultasi.value.filter(k => {
    const matchStatus = !filterStatus.value || k.status === filterStatus.value
    const matchSearch = !searchQuery.value || 
      k.nama.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      k.topik.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      k.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    return matchStatus && matchSearch
  })
})

const stats = computed(() => ({
  total: konsultasi.value.length,
  pending: konsultasi.value.filter(k => k.status === 'pending').length,
  approved: konsultasi.value.filter(k => k.status === 'approved').length,
  rejected: konsultasi.value.filter(k => k.status === 'rejected').length
}))

const openModal = (item) => {
  selectedItem.value = item
  catatan.value = item.catatan || ''
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  selectedItem.value = null
  catatan.value = ''
}

const updateStatus = (status) => {
  if (selectedItem.value) {
    konsultasiStorage.updateStatus(selectedItem.value.id, status, catatan.value)
    loadData()
    closeModal()
  }
}

const deleteItem = (id) => {
  if (confirm('Hapus booking konsultasi ini?')) {
    konsultasiStorage.delete(id)
    loadData()
  }
}

const getNarasumberLabel = (id) => narasumberLabels[id] || id

const getStatusBadge = (status) => {
  const badges = {
    pending: { class: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400', label: 'Menunggu' },
    approved: { class: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400', label: 'Disetujui' },
    rejected: { class: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400', label: 'Ditolak' }
  }
  return badges[status] || badges.pending
}

const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
}

const formatDateTime = (dateStr) => {
  return new Date(dateStr).toLocaleString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })
}
</script>

<template>
  <AdminLayout title="Kelola Konsultasi PAUD HI">
    <p class="text-gray-600 dark:text-gray-400 -mt-6 mb-6">Kelola booking konsultasi PAUD HI</p>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <div class="card p-4 text-center cursor-pointer hover:shadow-md transition-shadow" @click="filterStatus = ''">
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</div>
        <div class="text-xs text-gray-500">Total</div>
      </div>
      <div class="card p-4 text-center cursor-pointer hover:shadow-md transition-shadow" @click="filterStatus = 'pending'">
        <div class="text-2xl font-bold text-yellow-600">{{ stats.pending }}</div>
        <div class="text-xs text-gray-500">Menunggu</div>
      </div>
      <div class="card p-4 text-center cursor-pointer hover:shadow-md transition-shadow" @click="filterStatus = 'approved'">
        <div class="text-2xl font-bold text-green-600">{{ stats.approved }}</div>
        <div class="text-xs text-gray-500">Disetujui</div>
      </div>
      <div class="card p-4 text-center cursor-pointer hover:shadow-md transition-shadow" @click="filterStatus = 'rejected'">
        <div class="text-2xl font-bold text-red-600">{{ stats.rejected }}</div>
        <div class="text-xs text-gray-500">Ditolak</div>
      </div>
    </div>

    <!-- Filters -->
    <div class="card p-4 mb-6">
      <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
          <input v-model="searchQuery" type="text" placeholder="Cari nama, topik, email..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
        </div>
        <select v-model="filterStatus" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
          <option value="">Semua Status</option>
          <option value="pending">Menunggu</option>
          <option value="approved">Disetujui</option>
          <option value="rejected">Ditolak</option>
        </select>
      </div>
    </div>

    <!-- Table -->
    <div class="card overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 dark:bg-gray-700/50">
            <tr>
              <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase">Pemohon</th>
              <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase">Topik</th>
              <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase">Jadwal</th>
              <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase">Status</th>
              <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <tr v-if="loading">
              <td colspan="5" class="py-8 text-center text-gray-500">Memuat...</td>
            </tr>
            <tr v-else-if="filteredKonsultasi.length === 0">
              <td colspan="5" class="py-8 text-center text-gray-500">Tidak ada data konsultasi</td>
            </tr>
            <tr v-for="item in filteredKonsultasi" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
              <td class="py-3 px-4">
                <div>
                  <p class="font-medium text-gray-900 dark:text-white text-sm">{{ item.nama }}</p>
                  <p class="text-xs text-gray-500">{{ item.instansi || '-' }}</p>
                  <p class="text-xs text-gray-400">{{ item.email }}</p>
                </div>
              </td>
              <td class="py-3 px-4">
                <p class="text-sm text-gray-900 dark:text-white">{{ item.topik }}</p>
                <p class="text-xs text-gray-500">{{ getNarasumberLabel(item.narasumber) }}</p>
              </td>
              <td class="py-3 px-4">
                <p class="text-sm text-gray-900 dark:text-white">{{ formatDate(item.tanggal) }}</p>
                <p class="text-xs text-gray-500">{{ item.jam }} WIB • {{ item.metode === 'online' ? '🌐 Online' : '🏢 Offline' }}</p>
              </td>
              <td class="py-3 px-4">
                <span :class="['px-2 py-1 rounded-full text-xs font-medium', getStatusBadge(item.status).class]">
                  {{ getStatusBadge(item.status).label }}
                </span>
              </td>
              <td class="py-3 px-4">
                <div class="flex items-center gap-2">
                  <button @click="openModal(item)" class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded" title="Detail">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  </button>
                  <button @click="deleteItem(item.id)" class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded" title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
      <div class="bg-white dark:bg-gray-800 rounded-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b dark:border-gray-700">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Detail Konsultasi</h3>
            <button @click="closeModal" class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>
        </div>
        
        <div v-if="selectedItem" class="p-6 space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <p class="text-xs text-gray-500">Nama</p>
              <p class="font-medium text-gray-900 dark:text-white">{{ selectedItem.nama }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-500">Instansi</p>
              <p class="text-gray-900 dark:text-white">{{ selectedItem.instansi || '-' }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-500">Email</p>
              <p class="text-gray-900 dark:text-white">{{ selectedItem.email }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-500">Telepon</p>
              <p class="text-gray-900 dark:text-white">{{ selectedItem.telepon }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-500">Topik</p>
              <p class="font-medium text-gray-900 dark:text-white">{{ selectedItem.topik }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-500">Narasumber</p>
              <p class="text-gray-900 dark:text-white">{{ getNarasumberLabel(selectedItem.narasumber) }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-500">Jadwal</p>
              <p class="text-gray-900 dark:text-white">{{ formatDate(selectedItem.tanggal) }} • {{ selectedItem.jam }} WIB</p>
            </div>
            <div>
              <p class="text-xs text-gray-500">Metode</p>
              <p class="text-gray-900 dark:text-white">{{ selectedItem.metode === 'online' ? '🌐 Online (Video Call)' : '🏢 Offline (Tatap Muka)' }}</p>
            </div>
          </div>
          
          <div>
            <p class="text-xs text-gray-500">Pesan</p>
            <p class="text-gray-900 dark:text-white text-sm">{{ selectedItem.pesan }}</p>
          </div>
          
          <div>
            <p class="text-xs text-gray-500 mb-1">Status Saat Ini</p>
            <span :class="['px-3 py-1 rounded-full text-xs font-medium', getStatusBadge(selectedItem.status).class]">
              {{ getStatusBadge(selectedItem.status).label }}
            </span>
          </div>
          
          <div>
            <label class="block text-xs text-gray-500 mb-1">Catatan Admin</label>
            <textarea v-model="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm" placeholder="Catatan untuk pemohon..."></textarea>
          </div>
          
          <div class="flex gap-3">
            <button @click="updateStatus('approved')" class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg text-sm font-medium">
              ✓ Setujui
            </button>
            <button @click="updateStatus('rejected')" class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg text-sm font-medium">
              ✗ Tolak
            </button>
          </div>
          
          <p class="text-xs text-gray-400 text-center">
            Diajukan: {{ formatDateTime(selectedItem.createdAt) }}
          </p>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
