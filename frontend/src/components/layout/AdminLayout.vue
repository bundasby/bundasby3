<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import { authService, userService } from "@/services";
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

// Login As feature
const showLoginAsModal = ref(false);
const availableUsers = ref([]);
const loadingUsers = ref(false);
const originalUser = ref(null); // Store original admin/super admin

// All menu items with role restrictions
const allMenuItems = [
  { name: "Dashboard", icon: "home", route: "/admin/dashboard", roles: ["*"] },
  { name: "Kelola User", icon: "user-group", route: "/admin/users", roles: ["super_admin"] },
  { name: "Publikasi & Informasi", icon: "document", route: "/admin/publikasi", roles: ["super_admin", "admin"] },
  { name: "Bunda PAUD", icon: "users", route: "/admin/bunda-paud", roles: ["super_admin", "admin", "bunda_kota"] },
  { name: "Mitra PAUD", icon: "handshake", route: "/admin/mitra-paud", roles: ["super_admin", "admin", "mitra_paud"] },
  { name: "Gugus Tugas", icon: "clipboard-list", route: "/admin/gugus-tugas", roles: ["super_admin", "admin", "gugus_tugas"] },
  { name: "Data PAUD", icon: "database", route: "/admin/data-paud", roles: ["super_admin", "admin", "bunda_kota", "bunda_kecamatan", "kemendikdasmen"] },
  { name: "Peminjaman Gedung", icon: "calendar", route: "/admin/peminjaman", roles: ["super_admin", "admin"] },
  { name: "Konsultasi PAUD", icon: "chat-alt", route: "/admin/konsultasi", roles: ["super_admin", "admin"] },
  { name: "Pengaduan", icon: "chat", route: "/admin/complaints", roles: ["super_admin", "admin"] },
  { name: "Profil", icon: "profile", route: "/admin/profiles", roles: ["super_admin", "admin"] },
  { name: "Pengaturan", icon: "settings", route: "/admin/settings", roles: ["super_admin", "admin"] },
];

// Computed menu items based on current user role
const menuItems = computed(() => {
  const userRole = user.value?.role?.code || 'admin';
  return allMenuItems.filter(item => 
    item.roles.includes('*') || item.roles.includes(userRole)
  );
});

// Check if can use Login As feature
const canLoginAs = computed(() => {
  const role = user.value?.role?.code;
  return role === 'super_admin' || role === 'admin';
});

// Check if currently impersonating
const isImpersonating = computed(() => {
  return localStorage.getItem('original_user') !== null;
});

const isActive = (path) => {
  return route.path === path;
};

// Load available users for Login As
const loadAvailableUsers = async () => {
  loadingUsers.value = true;
  try {
    const res = await userService.getAll();
    // Filter out current user and inactive users
    availableUsers.value = res.data.filter(u => 
      u.id !== user.value?.id && u.is_active
    );
  } catch (error) {
    console.error('Failed to load users:', error);
  } finally {
    loadingUsers.value = false;
  }
};

// Open Login As modal
const openLoginAsModal = () => {
  loadAvailableUsers();
  showLoginAsModal.value = true;
};

// Login As selected user
const loginAs = (targetUser) => {
  // Save original user if not already impersonating
  if (!isImpersonating.value) {
    localStorage.setItem('original_user', JSON.stringify(user.value));
  }
  
  // Switch to target user
  localStorage.setItem('demo_user', JSON.stringify(targetUser));
  user.value = targetUser;
  showLoginAsModal.value = false;
  
  // Reload page to apply new permissions
  router.push('/admin/dashboard');
  window.location.reload();
};

// Back to original account
const backToOriginalAccount = () => {
  const original = localStorage.getItem('original_user');
  if (original) {
    const originalUserData = JSON.parse(original);
    localStorage.setItem('demo_user', JSON.stringify(originalUserData));
    localStorage.removeItem('original_user');
    user.value = originalUserData;
    
    // Reload page
    router.push('/admin/dashboard');
    window.location.reload();
  }
};

onMounted(async () => {
  // First try to get user from authService (new method)
  const currentUser = authService.getUser();
  if (currentUser) {
    user.value = currentUser;
    loading.value = false;
    return;
  }
  
  // Fallback to demo_user
  const demoUser = localStorage.getItem("demo_user");
  if (demoUser) {
    user.value = JSON.parse(demoUser);
    loading.value = false;
    return;
  }

  // If no auth, redirect to login
  if (!localStorage.getItem("auth_token")) {
    router.push("/admin/login");
  }
  loading.value = false;
});

const handleLogout = async () => {
  try {
    await authService.logout();
  } catch (e) {
    // Ignore error
  }
  localStorage.removeItem("demo_user");
  localStorage.removeItem("auth_token");
  localStorage.removeItem("original_user");
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
        class="w-64 bg-white dark:bg-gray-800 h-screen border-r border-gray-200 dark:border-gray-700 fixed flex flex-col"
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
          
          <!-- Back to Public View Button -->
          <router-link 
            to="/" 
            class="mt-4 flex items-center gap-2 px-4 py-2 bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 rounded-lg text-sm font-medium hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors w-full"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Beranda
          </router-link>
        </div>

        <!-- Navigation (Scrollable) -->
        <nav class="flex-1 overflow-y-auto p-4">
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
                <!-- Handshake Icon -->
                <svg
                  v-if="item.icon === 'handshake'"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                  />
                </svg>
                <!-- Clipboard List Icon -->
                <svg
                  v-if="item.icon === 'clipboard-list'"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
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
                <!-- Profile Icon -->
                <svg
                  v-if="item.icon === 'profile'"
                  class="w-5 h-5"
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
                <!-- Database Icon -->
                <svg
                  v-if="item.icon === 'database'"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"
                  />
                </svg>
                <!-- User Group Icon -->
                <svg
                  v-if="item.icon === 'user-group'"
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
                {{ item.name }}
              </router-link>
            </li>
          </ul>
        </nav>

        <!-- Bottom Section (Fixed at bottom, not absolute) -->
        <div
          class="flex-shrink-0 p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800"
        >
          <!-- Impersonating Banner -->
          <div v-if="isImpersonating" class="mb-3 p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg">
            <p class="text-xs text-amber-800 dark:text-amber-300 text-center">⚠️ Sedang login sebagai user lain</p>
            <button
              @click="backToOriginalAccount"
              class="w-full mt-2 px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium rounded-lg transition-colors"
            >
              ← Kembali ke Akun Utama
            </button>
          </div>

          <!-- Login As Button (for Super Admin/Admin) -->
          <button
            v-if="canLoginAs && !isImpersonating"
            @click="openLoginAsModal"
            class="w-full mb-3 px-3 py-2 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 text-sm font-medium rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition-colors flex items-center justify-center gap-2"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Login Sebagai User Lain
          </button>

          <!-- Dark Mode Toggle -->
          <div
            class="flex items-center justify-between mb-3 pb-3 border-b border-gray-200 dark:border-gray-700"
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
                <p class="text-xs text-gray-500">{{ user?.role?.name || "Administrator" }}</p>
              </div>
            </div>
            <button
              @click="handleLogout"
              class="flex items-center gap-1 px-3 py-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors text-sm"
              title="Logout"
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
                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                />
              </svg>
              Keluar
            </button>
          </div>
        </div>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 ml-64 p-8 min-h-screen">
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

    <!-- Login As Modal -->
    <div v-if="showLoginAsModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/50" @click="showLoginAsModal = false"></div>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md mx-4 max-h-[80vh] overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white">Login Sebagai User Lain</h2>
          <p class="text-sm text-gray-500 mt-1">Pilih akun untuk login tanpa perlu input password</p>
        </div>
        
        <div class="p-4 max-h-96 overflow-y-auto">
          <div v-if="loadingUsers" class="text-center py-8">
            <div class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin mx-auto"></div>
            <p class="mt-2 text-gray-500">Memuat daftar user...</p>
          </div>
          
          <div v-else-if="availableUsers.length === 0" class="text-center py-8 text-gray-500">
            Tidak ada user lain yang tersedia
          </div>
          
          <div v-else class="space-y-2">
            <button
              v-for="targetUser in availableUsers"
              :key="targetUser.id"
              @click="loginAs(targetUser)"
              class="w-full p-3 flex items-center gap-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-left"
            >
              <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-primary-600 dark:text-primary-400 font-medium">
                  {{ targetUser.name?.charAt(0) || "U" }}
                </span>
              </div>
              <div class="flex-1 min-w-0">
                <p class="font-medium text-gray-900 dark:text-white truncate">{{ targetUser.name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ targetUser.email }}</p>
              </div>
              <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                {{ targetUser.role?.name || 'User' }}
              </span>
            </button>
          </div>
        </div>
        
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
          <button
            @click="showLoginAsModal = false"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
          >
            Batal
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
