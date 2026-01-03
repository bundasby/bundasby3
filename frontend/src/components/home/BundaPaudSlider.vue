<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { kelurahanData, generateKelurahanBundaPaud } from '@/data/kelurahanData.js'

// Array warna untuk variasi
const colors = [
  'ec4899', 'f43f5e', 'a855f7', 'd946ef', 'f472b6', 'fb7185', 'c084fc', 'e879f9',
  'fb923c', 'f97316', 'fbbf24', 'facc15', '4ade80', '2dd4bf', '22d3ee', '38bdf8',
  'ec4899', 'f43f5e', 'a855f7', 'd946ef', 'f472b6', 'fb7185', 'c084fc', 'e879f9',
  'fb923c', 'f97316', 'fbbf24', 'facc15', '4ade80', '2dd4bf', '22d3ee'
]

// Dummy female photo URLs with different styles
// Dummy female photo URLs using pravatar.cc (female avatars)
// Using female IDs from pravatar (generally 1-70 are diverse photos)
const getPhotoUrl = (index) => {
  // Use different female avatar IDs
  const avatarId = (index % 70) + 1
  return `https://i.pravatar.cc/150?img=${avatarId}`
}

// Generate Bunda PAUD Kecamatan (31 kecamatan)
const kecamatanList = Object.keys(kelurahanData)
const bundaPaudKecamatan = kecamatanList.map((kecamatan, index) => ({
  id: index + 1,
  name: '[Nama Bunda PAUD]',
  area: `Kec. ${kecamatan}`,
  type: 'kecamatan',
  photo: getPhotoUrl(index)
}))

// Generate Bunda PAUD Kelurahan (154 kelurahan)
const bundaPaudKelurahan = generateKelurahanBundaPaud().map((item, index) => ({
  ...item,
  area: `Kel. ${item.kelurahan}`,
  photo: getPhotoUrl(index + 31, 'BP')
}))

// Combine both kecamatan and kelurahan (31 + 154 = 185 total)
const bundaPaudList = [...bundaPaudKecamatan, ...bundaPaudKelurahan]

// Duplicate for infinite scroll effect
const duplicatedList = [...bundaPaudList, ...bundaPaudList]

const isPaused = ref(false)
const sliderRef = ref(null)
const isDragging = ref(false)
const startX = ref(0)
const scrollLeft = ref(0)

// Touch/Mouse drag functionality
const handleMouseDown = (e) => {
  isDragging.value = true
  isPaused.value = true
  startX.value = e.pageX - sliderRef.value.offsetLeft
  scrollLeft.value = sliderRef.value.scrollLeft
  sliderRef.value.style.cursor = 'grabbing'
}

const handleMouseUp = () => {
  isDragging.value = false
  if (sliderRef.value) {
    sliderRef.value.style.cursor = 'grab'
  }
}

const handleMouseMove = (e) => {
  if (!isDragging.value) return
  e.preventDefault()
  const x = e.pageX - sliderRef.value.offsetLeft
  const walk = (x - startX.value) * 2
  sliderRef.value.scrollLeft = scrollLeft.value - walk
}

const handleMouseLeave = () => {
  isDragging.value = false
  isPaused.value = false
}

// Touch events
const handleTouchStart = (e) => {
  isPaused.value = true
  startX.value = e.touches[0].pageX - sliderRef.value.offsetLeft
  scrollLeft.value = sliderRef.value.scrollLeft
}

const handleTouchMove = (e) => {
  const x = e.touches[0].pageX - sliderRef.value.offsetLeft
  const walk = (x - startX.value) * 2
  sliderRef.value.scrollLeft = scrollLeft.value - walk
}

const handleTouchEnd = () => {
  isPaused.value = false
}

// Auto-scroll animation
let animationId = null
const autoScroll = () => {
  if (!isPaused.value && sliderRef.value) {
    sliderRef.value.scrollLeft += 1.5 // Speed adjustment
    
    // Reset to start when reaching halfway (the duplicate point)
    if (sliderRef.value.scrollLeft >= sliderRef.value.scrollWidth / 2) {
      sliderRef.value.scrollLeft = 0
    }
  }
  animationId = requestAnimationFrame(autoScroll)
}

onMounted(() => {
  autoScroll()
})

onUnmounted(() => {
  if (animationId) {
    cancelAnimationFrame(animationId)
  }
})
</script>

<template>
  <section class="py-12 bg-gray-50 dark:bg-gray-800/50 overflow-hidden">
    <div class="section-container mb-8">
      <div class="text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2">
          Bunda PAUD Kecamatan dan Kelurahan
        </h2>
        <p class="text-gray-600 dark:text-gray-400">
          Jaringan Bunda PAUD di seluruh Kota Surabaya
        </p>
      </div>
    </div>

    <!-- Swipeable Infinite Scroll Slider -->
    <div class="relative">
      <div 
        ref="sliderRef"
        class="flex gap-6 overflow-x-auto scrollbar-hide cursor-grab select-none"
        style="scroll-behavior: auto; -webkit-overflow-scrolling: touch;"
        @mousedown="handleMouseDown"
        @mouseup="handleMouseUp"
        @mousemove="handleMouseMove"
        @mouseleave="handleMouseLeave"
        @mouseenter="isPaused = true"
        @touchstart="handleTouchStart"
        @touchmove="handleTouchMove"
        @touchend="handleTouchEnd"
      >
        <div 
          v-for="(bunda, index) in duplicatedList" 
          :key="`${bunda.id}-${index}`"
          class="flex-shrink-0 w-36"
        >
          <div class="card p-4 text-center hover:shadow-lg transition-all hover:-translate-y-1">
            <div class="w-20 h-20 mx-auto mb-3 rounded-full overflow-hidden ring-4 ring-white dark:ring-gray-700 shadow-lg">
              <img :src="bunda.photo" :alt="bunda.name" class="w-full h-full object-cover" draggable="false"/>
            </div>
            <h3 class="font-medium text-gray-900 dark:text-white text-xs truncate">{{ bunda.name }}</h3>
            <p class="text-primary-600 dark:text-primary-400 text-xs truncate">{{ bunda.area }}</p>
          </div>
        </div>
      </div>
      
      <!-- Gradient overlays -->
      <div class="absolute inset-y-0 left-0 w-16 bg-gradient-to-r from-gray-50 dark:from-gray-800/50 to-transparent pointer-events-none"></div>
      <div class="absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-gray-50 dark:from-gray-800/50 to-transparent pointer-events-none"></div>
    </div>

    <!-- Swipe hint -->
    <p class="text-center text-xs text-gray-400 mt-4">← Geser untuk melihat lebih banyak →</p>

    <!-- View All Link -->
    <div class="section-container mt-6 text-center">
      <router-link 
        to="/bunda-paud"
        class="inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 hover:text-primary-700 font-medium"
      >
        Lihat Semua Bunda PAUD
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </router-link>
    </div>
  </section>
</template>

<style scoped>
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
</style>
