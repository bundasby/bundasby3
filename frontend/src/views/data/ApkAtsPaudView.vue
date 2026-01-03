<script setup>
import { ref } from 'vue'
</script>

<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="relative h-64 rounded-3xl overflow-hidden mb-8">
      <div class="absolute inset-0 bg-gradient-to-r from-primary-600 to-primary-400"></div>
      <div class="relative h-full flex items-center px-8 md:px-12">
        <div class="max-w-3xl">
          <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">APK, AKS, ATS PAUD</h1>
          <p class="text-primary-100 text-lg">
            Angka Partisipasi Kasar, Angka Partisipasi Sekolah, dan Anak Tidak Sekolah
          </p>
        </div>
      </div>
    </div>

    <!-- Content -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 min-h-[400px] flex items-center justify-center">
      <div class="text-center max-w-md mx-auto">
        <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
          </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Halaman Sedang Disiapkan</h2>
        <p class="text-gray-600 dark:text-gray-400">
          Data statistik partisipasi pendidikan sedang dalam proses integrasi.
        </p>
      </div>
    </div>
  </div>
</template>
