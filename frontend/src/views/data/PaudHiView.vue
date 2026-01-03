<script setup>
import { ref } from 'vue'
import DataLayout from '@/components/layout/DataLayout.vue'

// Data PAUD HI
const paudHiStats = ref({
  totalPaudHI: 854,
  persentase: 68.5,
  targetNasional: 75,
  kecamatanTercakup: 31
})

const layananPaudHI = ref([
  {
    nama: 'Layanan Pendidikan',
    icon: '📚',
    deskripsi: 'Pemberian rangsangan pendidikan melalui bermain yang berorientasi pada perkembangan anak',
    kegiatan: ['Pembelajaran berbasis bermain', 'Stimulasi 6 aspek perkembangan', 'Kurikulum Merdeka PAUD']
  },
  {
    nama: 'Layanan Kesehatan & Gizi',
    icon: '🏥',
    deskripsi: 'Pemantauan kesehatan dan pemenuhan gizi anak secara berkala',
    kegiatan: ['Penimbangan rutin', 'Imunisasi lengkap', 'PMT (Pemberian Makanan Tambahan)', 'Deteksi dini tumbuh kembang']
  },
  {
    nama: 'Layanan Pengasuhan',
    icon: '👨‍👩‍👧',
    deskripsi: 'Kerja sama dengan orang tua dalam mendukung tumbuh kembang anak',
    kegiatan: ['Program parenting', 'Konsultasi perkembangan anak', 'Edukasi pola asuh positif']
  },
  {
    nama: 'Layanan Perlindungan',
    icon: '🛡️',
    deskripsi: 'Memastikan lingkungan aman dan bebas dari kekerasan',
    kegiatan: ['Pencegahan kekerasan', 'Perlindungan hak anak', 'Lingkungan ramah anak']
  }
])

const tujuanPaudHI = ref([
  { tujuan: 'Anak Indonesia yang sehat, cerdas, ceria, dan berakhlak mulia', icon: '🌟' },
  { tujuan: 'Terpenuhinya kebutuhan esensial anak secara utuh', icon: '💝' },
  { tujuan: 'Terlindunginya anak dari kekerasan dan penelantaran', icon: '🛡️' },
  { tujuan: 'Layanan anak usia dini terintegrasi antar lembaga', icon: '🤝' }
])

const mitraKerjaSama = ref([
  { nama: 'Posyandu', icon: '🏥' },
  { nama: 'Puskesmas', icon: '⚕️' },
  { nama: 'PKK', icon: '👩‍👧' },
  { nama: 'Desa/Kelurahan', icon: '🏘️' },
  { nama: 'Kader Kesehatan', icon: '💚' },
  { nama: 'BKKBN', icon: '👨‍👩‍👧‍👦' }
])
</script>

<template>
  <DataLayout 
    title="PAUD Holistik Integratif" 
    subtitle="Layanan pendidikan anak usia dini terpadu di Kota Surabaya"
    icon="⭐"
  >
    <div class="space-y-6">
      <!-- Stats -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-lime-600">{{ paudHiStats.totalPaudHI }}</div>
          <div class="text-xs text-gray-500">PAUD HI Aktif</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-green-600">{{ paudHiStats.persentase }}%</div>
          <div class="text-xs text-gray-500">Capaian</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-blue-600">{{ paudHiStats.targetNasional }}%</div>
          <div class="text-xs text-gray-500">Target Nasional</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-purple-600">{{ paudHiStats.kecamatanTercakup }}</div>
          <div class="text-xs text-gray-500">Kecamatan Tercakup</div>
        </div>
      </div>

      <!-- About PAUD HI -->
      <div class="bg-gradient-to-r from-lime-50 to-green-50 dark:from-lime-900/20 dark:to-green-900/20 rounded-xl p-5 border border-lime-100 dark:border-lime-800">
        <h3 class="font-bold text-lime-900 dark:text-lime-300 mb-2">⭐ Apa itu PAUD Holistik Integratif?</h3>
        <p class="text-sm text-lime-700 dark:text-lime-400">
          PAUD Holistik Integratif (HI) adalah penanganan anak usia dini secara utuh yang mencakup layanan 
          pendidikan, kesehatan, gizi, pengasuhan, dan perlindungan. Program ini diatur dalam 
          <strong>Perpres No. 60 Tahun 2013</strong> untuk mengoptimalkan tumbuh kembang anak.
        </p>
      </div>

      <!-- Tujuan -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">🎯 Tujuan PAUD Holistik Integratif</h3>
        <div class="grid md:grid-cols-2 gap-3">
          <div v-for="item in tujuanPaudHI" :key="item.tujuan" class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
            <span class="text-2xl">{{ item.icon }}</span>
            <p class="text-sm text-gray-700 dark:text-gray-300">{{ item.tujuan }}</p>
          </div>
        </div>
      </div>

      <!-- 4 Layanan -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">🌈 4 Pilar Layanan PAUD HI</h3>
        <div class="grid md:grid-cols-2 gap-4">
          <div v-for="layanan in layananPaudHI" :key="layanan.nama" class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
            <div class="flex items-center gap-3 mb-3">
              <div class="text-3xl">{{ layanan.icon }}</div>
              <h4 class="font-bold text-gray-900 dark:text-white text-sm">{{ layanan.nama }}</h4>
            </div>
            <p class="text-xs text-gray-500 mb-3">{{ layanan.deskripsi }}</p>
            <div class="space-y-1">
              <div v-for="(kegiatan, idx) in layanan.kegiatan" :key="idx" class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                <span class="w-1.5 h-1.5 bg-lime-500 rounded-full"></span>
                {{ kegiatan }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Mitra Kerja Sama -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">🤝 Mitra Kerja Sama Lintas Sektor</h3>
        <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
          <div v-for="mitra in mitraKerjaSama" :key="mitra.nama" class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl text-center">
            <div class="text-2xl mb-1">{{ mitra.icon }}</div>
            <p class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ mitra.nama }}</p>
          </div>
        </div>
      </div>

      <!-- Info -->
      <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-5 border border-blue-100 dark:border-blue-800">
        <h3 class="font-bold text-blue-900 dark:text-blue-300 mb-2">ℹ️ Dasar Hukum</h3>
        <p class="text-sm text-blue-700 dark:text-blue-400">
          <strong>Peraturan Presiden No. 60 Tahun 2013</strong> tentang Pengembangan Anak Usia Dini Holistik Integratif 
          menjadi landasan utama penyelenggaraan PAUD HI di Indonesia, termasuk di Kota Surabaya.
        </p>
      </div>
    </div>
  </DataLayout>
</template>
