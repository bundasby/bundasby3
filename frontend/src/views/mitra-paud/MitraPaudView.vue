<script setup>
import MitraPaudSidebar from '@/components/layout/MitraPaudSidebar.vue'
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Hero -->
    <section class="bg-gradient-to-br from-teal-600 to-green-700 text-white py-16">
      <div class="section-container text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Mitra PAUD</h1>
        <p class="text-teal-100 max-w-2xl mx-auto">
          Jaringan kemitraan untuk pengembangan PAUD berkualitas di Kota Surabaya
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
        <!-- Intro -->
        <div class="card p-8 mb-8 text-center">
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Siapa Mitra PAUD?</h2>
          <p class="text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
            Mitra PAUD adalah organisasi, lembaga, atau individu yang berkolaborasi dengan Bunda PAUD dalam mendukung penyelenggaraan layanan PAUD berkualitas. Mitra PAUD berperan penting dalam memperluas jangkauan dan meningkatkan kualitas pendidikan anak usia dini.
          </p>
        </div>

        <!-- Quick Links -->
        <div class="grid md:grid-cols-3 gap-6 mb-12">
          <router-link to="/mitra-paud/pendaftaran" class="card p-6 text-center hover:border-teal-300 dark:hover:border-teal-600 border-2 border-transparent transition-all hover:shadow-lg group">
            <div class="w-16 h-16 mx-auto mb-4 bg-teal-100 dark:bg-teal-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
              <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
              </svg>
            </div>
            <h3 class="font-bold text-gray-900 dark:text-white mb-2">Pendaftaran Mitra</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Daftar sebagai mitra PAUD Kota Surabaya</p>
          </router-link>

          <router-link to="/bunda-paud" class="card p-6 text-center hover:border-blue-300 dark:hover:border-blue-600 border-2 border-transparent transition-all hover:shadow-lg group">
            <div class="w-16 h-16 mx-auto mb-4 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
              <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <h3 class="font-bold text-gray-900 dark:text-white mb-2">Bunda PAUD Kec. & Kel.</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Daftar Bunda PAUD di tingkat Kecamatan dan Kelurahan</p>
          </router-link>

          <router-link to="/mitra-paud/gugus-tugas" class="card p-6 text-center hover:border-purple-300 dark:hover:border-purple-600 border-2 border-transparent transition-all hover:shadow-lg group">
            <div class="w-16 h-16 mx-auto mb-4 bg-purple-100 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
              <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <h3 class="font-bold text-gray-900 dark:text-white mb-2">Gugus Tugas PAUD HI</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Tim koordinasi PAUD Holistik Integratif</p>
          </router-link>
        </div>

        <!-- Categories -->
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Kategori Mitra PAUD</h2>
        <div class="grid md:grid-cols-2 gap-6 mb-12">
          <div class="card p-6">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
              <span class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
              </span>
              Lembaga Pemerintah
            </h3>
            <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
              <li>• Dinas Pendidikan</li>
              <li>• Dinas Kesehatan</li>
              <li>• Dinas Sosial</li>
              <li>• Dinas Pemberdayaan Perempuan</li>
              <li>• Kecamatan dan Kelurahan</li>
            </ul>
          </div>

          <div class="card p-6">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
              <span class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
              </span>
              Organisasi Masyarakat
            </h3>
            <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
              <li>• HIMPAUDI (Himpunan Pendidik dan Tenaga Kependidikan PAUD)</li>
              <li>• IGTKI (Ikatan Guru Taman Kanak-Kanak Indonesia)</li>
              <li>• Forum PAUD</li>
              <li>• PKK</li>
              <li>• Posyandu</li>
            </ul>
          </div>

          <div class="card p-6">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
              <span class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </span>
              Akademisi & Perguruan Tinggi
            </h3>
            <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
              <li>• Universitas Negeri Surabaya</li>
              <li>• Universitas Airlangga</li>
              <li>• ITS</li>
              <li>• Perguruan Tinggi Swasta</li>
              <li>• Lembaga Penelitian</li>
            </ul>
          </div>

          <div class="card p-6">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
              <span class="w-8 h-8 bg-pink-100 dark:bg-pink-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </span>
              Dunia Usaha & Industri
            </h3>
            <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
              <li>• CSR Perusahaan</li>
              <li>• Bank dan Lembaga Keuangan</li>
              <li>• Penerbit Buku Anak</li>
              <li>• Produsen APE (Alat Permainan Edukatif)</li>
              <li>• Media Massa</li>
            </ul>
          </div>
        </div>

        <!-- CTA -->
        <div class="card p-8 bg-gradient-to-r from-teal-50 to-green-50 dark:from-teal-900/20 dark:to-green-900/20 text-center">
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Tertarik Menjadi Mitra PAUD?</h3>
          <p class="text-gray-600 dark:text-gray-400 mb-6">
            Bergabunglah dalam gerakan untuk mewujudkan layanan PAUD berkualitas di Kota Surabaya
          </p>
          <router-link to="/mitra-paud/pendaftaran" class="btn-primary">
            Daftar Sekarang
          </router-link>
        </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
