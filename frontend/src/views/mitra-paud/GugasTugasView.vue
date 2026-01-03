<script setup>
import MitraPaudSidebar from '@/components/layout/MitraPaudSidebar.vue'
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Hero -->
    <section class="bg-gradient-to-br from-purple-600 to-indigo-700 text-white py-16">
      <div class="section-container text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Gugus Tugas PAUD Holistik Integratif</h1>
        <p class="text-purple-100 max-w-2xl mx-auto">
          Tim koordinasi lintas sektor untuk penyelenggaraan layanan PAUD terpadu di Kota Surabaya
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
        <div class="card p-8 mb-8">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Apa itu PAUD Holistik Integratif?</h2>
          <p class="text-gray-600 dark:text-gray-400 mb-4">
            <strong>PAUD Holistik Integratif (PAUD HI)</strong> adalah penyelenggaraan PAUD yang mengintegrasikan berbagai layanan esensial anak usia dini secara komprehensif dan terpadu. Layanan ini mencakup:
          </p>
          <div class="grid md:grid-cols-5 gap-4">
            <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
              <div class="w-12 h-12 mx-auto mb-2 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
              <p class="text-sm font-medium text-gray-900 dark:text-white">Pendidikan</p>
            </div>
            <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
              <div class="w-12 h-12 mx-auto mb-2 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
              </div>
              <p class="text-sm font-medium text-gray-900 dark:text-white">Kesehatan</p>
            </div>
            <div class="text-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
              <div class="w-12 h-12 mx-auto mb-2 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
              <p class="text-sm font-medium text-gray-900 dark:text-white">Gizi</p>
            </div>
            <div class="text-center p-4 bg-pink-50 dark:bg-pink-900/20 rounded-lg">
              <div class="w-12 h-12 mx-auto mb-2 bg-pink-100 dark:bg-pink-900/30 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
              <p class="text-sm font-medium text-gray-900 dark:text-white">Pengasuhan</p>
            </div>
            <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
              <div class="w-12 h-12 mx-auto mb-2 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
              </div>
              <p class="text-sm font-medium text-gray-900 dark:text-white">Perlindungan</p>
            </div>
          </div>
        </div>

        <!-- Struktur Gugus Tugas -->
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Struktur Gugus Tugas PAUD HI</h2>
        <div class="space-y-6 mb-12">
          <div class="card p-6">
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center shrink-0">
                <span class="text-primary-600 font-bold">1</span>
              </div>
              <div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-2">Pengarah</h3>
                <p class="text-gray-600 dark:text-gray-400">Walikota dan Wakil Walikota Surabaya sebagai pengarah utama Gugus Tugas PAUD HI.</p>
              </div>
            </div>
          </div>

          <div class="card p-6">
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center shrink-0">
                <span class="text-blue-600 font-bold">2</span>
              </div>
              <div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-2">Ketua</h3>
                <p class="text-gray-600 dark:text-gray-400">Bunda PAUD Kota Surabaya sebagai ketua Gugus Tugas yang mengkoordinasikan seluruh kegiatan.</p>
              </div>
            </div>
          </div>

          <div class="card p-6">
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center shrink-0">
                <span class="text-green-600 font-bold">3</span>
              </div>
              <div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-2">Sekretariat</h3>
                <p class="text-gray-600 dark:text-gray-400">Dinas Pendidikan Kota Surabaya sebagai sekretariat yang menjalankan administrasi dan koordinasi harian.</p>
              </div>
            </div>
          </div>

          <div class="card p-6">
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center shrink-0">
                <span class="text-orange-600 font-bold">4</span>
              </div>
              <div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-2">Anggota</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-3">Terdiri dari berbagai Organisasi Perangkat Daerah (OPD) terkait:</p>
                <div class="grid md:grid-cols-2 gap-2">
                  <span class="text-sm bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded">Dinas Kesehatan</span>
                  <span class="text-sm bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded">Dinas Sosial</span>
                  <span class="text-sm bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded">DP3A</span>
                  <span class="text-sm bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded">Dinas Kependudukan</span>
                  <span class="text-sm bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded">BAPPEKO</span>
                  <span class="text-sm bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded">Kecamatan</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tugas -->
        <div class="card p-8 mb-8">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Tugas Gugus Tugas PAUD HI</h2>
          <div class="space-y-4">
            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-green-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <p class="text-gray-600 dark:text-gray-400">Menyusun rencana aksi daerah pengembangan PAUD Holistik Integratif</p>
            </div>
            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-green-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <p class="text-gray-600 dark:text-gray-400">Mengkoordinasikan pelaksanaan program PAUD HI lintas sektor</p>
            </div>
            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-green-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <p class="text-gray-600 dark:text-gray-400">Melakukan monitoring dan evaluasi pelaksanaan PAUD HI</p>
            </div>
            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-green-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <p class="text-gray-600 dark:text-gray-400">Membangun kemitraan dengan berbagai pemangku kepentingan</p>
            </div>
            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-green-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <p class="text-gray-600 dark:text-gray-400">Melaporkan pelaksanaan kegiatan kepada Walikota</p>
            </div>
          </div>
        </div>

        <!-- CTA -->
        <div class="card p-8 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 text-center">
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Ingin Berkontribusi?</h3>
          <p class="text-gray-600 dark:text-gray-400 mb-6">
            Bergabunglah sebagai mitra untuk mendukung penyelenggaraan PAUD Holistik Integratif
          </p>
          <router-link to="/mitra-paud/pendaftaran" class="btn-primary">
            Daftar Sebagai Mitra
          </router-link>
        </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
