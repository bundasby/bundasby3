<script setup>
defineProps({
  title: {
    type: String,
    default: 'Data Tidak Ditemukan'
  },
  message: {
    type: String,
    default: 'Tidak ada data yang tersedia.'
  },
  icon: {
    type: String,
    default: 'document' // document, search, folder
  }
})
</script>

<template>
  <div class="text-center py-12">
    <!-- Document Icon -->
    <svg v-if="icon === 'document'" class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
    </svg>
    
    <!-- Search Icon -->
    <svg v-else-if="icon === 'search'" class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
    </svg>
    
    <!-- Folder Icon -->
    <svg v-else-if="icon === 'folder'" class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
    </svg>
    
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ title }}</h3>
    <p class="text-gray-500 dark:text-gray-400">{{ message }}</p>
    
    <slot></slot>
  </div>
</template>
