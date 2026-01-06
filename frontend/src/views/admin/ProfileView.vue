<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

// Tabs according to public menu
const activeTab = ref('sambutan')
const tabs = [
  { key: 'sambutan', label: 'Sambutan Bunda PAUD', icon: '👋' },
  { key: 'visiMisi', label: 'Visi & Misi', icon: '🎯' },
  { key: 'artiLogo', label: 'Arti Logo', icon: '🎨' },
  { key: 'tugasFungsi', label: 'Tugas & Fungsi', icon: '📋' },
  { key: 'strukturOrganisasi', label: 'Struktur Organisasi', icon: '🏢' },
  { key: 'penghargaan', label: 'Penghargaan', icon: '🏆' }
]

const loading = ref(true)
const saving = ref(false)

// Data model matching SettingsView usage
const profilData = ref({
  sambutan: '',
  visiMisi: '',
  artiLogo: '',
  tugasFungsi: '',
  strukturOrganisasi: '',
  penghargaan: ''
})

// Load data
onMounted(() => {
  const saved = localStorage.getItem('bp_profil_settings')
  if (saved) {
    profilData.value = { ...profilData.value, ...JSON.parse(saved) }
  }
  loading.value = false
})

// Save data
const saveSettings = () => {
  saving.value = true
  localStorage.setItem('bp_profil_settings', JSON.stringify(profilData.value))
  setTimeout(() => {
    saving.value = false
    alert('Pengaturan profil berhasil disimpan!')
  }, 500)
}
</script>

<template>
  <AdminLayout title="Kelola Profil">
    <div class="flex flex-col md:flex-row gap-6">
      <!-- Sidebar Tabs -->
      <div class="w-full md:w-64 flex-shrink-0">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
          <button
            v-for="tab in tabs"
            :key="tab.key"
            @click="activeTab = tab.key"
            class="w-full text-left px-4 py-3 flex items-center gap-3 transition-colors border-l-4"
            :class="activeTab === tab.key 
              ? 'bg-pink-50 dark:bg-pink-900/20 text-pink-700 dark:text-pink-300 border-pink-600 font-medium' 
              : 'text-gray-600 dark:text-gray-400 border-transparent hover:bg-gray-50 dark:hover:bg-gray-700'"
          >
            <span class="text-lg">{{ tab.icon }}</span>
            <span class="text-sm">{{ tab.label }}</span>
          </button>
        </div>
      </div>

      <!-- Content Area -->
      <div class="flex-1">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 min-h-[500px]">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
              <span class="text-2xl">{{ tabs.find(t => t.key === activeTab)?.icon }}</span>
              Edit {{ tabs.find(t => t.key === activeTab)?.label }}
            </h2>
            <button 
              @click="saveSettings" 
              :disabled="saving"
              class="btn-primary flex items-center gap-2"
            >
              <svg v-if="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
              {{ saving ? 'Menyimpan...' : 'Simpan Perubahan' }}
            </button>
          </div>

          <!-- Editor Area -->
          <div v-if="loading" class="flex justify-center py-12">
            <div class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
          </div>
          
          <div v-else class="space-y-4">
            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200 text-sm rounded-lg flex items-start gap-3">
              <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              <div>
                <p><strong>Tips Format Teks:</strong></p>
                <ul class="list-disc ml-4 mt-1 space-y-1">
                  <li>Gunakan tag <code>&lt;p&gt;</code> untuk paragraf baru.</li>
                  <li>Gunakan <code>&lt;b&gt;teks tebal&lt;/b&gt;</code> untuk menebalkan.</li>
                  <li>Gunakan <code>&lt;ul&gt;&lt;li&gt;item&lt;/li&gt;&lt;/ul&gt;</code> untuk list.</li>
                </ul>
              </div>
            </div>

            <textarea
              v-model="profilData[activeTab]"
              rows="20"
              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-mono text-sm focus:ring-2 focus:ring-primary-500"
              placeholder="Tulis konten di sini..."
            ></textarea>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
