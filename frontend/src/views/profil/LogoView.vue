<script setup>
import { ref, onMounted } from 'vue'
import { profileService } from '@/services'

const content = ref(null)
const loading = ref(true)

onMounted(async () => {
  try {
    const response = await profileService.getActive()
    if (response.success && response.data['arti_logo']) {
      content.value = response.data['arti_logo']
    }
  } catch (e) {
    console.error('Failed to load Logo content:', e)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Hero -->
    <section class="bg-gradient-to-br from-blue-600 to-blue-800 text-white py-16">
      <div class="section-container text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Arti Logo Bunda PAUD</h1>
        <p class="text-blue-100 max-w-2xl mx-auto">
          Makna dan Filosofi Logo Bunda PAUD
        </p>
      </div>
    </section>

    <!-- Content -->
    <section class="section-container py-12">
      <div v-if="loading" class="flex justify-center py-12">
        <div class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
      </div>

      <div v-else class="max-w-4xl mx-auto">
        <!-- Logo Display -->
        <div class="card p-8 text-center mb-8">
          <img 
            :src="content?.image || '/logo-bunda-paud.png'" 
            :alt="content?.title || 'Logo Bunda PAUD'" 
            class="h-48 mx-auto mb-6"
            onerror="this.src='https://placehold.co/200x200/2563eb/ffffff?text=Logo+Bunda+PAUD'"
          />
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ content?.title || 'Logo Bunda PAUD' }}</h2>
        </div>

        <!-- Filosofi (Dynamic) -->
        <div v-if="content" class="card p-8 mb-8">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
            Makna dan Filosofi
          </h2>
          <div class="prose dark:prose-invert max-w-none" v-html="content.content"></div>
        </div>
        
        <!-- Fallback if no content -->
        <div v-else class="card p-8 mb-8">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Makna dan Filosofi</h2>
          <p class="text-gray-600 dark:text-gray-400">
            Logo Bunda PAUD menampilkan bentuk <strong>berlian berwarna biru dan hijau</strong> yang merepresentasikan keteguhan, kualitas, serta pertumbuhan berkelanjutan dalam dunia pendidikan anak usia dini.
          </p>
        </div>

        <!-- Elemen Logo -->
        <div class="grid md:grid-cols-2 gap-6 mb-8">
          <!-- Warna Biru -->
          <div class="card p-6">
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-4">
              <div class="w-6 h-6 bg-blue-600 rounded-full"></div>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Warna Biru</h3>
            <p class="text-gray-600 dark:text-gray-400">
              Menggambarkan suasana pembelajaran yang <strong>tenang</strong>, penuh <strong>kepercayaan</strong>, dan <strong>stabilitas</strong> dalam penyelenggaraan PAUD.
            </p>
          </div>

          <!-- Warna Hijau -->
          <div class="card p-6">
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mb-4">
              <div class="w-6 h-6 bg-green-600 rounded-full"></div>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Warna Hijau</h3>
            <p class="text-gray-600 dark:text-gray-400">
              Melambangkan <strong>harapan</strong>, <strong>kesegaran</strong>, serta <strong>perkembangan positif</strong> dalam pertumbuhan anak usia dini.
            </p>
          </div>

          <!-- Figur Manusia Kuning -->
          <div class="card p-6">
            <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Tiga Figur Manusia Kuning</h3>
            <p class="text-gray-600 dark:text-gray-400">
              Mencerminkan peran <strong>pendidik atau orang tua sebagai pembimbing</strong>, serta <strong>anak-anak sebagai generasi penerus</strong> yang penuh semangat, kebahagiaan, dan optimisme.
            </p>
          </div>

          <!-- Buku Terbuka -->
          <div class="card p-6">
            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Buku Terbuka</h3>
            <p class="text-gray-600 dark:text-gray-400">
              Menjadi simbol <strong>pendidikan sebagai fondasi utama</strong> dalam membentuk karakter dan masa depan anak-anak.
            </p>
          </div>
        </div>

        <!-- Komitmen -->
        <div class="card p-8 bg-gradient-to-r from-primary-50 to-blue-50 dark:from-primary-900/20 dark:to-blue-900/20">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Komitmen Bunda PAUD</h3>
          <p class="text-gray-600 dark:text-gray-400">
            Melalui elemen-elemen ini, PAUD menyampaikan komitmen untuk menciptakan <strong>lingkungan belajar yang aman, penuh kasih sayang, kreatif</strong>, serta berorientasi pada pengembangan anak secara optimal, baik dari aspek <strong>fisik, emosional, sosial, maupun intelektual</strong>.
          </p>
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
