<script setup>
import { ref } from 'vue'
import MitraPaudSidebar from '@/components/layout/MitraPaudSidebar.vue'

// Tab state
const activeTab = ref('pengertian')

// Demo list anggota gugus tugas
const anggotaList = ref([
  { id: 1, nama: 'Walikota Surabaya', jabatan: 'Pengarah', instansi: 'Pemerintah Kota Surabaya', kategori: 'Pengarah' },
  { id: 2, nama: 'Wakil Walikota Surabaya', jabatan: 'Pengarah', instansi: 'Pemerintah Kota Surabaya', kategori: 'Pengarah' },
  { id: 3, nama: 'Bunda PAUD Kota Surabaya', jabatan: 'Ketua', instansi: 'Pokja Bunda PAUD', kategori: 'Ketua' },
  { id: 4, nama: 'Kepala Dinas Pendidikan', jabatan: 'Sekretaris', instansi: 'Dinas Pendidikan Kota Surabaya', kategori: 'Sekretariat' },
  { id: 5, nama: 'Kabid PAUD Dikmas', jabatan: 'Wakil Sekretaris', instansi: 'Dinas Pendidikan Kota Surabaya', kategori: 'Sekretariat' },
  { id: 6, nama: 'Kepala Dinas Kesehatan', jabatan: 'Anggota', instansi: 'Dinas Kesehatan Kota Surabaya', kategori: 'Anggota' },
  { id: 7, nama: 'Kepala Dinas Sosial', jabatan: 'Anggota', instansi: 'Dinas Sosial Kota Surabaya', kategori: 'Anggota' },
  { id: 8, nama: 'Kepala DP3A', jabatan: 'Anggota', instansi: 'Dinas Pemberdayaan Perempuan dan Perlindungan Anak', kategori: 'Anggota' },
  { id: 9, nama: 'Kepala Dispendukcapil', jabatan: 'Anggota', instansi: 'Dinas Kependudukan dan Catatan Sipil', kategori: 'Anggota' },
  { id: 10, nama: 'Kepala BAPPEKO', jabatan: 'Anggota', instansi: 'Badan Perencanaan Pembangunan Kota', kategori: 'Anggota' },
  { id: 11, nama: 'Ketua HIMPAUDI Kota Surabaya', jabatan: 'Anggota', instansi: 'HIMPAUDI', kategori: 'Anggota' },
  { id: 12, nama: 'Ketua IGTKI Kota Surabaya', jabatan: 'Anggota', instansi: 'IGTKI', kategori: 'Anggota' },
])

const filterKategori = ref('')
const searchQuery = ref('')

const filteredAnggota = computed(() => {
  return anggotaList.value.filter(a => {
    const matchSearch = a.nama.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                        a.instansi.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchKategori = !filterKategori.value || a.kategori === filterKategori.value
    return matchSearch && matchKategori
  })
})

const kategoriOptions = ['Pengarah', 'Ketua', 'Sekretariat', 'Anggota']

import { computed } from 'vue'
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
            <!-- Tabs -->
            <div class="flex flex-wrap gap-2 mb-6">
              <button
                @click="activeTab = 'pengertian'"
                class="px-5 py-2.5 rounded-lg font-medium transition-all"
                :class="activeTab === 'pengertian' 
                  ? 'bg-purple-600 text-white shadow-lg' 
                  : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100'"
              >
                📖 Pengertian Gugus Tugas
              </button>
              <button
                @click="activeTab = 'anggota'"
                class="px-5 py-2.5 rounded-lg font-medium transition-all"
                :class="activeTab === 'anggota' 
                  ? 'bg-purple-600 text-white shadow-lg' 
                  : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100'"
              >
                👥 List Anggota
              </button>
            </div>

            <!-- Tab: Pengertian -->
            <div v-if="activeTab === 'pengertian'">
              <!-- Intro -->
              <div class="card p-8 mb-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Apa itu Gugus Tugas PAUD HI?</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                  <strong>Gugus Tugas PAUD Holistik Integratif (PAUD HI)</strong> adalah tim koordinasi lintas sektor yang dibentuk untuk mengintegrasikan berbagai layanan esensial anak usia dini secara komprehensif dan terpadu. Gugus tugas ini berperan penting dalam memastikan anak usia dini mendapatkan layanan yang mencakup:
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
              <div class="space-y-4 mb-8">
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
                <router-link to="/mitra-paud?tab=pendaftaran" class="btn-primary">
                  Daftar Sebagai Mitra
                </router-link>
              </div>
            </div>

            <!-- Tab: List Anggota -->
            <div v-else-if="activeTab === 'anggota'">
              <div class="card p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Daftar Anggota Gugus Tugas PAUD HI</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Kota Surabaya Periode 2025-2030</p>
                
                <!-- Filter & Search -->
                <div class="grid md:grid-cols-2 gap-4 mb-6">
                  <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Cari nama atau instansi..."
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                  />
                  <select
                    v-model="filterKategori"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                  >
                    <option value="">Semua Kategori</option>
                    <option v-for="kat in kategoriOptions" :key="kat" :value="kat">{{ kat }}</option>
                  </select>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                  <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg text-center">
                    <p class="text-2xl font-bold text-purple-600">{{ anggotaList.length }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Anggota</p>
                  </div>
                  <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-center">
                    <p class="text-2xl font-bold text-blue-600">{{ anggotaList.filter(a => a.kategori === 'Pengarah').length }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pengarah</p>
                  </div>
                  <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg text-center">
                    <p class="text-2xl font-bold text-green-600">{{ anggotaList.filter(a => a.kategori === 'Sekretariat').length }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Sekretariat</p>
                  </div>
                  <div class="p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg text-center">
                    <p class="text-2xl font-bold text-orange-600">{{ anggotaList.filter(a => a.kategori === 'Anggota').length }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Anggota</p>
                  </div>
                </div>

                <!-- Anggota List -->
                <div class="space-y-4">
                  <div
                    v-for="anggota in filteredAnggota"
                    :key="anggota.id"
                    class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg"
                  >
                    <div class="flex items-center gap-4">
                      <div class="w-12 h-12 rounded-xl flex items-center justify-center" :class="{
                        'bg-primary-100 dark:bg-primary-900/30': anggota.kategori === 'Pengarah',
                        'bg-blue-100 dark:bg-blue-900/30': anggota.kategori === 'Ketua',
                        'bg-green-100 dark:bg-green-900/30': anggota.kategori === 'Sekretariat',
                        'bg-orange-100 dark:bg-orange-900/30': anggota.kategori === 'Anggota'
                      }">
                        <svg class="w-6 h-6" :class="{
                          'text-primary-600': anggota.kategori === 'Pengarah',
                          'text-blue-600': anggota.kategori === 'Ketua',
                          'text-green-600': anggota.kategori === 'Sekretariat',
                          'text-orange-600': anggota.kategori === 'Anggota'
                        }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                      </div>
                      <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ anggota.nama }}</h3>
                        <p class="text-sm text-gray-500">{{ anggota.instansi }}</p>
                      </div>
                    </div>
                    <div class="text-right">
                      <span class="px-3 py-1 text-xs font-medium rounded-full" :class="{
                        'bg-primary-100 text-primary-700': anggota.kategori === 'Pengarah',
                        'bg-blue-100 text-blue-700': anggota.kategori === 'Ketua',
                        'bg-green-100 text-green-700': anggota.kategori === 'Sekretariat',
                        'bg-orange-100 text-orange-700': anggota.kategori === 'Anggota'
                      }">
                        {{ anggota.jabatan }}
                      </span>
                    </div>
                  </div>

                  <div v-if="filteredAnggota.length === 0" class="text-center py-12 text-gray-500">
                    Tidak ada anggota yang ditemukan.
                  </div>
                </div>
              </div>

              <!-- Note -->
              <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <p class="text-sm text-blue-700 dark:text-blue-400">
                  <strong>Catatan:</strong> Daftar anggota akan diperbarui sesuai dengan SK Walikota terbaru.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
