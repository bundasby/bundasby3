<script setup>
import { ref, computed } from 'vue'
import DataLayout from '@/components/layout/DataLayout.vue'

const searchQuery = ref('')
const selectedStatus = ref('')

const permits = ref([
  { id: 1, name: 'TK Negeri Pembina', permitNumber: 'IO/TK/2023/001', issueDate: '15 Jan 2023', expiryDate: '15 Jan 2028', status: 'Aktif', kecamatan: 'Gubeng' },
  { id: 2, name: 'PAUD Ceria Mandiri', permitNumber: 'IO/PAUD/2022/045', issueDate: '10 Mar 2022', expiryDate: '10 Mar 2027', status: 'Aktif', kecamatan: 'Rungkut' },
  { id: 3, name: 'KB Tunas Bangsa', permitNumber: 'IO/KB/2021/023', issueDate: '5 Jun 2021', expiryDate: '5 Jun 2026', status: 'Aktif', kecamatan: 'Wonokromo' },
  { id: 4, name: 'TK Kartini', permitNumber: 'IO/TK/2019/102', issueDate: '20 Agt 2019', expiryDate: '20 Agt 2024', status: 'Perlu Diperpanjang', kecamatan: 'Tegalsari' },
  { id: 5, name: 'PAUD Pelangi', permitNumber: 'IO/PAUD/2020/067', issueDate: '12 Nov 2020', expiryDate: '12 Nov 2025', status: 'Aktif', kecamatan: 'Sawahan' },
  { id: 6, name: 'TK Harapan Bunda', permitNumber: 'IO/TK/2018/089', issueDate: '8 Apr 2018', expiryDate: '8 Apr 2023', status: 'Expired', kecamatan: 'Sukolilo' }
])

const statuses = ['Semua', 'Aktif', 'Perlu Diperpanjang', 'Expired']

const filteredPermits = computed(() => {
  return permits.value.filter(item => {
    const matchSearch = item.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                       item.permitNumber.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchStatus = !selectedStatus.value || selectedStatus.value === 'Semua' || item.status === selectedStatus.value
    return matchSearch && matchStatus
  })
})

const stats = computed(() => ({
  total: permits.value.length,
  active: permits.value.filter(p => p.status === 'Aktif').length,
  needRenewal: permits.value.filter(p => p.status === 'Perlu Diperpanjang').length,
  expired: permits.value.filter(p => p.status === 'Expired').length
}))

const getStatusColor = (status) => {
  const colors = {
    'Aktif': 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
    'Perlu Diperpanjang': 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
    'Expired': 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
  }
  return colors[status] || 'bg-gray-100 text-gray-700'
}
</script>

<template>
  <DataLayout 
    title="Ijin Operasional" 
    subtitle="Informasi ijin operasional lembaga PAUD di Kota Surabaya"
    icon="📄"
  >
    <div class="space-y-6">
      <!-- Stats -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ stats.total }}</div>
          <div class="text-xs text-gray-500">Total Ijin</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border-l-4 border-green-500">
          <div class="text-2xl font-bold text-green-600">{{ stats.active }}</div>
          <div class="text-xs text-gray-500">Aktif</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border-l-4 border-yellow-500">
          <div class="text-2xl font-bold text-yellow-600">{{ stats.needRenewal }}</div>
          <div class="text-xs text-gray-500">Perlu Perpanjangan</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border-l-4 border-red-500">
          <div class="text-2xl font-bold text-red-600">{{ stats.expired }}</div>
          <div class="text-xs text-gray-500">Kadaluarsa</div>
        </div>
      </div>

      <!-- Filter -->
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex flex-col md:flex-row gap-3">
          <div class="flex-1 relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input v-model="searchQuery" type="text" placeholder="Cari nama atau nomor ijin..." class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm" />
          </div>
          <div class="flex gap-2 flex-wrap">
            <button v-for="status in statuses" :key="status" @click="selectedStatus = status === 'Semua' ? '' : status"
              class="px-3 py-2 rounded-lg text-xs font-medium transition-colors"
              :class="(selectedStatus === status || (status === 'Semua' && !selectedStatus)) ? 'bg-green-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'">
              {{ status }}
            </button>
          </div>
        </div>
      </div>

      <!-- Cards -->
      <div class="grid md:grid-cols-2 gap-4">
        <div v-for="permit in filteredPermits" :key="permit.id" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700">
          <div class="flex items-start justify-between mb-3">
            <div>
              <h3 class="font-bold text-gray-900 dark:text-white text-sm">{{ permit.name }}</h3>
              <p class="text-xs text-gray-500">{{ permit.kecamatan }}</p>
            </div>
            <span class="px-2 py-1 text-xs font-medium rounded-full" :class="getStatusColor(permit.status)">{{ permit.status }}</span>
          </div>
          <div class="space-y-1 text-xs">
            <p><span class="text-gray-500">No. Ijin:</span> <span class="text-gray-900 dark:text-white">{{ permit.permitNumber }}</span></p>
            <p><span class="text-gray-500">Terbit:</span> <span class="text-gray-900 dark:text-white">{{ permit.issueDate }}</span></p>
            <p><span class="text-gray-500">Berlaku:</span> <span :class="permit.status === 'Expired' ? 'text-red-600' : 'text-gray-900 dark:text-white'">{{ permit.expiryDate }}</span></p>
          </div>
        </div>
      </div>

      <div v-if="filteredPermits.length === 0" class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl">
        <p class="text-gray-500 text-sm">Tidak ada data ijin operasional yang sesuai.</p>
      </div>
    </div>
  </DataLayout>
</template>
