<script setup>
import { ref, computed } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const activeTab = ref('semua')
const showModal = ref(false)
const editingItem = ref(null)
const saving = ref(false)

const laporanList = ref([
  { id: 1, judul: 'Laporan Kegiatan Pelatihan Q1 2026', kegiatan: 'Pelatihan Kurikulum Merdeka', tanggal: '2026-01-20', status: 'submitted', file: 'laporan-q1.pdf' },
  { id: 2, judul: 'Laporan Rapat Koordinasi Januari', kegiatan: 'Rapat Koordinasi Bulanan', tanggal: '2026-01-15', status: 'approved', file: 'laporan-rakor.pdf' },
  { id: 3, judul: 'Laporan Workshop Media Pembelajaran', kegiatan: 'Workshop Pembelajaran Kreatif', tanggal: '2026-01-10', status: 'draft', file: null },
  { id: 4, judul: 'Laporan Kegiatan Desember 2025', kegiatan: 'Seminar Parenting', tanggal: '2025-12-28', status: 'approved', file: 'laporan-des.pdf' }
])

const form = ref({ judul: '', kegiatan: '', deskripsi: '', file: null })

const tabs = [
  { id: 'semua', label: 'Semua', count: laporanList.value.length },
  { id: 'draft', label: 'Draft', count: laporanList.value.filter(l => l.status === 'draft').length },
  { id: 'submitted', label: 'Diajukan', count: laporanList.value.filter(l => l.status === 'submitted').length },
  { id: 'approved', label: 'Disetujui', count: laporanList.value.filter(l => l.status === 'approved').length }
]

const stats = computed(() => ({
  total: laporanList.value.length,
  draft: laporanList.value.filter(l => l.status === 'draft').length,
  submitted: laporanList.value.filter(l => l.status === 'submitted').length,
  approved: laporanList.value.filter(l => l.status === 'approved').length
}))

const filteredLaporan = computed(() => {
  if (activeTab.value === 'semua') return laporanList.value
  return laporanList.value.filter(l => l.status === activeTab.value)
})

const getStatusBadge = (status) => {
  const badges = {
    draft: { bg: 'bg-gray-100', text: 'text-gray-600', label: 'Draft' },
    submitted: { bg: 'bg-blue-100', text: 'text-blue-600', label: 'Diajukan' },
    approved: { bg: 'bg-green-100', text: 'text-green-600', label: 'Disetujui' },
    rejected: { bg: 'bg-red-100', text: 'text-red-600', label: 'Ditolak' }
  }
  return badges[status] || badges.draft
}

const openModal = (item = null) => {
  editingItem.value = item
  form.value = item ? { ...item } : { judul: '', kegiatan: '', deskripsi: '', file: null }
  showModal.value = true
}

const saveItem = async () => {
  saving.value = true
  await new Promise(r => setTimeout(r, 500))
  if (editingItem.value) {
    const idx = laporanList.value.findIndex(l => l.id === editingItem.value.id)
    if (idx !== -1) laporanList.value[idx] = { ...laporanList.value[idx], ...form.value }
  } else {
    laporanList.value.push({ id: Date.now(), ...form.value, status: 'draft', tanggal: new Date().toISOString().split('T')[0] })
  }
  saving.value = false
  showModal.value = false
}

const submitLaporan = (id) => {
  const idx = laporanList.value.findIndex(l => l.id === id)
  if (idx !== -1) laporanList.value[idx].status = 'submitted'
}
</script>

<template>
  <AdminLayout title="Laporan Mitra">
    <div class="space-y-6">
      <!-- Stats -->
      <div class="grid grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 border">
          <p class="text-sm text-gray-500">Total</p>
          <p class="text-2xl font-bold">{{ stats.total }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border">
          <p class="text-sm text-gray-500">Draft</p>
          <p class="text-2xl font-bold text-gray-600">{{ stats.draft }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border">
          <p class="text-sm text-gray-500">Diajukan</p>
          <p class="text-2xl font-bold text-blue-600">{{ stats.submitted }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border">
          <p class="text-sm text-gray-500">Disetujui</p>
          <p class="text-2xl font-bold text-green-600">{{ stats.approved }}</p>
        </div>
      </div>

      <!-- Tabs & Actions -->
      <div class="flex justify-between">
        <div class="flex gap-2">
          <button v-for="tab in tabs" :key="tab.id" @click="activeTab = tab.id"
            :class="activeTab === tab.id ? 'bg-primary-600 text-white' : 'bg-gray-100'" class="px-4 py-2 rounded-lg text-sm">
            {{ tab.label }} ({{ tab.count }})
          </button>
        </div>
        <button @click="openModal()" class="px-4 py-2 bg-primary-600 text-white rounded-xl">Buat Laporan</button>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-xl border overflow-hidden">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul Laporan</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kegiatan</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <tr v-for="(item, i) in filteredLaporan" :key="item.id">
              <td class="px-6 py-4 text-sm">{{ i + 1 }}</td>
              <td class="px-6 py-4 font-medium">{{ item.judul }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ item.kegiatan }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ item.tanggal }}</td>
              <td class="px-6 py-4">
                <span :class="[getStatusBadge(item.status).bg, getStatusBadge(item.status).text]" class="px-2 py-1 rounded text-xs">
                  {{ getStatusBadge(item.status).label }}
                </span>
              </td>
              <td class="px-6 py-4 text-center">
                <div class="flex justify-center gap-2">
                  <button @click="openModal(item)" class="text-blue-600 text-sm">Edit</button>
                  <button v-if="item.status === 'draft'" @click="submitLaporan(item.id)" class="text-green-600 text-sm">Ajukan</button>
                  <a v-if="item.file" href="#" class="text-purple-600 text-sm">📄 Unduh</a>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
      <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">{{ editingItem ? 'Edit' : 'Buat' }} Laporan</h2>
        <input v-model="form.judul" placeholder="Judul Laporan" class="w-full mb-3 px-4 py-2 border rounded" />
        <input v-model="form.kegiatan" placeholder="Nama Kegiatan" class="w-full mb-3 px-4 py-2 border rounded" />
        <textarea v-model="form.deskripsi" placeholder="Deskripsi" rows="3" class="w-full mb-3 px-4 py-2 border rounded"></textarea>
        <div class="mb-4">
          <label class="block text-sm text-gray-600 mb-1">Upload File (PDF)</label>
          <input type="file" accept=".pdf" class="w-full" />
        </div>
        <div class="flex gap-3 justify-end">
          <button @click="showModal = false" class="px-4 py-2 border rounded">Batal</button>
          <button @click="saveItem" class="px-4 py-2 bg-primary-600 text-white rounded">{{ saving ? 'Menyimpan...' : 'Simpan' }}</button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
