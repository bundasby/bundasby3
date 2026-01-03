<script setup>
import { ref, onMounted } from 'vue'
import ProfilSidebar from '@/components/layout/ProfilSidebar.vue'
import { settingsService } from '@/services'

const form = ref({
  name: '',
  email: '',
  phone: '',
  subject: '',
  message: '',
})

const contactInfo = ref({
  email: 'bundapaud@surabaya.go.id',
  phone: '(031) 5456789',
  whatsappNumber: '6281234567890'
})

const loading = ref(false)
const submitted = ref(false)

onMounted(() => {
  // Load settings from localStorage
  const settings = settingsService.get()
  contactInfo.value = {
    email: settings.email || contactInfo.value.email,
    phone: settings.phone || contactInfo.value.phone,
    whatsappNumber: settings.whatsappNumber || contactInfo.value.whatsappNumber
  }
})

const handleSubmit = async () => {
  loading.value = true
  // Simulate API call
  await new Promise(resolve => setTimeout(resolve, 1500))
  submitted.value = true
  loading.value = false
}

const resetForm = () => {
  form.value = { name: '', email: '', phone: '', subject: '', message: '' }
  submitted.value = false
}

const formatWhatsApp = (number) => {
  if (!number) return ''
  // Format: 6281234567890 -> 0812-3456-7890
  const cleaned = number.replace(/^62/, '0')
  return cleaned.replace(/(\d{4})(\d{4})(\d{4})/, '$1-$2-$3')
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Hero -->
    <section class="bg-gradient-to-br from-red-600 to-red-800 text-white py-16">
      <div class="section-container text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Pengaduan Masyarakat</h1>
        <p class="text-red-100 max-w-2xl mx-auto">
          Sampaikan pengaduan, saran, atau masukan terkait layanan PAUD di Kota Surabaya
        </p>
      </div>
    </section>

    <!-- Content -->
    <section class="py-12 bg-white dark:bg-gray-900">
      <div class="section-container">
        <div class="grid lg:grid-cols-4 gap-8">
          <!-- Sidebar -->
          <div class="lg:col-span-1">
            <ProfilSidebar />
          </div>

          <!-- Main Content -->
          <div class="lg:col-span-3">
        <!-- Success State -->
        <div v-if="submitted" class="card p-8 text-center">
          <div class="w-20 h-20 mx-auto mb-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Pengaduan Terkirim!</h2>
          <p class="text-gray-600 dark:text-gray-400 mb-6">
            Terima kasih atas pengaduan Anda. Tim kami akan menindaklanjuti dalam 1x24 jam.
          </p>
          <button @click="resetForm" class="btn-primary">
            Kirim Pengaduan Lain
          </button>
        </div>

        <!-- Form -->
        <div v-else class="card p-8">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Form Pengaduan</h2>
          
          <form @submit.prevent="handleSubmit" class="space-y-6">
            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap *</label>
                <input 
                  v-model="form.name"
                  type="text"
                  required
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                  placeholder="Masukkan nama lengkap"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                <input 
                  v-model="form.email"
                  type="email"
                  required
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                  placeholder="email@example.com"
                />
              </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. Telepon</label>
                <input 
                  v-model="form.phone"
                  type="tel"
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                  placeholder="08xxxxxxxxxx"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Perihal *</label>
                <select 
                  v-model="form.subject"
                  required
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                >
                  <option value="">Pilih perihal</option>
                  <option value="pengaduan">Pengaduan</option>
                  <option value="saran">Saran</option>
                  <option value="pertanyaan">Pertanyaan</option>
                  <option value="lainnya">Lainnya</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Isi Pengaduan *</label>
              <textarea 
                v-model="form.message"
                required
                rows="6"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                placeholder="Jelaskan pengaduan Anda secara detail..."
              ></textarea>
            </div>

            <div class="flex items-center gap-4">
              <button 
                type="submit"
                :disabled="loading"
                class="btn-primary py-3 px-8 disabled:opacity-50"
              >
                <span v-if="loading" class="flex items-center gap-2">
                  <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Mengirim...
                </span>
                <span v-else>Kirim Pengaduan</span>
              </button>
            </div>
          </form>
        </div>

        <!-- Info -->
        <div class="mt-8 grid md:grid-cols-2 gap-6">
          <div class="card p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
              <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Informasi Penting
            </h3>
            <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
              <li>• Pengaduan akan diproses dalam 3x24 jam</li>
              <li>• Pastikan email aktif untuk notifikasi</li>
              <li>• Data Anda dijamin kerahasiaannya</li>
            </ul>
          </div>
          <div class="card p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
              <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              Kontak Alternatif
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Email: {{ contactInfo.email }}<br>
              Telp: {{ contactInfo.phone }}<br>
              WhatsApp: {{ formatWhatsApp(contactInfo.whatsappNumber) }}
            </p>
          </div>
        </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

