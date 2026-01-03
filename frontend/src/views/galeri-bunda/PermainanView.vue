<script setup>
import { ref } from 'vue'

const games = ref([
  {
    id: 1,
    title: 'Tebak Huruf',
    category: 'Literasi',
    thumbnail: 'https://placehold.co/600x400/2563eb/ffffff?text=Tebak+Huruf',
    description: 'Permainan menebak huruf untuk melatih pengenalan alfabet pada anak.',
    ageRange: '4-6 tahun',
    playUrl: '#'
  },
  {
    id: 2,
    title: 'Puzzle Angka',
    category: 'Numerasi',
    thumbnail: 'https://placehold.co/600x400/16a34a/ffffff?text=Puzzle+Angka',
    description: 'Susun puzzle angka untuk melatih kemampuan numerasi dan motorik.',
    ageRange: '3-5 tahun',
    playUrl: '#'
  },
  {
    id: 3,
    title: 'Warna Ceria',
    category: 'Kreativitas',
    thumbnail: 'https://placehold.co/600x400/f59e0b/ffffff?text=Warna+Ceria',
    description: 'Mewarnai gambar digital dengan berbagai warna-warni menarik.',
    ageRange: '3-6 tahun',
    playUrl: '#'
  },
  {
    id: 4,
    title: 'Mencocokkan Bentuk',
    category: 'Logika',
    thumbnail: 'https://placehold.co/600x400/8b5cf6/ffffff?text=Cocok+Bentuk',
    description: 'Latih kemampuan kognitif dengan mencocokkan bentuk geometri.',
    ageRange: '3-5 tahun',
    playUrl: '#'
  },
  {
    id: 5,
    title: 'Suara Hewan',
    category: 'Pengetahuan',
    thumbnail: 'https://placehold.co/600x400/ec4899/ffffff?text=Suara+Hewan',
    description: 'Kenali berbagai hewan melalui suara khas mereka.',
    ageRange: '2-5 tahun',
    playUrl: '#'
  },
  {
    id: 6,
    title: 'Labirin Petualangan',
    category: 'Logika',
    thumbnail: 'https://placehold.co/600x400/14b8a6/ffffff?text=Labirin',
    description: 'Temukan jalan keluar dari labirin untuk melatih kemampuan pemecahan masalah.',
    ageRange: '4-6 tahun',
    playUrl: '#'
  }
])

const categories = ['Semua', 'Literasi', 'Numerasi', 'Kreativitas', 'Logika', 'Pengetahuan']
const activeCategory = ref('Semua')

const filteredGames = ref([...games.value])

const filterByCategory = (category) => {
  activeCategory.value = category
  if (category === 'Semua') {
    filteredGames.value = [...games.value]
  } else {
    filteredGames.value = games.value.filter(item => item.category === category)
  }
}
</script>

<template>
  <div>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-orange-500 to-pink-600 text-white py-16">
      <div class="section-container text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">🎮 Permainan Edukatif</h1>
        <p class="text-lg text-orange-100 max-w-2xl mx-auto">
          Koleksi permainan interaktif yang menyenangkan sekaligus mendidik untuk anak usia dini.
        </p>
      </div>
    </section>

    <!-- Filter -->
    <section class="py-8 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
      <div class="section-container">
        <div class="flex flex-wrap gap-3 justify-center">
          <button
            v-for="category in categories"
            :key="category"
            @click="filterByCategory(category)"
            class="px-6 py-2 rounded-full text-sm font-medium transition-all duration-300"
            :class="activeCategory === category 
              ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' 
              : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-orange-50 dark:hover:bg-gray-600'"
          >
            {{ category }}
          </button>
        </div>
      </div>
    </section>

    <!-- Games Grid -->
    <section class="py-16 bg-white dark:bg-gray-900">
      <div class="section-container">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          <div 
            v-for="game in filteredGames" 
            :key="game.id"
            class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700"
          >
            <!-- Thumbnail -->
            <div class="relative overflow-hidden">
              <img 
                :src="game.thumbnail" 
                :alt="game.title"
                class="w-full aspect-video object-cover transition-transform duration-500 group-hover:scale-110"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                <a 
                  :href="game.playUrl"
                  class="px-6 py-3 bg-white text-orange-600 font-bold rounded-full shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300"
                >
                  ▶ Main Sekarang
                </a>
              </div>
              <div class="absolute top-4 left-4">
                <span class="px-3 py-1 bg-orange-500 text-white text-xs font-semibold rounded-full">
                  {{ game.category }}
                </span>
              </div>
            </div>

            <!-- Content -->
            <div class="p-6">
              <h3 class="font-bold text-xl text-gray-900 dark:text-white mb-2">
                {{ game.title }}
              </h3>
              <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                {{ game.description }}
              </p>
              <div class="flex items-center justify-between">
                <span class="inline-flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                  {{ game.ageRange }}
                </span>
                <a 
                  :href="game.playUrl"
                  class="inline-flex items-center gap-2 text-orange-600 hover:text-orange-700 font-medium text-sm transition-colors"
                >
                  Main
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="filteredGames.length === 0" class="text-center py-16">
          <span class="text-6xl mb-4 block">🎮</span>
          <p class="text-gray-500 dark:text-gray-400">Tidak ada permainan dalam kategori ini.</p>
        </div>
      </div>
    </section>
  </div>
</template>
