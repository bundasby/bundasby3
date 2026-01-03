import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useAppStore = defineStore('app', () => {
  // State
  const isDarkMode = ref(false)
  const isMobileMenuOpen = ref(false)
  const isLoading = ref(false)
  
  // Statistics
  const statistics = ref({
    totalPaud: 0,
    totalLembaga: 0,
    totalGuru: 0,
    totalSiswa: 0
  })
  
  // Actions
  function toggleDarkMode() {
    isDarkMode.value = !isDarkMode.value
    localStorage.setItem('darkMode', isDarkMode.value)
    
    if (isDarkMode.value) {
      document.documentElement.classList.add('dark')
    } else {
      document.documentElement.classList.remove('dark')
    }
  }
  
  function initDarkMode() {
    const savedMode = localStorage.getItem('darkMode')
    if (savedMode !== null) {
      isDarkMode.value = savedMode === 'true'
    } else {
      isDarkMode.value = window.matchMedia('(prefers-color-scheme: dark)').matches
    }
    
    if (isDarkMode.value) {
      document.documentElement.classList.add('dark')
    }
  }
  
  function toggleMobileMenu() {
    isMobileMenuOpen.value = !isMobileMenuOpen.value
  }
  
  function closeMobileMenu() {
    isMobileMenuOpen.value = false
  }
  
  function setStatistics(stats) {
    statistics.value = { ...statistics.value, ...stats }
  }
  
  function setLoading(value) {
    isLoading.value = value
  }
  
  return {
    // State
    isDarkMode,
    isMobileMenuOpen,
    isLoading,
    statistics,
    
    // Actions
    toggleDarkMode,
    initDarkMode,
    toggleMobileMenu,
    closeMobileMenu,
    setStatistics,
    setLoading
  }
})
