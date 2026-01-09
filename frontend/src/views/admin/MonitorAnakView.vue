<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const loading = ref(true)
const searchQuery = ref('')
const filterKecamatan = ref('')

// Mock data
const items = ref([
  { id: 1, nama_anak: 'Aldi Prasetyo', usia: '5 tahun', jenis_kelamin: 'L', alamat: 'Jl. Gayungsari No. 15', kecamatan: 'Gayungan', kelurahan: 'Gayungan', nama_ortu: 'Bapak Prasetyo', no_hp: '081234567890', alasan: 'Belum terdaftar di PAUD/TK', status: 'follow_up' },
  { id: 2, nama_anak: 'Putri Ayu', usia: '4 tahun', jenis_kelamin: 'P', alamat: 'Jl. Ketintang Baru No. 8', kecamatan: 'Gayungan', kelurahan: 'Ketintang', nama_ortu: 'Ibu Ayu', no_hp: '082345678901', alasan: 'Kendala biaya', status: 'pending' },
  { id: 3, nama_anak: 'Bagus Firmansyah', usia: '5 tahun', jenis_kelamin: 'L', alamat: 'Jl. Wonocolo No. 20', kecamatan: 'Wonocolo', kelurahan: 'Wonocolo', nama_ortu: 'Bapak Firman', no_hp: '083456789012', alasan: 'Tidak ada PAUD dekat rumah', status: 'selesai' },
  { id: 4, nama_anak: 'Dewi Anggraini', usia: '6 tahun', jenis_kelamin: 'P', alamat: 'Jl. Bendul Merisi No. 5', kecamatan: 'Wonocolo', kelurahan: 'Bendul Merisi', nama_ortu: 'Ibu Dewi', no_hp: '084567890123', alasan: 'Orang tua bekerja', status: 'follow_up' }
])

const kecamatanList = ['Gayungan', 'Wonocolo', 'Rungkut', 'Sukolilo', 'Tenggilis Mejoyo']
const statusOptions = [
  { value: 'pending', label: 'Belum Ditindak', class: 'bg-gray-100 text-gray-700' },
  { value: 'follow_up', label: 'Dalam Follow Up', class: 'bg-yellow-100 text-yellow-700' },
  { value: 'selesai', label: 'Sudah Sekolah', class: 'bg-green-100 text-green-700' }
]

const filteredItems = computed(() => {
  return items.value.filter(i => {
    const matchSearch = i.nama_anak.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchKec = !filterKecamatan.value || i.kecamatan === filterKecamatan.value
    return matchSearch && matchKec
  })
})

const stats = computed(() => ({
  total: items.value.length,
  pending: items.value.filter(i => i.status === 'pending').length,
  follow_up: items.value.filter(i => i.status === 'follow_up').length,
  selesai: items.value.filter(i => i.status === 'selesai').length
}))

const getStatusClass = (status) => statusOptions.find(s => s.value === status)?.class || ''
const getStatusLabel = (status) => statusOptions.find(s => s.value === status)?.label || status

const updateStatus = (item, newStatus) => {
  item.status = newStatus
}

onMounted(() => {
  setTimeout(() => { loading.value = false }, 500)
})
</script>

<template>
  <AdminLayout title="Monitoring Anak Belum Sekolah">
    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Total Anak</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Belum Ditindak</p>
        <p class="text-2xl font-bold text-gray-600">{{ stats.pending }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Dalam Follow Up</p>
        <p class="text-2xl font-bold text-yellow-600">{{ stats.follow_up }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Sudah Sekolah</p>
        <p class="text-2xl font-bold text-green-600">{{ stats.selesai }}</p>
      </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-3 mb-6">
      <div class="relative flex-1 min-w-[200px]">
        <input v-model="searchQuery" type="text" placeholder="Cari nama anak..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
        <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </div>
      <select v-model="filterKecamatan" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        <option value="">Semua Kecamatan</option>
        <option v-for="k in kecamatanList" :key="k" :value="k">{{ k }}</option>
      </select>
    </div>

    <!-- Cards -->
    <div v-if="loading" class="text-center py-12">
      <div class="w-10 h-10 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin mx-auto"></div>
    </div>

    <div v-else class="grid md:grid-cols-2 gap-4">
      <div v-for="item in filteredItems" :key="item.id" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-4">
          <div class="flex items-start justify-between">
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl" :class="item.jenis_kelamin === 'L' ? 'bg-blue-100' : 'bg-pink-100'">
                {{ item.jenis_kelamin === 'L' ? '👦' : '👧' }}
              </div>
              <div>
                <h3 class="font-semibold text-gray-900 dark:text-white">{{ item.nama_anak }}</h3>
                <p class="text-sm text-gray-500">{{ item.usia }} • {{ item.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
              </div>
            </div>
            <span class="px-2 py-1 rounded-full text-xs font-medium" :class="getStatusClass(item.status)">
              {{ getStatusLabel(item.status) }}
            </span>
          </div>

          <div class="mt-4 space-y-2 text-sm">
            <div class="flex">
              <span class="text-gray-500 w-24">Alamat:</span>
              <span class="text-gray-700 dark:text-gray-300">{{ item.alamat }}</span>
            </div>
            <div class="flex">
              <span class="text-gray-500 w-24">Kecamatan:</span>
              <span class="text-gray-700 dark:text-gray-300">{{ item.kecamatan }} / {{ item.kelurahan }}</span>
            </div>
            <div class="flex">
              <span class="text-gray-500 w-24">Orang Tua:</span>
              <span class="text-gray-700 dark:text-gray-300">{{ item.nama_ortu }}</span>
            </div>
            <div class="flex">
              <span class="text-gray-500 w-24">No. HP:</span>
              <a :href="'tel:' + item.no_hp" class="text-primary-600 hover:underline">{{ item.no_hp }}</a>
            </div>
            <div class="flex">
              <span class="text-gray-500 w-24">Alasan:</span>
              <span class="text-gray-700 dark:text-gray-300">{{ item.alasan }}</span>
            </div>
          </div>
        </div>

        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 flex justify-end gap-2">
          <button v-if="item.status !== 'follow_up'" @click="updateStatus(item, 'follow_up')" class="px-3 py-1.5 text-sm text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg">
            Follow Up
          </button>
          <button v-if="item.status !== 'selesai'" @click="updateStatus(item, 'selesai')" class="px-3 py-1.5 text-sm text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg">
            ✓ Sudah Sekolah
          </button>
          <a :href="'https://wa.me/62' + item.no_hp.replace(/^0/, '')" target="_blank" class="px-3 py-1.5 text-sm text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg flex items-center gap-1">
            💬 WhatsApp
          </a>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
