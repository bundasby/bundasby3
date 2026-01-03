<script setup>
import { ref } from 'vue'

const activeTab = ref('stunting')

const tabs = [
  { id: 'stunting', name: 'Data Stunting', icon: 'chart' },
  { id: 'mbg', name: 'Data MBG', icon: 'food' },
  { id: 'pengaduan', name: 'Pengaduan', icon: 'message' }
]

// Stunting Data
const stuntingData = ref({
  total: 1250,
  percentage: '12%',
  byKecamatan: [
    { name: 'Sukolilo', count: 85, percentage: '8%' },
    { name: 'Gubeng', count: 92, percentage: '9%' },
    { name: 'Rungkut', count: 78, percentage: '7%' },
    { name: 'Tenggilis Mejoyo', count: 65, percentage: '11%' },
    { name: 'Wonokromo', count: 110, percentage: '14%' }
  ]
})

// MBG Data
const mbgData = ref({
  totalPenerima: 35000,
  totalLembaga: 650,
  totalAnggaran: 'Rp 15.2 M',
  coverage: '78%'
})

// Pengaduan Form
const pengaduanForm = ref({
  nama: '',
  email: '',
  telepon: '',
  subjek: '',
  pesan: ''
})

const submitPengaduan = () => {
  alert('Pengaduan berhasil dikirim! Kami akan menghubungi Anda segera.')
  pengaduanForm.value = { nama: '', email: '', telepon: '', subjek: '', pesan: '' }
}
</script>

<template>
  <div>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-primary-600 to-primary-800 text-white py-16">
      <div class="section-container text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Layanan</h1>
        <p class="text-lg text-blue-100 max-w-2xl mx-auto">
          Akses data dan layanan pengaduan terkait PAUD di Kota Surabaya.
        </p>
      </div>
    </section>

    <!-- Tabs -->
    <section class="py-16 bg-white dark:bg-gray-900">
      <div class="section-container">
        <!-- Tab Buttons -->
        <div class="flex flex-wrap gap-2 mb-8 border-b border-gray-200 dark:border-gray-700 pb-4">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            @click="activeTab = tab.id"
            class="px-6 py-3 rounded-lg text-sm font-medium transition-colors"
            :class="activeTab === tab.id 
              ? 'bg-primary-600 text-white' 
              : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700'"
          >
            {{ tab.name }}
          </button>
        </div>

        <!-- Stunting Tab -->
        <div v-show="activeTab === 'stunting'" id="stunting">
          <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="card p-6 text-center">
              <div class="text-4xl font-bold text-red-600 mb-2">{{ stuntingData.total }}</div>
              <div class="text-gray-600 dark:text-gray-400">Total Kasus Stunting</div>
            </div>
            <div class="card p-6 text-center">
              <div class="text-4xl font-bold text-accent-600 mb-2">{{ stuntingData.percentage }}</div>
              <div class="text-gray-600 dark:text-gray-400">Prevalensi</div>
            </div>
            <div class="card p-6 text-center">
              <div class="text-4xl font-bold text-primary-600 mb-2">31</div>
              <div class="text-gray-600 dark:text-gray-400">Kecamatan Terpantau</div>
            </div>
          </div>

          <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Data per Kecamatan (Top 5)</h3>
            <div class="space-y-4">
              <div 
                v-for="item in stuntingData.byKecamatan" 
                :key="item.name"
                class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg"
              >
                <span class="font-medium text-gray-900 dark:text-white">{{ item.name }}</span>
                <div class="flex items-center gap-4">
                  <span class="text-gray-600 dark:text-gray-400">{{ item.count }} kasus</span>
                  <span class="px-2 py-1 bg-red-100 text-red-600 rounded text-sm font-medium">{{ item.percentage }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- MBG Tab -->
        <div v-show="activeTab === 'mbg'" id="mbg">
          <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="card p-6 text-center">
              <div class="text-4xl font-bold text-primary-600 mb-2">{{ mbgData.totalPenerima.toLocaleString('id-ID') }}</div>
              <div class="text-gray-600 dark:text-gray-400">Total Penerima</div>
            </div>
            <div class="card p-6 text-center">
              <div class="text-4xl font-bold text-accent-600 mb-2">{{ mbgData.totalLembaga }}</div>
              <div class="text-gray-600 dark:text-gray-400">Lembaga PAUD</div>
            </div>
            <div class="card p-6 text-center">
              <div class="text-4xl font-bold text-emerald-600 mb-2">{{ mbgData.totalAnggaran }}</div>
              <div class="text-gray-600 dark:text-gray-400">Total Anggaran</div>
            </div>
            <div class="card p-6 text-center">
              <div class="text-4xl font-bold text-purple-600 mb-2">{{ mbgData.coverage }}</div>
              <div class="text-gray-600 dark:text-gray-400">Coverage</div>
            </div>
          </div>

          <div class="card p-6 mt-8">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Tentang Program MBG</h3>
            <p class="text-gray-600 dark:text-gray-400">
              Program Makanan Bergizi Gratis (MBG) adalah inisiatif pemerintah untuk memberikan makanan bergizi 
              kepada anak-anak PAUD di Kota Surabaya. Program ini bertujuan untuk mendukung tumbuh kembang optimal 
              anak dan mencegah stunting.
            </p>
          </div>
        </div>

        <!-- Pengaduan Tab -->
        <div v-show="activeTab === 'pengaduan'" id="pengaduan">
          <div class="max-w-2xl mx-auto">
            <div class="card p-6 lg:p-8">
              <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Form Pengaduan</h3>
              <form @submit.prevent="submitPengaduan" class="space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                    <input 
                      v-model="pengaduanForm.nama"
                      type="text" 
                      required
                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                    <input 
                      v-model="pengaduanForm.email"
                      type="email" 
                      required
                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    />
                  </div>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telepon</label>
                    <input 
                      v-model="pengaduanForm.telepon"
                      type="tel"
                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subjek</label>
                    <input 
                      v-model="pengaduanForm.subjek"
                      type="text" 
                      required
                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    />
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pesan</label>
                  <textarea 
                    v-model="pengaduanForm.pesan"
                    rows="5" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                  ></textarea>
                </div>
                <button type="submit" class="w-full btn-primary">
                  Kirim Pengaduan
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
