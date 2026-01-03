import { ref, onMounted, onUnmounted } from 'vue'

export function useScrollspy(sectionIds, offset = 100) {
  const activeSection = ref(sectionIds[0] || '')

  const handleScroll = () => {
    const scrollPosition = window.scrollY + offset

    for (let i = sectionIds.length - 1; i >= 0; i--) {
      const section = document.getElementById(sectionIds[i])
      if (section && section.offsetTop <= scrollPosition) {
        activeSection.value = sectionIds[i]
        break
      }
    }
  }

  onMounted(() => {
    window.addEventListener('scroll', handleScroll)
    handleScroll() // Initial check
  })

  onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
  })

  const scrollTo = (id) => {
    const element = document.getElementById(id)
    if (element) {
      const y = element.offsetTop - offset + 20
      window.scrollTo({ top: y, behavior: 'smooth' })
    }
  }

  return {
    activeSection,
    scrollTo,
  }
}
