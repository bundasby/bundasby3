<script setup>
import { ref, computed } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useAppStore } from "@/stores/app";

const router = useRouter();
const route = useRoute();
const appStore = useAppStore();

const navItems = [
  { name: "Beranda", path: "/" },
  {
    name: "Profil",
    path: "/profil",
    children: [
      { name: "Sambutan Bunda PAUD", path: "/profil/tentang" },
      { name: "Visi, Misi, Motto", path: "/profil/visi-misi" },
      { name: "Arti Logo Bunda PAUD", path: "/profil/logo" },
      { name: "Tugas dan Fungsi", path: "/profil/tugas-fungsi" },
      { name: "Struktur Organisasi", path: "/profil/struktur-organisasi" },
      { name: "Penghargaan", path: "/profil/penghargaan" },
      { name: "Program Kerja", path: "/program" },
      { name: "FAQ", path: "/faq" },
      { name: "Kontak Kami", path: "/kontak" },
      { name: "Pengaduan", path: "/pengaduan" },
    ],
  },
  {
    name: "Mitra PAUD",
    path: "/mitra-paud",
    children: [
      { name: "Pendaftaran Mitra PAUD", path: "/mitra-paud/pendaftaran" },
      { name: "Bunda PAUD Kec. dan Kel.", path: "/bunda-paud" },
      { name: "Gugus Tugas PAUD HI", path: "/mitra-paud/gugus-tugas" },
      { name: "Mitra PAUD", path: "/mitra-paud" },
    ],
  },
  {
    name: "Layanan",
    path: "/layanan",
    children: [
      { name: "Program Prioritas", path: "/layanan/program-prioritas" },
      { name: "Konsultasi PAUD HI", path: "/layanan/konsultasi-paud-hi" },
      {
        name: "Peminjaman Gedung Grha Bunda PAUD",
        path: "/layanan/peminjaman-gedung",
      },
      {
        name: "PAUD Negeri Bung Karno (Satu Atap)",
        path: "/layanan/paud-bung-karno",
      },
    ],
  },
  {
    name: "Galeri Bunda",
    path: "/galeri-bunda",
    children: [
      { name: "Berita", path: "/berita" },
      { name: "Galeri", path: "/galeri" },
      { name: "Infografis", path: "/galeri-bunda/infografis" },
      { name: "Majalah/Buku", path: "/galeri-bunda/majalah-buku" },
      { name: "Materi", path: "/galeri-bunda/materi" },
      { name: "Permainan", path: "/galeri-bunda/permainan" },
      { name: "Bunda TV", path: "/galeri-bunda/bunda-tv" },
    ],
  },
  {
    name: "Data",
    path: "/data",
    children: [
      { name: "Data PAUD", path: "/data/paud" },
      { name: "Peraturan", path: "/data/peraturan" },
      { name: "Norma, Prosedur, Kriteria (NPK)", path: "/data/npk" },
      { name: "Laporan", path: "/data/laporan" },
    ],
  },
];

const activeDropdown = ref(null);

const isActive = (path) => {
  return route.path === path || route.path.startsWith(path + "/");
};

const toggleDropdown = (name) => {
  activeDropdown.value = activeDropdown.value === name ? null : name;
};

const closeDropdown = () => {
  activeDropdown.value = null;
};
</script>

<template>
  <header class="fixed top-0 left-0 right-0 z-50 glass-navbar">
    <nav class="section-container">
      <div class="flex items-center justify-between h-16 lg:h-20">
        <!-- Logo -->
        <router-link to="/" class="flex items-center gap-3">
          <img
            src="/logo-bunda-paud.png"
            alt="Logo Bunda PAUD"
            class="h-10 lg:h-12 w-auto"
            onerror="this.src='https://placehold.co/48x48/2563eb/ffffff?text=BP'"
          />
          <div class="hidden sm:block">
            <h1
              class="text-lg font-bold text-primary-700 dark:text-primary-400"
            >
              Bunda PAUD
            </h1>
            <p class="text-xs text-gray-600 dark:text-gray-400">
              Kota Surabaya
            </p>
          </div>
        </router-link>

        <!-- Desktop Navigation -->
        <div class="hidden lg:flex items-center gap-1">
          <template v-for="item in navItems" :key="item.name">
            <!-- With Dropdown (Parent) -->
            <div v-if="item.children" class="relative group">
              <button
                @click="toggleDropdown(item.name)"
                class="flex items-center gap-1 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200"
                :class="{
                  'text-primary-600 dark:text-primary-400': isActive(item.path),
                }"
              >
                {{ item.name }}
                <svg
                  class="w-4 h-4 transition-transform"
                  :class="{ 'rotate-180': activeDropdown === item.name }"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                  />
                </svg>
              </button>

              <!-- Dropdown Menu (Level 1) -->
              <div
                class="absolute top-full left-0 mt-1 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 overflow-visible"
              >
                <div
                  v-for="child in item.children"
                  :key="child.name"
                  class="relative group/sub"
                >
                  <!-- With Submenu (Level 2) -->
                  <div v-if="child.children" class="w-full">
                    <button
                      class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:text-primary-600 dark:hover:text-primary-400 transition-colors flex items-center justify-between"
                    >
                      {{ child.name }}
                      <svg
                        class="w-4 h-4 transform -rotate-90 group-hover/sub:rotate-0 transition-transform"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M19 9l-7 7-7-7"
                        />
                      </svg>
                    </button>

                    <!-- Submenu Dropdown (Level 3) -->
                    <div
                      class="absolute top-0 left-full ml-1 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 py-2 opacity-0 invisible group-hover/sub:opacity-100 group-hover/sub:visible transition-all duration-200 z-50"
                    >
                      <router-link
                        v-for="subchild in child.children"
                        :key="subchild.name"
                        :to="subchild.path"
                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:text-primary-600 dark:hover:text-primary-400 transition-colors"
                        @click="closeDropdown"
                      >
                        {{ subchild.name }}
                      </router-link>
                    </div>
                  </div>

                  <!-- Normal Link (Level 2) -->
                  <router-link
                    v-else
                    :to="child.path"
                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:text-primary-600 dark:hover:text-primary-400 transition-colors"
                    @click="closeDropdown"
                  >
                    {{ child.name }}
                  </router-link>
                </div>
              </div>
            </div>

            <!-- Without Dropdown -->
            <router-link
              v-else
              :to="item.path"
              class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200"
              :class="{
                'text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20':
                  isActive(item.path),
              }"
            >
              {{ item.name }}
            </router-link>
          </template>
        </div>

        <!-- Right Side Actions -->
        <div class="flex items-center gap-2">
          <!-- Search Button -->
          <router-link
            to="/cari"
            class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
            title="Cari"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
              />
            </svg>
          </router-link>

          <!-- Dark Mode Toggle -->
          <button
            @click="appStore.toggleDarkMode"
            class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
            :title="appStore.isDarkMode ? 'Mode Terang' : 'Mode Gelap'"
          >
            <!-- Sun Icon -->
            <svg
              v-if="appStore.isDarkMode"
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
              />
            </svg>
            <!-- Moon Icon -->
            <svg
              v-else
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
              />
            </svg>
          </button>

          <!-- Login Admin Button -->
          <router-link
            to="/admin/login"
            class="hidden sm:inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
          >
            <svg
              class="w-4 h-4"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
              />
            </svg>
            Login
          </router-link>

          <!-- SIBUNDA Link -->
          <a
            href="https://sibunda.surabaya.go.id"
            target="_blank"
            class="hidden sm:inline-flex btn-primary text-sm"
          >
            SIBUNDA
          </a>

          <!-- Mobile Menu Toggle -->
          <button
            @click="appStore.toggleMobileMenu"
            class="lg:hidden p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800"
          >
            <svg
              v-if="!appStore.isMobileMenuOpen"
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
            </svg>
            <svg
              v-else
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile Menu -->
      <div
        v-show="appStore.isMobileMenuOpen"
        class="lg:hidden border-t border-gray-200 dark:border-gray-700 py-4 animate-fade-in"
      >
        <div class="space-y-1">
          <template v-for="item in navItems" :key="item.name">
            <div v-if="item.children">
              <button
                @click="toggleDropdown(item.name)"
                class="w-full flex items-center justify-between px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg"
              >
                {{ item.name }}
                <svg
                  class="w-4 h-4 transition-transform"
                  :class="{ 'rotate-180': activeDropdown === item.name }"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                  />
                </svg>
              </button>
              <div v-show="activeDropdown === item.name" class="pl-4 space-y-1">
                <router-link
                  v-for="child in item.children"
                  :key="child.name"
                  :to="child.path"
                  class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 rounded-lg"
                  @click="appStore.closeMobileMenu"
                >
                  {{ child.name }}
                </router-link>
              </div>
            </div>
            <router-link
              v-else
              :to="item.path"
              class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg"
              @click="appStore.closeMobileMenu"
            >
              {{ item.name }}
            </router-link>
          </template>

          <!-- Mobile SIBUNDA Button -->
          <a
            href="https://sibunda.surabaya.go.id"
            target="_blank"
            class="block mx-4 mt-4 btn-primary text-center"
          >
            Masuk SIBUNDA
          </a>
        </div>
      </div>
    </nav>
  </header>
</template>
