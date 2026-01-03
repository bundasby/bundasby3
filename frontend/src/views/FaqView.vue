<script setup>
import { ref } from 'vue'
import ProfilSidebar from '@/components/layout/ProfilSidebar.vue'

const faqs = ref([
  {
    question: 'Apa itu Bunda PAUD?',
    answer: 'Bunda PAUD adalah predikat yang diberikan kepada istri kepala pemerintahan atau kepala daerah (mulai dari Presiden hingga Kepala Desa/Lurah). Mereka adalah penggerak utama dalam pembinaan layanan Pendidikan Anak Usia Dini (PAUD) di wilayahnya. Di Kota Surabaya, Bunda PAUD dijabat oleh Ibu Walikota Surabaya.',
    isOpen: true
  },
  {
    question: 'Apa peran dan tugas Bunda PAUD?',
    answer: 'Bunda PAUD berperan sebagai penggerak utama untuk memotivasi semua pihak dalam mewujudkan Gerakan Nasional PAUD Berkualitas. Tugas meliputi: 1) Advokasi dan sosialisasi program PAUD, 2) Koordinasi lintas sektor, 3) Pemberdayaan masyarakat untuk akses dan kualitas layanan PAUD, 4) Pengukuhan Bunda PAUD di tingkat bawahnya, 5) Pembentukan Kelompok Kerja (Pokja) Bunda PAUD.',
    isOpen: false
  },
  {
    question: 'Apa itu Pokja Bunda PAUD?',
    answer: 'Pokja (Kelompok Kerja) Bunda PAUD adalah tim pendukung yang membantu Bunda PAUD dalam menjalankan tugas dan fungsinya. Pokja terdiri dari berbagai unsur seperti Dinas Pendidikan, organisasi mitra PAUD, akademisi, dan pemangku kepentingan terkait yang bekerja sama untuk mengembangkan layanan PAUD di wilayahnya.',
    isOpen: false
  },
  {
    question: 'Apa itu PAUD Holistik Integratif (PAUD HI)?',
    answer: 'PAUD Holistik Integratif adalah penyelenggaraan PAUD yang mengintegrasikan berbagai layanan esensial anak usia dini secara terpadu, meliputi: pendidikan, kesehatan dan gizi, rangsangan tumbuh kembang, pengasuhan, dan perlindungan anak. Bunda PAUD berperan mengkoordinasikan lintas sektor untuk mewujudkan PAUD HI.',
    isOpen: false
  },
  {
    question: 'Mengapa peran Bunda PAUD penting?',
    answer: 'Keberadaan Bunda PAUD sangat penting untuk menggerakkan segenap komponen dan sumber daya di wilayahnya dalam mendorong penyelenggaraan PAUD berkualitas. Bunda PAUD dapat membangun komunikasi dengan semua pemangku kepentingan yang berpotensi mengembangkan layanan PAUD dan memastikan setiap anak usia dini mendapat hak pendidikan berkualitas.',
    isOpen: false
  },
  {
    question: 'Apa itu SIBUNDA?',
    answer: 'SIBUNDA (Sistem Informasi Bunda PAUD) adalah platform digital yang menyediakan data dan informasi terkait PAUD, profil Bunda PAUD, program kerja, serta layanan digital lainnya. SIBUNDA dapat diakses melalui sibunda.surabaya.go.id untuk memudahkan koordinasi dan pelaporan kegiatan Bunda PAUD.',
    isOpen: false
  },
  {
    question: 'Bagaimana struktur organisasi Bunda PAUD?',
    answer: 'Struktur Bunda PAUD berjenjang mulai dari tingkat nasional (Ibu Negara), provinsi (Istri Gubernur), kabupaten/kota (Istri Bupati/Walikota), kecamatan (Istri Camat), hingga kelurahan/desa (Istri Lurah/Kepala Desa). Setiap tingkat memiliki Pokja sebagai tim pendukung operasional.',
    isOpen: false
  },
  {
    question: 'Apa saja program unggulan Bunda PAUD Kota Surabaya?',
    answer: 'Program unggulan meliputi: 1) Gerakan Nasional PAUD Berkualitas, 2) Pencegahan dan penanganan stunting, 3) Makan Bergizi Gratis untuk anak PAUD, 4) Pelatihan dan peningkatan kapasitas guru PAUD, 5) Program parenting untuk orang tua, 6) Pembinaan lembaga PAUD di seluruh kecamatan dan kelurahan.',
    isOpen: false
  }
])

const toggleFaq = (index) => {
  faqs.value[index].isOpen = !faqs.value[index].isOpen
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Hero -->
    <section class="bg-gradient-to-br from-purple-600 to-purple-800 text-white py-16">
      <div class="section-container text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Pertanyaan yang Sering Diajukan</h1>
        <p class="text-purple-100 max-w-2xl mx-auto">
          Temukan jawaban atas pertanyaan umum seputar program dan layanan Bunda PAUD Kota Surabaya
        </p>
      </div>
    </section>

    <!-- Content with Sidebar -->
    <section class="py-12 bg-white dark:bg-gray-900">
      <div class="section-container">
        <div class="grid lg:grid-cols-4 gap-8">
          <!-- Sidebar -->
          <div class="lg:col-span-1">
            <ProfilSidebar />
          </div>

          <!-- Main Content -->
          <div class="lg:col-span-3">
            <!-- FAQ Items -->
            <div class="space-y-4">
              <div 
                v-for="(faq, index) in faqs" 
                :key="index"
                class="card overflow-hidden"
              >
                <button
                  @click="toggleFaq(index)"
                  class="w-full px-6 py-4 flex items-center justify-between text-left hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                >
                  <span class="font-semibold text-gray-900 dark:text-white pr-4">{{ faq.question }}</span>
                  <svg 
                    class="w-5 h-5 text-gray-500 transition-transform shrink-0" 
                    :class="{ 'rotate-180': faq.isOpen }"
                    fill="none" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
                <div 
                  v-show="faq.isOpen" 
                  class="px-6 pb-4 text-gray-600 dark:text-gray-400 border-t border-gray-100 dark:border-gray-700 pt-4"
                >
                  {{ faq.answer }}
                </div>
              </div>
            </div>

            <!-- Contact CTA -->
            <div class="mt-12 card p-8 text-center bg-gradient-to-r from-primary-50 to-purple-50 dark:from-primary-900/20 dark:to-purple-900/20">
              <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Masih ada pertanyaan?</h2>
              <p class="text-gray-600 dark:text-gray-400 mb-6">
                Jika pertanyaan Anda belum terjawab, silakan hubungi kami
              </p>
              <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <router-link to="/kontak" class="btn-primary">
                  Hubungi Kami
                </router-link>
                <router-link to="/pengaduan" class="btn-secondary">
                  Kirim Pengaduan
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
