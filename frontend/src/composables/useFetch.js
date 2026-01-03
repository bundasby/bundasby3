import { ref, computed } from 'vue'

export function useFetch(fetchFn) {
  const data = ref(null)
  const error = ref(null)
  const loading = ref(false)

  const execute = async (...args) => {
    loading.value = true
    error.value = null
    
    try {
      const result = await fetchFn(...args)
      data.value = result.data || result
      return result
    } catch (e) {
      error.value = e.response?.data?.message || e.message || 'Terjadi kesalahan'
      throw e
    } finally {
      loading.value = false
    }
  }

  const reset = () => {
    data.value = null
    error.value = null
    loading.value = false
  }

  return {
    data,
    error,
    loading,
    execute,
    reset,
  }
}
