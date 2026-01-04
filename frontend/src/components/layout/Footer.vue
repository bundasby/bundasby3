<script setup>
import { ref, computed, onMounted } from 'vue'
import { settingsService } from '@/services'

const currentYear = new Date().getFullYear()

const quickLinks = [
  { name: 'Beranda', path: '/' },
  { name: 'Profil', path: '/profil' },
  { name: 'Program Kerja', path: '/program' },
  { name: 'Berita', path: '/berita' },
  { name: 'Galeri', path: '/galeri' },
  { name: 'Kontak', path: '/kontak' }
]

const layanan = [
  { name: 'SIBUNDA', url: 'https://sibunda.surabaya.go.id' },
  { name: 'PAUDPEDIA', url: 'https://paudpedia.kemdikbud.go.id' },
  { name: 'Data Stunting', path: '/layanan#stunting' },
  { name: 'Pengaduan', path: '/layanan#pengaduan' }
]

const contactInfo = ref({
  address: 'Jl. Ketintang, Surabaya, Jawa Timur 60231',
  phone: '(031) 8281xxx',
  email: 'bundapaud@surabaya.go.id'
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

onMounted(() => {
  // Load settings from localStorage
  const data = settingsService.get()
  settings.value = data
  contactInfo.value = {
    address: data.address || contactInfo.value.address,
    phone: data.phone || contactInfo.value.phone,
    email: data.email || contactInfo.value.email
  }
})
</script>

<template>
  <footer class="bg-gray-900 text-gray-300">
    <!-- Main Footer -->
    <div class="section-container py-12 lg:py-16">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
        <!-- Brand -->
        <div class="lg:col-span-1">
          <div class="flex items-center gap-3 mb-4">
            <img 
              src="/logo-bunda-paud.png" 
              alt="Logo Bunda PAUD" 
              class="h-12 w-auto"
              onerror="this.src='https://placehold.co/48x48/2563eb/ffffff?text=BP'"
            />
            <div>
              <h3 class="text-lg font-bold text-white">Bunda PAUD</h3>
              <p class="text-sm text-gray-400">Kota Surabaya</p>
            </div>
          </div>
          <p class="text-sm text-gray-400 mb-4">
            Bunda PAUD Kota Surabaya berkomitmen untuk mewujudkan pendidikan anak usia dini yang berkualitas, 
            inklusif, dan ramah anak.
          </p>
          
          <!-- Social Media -->
          <div class="flex gap-3">
            <a 
              v-for="social in socialMedia"
              :key="social.name"
              :href="social.url"
              :title="social.name"
              target="_blank"
              class="p-2 bg-gray-800 rounded-lg hover:bg-primary-600 transition-colors"
            >
              <!-- Instagram -->
              <svg v-if="social.icon === 'instagram'" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
              </svg>
              <!-- Facebook -->
              <svg v-if="social.icon === 'facebook'" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
              </svg>
              <!-- YouTube -->
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

        <!-- Quick Links -->
        <div>
          <h4 class="text-white font-semibold mb-4">Tautan Cepat</h4>
          <ul class="space-y-2">
            <li v-for="link in quickLinks" :key="link.name">
              <router-link 
                :to="link.path"
                class="text-sm hover:text-primary-400 transition-colors"
              >
                {{ link.name }}
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Layanan -->
        <div>
          <h4 class="text-white font-semibold mb-4">Layanan</h4>
          <ul class="space-y-2">
            <li v-for="item in layanan" :key="item.name">
              <a 
                v-if="item.url"
                :href="item.url"
                target="_blank"
                class="text-sm hover:text-primary-400 transition-colors"
              >
                {{ item.name }}
              </a>
              <router-link 
                v-else
                :to="item.path"
                class="text-sm hover:text-primary-400 transition-colors"
              >
                {{ item.name }}
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Contact -->
        <div>
          <h4 class="text-white font-semibold mb-4">Kontak</h4>
          <ul class="space-y-3">
            <li class="flex gap-3 text-sm">
              <svg class="w-5 h-5 text-primary-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span>{{ contactInfo.address }}</span>
            </li>
            <li class="flex gap-3 text-sm">
              <svg class="w-5 h-5 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              <span>{{ contactInfo.phone }}</span>
            </li>
            <li class="flex gap-3 text-sm">
              <svg class="w-5 h-5 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              <a :href="`mailto:${contactInfo.email}`" class="hover:text-primary-400 transition-colors">
                {{ contactInfo.email }}
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-gray-800">
      <div class="section-container py-4 flex flex-col sm:flex-row justify-between items-center gap-2">
        <p class="text-sm text-gray-500">
          © {{ currentYear }} Bunda PAUD Kota Surabaya. Hak Cipta Dilindungi.
        </p>
        <p class="text-sm text-gray-500">
          Terintegrasi dengan <a href="https://sibunda.surabaya.go.id" target="_blank" class="text-primary-400 hover:underline">SIBUNDA</a>
        </p>
      </div>
    </div>
  </footer>
</template>
