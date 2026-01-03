<script setup>
import { ref } from 'vue'
import DataLayout from '@/components/layout/DataLayout.vue'

const raporData = ref({
  tahun: 2024,
  totalLembaga: 1247,
  lembagaDinilai: 1089,
  rataRataCapaian: 76.5
})

const indikatorRapor = ref([
  { id: 1, kategori: 'Input', indikator: 'Kualifikasi dan Kompetensi Guru', nilai: 78.5, kategoriNilai: 'Baik' },
  { id: 2, kategori: 'Input', indikator: 'Rasio Guru dan Peserta Didik', nilai: 82.3, kategoriNilai: 'Baik' },
  { id: 3, kategori: 'Proses', indikator: 'Kualitas Pembelajaran', nilai: 74.8, kategoriNilai: 'Cukup' },
  { id: 4, kategori: 'Proses', indikator: 'Iklim Keamanan dan Inklusivitas', nilai: 85.2, kategoriNilai: 'Baik' },
  { id: 5, kategori: 'Output', indikator: 'Capaian Perkembangan Anak', nilai: 71.6, kategoriNilai: 'Cukup' },
  { id: 6, kategori: 'Output', indikator: 'Kesiapan Belajar', nilai: 76.9, kategoriNilai: 'Baik' }
])

const distribusiKategori = ref([
  { kategori: 'Sangat Baik', jumlah: 156, persentase: 14.3, color: 'bg-green-500' },
  { kategori: 'Baik', jumlah: 489, persentase: 44.9, color: 'bg-blue-500' },
  { kategori: 'Cukup', jumlah: 312, persentase: 28.6, color: 'bg-yellow-500' },
  { kategori: 'Kurang', jumlah: 132, persentase: 12.1, color: 'bg-red-500' }
])

const getKategoriColor = (kategori) => {
  switch(kategori) {
    case 'Sangat Baik': return 'text-green-600 bg-green-100 dark:bg-green-900/30'
    case 'Baik': return 'text-blue-600 bg-blue-100 dark:bg-blue-900/30'
    case 'Cukup': return 'text-yellow-600 bg-yellow-100 dark:bg-yellow-900/30'
    case 'Kurang': return 'text-red-600 bg-red-100 dark:bg-red-900/30'
    default: return 'text-gray-600 bg-gray-100'
  }
}

const getBarColor = (nilai) => {
  if (nilai >= 80) return 'bg-green-500'
  if (nilai >= 70) return 'bg-blue-500'
  if (nilai >= 60) return 'bg-yellow-500'
  return 'bg-red-500'
}
</script>

<template>
  <DataLayout 
    title="Rapor Pendidikan PAUD" 
    subtitle="Hasil penilaian kualitas pendidikan anak usia dini di Kota Surabaya"
    icon="📋"
  >
    <div class="space-y-6">
      <!-- Overview Stats -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-primary-600 mb-1">{{ raporData.totalLembaga.toLocaleString('id-ID') }}</div>
          <div class="text-xs text-gray-500">Total Lembaga</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-green-600 mb-1">{{ raporData.lembagaDinilai.toLocaleString('id-ID') }}</div>
          <div class="text-xs text-gray-500">Lembaga Dinilai</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-blue-600 mb-1">{{ raporData.rataRataCapaian }}%</div>
          <div class="text-xs text-gray-500">Rata-rata Capaian</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-yellow-600 mb-1">{{ raporData.tahun }}</div>
          <div class="text-xs text-gray-500">Tahun Penilaian</div>
        </div>
      </div>

      <!-- Indikator Rapor -->
      <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">📊 Indikator Rapor Pendidikan</h3>
        <div class="space-y-3">
          <div 
            v-for="item in indikatorRapor" 
            :key="item.id"
            class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg"
          >
            <div class="flex items-center justify-between mb-2">
              <div>
                <span class="text-xs text-primary-600 font-medium">{{ item.kategori }}</span>
                <h4 class="font-medium text-gray-900 dark:text-white text-sm">{{ item.indikator }}</h4>
              </div>
              <div class="text-right">
                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ item.nilai }}</div>
                <span :class="['text-xs px-2 py-0.5 rounded', getKategoriColor(item.kategoriNilai)]">
                  {{ item.kategoriNilai }}
                </span>
              </div>
            </div>
            <div class="h-1.5 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
              <div 
                :class="['h-full rounded-full', getBarColor(item.nilai)]"
                :style="{ width: item.nilai + '%' }"
              ></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Distribusi Kategori -->
      <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">🏆 Distribusi Kategori Lembaga</h3>
        <div class="space-y-2">
          <div 
            v-for="item in distribusiKategori" 
            :key="item.kategori"
            class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg"
          >
            <div class="flex items-center gap-2">
              <div :class="['w-3 h-3 rounded-full', item.color]"></div>
              <span class="font-medium text-gray-900 dark:text-white text-sm">{{ item.kategori }}</span>
            </div>
            <div class="text-right">
              <span class="font-bold text-gray-900 dark:text-white text-sm">{{ item.jumlah }}</span>
              <span class="text-xs text-gray-500 ml-1">({{ item.persentase }}%)</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DataLayout>
</template>
