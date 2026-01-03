<script setup>
import { ref, onMounted } from 'vue'
import { settingsService } from '@/services'

const isOpen = ref(false)
const whatsappNumber = ref('6281234567890')
const defaultMessage = ref('Halo, saya ingin bertanya tentang program Bunda PAUD Kota Surabaya')

onMounted(() => {
  // Load settings from localStorage
  const settings = settingsService.get()
  if (settings.whatsappNumber) {
    whatsappNumber.value = settings.whatsappNumber
  }
  if (settings.whatsappMessage) {
    defaultMessage.value = settings.whatsappMessage
  }
})

const openWhatsApp = () => {
  const encodedMessage = encodeURIComponent(defaultMessage.value)
  window.open(`https://wa.me/${whatsappNumber.value}?text=${encodedMessage}`, '_blank')
}
</script>

<template>
  <!-- Floating WhatsApp Button -->
  <div class="fixed bottom-6 right-6 z-50">
    <!-- Chat Popup -->
    <transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0 translate-y-2 scale-95"
      enter-to-class="opacity-100 translate-y-0 scale-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100 translate-y-0 scale-100"
      leave-to-class="opacity-0 translate-y-2 scale-95"
    >
      <div v-if="isOpen" class="absolute bottom-20 right-0 w-80 bg-white rounded-2xl shadow-2xl overflow-hidden mb-2">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 p-4 text-white">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
              <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
              </svg>
            </div>
            <div>
              <h3 class="font-bold text-lg">Hotline Bunda PAUD</h3>
              <p class="text-sm text-green-100">Kota Surabaya</p>
            </div>
          </div>
        </div>
        
        <!-- Body -->
        <div class="p-4 bg-gray-50">
          <div class="bg-white rounded-lg p-3 shadow-sm">
            <p class="text-gray-600 text-sm">
              Halo! 👋 Ada yang bisa kami bantu? Silakan hubungi kami melalui WhatsApp untuk informasi seputar:
            </p>
            <ul class="text-sm text-gray-600 mt-2 space-y-1">
              <li>• Program Bunda PAUD</li>
              <li>• Layanan PAUD</li>
              <li>• Pengaduan & Konsultasi</li>
            </ul>
          </div>
        </div>
        
        <!-- Footer -->
        <div class="p-4 pt-0 bg-gray-50">
          <button
            @click="openWhatsApp"
            class="w-full bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-xl font-medium flex items-center justify-center gap-2 transition-colors"
          >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            Hubungi via WhatsApp
          </button>
        </div>
      </div>
    </transition>

    <!-- Floating Button with Label -->
    <div class="flex flex-col items-center relative group">
      <!-- Label Above Button -->
      <div v-if="!isOpen" class="absolute bottom-24 right-0 bg-white dark:bg-gray-800 text-center px-4 py-2 rounded-xl shadow-xl border-2 border-primary-100 dark:border-primary-900 animate-bounce">
        <div class="text-base font-black text-red-500 tracking-wider leading-tight">HOTLINE</div>
        <div class="text-[10px] font-bold text-primary-700 dark:text-primary-300 leading-tight">BUNDA PAUD</div>
        <!-- Arrow -->
        <div class="absolute -bottom-2 right-8 w-4 h-4 bg-white dark:bg-gray-800 border-b-2 border-r-2 border-primary-100 dark:border-primary-900 transform rotate-45"></div>
      </div>

      <button
        @click="isOpen = !isOpen"
        class="w-20 h-20 flex items-center justify-center transition-all duration-300 hover:scale-110 relative"
        :class="{ 'rotate-0': !isOpen }"
      >
        <!-- Bunda PAUD Logo / Close Icon -->
        <div v-if="!isOpen" class="relative w-full h-full p-1">
           <!-- Glow effect -->
           <div class="absolute inset-0 bg-white/50 dark:bg-black/50 rounded-full blur-md animate-pulse"></div>
           <img 
            src="/logo-bunda-paud.png" 
            alt="Bunda PAUD" 
            class="w-full h-full object-contain relative z-10 drop-shadow-xl"
            onerror="this.src='https://placehold.co/80x80/2563eb/ffffff?text=BP'"
          />
        </div>
        
        <div v-else class="w-14 h-14 bg-white dark:bg-gray-800 rounded-full shadow-lg flex items-center justify-center border-2 border-gray-200">
          <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </div>
      </button>
    </div>
  </div>
</template>
