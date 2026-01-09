<template>
  <div class="language-switcher relative">
    <button 
      @click="toggleDropdown" 
      class="flex items-center gap-1 px-2 py-1.5 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-sm"
      :aria-expanded="isOpen"
    >
      <span>{{ currentFlag }}</span>
      <span class="hidden sm:inline">{{ currentCode }}</span>
      <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': isOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </button>

    <!-- Dropdown -->
    <transition name="dropdown">
      <div v-if="isOpen" class="absolute top-full right-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden z-50">
        <button
          v-for="lang in languages"
          :key="lang.code"
          @click="selectLanguage(lang.code)"
          class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
          :class="{ 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400': locale === lang.code }"
        >
          <span class="text-lg">{{ lang.flag }}</span>
          <span>{{ lang.name }}</span>
          <svg v-if="locale === lang.code" class="w-4 h-4 ml-auto text-primary-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useI18n } from 'vue-i18n'

const { locale } = useI18n()
const isOpen = ref(false)

const languages = [
  { code: 'id', flag: '🇮🇩', name: 'Indonesia' },
  { code: 'en', flag: '🇺🇸', name: 'English' }
]

const currentFlag = computed(() => {
  const lang = languages.find(l => l.code === locale.value)
  return lang?.flag || '🇮🇩'
})

const currentCode = computed(() => {
  return locale.value.toUpperCase()
})

function toggleDropdown() {
  isOpen.value = !isOpen.value
}

function selectLanguage(code) {
  locale.value = code
  localStorage.setItem('locale', code)
  isOpen.value = false
}

function handleClickOutside(event) {
  const el = document.querySelector('.language-switcher')
  if (el && !el.contains(event.target)) {
    isOpen.value = false
  }
}

onMounted(() => document.addEventListener('click', handleClickOutside))
onUnmounted(() => document.removeEventListener('click', handleClickOutside))
</script>

<style scoped>
.dropdown-enter-active, .dropdown-leave-active {
  transition: all 0.2s ease;
}
.dropdown-enter-from, .dropdown-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}
</style>
