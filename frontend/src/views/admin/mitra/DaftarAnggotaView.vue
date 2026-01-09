<script setup>
import { ref, computed } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

// State
const activeTab = ref('anggota')
const searchQuery = ref('')
const showModal = ref(false)
const editingItem = ref(null)
const saving = ref(false)

// Mock data organisasi mitra
const organisasiInfo = ref({
  nama: 'Forum Komunikasi PAUD Surabaya',
  kategori: 'Forum Komunikasi',
  alamat: 'Jl. Jimerto No.25-27, Ketabang, Kec. Genteng, Kota Surabaya',
  telepon: '(031) 5320-1508',
  email: 'forum.paud.surabaya@gmail.com',
  ketua: 'Dr. Siti Aminah, M.Pd',
  statusAnggota: 'Aktif',
  tanggalBergabung: '2024-01-15'
})

// Mock data anggota
const anggotaList = ref([
  { id: 1, nama: 'Dr. Siti Aminah, M.Pd', jabatan: 'Ketua', lembaga: 'IGTKI Surabaya', telepon: '081234567890', email: 'siti.aminah@gmail.com', status: 'active' },
  { id: 2, nama: 'Hj. Dewi Sartika, S.Pd', jabatan: 'Wakil Ketua', lembaga: 'HIMPAUDI Surabaya', telepon: '081234567891', email: 'dewi.sartika@gmail.com', status: 'active' },
  { id: 3, nama: 'Dra. Rina Marlina', jabatan: 'Sekretaris', lembaga: 'PERPAUD Surabaya', telepon: '081234567892', email: 'rina.marlina@gmail.com', status: 'active' },
  { id: 4, nama: 'Ir. Budi Santoso, M.M', jabatan: 'Bendahara', lembaga: 'APT Surabaya', telepon: '081234567893', email: 'budi.santoso@gmail.com', status: 'active' },
  { id: 5, nama: 'Maya Indah, S.Psi', jabatan: 'Anggota', lembaga: 'KKKS PAUD', telepon: '081234567894', email: 'maya.indah@gmail.com', status: 'inactive' }
])

// Form
const form = ref({
  nama: '',
  jabatan: '',
  lembaga: '',
  telepon: '',
  email: '',
  status: 'active'
})

const tabs = [
  { id: 'anggota', label: 'Daftar Anggota', icon: '👥' },
  { id: 'profil', label: 'Profil Organisasi', icon: '🏢' }
]

const jabatanOptions = ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Bendahara', 'Anggota', 'Koordinator']

const stats = computed(() => ({
  total: anggotaList.value.length,
  active: anggotaList.value.filter(a => a.status === 'active').length,
  inactive: anggotaList.value.filter(a => a.status === 'inactive').length
}))

const filteredAnggota = computed(() => {
  if (!searchQuery.value) return anggotaList.value
  const query = searchQuery.value.toLowerCase()
  return anggotaList.value.filter(a => 
    a.nama.toLowerCase().includes(query) ||
    a.lembaga.toLowerCase().includes(query) ||
    a.jabatan.toLowerCase().includes(query)
  )
})

const openModal = (item = null) => {
  if (item) {
    editingItem.value = item
    form.value = { ...item }
  } else {
    editingItem.value = null
    form.value = { nama: '', jabatan: '', lembaga: '', telepon: '', email: '', status: 'active' }
  }
  showModal.value = true
}

const saveItem = async () => {
  saving.value = true
  await new Promise(r => setTimeout(r, 800))
  
  if (editingItem.value) {
    const idx = anggotaList.value.findIndex(a => a.id === editingItem.value.id)
    if (idx !== -1) anggotaList.value[idx] = { ...anggotaList.value[idx], ...form.value }
  } else {
    anggotaList.value.push({ id: Date.now(), ...form.value })
  }
  
  saving.value = false
  showModal.value = false
}

const deleteItem = (id) => {
  if (confirm('Hapus anggota ini?')) {
    anggotaList.value = anggotaList.value.filter(a => a.id !== id)
  }
}
</script>

<template>
  <AdminLayout title="Daftar Anggota">
    <div class="space-y-6">
      <!-- Organization Card -->
      <div class="bg-gradient-to-r from-orange-500 to-amber-500 rounded-2xl p-6 text-white">
        <div class="flex items-start justify-between">
          <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center text-3xl">🤝</div>
            <div>
              <h2 class="text-2xl font-bold">{{ organisasiInfo.nama }}</h2>
              <p class="text-orange-100">{{ organisasiInfo.kategori }}</p>
              <p class="text-sm text-orange-200 mt-1">Ketua: {{ organisasiInfo.ketua }}</p>
            </div>
          </div>
          <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-medium">{{ organisasiInfo.statusAnggota }}</span>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
          <p class="text-sm text-gray-500 dark:text-gray-400">Total Anggota</p>
          <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
          <p class="text-sm text-gray-500 dark:text-gray-400">Aktif</p>
          <p class="text-2xl font-bold text-green-600">{{ stats.active }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
          <p class="text-sm text-gray-500 dark:text-gray-400">Tidak Aktif</p>
          <p class="text-2xl font-bold text-gray-400">{{ stats.inactive }}</p>
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
            :class="activeTab === tab.id ? 'bg-primary-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'"
          >
            <span>{{ tab.icon }}</span>
            {{ tab.label }}
          </button>
        </div>
      </div>

      <!-- Anggota Tab -->
      <div v-if="activeTab === 'anggota'" class="space-y-4">
        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
          <div class="relative flex-1 max-w-md">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input v-model="searchQuery" type="text" placeholder="Cari anggota..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
          </div>
          <button @click="openModal()" class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Anggota
          </button>
        </div>

        <!-- Anggota Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-for="anggota in filteredAnggota" :key="anggota.id" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-amber-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                {{ anggota.nama.charAt(0) }}
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                  <h3 class="font-semibold text-gray-900 dark:text-white truncate">{{ anggota.nama }}</h3>
                  <span v-if="anggota.status === 'inactive'" class="px-1.5 py-0.5 text-xs bg-gray-100 dark:bg-gray-700 text-gray-500 rounded">Tidak Aktif</span>
                </div>
                <p class="text-sm text-primary-600 dark:text-primary-400">{{ anggota.jabatan }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ anggota.lembaga }}</p>
              </div>
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 space-y-2">
              <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                {{ anggota.telepon }}
              </div>
              <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                <span class="truncate">{{ anggota.email }}</span>
              </div>
            </div>

            <div class="mt-4 flex gap-2">
              <button @click="openModal(anggota)" class="flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600">Edit</button>
              <button @click="deleteItem(anggota.id)" class="px-3 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg text-sm font-medium">Hapus</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Profil Tab -->
      <div v-if="activeTab === 'profil'" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Profil Organisasi</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nama Organisasi</label>
            <p class="text-gray-900 dark:text-white">{{ organisasiInfo.nama }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Kategori</label>
            <p class="text-gray-900 dark:text-white">{{ organisasiInfo.kategori }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Alamat</label>
            <p class="text-gray-900 dark:text-white">{{ organisasiInfo.alamat }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Telepon</label>
            <p class="text-gray-900 dark:text-white">{{ organisasiInfo.telepon }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email</label>
            <p class="text-gray-900 dark:text-white">{{ organisasiInfo.email }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal Bergabung</label>
            <p class="text-gray-900 dark:text-white">{{ organisasiInfo.tanggalBergabung }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black/50" @click="showModal = false"></div>
      <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-lg">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ editingItem ? 'Edit' : 'Tambah' }} Anggota</h2>
        </div>
        <div class="p-6 space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
            <input v-model="form.nama" type="text" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jabatan</label>
              <select v-model="form.jabatan" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                <option v-for="jab in jabatanOptions" :key="jab" :value="jab">{{ jab }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lembaga</label>
              <input v-model="form.lembaga" type="text" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telepon</label>
              <input v-model="form.telepon" type="tel" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
              <input v-model="form.email" type="email" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select v-model="form.status" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
              <option value="active">Aktif</option>
              <option value="inactive">Tidak Aktif</option>
            </select>
          </div>
        </div>
        <div class="p-6 border-t border-gray-200 dark:border-gray-700 flex gap-3 justify-end">
          <button @click="showModal = false" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Batal</button>
          <button @click="saveItem" :disabled="saving" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium disabled:opacity-50">
            {{ saving ? 'Menyimpan...' : 'Simpan' }}
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
