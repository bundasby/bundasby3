<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const loading = ref(true)
const activeTab = ref('anak')
const selectedKecamatan = ref('')

// Mock data for diagrams
const data = {
  anak: {
    title: 'Diagram Anak 5-6 Tahun',
    subtitle: 'Data anak usia 5-6 tahun per kecamatan',
    categories: ['Laki-laki', 'Perempuan'],
    colors: ['#3b82f6', '#ec4899'],
    items: [
      { name: 'Gayungan', values: [245, 230] },
      { name: 'Wonocolo', values: [312, 298] },
      { name: 'Tenggilis Mejoyo', values: [189, 201] },
      { name: 'Rungkut', values: [456, 423] },
      { name: 'Sukolilo', values: [378, 356] },
      { name: 'Gubeng', values: [289, 276] },
      { name: 'Mulyorejo', values: [234, 221] }
    ]
  },
  guru: {
    title: 'Diagram Guru PAUD',
    subtitle: 'Data guru PAUD per tingkat pendidikan',
    categories: ['S1', 'D3', 'SMA/SMK'],
    colors: ['#10b981', '#f59e0b', '#8b5cf6'],
    items: [
      { name: 'Gayungan', values: [45, 12, 8] },
      { name: 'Wonocolo', values: [52, 18, 11] },
      { name: 'Tenggilis Mejoyo', values: [38, 15, 9] },
      { name: 'Rungkut', values: [67, 23, 15] },
      { name: 'Sukolilo', values: [58, 20, 12] }
    ]
  },
  lembaga: {
    title: 'Diagram Lembaga PAUD',
    subtitle: 'Data lembaga PAUD per jenis',
    categories: ['TK', 'KB', 'TPA', 'SPS'],
    colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
    items: [
      { name: 'Gayungan', values: [12, 8, 3, 2] },
      { name: 'Wonocolo', values: [15, 10, 4, 3] },
      { name: 'Tenggilis Mejoyo', values: [10, 6, 2, 1] },
      { name: 'Rungkut', values: [20, 14, 6, 4] },
      { name: 'Sukolilo', values: [18, 12, 5, 3] }
    ]
  }
}

const tabs = [
  { key: 'anak', label: 'Anak 5-6 Tahun', icon: '👶' },
  { key: 'guru', label: 'Guru', icon: '👩‍🏫' },
  { key: 'lembaga', label: 'Lembaga', icon: '🏫' }
]

const currentData = computed(() => data[activeTab.value])

const maxValue = computed(() => {
  const values = currentData.value.items.flatMap(i => i.values)
  return Math.max(...values)
})

const totals = computed(() => {
  const items = currentData.value.items
  return currentData.value.categories.map((_, idx) => 
    items.reduce((sum, item) => sum + item.values[idx], 0)
  )
})

const grandTotal = computed(() => totals.value.reduce((a, b) => a + b, 0))

const getBarWidth = (value) => {
  return (value / maxValue.value) * 100
}

const exportData = () => {
  alert('Export data - fitur akan segera hadir!')
}

onMounted(() => {
  setTimeout(() => { loading.value = false }, 500)
})
</script>

<template>
  <AdminLayout title="Diagram Data">
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
      
      <div class="ml-auto">
        <button @click="exportData" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm">
          📊 Export
        </button>
      </div>
    </div>

    <div v-if="loading" class="text-center py-12">
      <div class="w-10 h-10 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin mx-auto"></div>
    </div>

    <div v-else class="space-y-6">
      <!-- Summary Cards -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
          <p class="text-sm text-gray-500">Total</p>
          <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ grandTotal.toLocaleString() }}</p>
        </div>
        <div 
          v-for="(cat, idx) in currentData.categories" 
          :key="cat"
          class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700"
        >
          <p class="text-sm text-gray-500">{{ cat }}</p>
          <p class="text-3xl font-bold" :style="{ color: currentData.colors[idx] }">
            {{ totals[idx].toLocaleString() }}
          </p>
        </div>
      </div>

      <!-- Chart Container -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex justify-between items-start mb-6">
          <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ currentData.title }}</h2>
            <p class="text-sm text-gray-500">{{ currentData.subtitle }}</p>
          </div>
          <!-- Legend -->
          <div class="flex items-center gap-4">
            <div v-for="(cat, idx) in currentData.categories" :key="cat" class="flex items-center gap-2">
              <div class="w-3 h-3 rounded-full" :style="{ backgroundColor: currentData.colors[idx] }"></div>
              <span class="text-sm text-gray-600 dark:text-gray-400">{{ cat }}</span>
            </div>
          </div>
        </div>

        <!-- Horizontal Bar Chart -->
        <div class="space-y-4">
          <div v-for="item in currentData.items" :key="item.name" class="group">
            <div class="flex items-center gap-4 mb-2">
              <span class="w-32 text-sm font-medium text-gray-700 dark:text-gray-300 truncate">{{ item.name }}</span>
              <div class="flex-1 flex gap-1">
                <div 
                  v-for="(value, idx) in item.values" 
                  :key="idx"
                  class="h-8 rounded transition-all hover:opacity-80 flex items-center justify-end pr-2"
                  :style="{ 
                    width: getBarWidth(value) + '%', 
                    backgroundColor: currentData.colors[idx],
                    minWidth: '40px'
                  }"
                >
                  <span class="text-xs text-white font-medium">{{ value }}</span>
                </div>
              </div>
              <span class="text-sm font-bold text-gray-900 dark:text-white w-16 text-right">
                {{ item.values.reduce((a, b) => a + b, 0).toLocaleString() }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Data Table -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
          <h3 class="font-semibold text-gray-900 dark:text-white">Detail Data per Kecamatan</h3>
        </div>
        <table class="w-full">
          <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Kecamatan</th>
              <th v-for="cat in currentData.categories" :key="cat" class="px-4 py-3 text-center text-sm font-medium text-gray-500">{{ cat }}</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Total</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="item in currentData.items" :key="item.name" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
              <td class="px-4 py-3 text-gray-900 dark:text-white font-medium">{{ item.name }}</td>
              <td v-for="(value, idx) in item.values" :key="idx" class="px-4 py-3 text-center" :style="{ color: currentData.colors[idx] }">
                {{ value.toLocaleString() }}
              </td>
              <td class="px-4 py-3 text-center font-bold text-gray-900 dark:text-white">
                {{ item.values.reduce((a, b) => a + b, 0).toLocaleString() }}
              </td>
            </tr>
          </tbody>
          <tfoot class="bg-gray-100 dark:bg-gray-700/50">
            <tr>
              <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">Total</td>
              <td v-for="(total, idx) in totals" :key="idx" class="px-4 py-3 text-center font-bold" :style="{ color: currentData.colors[idx] }">
                {{ total.toLocaleString() }}
              </td>
              <td class="px-4 py-3 text-center font-bold text-primary-600">{{ grandTotal.toLocaleString() }}</td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>
