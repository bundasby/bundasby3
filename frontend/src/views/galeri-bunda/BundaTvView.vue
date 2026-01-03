<script setup>
import { ref } from 'vue'

const videos = ref([
  {
    id: 1,
    title: 'Lagu Anak: Bersih Itu Sehat',
    category: 'Lagu Anak',
    youtubeId: 'dQw4w9WgXcQ', // Ganti dengan ID YouTube asli
    views: '12.5K',
    date: '20 Des 2025'
  },
  {
    id: 2,
    title: 'Tutorial: Membuat Origami Burung',
    category: 'Tutorial',
    youtubeId: 'dQw4w9WgXcQ', // Ganti dengan ID YouTube asli
    views: '8.3K',
    date: '18 Des 2025'
  },
  {
    id: 3,
    title: 'Cerita Dongeng: Si Kancil dan Buaya',
    category: 'Dongeng',
    youtubeId: 'dQw4w9WgXcQ', // Ganti dengan ID YouTube asli
    views: '25.1K',
    date: '15 Des 2025'
  },
  {
    id: 4,
    title: 'Belajar Menghitung 1-10',
    category: 'Edukasi',
    youtubeId: 'dQw4w9WgXcQ', // Ganti dengan ID YouTube asli
    views: '18.7K',
    date: '12 Des 2025'
  },
  {
    id: 5,
    title: 'Senam Ceria Bersama Bunda PAUD',
    category: 'Senam',
    youtubeId: 'dQw4w9WgXcQ', // Ganti dengan ID YouTube asli
    views: '32.4K',
    date: '10 Des 2025'
  },
  {
    id: 6,
    title: 'Mengenal Huruf ABC',
    category: 'Edukasi',
    youtubeId: 'dQw4w9WgXcQ', // Ganti dengan ID YouTube asli
    views: '21.9K',
    date: '8 Des 2025'
  }
])

const featuredVideo = ref(videos.value[0])

const categories = ['Semua', 'Lagu Anak', 'Tutorial', 'Dongeng', 'Edukasi', 'Senam']
const activeCategory = ref('Semua')

const filteredVideos = ref([...videos.value])

const filterByCategory = (category) => {
  activeCategory.value = category
  if (category === 'Semua') {
    filteredVideos.value = [...videos.value]
  } else {
    filteredVideos.value = videos.value.filter(item => item.category === category)
  }
}

const selectVideo = (video) => {
  featuredVideo.value = video
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const getYoutubeThumbnail = (youtubeId) => {
  return `https://img.youtube.com/vi/${youtubeId}/mqdefault.jpg`
}
</script>

<template>
  <div>
    <!-- Hero with Featured Video -->
    <section class="bg-gradient-to-br from-red-600 to-rose-700 text-white py-8">
      <div class="section-container">
        <div class="text-center mb-8">
          <h1 class="text-4xl md:text-5xl font-bold mb-4">📺 Bunda TV</h1>
          <p class="text-lg text-red-100 max-w-2xl mx-auto">
            Tayangan edukatif dan menghibur untuk anak usia dini.
          </p>
        </div>

        <!-- Featured Video Player (YouTube Embed) -->
        <div class="max-w-4xl mx-auto">
          <div class="relative bg-black rounded-2xl overflow-hidden shadow-2xl">
            <div class="aspect-video">
              <iframe
                :src="`https://www.youtube.com/embed/${featuredVideo.youtubeId}?rel=0`"
                :title="featuredVideo.title"
                class="w-full h-full"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen
              ></iframe>
            </div>
          </div>
          <div class="mt-4 px-2">
            <h2 class="text-xl font-bold">{{ featuredVideo.title }}</h2>
            <div class="flex items-center gap-4 mt-2 text-red-200 text-sm">
              <span>{{ featuredVideo.views }} views</span>
              <span>•</span>
              <span>{{ featuredVideo.date }}</span>
              <span>•</span>
              <span class="px-2 py-0.5 bg-white/20 rounded-full">{{ featuredVideo.category }}</span>
            </div>
          </div>
        </div>
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
              ? 'bg-red-600 text-white shadow-lg shadow-red-500/30' 
              : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-gray-600'"
          >
            {{ category }}
          </button>
        </div>
      </div>
    </section>

    <!-- Videos Grid -->
    <section class="py-16 bg-white dark:bg-gray-900">
      <div class="section-container">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Semua Video</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="video in filteredVideos" 
            :key="video.id"
            @click="selectVideo(video)"
            class="group cursor-pointer"
          >
            <!-- Thumbnail -->
            <div class="relative overflow-hidden rounded-xl">
              <img 
                :src="getYoutubeThumbnail(video.youtubeId)" 
                :alt="video.title"
                class="w-full aspect-video object-cover transition-transform duration-500 group-hover:scale-110"
              />
              <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                <div class="w-14 h-14 bg-red-600 rounded-full flex items-center justify-center shadow-lg">
                  <svg class="w-7 h-7 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z" />
                  </svg>
                </div>
              </div>
              <!-- YouTube Badge -->
              <div class="absolute bottom-2 right-2 px-2 py-1 bg-red-600 text-white text-xs font-semibold rounded flex items-center gap-1">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                </svg>
                YouTube
              </div>
            </div>

            <!-- Info -->
            <div class="mt-3">
              <h3 class="font-semibold text-gray-900 dark:text-white line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">
                {{ video.title }}
              </h3>
              <div class="flex items-center gap-2 mt-2 text-sm text-gray-500 dark:text-gray-400">
                <span>{{ video.views }} views</span>
                <span>•</span>
                <span>{{ video.date }}</span>
              </div>
              <span class="inline-block mt-2 px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs rounded-full">
                {{ video.category }}
              </span>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="filteredVideos.length === 0" class="text-center py-16">
          <span class="text-6xl mb-4 block">📺</span>
          <p class="text-gray-500 dark:text-gray-400">Tidak ada video dalam kategori ini.</p>
        </div>
      </div>
    </section>

    <!-- Info Section -->
    <section class="py-12 bg-gray-50 dark:bg-gray-800">
      <div class="section-container text-center">
        <div class="max-w-2xl mx-auto">
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
            🎬 Tonton Video Edukatif Lainnya
          </h3>
          <p class="text-gray-600 dark:text-gray-300 mb-6">
            Kunjungi channel YouTube resmi Bunda PAUD Kota Surabaya untuk lebih banyak video edukatif.
          </p>
          <a 
            href="https://www.youtube.com/@bundapaudkotasurabaya" 
            target="_blank"
            class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-colors"
          >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
            </svg>
            Subscribe Channel YouTube
          </a>
        </div>
      </div>
    </section>
  </div>
</template>
