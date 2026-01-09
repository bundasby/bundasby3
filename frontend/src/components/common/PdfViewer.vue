<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  url: {
    type: String,
    required: true
  },
  title: {
    type: String,
    default: 'Dokumen PDF'
  }
})

const emit = defineEmits(['close'])

const isLoading = ref(true)

// Convert Google Drive URL to embed format
const embedUrl = computed(() => {
  const url = props.url
  
  // Extract file ID from Google Drive URL
  let fileId = null
  
  // Pattern: /file/d/{ID}/
  const pattern1 = /\/file\/d\/([a-zA-Z0-9_-]+)/
  const match1 = url.match(pattern1)
  if (match1) {
    fileId = match1[1]
  }
  
  // Pattern: ?id={ID} or &id={ID}
  if (!fileId) {
    const pattern2 = /[?&]id=([a-zA-Z0-9_-]+)/
    const match2 = url.match(pattern2)
    if (match2) {
      fileId = match2[1]
    }
  }
  
  // If it's a Google Drive link, convert to embed/preview format
  if (fileId) {
    return `https://drive.google.com/file/d/${fileId}/preview`
  }
  
  // Check if it's already a preview link
  if (url.includes('drive.google.com') && url.includes('/preview')) {
    return url
  }
  
  // Default: return as-is
  return url
})

// Handle iframe load
const onIframeLoad = () => {
  isLoading.value = false
}

// Keyboard handler
const handleKeydown = (e) => {
  if (e.key === 'Escape') {
    emit('close')
  }
}

// Close on backdrop click
const closeOnBackdrop = (e) => {
  if (e.target === e.currentTarget) {
    emit('close')
  }
}

onMounted(() => {
  document.addEventListener('keydown', handleKeydown)
  document.body.style.overflow = 'hidden'
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown)
  document.body.style.overflow = ''
})
</script>

<template>
  <!-- Backdrop -->
  <div 
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4"
    @click="closeOnBackdrop"
  >
    <!-- Modal Container - Centered, not fullscreen -->
    <div class="relative w-full max-w-4xl bg-gray-900 rounded-2xl shadow-2xl overflow-hidden animate-fade-in">
      <!-- Header -->
      <div class="flex items-center justify-between px-4 py-3 bg-gray-800 border-b border-gray-700">
        <h3 class="text-sm font-medium text-white truncate max-w-md">{{ title }}</h3>
        
        <div class="flex items-center gap-2">
          <!-- Open in New Tab -->
          <a 
            :href="url" 
            target="_blank"
            class="p-2 hover:bg-gray-700 rounded-lg transition-colors text-gray-400 hover:text-white"
            title="Buka di Tab Baru"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
          </a>
          
          <!-- Close Button -->
          <button 
            @click="$emit('close')" 
            class="p-2 hover:bg-gray-700 rounded-lg transition-colors text-gray-400 hover:text-white"
            title="Tutup (Esc)"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- PDF Container -->
      <div class="relative bg-gray-800" style="height: 70vh;">
        <!-- Loading Spinner -->
        <div 
          v-if="isLoading" 
          class="absolute inset-0 flex items-center justify-center bg-gray-800 z-10"
        >
          <div class="text-center">
            <svg class="animate-spin h-10 w-10 text-primary-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-400 text-sm">Memuat dokumen...</p>
          </div>
        </div>

        <!-- PDF iFrame -->
        <iframe
          :src="embedUrl"
          class="w-full h-full border-0"
          @load="onIframeLoad"
          allow="autoplay"
          allowfullscreen
        ></iframe>
      </div>

      <!-- Footer Info -->
      <div class="bg-gray-800 px-4 py-2 border-t border-gray-700">
        <p class="text-xs text-gray-500 text-center">
          Tekan <kbd class="px-1.5 py-0.5 bg-gray-700 rounded text-gray-300">Esc</kbd> untuk menutup • 
          Gunakan kontrol PDF untuk navigasi
        </p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.2s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
</style>
