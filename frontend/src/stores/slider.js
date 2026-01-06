import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/services/api'

export const useSliderStore = defineStore('slider', () => {
  // Slides data
  const slides = ref([])
  const loading = ref(false)
  const error = ref(null)

  // Fetch active slides (public)
  const fetchActiveSlides = async () => {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/sliders')
      if (response.data.success) {
        slides.value = response.data.data.map(s => ({
          ...s,
          active: s.is_active
        }))
      }
    } catch (e) {
      console.error('Error fetching sliders:', e)
      error.value = e.message
    } finally {
      loading.value = false
    }
  }

  // Fetch all slides (admin)
  const fetchAllSlides = async () => {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/sliders/all')
      if (response.data.success) {
        slides.value = response.data.data.map(s => ({
          ...s,
          active: s.is_active
        }))
      }
    } catch (e) {
      // Fallback to public endpoint
      await fetchActiveSlides()
    } finally {
      loading.value = false
    }
  }

  // Get active slides only
  const getActiveSlides = () => {
    return slides.value.filter(s => s.is_active || s.active)
  }

  // Add new slide
  const addSlide = async (slide) => {
    loading.value = true
    try {
      const response = await api.post('/sliders', {
        title: slide.title || '',
        caption: slide.caption || '',
        image: slide.image || '',
        button_text: slide.buttonText || '',
        button_link: slide.buttonLink || '',
        is_active: true
      })
      if (response.data.success) {
        const newSlide = { ...response.data.data, active: response.data.data.is_active }
        slides.value.push(newSlide)
        return { success: true, data: newSlide }
      }
      return { success: false, error: response.data.message }
    } catch (e) {
      console.error('Error adding slider:', e)
      return { success: false, error: e.message }
    } finally {
      loading.value = false
    }
  }

  // Update slide
  const updateSlide = async (id, data) => {
    loading.value = true
    try {
      const response = await api.put(`/sliders/${id}`, {
        ...data,
        is_active: data.active !== undefined ? data.active : true
      })
      
      if (response.data.success) {
        const index = slides.value.findIndex(s => s.id === id)
        if (index !== -1) {
          slides.value[index] = { ...response.data.data, active: response.data.data.is_active }
        }
        return { success: true }
      }
      return { success: false, error: response.data.message }
    } catch (e) {
      console.error('Error updating slider:', e)
      return { success: false, error: e.message }
    } finally {
      loading.value = false
    }
  }

  // Delete slide
  const deleteSlide = async (id) => {
    loading.value = true
    try {
      await api.delete(`/sliders/${id}`)
      slides.value = slides.value.filter(s => s.id !== id)
      return { success: true }
    } catch (e) {
      console.error('Error deleting slider:', e)
      return { success: false, error: e.message }
    } finally {
      loading.value = false
    }
  }

  // Toggle active status
  const toggleActive = async (id) => {
    loading.value = true
    try {
      const response = await api.patch(`/sliders/${id}/toggle`)
      if (response.data.success) {
        const index = slides.value.findIndex(s => s.id === id)
        if (index !== -1) {
          slides.value[index] = { ...response.data.data, active: response.data.data.is_active }
        }
        return { success: true }
      }
      return { success: false, error: response.data.message }
    } catch (e) {
      console.error('Error toggling slider:', e)
      return { success: false, error: e.message }
    } finally {
      loading.value = false
    }
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
    toggleActive
  }
})
