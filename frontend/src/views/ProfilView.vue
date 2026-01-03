<script setup>
import { RouterView, useRoute } from 'vue-router'

const route = useRoute()

const subMenus = [
  { name: 'Sambutan Bunda PAUD', path: '/profil/tentang' },
  { name: 'Visi, Misi, Motto', path: '/profil/visi-misi' },
  { name: 'Arti Logo Bunda PAUD', path: '/profil/logo' },
  { name: 'Tugas dan Fungsi', path: '/profil/tugas-fungsi' },
  { name: 'Struktur Organisasi', path: '/profil/struktur-organisasi' },
  { name: 'Penghargaan', path: '/profil/penghargaan' },
  { name: 'FAQ', path: '/faq' },
  { name: 'Kontak Kami', path: '/kontak' },
  { name: 'Pengaduan', path: '/pengaduan' }
]

const isActive = (path) => route.path === path
</script>

<template>
  <div>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-primary-600 to-primary-800 text-white py-16">
      <div class="section-container text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Profil</h1>
        <p class="text-lg text-blue-100 max-w-2xl mx-auto">
          Mengenal lebih dekat Bunda PAUD Kota Surabaya.
        </p>
      </div>
    </section>

    <!-- Content -->
    <section class="py-16 bg-white dark:bg-gray-900">
      <div class="section-container">
        <div class="grid lg:grid-cols-4 gap-8">
          <!-- Sidebar -->
          <div class="lg:col-span-1">
            <nav class="card p-4 sticky top-24">
              <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Menu Profil</h3>
              <ul class="space-y-1">
                <li v-for="menu in subMenus" :key="menu.path">
                  <router-link 
                    :to="menu.path"
                    class="block px-4 py-2 rounded-lg text-sm transition-colors"
                    :class="isActive(menu.path) 
                      ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 font-medium' 
                      : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'"
                  >
                    {{ menu.name }}
                  </router-link>
                </li>
              </ul>
            </nav>
          </div>

          <!-- Main Content -->
          <div class="lg:col-span-3">
            <RouterView />
            
            <!-- Default Content jika belum ada sub-route -->
            <div v-if="route.path === '/profil'" class="space-y-8">
              <!-- Visi -->
              <div class="card p-6">
                <h2 class="text-2xl font-bold text-primary-600 mb-4">Visi</h2>
                <p class="text-gray-700 dark:text-gray-300 text-lg">
                  Mewujudkan layanan PAUD berkualitas, holistik-integratif, yang mudah diakses oleh 
                  seluruh anak usia dini di Kota Surabaya menuju generasi emas Indonesia 2045.
                </p>
              </div>
              
              <!-- Misi -->
              <div class="card p-6">
                <h2 class="text-2xl font-bold text-primary-600 mb-4">Misi</h2>
                <ol class="list-decimal list-inside space-y-3 text-gray-700 dark:text-gray-300">
                  <li>Meningkatkan akses dan kualitas layanan PAUD yang merata dan inklusif.</li>
                  <li>Mengembangkan kompetensi pendidik dan tenaga kependidikan PAUD.</li>
                  <li>Memperkuat peran keluarga dan masyarakat dalam pendidikan anak usia dini.</li>
                  <li>Membangun sistem manajemen PAUD yang profesional dan berkelanjutan.</li>
                  <li>Mengoptimalkan kemitraan lintas sektor dalam penyelenggaraan PAUD.</li>
                </ol>
              </div>

              <!-- Quick Links -->
              <div class="grid md:grid-cols-3 gap-4">
                <router-link 
                  v-for="menu in subMenus" 
                  :key="menu.path"
                  :to="menu.path"
                  class="card p-4 text-center hover:border-primary-300 dark:hover:border-primary-600 border-2 border-transparent transition-colors"
                >
                  <h3 class="font-semibold text-gray-900 dark:text-white mb-2">{{ menu.name }}</h3>
                  <span class="text-sm text-primary-600 dark:text-primary-400">Lihat lebih lanjut →</span>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
