<script setup>
import { ref, computed } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

// State
const activeTab = ref('dashboard')
const searchQuery = ref('')
const selectedKecamatan = ref('')
const showModal = ref(false)
const editingItem = ref(null)
const saving = ref(false)

// Mock data vaksinasi
const vaksinasiData = ref([
  { id: 1, sekolah: 'TK Aisyiyah 1', kecamatan: 'Genteng', totalSiswa: 80, bcg: 78, polio: 80, campak: 75, dpt: 77, hib: 76 },
  { id: 2, sekolah: 'KB Tunas Harapan', kecamatan: 'Tegalsari', totalSiswa: 45, bcg: 43, polio: 45, campak: 40, dpt: 42, hib: 41 },
  { id: 3, sekolah: 'PAUD Ceria', kecamatan: 'Sawahan', totalSiswa: 60, bcg: 58, polio: 60, campak: 55, dpt: 57, hib: 56 },
  { id: 4, sekolah: 'TK Pembina', kecamatan: 'Wonokromo', totalSiswa: 100, bcg: 95, polio: 98, campak: 90, dpt: 94, hib: 93 },
  { id: 5, sekolah: 'KB Melati', kecamatan: 'Rungkut', totalSiswa: 35, bcg: 33, polio: 35, campak: 30, dpt: 32, hib: 31 },
  { id: 6, sekolah: 'TK Dharma Wanita', kecamatan: 'Sukolilo', totalSiswa: 70, bcg: 68, polio: 70, campak: 65, dpt: 67, hib: 66 }
])

const kecamatanList = ['Genteng', 'Tegalsari', 'Sawahan', 'Wonokromo', 'Rungkut', 'Sukolilo', 'Mulyorejo', 'Tambaksari']

// Form
const form = ref({
  sekolah: '',
  kecamatan: '',
  totalSiswa: 0,
  bcg: 0,
  polio: 0,
  campak: 0,
  dpt: 0,
  hib: 0
})

const tabs = [
  { id: 'dashboard', label: 'Dashboard', icon: '📊' },
  { id: 'data', label: 'Data Sekolah', icon: '🏫' },
  { id: 'laporan', label: 'Laporan', icon: '📄' }
]

const stats = computed(() => {
  const totalSiswa = vaksinasiData.value.reduce((sum, v) => sum + v.totalSiswa, 0)
  const totalBcg = vaksinasiData.value.reduce((sum, v) => sum + v.bcg, 0)
  const totalPolio = vaksinasiData.value.reduce((sum, v) => sum + v.polio, 0)
  const totalCampak = vaksinasiData.value.reduce((sum, v) => sum + v.campak, 0)
  const totalDpt = vaksinasiData.value.reduce((sum, v) => sum + v.dpt, 0)
  const totalHib = vaksinasiData.value.reduce((sum, v) => sum + v.hib, 0)
  
  return {
    totalSekolah: vaksinasiData.value.length,
    totalSiswa,
    bcg: { count: totalBcg, pct: Math.round((totalBcg / totalSiswa) * 100) },
    polio: { count: totalPolio, pct: Math.round((totalPolio / totalSiswa) * 100) },
    campak: { count: totalCampak, pct: Math.round((totalCampak / totalSiswa) * 100) },
    dpt: { count: totalDpt, pct: Math.round((totalDpt / totalSiswa) * 100) },
    hib: { count: totalHib, pct: Math.round((totalHib / totalSiswa) * 100) }
  }
})

const filteredData = computed(() => {
  let result = vaksinasiData.value
  
  if (selectedKecamatan.value) {
    result = result.filter(v => v.kecamatan === selectedKecamatan.value)
  }
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter(v => 
      v.sekolah.toLowerCase().includes(query) ||
      v.kecamatan.toLowerCase().includes(query)
    )
  }
  
  return result
})

const getCoverageColor = (pct) => {
  if (pct >= 90) return 'text-green-600 dark:text-green-400'
  if (pct >= 70) return 'text-amber-600 dark:text-amber-400'
  return 'text-red-600 dark:text-red-400'
}

const getCoverageBg = (pct) => {
  if (pct >= 90) return 'bg-green-500'
  if (pct >= 70) return 'bg-amber-500'
  return 'bg-red-500'
}

const openModal = (item = null) => {
  if (item) {
    editingItem.value = item
    form.value = { ...item }
  } else {
    editingItem.value = null
    form.value = { sekolah: '', kecamatan: '', totalSiswa: 0, bcg: 0, polio: 0, campak: 0, dpt: 0, hib: 0 }
  }
  showModal.value = true
}

const saveItem = async () => {
  saving.value = true
  await new Promise(r => setTimeout(r, 800))
  
  if (editingItem.value) {
    const idx = vaksinasiData.value.findIndex(v => v.id === editingItem.value.id)
    if (idx !== -1) vaksinasiData.value[idx] = { ...vaksinasiData.value[idx], ...form.value }
  } else {
    vaksinasiData.value.push({ id: Date.now(), ...form.value })
  }
  
  saving.value = false
  showModal.value = false
}
</script>

<template>
  <AdminLayout title="Data Vaksinasi">
    <div class="space-y-6">
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

      <!-- Dashboard Tab -->
      <div v-if="activeTab === 'dashboard'" class="space-y-6">
        <!-- Summary Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Sekolah</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.totalSekolah }}</p>
          </div>
          <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Anak</p>
            <p class="text-2xl font-bold text-blue-600">{{ stats.totalSiswa }}</p>
          </div>
          <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Rata-rata Cakupan</p>
            <p class="text-2xl font-bold text-green-600">{{ Math.round((stats.bcg.pct + stats.polio.pct + stats.campak.pct) / 3) }}%</p>
          </div>
          <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Kecamatan</p>
            <p class="text-2xl font-bold text-purple-600">{{ [...new Set(vaksinasiData.map(v => v.kecamatan))].length }}</p>
          </div>
        </div>

        <!-- Vaccine Coverage Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
          <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-3">
              <span class="text-2xl">💉</span>
              <span class="text-2xl font-bold" :class="getCoverageColor(stats.bcg.pct)">{{ stats.bcg.pct }}%</span>
            </div>
            <p class="font-medium text-gray-900 dark:text-white">BCG</p>
            <p class="text-sm text-gray-500">{{ stats.bcg.count }} dari {{ stats.totalSiswa }} anak</p>
            <div class="mt-3 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
              <div class="h-2 rounded-full transition-all" :class="getCoverageBg(stats.bcg.pct)" :style="{ width: stats.bcg.pct + '%' }"></div>
            </div>
          </div>

          <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-3">
              <span class="text-2xl">💧</span>
              <span class="text-2xl font-bold" :class="getCoverageColor(stats.polio.pct)">{{ stats.polio.pct }}%</span>
            </div>
            <p class="font-medium text-gray-900 dark:text-white">Polio</p>
            <p class="text-sm text-gray-500">{{ stats.polio.count }} dari {{ stats.totalSiswa }} anak</p>
            <div class="mt-3 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
              <div class="h-2 rounded-full transition-all" :class="getCoverageBg(stats.polio.pct)" :style="{ width: stats.polio.pct + '%' }"></div>
            </div>
          </div>

          <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-3">
              <span class="text-2xl">🔴</span>
              <span class="text-2xl font-bold" :class="getCoverageColor(stats.campak.pct)">{{ stats.campak.pct }}%</span>
            </div>
            <p class="font-medium text-gray-900 dark:text-white">Campak</p>
            <p class="text-sm text-gray-500">{{ stats.campak.count }} dari {{ stats.totalSiswa }} anak</p>
            <div class="mt-3 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
              <div class="h-2 rounded-full transition-all" :class="getCoverageBg(stats.campak.pct)" :style="{ width: stats.campak.pct + '%' }"></div>
            </div>
          </div>

          <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-3">
              <span class="text-2xl">🛡️</span>
              <span class="text-2xl font-bold" :class="getCoverageColor(stats.dpt.pct)">{{ stats.dpt.pct }}%</span>
            </div>
            <p class="font-medium text-gray-900 dark:text-white">DPT</p>
            <p class="text-sm text-gray-500">{{ stats.dpt.count }} dari {{ stats.totalSiswa }} anak</p>
            <div class="mt-3 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
              <div class="h-2 rounded-full transition-all" :class="getCoverageBg(stats.dpt.pct)" :style="{ width: stats.dpt.pct + '%' }"></div>
            </div>
          </div>

          <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-3">
              <span class="text-2xl">🧬</span>
              <span class="text-2xl font-bold" :class="getCoverageColor(stats.hib.pct)">{{ stats.hib.pct }}%</span>
            </div>
            <p class="font-medium text-gray-900 dark:text-white">HiB</p>
            <p class="text-sm text-gray-500">{{ stats.hib.count }} dari {{ stats.totalSiswa }} anak</p>
            <div class="mt-3 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
              <div class="h-2 rounded-full transition-all" :class="getCoverageBg(stats.hib.pct)" :style="{ width: stats.hib.pct + '%' }"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Data Tab -->
      <div v-if="activeTab === 'data'" class="space-y-4">
        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
          <div class="flex gap-3">
            <div class="relative">
              <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input v-model="searchQuery" type="text" placeholder="Cari sekolah..." class="w-64 pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
            <select v-model="selectedKecamatan" class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
              <option value="">Semua Kecamatan</option>
              <option v-for="kec in kecamatanList" :key="kec" :value="kec">{{ kec }}</option>
            </select>
          </div>
          <div class="flex gap-3">
            <button class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium flex items-center gap-2 hover:bg-gray-50 dark:hover:bg-gray-700">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
              Export
            </button>
            <button @click="openModal()" class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
              Tambah Data
            </button>
          </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sekolah</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kecamatan</th>
                  <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total</th>
                  <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">BCG</th>
                  <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Polio</th>
                  <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Campak</th>
                  <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">DPT</th>
                  <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">HiB</th>
                  <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="(item, index) in filteredData" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                  <td class="px-4 py-3 text-sm text-gray-500">{{ index + 1 }}</td>
                  <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ item.sekolah }}</td>
                  <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ item.kecamatan }}</td>
                  <td class="px-4 py-3 text-center text-sm font-medium text-gray-900 dark:text-white">{{ item.totalSiswa }}</td>
                  <td class="px-4 py-3 text-center">
                    <span class="text-sm font-medium" :class="getCoverageColor((item.bcg / item.totalSiswa) * 100)">{{ item.bcg }}</span>
                  </td>
                  <td class="px-4 py-3 text-center">
                    <span class="text-sm font-medium" :class="getCoverageColor((item.polio / item.totalSiswa) * 100)">{{ item.polio }}</span>
                  </td>
                  <td class="px-4 py-3 text-center">
                    <span class="text-sm font-medium" :class="getCoverageColor((item.campak / item.totalSiswa) * 100)">{{ item.campak }}</span>
                  </td>
                  <td class="px-4 py-3 text-center">
                    <span class="text-sm font-medium" :class="getCoverageColor((item.dpt / item.totalSiswa) * 100)">{{ item.dpt }}</span>
                  </td>
                  <td class="px-4 py-3 text-center">
                    <span class="text-sm font-medium" :class="getCoverageColor((item.hib / item.totalSiswa) * 100)">{{ item.hib }}</span>
                  </td>
                  <td class="px-4 py-3 text-center">
                    <button @click="openModal(item)" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Edit</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Laporan Tab Placeholder -->
      <div v-if="activeTab === 'laporan'" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
        <div class="text-gray-400 dark:text-gray-500">
          <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <p class="text-lg font-medium">Fitur Laporan Vaksinasi</p>
          <p class="text-sm mt-2">Generate dan download laporan cakupan vaksinasi</p>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black/50" @click="showModal = false"></div>
      <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-lg">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ editingItem ? 'Edit' : 'Tambah' }} Data Vaksinasi</h2>
        </div>
        <div class="p-6 space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sekolah</label>
              <input v-model="form.sekolah" type="text" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kecamatan</label>
              <select v-model="form.kecamatan" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                <option v-for="kec in kecamatanList" :key="kec" :value="kec">{{ kec }}</option>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Siswa</label>
            <input v-model.number="form.totalSiswa" type="number" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
          </div>
          <div class="grid grid-cols-5 gap-3">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">BCG</label>
              <input v-model.number="form.bcg" type="number" class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Polio</label>
              <input v-model.number="form.polio" type="number" class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Campak</label>
              <input v-model.number="form.campak" type="number" class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">DPT</label>
              <input v-model.number="form.dpt" type="number" class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">HiB</label>
              <input v-model.number="form.hib" type="number" class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
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
