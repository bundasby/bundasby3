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
          <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Rapor Pendidikan PAUD</h1>
          <p class="text-primary-100 text-lg">
            Hasil evaluasi dan capaian kualitas pendidikan satuan PAUD
          </p>
        </div>
      </div>
    </div>

    <!-- Content -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 min-h-[400px] flex items-center justify-center">
      <div class="text-center max-w-md mx-auto">
        <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Halaman Sedang Disiapkan</h2>
        <p class="text-gray-600 dark:text-gray-400">
          Data rapor pendidikan sedang dalam proses sinkronisasi.
        </p>
      </div>
    </div>
  </div>
</template>
