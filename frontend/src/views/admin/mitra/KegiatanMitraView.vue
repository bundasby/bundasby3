<script setup>
import { ref, computed } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const activeTab = ref('semua')
const searchQuery = ref('')
const showModal = ref(false)
const editingItem = ref(null)
const saving = ref(false)

const kegiatanList = ref([
  { id: 1, judul: 'Pelatihan Kurikulum Merdeka', tanggal: '2026-02-15', waktu: '08:00 - 16:00', lokasi: 'Grha Bunda PAUD', peserta: 50, status: 'upcoming', deskripsi: 'Pelatihan implementasi kurikulum merdeka' },
  { id: 2, judul: 'Workshop Pembelajaran Kreatif', tanggal: '2026-01-20', waktu: '09:00 - 15:00', lokasi: 'Aula Dispendik', peserta: 45, status: 'ongoing', deskripsi: 'Workshop media pembelajaran' },
  { id: 3, judul: 'Rapat Koordinasi Bulanan', tanggal: '2026-01-10', waktu: '13:00 - 16:00', lokasi: 'Ruang Rapat', peserta: 20, status: 'completed', deskripsi: 'Rapat koordinasi anggota forum' }
])

const form = ref({ judul: '', tanggal: '', waktu: '', lokasi: '', peserta: 0, deskripsi: '' })

const tabs = [
  { id: 'semua', label: 'Semua', count: kegiatanList.value.length },
  { id: 'upcoming', label: 'Akan Datang', count: kegiatanList.value.filter(k => k.status === 'upcoming').length },
  { id: 'completed', label: 'Selesai', count: kegiatanList.value.filter(k => k.status === 'completed').length }
]

const filteredKegiatan = computed(() => {
  let result = kegiatanList.value
  if (activeTab.value !== 'semua') result = result.filter(k => k.status === activeTab.value)
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase()
    result = result.filter(k => k.judul.toLowerCase().includes(q))
  }
  return result
})

const getStatusBadge = (status) => {
  const badges = {
    upcoming: { bg: 'bg-blue-100', text: 'text-blue-600', label: 'Akan Datang' },
    ongoing: { bg: 'bg-amber-100', text: 'text-amber-600', label: 'Berlangsung' },
    completed: { bg: 'bg-green-100', text: 'text-green-600', label: 'Selesai' }
  }
  return badges[status] || badges.upcoming
}

const openModal = (item = null) => {
  editingItem.value = item
  form.value = item ? { ...item } : { judul: '', tanggal: '', waktu: '', lokasi: '', peserta: 0, deskripsi: '' }
  showModal.value = true
}

const saveItem = async () => {
  saving.value = true
  await new Promise(r => setTimeout(r, 500))
  if (editingItem.value) {
    const idx = kegiatanList.value.findIndex(k => k.id === editingItem.value.id)
    if (idx !== -1) kegiatanList.value[idx] = { ...kegiatanList.value[idx], ...form.value }
  } else {
    kegiatanList.value.push({ id: Date.now(), ...form.value, status: 'upcoming' })
  }
  saving.value = false
  showModal.value = false
}
</script>

<template>
  <AdminLayout title="Kegiatan Mitra">
    <div class="space-y-6">
      <div class="flex gap-4 justify-between">
        <input v-model="searchQuery" type="text" placeholder="Cari..." class="px-4 py-2 border rounded-xl" />
        <button @click="openModal()" class="px-4 py-2 bg-primary-600 text-white rounded-xl">Tambah</button>
      </div>
      
      <div class="flex gap-2">
        <button v-for="tab in tabs" :key="tab.id" @click="activeTab = tab.id" 
          :class="activeTab === tab.id ? 'bg-primary-600 text-white' : 'bg-gray-100'" class="px-4 py-2 rounded-lg">
          {{ tab.label }} ({{ tab.count }})
        </button>
      </div>

      <div class="grid gap-4">
        <div v-for="k in filteredKegiatan" :key="k.id" class="bg-white rounded-xl p-6 border">
          <div class="flex justify-between">
            <h3 class="font-bold">{{ k.judul }}</h3>
            <span :class="[getStatusBadge(k.status).bg, getStatusBadge(k.status).text]" class="px-2 py-1 rounded text-sm">{{ getStatusBadge(k.status).label }}</span>
          </div>
          <p class="text-gray-500 mt-2">{{ k.tanggal }} • {{ k.lokasi }} • {{ k.peserta }} peserta</p>
          <button @click="openModal(k)" class="mt-4 text-blue-600">Edit</button>
        </div>
      </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
      <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">{{ editingItem ? 'Edit' : 'Tambah' }} Kegiatan</h2>
        <input v-model="form.judul" placeholder="Judul" class="w-full mb-3 px-4 py-2 border rounded" />
        <input v-model="form.tanggal" type="date" class="w-full mb-3 px-4 py-2 border rounded" />
        <input v-model="form.lokasi" placeholder="Lokasi" class="w-full mb-3 px-4 py-2 border rounded" />
        <div class="flex gap-3 justify-end">
          <button @click="showModal = false" class="px-4 py-2 border rounded">Batal</button>
          <button @click="saveItem" class="px-4 py-2 bg-primary-600 text-white rounded">Simpan</button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
