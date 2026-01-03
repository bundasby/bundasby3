<script setup>
import { ref, computed } from 'vue'
import DataLayout from '@/components/layout/DataLayout.vue'

const searchQuery = ref('')
const selectedKecamatan = ref('')
const selectedType = ref('')

const paudData = ref([
  {
    id: 1,
    name: 'TK Negeri Pembina',
    type: 'TK',
    kecamatan: 'Gubeng',
    kelurahan: 'Airlangga',
    address: 'Jl. Airlangga No. 10, Surabaya',
    students: 120,
    teachers: 8,
    status: 'Akreditasi A',
    phone: '031-5012345'
  },
  {
    id: 2,
    name: 'PAUD Ceria Mandiri',
    type: 'PAUD',
    kecamatan: 'Rungkut',
    kelurahan: 'Kalirungkut',
    address: 'Jl. Rungkut Asri No. 25, Surabaya',
    students: 45,
    teachers: 4,
    status: 'Akreditasi B',
    phone: '031-8712345'
  },
  {
    id: 3,
    name: 'KB Tunas Bangsa',
    type: 'KB',
    kecamatan: 'Wonokromo',
    kelurahan: 'Darmo',
    address: 'Jl. Darmo Permai No. 15, Surabaya',
    students: 30,
    teachers: 3,
    status: 'Akreditasi B',
    phone: '031-5678901'
  },
  {
    id: 4,
    name: 'TK Al-Hidayah',
    type: 'TK',
    kecamatan: 'Sukolilo',
    kelurahan: 'Keputih',
    address: 'Jl. Keputih Tegal No. 8, Surabaya',
    students: 85,
    teachers: 6,
    status: 'Akreditasi A',
    phone: '031-5991234'
  },
  {
    id: 5,
    name: 'PAUD Melati Indah',
    type: 'PAUD',
    kecamatan: 'Tegalsari',
    kelurahan: 'Tegalsari',
    address: 'Jl. Tegalsari No. 42, Surabaya',
    students: 55,
    teachers: 5,
    status: 'Akreditasi B',
    phone: '031-5471234'
  },
  {
    id: 6,
    name: 'TK Bhayangkari',
    type: 'TK',
    kecamatan: 'Sawahan',
    kelurahan: 'Pakis',
    address: 'Jl. Pakis Argosari No. 20, Surabaya',
    students: 95,
    teachers: 7,
    status: 'Akreditasi A',
    phone: '031-5321234'
  }
])

const kecamatanList = [...new Set(paudData.value.map(p => p.kecamatan))].sort()
const typeList = ['TK', 'PAUD', 'KB']

const filteredData = computed(() => {
  return paudData.value.filter(item => {
    const matchSearch = item.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                       item.address.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchKecamatan = !selectedKecamatan.value || item.kecamatan === selectedKecamatan.value
    const matchType = !selectedType.value || item.type === selectedType.value
    return matchSearch && matchKecamatan && matchType
  })
})

const stats = computed(() => ({
  total: paudData.value.length,
  tk: paudData.value.filter(p => p.type === 'TK').length,
  paud: paudData.value.filter(p => p.type === 'PAUD').length,
  kb: paudData.value.filter(p => p.type === 'KB').length,
  totalStudents: paudData.value.reduce((sum, p) => sum + p.students, 0),
  totalTeachers: paudData.value.reduce((sum, p) => sum + p.teachers, 0)
}))

const getStatusColor = (status) => {
  if (status.includes('A')) return 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
  if (status.includes('B')) return 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400'
  return 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400'
}

const getTypeColor = (type) => {
  const colors = {
    'TK': 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
    'PAUD': 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
    'KB': 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400'
  }
  return colors[type] || 'bg-gray-100 text-gray-700'
}
</script>

<template>
  <DataLayout 
    title="Data PAUD" 
    subtitle="Database lengkap lembaga Pendidikan Anak Usia Dini di Kota Surabaya"
    icon="📊"
  >
    <div class="space-y-6">
      <!-- Stats -->
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-blue-600">{{ stats.total }}</div>
          <div class="text-xs text-gray-500 dark:text-gray-400">Total Lembaga</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-blue-600">{{ stats.tk }}</div>
          <div class="text-xs text-gray-500 dark:text-gray-400">TK</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-purple-600">{{ stats.paud }}</div>
          <div class="text-xs text-gray-500 dark:text-gray-400">PAUD</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-pink-600">{{ stats.kb }}</div>
          <div class="text-xs text-gray-500 dark:text-gray-400">KB</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-green-600">{{ stats.totalStudents }}</div>
          <div class="text-xs text-gray-500 dark:text-gray-400">Peserta Didik</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-orange-600">{{ stats.totalTeachers }}</div>
          <div class="text-xs text-gray-500 dark:text-gray-400">Tenaga Pendidik</div>
        </div>
      </div>

      <!-- Filter & Search -->
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex flex-col md:flex-row gap-3">
          <!-- Search -->
          <div class="flex-1">
            <div class="relative">
              <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input 
                v-model="searchQuery"
                type="text" 
                placeholder="Cari nama atau alamat lembaga..."
                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm"
              />
            </div>
          </div>
          <!-- Filters -->
          <select 
            v-model="selectedKecamatan"
            class="px-3 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 text-sm"
          >
            <option value="">Semua Kecamatan</option>
            <option v-for="kec in kecamatanList" :key="kec" :value="kec">{{ kec }}</option>
          </select>
          <select 
            v-model="selectedType"
            class="px-3 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 text-sm"
          >
            <option value="">Semua Jenis</option>
            <option v-for="type in typeList" :key="type" :value="type">{{ type }}</option>
          </select>
        </div>
      </div>

      <!-- Data Table -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Nama Lembaga</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Jenis</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Kecamatan</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Siswa</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Status</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
              <tr 
                v-for="item in filteredData" 
                :key="item.id"
                class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
              >
                <td class="px-4 py-3">
                  <div class="font-medium text-gray-900 dark:text-white text-sm">{{ item.name }}</div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">{{ item.address }}</div>
                </td>
                <td class="px-4 py-3">
                  <span class="px-2 py-1 text-xs font-medium rounded-full" :class="getTypeColor(item.type)">
                    {{ item.type }}
                  </span>
                </td>
                <td class="px-4 py-3">
                  <div class="text-sm text-gray-900 dark:text-white">{{ item.kecamatan }}</div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">{{ item.kelurahan }}</div>
                </td>
                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ item.students }}</td>
                <td class="px-4 py-3">
                  <span class="px-2 py-1 text-xs font-medium rounded-full" :class="getStatusColor(item.status)">
                    {{ item.status }}
                  </span>
                </td>
                <td class="px-4 py-3">
                  <button class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium text-sm">
                    Detail
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty State -->
        <div v-if="filteredData.length === 0" class="text-center py-12">
          <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada data yang sesuai dengan pencarian.</p>
        </div>

        <!-- Result Count -->
        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400">
          Menampilkan {{ filteredData.length }} dari {{ paudData.length }} lembaga
        </div>
      </div>
    </div>
  </DataLayout>
</template>
