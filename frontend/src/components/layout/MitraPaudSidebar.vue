<script setup>
import { useRoute } from 'vue-router'

const route = useRoute()

const menuItems = [
  { name: 'Mitra PAUD', path: '/mitra-paud' },
  { name: 'Bunda PAUD Kec. dan Kel.', path: '/bunda-paud' },
  { name: 'Gugus Tugas PAUD HI', path: '/mitra-paud/gugus-tugas' }
]

const isActive = (path) => route.path === path
</script>

<template>
  <nav class="card p-4 sticky top-24">
    <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Menu Mitra PAUD</h3>
    <ul class="space-y-1">
      <li v-for="menu in menuItems" :key="menu.path">
        <router-link 
          :to="menu.path"
          class="block px-4 py-2 rounded-lg text-sm transition-colors"
          :class="isActive(menu.path) 
            ? 'bg-teal-100 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 font-medium' 
            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'"
        >
          {{ menu.name }}
        </router-link>
      </li>
    </ul>
  </nav>
</template>
