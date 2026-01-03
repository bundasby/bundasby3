<script setup>
// Daftar Ijin Operasional - External link to SIBUNDA
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// Redirect to external SIBUNDA page
onMounted(() => {
  window.open('https://sibunda.surabaya.go.id/landing/ijop', '_blank')
  router.back()
})
</script>

<template>
  <div class="min-h-screen flex items-center justify-center">
    <div class="text-center">
      <div class="animate-spin w-12 h-12 border-4 border-primary-500 border-t-transparent rounded-full mx-auto mb-4"></div>
      <p class="text-gray-600 dark:text-gray-400">Mengalihkan ke SIBUNDA...</p>
      <a 
        href="https://sibunda.surabaya.go.id/landing/ijop" 
        target="_blank"
        class="text-primary-600 hover:underline mt-2 inline-block"
      >
        Klik di sini jika tidak dialihkan otomatis
      </a>
    </div>
  </div>
</template>
