<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

// State
const items = ref([])
const loading = ref(true)
const showModal = ref(false)
const editingItem = ref(null)
const activeTab = ref('user') // user, profil, program, kegiatan

// Mock data
const mockData = {
  user: [
    { kecamatan: 'Gayungan', total: 5, aktif: 5, nonaktif: 0 },
    { kecamatan: 'Wonocolo', total: 4, aktif: 4, nonaktif: 0 },
    { kecamatan: 'Tenggilis Mejoyo', total: 6, aktif: 5, nonaktif: 1 },
    { kecamatan: 'Rungkut', total: 8, aktif: 7, nonaktif: 1 },
    { kecamatan: 'Sukolilo', total: 7, aktif: 6, nonaktif: 1 },
  ],
  profil: [
    { kecamatan: 'Gayungan', lengkap: 5, pending: 0, persentase: 100 },
    { kecamatan: 'Wonocolo', lengkap: 3, pending: 1, persentase: 75 },
    { kecamatan: 'Tenggilis Mejoyo', lengkap: 4, pending: 2, persentase: 67 },
  ],
  program: [
    { program: 'Pendataan PAUD HI', target: 31, tercapai: 28, persentase: 90 },
    { program: 'Pelatihan Guru', target: 154, tercapai: 120, persentase: 78 },
    { program: 'Vaksinasi Anak', target: 5000, tercapai: 4200, persentase: 84 },
  ],
  kegiatan: [
    { bulan: 'Januari', rencana: 10, terlaksana: 8, persentase: 80 },
    { bulan: 'Februari', rencana: 12, terlaksana: 10, persentase: 83 },
    { bulan: 'Maret', rencana: 15, terlaksana: 0, persentase: 0 },
  ]
}

const tabs = [
  { key: 'user', label: 'User', icon: '👥' },
  { key: 'profil', label: 'Profil', icon: '📋' },
  { key: 'program', label: 'Program Kerja', icon: '📊' },
  { key: 'kegiatan', label: 'Kegiatan', icon: '📝' }
]

const currentData = computed(() => mockData[activeTab.value] || [])

const totals = computed(() => {
  if (activeTab.value === 'user') {
    return {
      total: currentData.value.reduce((sum, d) => sum + d.total, 0),
      aktif: currentData.value.reduce((sum, d) => sum + d.aktif, 0),
      nonaktif: currentData.value.reduce((sum, d) => sum + d.nonaktif, 0)
    }
  }
  return {}
})

const exportPDF = () => {
  alert('Export PDF - fitur akan segera hadir!')
}

const exportExcel = () => {
  alert('Export Excel - fitur akan segera hadir!')
}

onMounted(() => {
  setTimeout(() => { loading.value = false }, 500)
})
</script>

<template>
  <AdminLayout title="Rekap Data">
    <!-- Tabs -->
    <div class="flex flex-wrap gap-2 mb-6">
      <button
        v-for="tab in tabs"
        :key="tab.key"
        @click="activeTab = tab.key"
        class="px-4 py-2 rounded-lg font-medium text-sm transition-colors"
        :class="activeTab === tab.key 
          ? 'bg-primary-600 text-white' 
          : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700'"
      >
        {{ tab.icon }} {{ tab.label }}
      </button>
    </div>

    <!-- Export Buttons -->
    <div class="flex justify-end gap-2 mb-4">
      <button @click="exportPDF" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm flex items-center gap-2">
        📄 Export PDF
      </button>
      <button @click="exportExcel" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm flex items-center gap-2">
        📊 Export Excel
      </button>
    </div>

    <!-- Content -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
      <div v-if="loading" class="text-center py-12">
        <div class="w-10 h-10 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin mx-auto"></div>
      </div>

      <!-- User Tab -->
      <div v-else-if="activeTab === 'user'">
        <div class="grid grid-cols-3 gap-4 p-4 bg-gray-50 dark:bg-gray-700/50">
          <div class="text-center">
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ totals.total }}</p>
            <p class="text-sm text-gray-500">Total User</p>
          </div>
          <div class="text-center">
            <p class="text-2xl font-bold text-green-600">{{ totals.aktif }}</p>
            <p class="text-sm text-gray-500">Aktif</p>
          </div>
          <div class="text-center">
            <p class="text-2xl font-bold text-red-600">{{ totals.nonaktif }}</p>
            <p class="text-sm text-gray-500">Non-aktif</p>
          </div>
        </div>
        <table class="w-full">
          <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Kecamatan</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Total</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Aktif</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Non-aktif</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="item in currentData" :key="item.kecamatan" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
              <td class="px-4 py-3 text-gray-900 dark:text-white">{{ item.kecamatan }}</td>
              <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ item.total }}</td>
              <td class="px-4 py-3 text-center text-green-600">{{ item.aktif }}</td>
              <td class="px-4 py-3 text-center text-red-600">{{ item.nonaktif }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Profil Tab -->
      <div v-else-if="activeTab === 'profil'">
        <table class="w-full">
          <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Kecamatan</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Lengkap</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Pending</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Persentase</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="item in currentData" :key="item.kecamatan" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
              <td class="px-4 py-3 text-gray-900 dark:text-white">{{ item.kecamatan }}</td>
              <td class="px-4 py-3 text-center text-green-600">{{ item.lengkap }}</td>
              <td class="px-4 py-3 text-center text-yellow-600">{{ item.pending }}</td>
              <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                  <div class="flex-1 h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                    <div class="h-full bg-primary-500 rounded-full" :style="{ width: item.persentase + '%' }"></div>
                  </div>
                  <span class="text-sm text-gray-600 dark:text-gray-400 w-12 text-right">{{ item.persentase }}%</span>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Program Tab -->
      <div v-else-if="activeTab === 'program'">
        <table class="w-full">
          <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Program</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Target</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Tercapai</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Progress</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="item in currentData" :key="item.program" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
              <td class="px-4 py-3 text-gray-900 dark:text-white">{{ item.program }}</td>
              <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ item.target.toLocaleString() }}</td>
              <td class="px-4 py-3 text-center text-primary-600">{{ item.tercapai.toLocaleString() }}</td>
              <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                  <div class="flex-1 h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500 rounded-full" :style="{ width: item.persentase + '%' }"></div>
                  </div>
                  <span class="text-sm font-medium" :class="item.persentase >= 80 ? 'text-green-600' : 'text-yellow-600'">{{ item.persentase }}%</span>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Kegiatan Tab -->
      <div v-else-if="activeTab === 'kegiatan'">
        <table class="w-full">
          <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Bulan</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Rencana</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Terlaksana</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Capaian</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="item in currentData" :key="item.bulan" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
              <td class="px-4 py-3 text-gray-900 dark:text-white">{{ item.bulan }} 2026</td>
              <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ item.rencana }}</td>
              <td class="px-4 py-3 text-center text-primary-600">{{ item.terlaksana }}</td>
              <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                  <div class="flex-1 h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 rounded-full" :style="{ width: item.persentase + '%' }"></div>
                  </div>
                  <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ item.persentase }}%</span>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>
