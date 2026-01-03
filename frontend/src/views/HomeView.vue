<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAppStore } from '@/stores/app'
import { useSliderStore } from '@/stores/slider'
import BundaPaudSlider from '@/components/home/BundaPaudSlider.vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Navigation, Pagination } from 'swiper/modules'
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'

const router = useRouter()
const appStore = useAppStore()
const sliderStore = useSliderStore()
const searchQuery = ref('')

// Handle search
const handleSearch = () => {
  if (searchQuery.value.trim()) {
    router.push({ path: '/berita', query: { search: searchQuery.value.trim() } })
  }
}

// Scroll to top helper
const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

// Hero Slider - using store
const currentSlide = ref(0)
const slides = computed(() => sliderStore.getActiveSlides())

const nextSlide = () => {
  if (slides.value.length > 0) {
    currentSlide.value = (currentSlide.value + 1) % slides.value.length
  }
}

const prevSlide = () => {
  if (slides.value.length > 0) {
    currentSlide.value = (currentSlide.value - 1 + slides.value.length) % slides.value.length
  }
}

// Auto slide
let slideInterval = null
onMounted(async () => {
  // Fetch slides from API
  await sliderStore.fetchActiveSlides()
  slideInterval = setInterval(nextSlide, 5000)
})

onUnmounted(() => {
  if (slideInterval) clearInterval(slideInterval)
})

// Animated statistics
const stats = ref([
  { label: 'Lembaga PAUD', value: 850, icon: 'building', suffix: '+' },
  { label: 'Guru PAUD', value: 3200, icon: 'users', suffix: '+' },
  { label: 'Peserta Didik', value: 45000, icon: 'children', suffix: '+' },
  { label: 'Kecamatan', value: 31, icon: 'location', suffix: '' }
])

// Program Kerja - 6 Program Prioritas Kemendikdasmen 2026
const programs = ref([
  {
    id: 1,
    title: 'Penguatan Pendidikan Karakter',
    description: 'Penanaman karakter 7 kebiasaan anak Indonesia hebat melalui pendidikan nilai dan bimbingan konseling.',
    icon: 'heart',
    image: 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=600&h=400&fit=crop'
  },
  {
    id: 2,
    title: 'Wajib Belajar 13 Tahun',
    description: 'Pemerataan kesempatan pendidikan termasuk PAUD, pendidikan jarak jauh, dan fasilitasi relawan mengajar.',
    icon: 'school',
    image: 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=600&h=400&fit=crop'
  },
  {
    id: 3,
    title: 'Peningkatan Kompetensi Guru',
    description: 'Peningkatan kualifikasi pendidikan guru minimal D-IV/S-1 serta pelatihan dan sertifikasi guru PAUD.',
    icon: 'users',
    image: 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=600&h=400&fit=crop'
  },
  {
    id: 4,
    title: 'Literasi, Numerasi & Sains',
    description: 'Penguatan pendidikan matematika, sains, dan teknologi sejak usia dini serta pengembangan sekolah unggul.',
    icon: 'book',
    image: 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&h=400&fit=crop'
  },
  {
    id: 5,
    title: 'Sarana dan Prasarana',
    description: 'Pemenuhan dan perbaikan fasilitas sekolah melalui program renovasi dan revitalisasi satuan pendidikan.',
    icon: 'building',
    image: 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=600&h=400&fit=crop'
  },
  {
    id: 6,
    title: 'Bahasa dan Sastra',
    description: 'Pemartabatan bahasa negara, pelindungan bahasa daerah, dan peningkatan literasi anak usia dini.',
    icon: 'language',
    image: 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=600&h=400&fit=crop'
  }
])

// Import articles from localStorage
import { articlesStorage, initializeData } from '@/services/localStorage'

// News categories for filter tabs
const newsCategories = ref([
  { key: null, label: 'SEMUA' },
  { key: 'berita', label: 'BERITA' },
  { key: 'pengumuman', label: 'PENGUMUMAN' },
  { key: 'kegiatan', label: 'KEGIATAN' },
  { key: 'edukasi', label: 'EDUKASI' }
])

// Selected category filter
const selectedCategory = ref(null)

// Berita Terbaru - fetch from localStorage
const allNews = ref([])

const loadNews = () => {
  initializeData() // Ensure data is initialized
  const articles = articlesStorage.getAll({ published: true })
  // Map articles to news format
  allNews.value = articles.map(article => ({
    id: article.id,
    slug: article.slug,
    title: article.title,
    excerpt: article.excerpt,
    thumbnail: article.image || 'https://placehold.co/400x600/1e3a5f/ffffff?text=Berita',
    category: article.category,
    date: article.publishedAt
  }))
}

// Computed: filtered news based on selected category
const latestNews = computed(() => {
  if (!selectedCategory.value) {
    return allNews.value
  }
  return allNews.value.filter(news => news.category === selectedCategory.value)
})

// Filter by category
const filterByCategory = (categoryKey) => {
  selectedCategory.value = categoryKey
}

// Load news on mount
onMounted(() => {
  loadNews()
})

// Counter animation
const animatedValues = ref([0, 0, 0, 0])

onMounted(() => {
  // Animate counters
  stats.value.forEach((stat, index) => {
    const duration = 2000
    const steps = 60
    const increment = stat.value / steps
    let current = 0
    const timer = setInterval(() => {
      current += increment
      if (current >= stat.value) {
        animatedValues.value[index] = stat.value
        clearInterval(timer)
      } else {
        animatedValues.value[index] = Math.floor(current)
      }
    }, duration / steps)
  })
})

const formatNumber = (num) => {
  return num.toLocaleString('id-ID')
}
</script>

<template>
  <div>
    <!-- Hero Section - Clean Image Slider -->
    <section class="bg-gray-100 dark:bg-gray-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 lg:py-6">
        <!-- Image Only Slider -->
        <div class="relative rounded-2xl overflow-hidden shadow-lg">
          <!-- Main Slide Area -->
          <div class="relative h-[300px] md:h-[400px] lg:h-[450px]">
            <transition name="fade" mode="out-in">
              <img 
                v-if="slides.length > 0 && slides[currentSlide]"
                :key="currentSlide"
                :src="slides[currentSlide].image" 
                :alt="slides[currentSlide].title"
                class="w-full h-full object-cover"
                @error="$event.target.src = 'https://placehold.co/1200x450/2e86c1/ffffff?text=Bunda+PAUD+Kota+Surabaya'"
              />
              <div v-else class="w-full h-full bg-gradient-to-r from-primary-600 to-primary-400 flex items-center justify-center">
                <span class="text-white text-xl">Memuat slider...</span>
              </div>
            </transition>
            
            <!-- Subtle Navigation Arrows - Only visible on hover -->
            <button 
              @click="prevSlide" 
              class="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-black/20 hover:bg-black/40 rounded-full flex items-center justify-center transition-all opacity-0 hover:opacity-100 group-hover:opacity-100 z-10"
              style="opacity: 0.3;"
            >
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            <button 
              @click="nextSlide" 
              class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-black/20 hover:bg-black/40 rounded-full flex items-center justify-center transition-all opacity-0 hover:opacity-100 group-hover:opacity-100 z-10"
              style="opacity: 0.3;"
            >
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
            
            <!-- Subtle Dots Indicator -->
            <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 z-10">
              <button 
                v-for="(slide, index) in slides" 
                :key="index"
                @click="currentSlide = index"
                class="w-2 h-2 rounded-full transition-all"
                :class="currentSlide === index ? 'bg-white/90 w-5' : 'bg-white/40'"
              ></button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Quick Links Section - Icon Style -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800">
      <div class="section-container">
        <div class="text-center mb-12">
          <h2 class="section-title">Layanan Kami</h2>
          <p class="section-subtitle">Akses cepat ke berbagai layanan Bunda PAUD</p>
        </div>
        
        <div class="grid grid-cols-3 md:grid-cols-5 lg:grid-cols-9 gap-4 lg:gap-6">
          <!-- 1. Profil Bunda PAUD -->
          <router-link to="/profil/tentang" class="group flex flex-col items-center">
            <div class="w-20 h-20 lg:w-24 lg:h-24 rounded-full bg-gradient-to-br from-pink-100 to-pink-200 dark:from-pink-900/30 dark:to-pink-800/30 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:shadow-xl transition-all duration-300 border-4 border-white dark:border-gray-700 shadow-lg">
              <div class="w-14 h-14 lg:w-16 lg:h-16 rounded-full bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center shadow-inner">
                <svg class="w-7 h-7 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
            <span class="text-xs lg:text-sm font-semibold text-gray-700 dark:text-gray-300 text-center leading-tight">PROFIL<br>BUNDA PAUD</span>
          </router-link>

          <!-- 2. Mitra PAUD -->
          <router-link to="/mitra-paud" class="group flex flex-col items-center">
            <div class="w-20 h-20 lg:w-24 lg:h-24 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:shadow-xl transition-all duration-300 border-4 border-white dark:border-gray-700 shadow-lg">
              <div class="w-14 h-14 lg:w-16 lg:h-16 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-inner">
                <svg class="w-7 h-7 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
            </div>
            <span class="text-xs lg:text-sm font-semibold text-gray-700 dark:text-gray-300 text-center leading-tight">MITRA<br>PAUD</span>
          </router-link>

          <!-- 3. Konsultasi PAUD HI -->
          <router-link to="/layanan/konsultasi-paud-hi" class="group flex flex-col items-center">
            <div class="w-20 h-20 lg:w-24 lg:h-24 rounded-full bg-gradient-to-br from-teal-100 to-teal-200 dark:from-teal-900/30 dark:to-teal-800/30 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:shadow-xl transition-all duration-300 border-4 border-white dark:border-gray-700 shadow-lg">
              <div class="w-14 h-14 lg:w-16 lg:h-16 rounded-full bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center shadow-inner">
                <svg class="w-7 h-7 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
              </div>
            </div>
            <span class="text-xs lg:text-sm font-semibold text-gray-700 dark:text-gray-300 text-center leading-tight">KONSULTASI<br>PAUD HI</span>
          </router-link>

          <!-- 4. Telekonsultasi -->
          <a href="https://wa.me/6281234567890" target="_blank" class="group flex flex-col items-center">
            <div class="w-20 h-20 lg:w-24 lg:h-24 rounded-full bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/30 dark:to-green-800/30 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:shadow-xl transition-all duration-300 border-4 border-white dark:border-gray-700 shadow-lg">
              <div class="w-14 h-14 lg:w-16 lg:h-16 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-inner">
                <svg class="w-7 h-7 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
              </div>
            </div>
            <span class="text-xs lg:text-sm font-semibold text-gray-700 dark:text-gray-300 text-center leading-tight">TELE<br>KONSULTASI</span>
          </a>

          <!-- 5. Peminjaman Grha PAUD -->
          <router-link to="/layanan/peminjaman-gedung" class="group flex flex-col items-center">
            <div class="w-20 h-20 lg:w-24 lg:h-24 rounded-full bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-900/30 dark:to-amber-800/30 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:shadow-xl transition-all duration-300 border-4 border-white dark:border-gray-700 shadow-lg">
              <div class="w-14 h-14 lg:w-16 lg:h-16 rounded-full bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-inner">
                <svg class="w-7 h-7 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
              </div>
            </div>
            <span class="text-xs lg:text-sm font-semibold text-gray-700 dark:text-gray-300 text-center leading-tight">PEMINJAMAN<br>GRHA PAUD</span>
          </router-link>

          <!-- 6. Permainan Edukatif -->
          <router-link to="/galeri-bunda/permainan" class="group flex flex-col items-center">
            <div class="w-20 h-20 lg:w-24 lg:h-24 rounded-full bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900/30 dark:to-purple-800/30 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:shadow-xl transition-all duration-300 border-4 border-white dark:border-gray-700 shadow-lg">
              <div class="w-14 h-14 lg:w-16 lg:h-16 rounded-full bg-gradient-to-br from-purple-500 to-violet-600 flex items-center justify-center shadow-inner">
                <svg class="w-7 h-7 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                </svg>
              </div>
            </div>
            <span class="text-xs lg:text-sm font-semibold text-gray-700 dark:text-gray-300 text-center leading-tight">PERMAINAN<br>EDUKATIF</span>
          </router-link>

          <!-- 7. KIE PAUD -->
          <router-link to="/galeri-bunda/infografis" class="group flex flex-col items-center">
            <div class="w-20 h-20 lg:w-24 lg:h-24 rounded-full bg-gradient-to-br from-cyan-100 to-cyan-200 dark:from-cyan-900/30 dark:to-cyan-800/30 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:shadow-xl transition-all duration-300 border-4 border-white dark:border-gray-700 shadow-lg">
              <div class="w-14 h-14 lg:w-16 lg:h-16 rounded-full bg-gradient-to-br from-cyan-500 to-sky-600 flex items-center justify-center shadow-inner">
                <svg class="w-7 h-7 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
            </div>
            <span class="text-xs lg:text-sm font-semibold text-gray-700 dark:text-gray-300 text-center leading-tight">KIE<br>PAUD</span>
          </router-link>

          <!-- 8. Bunda TV -->
          <router-link to="/galeri-bunda/bunda-tv" class="group flex flex-col items-center">
            <div class="w-20 h-20 lg:w-24 lg:h-24 rounded-full bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900/30 dark:to-red-800/30 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:shadow-xl transition-all duration-300 border-4 border-white dark:border-gray-700 shadow-lg">
              <div class="w-14 h-14 lg:w-16 lg:h-16 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-inner">
                <svg class="w-7 h-7 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
            <span class="text-xs lg:text-sm font-semibold text-gray-700 dark:text-gray-300 text-center leading-tight">BUNDA<br>TV</span>
          </router-link>

          <!-- 9. Pengaduan -->
          <router-link to="/pengaduan" class="group flex flex-col items-center">
            <div class="w-20 h-20 lg:w-24 lg:h-24 rounded-full bg-gradient-to-br from-rose-100 to-rose-200 dark:from-rose-900/30 dark:to-rose-800/30 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:shadow-xl transition-all duration-300 border-4 border-white dark:border-gray-700 shadow-lg">
              <div class="w-14 h-14 lg:w-16 lg:h-16 rounded-full bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center shadow-inner">
                <svg class="w-7 h-7 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                </svg>
              </div>
            </div>
            <span class="text-xs lg:text-sm font-semibold text-gray-700 dark:text-gray-300 text-center leading-tight">PENGADUAN<br>MASYARAKAT</span>
          </router-link>
        </div>
      </div>
    </section>

    <!-- Program Kerja Section - 6 Program Prioritas -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800">
      <div class="section-container">
        <div class="text-center mb-12">
          <span class="badge badge-primary mb-4">6 Program Prioritas Kemendikdasmen</span>
          <h2 class="section-title">Program Kerja 2026</h2>
          <p class="section-subtitle">Fokus utama Bunda PAUD Kota Surabaya dalam mendukung program nasional</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          <router-link 
            v-for="program in programs" 
            :key="program.id"
            to="/program"
            class="rounded-2xl overflow-hidden text-white card-hover cursor-pointer transition-all duration-300 hover:scale-105 relative group"
            style="min-height: 280px"
          >
            <!-- Background Image -->
            <div 
              class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110"
              :style="{ backgroundImage: `url(${program.image})` }"
            ></div>
            <!-- Dark Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-black/30"></div>
            
            <!-- Content -->
            <div class="relative z-10 p-6 lg:p-8 h-full flex flex-col justify-end">
              <!-- Icon -->
              <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                <!-- Heart Icon - Pendidikan Karakter -->
                <svg v-if="program.icon === 'heart'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <!-- School Icon - Wajib Belajar -->
                <svg v-if="program.icon === 'school'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                </svg>
                <!-- Users Icon - Kompetensi Guru -->
                <svg v-if="program.icon === 'users'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <!-- Book Icon - Literasi -->
                <svg v-if="program.icon === 'book'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <!-- Building Icon - Sarana Prasarana -->
                <svg v-if="program.icon === 'building'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <!-- Language Icon - Bahasa Sastra -->
                <svg v-if="program.icon === 'language'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                </svg>
              </div>
              
              <div class="flex items-start gap-3 mb-2">
                <span class="bg-primary-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">{{ program.id }}</span>
                <h3 class="text-lg font-bold leading-tight drop-shadow-lg">{{ program.title }}</h3>
              </div>
              <p class="text-white/90 text-sm leading-relaxed drop-shadow">{{ program.description }}</p>
            </div>
          </router-link>
        </div>
        
        <!-- View All Button -->
        <div class="text-center mt-10">
          <router-link to="/program" class="btn-primary inline-flex items-center gap-2">
            Lihat Detail Program
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </router-link>
        </div>
      </div>
    </section>

    <!-- Berita Terbaru Section -->
    <section class="py-12 bg-gray-100 dark:bg-gray-800">
      <div class="section-container">
        <!-- Header with Category Tabs -->
        <div class="flex flex-wrap justify-between items-end mb-6 border-b border-gray-300 dark:border-gray-600">
          <!-- Left: Title + Category Tabs -->
          <div class="flex flex-wrap items-end gap-4 lg:gap-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase border-b-2 border-pink-600 pb-2 -mb-px">BERITA TERKINI</h2>
            <div class="hidden md:flex items-center gap-4 lg:gap-6 pb-2 text-sm font-medium">
              <button 
                v-for="cat in newsCategories" 
                :key="cat.key || 'all'"
                @click="filterByCategory(cat.key)"
                class="transition-colors cursor-pointer"
                :class="selectedCategory === cat.key ? 'text-pink-600 font-bold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
              >
                {{ cat.label }}
              </button>
            </div>
          </div>
          <!-- Right: "Lainnya" Button -->
          <router-link to="/berita" class="hidden sm:inline-flex items-center text-pink-600 font-semibold hover:text-pink-700 transition-colors border border-pink-600 px-4 py-1.5 rounded-lg mb-2">
            Lainnya
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </router-link>
        </div>
        
        <swiper
          :modules="[Navigation, Pagination]"
          :slides-per-view="1.2"
          :space-between="16"
          :breakpoints="{
            '480': {
              slidesPerView: 1.5,
              spaceBetween: 16,
            },
            '640': {
              slidesPerView: 2.2,
              spaceBetween: 16,
            },
            '1024': {
              slidesPerView: 3.5,
              spaceBetween: 20,
            },
            '1280': {
              slidesPerView: 4,
              spaceBetween: 20,
            },
          }"
          :navigation="{
            nextEl: '.news-next',
            prevEl: '.news-prev',
          }"
          :pagination="{
            el: '.news-pagination',
            type: 'progressbar',
          }"
          class="news-swiper !pb-8"
        >
          <swiper-slide v-for="news in latestNews" :key="news.id">
            <router-link :to="`/berita/${news.slug}`" class="block">
              <div class="relative h-[320px] lg:h-[380px] rounded-xl overflow-hidden group cursor-pointer shadow-lg hover:shadow-2xl transition-all duration-300">
                <!-- Background Image -->
                <img 
                  :src="news.thumbnail" 
                  :alt="news.title"
                  class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                  onerror="this.src='https://placehold.co/400x600/1e3a5f/ffffff?text=Berita'"
                />
                
                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/40 to-black/20"></div>
                
                <!-- Category Badge (Top Left) - Larger & More Visible -->
                <div class="absolute top-3 left-3 z-10">
                  <span class="px-3 py-1 text-white text-[11px] font-bold uppercase rounded shadow-md"
                        :class="{
                          'bg-pink-600': news.category === 'kegiatan',
                          'bg-blue-600': news.category === 'edukasi',
                          'bg-amber-500': news.category === 'pengumuman',
                          'bg-teal-600': news.category === 'berita',
                          'bg-purple-600': !['kegiatan', 'edukasi', 'pengumuman', 'berita'].includes(news.category)
                        }">
                      {{ news.category || 'LAIN-LAIN' }}
                  </span>
                </div>

                <!-- Content (Bottom) -->
                <div class="absolute bottom-0 left-0 w-full p-4 lg:p-5 text-white z-10">
                  <h3 class="text-base lg:text-lg font-bold leading-snug mb-2 line-clamp-3 group-hover:text-pink-300 transition-colors">
                    {{ news.title }}
                  </h3>
                  <p class="text-[11px] text-gray-300 mb-3">
                     Update Terakhir {{ new Date(news.date).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }) }}
                  </p>
                  
                  <span class="bg-pink-600 hover:bg-pink-500 text-white text-xs font-semibold px-4 py-2 rounded transition-colors inline-block">
                    Selengkapnya ↗
                  </span>
                </div>
              </div>
            </router-link>
          </swiper-slide>
        </swiper>

        <!-- Progress Bar -->
        <div class="news-pagination mt-4 h-1 bg-gray-300 dark:bg-gray-600 rounded-full overflow-hidden"></div>

         <!-- Custom Navigation Arrows (Bottom Right) -->
         <div class="flex justify-end gap-2 mt-4">
            <button class="news-prev w-9 h-9 rounded-full border border-gray-400 flex items-center justify-center hover:bg-pink-100 hover:border-pink-400 text-gray-500 dark:text-gray-400 dark:border-gray-500 dark:hover:bg-gray-700 transition-all cursor-pointer disabled:opacity-40">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </button>
            <button class="news-next w-9 h-9 rounded-full border border-gray-400 flex items-center justify-center hover:bg-pink-100 hover:border-pink-400 text-gray-500 dark:text-gray-400 dark:border-gray-500 dark:hover:bg-gray-700 transition-all cursor-pointer disabled:opacity-40">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
            <button @click="scrollToTop" class="w-9 h-9 rounded-full border border-gray-400 flex items-center justify-center hover:bg-pink-100 hover:border-pink-400 text-pink-600 dark:border-gray-500 dark:hover:bg-gray-700 transition-all cursor-pointer">
                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
            </button>
         </div>

         <!-- Mobile View All Link -->
         <div class="mt-5 text-center sm:hidden">
            <router-link to="/berita" class="text-pink-600 font-semibold hover:underline">
               Lihat Semua Berita &rarr;
            </router-link>
         </div>
      </div>
    </section>

    <!-- Bunda PAUD Slider -->
    <BundaPaudSlider />
  </div>
</template>

<style scoped>
/* Swiper Progress Bar Styling */
.news-pagination.swiper-pagination-progressbar {
  position: relative !important;
  top: auto !important;
  width: 100% !important;
  height: 4px !important;
  background: #d1d5db !important;
  border-radius: 9999px !important;
}

.news-pagination :deep(.swiper-pagination-progressbar-fill) {
  background: linear-gradient(90deg, #db2777, #ec4899) !important;
  border-radius: 9999px !important;
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
  .news-pagination.swiper-pagination-progressbar {
    background: #4b5563 !important;
  }
}
</style>
