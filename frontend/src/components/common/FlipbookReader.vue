<script setup>
import { ref, onMounted, onUnmounted, computed, watch } from 'vue'

const props = defineProps({
  pages: {
    type: Array,
    required: true,
    // Array of page image URLs or content
  },
  title: {
    type: String,
    default: 'Buku Digital'
  }
})

const emit = defineEmits(['close'])

const currentSpread = ref(0) // Current spread (2 pages)
const isFullscreen = ref(false)
const isFlipping = ref(false)
const flipDirection = ref('next')
const containerRef = ref(null)
const showThumbnails = ref(false)
const zoomLevel = ref(1)

// Total spreads (2 pages per spread, first page is cover alone)
const totalSpreads = computed(() => {
  return Math.ceil((props.pages.length + 1) / 2)
})

// Get current visible pages
const visiblePages = computed(() => {
  if (currentSpread.value === 0) {
    // Cover page only
    return [null, props.pages[0]]
  }
  
  const leftIndex = currentSpread.value * 2 - 1
  const rightIndex = leftIndex + 1
  
  return [
    props.pages[leftIndex] || null,
    props.pages[rightIndex] || null
  ]
})

// Page numbers
const pageNumbers = computed(() => {
  if (currentSpread.value === 0) {
    return [null, 1]
  }
  const left = currentSpread.value * 2
  const right = left + 1
  return [
    left <= props.pages.length ? left : null,
    right <= props.pages.length ? right : null
  ]
})

// Navigation
const canGoNext = computed(() => currentSpread.value < totalSpreads.value - 1)
const canGoPrev = computed(() => currentSpread.value > 0)

const nextPage = () => {
  if (!canGoNext.value || isFlipping.value) return
  isFlipping.value = true
  flipDirection.value = 'next'
  setTimeout(() => {
    currentSpread.value++
    isFlipping.value = false
  }, 400)
}

const prevPage = () => {
  if (!canGoPrev.value || isFlipping.value) return
  isFlipping.value = true
  flipDirection.value = 'prev'
  setTimeout(() => {
    currentSpread.value--
    isFlipping.value = false
  }, 400)
}

const goToPage = (pageNum) => {
  const spread = Math.floor(pageNum / 2)
  currentSpread.value = spread
  showThumbnails.value = false
}

// Fullscreen
const toggleFullscreen = async () => {
  if (!document.fullscreenElement) {
    await containerRef.value?.requestFullscreen()
    isFullscreen.value = true
  } else {
    await document.exitFullscreen()
    isFullscreen.value = false
  }
}

// Zoom
const zoomIn = () => {
  if (zoomLevel.value < 2) zoomLevel.value += 0.25
}

const zoomOut = () => {
  if (zoomLevel.value > 0.5) zoomLevel.value -= 0.25
}

const resetZoom = () => {
  zoomLevel.value = 1
}

// Keyboard navigation
const handleKeydown = (e) => {
  if (e.key === 'ArrowRight' || e.key === 'PageDown') {
    nextPage()
  } else if (e.key === 'ArrowLeft' || e.key === 'PageUp') {
    prevPage()
  } else if (e.key === 'Escape') {
    if (isFullscreen.value) {
      document.exitFullscreen()
    } else {
      emit('close')
    }
  } else if (e.key === 'Home') {
    currentSpread.value = 0
  } else if (e.key === 'End') {
    currentSpread.value = totalSpreads.value - 1
  }
}

onMounted(() => {
  document.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown)
})

// Progress percentage
const progress = computed(() => {
  return ((currentSpread.value + 1) / totalSpreads.value) * 100
})
</script>

<template>
  <div 
    ref="containerRef"
    class="flipbook-container fixed inset-0 z-50 bg-gray-900 flex flex-col"
  >
    <!-- Header -->
    <div class="flex items-center justify-between px-4 py-2 bg-gray-800 text-white">
      <div class="flex items-center gap-4">
        <button @click="$emit('close')" class="p-2 hover:bg-gray-700 rounded-lg transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
        <span class="text-sm text-gray-300">{{ currentSpread * 2 || 1 }} / {{ pages.length }}</span>
      </div>
      
      <h3 class="text-sm font-medium truncate max-w-md">{{ title }}</h3>
      
      <div class="flex items-center gap-2">
        <!-- Zoom -->
        <button @click="zoomOut" class="p-2 hover:bg-gray-700 rounded-lg" title="Zoom Out">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7" />
          </svg>
        </button>
        <span class="text-xs w-12 text-center">{{ Math.round(zoomLevel * 100) }}%</span>
        <button @click="zoomIn" class="p-2 hover:bg-gray-700 rounded-lg" title="Zoom In">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
          </svg>
        </button>
        
        <!-- Thumbnails -->
        <button @click="showThumbnails = !showThumbnails" class="p-2 hover:bg-gray-700 rounded-lg" title="Daftar Halaman">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
          </svg>
        </button>
        
        <!-- Fullscreen -->
        <button @click="toggleFullscreen" class="p-2 hover:bg-gray-700 rounded-lg" title="Fullscreen">
          <svg v-if="!isFullscreen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
          </svg>
          <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9V4m0 5H4m0 0l5-5m6 5V4m0 5h5m0 0l-5-5M9 15v5m0-5H4m0 0l5 5m6-5v5m0-5h5m0 0l-5 5" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Book Container -->
    <div class="flex-1 flex items-center justify-center overflow-hidden relative">
      <!-- Navigation Arrows -->
      <button 
        v-if="canGoPrev"
        @click="prevPage"
        class="absolute left-4 z-10 p-3 bg-black/50 hover:bg-black/70 text-white rounded-full transition-all"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>

      <button 
        v-if="canGoNext"
        @click="nextPage"
        class="absolute right-4 z-10 p-3 bg-black/50 hover:bg-black/70 text-white rounded-full transition-all"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>

      <!-- Book -->
      <div 
        class="book-wrapper perspective-1000"
        :style="{ transform: `scale(${zoomLevel})` }"
      >
        <div 
          class="book flex shadow-2xl"
          :class="{ 
            'flip-next': isFlipping && flipDirection === 'next',
            'flip-prev': isFlipping && flipDirection === 'prev'
          }"
        >
          <!-- Left Page -->
          <div class="page page-left bg-white relative" style="width: 400px; height: 560px;">
            <div v-if="visiblePages[0]" class="w-full h-full">
              <img 
                :src="visiblePages[0]" 
                :alt="`Halaman ${pageNumbers[0]}`"
                class="w-full h-full object-contain"
              />
            </div>
            <div v-else class="w-full h-full bg-gray-100 flex items-center justify-center">
              <span class="text-gray-400">Sampul Belakang</span>
            </div>
            <!-- Page Number -->
            <div v-if="pageNumbers[0]" class="absolute bottom-4 left-4 text-xs text-gray-500">
              {{ pageNumbers[0] }}
            </div>
            <!-- Page curl effect -->
            <div class="absolute inset-y-0 right-0 w-8 bg-gradient-to-l from-gray-200/50 to-transparent"></div>
          </div>

          <!-- Center Spine -->
          <div class="spine w-4 bg-gradient-to-r from-gray-300 via-gray-400 to-gray-300 shadow-inner"></div>

          <!-- Right Page -->
          <div class="page page-right bg-white relative" style="width: 400px; height: 560px;">
            <div v-if="visiblePages[1]" class="w-full h-full">
              <img 
                :src="visiblePages[1]" 
                :alt="`Halaman ${pageNumbers[1]}`"
                class="w-full h-full object-contain"
              />
            </div>
            <div v-else class="w-full h-full bg-gray-100 flex items-center justify-center">
              <span class="text-gray-400">Akhir Buku</span>
            </div>
            <!-- Page Number -->
            <div v-if="pageNumbers[1]" class="absolute bottom-4 right-4 text-xs text-gray-500">
              {{ pageNumbers[1] }}
            </div>
            <!-- Page curl effect -->
            <div class="absolute inset-y-0 left-0 w-8 bg-gradient-to-r from-gray-200/50 to-transparent"></div>
          </div>
        </div>
      </div>

      <!-- Thumbnails Panel -->
      <div 
        v-if="showThumbnails"
        class="absolute right-0 top-0 bottom-0 w-64 bg-gray-800 overflow-y-auto p-4 shadow-xl"
      >
        <h4 class="text-white font-medium mb-4">Daftar Halaman</h4>
        <div class="grid grid-cols-2 gap-2">
          <button
            v-for="(page, index) in pages"
            :key="index"
            @click="goToPage(index + 1)"
            class="relative aspect-[3/4] bg-gray-700 rounded overflow-hidden hover:ring-2 hover:ring-primary-500 transition-all"
            :class="{ 'ring-2 ring-primary-500': Math.floor((index + 1) / 2) === currentSpread }"
          >
            <img :src="page" :alt="`Hal ${index + 1}`" class="w-full h-full object-cover" />
            <span class="absolute bottom-1 right-1 text-xs bg-black/70 text-white px-1 rounded">
              {{ index + 1 }}
            </span>
          </button>
        </div>
      </div>
    </div>

    <!-- Footer Controls -->
    <div class="bg-gray-800 px-4 py-3">
      <!-- Progress Bar -->
      <div class="w-full bg-gray-700 rounded-full h-1 mb-3">
        <div 
          class="bg-primary-500 h-1 rounded-full transition-all duration-300"
          :style="{ width: `${progress}%` }"
        ></div>
      </div>

      <div class="flex items-center justify-center gap-4">
        <!-- First Page -->
        <button 
          @click="currentSpread = 0"
          class="p-2 text-gray-400 hover:text-white disabled:opacity-50"
          :disabled="currentSpread === 0"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
          </svg>
        </button>

        <!-- Previous -->
        <button @click="prevPage" class="p-2 text-gray-400 hover:text-white disabled:opacity-50" :disabled="!canGoPrev">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>

        <!-- Page Input -->
        <div class="flex items-center gap-2 text-white">
          <span class="text-sm">Halaman</span>
          <input 
            type="number" 
            :value="currentSpread * 2 || 1"
            @change="goToPage($event.target.value)"
            class="w-16 px-2 py-1 bg-gray-700 rounded text-center text-sm"
            :min="1"
            :max="pages.length"
          />
          <span class="text-sm text-gray-400">dari {{ pages.length }}</span>
        </div>

        <!-- Next -->
        <button @click="nextPage" class="p-2 text-gray-400 hover:text-white disabled:opacity-50" :disabled="!canGoNext">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>

        <!-- Last Page -->
        <button 
          @click="currentSpread = totalSpreads - 1"
          class="p-2 text-gray-400 hover:text-white disabled:opacity-50"
          :disabled="currentSpread === totalSpreads - 1"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.perspective-1000 {
  perspective: 2000px;
}

.book-wrapper {
  transition: transform 0.3s ease;
}

.book {
  transform-style: preserve-3d;
}

.page {
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

.page-left {
  border-radius: 3px 0 0 3px;
  transform-origin: right center;
}

.page-right {
  border-radius: 0 3px 3px 0;
  transform-origin: left center;
}

.flip-next .page-right {
  animation: flip-right 0.4s ease-in-out;
}

.flip-prev .page-left {
  animation: flip-left 0.4s ease-in-out;
}

@keyframes flip-right {
  0% { transform: rotateY(0deg); }
  50% { transform: rotateY(-15deg); }
  100% { transform: rotateY(0deg); }
}

@keyframes flip-left {
  0% { transform: rotateY(0deg); }
  50% { transform: rotateY(15deg); }
  100% { transform: rotateY(0deg); }
}

/* Book shadow */
.book::before {
  content: '';
  position: absolute;
  bottom: -20px;
  left: 10%;
  width: 80%;
  height: 20px;
  background: radial-gradient(ellipse, rgba(0,0,0,0.3) 0%, transparent 70%);
  filter: blur(5px);
}
</style>
