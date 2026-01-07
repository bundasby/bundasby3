<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  src: {
    type: String,
    required: true
  },
  alt: {
    type: String,
    default: ''
  },
  placeholder: {
    type: String,
    default: 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300"%3E%3Crect fill="%23e2e8f0" width="400" height="300"/%3E%3C/svg%3E'
  },
  class: {
    type: String,
    default: ''
  }
})

const imageRef = ref(null)
const isLoaded = ref(false)
const isInView = ref(false)
const currentSrc = ref(props.placeholder)

let observer = null

const loadImage = () => {
  if (isLoaded.value) return
  
  const img = new Image()
  img.onload = () => {
    currentSrc.value = props.src
    isLoaded.value = true
  }
  img.onerror = () => {
    // Keep placeholder on error
    isLoaded.value = true
  }
  img.src = props.src
}

onMounted(() => {
  if ('IntersectionObserver' in window) {
    observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            isInView.value = true
            loadImage()
            observer.unobserve(entry.target)
          }
        })
      },
      {
        rootMargin: '50px 0px',
        threshold: 0.01
      }
    )
    
    if (imageRef.value) {
      observer.observe(imageRef.value)
    }
  } else {
    // Fallback for browsers without IntersectionObserver
    loadImage()
  }
})

onUnmounted(() => {
  if (observer && imageRef.value) {
    observer.unobserve(imageRef.value)
  }
})
</script>

<template>
  <img 
    ref="imageRef"
    :src="currentSrc"
    :alt="alt"
    :class="[
      props.class,
      'lazy-image',
      { 'lazy-image--loading': !isLoaded, 'lazy-image--loaded': isLoaded }
    ]"
    loading="lazy"
    decoding="async"
  />
</template>

<style scoped>
.lazy-image {
  transition: opacity 0.3s ease-in-out, filter 0.3s ease-in-out;
}

.lazy-image--loading {
  opacity: 0.6;
  filter: blur(5px);
}

.lazy-image--loaded {
  opacity: 1;
  filter: blur(0);
}
</style>
