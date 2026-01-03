<script setup>
import { ref } from 'vue'
import DataLayout from '@/components/layout/DataLayout.vue'

// Data Profiling
const profilingStats = ref({
  totalLembaga: 1247,
  sudahProfiling: 1089,
  belumProfiling: 158,
  persentase: 87.3
})

const dimensiProfil = ref([
  { nama: 'Identitas Lembaga', icon: '🏫', persentase: 98, deskripsi: 'Data nama, NPSN, alamat, dan informasi dasar lembaga' },
  { nama: 'Peserta Didik', icon: '👶', persentase: 95, deskripsi: 'Data jumlah siswa, usia, dan informasi peserta didik' },
  { nama: 'Pendidik & Tenaga Kependidikan', icon: '👨‍🏫', persentase: 92, deskripsi: 'Data guru, kualifikasi, dan tenaga kependidikan' },
  { nama: 'Sarana Prasarana', icon: '🏠', persentase: 88, deskripsi: 'Data gedung, ruang kelas, APE, dan fasilitas belajar' },
  { nama: 'Kurikulum', icon: '📚', persentase: 85, deskripsi: 'Data implementasi kurikulum dan program pembelajaran' },
  { nama: 'Keuangan', icon: '💰', persentase: 82, deskripsi: 'Data pembiayaan, sumber dana, dan pengelolaan keuangan' },
  { nama: 'Akreditasi', icon: '🏆', persentase: 78, deskripsi: 'Data status dan peringkat akreditasi lembaga' },
  { nama: 'Kemitraan', icon: '🤝', persentase: 75, deskripsi: 'Data kerja sama dengan pihak terkait dan mitra' }
])

const getBarColor = (nilai) => {
  if (nilai >= 90) return 'bg-green-500'
  if (nilai >= 80) return 'bg-blue-500'
  if (nilai >= 70) return 'bg-yellow-500'
  return 'bg-red-500'
}
</script>

<template>
  <DataLayout 
    title="Profiling PAUD" 
    subtitle="Data profil lengkap satuan Pendidikan Anak Usia Dini di Kota Surabaya"
    icon="👤"
  >
    <div class="space-y-6">
      <!-- Stats -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ profilingStats.totalLembaga.toLocaleString('id-ID') }}</div>
          <div class="text-xs text-gray-500">Total Lembaga</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-green-600">{{ profilingStats.sudahProfiling.toLocaleString('id-ID') }}</div>
          <div class="text-xs text-gray-500">Sudah Profiling</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-orange-600">{{ profilingStats.belumProfiling }}</div>
          <div class="text-xs text-gray-500">Belum Profiling</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-blue-600">{{ profilingStats.persentase }}%</div>
          <div class="text-xs text-gray-500">Capaian</div>
        </div>
      </div>

      <!-- About Profiling -->
      <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl p-5 border border-purple-100 dark:border-purple-800">
        <h3 class="font-bold text-purple-900 dark:text-purple-300 mb-2">📊 Apa itu Profiling PAUD?</h3>
        <p class="text-sm text-purple-700 dark:text-purple-400">
          Profiling PAUD adalah proses pengumpulan dan penyajian data lengkap tentang satuan pendidikan yang mencakup 
          8 dimensi utama. Data ini digunakan untuk pemetaan kondisi lembaga PAUD, perencanaan program, 
          dan pengambilan keputusan berbasis data.
        </p>
      </div>

      <!-- Dimensi Profil -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">🎯 8 Dimensi Profil Lulusan PAUD</h3>
        <div class="grid md:grid-cols-2 gap-4">
          <div 
            v-for="dimensi in dimensiProfil" 
            :key="dimensi.nama"
            class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl"
          >
            <div class="flex items-center gap-3 mb-2">
              <div class="text-2xl">{{ dimensi.icon }}</div>
              <div class="flex-1">
                <h4 class="font-semibold text-gray-900 dark:text-white text-sm">{{ dimensi.nama }}</h4>
                <p class="text-xs text-gray-500">{{ dimensi.deskripsi }}</p>
              </div>
              <div class="text-lg font-bold" :class="dimensi.persentase >= 90 ? 'text-green-600' : dimensi.persentase >= 80 ? 'text-blue-600' : 'text-yellow-600'">
                {{ dimensi.persentase }}%
              </div>
            </div>
            <div class="h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
              <div :class="['h-full rounded-full transition-all', getBarColor(dimensi.persentase)]" :style="{ width: dimensi.persentase + '%' }"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Manfaat -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">✨ Manfaat Profiling PAUD</h3>
        <div class="grid md:grid-cols-3 gap-4">
          <div class="text-center p-4">
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-3 text-xl">📈</div>
            <h4 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">Pemetaan Kondisi</h4>
            <p class="text-xs text-gray-500">Mengetahui kondisi riil lembaga PAUD</p>
          </div>
          <div class="text-center p-4">
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-3 text-xl">🎯</div>
            <h4 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">Perencanaan Program</h4>
            <p class="text-xs text-gray-500">Dasar penyusunan program intervensi</p>
          </div>
          <div class="text-center p-4">
            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-3 text-xl">📊</div>
            <h4 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">Keputusan Berbasis Data</h4>
            <p class="text-xs text-gray-500">Pengambilan kebijakan yang tepat sasaran</p>
          </div>
        </div>
      </div>
    </div>
  </DataLayout>
</template>
