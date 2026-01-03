import { defineStore } from 'pinia'
import { ref } from 'vue'
import { sliderService } from '@/services'

export const useSliderStore = defineStore('slider', () => {
  // Default slides
  const defaultSlides = [
    {
      id: 1,
      image: 'https://placehold.co/1200x450/2e86c1/ffffff?text=Kegiatan+Bunda+PAUD+1',
      title: 'Konferensi Bunda PAUD 2026',
      caption: 'Pertemuan tahunan seluruh Bunda PAUD se-Kota Surabaya',
      is_active: true,
      active: true
    },
    {
      id: 2,
      image: 'https://placehold.co/1200x450/22c55e/ffffff?text=Kegiatan+Bunda+PAUD+2',
      title: 'Pelatihan Guru PAUD',
      caption: 'Program peningkatan kompetensi guru PAUD',
      is_active: true,
      active: true
    },
    {
      id: 3,
      image: 'https://placehold.co/1200x450/f59e0b/ffffff?text=Kegiatan+Bunda+PAUD+3',
      title: 'Program MBG PAUD',
      caption: 'Makanan Bergizi Gratis untuk anak PAUD',
      is_active: true,
      active: true
    },
    {
      id: 4,
      image: 'https://placehold.co/1200x450/ec4899/ffffff?text=Kegiatan+Bunda+PAUD+4',
      title: 'Hari Anak Nasional',
      caption: 'Perayaan dan kegiatan edukatif untuk anak usia dini',
      is_active: true,
      active: true
    }
  ]

  // Slides data - initialized with defaults
  const slides = ref([...defaultSlides])
  const loading = ref(false)
  const error = ref(null)

  // Load from localStorage on init
  const loadFromLocalStorage = () => {
    const saved = localStorage.getItem('homepage_slides')
    if (saved) {
      try {
        slides.value = JSON.parse(saved)
      } catch (e) {
        console.error('Error parsing slides from localStorage', e)
        slides.value = [...defaultSlides]
      }
    }
  }

  // Save to localStorage
  const saveToLocalStorage = () => {
    localStorage.setItem('homepage_slides', JSON.stringify(slides.value))
  }

  // Initialize
  loadFromLocalStorage()

  // Fetch active slides (public) - Simulation
  const fetchActiveSlides = async () => {
    loading.value = true
    // Simulate network delay
    await new Promise(resolve => setTimeout(resolve, 500))
    loadFromLocalStorage()
    loading.value = false
  }

  // Fetch all slides (admin) - Simulation
  const fetchAllSlides = async () => {
    loading.value = true
    await new Promise(resolve => setTimeout(resolve, 500))
    loadFromLocalStorage()
    loading.value = false
  }

  // Get active slides only
  const getActiveSlides = () => {
    return slides.value.filter(s => s.is_active || s.active)
  }

  // Add new slide
  const addSlide = async (slide) => {
    loading.value = true
    await new Promise(resolve => setTimeout(resolve, 500))
    
    const newId = Math.max(...slides.value.map(s => s.id), 0) + 1
    const newSlide = {
      id: newId,
      image: slide.image || '',
      title: slide.title || '',
      caption: slide.caption || '',
      is_active: true,
      active: true
    }
    
    slides.value.push(newSlide)
    saveToLocalStorage()
    loading.value = false
    return { success: true, data: newSlide }
  }

  // Update slide
  const updateSlide = async (id, data) => {
    loading.value = true
    await new Promise(resolve => setTimeout(resolve, 500))
    
    const index = slides.value.findIndex(s => s.id === id)
    if (index !== -1) {
      slides.value[index] = { ...slides.value[index], ...data }
      // Ensure active property logic is consistent
      if (data.is_active !== undefined) {
        slides.value[index].active = data.is_active
      }
      saveToLocalStorage()
    }
    
    loading.value = false
    return { success: true }
  }

  // Delete slide
  const deleteSlide = async (id) => {
    loading.value = true
    await new Promise(resolve => setTimeout(resolve, 500))
    
    slides.value = slides.value.filter(s => s.id !== id)
    saveToLocalStorage()
    
    loading.value = false
    return { success: true }
  }

  // Toggle active status
  const toggleActive = async (id) => {
    loading.value = true
    await new Promise(resolve => setTimeout(resolve, 500))
    
    const slide = slides.value.find(s => s.id === id)
    if (slide) {
      slide.is_active = !slide.is_active
      slide.active = slide.is_active
      saveToLocalStorage()
    }
    
    loading.value = false
    return { success: true }
  }

  return {
    slides,
    loading,
    error,
    getActiveSlides,
    fetchActiveSlides,
    fetchAllSlides,
    addSlide,
    updateSlide,
    deleteSlide,
    toggleActive,
    loadFromLocalStorage
  }
})
