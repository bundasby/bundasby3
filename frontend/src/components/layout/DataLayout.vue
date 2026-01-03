<script setup>
import DataSidebar from '@/components/layout/DataSidebar.vue'

defineProps({
  title: {
    type: String,
    default: 'Data PAUD'
  },
  subtitle: {
    type: String,
    default: ''
  },
  icon: {
    type: String,
    default: '📊'
  }
})
</script>

<template>
  <div>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-blue-600 to-cyan-700 text-white py-12">
      <div class="section-container text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ icon }} {{ title }}</h1>
        <p v-if="subtitle" class="text-blue-100 max-w-2xl mx-auto">
          {{ subtitle }}
        </p>
      </div>
    </section>

    <!-- Main Content with Sidebar -->
    <section class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
      <div class="section-container">
        <div class="grid lg:grid-cols-4 gap-6">
          <!-- Sidebar -->
          <div class="lg:col-span-1 hidden lg:block">
            <DataSidebar />
          </div>

          <!-- Mobile Category Toggle -->
          <div class="lg:hidden mb-4">
            <details class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
              <summary class="p-4 cursor-pointer font-medium text-gray-900 dark:text-white flex items-center justify-between">
                <span>📂 Kategori Data Lainnya</span>
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </summary>
              <div class="p-4 pt-0">
                <DataSidebar />
              </div>
            </details>
          </div>

          <!-- Main Content -->
          <div class="lg:col-span-3">
            <slot></slot>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
