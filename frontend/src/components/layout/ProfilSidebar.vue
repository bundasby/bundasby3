<script setup>
import { useRoute } from 'vue-router'

const route = useRoute()

const menuItems = [
  { name: 'Sambutan Bunda PAUD', path: '/profil/tentang' },
  { name: 'Visi, Misi, Motto', path: '/profil/visi-misi' },
  { name: 'Arti Logo Bunda PAUD', path: '/profil/logo' },
  { name: 'Tugas dan Fungsi', path: '/profil/tugas-fungsi' },
  { name: 'Struktur Organisasi', path: '/profil/struktur-organisasi' },
  { name: 'Program Kerja', path: '/program' },
  { name: 'FAQ', path: '/faq' },
  { name: 'Kontak Kami', path: '/kontak' },
  { name: 'Pengaduan', path: '/pengaduan' }
]

const isActive = (path) => route.path === path
</script>

<template>
  <nav class="card p-4 sticky top-24">
    <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Menu Profil</h3>
    <ul class="space-y-1">
      <li v-for="menu in menuItems" :key="menu.path">
        <router-link 
          :to="menu.path"
          class="block px-4 py-2 rounded-lg text-sm transition-colors"
          :class="isActive(menu.path) 
            ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 font-medium' 
            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'"
        >
          {{ menu.name }}
        </router-link>
      </li>
    </ul>
  </nav>
</template>
