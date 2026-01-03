import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import { useAppStore } from './stores/app'
import App from './App.vue'
import './style.css'

const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(router)

// Initialize dark mode before mounting
const appStore = useAppStore()
appStore.initDarkMode()

app.mount('#app')
