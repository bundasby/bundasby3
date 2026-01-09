<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

// Mock data untuk statistik penilaian
const stats = ref({
  totalPeserta: 45,
  sudahDinilai: 28,
  belumDinilai: 17,
  tahap1Selesai: 35,
  tahap2Selesai: 15
})

const recentPenilaian = ref([
  { id: 1, nama: 'TK Aisyiyah 1', kecamatan: 'Genteng', kategori: 'TK Negeri', nilaiTahap1: 85, status: 'tahap2' },
  { id: 2, nama: 'KB Tunas Harapan', kecamatan: 'Tegalsari', kategori: 'KB Swasta', nilaiTahap1: 78, status: 'tahap1' },
  { id: 3, nama: 'PAUD Ceria', kecamatan: 'Sawahan', kategori: 'PAUD', nilaiTahap1: 92, status: 'selesai' },
  { id: 4, nama: 'TK Pembina', kecamatan: 'Wonokromo', kategori: 'TK Negeri', nilaiTahap1: null, status: 'pending' },
  { id: 5, nama: 'KB Melati', kecamatan: 'Rungkut', kategori: 'KB Swasta', nilaiTahap1: 88, status: 'tahap2' }
])

const progressTahap1 = computed(() => Math.round((stats.value.tahap1Selesai / stats.value.totalPeserta) * 100))
const progressTahap2 = computed(() => Math.round((stats.value.tahap2Selesai / stats.value.totalPeserta) * 100))

const getStatusBadge = (status) => {
  const badges = {
    pending: { bg: 'bg-gray-100 dark:bg-gray-700', text: 'text-gray-600 dark:text-gray-400', label: 'Belum Dinilai' },
    tahap1: { bg: 'bg-blue-100 dark:bg-blue-900/30', text: 'text-blue-600 dark:text-blue-400', label: 'Tahap 1' },
    tahap2: { bg: 'bg-amber-100 dark:bg-amber-900/30', text: 'text-amber-600 dark:text-amber-400', label: 'Tahap 2' },
    selesai: { bg: 'bg-green-100 dark:bg-green-900/30', text: 'text-green-600 dark:text-green-400', label: 'Selesai' }
  }
  return badges[status] || badges.pending
}
</script>

<template>
  <AdminLayout title="Dashboard Juri">
    <div class="space-y-6">
      <!-- Welcome Banner -->
      <div class="bg-gradient-to-r from-yellow-500 to-amber-500 rounded-2xl p-6 text-white">
        <h2 class="text-2xl font-bold mb-2">Selamat Datang, Juri! ⚖️</h2>
        <p class="text-yellow-100">Anda bertugas menilai peserta Lomba Apresiasi Bunda PAUD Kota Surabaya</p>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
              <span class="text-2xl">👥</span>
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">Total Peserta</p>
              <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.totalPeserta }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
              <span class="text-2xl">✅</span>
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">Sudah Dinilai</p>
              <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ stats.sudahDinilai }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center">
              <span class="text-2xl">⏳</span>
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">Belum Dinilai</p>
              <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ stats.belumDinilai }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
              <span class="text-2xl">🏆</span>
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">Selesai Total</p>
              <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ stats.tahap2Selesai }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Progress Section -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Progress Tahap 1 -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Progress Penilaian Tahap 1</h3>
          <div class="mb-2 flex justify-between text-sm">
            <span class="text-gray-500 dark:text-gray-400">{{ stats.tahap1Selesai }} dari {{ stats.totalPeserta }} peserta</span>
            <span class="font-medium text-blue-600 dark:text-blue-400">{{ progressTahap1 }}%</span>
          </div>
          <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
            <div class="bg-blue-500 h-3 rounded-full transition-all duration-500" :style="{ width: progressTahap1 + '%' }"></div>
          </div>
          <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Penilaian berkas dan administrasi</p>
        </div>

        <!-- Progress Tahap 2 -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Progress Penilaian Tahap 2</h3>
          <div class="mb-2 flex justify-between text-sm">
            <span class="text-gray-500 dark:text-gray-400">{{ stats.tahap2Selesai }} dari {{ stats.totalPeserta }} peserta</span>
            <span class="font-medium text-amber-600 dark:text-amber-400">{{ progressTahap2 }}%</span>
          </div>
          <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
            <div class="bg-amber-500 h-3 rounded-full transition-all duration-500" :style="{ width: progressTahap2 + '%' }"></div>
          </div>
          <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Penilaian presentasi dan wawancara</p>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <router-link to="/admin/juri/penilaian?tahap=1" class="flex items-center gap-3 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <div>
              <p class="font-medium text-blue-900 dark:text-blue-100">Penilaian Tahap 1</p>
              <p class="text-sm text-blue-600 dark:text-blue-400">Berkas & Administrasi</p>
            </div>
          </router-link>

          <router-link to="/admin/juri/penilaian?tahap=2" class="flex items-center gap-3 p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl hover:bg-amber-100 dark:hover:bg-amber-900/30 transition-colors">
            <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center text-white">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
              </svg>
            </div>
            <div>
              <p class="font-medium text-amber-900 dark:text-amber-100">Penilaian Tahap 2</p>
              <p class="text-sm text-amber-600 dark:text-amber-400">Presentasi & Wawancara</p>
            </div>
          </router-link>

          <router-link to="/admin/juri/penilaian?status=selesai" class="flex items-center gap-3 p-4 bg-green-50 dark:bg-green-900/20 rounded-xl hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center text-white">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <div>
              <p class="font-medium text-green-900 dark:text-green-100">Lihat Hasil</p>
              <p class="text-sm text-green-600 dark:text-green-400">Rekap Penilaian</p>
            </div>
          </router-link>
        </div>
      </div>

      <!-- Recent Penilaian Table -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Penilaian Terbaru</h3>
            <router-link to="/admin/juri/penilaian" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
              Lihat Semua →
            </router-link>
          </div>
        </div>
        
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Lembaga</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kecamatan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nilai Tahap 1</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="item in recentPenilaian" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <p class="font-medium text-gray-900 dark:text-white">{{ item.nama }}</p>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ item.kecamatan }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ item.kategori }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span v-if="item.nilaiTahap1" class="text-lg font-bold" :class="item.nilaiTahap1 >= 80 ? 'text-green-600 dark:text-green-400' : item.nilaiTahap1 >= 60 ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400'">
                    {{ item.nilaiTahap1 }}
                  </span>
                  <span v-else class="text-gray-400">-</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs font-medium rounded-full" :class="[getStatusBadge(item.status).bg, getStatusBadge(item.status).text]">
                    {{ getStatusBadge(item.status).label }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <router-link :to="`/admin/juri/penilaian/${item.id}`" class="text-primary-600 dark:text-primary-400 hover:underline text-sm font-medium">
                    {{ item.status === 'selesai' ? 'Lihat' : 'Nilai' }}
                  </router-link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
