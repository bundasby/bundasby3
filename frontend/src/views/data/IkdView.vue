<script setup>
import { ref } from 'vue'
import DataLayout from '@/components/layout/DataLayout.vue'

// Data IKD
const ikdStats = ref({
  totalLembaga: 1247,
  ikdBaik: 876,
  ikdCukup: 289,
  ikdKurang: 82
})

const kriteriaPenilaian = ref([
  {
    nama: 'Kelengkapan (KL)',
    bobot: 30,
    icon: '📋',
    deskripsi: 'Mengukur seberapa lengkap data yang terisi dalam Dapodik',
    color: 'blue'
  },
  {
    nama: 'Validitas (VL)',
    bobot: 40,
    icon: '✅',
    deskripsi: 'Menilai keakuratan dan keabsahan data yang dimasukkan',
    color: 'green'
  },
  {
    nama: 'Kemutakhiran (MT)',
    bobot: 30,
    icon: '🔄',
    deskripsi: 'Memastikan data yang tersedia adalah data terbaru hasil sinkronisasi',
    color: 'purple'
  }
])

const entitasData = ref([
  { nama: 'Satuan Pendidikan', icon: '🏫', status: 95 },
  { nama: 'Sarana & Prasarana', icon: '🏠', status: 88 },
  { nama: 'Pendidik & Tenaga Kependidikan', icon: '👨‍🏫', status: 92 },
  { nama: 'Peserta Didik', icon: '👶', status: 96 },
  { nama: 'Pembelajaran', icon: '📚', status: 85 }
])

const getStatusColor = (nilai) => {
  if (nilai >= 90) return 'text-green-600'
  if (nilai >= 80) return 'text-blue-600'
  if (nilai >= 70) return 'text-yellow-600'
  return 'text-red-600'
}

const getBarColor = (nilai) => {
  if (nilai >= 90) return 'bg-green-500'
  if (nilai >= 80) return 'bg-blue-500'
  if (nilai >= 70) return 'bg-yellow-500'
  return 'bg-red-500'
}
</script>

<template>
  <DataLayout 
    title="Indeks Kualitas Dapodik (IKD)" 
    subtitle="Penilaian kualitas data pokok pendidikan satuan PAUD di Kota Surabaya"
    icon="📊"
  >
    <div class="space-y-6">
      <!-- Stats -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ ikdStats.totalLembaga.toLocaleString('id-ID') }}</div>
          <div class="text-xs text-gray-500">Total Lembaga</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-green-600">{{ ikdStats.ikdBaik }}</div>
          <div class="text-xs text-gray-500">IKD Baik (≥80)</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-yellow-600">{{ ikdStats.ikdCukup }}</div>
          <div class="text-xs text-gray-500">IKD Cukup (60-79)</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-red-600">{{ ikdStats.ikdKurang }}</div>
          <div class="text-xs text-gray-500">IKD Kurang (&lt;60)</div>
        </div>
      </div>

      <!-- About IKD -->
      <div class="bg-gradient-to-r from-sky-50 to-cyan-50 dark:from-sky-900/20 dark:to-cyan-900/20 rounded-xl p-5 border border-sky-100 dark:border-sky-800">
        <h3 class="font-bold text-sky-900 dark:text-sky-300 mb-2">📊 Apa itu Indeks Kualitas Dapodik?</h3>
        <p class="text-sm text-sky-700 dark:text-sky-400">
          Indeks Kualitas Dapodik (IKD) adalah ukuran yang menilai kualitas data pokok pendidikan berdasarkan 
          kelengkapan, validitas, dan kemutakhiran data. IKD yang baik memastikan data pendidikan akurat 
          untuk pengambilan kebijakan dan penyaluran bantuan.
        </p>
      </div>

      <!-- Formula IKD -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">📐 Rumus Perhitungan IKD</h3>
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 text-center mb-4">
          <p class="text-lg font-mono font-bold text-gray-900 dark:text-white">
            IKD = (KL × 30%) + (VL × 40%) + (MT × 30%)
          </p>
        </div>
        <div class="grid md:grid-cols-3 gap-4">
          <div v-for="kriteria in kriteriaPenilaian" :key="kriteria.nama" class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
            <div class="flex items-center gap-2 mb-2">
              <span class="text-2xl">{{ kriteria.icon }}</span>
              <div>
                <h4 class="font-semibold text-gray-900 dark:text-white text-sm">{{ kriteria.nama }}</h4>
                <span class="text-xs font-bold" :class="kriteria.color === 'blue' ? 'text-blue-600' : kriteria.color === 'green' ? 'text-green-600' : 'text-purple-600'">
                  Bobot {{ kriteria.bobot }}%
                </span>
              </div>
            </div>
            <p class="text-xs text-gray-500">{{ kriteria.deskripsi }}</p>
          </div>
        </div>
      </div>

      <!-- Entitas Data -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">🗄️ Capaian IKD per Entitas Data</h3>
        <div class="space-y-4">
          <div v-for="entitas in entitasData" :key="entitas.nama" class="flex items-center gap-4">
            <div class="text-2xl w-8">{{ entitas.icon }}</div>
            <div class="flex-1">
              <div class="flex justify-between mb-1">
                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ entitas.nama }}</span>
                <span :class="['text-sm font-bold', getStatusColor(entitas.status)]">{{ entitas.status }}%</span>
              </div>
              <div class="h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                <div :class="['h-full rounded-full transition-all', getBarColor(entitas.status)]" :style="{ width: entitas.status + '%' }"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tips -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">💡 Tips Meningkatkan IKD</h3>
        <div class="grid md:grid-cols-2 gap-4">
          <div class="flex items-start gap-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
            <span class="text-xl">✅</span>
            <div>
              <h4 class="font-semibold text-green-900 dark:text-green-300 text-sm">Lengkapi Semua Data</h4>
              <p class="text-xs text-green-700 dark:text-green-400">Isi semua field yang tersedia di Dapodik</p>
            </div>
          </div>
          <div class="flex items-start gap-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
            <span class="text-xl">🔄</span>
            <div>
              <h4 class="font-semibold text-blue-900 dark:text-blue-300 text-sm">Sinkronisasi Rutin</h4>
              <p class="text-xs text-blue-700 dark:text-blue-400">Lakukan sync data secara berkala</p>
            </div>
          </div>
          <div class="flex items-start gap-3 p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
            <span class="text-xl">🔍</span>
            <div>
              <h4 class="font-semibold text-purple-900 dark:text-purple-300 text-sm">Verifikasi Data</h4>
              <p class="text-xs text-purple-700 dark:text-purple-400">Periksa keakuratan data yang diinput</p>
            </div>
          </div>
          <div class="flex items-start gap-3 p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
            <span class="text-xl">📱</span>
            <div>
              <h4 class="font-semibold text-orange-900 dark:text-orange-300 text-sm">Update Berkala</h4>
              <p class="text-xs text-orange-700 dark:text-orange-400">Perbarui data setiap ada perubahan</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DataLayout>
</template>
