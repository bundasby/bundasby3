<script setup>
import { ref, onMounted } from 'vue'
import { profileService } from '@/services'

const content = ref(null)
const loading = ref(true)

onMounted(async () => {
  try {
    const response = await profileService.getActive()
    if (response.success && response.data['tugas_fungsi']) {
      content.value = response.data['tugas_fungsi']
    }
  } catch (e) {
    console.error('Failed to load Tugas Fungsi:', e)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Hero -->
    <section class="bg-gradient-to-br from-teal-600 to-teal-800 text-white py-16">
      <div class="section-container text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Tugas dan Fungsi</h1>
        <p class="text-teal-100 max-w-2xl mx-auto">
          Pedoman Peran Bunda PAUD dalam Gerakan Nasional PAUD Berkualitas
        </p>
      </div>
    </section>

    <!-- Content -->
    <section class="section-container py-12">
      <div v-if="loading" class="flex justify-center py-12">
        <div class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
      </div>

      <div v-else class="max-w-4xl mx-auto">
        <!-- Dynamic Content -->
        <div v-if="content" class="card p-8 mb-8">
           <div class="prose dark:prose-invert max-w-none" v-html="content.content"></div>
        </div>
        
        <!-- Fallback Static Content if no data -->
        <div v-else>
           <!-- Intro -->
            <div class="card p-8 mb-8">
              <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Peran Strategis Bunda PAUD</h2>
              <p class="text-gray-600 dark:text-gray-400">
                Sebagai mitra utama dalam <strong>Gerakan Nasional PAUD Berkualitas</strong> menuju PAUD Bermutu untuk Semua, Bunda PAUD memiliki peran strategis dalam menggerakkan segenap komponen dan sumber daya yang ada di wilayahnya.
              </p>
            </div>
            <!-- (We could include the rest of the static content here as fallback, but for brevity let's just show the intro and a note) -->
             <div class="card p-8 mb-8 text-center text-gray-500">
                Data Tugas dan Fungsi belum tersedia.
             </div>
        </div>

        <!-- Pedoman -->
        <div class="card p-8 bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/20">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Pedoman Peran Bunda PAUD</h3>
          <p class="text-gray-600 dark:text-gray-400 mb-4">
            Pedoman Peran Bunda PAUD disusun untuk menjadi acuan bagi Bunda PAUD di tingkat <strong>Provinsi, Kabupaten/Kota, Kecamatan, dan Desa/Kelurahan</strong> dalam melaksanakan tugas dan perannya. Melalui pedoman ini, diharapkan Bunda PAUD dapat menggerakkan seluruh unsur melalui Kelompok Kerja (Pokja) Bunda PAUD.
          </p>
          <a 
            href="https://paudpedia.kemendikdasmen.go.id/kabar-paud/berita/pedoman-peran-bunda-paud-2025" 
            target="_blank"
            class="inline-flex items-center gap-2 text-primary-600 hover:underline"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Unduh Pedoman Lengkap
          </a>
        </div>

        <!-- Source -->
        <div class="mt-8 text-center">
          <p class="text-sm text-gray-500 dark:text-gray-400">
            Sumber: 
            <a href="https://paudpedia.kemendikdasmen.go.id" target="_blank" class="text-primary-600 hover:underline">
              PAUDPEDIA Kemendikdasmen
            </a>
          </p>
        </div>
      </div>
    </section>
  </div>
</template>
