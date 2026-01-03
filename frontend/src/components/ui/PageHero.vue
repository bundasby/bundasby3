<script setup>
defineProps({
  title: {
    type: String,
    required: true
  },
  subtitle: {
    type: String,
    default: ''
  },
  badge: {
    type: String,
    default: ''
  }
})
</script>

<template>
  <section class="bg-gradient-to-br from-primary-600 to-primary-800 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <span v-if="badge" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 text-white mb-4">
        {{ badge }}
      </span>
      <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ title }}</h1>
      <p v-if="subtitle" class="text-lg text-blue-100 max-w-2xl mx-auto">
        {{ subtitle }}
      </p>
      <slot></slot>
    </div>
  </section>
</template>
