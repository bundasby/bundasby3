<script setup>
import { ref } from 'vue'
import MitraPaudSidebar from '@/components/layout/MitraPaudSidebar.vue'

const form = ref({
  namaLembaga: '',
  jenis: '',
  alamat: '',
  penanggungJawab: '',
  jabatan: '',
  telepon: '',
  email: '',
  website: '',
  bidangKerjasama: '',
  deskripsi: ''
})

const loading = ref(false)
const submitted = ref(false)

const jenisOptions = [
  'Lembaga Pemerintah',
  'Organisasi Masyarakat',
  'Perguruan Tinggi',
  'Dunia Usaha/Industri',
  'Lembaga Swadaya Masyarakat',
  'Media',
  'Lainnya'
]

const bidangOptions = [
  'Pendidikan dan Pelatihan',
  'Kesehatan dan Gizi',
  'Perlindungan Anak',
  'Pemberdayaan Masyarakat',
  'CSR dan Pendanaan',
  'Teknologi dan Inovasi',
  'Media dan Komunikasi',
  'Lainnya'
]

const handleSubmit = async () => {
  loading.value = true
  await new Promise(resolve => setTimeout(resolve, 1500))
  submitted.value = true
  loading.value = false
}

const resetForm = () => {
  form.value = {
    namaLembaga: '',
    jenis: '',
    alamat: '',
    penanggungJawab: '',
    jabatan: '',
    telepon: '',
    email: '',
    website: '',
    bidangKerjasama: '',
    deskripsi: ''
  }
  submitted.value = false
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Hero -->
    <section class="bg-gradient-to-br from-teal-600 to-green-700 text-white py-16">
      <div class="section-container text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Pendaftaran Mitra PAUD</h1>
        <p class="text-teal-100 max-w-2xl mx-auto">
          Daftarkan lembaga atau organisasi Anda untuk menjadi mitra dalam pengembangan PAUD di Kota Surabaya
        </p>
      </div>
    </section>

    <!-- Content with Sidebar -->
    <section class="py-12 bg-white dark:bg-gray-900">
      <div class="section-container">
        <div class="grid lg:grid-cols-4 gap-8">
          <!-- Sidebar -->
          <div class="lg:col-span-1">
            <MitraPaudSidebar />
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
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Pendaftaran Berhasil!</h2>
          <p class="text-gray-600 dark:text-gray-400 mb-6">
            Terima kasih telah mendaftar sebagai Mitra PAUD. Tim kami akan menghubungi Anda untuk proses verifikasi.
          </p>
          <button @click="resetForm" class="btn-primary">
            Daftar Lembaga Lain
          </button>
        </div>

        <!-- Form -->
        <div v-else class="card p-8">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Form Pendaftaran Mitra</h2>
          
          <form @submit.prevent="handleSubmit" class="space-y-6">
            <!-- Informasi Lembaga -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
              <h3 class="font-semibold text-gray-900 dark:text-white">Informasi Lembaga</h3>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Lembaga/Organisasi *</label>
              <input 
                v-model="form.namaLembaga"
                type="text"
                required
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500"
                placeholder="Masukkan nama lembaga"
              />
            </div>

            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Lembaga *</label>
                <select 
                  v-model="form.jenis"
                  required
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500"
                >
                  <option value="">Pilih jenis lembaga</option>
                  <option v-for="jenis in jenisOptions" :key="jenis" :value="jenis">{{ jenis }}</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bidang Kerjasama *</label>
                <select 
                  v-model="form.bidangKerjasama"
                  required
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500"
                >
                  <option value="">Pilih bidang kerjasama</option>
                  <option v-for="bidang in bidangOptions" :key="bidang" :value="bidang">{{ bidang }}</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat *</label>
              <textarea 
                v-model="form.alamat"
                rows="2"
                required
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500"
                placeholder="Alamat lengkap lembaga"
              ></textarea>
            </div>

            <!-- Kontak Person -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4 pt-4">
              <h3 class="font-semibold text-gray-900 dark:text-white">Penanggung Jawab</h3>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Penanggung Jawab *</label>
                <input 
                  v-model="form.penanggungJawab"
                  type="text"
                  required
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500"
                  placeholder="Nama lengkap"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jabatan *</label>
                <input 
                  v-model="form.jabatan"
                  type="text"
                  required
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500"
                  placeholder="Jabatan di lembaga"
                />
              </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Telepon *</label>
                <input 
                  v-model="form.telepon"
                  type="tel"
                  required
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500"
                  placeholder="08xxxxxxxxxx"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                <input 
                  v-model="form.email"
                  type="email"
                  required
                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500"
                  placeholder="email@lembaga.com"
                />
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Website (Opsional)</label>
              <input 
                v-model="form.website"
                type="url"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500"
                placeholder="https://www.lembaga.com"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi Lembaga & Rencana Kontribusi</label>
              <textarea 
                v-model="form.deskripsi"
                rows="4"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500"
                placeholder="Jelaskan profil lembaga dan rencana kontribusi untuk PAUD..."
              ></textarea>
            </div>

            <button 
              type="submit"
              :disabled="loading"
              class="w-full btn-primary py-4 disabled:opacity-50"
            >
              <span v-if="loading" class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Mengirim...
              </span>
              <span v-else>Daftar Sebagai Mitra</span>
            </button>
          </form>
        </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
