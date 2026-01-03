<script setup>
import { ref, onMounted } from 'vue'
import { profileService } from '@/services'

const content = ref(null)
const loading = ref(true)

const bidangList = [
  {
    id: 1,
    name: 'Bidang Perencanaan dan Pengembangan Program',
    icon: '📋',
    color: 'from-blue-500 to-blue-600',
    tupoksi: [
      'Menyusun rencana strategis dan program kerja Pokja Bunda PAUD',
      'Mengembangkan program-program inovatif untuk peningkatan kualitas PAUD',
      'Melakukan kajian dan penelitian untuk pengembangan PAUD HI',
      'Menyusun standar dan pedoman pelaksanaan program PAUD'
    ]
  },
  {
    id: 2,
    name: 'Bidang Sosialisasi dan Advokasi',
    icon: '📢',
    color: 'from-green-500 to-green-600',
    tupoksi: [
      'Melakukan sosialisasi program PAUD HI kepada masyarakat',
      'Mengadvokasi kebijakan yang mendukung pengembangan PAUD',
      'Menyelenggarakan kampanye kesadaran pentingnya pendidikan anak usia dini',
      'Membangun komunikasi dengan media dan pemangku kepentingan'
    ]
  },
  {
    id: 3,
    name: 'Bidang Koordinasi dan Kerjasama Kemitraan',
    icon: '🤝',
    color: 'from-purple-500 to-purple-600',
    tupoksi: [
      'Membangun kemitraan dengan instansi pemerintah dan swasta',
      'Mengkoordinasikan kegiatan lintas sektor terkait PAUD HI',
      'Menjalin kerjasama dengan lembaga pendidikan dan organisasi masyarakat',
      'Memfasilitasi jejaring antar Bunda PAUD tingkat kecamatan dan kelurahan'
    ]
  },
  {
    id: 4,
    name: 'Bidang Pengawasan/Pemantauan dan Evaluasi',
    icon: '📊',
    color: 'from-orange-500 to-orange-600',
    tupoksi: [
      'Melakukan monitoring pelaksanaan program PAUD HI',
      'Mengevaluasi capaian dan dampak program secara berkala',
      'Menyusun laporan pelaksanaan kegiatan Pokja Bunda PAUD',
      'Memberikan rekomendasi perbaikan berdasarkan hasil evaluasi'
    ]
  }
]

onMounted(async () => {
  try {
    const response = await profileService.getActive()
    if (response.success && response.data['struktur_organisasi']) {
      content.value = response.data['struktur_organisasi']
    }
  } catch (e) {
    console.error('Failed to load Struktur:', e)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Struktur Organisasi</h2>
    <p class="text-gray-600 dark:text-gray-400 mb-8">
      Pokja Bunda PAUD Kota Surabaya periode 2025-2030
    </p>

    <!-- Org Chart -->
    <div v-if="loading" class="flex justify-center py-12">
      <div class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
    </div>

    <div v-else-if="content" class="space-y-8">
      <!-- Dynamic Image (Chart) -->
      <div v-if="content.image" class="card p-4 flex justify-center bg-white dark:bg-gray-800">
         <img :src="content.image" :alt="content.title" class="max-w-full h-auto rounded"/>
      </div>

      <!-- Dynamic Content -->
      <div class="card p-8">
         <div class="prose dark:prose-invert max-w-none" v-html="content.content"></div>
      </div>
    </div>

    <!-- Fallback Static Structure with Image Placeholder -->
    <div v-else class="space-y-8">
       <div class="card p-8 text-center">
          <!-- Placeholder for Struktur Organisasi Image -->
          <div class="bg-gray-100 dark:bg-gray-700 rounded-xl p-8 mb-6">
            <div class="text-center py-12">
              <svg class="w-24 h-24 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              <p class="mt-4 text-lg font-medium text-gray-600 dark:text-gray-300">
                Gambar Struktur Organisasi
              </p>
              <p class="mt-2 text-gray-500 dark:text-gray-400">
                Bagan struktur organisasi Bunda PAUD Kota Surabaya akan ditampilkan di sini
              </p>
            </div>
          </div>
          <p class="text-gray-500 dark:text-gray-400">
            Upload gambar struktur organisasi melalui panel admin untuk menampilkan bagan organisasi.
          </p>
       </div>
    </div>

    <!-- Tupoksi Bidang -->
    <div class="mt-10">
      <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 text-center">
        Tugas Pokok dan Fungsi Bidang
      </h3>
      <div class="grid gap-6 md:grid-cols-2">
        <div 
          v-for="bidang in bidangList" 
          :key="bidang.id"
          class="card overflow-hidden hover:shadow-lg transition-shadow duration-300"
        >
          <!-- Header -->
          <div :class="`bg-gradient-to-r ${bidang.color} p-4 text-white`">
            <div class="flex items-center gap-3">
              <span class="text-2xl">{{ bidang.icon }}</span>
              <h4 class="font-semibold text-sm leading-tight">{{ bidang.name }}</h4>
            </div>
          </div>
          
          <!-- Tupoksi List -->
          <div class="p-4">
            <ul class="space-y-2">
              <li 
                v-for="(tugas, index) in bidang.tupoksi" 
                :key="index"
                class="flex items-start gap-2 text-sm text-gray-700 dark:text-gray-300"
              >
                <svg class="w-4 h-4 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ tugas }}
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Note -->
    <div class="mt-8 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
      <p class="text-sm text-amber-700 dark:text-amber-400">
        <strong>Catatan:</strong> Nama-nama pengurus akan diperbarui sesuai dengan SK Pengangkatan terbaru.
      </p>
    </div>
  </div>
</template>

