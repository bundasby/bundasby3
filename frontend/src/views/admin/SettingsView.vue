<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import { settingsService } from '@/services'

const saving = ref(false)
const loading = ref(true)

const settings = ref({
  siteName: 'Bunda PAUD Kota Surabaya',
  email: 'bundapaud@surabaya.go.id',
  phone: '(031) 5555-1234',
  address: 'Jl. Jimerto No.25-27, Ketabang, Kec. Genteng, Kota Surabaya, Jawa Timur 60272',
  googleMapsUrl: 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.634897788675!2d112.73694!3d-7.2875897!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMTcnMTUuMyJTIDExMsKwNDQnMTMuMCJF!5e0!3m2!1sid!2sid!4v1234567890',
  whatsappNumber: '6281234567890',
  whatsappMessage: 'Halo, saya ingin bertanya tentang program Bunda PAUD Kota Surabaya'
})

onMounted(() => {
  // Load settings from localStorage
  const data = settingsService.get()
  settings.value = { ...settings.value, ...data }
  loading.value = false
})

const saveSettings = () => {
  saving.value = true
  // Save to localStorage
  settingsService.update(settings.value)
  setTimeout(() => {
    saving.value = false
    alert('Pengaturan berhasil disimpan!')
  }, 500)
}

// Extract src URL from iframe HTML
const extractedMapSrc = computed(() => {
  const html = settings.value.googleMapsUrl
  if (!html) return null
  
  // If it's already a URL, return it directly
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
</script>

<template>
  <AdminLayout title="Pengaturan Website">
    <p class="text-gray-600 dark:text-gray-400 -mt-6 mb-6">Kelola pengaturan dan konfigurasi website</p>

    <div class="max-w-2xl space-y-6">
      <!-- General Settings -->
      <div class="card p-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Informasi Umum</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Website</label>
            <input v-model="settings.siteName" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <input v-model="settings.email" type="email" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telepon</label>
            <input v-model="settings.phone" type="tel" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
            <textarea v-model="settings.address" rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"></textarea>
          </div>
        </div>
      </div>

      <!-- WhatsApp Settings -->
      <div class="card p-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
          <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
          </svg>
          Hotline WhatsApp
        </h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor WhatsApp</label>
            <input v-model="settings.whatsappNumber" type="text" placeholder="6281234567890" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
            <p class="text-xs text-gray-500 mt-1">Format: 62xxx (tanpa + atau spasi)</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pesan Default</label>
            <textarea v-model="settings.whatsappMessage" rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"></textarea>
            <p class="text-xs text-gray-500 mt-1">Pesan yang akan muncul otomatis saat user klik tombol WhatsApp</p>
          </div>
        </div>
      </div>

      <!-- Google Maps Settings -->
      <div class="card p-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
          <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 0C7.802 0 4 3.403 4 7.602 4 11.8 7.469 16.812 12 24c4.531-7.188 8-12.2 8-16.398C20 3.403 16.199 0 12 0zm0 11a3 3 0 110-6 3 3 0 010 6z"/>
          </svg>
          Google Maps
        </h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode Embed Google Maps (HTML)</label>
            <textarea v-model="settings.googleMapsUrl" rows="5" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs font-mono" placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." ...></iframe>'></textarea>
            <p class="text-xs text-gray-500 mt-1">Salin kode dari Google Maps → Bagikan → Sematkan peta → Salin HTML</p>
          </div>
          <div v-if="extractedMapSrc" class="bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden">
            <p class="text-xs text-gray-500 p-2">Preview Peta:</p>
            <iframe :src="extractedMapSrc" width="100%" height="200" style="border:0;" allowfullscreen loading="lazy"></iframe>
          </div>
          <div v-else-if="settings.googleMapsUrl" class="bg-red-50 dark:bg-red-900/20 p-3 rounded-lg">
            <p class="text-xs text-red-600 dark:text-red-400">⚠️ Format tidak valid. Pastikan Anda menyalin kode iframe lengkap dari Google Maps.</p>
          </div>
        </div>
      </div>

      <!-- Save Button -->
      <div class="flex justify-end">
        <button @click="saveSettings" :disabled="saving" class="btn-primary flex items-center gap-2">
          <svg v-if="saving" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle>
            <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" class="opacity-75"></path>
          </svg>
          <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          {{ saving ? 'Menyimpan...' : 'Simpan Pengaturan' }}
        </button>
      </div>
    </div>
  </AdminLayout>
</template>
