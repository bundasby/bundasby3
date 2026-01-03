<script setup>
defineProps({
  loading: {
    type: Boolean,
    default: false
  },
  text: {
    type: String,
    default: 'Memuat...'
  }
})
</script>

<template>
  <div v-if="loading" class="flex flex-col items-center justify-center py-12">
    <div class="w-12 h-12 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
    <p class="mt-4 text-gray-600 dark:text-gray-400">{{ text }}</p>
  </div>
  <slot v-else></slot>
</template>
