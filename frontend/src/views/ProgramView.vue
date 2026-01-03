<script setup>
import { ref } from 'vue'
import ProfilSidebar from '@/components/layout/ProfilSidebar.vue'

// 6 Program Prioritas Kemendikdasmen 2026
const programs = ref([
  {
    id: 1,
    title: 'Penguatan Pendidikan Karakter',
    description: 'Penanaman karakter 7 kebiasaan anak Indonesia hebat melalui pendidikan nilai, bimbingan konseling, dan penguatan peran guru agama serta guru BK di satuan PAUD.',
    activities: [
      'Pelatihan bimbingan konseling untuk guru kelas',
      'Penanaman karakter 7 kebiasaan anak Indonesia hebat',
      'Peningkatan kompetensi guru BK dan guru agama',
      'Program makan siang bergizi sehat'
    ],
    icon: 'heart',
    color: 'blue'
  },
  {
    id: 2,
    title: 'Wajib Belajar 13 Tahun dan Pemerataan Kesempatan Pendidikan',
    description: 'Pemerataan akses pendidikan anak usia dini melalui afirmasi pendidikan masyarakat, rumah belajar, pendidikan jarak jauh, dan fasilitasi relawan mengajar.',
    activities: [
      'Perluasan akses PAUD di seluruh wilayah',
      'Fasilitasi pendidikan jarak jauh',
      'Program relawan mengajar PAUD',
      'Afirmasi pendidikan masyarakat'
    ],
    icon: 'school',
    color: 'gold'
  },
  {
    id: 3,
    title: 'Peningkatan Kualifikasi, Kompetensi, dan Kesejahteraan Guru',
    description: 'Program peningkatan kualifikasi pendidikan guru minimal D-IV/S-1, pelatihan kompetensi berkelanjutan, serta peningkatan kesejahteraan melalui sertifikasi.',
    activities: [
      'Pelatihan peningkatan kualifikasi guru PAUD',
      'Program sertifikasi guru PAUD',
      'Tunjangan profesi guru non-ASN',
      'Workshop kurikulum merdeka belajar'
    ],
    icon: 'users',
    color: 'green'
  },
  {
    id: 4,
    title: 'Penguatan Pendidikan Unggul, Literasi, Numerasi, dan Sains Teknologi',
    description: 'Fokus pendidikan matematika, sains, dan teknologi sejak usia dini, pengembangan sekolah unggul, serta penguatan pendidikan vokasi dan kejuruan.',
    activities: [
      'Pendidikan STEM untuk anak usia dini',
      'Pengembangan lembaga PAUD unggul',
      'Digitalisasi pembelajaran PAUD',
      'Penguatan literasi dan numerasi dasar'
    ],
    icon: 'book',
    color: 'purple'
  },
  {
    id: 5,
    title: 'Pemenuhan dan Perbaikan Sarana dan Prasarana',
    description: 'Program rekonstruksi, renovasi, dan pemeliharaan fasilitas lembaga PAUD untuk menciptakan lingkungan belajar yang aman, nyaman, dan kondusif.',
    activities: [
      'Renovasi gedung lembaga PAUD',
      'Penyediaan APE (Alat Permainan Edukatif)',
      'Revitalisasi sarana bermain outdoor',
      'Pemenuhan standar sarana prasarana'
    ],
    icon: 'building',
    color: 'teal'
  },
  {
    id: 6,
    title: 'Pembangunan Bahasa dan Sastra',
    description: 'Pemartabatan bahasa negara, pelindungan bahasa daerah, penginternasionalan bahasa Indonesia, dan peningkatan literasi anak usia dini.',
    activities: [
      'Pengenalan bahasa daerah di PAUD',
      'Program literasi anak usia dini',
      'Pengembangan buku cerita lokal',
      'Festival bahasa dan sastra anak'
    ],
    icon: 'language',
    color: 'pink'
  }
])

const targets = ref([
  { label: 'Lembaga PAUD Terakreditasi', target: '100%', current: '78%' },
  { label: 'Guru PAUD Bersertifikasi', target: '95%', current: '68%' },
  { label: 'APK PAUD (Wajib Belajar 13 Tahun)', target: '90%', current: '80%' },
  { label: 'PAUD dengan Standar Nasional', target: '85%', current: '60%' }
])
</script>

<template>
  <div>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-primary-600 to-primary-800 text-white py-16">
      <div class="section-container text-center">
        <span class="badge bg-white/20 text-white mb-4">6 Program Prioritas Kemendikdasmen</span>
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Program Kerja 2026</h1>
        <p class="text-lg text-blue-100 max-w-2xl mx-auto">
          Implementasi program prioritas nasional Kemendikdasmen di tingkat PAUD Kota Surabaya untuk mewujudkan pendidikan anak usia dini yang berkualitas.
        </p>
      </div>
    </section>

    <!-- Programs with Sidebar -->
    <section class="py-16 bg-white dark:bg-gray-900">
      <div class="section-container">
        <div class="grid lg:grid-cols-4 gap-8">
          <!-- Sidebar -->
          <div class="lg:col-span-1">
            <ProfilSidebar />
          </div>

          <!-- Main Content -->
          <div class="lg:col-span-3">
            <div class="grid md:grid-cols-2 gap-6">
              <div 
                v-for="program in programs" 
                :key="program.id"
                class="card p-6 hover:shadow-lg transition-shadow"
              >
                <!-- Header -->
                <div class="flex items-start gap-4 mb-5">
                  <div 
                    class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                    :class="{
                      'bg-blue-100 text-blue-600': program.color === 'blue',
                      'bg-amber-100 text-amber-600': program.color === 'gold',
                      'bg-green-100 text-green-600': program.color === 'green',
                      'bg-purple-100 text-purple-600': program.color === 'purple',
                      'bg-teal-100 text-teal-600': program.color === 'teal',
                      'bg-pink-100 text-pink-600': program.color === 'pink'
                    }"
                  >
                    <!-- Heart Icon -->
                    <svg v-if="program.icon === 'heart'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <!-- School Icon -->
                    <svg v-if="program.icon === 'school'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                    <!-- Users Icon -->
                    <svg v-if="program.icon === 'users'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <!-- Book Icon -->
                    <svg v-if="program.icon === 'book'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <!-- Building Icon -->
                    <svg v-if="program.icon === 'building'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <!-- Language Icon -->
                    <svg v-if="program.icon === 'language'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                    </svg>
                  </div>
                  <div>
                    <span class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-bold px-2 py-0.5 rounded mb-2">Program {{ program.id }}</span>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-tight">{{ program.title }}</h3>
                  </div>
                </div>

                <!-- Description -->
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-5 leading-relaxed">{{ program.description }}</p>

                <!-- Activities -->
                <div>
                  <h4 class="text-xs font-semibold text-gray-900 dark:text-white mb-2 uppercase tracking-wide">Kegiatan Utama:</h4>
                  <ul class="space-y-1.5">
                    <li 
                      v-for="activity in program.activities" 
                      :key="activity"
                      class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400"
                    >
                      <svg class="w-4 h-4 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                      {{ activity }}
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Targets -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800">
      <div class="section-container">
        <div class="text-center mb-12">
          <h2 class="section-title">Target Pencapaian 2026</h2>
          <p class="section-subtitle">Indikator keberhasilan implementasi 6 Program Prioritas Kemendikdasmen di Kota Surabaya</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div 
            v-for="target in targets" 
            :key="target.label"
            class="card p-6 text-center hover:shadow-lg transition-shadow"
          >
            <div class="text-3xl font-bold text-primary-600 mb-2">{{ target.target }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ target.label }}</div>
            <div class="text-xs text-gray-500">
              Saat ini: <span class="font-medium text-accent-600">{{ target.current }}</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
