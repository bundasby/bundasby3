<script setup>
import { ref, onMounted } from 'vue'
import { profileService } from '@/services'

const content = ref(null)
const loading = ref(true)

onMounted(async () => {
  try {
    const response = await profileService.getActive()
    if (response.success && response.data['visi_misi']) {
      content.value = response.data['visi_misi']
    }
  } catch (e) {
    console.error('Failed to load Visi Misi:', e)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Visi & Misi</h2>
    
    <div v-if="loading" class="flex justify-center py-12">
      <div class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
    </div>

    <!-- Dynamic Content -->
    <div v-else-if="content" class="card p-8">
      <!-- We use prose class to style HTML content from backend -->
      <div class="prose dark:prose-invert max-w-none" v-html="content.content"></div>
    </div>

    <!-- Fallback if empty -->
    <div v-else class="card p-6 text-center text-gray-500">
      Belum ada konten Visi & Misi.
    </div>
  </div>
</template>

