<script setup>
import { ref } from 'vue'
import DataLayout from '@/components/layout/DataLayout.vue'

// Data Sulingjar
const sulingjarStats = ref({
  tahun: 2024,
  totalPeserta: 2178,
  sudahMengisi: 1956,
  persentase: 89.8
})

const jadwal2024 = ref([
  { kegiatan: 'Cut Off Data Peserta', tanggal: '22 September 2024', status: 'selesai' },
  { kegiatan: 'Cetak Kartu Login', tanggal: '29 September 2024', status: 'selesai' },
  { kegiatan: 'Pelaksanaan Survei', tanggal: '30 Sep - 27 Okt 2024', status: 'selesai' },
  { kegiatan: 'Rilis Hasil', tanggal: 'November 2024', status: 'selesai' }
])

const aspekSulingjar = ref([
  { nama: 'Iklim Keamanan', icon: '🛡️', deskripsi: 'Kondisi keamanan fisik dan psikologis di satuan PAUD' },
  { nama: 'Iklim Inklusivitas', icon: '🤝', deskripsi: 'Penerimaan dan penghargaan terhadap keberagaman' },
  { nama: 'Kualitas Pembelajaran', icon: '📚', deskripsi: 'Praktik pembelajaran yang berpusat pada anak' },
  { nama: 'Kompetensi Guru', icon: '👨‍🏫', deskripsi: 'Kualifikasi dan pengembangan kompetensi pendidik' },
  { nama: 'Kepemimpinan Satuan', icon: '🎯', deskripsi: 'Kepemimpinan kepala sekolah yang efektif' },
  { nama: 'Partisipasi Orang Tua', icon: '👨‍👩‍👧', deskripsi: 'Keterlibatan orang tua dalam pendidikan anak' }
])

const langkahPengisian = ref([
  'Akses laman surveilingkunganbelajar.kemdikbud.go.id',
  'Masukkan token dari Kartu Peserta',
  'Isi NIK dan tanggal lahir',
  'Login dan periksa profil',
  'Jawab 131 pertanyaan survei',
  'Klik "Selesai" dan konfirmasi'
])
</script>

<template>
  <DataLayout 
    title="Survei Lingkungan Belajar (Sulingjar)" 
    subtitle="Pemetaan kualitas lingkungan belajar satuan PAUD di Kota Surabaya"
    icon="📋"
  >
    <div class="space-y-6">
      <!-- Stats -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-indigo-600">{{ sulingjarStats.tahun }}</div>
          <div class="text-xs text-gray-500">Tahun Survei</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ sulingjarStats.totalPeserta.toLocaleString('id-ID') }}</div>
          <div class="text-xs text-gray-500">Total Peserta</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-green-600">{{ sulingjarStats.sudahMengisi.toLocaleString('id-ID') }}</div>
          <div class="text-xs text-gray-500">Sudah Mengisi</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-blue-600">{{ sulingjarStats.persentase }}%</div>
          <div class="text-xs text-gray-500">Partisipasi</div>
        </div>
      </div>

      <!-- About Sulingjar -->
      <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-xl p-5 border border-indigo-100 dark:border-indigo-800">
        <h3 class="font-bold text-indigo-900 dark:text-indigo-300 mb-2">📊 Apa itu Sulingjar PAUD?</h3>
        <p class="text-sm text-indigo-700 dark:text-indigo-400">
          Survei Lingkungan Belajar (Sulingjar) PAUD adalah program Kemendikbudristek untuk mengumpulkan informasi 
          komprehensif mengenai kualitas layanan PAUD. Hasil survei digunakan untuk menyusun Rapor Pendidikan 
          dan Perencanaan Berbasis Data (PBD) guna meningkatkan mutu pendidikan.
        </p>
      </div>

      <!-- Jadwal -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">📅 Jadwal Sulingjar PAUD 2024</h3>
        <div class="space-y-3">
          <div v-for="(item, index) in jadwal2024" :key="index" class="flex items-center gap-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
            <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <div class="flex-1">
              <h4 class="font-semibold text-gray-900 dark:text-white text-sm">{{ item.kegiatan }}</h4>
              <p class="text-xs text-gray-500">{{ item.tanggal }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Aspek Sulingjar -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">🎯 Aspek yang Dinilai</h3>
        <div class="grid md:grid-cols-2 gap-4">
          <div v-for="aspek in aspekSulingjar" :key="aspek.nama" class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
            <div class="text-2xl">{{ aspek.icon }}</div>
            <div>
              <h4 class="font-semibold text-gray-900 dark:text-white text-sm">{{ aspek.nama }}</h4>
              <p class="text-xs text-gray-500">{{ aspek.deskripsi }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Langkah Pengisian -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">📝 Langkah Pengisian Sulingjar</h3>
        <div class="space-y-2">
          <div v-for="(langkah, index) in langkahPengisian" :key="index" class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
            <div class="w-6 h-6 bg-indigo-500 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">
              {{ index + 1 }}
            </div>
            <p class="text-sm text-gray-700 dark:text-gray-300">{{ langkah }}</p>
          </div>
        </div>
        <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
          <p class="text-sm text-blue-700 dark:text-blue-400">
            🔗 <strong>Link Survei:</strong> 
            <a href="https://surveilingkunganbelajar.kemdikbud.go.id" target="_blank" class="underline hover:text-blue-900">
              surveilingkunganbelajar.kemdikbud.go.id
            </a>
          </p>
        </div>
      </div>
    </div>
  </DataLayout>
</template>
