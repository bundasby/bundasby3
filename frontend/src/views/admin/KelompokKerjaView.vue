<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const loading = ref(true)
const searchQuery = ref('')
const showModal = ref(false)
const editingItem = ref(null)

const form = ref({
  name: '',
  ketua: '',
  anggota: [],
  fokus: '',
  kecamatan: '',
  status: 'active'
})

// Mock data
const items = ref([
  { id: 1, name: 'Pokja Kurikulum', ketua: 'Ibu Sri Wahyuni', anggota: ['Ibu Siti', 'Ibu Rina', 'Ibu Dewi'], fokus: 'Pengembangan Kurikulum PAUD HI', kecamatan: 'Gayungan', status: 'active' },
  { id: 2, name: 'Pokja Sarana Prasarana', ketua: 'Ibu Endang', anggota: ['Ibu Yuli', 'Ibu Novi'], fokus: 'Pengelolaan fasilitas PAUD', kecamatan: 'Gayungan', status: 'active' },
  { id: 3, name: 'Pokja Kemitraan', ketua: 'Ibu Ratna', anggota: ['Ibu Maya', 'Ibu Wati', 'Ibu Lina'], fokus: 'Kerjasama dengan mitra', kecamatan: 'Wonocolo', status: 'active' },
  { id: 4, name: 'Pokja Monitoring', ketua: 'Ibu Heni', anggota: ['Ibu Ika'], fokus: 'Monitoring dan evaluasi program', kecamatan: 'Wonocolo', status: 'inactive' }
])

const filteredItems = computed(() => {
  return items.value.filter(item => {
    return item.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
           item.ketua.toLowerCase().includes(searchQuery.value.toLowerCase())
  })
})

const stats = computed(() => ({
  total: items.value.length,
  active: items.value.filter(i => i.status === 'active').length,
  anggota: items.value.reduce((sum, i) => sum + i.anggota.length + 1, 0)
}))

const openModal = (item = null) => {
  if (item) {
    editingItem.value = item
    form.value = { ...item, anggota: [...item.anggota] }
  } else {
    editingItem.value = null
    form.value = { name: '', ketua: '', anggota: [], fokus: '', kecamatan: '', status: 'active' }
  }
  showModal.value = true
}

const saveItem = () => {
  if (editingItem.value) {
    const idx = items.value.findIndex(i => i.id === editingItem.value.id)
    if (idx > -1) items.value[idx] = { ...form.value, id: editingItem.value.id }
  } else {
    items.value.push({ ...form.value, id: Date.now() })
  }
  showModal.value = false
}

const deleteItem = (id) => {
  if (confirm('Yakin ingin menghapus kelompok kerja ini?')) {
    items.value = items.value.filter(i => i.id !== id)
  }
}

onMounted(() => {
  setTimeout(() => { loading.value = false }, 500)
})
</script>

<template>
  <AdminLayout title="Kelompok Kerja">
    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 mb-6">
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Total Pokja</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Pokja Aktif</p>
        <p class="text-2xl font-bold text-green-600">{{ stats.active }}</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">Total Anggota</p>
        <p class="text-2xl font-bold text-primary-600">{{ stats.anggota }}</p>
      </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-3 mb-6">
      <div class="relative flex-1 min-w-[200px]">
        <input v-model="searchQuery" type="text" placeholder="Cari kelompok kerja..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
        <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </div>
      <button @click="openModal()" class="btn-primary flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Pokja
      </button>
    </div>

    <!-- Cards -->
    <div v-if="loading" class="text-center py-12">
      <div class="w-10 h-10 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin mx-auto"></div>
    </div>

    <div v-else class="grid md:grid-cols-2 gap-4">
      <div v-for="item in filteredItems" :key="item.id" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-4">
          <div class="flex items-start justify-between">
            <div>
              <div class="flex items-center gap-2">
                <h3 class="font-semibold text-gray-900 dark:text-white">{{ item.name }}</h3>
                <span class="px-2 py-0.5 rounded-full text-xs font-medium" :class="item.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'">
                  {{ item.status === 'active' ? 'Aktif' : 'Non-aktif' }}
                </span>
              </div>
              <p class="text-sm text-gray-500 mt-1">📍 {{ item.kecamatan }}</p>
            </div>
          </div>
          
          <div class="mt-4 space-y-2">
            <div class="flex items-center gap-2">
              <span class="text-sm text-gray-500 w-20">Ketua:</span>
              <span class="text-sm font-medium text-gray-900 dark:text-white">{{ item.ketua }}</span>
            </div>
            <div class="flex items-start gap-2">
              <span class="text-sm text-gray-500 w-20">Anggota:</span>
              <div class="flex flex-wrap gap-1">
                <span v-for="(anggota, idx) in item.anggota" :key="idx" class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded text-xs text-gray-600 dark:text-gray-400">
                  {{ anggota }}
                </span>
                <span class="text-xs text-gray-400">({{ item.anggota.length }} orang)</span>
              </div>
            </div>
            <div class="flex items-start gap-2">
              <span class="text-sm text-gray-500 w-20">Fokus:</span>
              <span class="text-sm text-gray-600 dark:text-gray-400">{{ item.fokus }}</span>
            </div>
          </div>
        </div>
        
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 flex justify-end gap-2">
          <button @click="openModal(item)" class="px-3 py-1.5 text-sm text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg">Edit</button>
          <button @click="deleteItem(item.id)" class="px-3 py-1.5 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg">Hapus</button>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/50" @click="showModal = false"></div>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-lg mx-4 p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
          {{ editingItem ? 'Edit Kelompok Kerja' : 'Tambah Kelompok Kerja' }}
        </h2>
        
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Pokja</label>
            <input v-model="form.name" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Pokja Kurikulum" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ketua</label>
              <input v-model="form.ketua" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kecamatan</label>
              <input v-model="form.kecamatan" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fokus Kerja</label>
            <textarea v-model="form.fokus" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select v-model="form.status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
              <option value="active">Aktif</option>
              <option value="inactive">Non-aktif</option>
            </select>
          </div>
        </div>
        
        <div class="flex gap-3 mt-6">
          <button @click="showModal = false" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Batal</button>
          <button @click="saveItem" class="flex-1 btn-primary">Simpan</button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
