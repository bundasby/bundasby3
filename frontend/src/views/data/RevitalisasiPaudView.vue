<script setup>
import { ref } from 'vue'
import DataLayout from '@/components/layout/DataLayout.vue'

// Data Revitalisasi
const revitalisasiStats = ref({
  totalTarget2025: 16177,
  rehabilitasi: 16105,
  usb: 72,
  digitalisasi2026: 'Terintegrasi'
})

const prinsipPelaksanaan = ref([
  { nama: 'Efektif', icon: '🎯', deskripsi: 'Hasil sesuai dengan kebutuhan nyata satuan pendidikan' },
  { nama: 'Efisien', icon: '⚡', deskripsi: 'Pemanfaatan sumber daya secara optimal untuk hasil maksimal' },
  { nama: 'Akuntabel', icon: '📋', deskripsi: 'Proses perencanaan, pelaksanaan, dan pelaporan yang transparan' },
  { nama: 'Partisipatif', icon: '🤝', deskripsi: 'Melibatkan sekolah, Pemda, dan masyarakat secara aktif' },
  { nama: 'Kepentingan Terbaik Peserta Didik', icon: '👶', deskripsi: 'Semua keputusan diarahkan untuk pemenuhan kebutuhan siswa' }
])

const lingkupProgram = ref([
  { 
    nama: 'Rehabilitasi & Pembangunan', 
    icon: '🏗️', 
    deskripsi: 'Perbaikan ruang eksisting dan pembangunan ruang kelas baru',
    target: 16105
  },
  { 
    nama: 'Unit Sekolah Baru (USB)', 
    icon: '🏫', 
    deskripsi: 'Pembangunan unit sekolah baru di daerah yang membutuhkan',
    target: 72
  },
  { 
    nama: 'Digitalisasi Pembelajaran', 
    icon: '💻', 
    deskripsi: 'Integrasi dengan program digitalisasi mulai TA 2026',
    target: 'TA 2026'
  }
])

const tujuanUtama = ref([
  'Memenuhi kebutuhan sarana dan prasarana pendidikan berkualitas',
  'Rehabilitasi, pembangunan, dan penyediaan infrastruktur pendidikan',
  'Transformasi satuan pendidikan melalui modernisasi dan integrasi digital',
  'Menjamin kelangsungan dan efektivitas pembelajaran di seluruh Indonesia'
])
</script>

<template>
  <DataLayout 
    title="Revitalisasi Satuan Pendidikan" 
    subtitle="Program strategis pemenuhan sarana prasarana pendidikan berkualitas"
    icon="🏗️"
  >
    <div class="space-y-6">
      <!-- Legal Basis -->
      <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl p-5 text-white">
        <div class="flex items-center gap-3 mb-2">
          <span class="text-2xl">📜</span>
          <div>
            <p class="text-sm text-blue-100">Dasar Hukum</p>
            <h3 class="font-bold text-lg">Instruksi Presiden Nomor 7 Tahun 2025</h3>
          </div>
        </div>
        <p class="text-sm text-blue-100 mt-2">
          Program Revitalisasi Satuan Pendidikan merupakan bagian dari <strong>Program Hasil Terbaik Cepat (PHTC)</strong> 
          Kemendikdasmen untuk percepatan pemenuhan infrastruktur pendidikan.
        </p>
      </div>

      <!-- Stats -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-blue-600">{{ revitalisasiStats.totalTarget2025.toLocaleString('id-ID') }}</div>
          <div class="text-xs text-gray-500">Target 2025</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-green-600">{{ revitalisasiStats.rehabilitasi.toLocaleString('id-ID') }}</div>
          <div class="text-xs text-gray-500">Rehabilitasi & Pembangunan</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-purple-600">{{ revitalisasiStats.usb }}</div>
          <div class="text-xs text-gray-500">Unit Sekolah Baru</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-cyan-600">{{ revitalisasiStats.digitalisasi2026 }}</div>
          <div class="text-xs text-gray-500">Digitalisasi TA 2026</div>
        </div>
      </div>

      <!-- About Revitalisasi -->
      <div class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-xl p-5 border border-emerald-100 dark:border-emerald-800">
        <h3 class="font-bold text-emerald-900 dark:text-emerald-300 mb-2">🏗️ Apa itu Revitalisasi Satuan Pendidikan?</h3>
        <p class="text-sm text-emerald-700 dark:text-emerald-400">
          Revitalisasi Satuan Pendidikan adalah program strategis untuk memenuhi kebutuhan sarana dan prasarana 
          pendidikan berkualitas melalui rehabilitasi, pembangunan, dan penyediaan infrastruktur. Program ini 
          bertujuan menjamin kelangsungan dan efektivitas pembelajaran di seluruh Indonesia.
        </p>
      </div>

      <!-- Tujuan -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">🎯 Tujuan Utama Program</h3>
        <div class="space-y-3">
          <div v-for="(tujuan, index) in tujuanUtama" :key="index" class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
            <div class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">
              {{ index + 1 }}
            </div>
            <p class="text-sm text-gray-700 dark:text-gray-300">{{ tujuan }}</p>
          </div>
        </div>
      </div>

      <!-- Lingkup Program -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">📦 Lingkup Program (Jenis Bantuan)</h3>
        <div class="grid md:grid-cols-3 gap-4">
          <div v-for="program in lingkupProgram" :key="program.nama" class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl text-center">
            <div class="text-3xl mb-2">{{ program.icon }}</div>
            <h4 class="font-bold text-gray-900 dark:text-white text-sm mb-1">{{ program.nama }}</h4>
            <p class="text-xs text-gray-500 mb-2">{{ program.deskripsi }}</p>
            <div class="text-lg font-bold text-blue-600">
              {{ typeof program.target === 'number' ? program.target.toLocaleString('id-ID') : program.target }}
            </div>
            <p class="text-xs text-gray-400">{{ typeof program.target === 'number' ? 'Satuan Pendidikan' : '' }}</p>
          </div>
        </div>
      </div>

      <!-- Prinsip Pelaksanaan -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">⚖️ Prinsip Pelaksanaan</h3>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-for="prinsip in prinsipPelaksanaan" :key="prinsip.nama" class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
            <span class="text-2xl">{{ prinsip.icon }}</span>
            <div>
              <h4 class="font-semibold text-gray-900 dark:text-white text-sm">{{ prinsip.nama }}</h4>
              <p class="text-xs text-gray-500">{{ prinsip.deskripsi }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Sistem Manajemen -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">🖥️ Sistem Manajemen</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
          Program ini menggunakan aplikasi manajemen khusus untuk mempermudah sekolah dalam melakukan:
        </p>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
          <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-center">
            <span class="text-xl">📝</span>
            <p class="text-xs font-medium text-blue-700 dark:text-blue-400 mt-1">Perencanaan</p>
          </div>
          <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg text-center">
            <span class="text-xl">🔨</span>
            <p class="text-xs font-medium text-green-700 dark:text-green-400 mt-1">Pelaksanaan</p>
          </div>
          <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg text-center">
            <span class="text-xl">✅</span>
            <p class="text-xs font-medium text-yellow-700 dark:text-yellow-400 mt-1">Verifikasi</p>
          </div>
          <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg text-center">
            <span class="text-xl">🔍</span>
            <p class="text-xs font-medium text-purple-700 dark:text-purple-400 mt-1">Validasi</p>
          </div>
          <div class="p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg text-center">
            <span class="text-xl">📊</span>
            <p class="text-xs font-medium text-orange-700 dark:text-orange-400 mt-1">Pelaporan</p>
          </div>
        </div>
      </div>

      <!-- Link -->
      <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-5 border border-blue-100 dark:border-blue-800">
        <h3 class="font-bold text-blue-900 dark:text-blue-300 mb-2">🔗 Akses Aplikasi</h3>
        <p class="text-sm text-blue-700 dark:text-blue-400 mb-3">
          Untuk mengakses Aplikasi Revitalisasi Satuan Pendidikan, kunjungi:
        </p>
        <a 
          href="https://revit.kemendikdasmen.go.id" 
          target="_blank"
          class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
          </svg>
          revit.kemendikdasmen.go.id
        </a>
      </div>
    </div>
  </DataLayout>
</template>
