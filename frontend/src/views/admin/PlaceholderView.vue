<script setup>
import { useRoute } from 'vue-router'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const route = useRoute()
const pageName = route.meta?.title || route.name || 'Halaman'
</script>

<template>
  <AdminLayout :title="pageName">
    <div class="flex flex-col items-center justify-center min-h-[60vh]">
      <div class="text-center">
        <div class="w-24 h-24 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center mx-auto mb-6">
          <span class="text-4xl text-white">🚧</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ pageName }}</h1>
        <p class="text-gray-500 dark:text-gray-400 mb-6">Halaman ini sedang dalam pengembangan</p>
        <div class="flex gap-4 justify-center">
          <router-link to="/admin/dashboard" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium">
            Kembali ke Dashboard
          </router-link>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
