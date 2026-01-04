<script setup>
import { ref, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import { authService } from "@/services";
import { useAppStore } from "@/stores/app";

const props = defineProps({
  title: {
    type: String,
    default: "Dashboard",
  },
});

const router = useRouter();
const route = useRoute();
const appStore = useAppStore();
const user = ref(null);
const loading = ref(true);

const menuItems = [
  { name: "Dashboard", icon: "home", route: "/admin/dashboard" },
  { name: "Slider Beranda", icon: "slider", route: "/admin/sliders" },
  { name: "Berita", icon: "document", route: "/admin/articles" },
  { name: "Galeri", icon: "photo", route: "/admin/galleries" },
  { name: "Dokumen", icon: "folder", route: "/admin/documents" },
  { name: "Bunda PAUD", icon: "users", route: "/admin/bunda-paud" },
  { name: "Peminjaman Gedung", icon: "calendar", route: "/admin/peminjaman" },
  { name: "Konsultasi PAUD", icon: "chat-alt", route: "/admin/konsultasi" },
  { name: "Pengaduan", icon: "chat", route: "/admin/complaints" },
  { name: "Pengaturan", icon: "settings", route: "/admin/settings" },
];

const isActive = (path) => {
  return route.path === path;
};

onMounted(async () => {
  const demoUser = localStorage.getItem("demo_user");
  if (demoUser) {
    user.value = JSON.parse(demoUser);
    loading.value = false;
    return;
  }

  try {
    const response = await authService.getUser();
    user.value = response.data;
  } catch (e) {
    if (!localStorage.getItem("auth_token")) {
      router.push("/admin/login");
    }
  } finally {
    loading.value = false;
  }
});

const handleLogout = async () => {
  try {
    await authService.logout();
  } catch (e) {
    // Ignore error
  }
  localStorage.removeItem("demo_user");
  localStorage.removeItem("auth_token");
  router.push("/admin/login");
};
</script>

<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <!-- Loading -->
    <div v-if="loading" class="flex items-center justify-center min-h-screen">
      <div
        class="w-12 h-12 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"
      ></div>
    </div>

    <!-- Layout -->
    <div v-else class="flex">
      <!-- Sidebar -->
      <aside
        class="w-64 bg-white dark:bg-gray-800 min-h-screen border-r border-gray-200 dark:border-gray-700 fixed"
      >
        <!-- Logo -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <router-link to="/admin/dashboard" class="flex items-center gap-3">
            <img
              src="/logo-bunda-paud.png"
              alt="Logo"
              class="h-10"
              onerror="this.src='https://placehold.co/40x40/2563eb/ffffff?text=BP'"
            />
            <div>
              <h1 class="font-bold text-gray-900 dark:text-white">
                Admin Panel
              </h1>
              <p class="text-xs text-gray-500">Bunda PAUD Surabaya</p>
            </div>
          </router-link>
        </div>

        <!-- Navigation -->
        <nav class="p-4 pb-32">
          <ul class="space-y-1">
            <li v-for="item in menuItems" :key="item.name">
              <router-link
                :to="item.route"
                class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors"
                :class="
                  isActive(item.route)
                    ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400'
                    : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'
                "
              >
                <!-- Home Icon -->
                <svg
                  v-if="item.icon === 'home'"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                  />
                </svg>
                <!-- Slider Icon -->
                <svg
                  v-if="item.icon === 'slider'"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                  />
                </svg>
                <!-- Document Icon -->
                <svg
                  v-if="item.icon === 'document'"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                  />
                </svg>
                <!-- Tag Icon -->
                <svg
                  v-if="item.icon === 'tag'"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                  />
                </svg>
                <!-- Photo Icon -->
                <svg
                  v-if="item.icon === 'photo'"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                  />
                </svg>
                <!-- Folder Icon -->
                <svg
                  v-if="item.icon === 'folder'"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"
                  />
                </svg>
                <!-- Users Icon -->
                <svg
                  v-if="item.icon === 'users'"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                  />
                </svg>
                <!-- Calendar Icon -->
                <svg
                  v-if="item.icon === 'calendar'"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                  />
                </svg>
                <!-- Chat Alt Icon -->
                <svg
                  v-if="item.icon === 'chat-alt'"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"
                  />
                </svg>
                <!-- Chat Icon -->
                <svg
                  v-if="item.icon === 'chat'"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                  />
                </svg>
                <!-- Settings Icon -->
                <svg
                  v-if="item.icon === 'settings'"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                  />
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                  />
                </svg>
                {{ item.name }}
              </router-link>
            </li>
          </ul>
        </nav>

        <!-- Bottom Section -->
        <div
          class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800"
        >
          <!-- Dark Mode Toggle -->
          <div
            class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200 dark:border-gray-700"
          >
            <span class="text-sm text-gray-600 dark:text-gray-400">{{
              appStore.isDarkMode ? "Mode Gelap" : "Mode Terang"
            }}</span>
            <button
              @click="appStore.toggleDarkMode"
              class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
            >
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
          </div>

          <!-- User Info -->
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div
                class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center"
              >
                <span
                  class="text-primary-600 dark:text-primary-400 font-medium"
                >
                  {{ user?.name?.charAt(0) || "A" }}
                </span>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">
                  {{ user?.name || "Admin" }}
                </p>
                <p class="text-xs text-gray-500">Administrator</p>
              </div>
            </div>
            <button
              @click="handleLogout"
              class="p-2 text-gray-500 hover:text-red-500 transition-colors"
              title="Logout"
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
                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                />
              </svg>
            </button>
          </div>
        </div>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 ml-64 p-8">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            {{ title }}
          </h1>
          <p class="text-gray-600 dark:text-gray-400">
            Selamat datang, {{ user?.name || "Admin" }}!
          </p>
        </div>

        <!-- Content Slot -->
        <slot></slot>
      </main>
    </div>
  </div>
</template>
