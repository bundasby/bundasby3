<script setup>
import { ref } from 'vue'
import DataLayout from '@/components/layout/DataLayout.vue'

const akreditasiData = ref([
  { id: 1, nama: 'TK Negeri Pembina', npsn: '20532001', alamat: 'Jl. Raya Kendangsari No.1', kecamatan: 'Tenggilis Mejoyo', akreditasi: 'A', tahun: 2024 },
  { id: 2, nama: 'TK Al-Ikhlas', npsn: '20532002', alamat: 'Jl. Semolowaru No.45', kecamatan: 'Sukolilo', akreditasi: 'A', tahun: 2024 },
  { id: 3, nama: 'KB Tunas Harapan', npsn: '20532003', alamat: 'Jl. Mulyorejo No.12', kecamatan: 'Mulyorejo', akreditasi: 'B', tahun: 2023 },
  { id: 4, nama: 'TK Dharma Wanita', npsn: '20532004', alamat: 'Jl. Gubeng Jaya No.8', kecamatan: 'Gubeng', akreditasi: 'A', tahun: 2024 },
  { id: 5, nama: 'PAUD Ceria', npsn: '20532005', alamat: 'Jl. Rungkut Asri No.22', kecamatan: 'Rungkut', akreditasi: 'B', tahun: 2023 },
  { id: 6, nama: 'TK Aisyiyah', npsn: '20532006', alamat: 'Jl. Wonokromo No.15', kecamatan: 'Wonokromo', akreditasi: 'A', tahun: 2024 },
  { id: 7, nama: 'KB Pelangi', npsn: '20532007', alamat: 'Jl. Sawahan No.33', kecamatan: 'Sawahan', akreditasi: 'B', tahun: 2022 },
  { id: 8, nama: 'TK Kartini', npsn: '20532008', alamat: 'Jl. Genteng Kali No.5', kecamatan: 'Genteng', akreditasi: 'A', tahun: 2024 },
])

const searchQuery = ref('')
const selectedAkreditasi = ref('')

const filteredData = () => {
  let data = akreditasiData.value
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    data = data.filter(item => 
      item.nama.toLowerCase().includes(query) ||
      item.npsn.includes(query) ||
      item.kecamatan.toLowerCase().includes(query)
    )
  }
  if (selectedAkreditasi.value) {
    data = data.filter(item => item.akreditasi === selectedAkreditasi.value)
  }
  return data
}

const getAkreditasiColor = (akreditasi) => {
  switch(akreditasi) {
    case 'A': return 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
    case 'B': return 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
    case 'C': return 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400'
    default: return 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400'
  }
}
</script>

<template>
  <DataLayout 
    title="Daftar Akreditasi PAUD" 
    subtitle="Data akreditasi lembaga PAUD di Kota Surabaya"
    icon="📋"
  >
    <div class="space-y-6">
      <!-- Filter -->
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="grid md:grid-cols-3 gap-3">
          <div class="md:col-span-2">
            <input 
              v-model="searchQuery"
              type="text"
              placeholder="Cari berdasarkan nama, NPSN, atau kecamatan..."
              class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-sm"
            />
          </div>
          <div>
            <select 
              v-model="selectedAkreditasi"
              class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-sm"
            >
              <option value="">Semua Akreditasi</option>
              <option value="A">Akreditasi A</option>
              <option value="B">Akreditasi B</option>
              <option value="C">Akreditasi C</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Statistics -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-primary-600">{{ akreditasiData.length }}</div>
          <div class="text-xs text-gray-500">Total Lembaga</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-green-600">{{ akreditasiData.filter(d => d.akreditasi === 'A').length }}</div>
          <div class="text-xs text-gray-500">Akreditasi A</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-blue-600">{{ akreditasiData.filter(d => d.akreditasi === 'B').length }}</div>
          <div class="text-xs text-gray-500">Akreditasi B</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-yellow-600">{{ akreditasiData.filter(d => d.akreditasi === 'C').length }}</div>
          <div class="text-xs text-gray-500">Akreditasi C</div>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">No</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Nama Lembaga</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">NPSN</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Kecamatan</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Akreditasi</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Tahun</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
              <tr 
                v-for="(item, index) in filteredData()" 
                :key="item.id"
                class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
              >
                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ index + 1 }}</td>
                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ item.nama }}</td>
                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ item.npsn }}</td>
                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ item.kecamatan }}</td>
                <td class="px-4 py-3 text-center">
                  <span :class="['px-2 py-1 rounded-full text-xs font-medium', getAkreditasiColor(item.akreditasi)]">
                    {{ item.akreditasi }}
                  </span>
                </td>
                <td class="px-4 py-3 text-center text-sm text-gray-600 dark:text-gray-400">{{ item.tahun }}</td>
              </tr>
              <tr v-if="filteredData().length === 0">
                <td colspan="6" class="px-4 py-12 text-center text-gray-500 text-sm">
                  Data tidak ditemukan
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </DataLayout>
</template>
