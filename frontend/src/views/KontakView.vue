<script setup>
import { ref, computed, onMounted } from 'vue'
import { settingsService } from '@/services'
import ProfilSidebar from '@/components/layout/ProfilSidebar.vue'

const contactForm = ref({
  nama: '',
  email: '',
  telepon: '',
  subjek: '',
  pesan: ''
})

const contactInfo = ref({
  address: 'Jl. Pawiyatan No.11, Bubutan, Kec. Bubutan, Surabaya, Jawa Timur 60174',
  phone: '081360004911',
  email: 'bundapaudsby@gmail.com',
  hours: 'Senin - Jumat: 07.30 - 16.30 WIB',
  googleMapsUrl: 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.5123456789!2d112.7315!3d-7.2900!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMTcnMjQuMCJTIDExMsKwNDMnNTMuNCJF!5e0!3m2!1sid!2sid!4v1234567890'
})

const settings = ref({})

const socialMedia = computed(() => {
  const items = []
  if (settings.value.instagram) items.push({ name: 'Instagram', icon: 'instagram', url: settings.value.instagram })
  if (settings.value.facebook) items.push({ name: 'Facebook', icon: 'facebook', url: settings.value.facebook })
  if (settings.value.youtube) items.push({ name: 'YouTube', icon: 'youtube', url: settings.value.youtube })
  if (settings.value.tiktok) items.push({ name: 'TikTok', icon: 'tiktok', url: settings.value.tiktok })
  if (settings.value.twitter) items.push({ name: 'Twitter/X', icon: 'twitter', url: settings.value.twitter })
  // Default if none set
  if (items.length === 0) {
    items.push({ name: 'Instagram', icon: 'instagram', url: '#' })
    items.push({ name: 'Facebook', icon: 'facebook', url: '#' })
    items.push({ name: 'YouTube', icon: 'youtube', url: '#' })
  }
  return items
})

// Extract src URL from iframe HTML
const extractedMapSrc = computed(() => {
  const html = contactInfo.value.googleMapsUrl
  if (!html) return null
  
  // If it's already a direct URL, return it
  if (html.startsWith('https://www.google.com/maps/embed')) {
    return html
  }
  
  // Extract src from iframe HTML
  const srcMatch = html.match(/src=["']([^"']+)["']/)
  if (srcMatch && srcMatch[1]) {
    return srcMatch[1]
  }
  
  return null
})

onMounted(() => {
  // Load settings from localStorage
  const data = settingsService.get()
  settings.value = data
  contactInfo.value = {
    address: data.address || contactInfo.value.address,
    phone: data.phone || contactInfo.value.phone,
    email: data.email || contactInfo.value.email,
    hours: data.hours || contactInfo.value.hours,
    googleMapsUrl: data.googleMapsUrl || contactInfo.value.googleMapsUrl
  }
})

const submitForm = () => {
  alert('Pesan berhasil dikirim! Kami akan menghubungi Anda segera.')
  contactForm.value = { nama: '', email: '', telepon: '', subjek: '', pesan: '' }
}
</script>

<template>
  <div>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-primary-600 to-primary-800 text-white py-16">
      <div class="section-container text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Kontak Kami</h1>
        <p class="text-lg text-blue-100 max-w-2xl mx-auto">
          Hubungi kami untuk informasi lebih lanjut tentang program dan layanan Bunda PAUD Kota Surabaya.
        </p>
      </div>
    </section>

    <!-- Contact Section -->
    <section class="py-16 bg-white dark:bg-gray-900">
      <div class="section-container">
        <div class="grid lg:grid-cols-4 gap-8">
          <!-- Sidebar -->
          <div class="lg:col-span-1">
            <ProfilSidebar />
          </div>

          <!-- Contact Info -->
          <div class="lg:col-span-1">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Informasi Kontak</h2>
            
            <div class="space-y-6">
              <!-- Address -->
              <div class="flex gap-4">
                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 dark:text-white">Alamat</h3>
                  <p class="text-gray-600 dark:text-gray-400">{{ contactInfo.address }}</p>
                </div>
              </div>

              <!-- Phone -->
              <div class="flex gap-4">
                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 dark:text-white">Telepon</h3>
                  <p class="text-gray-600 dark:text-gray-400">{{ contactInfo.phone }}</p>
                </div>
              </div>

              <!-- Email -->
              <div class="flex gap-4">
                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 dark:text-white">Email</h3>
                  <a :href="`mailto:${contactInfo.email}`" class="text-primary-600 dark:text-primary-400 hover:underline">
                    {{ contactInfo.email }}
                  </a>
                </div>
              </div>

              <!-- Hours -->
              <div class="flex gap-4">
                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Jam Layanan</h3>
                  <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                    <div>
                      <p class="font-medium text-gray-800 dark:text-gray-200">Senin - Kamis</p>
                      <p>Waktu: 07:30 - 16:00 WIB</p>
                      <p class="text-xs text-gray-500">Istirahat: 12:00 - 13:00 WIB</p>
                    </div>
                    <div>
                      <p class="font-medium text-gray-800 dark:text-gray-200">Jumat</p>
                      <p>Waktu: 07:00 - 16:30 WIB</p>
                      <p class="text-xs text-gray-500">Istirahat: 11:30 - 13:00 WIB</p>
                    </div>
                    <div>
                      <p class="text-red-500 font-medium">Sabtu & Minggu: Libur</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Social Media -->
            <div class="mt-8">
              <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Ikuti Kami</h3>
              <div class="flex gap-3">
                <a 
                  v-for="social in socialMedia"
                  :key="social.name"
                  :href="social.url"
                  target="_blank"
                  class="w-10 h-10 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center text-gray-600 dark:text-gray-400 hover:bg-primary-600 hover:text-white transition-colors"
                >
                  <svg v-if="social.icon === 'instagram'" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                  </svg>
                  <svg v-if="social.icon === 'facebook'" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                  </svg>
                  <svg v-if="social.icon === 'youtube'" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                  </svg>
                  <!-- TikTok -->
                  <svg v-if="social.icon === 'tiktok'" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                  </svg>
                  <!-- Twitter/X -->
                  <svg v-if="social.icon === 'twitter'" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                  </svg>
                </a>
              </div>
            </div>
          </div>

          <!-- Contact Form -->
          <div class="lg:col-span-2">
            <div class="card p-6 lg:p-8">
              <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Kirim Pesan</h2>
              <form @submit.prevent="submitForm" class="space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap *</label>
                    <input 
                      v-model="contactForm.nama"
                      type="text" 
                      required
                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                      placeholder="Masukkan nama lengkap"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email *</label>
                    <input 
                      v-model="contactForm.email"
                      type="email" 
                      required
                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                      placeholder="email@example.com"
                    />
                  </div>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telepon</label>
                    <input 
                      v-model="contactForm.telepon"
                      type="tel"
                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                      placeholder="08xxxxxxxxxx"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subjek *</label>
                    <input 
                      v-model="contactForm.subjek"
                      type="text" 
                      required
                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                      placeholder="Subjek pesan"
                    />
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pesan *</label>
                  <textarea 
                    v-model="contactForm.pesan"
                    rows="5" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors resize-none"
                    placeholder="Tulis pesan Anda di sini..."
                  ></textarea>
                </div>
                <button type="submit" class="w-full btn-primary py-4">
                  Kirim Pesan
                </button>
              </form>
            </div>
          </div>
        </div>

        <!-- Map -->
        <div class="mt-12">
          <div class="card overflow-hidden">
            <iframe 
              v-if="extractedMapSrc"
              :src="extractedMapSrc"
              width="100%" 
              height="400" 
              style="border:0;" 
              allowfullscreen="" 
              loading="lazy"
              class="w-full"
            ></iframe>
            <div v-else class="h-64 flex items-center justify-center bg-gray-100 dark:bg-gray-800">
              <p class="text-gray-500">Peta tidak tersedia. Silakan atur Google Maps di admin.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
