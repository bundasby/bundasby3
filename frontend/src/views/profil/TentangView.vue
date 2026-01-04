<script setup>
import { ref, onMounted } from 'vue'
import { profileService, statisticsService } from '@/services'

const sambutan = ref(null)
const localSambutan = ref('')
const statistics = ref(null)
const loading = ref(true)

onMounted(async () => {
  try {
    // First check localStorage for admin-edited content
    const saved = localStorage.getItem('bp_profil_settings')
    if (saved) {
      const profilData = JSON.parse(saved)
      if (profilData.sambutan) {
        localSambutan.value = profilData.sambutan
      }
    }
    
    // Also try to load from profileService
    const [profileRes, statsRes] = await Promise.all([
      profileService.getActive(),
      statisticsService.get()
    ])
    
    if (profileRes.success && profileRes.data['sambutan']) {
      sambutan.value = profileRes.data['sambutan']
    }
    
    if (statsRes.success) {
      statistics.value = statsRes.data
    }
  } catch (e) {
    console.error('Failed to load data:', e)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Tentang Bunda PAUD</h2>
    
    <!-- Admin Content from localStorage -->
    <div v-if="localSambutan" class="card p-6 mb-8 bg-gradient-to-r from-primary-50 to-accent-50 dark:from-primary-900/20 dark:to-accent-900/20">
      <div class="flex flex-col md:flex-row gap-6 items-center">
        <div class="w-32 h-32 rounded-full bg-primary-200 dark:bg-primary-800 flex items-center justify-center overflow-hidden flex-shrink-0">
          <img src="https://placehold.co/128x128/2563eb/ffffff?text=BP" alt="Bunda PAUD" class="w-full h-full object-cover"/>
        </div>
        <div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Sambutan Bunda PAUD Kota Surabaya</h3>
          <div class="text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ localSambutan }}</div>
        </div>
      </div>
    </div>

    <!-- Dynamic Sambutan / Hero -->
    <div v-else-if="sambutan" class="card p-6 mb-8 bg-gradient-to-r from-primary-50 to-accent-50 dark:from-primary-900/20 dark:to-accent-900/20">
      <div class="flex flex-col md:flex-row gap-6 items-center">
        <div v-if="sambutan.image" class="w-32 h-32 rounded-full bg-primary-200 dark:bg-primary-800 flex items-center justify-center overflow-hidden flex-shrink-0">
          <img :src="sambutan.image" :alt="sambutan.title" class="w-full h-full object-cover"/>
        </div>
        <div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ sambutan.title }}</h3>
          <div class="text-gray-600 dark:text-gray-400 prose dark:prose-invert max-w-none" v-html="sambutan.content"></div>
        </div>
      </div>
    </div>

    <!-- Static fallback if no dynamic data -->
    <div v-else class="card p-6 mb-8 bg-gradient-to-r from-primary-50 to-accent-50 dark:from-primary-900/20 dark:to-accent-900/20">
      <div class="flex flex-col md:flex-row gap-6 items-center">
        <div class="w-32 h-32 rounded-full bg-primary-200 dark:bg-primary-800 flex items-center justify-center overflow-hidden flex-shrink-0">
          <img src="https://placehold.co/128x128/2563eb/ffffff?text=BP" alt="Bunda PAUD" class="w-full h-full object-cover"/>
        </div>
        <div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Bunda PAUD Kota Surabaya</h3>
          <p class="text-gray-600 dark:text-gray-400">
            "Bersama membangun generasi emas Indonesia melalui pendidikan anak usia dini yang berkualitas"
          </p>
        </div>
      </div>
    </div>

    <!-- What is Bunda PAUD -->
    <div class="card p-6 mb-6">
      <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Apa itu Bunda PAUD?
      </h3>
      <p class="text-gray-700 dark:text-gray-300 mb-4">
        <strong>Bunda PAUD</strong> adalah sebutan untuk istri Kepala Daerah yang berperan sebagai penggerak 
        dalam pembinaan dan pengembangan Pendidikan Anak Usia Dini (PAUD) di wilayahnya. Peran Bunda PAUD 
        ditetapkan melalui Peraturan Presiden dan menjadi bagian penting dalam upaya meningkatkan akses 
        dan kualitas layanan PAUD di Indonesia.
      </p>
      <p class="text-gray-700 dark:text-gray-300">
        Di Kota Surabaya, Bunda PAUD didampingi oleh <strong>Pokja (Kelompok Kerja) Bunda PAUD</strong> yang 
        terdiri dari berbagai unsur masyarakat dan pemangku kepentingan pendidikan anak usia dini.
      </p>
    </div>

    <!-- Roles & Functions -->
    <div class="card p-6 mb-6">
      <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
        </svg>
        Peran dan Fungsi
      </h3>
      <div class="grid md:grid-cols-2 gap-4">
        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
          <h4 class="font-semibold text-blue-700 dark:text-blue-400 mb-2">Penggerak</h4>
          <p class="text-sm text-gray-600 dark:text-gray-400">Menggerakkan masyarakat untuk berpartisipasi aktif dalam pengembangan PAUD</p>
        </div>
        <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
          <h4 class="font-semibold text-green-700 dark:text-green-400 mb-2">Pembina</h4>
          <p class="text-sm text-gray-600 dark:text-gray-400">Membina dan mendukung lembaga PAUD untuk meningkatkan kualitas layanan</p>
        </div>
        <div class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
          <h4 class="font-semibold text-amber-700 dark:text-amber-400 mb-2">Motivator</h4>
          <p class="text-sm text-gray-600 dark:text-gray-400">Memotivasi guru dan pengelola PAUD dalam menjalankan tugas pendidikan</p>
        </div>
        <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
          <h4 class="font-semibold text-purple-700 dark:text-purple-400 mb-2">Koordinator</h4>
          <p class="text-sm text-gray-600 dark:text-gray-400">Mengkoordinasikan program antar lembaga terkait untuk PAUD yang optimal</p>
        </div>
      </div>
    </div>

    <!-- History -->
    <div class="card p-6 mb-6">
      <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Sejarah Singkat
      </h3>
      <div class="space-y-4">
        <div class="flex gap-4">
          <div class="w-16 flex-shrink-0 text-center">
            <span class="inline-block px-3 py-1 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 rounded-full text-sm font-medium">2011</span>
          </div>
          <p class="text-gray-700 dark:text-gray-300">Dibentuknya Pokja Bunda PAUD Kota Surabaya sebagai wadah koordinasi pengembangan PAUD</p>
        </div>
        <div class="flex gap-4">
          <div class="w-16 flex-shrink-0 text-center">
            <span class="inline-block px-3 py-1 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 rounded-full text-sm font-medium">2015</span>
          </div>
          <p class="text-gray-700 dark:text-gray-300">Perluasan program hingga menjangkau seluruh 31 kecamatan di Kota Surabaya</p>
        </div>
        <div class="flex gap-4">
          <div class="w-16 flex-shrink-0 text-center">
            <span class="inline-block px-3 py-1 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 rounded-full text-sm font-medium">2020</span>
          </div>
          <p class="text-gray-700 dark:text-gray-300">Peluncuran program PAUD Holistik Integratif dan integrasi dengan SIBUNDA</p>
        </div>
        <div class="flex gap-4">
          <div class="w-16 flex-shrink-0 text-center">
            <span class="inline-block px-3 py-1 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 rounded-full text-sm font-medium">2025</span>
          </div>
          <p class="text-gray-700 dark:text-gray-300">Periode baru Pokja Bunda PAUD 2025-2030 dengan fokus pencegahan stunting dan MBG</p>
        </div>
      </div>
    </div>

    <!-- Statistics -->
    <div class="card p-6">
      <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
        Data PAUD Kota Surabaya
      </h3>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
          <p class="text-2xl font-bold text-primary-600">850+</p>
          <p class="text-sm text-gray-600 dark:text-gray-400">Lembaga PAUD</p>
        </div>
        <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
          <p class="text-2xl font-bold text-green-600">3.200+</p>
          <p class="text-sm text-gray-600 dark:text-gray-400">Guru PAUD</p>
        </div>
        <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
          <p class="text-2xl font-bold text-amber-600">45.000+</p>
          <p class="text-sm text-gray-600 dark:text-gray-400">Peserta Didik</p>
        </div>
        <div class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
          <p class="text-2xl font-bold text-purple-600">31</p>
          <p class="text-sm text-gray-600 dark:text-gray-400">Kecamatan</p>
        </div>
      </div>
    </div>
  </div>
</template>
