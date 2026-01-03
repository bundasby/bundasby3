<script setup>
import { ref, onMounted, computed } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const loading = ref(true)
const searchQuery = ref('')
const showModal = ref(false)
const editingItem = ref(null)
const form = ref({ name: '', area: '', type: 'kecamatan', phone: '', photo_url: '' })

const bundaPaudList = ref([
  { id: 1, name: 'Nama Bunda PAUD 1', area: 'Tegalsari', type: 'kecamatan', phone: '081234567890', photo_url: '', is_active: true },
  { id: 2, name: 'Nama Bunda PAUD 2', area: 'Genteng', type: 'kecamatan', phone: '081234567891', photo_url: '', is_active: true },
  { id: 3, name: 'Nama Bunda PAUD 3', area: 'Bubutan', type: 'kecamatan', phone: '081234567892', photo_url: '', is_active: true },
  { id: 4, name: 'Nama Bunda PAUD 4', area: 'Simokerto', type: 'kecamatan', phone: '081234567893', photo_url: '', is_active: false },
])

onMounted(() => setTimeout(() => loading.value = false, 500))

const filteredList = computed(() => {
  if (!searchQuery.value) return bundaPaudList.value
  return bundaPaudList.value.filter(b => 
    b.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    b.area.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

const toggleActive = (item) => item.is_active = !item.is_active

const deleteItem = (id) => {
  if (confirm('Yakin ingin menghapus data ini?')) {
    bundaPaudList.value = bundaPaudList.value.filter(b => b.id !== id)
  }
}

const openAddModal = () => {
  editingItem.value = null
  form.value = { name: '', area: '', type: 'kecamatan', phone: '', photo_url: '' }
  showModal.value = true
}

const editItem = (item) => {
  editingItem.value = item
  form.value = { name: item.name, area: item.area, type: item.type, phone: item.phone, photo_url: item.photo_url || '' }
  showModal.value = true
}

const convertGoogleDriveUrl = (url) => {
  const match = url.match(/\/d\/([a-zA-Z0-9_-]+)/)
  if (match) {
    return `https://drive.google.com/uc?export=view&id=${match[1]}`
  }
  return url
}

const handleSubmit = () => {
  const photoUrl = form.value.photo_url ? convertGoogleDriveUrl(form.value.photo_url) : ''
  
  if (editingItem.value) {
    editingItem.value.name = form.value.name
    editingItem.value.area = form.value.area
    editingItem.value.type = form.value.type
    editingItem.value.phone = form.value.phone
    editingItem.value.photo_url = photoUrl
    alert('Data berhasil diupdate!')
  } else {
    const newId = Math.max(...bundaPaudList.value.map(b => b.id)) + 1
    bundaPaudList.value.push({
      id: newId,
      name: form.value.name,
      area: form.value.area,
      type: form.value.type,
      phone: form.value.phone,
      photo_url: photoUrl,
      is_active: true
    })
    alert('Data berhasil ditambahkan!')
  }
  showModal.value = false
}
</script>

<template>
  <AdminLayout title="Kelola Bunda PAUD">
    <div class="flex items-center justify-between mb-8">
      <p class="text-gray-600 dark:text-gray-400">Data Bunda PAUD Kecamatan dan Kelurahan</p>
      <button @click="openAddModal" class="btn-primary flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Tambah Data
      </button>
    </div>

    <!-- Search -->
    <div class="mb-6">
      <input 
        v-model="searchQuery"
        type="text"
        placeholder="Cari nama atau wilayah..."
        class="w-full max-w-md px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
      />
    </div>

    <div v-if="loading" class="flex justify-center py-12">
      <div class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
    </div>

    <!-- Table -->
    <div v-else class="card overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Wilayah</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tipe</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-for="item in filteredList" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div v-if="item.photo_url" class="w-10 h-10 rounded-full overflow-hidden">
                  <img :src="item.photo_url" :alt="item.name" class="w-full h-full object-cover"/>
                </div>
                <div v-else class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                  <span class="text-primary-600 font-medium">{{ item.name.charAt(0) }}</span>
                </div>
                <div>
                  <p class="font-medium text-gray-900 dark:text-white">{{ item.name }}</p>
                  <p class="text-sm text-gray-500">{{ item.phone }}</p>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ item.area }}</td>
            <td class="px-6 py-4">
              <span class="px-2 py-1 text-xs font-medium rounded-full" :class="item.type === 'kecamatan' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700'">
                {{ item.type === 'kecamatan' ? 'Kecamatan' : 'Kelurahan' }}
              </span>
            </td>
            <td class="px-6 py-4">
              <button @click="toggleActive(item)" class="px-2 py-1 text-xs font-medium rounded-full" :class="item.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'">
                {{ item.is_active ? 'Aktif' : 'Nonaktif' }}
              </button>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <button @click="editItem(item)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
                <button @click="deleteItem(item.id)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.self="showModal = false">
      <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto relative">
        <button @click="showModal = false" class="absolute top-4 right-4 p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pr-8">
          {{ editingItem ? 'Edit Data' : 'Tambah Bunda PAUD' }}
        </h3>

        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
            <input v-model="form.name" type="text" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Wilayah</label>
            <input v-model="form.area" type="text" required placeholder="Nama Kecamatan/Kelurahan" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe</label>
            <select v-model="form.type" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
              <option value="kecamatan">Kecamatan</option>
              <option value="kelurahan">Kelurahan</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No. Telepon</label>
            <input v-model="form.phone" type="tel" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link Foto (Google Drive / URL)</label>
            <input v-model="form.photo_url" type="url" placeholder="https://drive.google.com/file/d/.../view" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
            <p class="text-xs text-gray-500 mt-1">Paste link Google Drive (open access) atau URL foto lainnya</p>
          </div>
          <div class="flex gap-3 pt-4">
            <button type="button" @click="showModal = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
            <button type="submit" class="flex-1 btn-primary">{{ editingItem ? 'Update' : 'Simpan' }}</button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
