<script setup>
import { RouterView, useRoute } from 'vue-router'
import { computed } from 'vue'
import Navbar from '@/components/layout/Navbar.vue'
import Footer from '@/components/layout/Footer.vue'
import SupportWidget from '@/components/SupportWidget.vue'

const route = useRoute()

const showNavbar = computed(() => !route.meta.hideNavbar)
const showFooter = computed(() => !route.meta.hideFooter)
const isAdminPage = computed(() => route.path.startsWith('/admin'))
</script>

<template>
  <div class="min-h-screen bg-white dark:bg-gray-900 transition-colors duration-300">
    <Navbar v-if="showNavbar" />
    <main :class="showNavbar ? 'pt-16 lg:pt-20' : ''">
      <RouterView />
    </main>
    <Footer v-if="showFooter" />
    
    <!-- Unified Support Widget (Chat + WhatsApp) -->
    <SupportWidget v-if="!isAdminPage" />
  </div>
</template>
