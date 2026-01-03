<script setup>
import { ref, computed } from 'vue'
import DataLayout from '@/components/layout/DataLayout.vue'

const selectedKecamatan = ref('')
const selectedKelurahan = ref('')

// Data per kecamatan dan kelurahan
const dataWilayah = ref([
  // Kecamatan Genteng
  { kecamatan: 'Genteng', kelurahan: 'Embong Kaliasin', apk: 86.5, aks: 84.2, ats: 2.3, anakUsiaPaud: 1250, anakSekolah: 1052, anakTidakSekolah: 29 },
  { kecamatan: 'Genteng', kelurahan: 'Ketabang', apk: 88.2, aks: 85.1, ats: 1.8, anakUsiaPaud: 980, anakSekolah: 834, anakTidakSekolah: 18 },
  { kecamatan: 'Genteng', kelurahan: 'Genteng', apk: 85.4, aks: 82.8, ats: 2.6, anakUsiaPaud: 870, anakSekolah: 720, anakTidakSekolah: 23 },
  { kecamatan: 'Genteng', kelurahan: 'Peneleh', apk: 84.1, aks: 81.5, ats: 2.6, anakUsiaPaud: 920, anakSekolah: 750, anakTidakSekolah: 24 },
  // Kecamatan Gubeng
  { kecamatan: 'Gubeng', kelurahan: 'Gubeng', apk: 87.3, aks: 84.8, ats: 2.5, anakUsiaPaud: 1580, anakSekolah: 1340, anakTidakSekolah: 40 },
  { kecamatan: 'Gubeng', kelurahan: 'Airlangga', apk: 89.5, aks: 87.2, ats: 2.3, anakUsiaPaud: 1120, anakSekolah: 977, anakTidakSekolah: 26 },
  { kecamatan: 'Gubeng', kelurahan: 'Kertajaya', apk: 86.8, aks: 83.5, ats: 3.3, anakUsiaPaud: 1450, anakSekolah: 1211, anakTidakSekolah: 48 },
  { kecamatan: 'Gubeng', kelurahan: 'Baratajaya', apk: 85.2, aks: 82.1, ats: 3.1, anakUsiaPaud: 1280, anakSekolah: 1051, anakTidakSekolah: 40 },
  // Kecamatan Sukolilo
  { kecamatan: 'Sukolilo', kelurahan: 'Keputih', apk: 82.5, aks: 79.8, ats: 2.7, anakUsiaPaud: 2150, anakSekolah: 1716, anakTidakSekolah: 58 },
  { kecamatan: 'Sukolilo', kelurahan: 'Gebang Putih', apk: 84.3, aks: 81.2, ats: 3.1, anakUsiaPaud: 1680, anakSekolah: 1364, anakTidakSekolah: 52 },
  { kecamatan: 'Sukolilo', kelurahan: 'Semolowaru', apk: 85.8, aks: 83.1, ats: 2.7, anakUsiaPaud: 1890, anakSekolah: 1571, anakTidakSekolah: 51 },
  { kecamatan: 'Sukolilo', kelurahan: 'Medokan Semampir', apk: 83.2, aks: 80.5, ats: 2.7, anakUsiaPaud: 1520, anakSekolah: 1224, anakTidakSekolah: 41 },
  // Kecamatan Mulyorejo
  { kecamatan: 'Mulyorejo', kelurahan: 'Mulyorejo', apk: 86.1, aks: 83.4, ats: 2.7, anakUsiaPaud: 1380, anakSekolah: 1151, anakTidakSekolah: 37 },
  { kecamatan: 'Mulyorejo', kelurahan: 'Manyar Sabrangan', apk: 84.5, aks: 81.8, ats: 2.7, anakUsiaPaud: 1120, anakSekolah: 916, anakTidakSekolah: 30 },
  { kecamatan: 'Mulyorejo', kelurahan: 'Kejawan Putih Tambak', apk: 83.8, aks: 80.9, ats: 2.9, anakUsiaPaud: 980, anakSekolah: 793, anakTidakSekolah: 28 },
  // Kecamatan Rungkut
  { kecamatan: 'Rungkut', kelurahan: 'Rungkut Kidul', apk: 81.5, aks: 78.2, ats: 3.3, anakUsiaPaud: 1850, anakSekolah: 1447, anakTidakSekolah: 61 },
  { kecamatan: 'Rungkut', kelurahan: 'Kalirungkut', apk: 82.8, aks: 79.5, ats: 3.3, anakUsiaPaud: 1620, anakSekolah: 1288, anakTidakSekolah: 53 },
  { kecamatan: 'Rungkut', kelurahan: 'Kedung Baruk', apk: 83.4, aks: 80.1, ats: 3.3, anakUsiaPaud: 1480, anakSekolah: 1185, anakTidakSekolah: 49 },
  { kecamatan: 'Rungkut', kelurahan: 'Penjaringan Sari', apk: 84.2, aks: 81.3, ats: 2.9, anakUsiaPaud: 1750, anakSekolah: 1423, anakTidakSekolah: 51 },
  // Kecamatan Wonokromo
  { kecamatan: 'Wonokromo', kelurahan: 'Wonokromo', apk: 80.5, aks: 77.2, ats: 3.3, anakUsiaPaud: 1680, anakSekolah: 1297, anakTidakSekolah: 55 },
  { kecamatan: 'Wonokromo', kelurahan: 'Jagir', apk: 81.8, aks: 78.5, ats: 3.3, anakUsiaPaud: 1420, anakSekolah: 1115, anakTidakSekolah: 47 },
  { kecamatan: 'Wonokromo', kelurahan: 'Ngagel', apk: 82.4, aks: 79.1, ats: 3.3, anakUsiaPaud: 1280, anakSekolah: 1013, anakTidakSekolah: 42 },
  { kecamatan: 'Wonokromo', kelurahan: 'Darmo', apk: 85.6, aks: 82.8, ats: 2.8, anakUsiaPaud: 1150, anakSekolah: 952, anakTidakSekolah: 32 },
])

// Trend data (5 tahun terakhir)
const trendData = ref({
  years: [2020, 2021, 2022, 2023, 2024],
  apk: [72.5, 74.8, 78.2, 81.5, 84.3],
  aks: [68.2, 71.5, 75.8, 79.2, 82.1],
  ats: [7.7, 6.2, 4.8, 3.5, 2.2]
})

// Get unique kecamatan list
const kecamatanList = computed(() => [...new Set(dataWilayah.value.map(d => d.kecamatan))].sort())

// Get kelurahan list based on selected kecamatan
const kelurahanList = computed(() => {
  if (!selectedKecamatan.value) {
    return [...new Set(dataWilayah.value.map(d => d.kelurahan))].sort()
  }
  return [...new Set(dataWilayah.value.filter(d => d.kecamatan === selectedKecamatan.value).map(d => d.kelurahan))].sort()
})

// Filtered data based on selection
const filteredData = computed(() => {
  return dataWilayah.value.filter(d => {
    const matchKecamatan = !selectedKecamatan.value || d.kecamatan === selectedKecamatan.value
    const matchKelurahan = !selectedKelurahan.value || d.kelurahan === selectedKelurahan.value
    return matchKecamatan && matchKelurahan
  })
})

// Calculated stats based on filtered data
const stats = computed(() => {
  const data = filteredData.value
  if (data.length === 0) {
    return { apk: 0, aks: 0, ats: 0, totalAnak: 0, anakSekolah: 0, anakTidakSekolah: 0 }
  }
  
  const totalAnak = data.reduce((sum, d) => sum + d.anakUsiaPaud, 0)
  const anakSekolah = data.reduce((sum, d) => sum + d.anakSekolah, 0)
  const anakTidakSekolah = data.reduce((sum, d) => sum + d.anakTidakSekolah, 0)
  
  // Weighted average for percentages
  const apk = data.reduce((sum, d) => sum + (d.apk * d.anakUsiaPaud), 0) / totalAnak
  const aks = data.reduce((sum, d) => sum + (d.aks * d.anakUsiaPaud), 0) / totalAnak
  const ats = (anakTidakSekolah / totalAnak) * 100
  
  return {
    apk: apk.toFixed(1),
    aks: aks.toFixed(1),
    ats: ats.toFixed(1),
    totalAnak,
    anakSekolah,
    anakTidakSekolah
  }
})

const resetFilter = () => {
  selectedKecamatan.value = ''
  selectedKelurahan.value = ''
}

const onKecamatanChange = () => {
  selectedKelurahan.value = ''
}
</script>

<template>
  <DataLayout 
    title="APK / AKS / ATS" 
    subtitle="Angka Partisipasi Kasar, Angka Partisipasi Sekolah, dan Anak Tidak Sekolah PAUD Kota Surabaya"
    icon="📊"
  >
    <div class="space-y-6">
      <!-- Filter -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
        <div class="flex flex-col md:flex-row gap-3 items-end">
          <div class="flex-1">
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Kecamatan</label>
            <select 
              v-model="selectedKecamatan"
              @change="onKecamatanChange"
              class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-rose-500 text-sm"
            >
              <option value="">Semua Kecamatan</option>
              <option v-for="kec in kecamatanList" :key="kec" :value="kec">{{ kec }}</option>
            </select>
          </div>
          <div class="flex-1">
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Kelurahan</label>
            <select 
              v-model="selectedKelurahan"
              class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-rose-500 text-sm"
            >
              <option value="">Semua Kelurahan</option>
              <option v-for="kel in kelurahanList" :key="kel" :value="kel">{{ kel }}</option>
            </select>
          </div>
          <button @click="resetFilter" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 text-sm">
            Reset
          </button>
        </div>
        <div class="mt-2 text-xs text-gray-500">
          Menampilkan data: <strong>{{ selectedKecamatan || 'Semua Kecamatan' }}</strong> - <strong>{{ selectedKelurahan || 'Semua Kelurahan' }}</strong>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid md:grid-cols-3 gap-4">
        <!-- APK -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
          <div class="flex items-start justify-between mb-3">
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
              <span class="text-2xl">📊</span>
            </div>
            <span class="px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-xs font-medium rounded-full">
              +2.8%
            </span>
          </div>
          <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Angka Partisipasi Kasar (APK)</p>
          <p class="text-3xl font-bold text-blue-600">{{ stats.apk }}%</p>
          <p class="text-xs text-gray-500 mt-1">Target: 95%</p>
          <div class="mt-3 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
            <div class="h-full bg-blue-500 rounded-full" :style="{ width: Math.min(stats.apk / 95 * 100, 100) + '%' }"></div>
          </div>
        </div>

        <!-- AKS -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
          <div class="flex items-start justify-between mb-3">
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
              <span class="text-2xl">📈</span>
            </div>
            <span class="px-2 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs font-medium rounded-full">
              +2.9%
            </span>
          </div>
          <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Angka Kesiapan Sekolah (AKS)</p>
          <p class="text-3xl font-bold text-green-600">{{ stats.aks }}%</p>
          <p class="text-xs text-gray-500 mt-1">Target: 90%</p>
          <div class="mt-3 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
            <div class="h-full bg-green-500 rounded-full" :style="{ width: Math.min(stats.aks / 90 * 100, 100) + '%' }"></div>
          </div>
        </div>

        <!-- ATS -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
          <div class="flex items-start justify-between mb-3">
            <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center">
              <span class="text-2xl">🎓</span>
            </div>
            <span class="px-2 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs font-medium rounded-full">
              -1.3%
            </span>
          </div>
          <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Anak Tidak Sekolah (ATS)</p>
          <p class="text-3xl font-bold text-orange-600">{{ stats.ats }}%</p>
          <p class="text-xs text-gray-500 mt-1">Target: 0%</p>
          <div class="mt-3 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
            <div class="h-full bg-orange-500 rounded-full" :style="{ width: Math.min((100 - parseFloat(stats.ats)), 100) + '%' }"></div>
          </div>
        </div>
      </div>

      <!-- Summary Stats -->
      <div class="grid grid-cols-3 gap-3">
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 text-center">
          <p class="text-2xl font-bold text-blue-700 dark:text-blue-400">{{ stats.totalAnak.toLocaleString('id-ID') }}</p>
          <p class="text-xs text-blue-600 dark:text-blue-500">Anak Usia PAUD</p>
        </div>
        <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 text-center">
          <p class="text-2xl font-bold text-green-700 dark:text-green-400">{{ stats.anakSekolah.toLocaleString('id-ID') }}</p>
          <p class="text-xs text-green-600 dark:text-green-500">Anak Bersekolah</p>
        </div>
        <div class="bg-orange-50 dark:bg-orange-900/20 rounded-xl p-4 text-center">
          <p class="text-2xl font-bold text-orange-700 dark:text-orange-400">{{ stats.anakTidakSekolah.toLocaleString('id-ID') }}</p>
          <p class="text-xs text-orange-600 dark:text-orange-500">Anak Tidak Sekolah</p>
        </div>
      </div>

      <!-- Trend 5 Tahun -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
          📈 Tren 5 Tahun Terakhir
        </h3>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-gray-200 dark:border-gray-700">
                <th class="text-left py-2 px-3 text-gray-600 dark:text-gray-400 font-medium">Indikator</th>
                <th v-for="year in trendData.years" :key="year" class="text-center py-2 px-3 text-gray-600 dark:text-gray-400 font-medium">{{ year }}</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border-b border-gray-100 dark:border-gray-700/50">
                <td class="py-3 px-3 font-medium text-gray-900 dark:text-white">APK</td>
                <td v-for="(val, idx) in trendData.apk" :key="idx" class="text-center py-3 px-3">
                  <span class="px-2 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 rounded-lg text-xs font-medium">{{ val }}%</span>
                </td>
              </tr>
              <tr class="border-b border-gray-100 dark:border-gray-700/50">
                <td class="py-3 px-3 font-medium text-gray-900 dark:text-white">AKS</td>
                <td v-for="(val, idx) in trendData.aks" :key="idx" class="text-center py-3 px-3">
                  <span class="px-2 py-1 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 rounded-lg text-xs font-medium">{{ val }}%</span>
                </td>
              </tr>
              <tr>
                <td class="py-3 px-3 font-medium text-gray-900 dark:text-white">ATS</td>
                <td v-for="(val, idx) in trendData.ats" :key="idx" class="text-center py-3 px-3">
                  <span class="px-2 py-1 bg-orange-50 dark:bg-orange-900/20 text-orange-700 dark:text-orange-400 rounded-lg text-xs font-medium">{{ val }}%</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Data per Wilayah -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
          📋 Data per Kecamatan / Kelurahan
        </h3>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="bg-gray-50 dark:bg-gray-700/50">
                <th class="text-left py-3 px-3 text-gray-600 dark:text-gray-400 font-medium rounded-l-lg">Kecamatan</th>
                <th class="text-left py-3 px-3 text-gray-600 dark:text-gray-400 font-medium">Kelurahan</th>
                <th class="text-center py-3 px-3 text-gray-600 dark:text-gray-400 font-medium">Anak Usia PAUD</th>
                <th class="text-center py-3 px-3 text-gray-600 dark:text-gray-400 font-medium">Bersekolah</th>
                <th class="text-center py-3 px-3 text-gray-600 dark:text-gray-400 font-medium">Tidak Sekolah</th>
                <th class="text-center py-3 px-3 text-gray-600 dark:text-gray-400 font-medium">APK</th>
                <th class="text-center py-3 px-3 text-gray-600 dark:text-gray-400 font-medium">AKS</th>
                <th class="text-center py-3 px-3 text-gray-600 dark:text-gray-400 font-medium rounded-r-lg">ATS</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(row, index) in filteredData" :key="index" class="border-b border-gray-100 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                <td class="py-3 px-3 font-medium text-gray-900 dark:text-white">{{ row.kecamatan }}</td>
                <td class="py-3 px-3 text-gray-700 dark:text-gray-300">{{ row.kelurahan }}</td>
                <td class="text-center py-3 px-3 text-gray-700 dark:text-gray-300">{{ row.anakUsiaPaud.toLocaleString('id-ID') }}</td>
                <td class="text-center py-3 px-3 text-green-600 font-medium">{{ row.anakSekolah.toLocaleString('id-ID') }}</td>
                <td class="text-center py-3 px-3 text-orange-600 font-medium">{{ row.anakTidakSekolah }}</td>
                <td class="text-center py-3 px-3">
                  <span class="px-2 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 rounded text-xs font-medium">{{ row.apk }}%</span>
                </td>
                <td class="text-center py-3 px-3">
                  <span class="px-2 py-1 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 rounded text-xs font-medium">{{ row.aks }}%</span>
                </td>
                <td class="text-center py-3 px-3">
                  <span class="px-2 py-1 bg-orange-50 dark:bg-orange-900/20 text-orange-700 dark:text-orange-400 rounded text-xs font-medium">{{ row.ats }}%</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-3 text-xs text-gray-500">
          Menampilkan {{ filteredData.length }} dari {{ dataWilayah.length }} data wilayah
        </div>
      </div>

      <!-- Keterangan -->
      <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
        <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-3">ℹ️ Keterangan</h3>
        <div class="grid md:grid-cols-3 gap-4 text-xs text-gray-600 dark:text-gray-400">
          <div>
            <strong class="text-blue-600">APK (Angka Partisipasi Kasar):</strong>
            <p>Rasio jumlah siswa PAUD tanpa memandang usia terhadap jumlah penduduk usia 3-6 tahun.</p>
          </div>
          <div>
            <strong class="text-green-600">AKS (Angka Kesiapan Sekolah):</strong>
            <p>Indikator kesiapan anak usia dini untuk memasuki jenjang pendidikan dasar (SD/MI).</p>
          </div>
          <div>
            <strong class="text-orange-600">ATS (Anak Tidak Sekolah):</strong>
            <p>Persentase anak usia 3-6 tahun yang belum mengakses layanan pendidikan PAUD.</p>
          </div>
        </div>
      </div>
    </div>
  </DataLayout>
</template>
