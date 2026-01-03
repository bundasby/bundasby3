<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { authService } from '@/services'

const router = useRouter()

const form = ref({
  email: '',
  password: '',
})

const error = ref('')
const loading = ref(false)

// Check if already logged in
onMounted(() => {
  if (authService.isLoggedIn()) {
    router.push('/admin/dashboard')
  }
})

const handleSubmit = async () => {
  error.value = ''
  loading.value = true

  // Demo credentials check (works without backend)
  const DEMO_EMAIL = 'admin@bundapaud.surabaya.go.id'
  const DEMO_PASSWORD = 'password123'

  try {
    // Try real API login first
    await authService.login(form.value)
    router.push('/admin/dashboard')
  } catch (e) {
    // If API fails, check demo credentials
    if (form.value.email === DEMO_EMAIL && form.value.password === DEMO_PASSWORD) {
      // Demo mode - set fake token
      localStorage.setItem('auth_token', 'demo_token_bundapaud_2026')
      localStorage.setItem('demo_user', JSON.stringify({
        name: 'Admin Demo',
        email: DEMO_EMAIL
      }))
      router.push('/admin/dashboard')
    } else {
      error.value = 'Email atau password salah'
    }
  } finally {
    loading.value = false
  }
}

</script>

<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
      <!-- Logo -->
      <div class="text-center mb-8">
        <img 
          src="/logo-bunda-paud.png" 
          alt="Logo Bunda PAUD"
          class="h-20 mx-auto mb-4"
        />
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Panel</h1>
        <p class="text-gray-600 dark:text-gray-400">Bunda PAUD Kota Surabaya</p>
      </div>

      <!-- Login Form -->
      <div class="card p-8">
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Error Message -->
          <div v-if="error" class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <p class="text-sm text-red-600 dark:text-red-400">{{ error }}</p>
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Email
            </label>
            <input 
              v-model="form.email"
              type="email"
              required
              autocomplete="email"
              placeholder="admin@bundapaud.surabaya.go.id"
              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Password
            </label>
            <input 
              v-model="form.password"
              type="password"
              required
              autocomplete="current-password"
              placeholder="••••••••"
              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
          </div>

          <!-- Submit -->
          <button 
            type="submit"
            :disabled="loading"
            class="w-full btn-primary py-4 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="loading" class="flex items-center justify-center gap-2">
              <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Memproses...
            </span>
            <span v-else>Masuk</span>
          </button>
        </form>

        <!-- Back Link -->
        <div class="mt-6 text-center">
          <router-link to="/" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
            ← Kembali ke Beranda
          </router-link>
        </div>
      </div>

      <!-- Demo Credentials -->
      <div class="mt-4 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
        <p class="text-sm text-amber-700 dark:text-amber-400">
          <strong>Demo:</strong> admin@bundapaud.surabaya.go.id / password123
        </p>
      </div>
    </div>
  </div>
</template>
