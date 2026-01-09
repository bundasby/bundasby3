// i18n Configuration
import { ref, computed } from 'vue'
import id from './id.js'
import en from './en.js'

// Available locales
const locales = { id, en }

// Current locale (default: Indonesian)
const currentLocale = ref(localStorage.getItem('locale') || 'id')

// Get translation
export function t(key, params = {}) {
  const keys = key.split('.')
  let value = locales[currentLocale.value]
  
  for (const k of keys) {
    value = value?.[k]
    if (value === undefined) {
      console.warn(`Translation key "${key}" not found for locale "${currentLocale.value}"`)
      return key
    }
  }
  
  // Replace params
  if (typeof value === 'string' && Object.keys(params).length > 0) {
    return value.replace(/{(\w+)}/g, (_, p) => params[p] || `{${p}}`)
  }
  
  return value
}

// Set locale
export function setLocale(locale) {
  if (locales[locale]) {
    currentLocale.value = locale
    localStorage.setItem('locale', locale)
    document.documentElement.lang = locale
  }
}

// Get current locale
export function getLocale() {
  return currentLocale.value
}

// Get available locales
export function getAvailableLocales() {
  return [
    { code: 'id', name: 'Bahasa Indonesia', flag: '🇮🇩' },
    { code: 'en', name: 'English', flag: '🇺🇸' }
  ]
}

// Toggle locale
export function toggleLocale() {
  const newLocale = currentLocale.value === 'id' ? 'en' : 'id'
  setLocale(newLocale)
  return newLocale
}

// Composable for Vue components
export function useI18n() {
  return {
    t,
    locale: computed(() => currentLocale.value),
    setLocale,
    getLocale,
    toggleLocale,
    availableLocales: getAvailableLocales()
  }
}

export default { t, setLocale, getLocale, toggleLocale, useI18n }
