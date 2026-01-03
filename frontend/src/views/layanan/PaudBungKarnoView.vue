<script setup>
const programs = [
  {
    title: 'Pendidikan Berkualitas',
    description: 'Kurikulum terpadu sesuai standar nasional dengan pendekatan bermain sambil belajar',
    icon: '📚'
  },
  {
    title: 'Tenaga Pendidik Profesional',
    description: 'Guru-guru berpengalaman dan tersertifikasi dengan rasio guru-murid yang ideal',
    icon: '👩‍🏫'
  },
  {
    title: 'Fasilitas Lengkap',
    description: 'Ruang kelas nyaman, area bermain outdoor, perpustakaan mini, dan UKS',
    icon: '🏫'
  },
  {
    title: 'Sistem Satu Atap',
    description: 'Integrasi PAUD hingga SD dalam satu kompleks untuk kemudahan orang tua',
    icon: '🏠'
  }
]

const jadwalPendaftaran = [
  { tahap: 'Pendaftaran Gelombang 1', tanggal: 'Januari - Februari', status: 'upcoming' },
  { tahap: 'Seleksi & Pengumuman', tanggal: 'Maret', status: 'upcoming' },
  { tahap: 'Daftar Ulang', tanggal: 'April', status: 'upcoming' },
  { tahap: 'Tahun Ajaran Baru', tanggal: 'Juli', status: 'upcoming' }
]

const persyaratan = [
  'Fotokopi Akta Kelahiran (2 lembar)',
  'Fotokopi Kartu Keluarga (2 lembar)',
  'Fotokopi KTP Orang Tua (2 lembar)',
  'Pas Foto 3x4 (4 lembar, latar merah)',
  'Surat Keterangan Sehat dari Dokter',
  'Mengisi formulir pendaftaran online'
]
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Hero -->
    <section class="relative bg-gradient-to-br from-green-600 to-teal-700 text-white py-20 overflow-hidden">
      <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
          <defs>
            <pattern id="dots" x="0" y="0" width="10" height="10" patternUnits="userSpaceOnUse">
              <circle cx="2" cy="2" r="1" fill="currentColor"/>
            </pattern>
          </defs>
          <rect fill="url(#dots)" width="100" height="100"/>
        </svg>
      </div>
      <div class="section-container text-center relative z-10">
        <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm mb-6">
          <span class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></span>
          Pendaftaran Tahun Ajaran 2026/2027 Segera Dibuka
        </div>
        <h1 class="text-3xl md:text-5xl font-bold mb-4">PAUD Negeri Bung Karno</h1>
        <p class="text-xl text-green-100 mb-2">(Sistem Satu Atap)</p>
        <p class="text-green-100 max-w-2xl mx-auto mt-4">
          Pendidikan Anak Usia Dini unggulan dengan sistem satu atap yang terintegrasi, 
          didukung fasilitas lengkap dan tenaga pendidik profesional.
        </p>
      </div>
    </section>

    <!-- Programs -->
    <section class="py-16 bg-white dark:bg-gray-900">
      <div class="section-container">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center mb-12">Keunggulan Kami</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div v-for="program in programs" :key="program.title" 
               class="card p-6 text-center hover:shadow-lg transition-shadow group">
            <div class="text-4xl mb-4 group-hover:scale-110 transition-transform">{{ program.icon }}</div>
            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">{{ program.title }}</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ program.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Info & Pendaftaran -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800">
      <div class="section-container">
        <div class="grid lg:grid-cols-2 gap-12">
          <!-- Jadwal -->
          <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Jadwal Pendaftaran</h2>
            <div class="space-y-4">
              <div v-for="(jadwal, index) in jadwalPendaftaran" :key="jadwal.tahap" 
                   class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 flex items-center justify-center font-bold">
                  {{ index + 1 }}
                </div>
                <div class="flex-1">
                  <h4 class="font-medium text-gray-900 dark:text-white">{{ jadwal.tahap }}</h4>
                  <p class="text-sm text-gray-600 dark:text-gray-400">{{ jadwal.tanggal }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Persyaratan -->
          <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Persyaratan Pendaftaran</h2>
            <div class="card p-6">
              <ul class="space-y-3">
                <li v-for="syarat in persyaratan" :key="syarat" class="flex items-start gap-3">
                  <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="text-gray-700 dark:text-gray-300">{{ syarat }}</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Galeri -->
    <section class="py-16 bg-white dark:bg-gray-900">
      <div class="section-container">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center mb-8">Galeri Sekolah</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="aspect-square bg-gray-200 dark:bg-gray-700 rounded-xl overflow-hidden">
            <img src="https://placehold.co/400x400/22c55e/ffffff?text=Ruang+Kelas" alt="Ruang Kelas" class="w-full h-full object-cover hover:scale-105 transition-transform"/>
          </div>
          <div class="aspect-square bg-gray-200 dark:bg-gray-700 rounded-xl overflow-hidden">
            <img src="https://placehold.co/400x400/22c55e/ffffff?text=Area+Bermain" alt="Area Bermain" class="w-full h-full object-cover hover:scale-105 transition-transform"/>
          </div>
          <div class="aspect-square bg-gray-200 dark:bg-gray-700 rounded-xl overflow-hidden">
            <img src="https://placehold.co/400x400/22c55e/ffffff?text=Perpustakaan" alt="Perpustakaan" class="w-full h-full object-cover hover:scale-105 transition-transform"/>
          </div>
          <div class="aspect-square bg-gray-200 dark:bg-gray-700 rounded-xl overflow-hidden">
            <img src="https://placehold.co/400x400/22c55e/ffffff?text=Kegiatan+Belajar" alt="Kegiatan Belajar" class="w-full h-full object-cover hover:scale-105 transition-transform"/>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="py-16 bg-gradient-to-r from-green-600 to-teal-600 text-white">
      <div class="section-container text-center">
        <h2 class="text-2xl md:text-3xl font-bold mb-4">Daftarkan Putra/Putri Anda Sekarang!</h2>
        <p class="text-green-100 mb-8 max-w-xl mx-auto">
          Berikan pendidikan terbaik untuk anak Anda bersama PAUD Negeri Bung Karno
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <a href="#" class="bg-white text-green-600 hover:bg-green-50 font-medium px-8 py-3 rounded-lg transition-colors">
            Daftar Sekarang
          </a>
          <a href="/kontak" class="border-2 border-white/50 hover:bg-white/10 font-medium px-8 py-3 rounded-lg transition-colors">
            Hubungi Kami
          </a>
        </div>
      </div>
    </section>

    <!-- Kontak -->
    <section class="py-12 bg-gray-50 dark:bg-gray-800">
      <div class="section-container">
        <div class="grid md:grid-cols-3 gap-6 text-center">
          <div class="card p-6">
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Alamat</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Jl. Perintis Kemerdekaan No. XX<br>Kota Surabaya
            </p>
          </div>
          <div class="card p-6">
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
            </div>
            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Telepon</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">(031) 555-6666</p>
          </div>
          <div class="card p-6">
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Jam Operasional</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Senin - Jumat<br>07:00 - 12:00 WIB
            </p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
