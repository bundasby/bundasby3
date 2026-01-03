<script setup>
import { ref } from 'vue'
import DataLayout from '@/components/layout/DataLayout.vue'

const items = ref([])
</script>

<template>
  <DataLayout 
    title="Daftar Lembaga PAUD" 
    subtitle="Daftar lengkap satuan Pendidikan Anak Usia Dini di Kota Surabaya"
    icon="🏫"
  >
    <div class="space-y-6">
      <!-- Content -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 min-h-[400px] flex items-center justify-center">
        <div class="text-center max-w-md mx-auto">
          <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
          </div>
          <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Halaman Sedang Disiapkan</h2>
          <p class="text-gray-600 dark:text-gray-400">
            Data lembaga PAUD sedang dalam proses integrasi. Silakan kembali lagi nanti.
          </p>
        </div>
      </div>
    </div>
  </DataLayout>
</template>
