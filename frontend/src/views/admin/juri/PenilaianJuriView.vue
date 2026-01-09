<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const route = useRoute()
const router = useRouter()

// State
const activeTab = ref('semua')
const searchQuery = ref('')
const selectedKategori = ref('')
const showPenilaianModal = ref(false)
const selectedPeserta = ref(null)
const saving = ref(false)

// Mock data peserta lomba
const pesertaList = ref([
  { id: 1, nama: 'TK Aisyiyah 1', kecamatan: 'Genteng', kelurahan: 'Ketabang', kategori: 'TK Negeri', nilaiTahap1: 85, nilaiTahap2: 88, status: 'selesai', berkas: ['proposal.pdf', 'foto.jpg'] },
  { id: 2, nama: 'KB Tunas Harapan', kecamatan: 'Tegalsari', kelurahan: 'Wonorejo', kategori: 'KB Swasta', nilaiTahap1: 78, nilaiTahap2: null, status: 'tahap2', berkas: ['proposal.pdf'] },
  { id: 3, nama: 'PAUD Ceria', kecamatan: 'Sawahan', kelurahan: 'Pakis', kategori: 'PAUD', nilaiTahap1: 92, nilaiTahap2: 90, status: 'selesai', berkas: ['proposal.pdf', 'video.mp4'] },
  { id: 4, nama: 'TK Pembina', kecamatan: 'Wonokromo', kelurahan: 'Darmo', kategori: 'TK Negeri', nilaiTahap1: null, nilaiTahap2: null, status: 'pending', berkas: ['proposal.pdf'] },
  { id: 5, nama: 'KB Melati', kecamatan: 'Rungkut', kelurahan: 'Kalirungkut', kategori: 'KB Swasta', nilaiTahap1: 88, nilaiTahap2: null, status: 'tahap2', berkas: ['proposal.pdf', 'foto.jpg'] },
  { id: 6, nama: 'TK Dharma Wanita', kecamatan: 'Sukolilo', kelurahan: 'Keputih', kategori: 'TK Negeri', nilaiTahap1: 75, nilaiTahap2: 80, status: 'selesai', berkas: ['proposal.pdf'] },
  { id: 7, nama: 'PAUD Ananda', kecamatan: 'Mulyorejo', kelurahan: 'Kalijudan', kategori: 'PAUD', nilaiTahap1: null, nilaiTahap2: null, status: 'pending', berkas: ['proposal.pdf'] },
  { id: 8, nama: 'KB Bintang Kecil', kecamatan: 'Tambaksari', kelurahan: 'Ploso', kategori: 'KB Swasta', nilaiTahap1: 82, nilaiTahap2: null, status: 'tahap2', berkas: ['proposal.pdf', 'foto.jpg', 'video.mp4'] }
])

// Form penilaian
const penilaianForm = ref({
  tahap: 1,
  kriteria: [
    { id: 1, nama: 'Kelengkapan Berkas', bobot: 20, nilai: 0 },
    { id: 2, nama: 'Program Kerja', bobot: 25, nilai: 0 },
    { id: 3, nama: 'Inovasi Pembelajaran', bobot: 25, nilai: 0 },
    { id: 4, nama: 'Kolaborasi Stakeholder', bobot: 15, nilai: 0 },
    { id: 5, nama: 'Dampak Program', bobot: 15, nilai: 0 }
  ],
  catatan: ''
})

const tabs = [
  { id: 'semua', label: 'Semua', count: pesertaList.value.length },
  { id: 'pending', label: 'Belum Dinilai', count: pesertaList.value.filter(p => p.status === 'pending').length },
  { id: 'tahap1', label: 'Tahap 1', count: pesertaList.value.filter(p => p.status === 'tahap1').length },
  { id: 'tahap2', label: 'Tahap 2', count: pesertaList.value.filter(p => p.status === 'tahap2').length },
  { id: 'selesai', label: 'Selesai', count: pesertaList.value.filter(p => p.status === 'selesai').length }
]

const kategoriOptions = ['Semua Kategori', 'TK Negeri', 'TK Swasta', 'KB Swasta', 'PAUD']

const filteredPeserta = computed(() => {
  let result = pesertaList.value

  // Filter by tab
  if (activeTab.value !== 'semua') {
    result = result.filter(p => p.status === activeTab.value)
  }

  // Filter by search
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter(p => 
      p.nama.toLowerCase().includes(query) ||
      p.kecamatan.toLowerCase().includes(query) ||
      p.kelurahan.toLowerCase().includes(query)
    )
  }

  // Filter by kategori
  if (selectedKategori.value && selectedKategori.value !== 'Semua Kategori') {
    result = result.filter(p => p.kategori === selectedKategori.value)
  }

  return result
})

const totalNilai = computed(() => {
  const total = penilaianForm.value.kriteria.reduce((sum, k) => {
    return sum + (k.nilai * k.bobot / 100)
  }, 0)
  return Math.round(total)
})

const openPenilaianModal = (peserta, tahap = 1) => {
  selectedPeserta.value = peserta
  penilaianForm.value.tahap = tahap
  // Reset form
  penilaianForm.value.kriteria.forEach(k => k.nilai = 0)
  penilaianForm.value.catatan = ''
  showPenilaianModal.value = true
}

const savePenilaian = async () => {
  saving.value = true
  
  // Simulate API call
  await new Promise(resolve => setTimeout(resolve, 1000))
  
  // Update peserta
  const index = pesertaList.value.findIndex(p => p.id === selectedPeserta.value.id)
  if (index !== -1) {
    if (penilaianForm.value.tahap === 1) {
      pesertaList.value[index].nilaiTahap1 = totalNilai.value
      pesertaList.value[index].status = 'tahap2'
    } else {
      pesertaList.value[index].nilaiTahap2 = totalNilai.value
      pesertaList.value[index].status = 'selesai'
    }
  }
  
  saving.value = false
  showPenilaianModal.value = false
}

const getStatusBadge = (status) => {
  const badges = {
    pending: { bg: 'bg-gray-100 dark:bg-gray-700', text: 'text-gray-600 dark:text-gray-400', label: 'Belum Dinilai' },
    tahap1: { bg: 'bg-blue-100 dark:bg-blue-900/30', text: 'text-blue-600 dark:text-blue-400', label: 'Tahap 1' },
    tahap2: { bg: 'bg-amber-100 dark:bg-amber-900/30', text: 'text-amber-600 dark:text-amber-400', label: 'Tahap 2' },
    selesai: { bg: 'bg-green-100 dark:bg-green-900/30', text: 'text-green-600 dark:text-green-400', label: 'Selesai' }
  }
  return badges[status] || badges.pending
}

const getNilaiColor = (nilai) => {
  if (nilai >= 80) return 'text-green-600 dark:text-green-400'
  if (nilai >= 60) return 'text-amber-600 dark:text-amber-400'
  return 'text-red-600 dark:text-red-400'
}

// Handle query params
onMounted(() => {
  if (route.query.tahap) {
    activeTab.value = `tahap${route.query.tahap}`
  }
  if (route.query.status) {
    activeTab.value = route.query.status
  }
})
</script>

<template>
  <AdminLayout title="Penilaian Juri">
    <div class="space-y-6">
      <!-- Header Actions -->
      <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
        <div class="flex-1 w-full md:w-auto">
          <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari nama lembaga, kecamatan..."
              class="w-full md:w-80 pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
          </div>
        </div>
        
        <div class="flex gap-3">
          <select
            v-model="selectedKategori"
            class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
          >
            <option v-for="kategori in kategoriOptions" :key="kategori" :value="kategori">{{ kategori }}</option>
          </select>
          
          <button class="px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export
          </button>
        </div>
      </div>

      <!-- Tabs -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-1.5">
        <div class="flex flex-wrap gap-1">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            @click="activeTab = tab.id"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2"
            :class="activeTab === tab.id 
              ? 'bg-primary-600 text-white' 
              : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'"
          >
            {{ tab.label }}
            <span 
              class="px-2 py-0.5 rounded-full text-xs"
              :class="activeTab === tab.id ? 'bg-white/20' : 'bg-gray-200 dark:bg-gray-600'"
            >
              {{ tab.count }}
            </span>
          </button>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">No</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Lembaga</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lokasi</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nilai Tahap 1</th>
                <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nilai Tahap 2</th>
                <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="(peserta, index) in filteredPeserta" :key="peserta.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ index + 1 }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <p class="font-medium text-gray-900 dark:text-white">{{ peserta.nama }}</p>
                  <p class="text-xs text-gray-500 dark:text-gray-400">{{ peserta.berkas.length }} berkas</p>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <p class="text-sm text-gray-900 dark:text-white">{{ peserta.kecamatan }}</p>
                  <p class="text-xs text-gray-500 dark:text-gray-400">{{ peserta.kelurahan }}</p>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ peserta.kategori }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <span v-if="peserta.nilaiTahap1" class="text-lg font-bold" :class="getNilaiColor(peserta.nilaiTahap1)">
                    {{ peserta.nilaiTahap1 }}
                  </span>
                  <span v-else class="text-gray-400">-</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <span v-if="peserta.nilaiTahap2" class="text-lg font-bold" :class="getNilaiColor(peserta.nilaiTahap2)">
                    {{ peserta.nilaiTahap2 }}
                  </span>
                  <span v-else class="text-gray-400">-</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <span v-if="peserta.nilaiTahap1 && peserta.nilaiTahap2" class="text-lg font-bold text-purple-600 dark:text-purple-400">
                    {{ Math.round((peserta.nilaiTahap1 + peserta.nilaiTahap2) / 2) }}
                  </span>
                  <span v-else class="text-gray-400">-</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs font-medium rounded-full" :class="[getStatusBadge(peserta.status).bg, getStatusBadge(peserta.status).text]">
                    {{ getStatusBadge(peserta.status).label }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <div class="flex items-center justify-center gap-2">
                    <!-- Lihat Berkas -->
                    <button class="p-2 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400" title="Lihat Berkas">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </button>
                    
                    <!-- Nilai Tahap 1 -->
                    <button 
                      v-if="peserta.status === 'pending'"
                      @click="openPenilaianModal(peserta, 1)"
                      class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg"
                    >
                      Nilai Tahap 1
                    </button>
                    
                    <!-- Nilai Tahap 2 -->
                    <button 
                      v-else-if="peserta.status === 'tahap2'"
                      @click="openPenilaianModal(peserta, 2)"
                      class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-medium rounded-lg"
                    >
                      Nilai Tahap 2
                    </button>
                    
                    <!-- Selesai -->
                    <span v-else class="text-green-600 dark:text-green-400 text-sm font-medium">✓ Selesai</span>
                  </div>
                </td>
              </tr>
              
              <!-- Empty State -->
              <tr v-if="filteredPeserta.length === 0">
                <td colspan="9" class="px-6 py-12 text-center">
                  <div class="text-gray-500 dark:text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="font-medium">Tidak ada data</p>
                    <p class="text-sm">Tidak ditemukan peserta yang sesuai dengan filter</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Penilaian Modal -->
    <div v-if="showPenilaianModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black/50" @click="showPenilaianModal = false"></div>
      <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                Penilaian Tahap {{ penilaianForm.tahap }}
              </h2>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ selectedPeserta?.nama }}</p>
            </div>
            <button @click="showPenilaianModal = false" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
              <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Content -->
        <div class="p-6 overflow-y-auto max-h-[60vh]">
          <div class="space-y-6">
            <!-- Kriteria -->
            <div v-for="kriteria in penilaianForm.kriteria" :key="kriteria.id" class="space-y-2">
              <div class="flex items-center justify-between">
                <label class="font-medium text-gray-900 dark:text-white">{{ kriteria.nama }}</label>
                <span class="text-sm text-gray-500 dark:text-gray-400">Bobot: {{ kriteria.bobot }}%</span>
              </div>
              <div class="flex items-center gap-4">
                <input
                  type="range"
                  v-model="kriteria.nilai"
                  min="0"
                  max="100"
                  class="flex-1 h-2 bg-gray-200 dark:bg-gray-700 rounded-full appearance-none cursor-pointer accent-primary-600"
                />
                <input
                  type="number"
                  v-model="kriteria.nilai"
                  min="0"
                  max="100"
                  class="w-20 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-center text-gray-900 dark:text-white"
                />
              </div>
            </div>

            <!-- Catatan -->
            <div>
              <label class="block font-medium text-gray-900 dark:text-white mb-2">Catatan (Opsional)</label>
              <textarea
                v-model="penilaianForm.catatan"
                rows="3"
                placeholder="Tambahkan catatan penilaian..."
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
              ></textarea>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">Total Nilai:</p>
              <p class="text-3xl font-bold" :class="getNilaiColor(totalNilai)">{{ totalNilai }}</p>
            </div>
            <div class="flex gap-3">
              <button 
                @click="showPenilaianModal = false"
                class="px-6 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600"
              >
                Batal
              </button>
              <button 
                @click="savePenilaian"
                :disabled="saving"
                class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium flex items-center gap-2 disabled:opacity-50"
              >
                <svg v-if="saving" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ saving ? 'Menyimpan...' : 'Simpan Penilaian' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
