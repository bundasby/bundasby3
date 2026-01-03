<script setup>
import { ref } from 'vue'

const selectedImage = ref(null)

const infografis = ref([
  {
    id: 1,
    title: 'Pentingnya PAUD untuk Anak',
    image: 'https://placehold.co/800x1000/2563eb/ffffff?text=Infografis+PAUD',
    category: 'Edukasi',
    date: '15 Des 2025'
  },
  {
    id: 2,
    title: 'Tips Parenting Anak Usia Dini',
    image: 'https://placehold.co/800x1000/16a34a/ffffff?text=Tips+Parenting',
    category: 'Parenting',
    date: '10 Des 2025'
  },
  {
    id: 3,
    title: 'Makanan Bergizi untuk Tumbuh Kembang',
    image: 'https://placehold.co/800x1000/f59e0b/ffffff?text=Gizi+Anak',
    category: 'Kesehatan',
    date: '5 Des 2025'
  },
  {
    id: 4,
    title: 'Stimulasi Motorik Anak',
    image: 'https://placehold.co/800x1000/8b5cf6/ffffff?text=Stimulasi+Motorik',
    category: 'Perkembangan',
    date: '1 Des 2025'
  },
  {
    id: 5,
    title: 'Program Bunda PAUD 2025',
    image: 'https://placehold.co/800x1000/ec4899/ffffff?text=Program+2025',
    category: 'Program',
    date: '28 Nov 2025'
  },
  {
    id: 6,
    title: 'Cara Mengajar Anak dengan Bermain',
    image: 'https://placehold.co/800x1000/14b8a6/ffffff?text=Belajar+Bermain',
    category: 'Edukasi',
    date: '20 Nov 2025'
  }
])

const categories = ['Semua', 'Edukasi', 'Parenting', 'Kesehatan', 'Perkembangan', 'Program']
const activeCategory = ref('Semua')

const filteredInfografis = ref([...infografis.value])

const filterByCategory = (category) => {
  activeCategory.value = category
  if (category === 'Semua') {
    filteredInfografis.value = [...infografis.value]
  } else {
    filteredInfografis.value = infografis.value.filter(item => item.category === category)
  }
}

const openLightbox = (image) => {
  selectedImage.value = image
}

const closeLightbox = () => {
  selectedImage.value = null
}
</script>

<template>
  <div>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-blue-600 to-indigo-800 text-white py-16">
      <div class="section-container text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Infografis</h1>
        <p class="text-lg text-blue-100 max-w-2xl mx-auto">
          Kumpulan infografis edukatif dan informatif seputar pendidikan anak usia dini.
        </p>
      </div>
    </section>

    <!-- Filter Categories -->
    <section class="py-8 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
      <div class="section-container">
        <div class="flex flex-wrap gap-3 justify-center">
          <button
            v-for="category in categories"
            :key="category"
            @click="filterByCategory(category)"
            class="px-6 py-2 rounded-full text-sm font-medium transition-all duration-300"
            :class="activeCategory === category 
              ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/30' 
              : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-600'"
          >
            {{ category }}
          </button>
        </div>
      </div>
    </section>

    <!-- Infografis Grid -->
    <section class="py-16 bg-white dark:bg-gray-900">
      <div class="section-container">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          <div 
            v-for="item in filteredInfografis" 
            :key="item.id"
            @click="openLightbox(item)"
            class="group cursor-pointer"
          >
            <div class="relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300">
              <img 
                :src="item.image" 
                :alt="item.title"
                class="w-full aspect-[4/5] object-cover transition-transform duration-500 group-hover:scale-110"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <div class="absolute bottom-0 left-0 right-0 p-6">
                  <span class="inline-block px-3 py-1 bg-primary-500 text-white text-xs rounded-full mb-3">
                    {{ item.category }}
                  </span>
                  <h3 class="text-white font-bold text-lg mb-2">{{ item.title }}</h3>
                  <p class="text-gray-300 text-sm">{{ item.date }}</p>
                </div>
              </div>
            </div>
            <div class="mt-4 px-2">
              <span class="text-xs text-primary-600 dark:text-primary-400 font-medium">{{ item.category }}</span>
              <h3 class="font-semibold text-gray-900 dark:text-white mt-1">{{ item.title }}</h3>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="filteredInfografis.length === 0" class="text-center py-16">
          <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <p class="text-gray-500 dark:text-gray-400">Tidak ada infografis dalam kategori ini.</p>
        </div>
      </div>
    </section>

    <!-- Lightbox -->
    <div 
      v-if="selectedImage"
      @click="closeLightbox"
      class="fixed inset-0 z-50 bg-black/95 flex items-center justify-center p-4"
    >
      <button 
        @click="closeLightbox"
        class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors z-10"
      >
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
      <div @click.stop class="max-w-3xl w-full max-h-[90vh] overflow-auto">
        <img 
          :src="selectedImage.image" 
          :alt="selectedImage.title"
          class="w-full rounded-lg"
        />
        <div class="text-center mt-4">
          <span class="inline-block px-3 py-1 bg-primary-500 text-white text-xs rounded-full mb-2">
            {{ selectedImage.category }}
          </span>
          <h3 class="text-white font-bold text-xl">{{ selectedImage.title }}</h3>
          <p class="text-gray-400 text-sm mt-1">{{ selectedImage.date }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
