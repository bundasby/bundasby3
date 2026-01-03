<script setup>
import { ref, onMounted } from 'vue'
import DataLayout from '@/components/layout/DataLayout.vue'

// Sample statistics data
const stats = ref([
  { label: 'Total Lembaga PAUD', value: 1247, icon: 'building', color: 'primary' },
  { label: 'Total Guru PAUD', value: 4523, icon: 'users', color: 'green' },
  { label: 'Total Peserta Didik', value: 52340, icon: 'children', color: 'blue' },
  { label: 'Lembaga Terakreditasi', value: 892, icon: 'star', color: 'yellow' }
])

// Data per jenis lembaga
const jenisLembaga = ref([
  { jenis: 'TK (Taman Kanak-kanak)', jumlah: 542, persentase: 43.5 },
  { jenis: 'KB (Kelompok Bermain)', jumlah: 389, persentase: 31.2 },
  { jenis: 'TPA (Tempat Penitipan Anak)', jumlah: 156, persentase: 12.5 },
  { jenis: 'SPS (Satuan PAUD Sejenis)', jumlah: 160, persentase: 12.8 }
])

// Data akreditasi
const akreditasiData = ref([
  { grade: 'A (Unggul)', jumlah: 234, persentase: 26.2, color: 'bg-green-500' },
  { grade: 'B (Baik)', jumlah: 412, persentase: 46.2, color: 'bg-blue-500' },
  { grade: 'C (Cukup)', jumlah: 189, persentase: 21.2, color: 'bg-yellow-500' },
  { grade: 'Belum Akreditasi', jumlah: 57, persentase: 6.4, color: 'bg-gray-400' }
])

// Data per kecamatan (top 10)
const dataKecamatan = ref([
  { kecamatan: 'Sukolilo', lembaga: 68, guru: 245, siswa: 2150 },
  { kecamatan: 'Rungkut', lembaga: 62, guru: 218, siswa: 1980 },
  { kecamatan: 'Gubeng', lembaga: 58, guru: 195, siswa: 1820 },
  { kecamatan: 'Tambaksari', lembaga: 55, guru: 188, siswa: 1750 },
  { kecamatan: 'Sawahan', lembaga: 52, guru: 175, siswa: 1680 },
  { kecamatan: 'Wonokromo', lembaga: 48, guru: 162, siswa: 1520 },
  { kecamatan: 'Mulyorejo', lembaga: 45, guru: 155, siswa: 1450 },
  { kecamatan: 'Tenggilis Mejoyo', lembaga: 42, guru: 148, siswa: 1380 },
  { kecamatan: 'Genteng', lembaga: 38, guru: 132, siswa: 1250 },
  { kecamatan: 'Tegalsari', lembaga: 35, guru: 125, siswa: 1180 }
])

const animatedValues = ref([0, 0, 0, 0])

onMounted(() => {
  stats.value.forEach((stat, index) => {
    const duration = 2000
    const steps = 60
    const increment = stat.value / steps
    let current = 0
    const timer = setInterval(() => {
      current += increment
      if (current >= stat.value) {
        animatedValues.value[index] = stat.value
        clearInterval(timer)
      } else {
        animatedValues.value[index] = Math.floor(current)
      }
    }, duration / steps)
  })
})

const formatNumber = (num) => {
  return num.toLocaleString('id-ID')
}
</script>

<template>
  <DataLayout 
    title="Ringkasan Data PAUD" 
    subtitle="Statistik dan ringkasan data Pendidikan Anak Usia Dini di Kota Surabaya"
    icon="📊"
  >
    <div class="space-y-6">
      <!-- Statistics Cards -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div 
          v-for="(stat, index) in stats" 
          :key="stat.label"
          class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-center"
        >
          <div 
            class="w-12 h-12 mx-auto mb-3 rounded-xl flex items-center justify-center"
            :class="{
              'bg-primary-100 dark:bg-primary-900/30': stat.color === 'primary',
              'bg-green-100 dark:bg-green-900/30': stat.color === 'green',
              'bg-blue-100 dark:bg-blue-900/30': stat.color === 'blue',
              'bg-yellow-100 dark:bg-yellow-900/30': stat.color === 'yellow'
            }"
          >
            <svg v-if="stat.icon === 'building'" class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <svg v-if="stat.icon === 'users'" class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <svg v-if="stat.icon === 'children'" class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <svg v-if="stat.icon === 'star'" class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
            </svg>
          </div>
          <div class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
            {{ formatNumber(animatedValues[index]) }}
          </div>
          <div class="text-xs text-gray-600 dark:text-gray-400">
            {{ stat.label }}
          </div>
        </div>
      </div>

      <!-- Charts Section -->
      <div class="grid lg:grid-cols-2 gap-6">
        <!-- Jenis Lembaga Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
          <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-5">
            📈 Distribusi Jenis Lembaga PAUD
          </h3>
          <div class="space-y-4">
            <div v-for="item in jenisLembaga" :key="item.jenis" class="space-y-2">
              <div class="flex justify-between text-sm">
                <span class="text-gray-700 dark:text-gray-300">{{ item.jenis }}</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ item.jumlah }} ({{ item.persentase }}%)</span>
              </div>
              <div class="h-2.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                <div 
                  class="h-full bg-gradient-to-r from-primary-500 to-primary-600 rounded-full transition-all duration-1000"
                  :style="{ width: item.persentase + '%' }"
                ></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Akreditasi Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
          <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-5">
            🏆 Status Akreditasi Lembaga PAUD
          </h3>
          <div class="flex items-center justify-center mb-5">
            <!-- Donut Chart Visualization -->
            <div class="relative w-40 h-40">
              <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="40" fill="none" stroke="#e5e7eb" stroke-width="12"/>
                <circle cx="50" cy="50" r="40" fill="none" stroke="#22c55e" stroke-width="12" 
                  stroke-dasharray="65.4 188.5" stroke-dashoffset="0"/>
                <circle cx="50" cy="50" r="40" fill="none" stroke="#3b82f6" stroke-width="12" 
                  stroke-dasharray="115.5 188.5" stroke-dashoffset="-65.4"/>
                <circle cx="50" cy="50" r="40" fill="none" stroke="#eab308" stroke-width="12" 
                  stroke-dasharray="53 188.5" stroke-dashoffset="-180.9"/>
              </svg>
              <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center">
                  <div class="text-xl font-bold text-gray-900 dark:text-white">892</div>
                  <div class="text-xs text-gray-500">Terakreditasi</div>
                </div>
              </div>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-2">
            <div v-for="item in akreditasiData" :key="item.grade" class="flex items-center gap-2">
              <div :class="['w-2.5 h-2.5 rounded-full', item.color]"></div>
              <span class="text-xs text-gray-600 dark:text-gray-400">{{ item.grade }}: {{ item.jumlah }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Data per Kecamatan Table -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-5 border-b border-gray-200 dark:border-gray-700">
          <h3 class="text-base font-semibold text-gray-900 dark:text-white">
            🗺️ Data PAUD per Kecamatan (Top 10)
          </h3>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">No</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Kecamatan</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Lembaga</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Guru</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Siswa</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
              <tr 
                v-for="(item, index) in dataKecamatan" 
                :key="item.kecamatan"
                class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
              >
                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ index + 1 }}</td>
                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ item.kecamatan }}</td>
                <td class="px-4 py-3 text-center text-sm text-gray-600 dark:text-gray-400">{{ item.lembaga }}</td>
                <td class="px-4 py-3 text-center text-sm text-gray-600 dark:text-gray-400">{{ item.guru }}</td>
                <td class="px-4 py-3 text-center text-sm text-gray-600 dark:text-gray-400">{{ formatNumber(item.siswa) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </DataLayout>
</template>
