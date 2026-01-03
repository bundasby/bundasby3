<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import { articlesStorage, initializeData } from '@/services/localStorage'

const router = useRouter()
const loading = ref(true)
const searchQuery = ref('')

// Load articles from localStorage
const articles = ref([])

const loadArticles = () => {
  initializeData() // Ensure data is initialized
  const allArticles = articlesStorage.getAll()
  // Map articles to expected format
  articles.value = allArticles.map(article => ({
    id: article.id,
    title: article.title,
    category: article.category,
    is_published: article.isPublished,
    published_at: article.publishedAt,
    author: article.author || 'Admin'
  }))
}

onMounted(() => {
  loadArticles()
  setTimeout(() => loading.value = false, 300)
})

const filteredArticles = computed(() => {
  if (!searchQuery.value) return articles.value
  return articles.value.filter(a => 
    a.title.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

const getCategoryLabel = (cat) => {
  const labels = { kegiatan: 'Kegiatan', edukasi: 'Edukasi', pengumuman: 'Pengumuman', berita: 'Berita' }
  return labels[cat] || cat
}

const getCategoryColor = (cat) => {
  const colors = {
    kegiatan: 'bg-blue-100 text-blue-700',
    edukasi: 'bg-green-100 text-green-700',
    pengumuman: 'bg-amber-100 text-amber-700',
    berita: 'bg-teal-100 text-teal-700'
  }
  return colors[cat] || 'bg-gray-100 text-gray-700'
}

const formatDate = (date) => {
  if (!date) return '-'
  return new Date(date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
}

const deleteArticle = (id) => {
  if (confirm('Yakin ingin menghapus artikel ini?')) {
    articlesStorage.delete(id)
    loadArticles() // Reload articles after deletion
  }
}

const togglePublish = (article) => {
  const newStatus = !article.is_published
  articlesStorage.update(article.id, { 
    isPublished: newStatus,
    publishedAt: newStatus ? new Date().toISOString().split('T')[0] : article.published_at
  })
  loadArticles() // Reload to reflect changes
}
</script>

<template>
  <AdminLayout title="Kelola Berita">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <p class="text-gray-600 dark:text-gray-400">Manage articles dan pengumuman</p>
      <router-link to="/admin/articles/new" class="btn-primary flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Tambah Berita
      </router-link>
    </div>

    <!-- Search -->
    <div class="card p-4 mb-6">
      <input 
        v-model="searchQuery"
        type="text"
        placeholder="Cari berita..."
        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
      />
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex justify-center py-12">
      <div class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
    </div>

    <!-- Table -->
    <div v-else class="card overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Judul</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Kategori</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tanggal</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-for="article in filteredArticles" :key="article.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
            <td class="px-6 py-4">
              <p class="font-medium text-gray-900 dark:text-white">{{ article.title }}</p>
              <p class="text-sm text-gray-500">By {{ article.author }}</p>
            </td>
            <td class="px-6 py-4">
              <span class="px-2 py-1 text-xs font-medium rounded-full" :class="getCategoryColor(article.category)">
                {{ getCategoryLabel(article.category) }}
              </span>
            </td>
            <td class="px-6 py-4">
              <button 
                @click="togglePublish(article)"
                class="px-2 py-1 text-xs font-medium rounded-full"
                :class="article.is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'"
              >
                {{ article.is_published ? 'Published' : 'Draft' }}
              </button>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
              {{ formatDate(article.published_at) }}
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <router-link :to="`/admin/articles/${article.id}`" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg" title="Edit">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </router-link>
                <button @click="deleteArticle(article.id)" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg" title="Hapus">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AdminLayout>
</template>
