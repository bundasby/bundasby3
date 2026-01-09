import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import i18n from './i18n'
import { useAppStore } from './stores/app'
import App from './App.vue'
import './style.css'

const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(router)
app.use(i18n)

// Initialize dark mode before mounting
const appStore = useAppStore()
appStore.initDarkMode()

app.mount('#app')
