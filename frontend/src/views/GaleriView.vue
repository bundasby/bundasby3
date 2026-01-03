<script setup>
import { ref } from 'vue'

const selectedImage = ref(null)

const galleries = ref([
  {
    id: 1,
    title: 'Konferensi Bunda PAUD 2025',
    images: [
      { id: 1, url: 'https://placehold.co/600x400/2563eb/ffffff?text=Konferensi+1', caption: 'Pembukaan acara' },
      { id: 2, url: 'https://placehold.co/600x400/2563eb/ffffff?text=Konferensi+2', caption: 'Sambutan Bunda PAUD' },
      { id: 3, url: 'https://placehold.co/600x400/2563eb/ffffff?text=Konferensi+3', caption: 'Peserta konferensi' }
    ]
  },
  {
    id: 2,
    title: 'Pelatihan Guru PAUD',
    images: [
      { id: 4, url: 'https://placehold.co/600x400/16a34a/ffffff?text=Pelatihan+1', caption: 'Sesi workshop' },
      { id: 5, url: 'https://placehold.co/600x400/16a34a/ffffff?text=Pelatihan+2', caption: 'Praktik mengajar' }
    ]
  },
  {
    id: 3,
    title: 'Program Makanan Bergizi',
    images: [
      { id: 6, url: 'https://placehold.co/600x400/f59e0b/ffffff?text=MBG+1', caption: 'Pembagian makanan' },
      { id: 7, url: 'https://placehold.co/600x400/f59e0b/ffffff?text=MBG+2', caption: 'Anak-anak makan bersama' },
      { id: 8, url: 'https://placehold.co/600x400/f59e0b/ffffff?text=MBG+3', caption: 'Menu makanan bergizi' },
      { id: 9, url: 'https://placehold.co/600x400/f59e0b/ffffff?text=MBG+4', caption: 'Monitoring gizi' }
    ]
  },
  {
    id: 4,
    title: 'Kunjungan ke Lembaga PAUD',
    images: [
      { id: 10, url: 'https://placehold.co/600x400/8b5cf6/ffffff?text=Kunjungan+1', caption: 'Kunjungan PAUD Melati' },
      { id: 11, url: 'https://placehold.co/600x400/8b5cf6/ffffff?text=Kunjungan+2', caption: 'Interaksi dengan siswa' }
    ]
  }
])

const allImages = ref([])
galleries.value.forEach(gallery => {
  gallery.images.forEach(img => {
    allImages.value.push({ ...img, category: gallery.title })
  })
})

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
    <section class="bg-gradient-to-br from-primary-600 to-primary-800 text-white py-16">
      <div class="section-container text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Galeri</h1>
        <p class="text-lg text-blue-100 max-w-2xl mx-auto">
          Dokumentasi kegiatan Bunda PAUD Kota Surabaya.
        </p>
      </div>
    </section>

    <!-- Gallery Grid -->
    <section class="py-16 bg-white dark:bg-gray-900">
      <div class="section-container">
        <div v-for="gallery in galleries" :key="gallery.id" class="mb-12">
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ gallery.title }}</h2>
          <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <div 
              v-for="image in gallery.images" 
              :key="image.id"
              @click="openLightbox(image)"
              class="relative group cursor-pointer overflow-hidden rounded-xl aspect-square"
            >
              <img 
                :src="image.url" 
                :alt="image.caption"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
              />
              <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                <p class="text-white text-sm">{{ image.caption }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Lightbox -->
    <div 
      v-if="selectedImage"
      @click="closeLightbox"
      class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
    >
      <button 
        @click="closeLightbox"
        class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors"
      >
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
      <div @click.stop class="max-w-4xl w-full">
        <img 
          :src="selectedImage.url" 
          :alt="selectedImage.caption"
          class="w-full rounded-lg"
        />
        <p class="text-white text-center mt-4">{{ selectedImage.caption }}</p>
      </div>
    </div>
  </div>
</template>
