<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

// Sample news data (akan diganti dengan API call)
const allNews = ref([
  {
    id: 1,
    slug: '1',
    title: 'Konferensi Bunda PAUD Kota Surabaya 2026',
    excerpt: 'Acara tahunan yang mempertemukan seluruh Bunda PAUD se-Kota Surabaya untuk berbagi pengalaman dan best practices.',
    content: `
      <p>Konferensi Bunda PAUD Kota Surabaya 2026 telah sukses diselenggarakan pada tanggal 1 Januari 2026 di Gedung Siola Surabaya. Acara ini dihadiri oleh lebih dari 500 peserta yang terdiri dari Bunda PAUD tingkat kelurahan, kecamatan, dan tokoh pendidikan anak usia dini se-Kota Surabaya.</p>
      
      <h3>Tema Konferensi</h3>
      <p>Konferensi tahun ini mengangkat tema "Mewujudkan Generasi Emas 2045 Melalui Pendidikan Anak Usia Dini yang Berkualitas". Tema ini dipilih untuk menegaskan komitmen Bunda PAUD Kota Surabaya dalam mendukung program pemerintah untuk mencetak generasi unggul Indonesia.</p>
      
      <h3>Rangkaian Kegiatan</h3>
      <p>Konferensi dibuka dengan sambutan dari Walikota Surabaya dan dilanjutkan dengan keynote speech dari Kepala Dinas Pendidikan Provinsi Jawa Timur. Selama dua hari pelaksanaan, peserta mengikuti berbagai sesi:</p>
      <ul>
        <li>Workshop Kurikulum Merdeka untuk PAUD</li>
        <li>Seminar Pencegahan Stunting</li>
        <li>Best Practice Sharing dari PAUD berprestasi</li>
        <li>Pameran APE (Alat Permainan Edukatif)</li>
      </ul>
      
      <h3>Hasil dan Rekomendasi</h3>
      <p>Konferensi menghasilkan beberapa rekomendasi penting untuk pengembangan PAUD di Kota Surabaya, termasuk:</p>
      <ol>
        <li>Peningkatan anggaran untuk pelatihan guru PAUD</li>
        <li>Perluasan program Makanan Bergizi Gratis (MBG)</li>
        <li>Digitalisasi sistem monitoring PAUD</li>
        <li>Penguatan koordinasi dengan posyandu untuk pencegahan stunting</li>
      </ol>
      
      <p>Bunda PAUD Kota Surabaya berkomitmen untuk menindaklanjuti rekomendasi ini melalui program kerja tahun 2026.</p>
    `,
    thumbnail: 'https://placehold.co/1200x600/2563eb/ffffff?text=Konferensi+2026',
    category: 'kegiatan',
    author: 'Admin',
    date: '2026-01-01'
  },
  {
    id: 2,
    slug: '2',
    title: 'Pelatihan Digitalisasi PAUD Batch 2',
    excerpt: 'Program pelatihan untuk meningkatkan kompetensi digital guru PAUD dalam pengelolaan pembelajaran.',
    content: `
      <p>Bunda PAUD Kota Surabaya bekerja sama dengan Dinas Pendidikan menyelenggarakan Pelatihan Digitalisasi PAUD Batch 2 yang berlangsung selama 3 hari di Hotel Mercure Surabaya.</p>
      
      <h3>Latar Belakang</h3>
      <p>Di era digital, guru PAUD dituntut untuk mampu memanfaatkan teknologi dalam proses pembelajaran. Pelatihan ini bertujuan untuk meningkatkan kompetensi digital guru PAUD agar dapat mengintegrasikan teknologi dalam kegiatan belajar mengajar.</p>
      
      <h3>Materi Pelatihan</h3>
      <ul>
        <li>Penggunaan aplikasi pembelajaran interaktif</li>
        <li>Pembuatan media pembelajaran digital</li>
        <li>Pengelolaan administrasi berbasis aplikasi</li>
        <li>Pemanfaatan platform SIBUNDA</li>
      </ul>
      
      <h3>Peserta</h3>
      <p>Pelatihan diikuti oleh 150 guru PAUD dari 31 kecamatan di Kota Surabaya. Setiap peserta mendapatkan sertifikat kompetensi digital dari Dinas Pendidikan.</p>
    `,
    thumbnail: 'https://placehold.co/1200x600/16a34a/ffffff?text=Pelatihan+Digital',
    category: 'edukasi',
    author: 'Admin',
    date: '2025-12-28'
  },
  {
    id: 3,
    slug: '3',
    title: 'Launching SIBUNDA 2.0',
    excerpt: 'Pembaruan sistem informasi Bunda PAUD dengan fitur-fitur terbaru untuk kemudahan pelaporan.',
    content: `
      <p>SIBUNDA (Sistem Informasi Bunda PAUD) versi 2.0 resmi diluncurkan pada tanggal 25 Desember 2025. Pembaruan ini membawa berbagai fitur baru yang mempermudah koordinasi dan pelaporan kegiatan Bunda PAUD di seluruh tingkatan.</p>
      
      <h3>Fitur Baru SIBUNDA 2.0</h3>
      <ul>
        <li><strong>Dashboard Real-time</strong> - Monitoring data PAUD secara langsung</li>
        <li><strong>Modul Pelaporan</strong> - Sistem pelaporan kegiatan yang lebih mudah</li>
        <li><strong>Integrasi Data Stunting</strong> - Koneksi dengan data kesehatan anak</li>
        <li><strong>Mobile App</strong> - Aplikasi mobile untuk akses dimana saja</li>
        <li><strong>Notifikasi</strong> - Pengingat kegiatan dan deadline pelaporan</li>
      </ul>
      
      <h3>Cara Akses</h3>
      <p>SIBUNDA 2.0 dapat diakses melalui <a href="https://sibunda.surabaya.go.id" target="_blank">sibunda.surabaya.go.id</a>. Pengguna yang sudah memiliki akun dapat langsung login dengan kredensial yang sama.</p>
    `,
    thumbnail: 'https://placehold.co/1200x600/f59e0b/ffffff?text=SIBUNDA+2.0',
    category: 'pengumuman',
    author: 'Admin',
    date: '2025-12-25'
  }
])

const article = ref(null)
const relatedNews = ref([])

onMounted(() => {
  const id = route.params.slug
  article.value = allNews.value.find(n => n.slug === id || n.id.toString() === id)
  
  if (article.value) {
    // Get related news (same category, exclude current)
    relatedNews.value = allNews.value
      .filter(n => n.category === article.value.category && n.id !== article.value.id)
      .slice(0, 2)
  }
})

const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString('id-ID', { 
    day: 'numeric', 
    month: 'long', 
    year: 'numeric' 
  })
}

const getCategoryLabel = (category) => {
  const labels = {
    kegiatan: 'Kegiatan',
    edukasi: 'Edukasi',
    pengumuman: 'Pengumuman'
  }
  return labels[category] || category
}

const goBack = () => {
  router.push('/berita')
}
</script>

<template>
  <div>
    <!-- Article Content -->
    <template v-if="article">
      <!-- Hero Image -->
      <section class="relative h-[40vh] md:h-[50vh] bg-gray-900">
        <img 
          :src="article.thumbnail" 
          :alt="article.title"
          class="w-full h-full object-cover opacity-60"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/50 to-transparent"></div>
        
        <!-- Back Button -->
        <button 
          @click="goBack"
          class="absolute top-6 left-6 flex items-center gap-2 text-white/80 hover:text-white transition-colors"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Kembali
        </button>
        
        <!-- Title Overlay -->
        <div class="absolute bottom-0 left-0 right-0 p-6 md:p-12">
          <div class="section-container">
            <span class="badge badge-primary mb-4">{{ getCategoryLabel(article.category) }}</span>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4">
              {{ article.title }}
            </h1>
            <div class="flex items-center gap-4 text-white/80 text-sm">
              <span>{{ formatDate(article.date) }}</span>
              <span>•</span>
              <span>{{ article.author }}</span>
            </div>
          </div>
        </div>
      </section>

      <!-- Article Body -->
      <section class="py-12 bg-white dark:bg-gray-900">
        <div class="section-container">
          <div class="grid lg:grid-cols-3 gap-12">
            <!-- Main Content -->
            <div class="lg:col-span-2">
              <article 
                class="prose prose-lg dark:prose-invert max-w-none
                       prose-headings:text-gray-900 dark:prose-headings:text-white
                       prose-p:text-gray-700 dark:prose-p:text-gray-300
                       prose-a:text-primary-600 dark:prose-a:text-primary-400
                       prose-ul:text-gray-700 dark:prose-ul:text-gray-300
                       prose-ol:text-gray-700 dark:prose-ol:text-gray-300
                       prose-li:text-gray-700 dark:prose-li:text-gray-300"
                v-html="article.content"
              ></article>
              
              <!-- Share Buttons -->
              <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Bagikan Artikel</h4>
                <div class="flex gap-3">
                  <a 
                    :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}`"
                    target="_blank"
                    class="p-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                  >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                  </a>
                  <a 
                    :href="`https://twitter.com/intent/tweet?url=${encodeURIComponent(window.location.href)}&text=${encodeURIComponent(article.title)}`"
                    target="_blank"
                    class="p-3 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors"
                  >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                  </a>
                  <a 
                    :href="`https://wa.me/?text=${encodeURIComponent(article.title + ' ' + window.location.href)}`"
                    target="_blank"
                    class="p-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                  >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
              <!-- Related News -->
              <div class="card p-6 sticky top-24">
                <h3 class="font-bold text-gray-900 dark:text-white mb-6">Berita Terkait</h3>
                
                <div v-if="relatedNews.length > 0" class="space-y-4">
                  <router-link 
                    v-for="news in relatedNews" 
                    :key="news.id"
                    :to="`/berita/${news.id}`"
                    class="block group"
                  >
                    <div class="flex gap-4">
                      <img 
                        :src="news.thumbnail" 
                        :alt="news.title"
                        class="w-20 h-20 rounded-lg object-cover flex-shrink-0"
                      />
                      <div class="flex-1 min-w-0">
                        <h4 class="font-medium text-gray-900 dark:text-white line-clamp-2 group-hover:text-primary-600 transition-colors">
                          {{ news.title }}
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                          {{ formatDate(news.date) }}
                        </p>
                      </div>
                    </div>
                  </router-link>
                </div>
                
                <div v-else class="text-gray-500 dark:text-gray-400 text-sm">
                  Tidak ada berita terkait.
                </div>
                
                <!-- CTA -->
                <router-link 
                  to="/berita" 
                  class="block mt-6 text-center text-primary-600 dark:text-primary-400 font-medium hover:underline"
                >
                  Lihat Semua Berita →
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </section>
    </template>

    <!-- Not Found -->
    <template v-else>
      <section class="py-32 bg-white dark:bg-gray-900 text-center">
        <div class="section-container">
          <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Artikel Tidak Ditemukan</h1>
          <p class="text-gray-600 dark:text-gray-400 mb-8">Maaf, artikel yang Anda cari tidak tersedia.</p>
          <router-link to="/berita" class="btn-primary">Kembali ke Berita</router-link>
        </div>
      </section>
    </template>
  </div>
</template>

.prose h3 {
  font-size: 1.25rem;
  line-height: 1.75rem;
  font-weight: 700;
  margin-top: 2rem;
  margin-bottom: 1rem;
}

.prose ul, .prose ol {
  margin-top: 1rem;
  margin-bottom: 1rem;
  padding-left: 1.5rem;
}

.prose li {
  margin-bottom: 0.5rem;
}

.prose p {
  margin-bottom: 1rem;
}
