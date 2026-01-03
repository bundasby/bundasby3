<script setup>
import { ref } from 'vue'
import DataLayout from '@/components/layout/DataLayout.vue'

// Data Digitalisasi
const digitalisasiStats = ref({
  totalLembaga: 1247,
  sudahDigital: 876,
  dalamProses: 245,
  belumDigital: 126
})

const programDigitalisasi = ref([
  {
    nama: 'Bantuan IFP 2025',
    icon: '📺',
    deskripsi: 'Interactive Flat Panel untuk pembelajaran digital interaktif',
    status: 'Aktif',
    penerima: 150,
    color: 'blue'
  },
  {
    nama: 'Akun Belajar.id',
    icon: '🔑',
    deskripsi: 'Aktivasi akun belajar.id untuk guru dan kepala sekolah PAUD',
    status: 'Aktif',
    penerima: 2500,
    color: 'green'
  },
  {
    nama: 'Ruang GTK (PMM)',
    icon: '📱',
    deskripsi: 'Platform Merdeka Mengajar untuk pengembangan kompetensi guru',
    status: 'Aktif',
    penerima: 1800,
    color: 'purple'
  },
  {
    nama: 'Rapor Pendidikan PAUD',
    icon: '📊',
    deskripsi: 'Dashboard capaian mutu pendidikan satuan PAUD',
    status: 'Aktif',
    penerima: 1089,
    color: 'orange'
  }
])

const layananDigital = ref([
  { nama: 'Dapodik PAUD', url: 'https://dapo.paud-dikmas.kemdikbud.go.id', icon: '🗄️' },
  { nama: 'Rapor Pendidikan', url: 'https://raporpendidikan.kemdikbud.go.id', icon: '📈' },
  { nama: 'Platform Merdeka Mengajar', url: 'https://guru.kemdikbud.go.id', icon: '📚' },
  { nama: 'SIBUNDA Surabaya', url: 'https://sibunda.surabaya.go.id', icon: '🌐' }
])
</script>

<template>
  <DataLayout 
    title="Digitalisasi PAUD" 
    subtitle="Program transformasi digital pendidikan anak usia dini di Kota Surabaya"
    icon="💻"
  >
    <div class="space-y-6">
      <!-- Stats -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ digitalisasiStats.totalLembaga.toLocaleString('id-ID') }}</div>
          <div class="text-xs text-gray-500">Total Lembaga</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-green-600">{{ digitalisasiStats.sudahDigital }}</div>
          <div class="text-xs text-gray-500">Sudah Digital</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-yellow-600">{{ digitalisasiStats.dalamProses }}</div>
          <div class="text-xs text-gray-500">Dalam Proses</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-red-600">{{ digitalisasiStats.belumDigital }}</div>
          <div class="text-xs text-gray-500">Belum Digital</div>
        </div>
      </div>

      <!-- About Digitalisasi -->
      <div class="bg-gradient-to-r from-cyan-50 to-blue-50 dark:from-cyan-900/20 dark:to-blue-900/20 rounded-xl p-5 border border-cyan-100 dark:border-cyan-800">
        <h3 class="font-bold text-cyan-900 dark:text-cyan-300 mb-2">🚀 Program Digitalisasi PAUD 2025</h3>
        <p class="text-sm text-cyan-700 dark:text-cyan-400">
          Berdasarkan Inpres No. 7 Tahun 2025, Kemendikdasmen menyelenggarakan program Bantuan Digitalisasi PAUD 
          untuk mempercepat transformasi digital di satuan pendidikan. Program ini meliputi pemberian 
          Interactive Flat Panel (IFP) dan konten pembelajaran digital.
        </p>
      </div>

      <!-- Program Digitalisasi -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">📱 Program Digitalisasi Aktif</h3>
        <div class="grid md:grid-cols-2 gap-4">
          <div 
            v-for="program in programDigitalisasi" 
            :key="program.nama"
            class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:shadow-md transition-all"
          >
            <div class="flex items-start gap-3">
              <div class="text-3xl">{{ program.icon }}</div>
              <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                  <h4 class="font-semibold text-gray-900 dark:text-white text-sm">{{ program.nama }}</h4>
                  <span class="px-2 py-0.5 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs rounded-full">
                    {{ program.status }}
                  </span>
                </div>
                <p class="text-xs text-gray-500 mb-2">{{ program.deskripsi }}</p>
                <div class="text-xs text-gray-600 dark:text-gray-400">
                  👥 <strong>{{ program.penerima.toLocaleString('id-ID') }}</strong> penerima manfaat
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Layanan Digital -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">🌐 Layanan Digital PAUD</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
          <a 
            v-for="layanan in layananDigital" 
            :key="layanan.nama"
            :href="layanan.url"
            target="_blank"
            class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl text-center hover:shadow-md hover:bg-gray-100 dark:hover:bg-gray-600/50 transition-all group"
          >
            <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">{{ layanan.icon }}</div>
            <h4 class="font-semibold text-gray-900 dark:text-white text-xs">{{ layanan.nama }}</h4>
          </a>
        </div>
      </div>

      <!-- Aspek Digitalisasi -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">⚙️ Aspek Digitalisasi PAUD</h3>
        <div class="grid md:grid-cols-3 gap-4">
          <div class="p-4 border border-gray-100 dark:border-gray-700 rounded-xl">
            <div class="text-2xl mb-2">📋</div>
            <h4 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">Administrasi Digital</h4>
            <p class="text-xs text-gray-500">Penjadwalan online, pelaporan, penyimpanan file digital</p>
          </div>
          <div class="p-4 border border-gray-100 dark:border-gray-700 rounded-xl">
            <div class="text-2xl mb-2">🎓</div>
            <h4 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">Pembelajaran Digital</h4>
            <p class="text-xs text-gray-500">Konten interaktif, media pembelajaran berbasis teknologi</p>
          </div>
          <div class="p-4 border border-gray-100 dark:border-gray-700 rounded-xl">
            <div class="text-2xl mb-2">📱</div>
            <h4 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">Komunikasi Digital</h4>
            <p class="text-xs text-gray-500">Komunikasi orang tua, rapat virtual, pengumuman online</p>
          </div>
        </div>
      </div>
    </div>
  </DataLayout>
</template>
