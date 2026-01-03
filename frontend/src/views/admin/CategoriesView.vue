<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import { categoriesStorage, initializeData } from '@/services/localStorage'

const loading = ref(true)
const categories = ref([])
const showModal = ref(false)
const editMode = ref(false)
const currentCategory = ref({ id: '', name: '', color: 'blue' })

const colorOptions = [
  { value: 'blue', label: 'Biru', class: 'bg-blue-100 text-blue-700' },
  { value: 'green', label: 'Hijau', class: 'bg-green-100 text-green-700' },
  { value: 'amber', label: 'Kuning', class: 'bg-amber-100 text-amber-700' },
  { value: 'teal', label: 'Tosca', class: 'bg-teal-100 text-teal-700' },
  { value: 'pink', label: 'Pink', class: 'bg-pink-100 text-pink-700' },
  { value: 'purple', label: 'Ungu', class: 'bg-purple-100 text-purple-700' },
  { value: 'red', label: 'Merah', class: 'bg-red-100 text-red-700' },
  { value: 'gray', label: 'Abu-abu', class: 'bg-gray-100 text-gray-700' },
]

const loadCategories = () => {
  initializeData()
  categories.value = categoriesStorage.getAll()
}

onMounted(() => {
  loadCategories()
  setTimeout(() => loading.value = false, 300)
})

const openAddModal = () => {
  editMode.value = false
  currentCategory.value = { id: '', name: '', color: 'blue' }
  showModal.value = true
}

const openEditModal = (category) => {
  editMode.value = true
  currentCategory.value = { ...category }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  currentCategory.value = { id: '', name: '', color: 'blue' }
}

const saveCategory = () => {
  if (!currentCategory.value.name.trim()) {
    alert('Nama jenis berita harus diisi!')
    return
  }

  if (editMode.value) {
    categoriesStorage.update(currentCategory.value.id, {
      name: currentCategory.value.name,
      color: currentCategory.value.color
    })
  } else {
    categoriesStorage.create({
      name: currentCategory.value.name,
      color: currentCategory.value.color
    })
  }

  loadCategories()
  closeModal()
}

const deleteCategory = (id) => {
  if (confirm('Yakin ingin menghapus jenis berita ini? Berita dengan jenis ini tidak akan terpengaruh.')) {
    categoriesStorage.delete(id)
    loadCategories()
  }
}

const getColorClass = (color) => {
  const found = colorOptions.find(c => c.value === color)
  return found ? found.class : 'bg-gray-100 text-gray-700'
}
</script>

<template>
  <AdminLayout title="Kelola Jenis Berita">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <p class="text-gray-600 dark:text-gray-400">Tambah, edit, atau hapus jenis berita</p>
      <button @click="openAddModal" class="btn-primary flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Tambah Jenis Berita
      </button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex justify-center py-12">
      <div class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
    </div>

    <!-- Categories List -->
    <div v-else class="card overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">No</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama Jenis</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Warna Label</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-for="(cat, index) in categories" :key="cat.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ index + 1 }}</td>
            <td class="px-6 py-4">
              <span class="font-medium text-gray-900 dark:text-white">{{ cat.name }}</span>
            </td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 text-xs font-medium rounded-full" :class="getColorClass(cat.color)">
                {{ cat.name }}
              </span>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <button @click="openEditModal(cat)" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg" title="Edit">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
                <button @click="deleteCategory(cat.id)" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg" title="Hapus">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="categories.length === 0">
            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
              Belum ada jenis berita. Klik "Tambah Jenis Berita" untuk membuat.
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            {{ editMode ? 'Edit Jenis Berita' : 'Tambah Jenis Berita Baru' }}
          </h3>
        </div>
        <div class="p-6 space-y-4">
          <!-- Name -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Nama Jenis Berita <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="currentCategory.name"
              type="text"
              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
              placeholder="Contoh: Agenda, Liputan, dll"
            />
          </div>
          
          <!-- Color -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Warna Label
            </label>
            <div class="grid grid-cols-4 gap-2">
              <button
                v-for="color in colorOptions"
                :key="color.value"
                type="button"
                @click="currentCategory.color = color.value"
                class="px-3 py-2 text-xs font-medium rounded-lg border-2 transition-all"
                :class="[
                  color.class,
                  currentCategory.color === color.value ? 'border-gray-900 dark:border-white ring-2 ring-offset-2' : 'border-transparent'
                ]"
              >
                {{ color.label }}
              </button>
            </div>
          </div>

          <!-- Preview -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preview</label>
            <span class="px-3 py-1 text-xs font-medium rounded-full" :class="getColorClass(currentCategory.color)">
              {{ currentCategory.name || 'Nama Jenis' }}
            </span>
          </div>
        </div>
        <div class="p-6 bg-gray-50 dark:bg-gray-900 flex justify-end gap-3">
          <button @click="closeModal" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg">
            Batal
          </button>
          <button @click="saveCategory" class="btn-primary">
            {{ editMode ? 'Simpan Perubahan' : 'Tambah' }}
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
