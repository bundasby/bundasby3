<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import { authService, userService } from "@/services";
import { useAppStore } from "@/stores/app";
import { getMenuByRole, roleNames, roleColors, roleIcons } from "@/config";

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
const expandedMenus = ref([]); // Track expanded submenus

// Login As feature
const showLoginAsModal = ref(false);
const availableUsers = ref([]);
const loadingUsers = ref(false);
const originalUser = ref(null);

// Get menu items based on current user role
const menuItems = computed(() => {
  const userRole = user.value?.role?.code || 'admin';
  return getMenuByRole(userRole);
});

// Get role display info
const roleInfo = computed(() => {
  const roleCode = user.value?.role?.code || 'admin';
  return {
    name: roleNames[roleCode] || 'User',
    color: roleColors[roleCode] || 'bg-gray-500',
    icon: roleIcons[roleCode] || '👤'
  };
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
  return route.path === path || route.path.startsWith(path + '/');
};

const toggleSubmenu = (title) => {
  const idx = expandedMenus.value.indexOf(title);
  if (idx > -1) {
    expandedMenus.value.splice(idx, 1);
  } else {
    expandedMenus.value.push(title);
  }
};

const isExpanded = (title) => expandedMenus.value.includes(title);

// Load available users for Login As
const loadAvailableUsers = async () => {
  loadingUsers.value = true;
  try {
    const res = await userService.getAll();
    availableUsers.value = res.data.filter(u => 
      u.id !== user.value?.id && u.is_active
    );
  } catch (error) {
    console.error('Failed to load users:', error);
  } finally {
    loadingUsers.value = false;
  }
};

const openLoginAsModal = () => {
  loadAvailableUsers();
  showLoginAsModal.value = true;
};

const loginAs = (targetUser) => {
  if (!isImpersonating.value) {
    localStorage.setItem('original_user', JSON.stringify(user.value));
  }
  localStorage.setItem('demo_user', JSON.stringify(targetUser));
  user.value = targetUser;
  showLoginAsModal.value = false;
  router.push('/admin/dashboard');
  window.location.reload();
};

const backToOriginalAccount = () => {
  const original = localStorage.getItem('original_user');
  if (original) {
    const originalUserData = JSON.parse(original);
    localStorage.setItem('demo_user', JSON.stringify(originalUserData));
    localStorage.removeItem('original_user');
    user.value = originalUserData;
    router.push('/admin/dashboard');
    window.location.reload();
  }
};

onMounted(async () => {
  const currentUser = authService.getUser();
  if (currentUser) {
    user.value = currentUser;
    loading.value = false;
    return;
  }
  
  const demoUser = localStorage.getItem("demo_user");
  if (demoUser) {
    user.value = JSON.parse(demoUser);
    loading.value = false;
    return;
  }

  if (!localStorage.getItem("auth_token")) {
    router.push("/admin/login");
  }
  loading.value = false;
});

const handleLogout = async () => {
  try {
    await authService.logout();
  } catch (e) {}
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
      <div class="w-12 h-12 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
    </div>

    <!-- Layout -->
    <div v-else class="flex">
      <!-- Sidebar -->
      <aside class="w-64 bg-white dark:bg-gray-800 h-screen border-r border-gray-200 dark:border-gray-700 fixed flex flex-col">
        <!-- Logo -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
          <router-link to="/admin/dashboard" class="flex items-center gap-3">
            <img src="/logo-bunda-paud.png" alt="Logo" class="h-10" onerror="this.src='https://placehold.co/40x40/2563eb/ffffff?text=BP'" />
            <div>
              <h1 class="font-bold text-gray-900 dark:text-white">Admin Panel</h1>
              <p class="text-xs text-gray-500">Bunda PAUD Surabaya</p>
            </div>
          </router-link>
          
          <!-- Role Badge -->
          <div class="mt-3 flex items-center gap-2 px-3 py-2 rounded-lg" :class="roleInfo.color + '/10'">
            <span class="text-lg">{{ roleInfo.icon }}</span>
            <span class="text-sm font-medium" :class="roleInfo.color.replace('bg-', 'text-').replace('-500', '-600')">{{ roleInfo.name }}</span>
          </div>
          
          <!-- Back to Public View -->
          <router-link to="/" class="mt-3 flex items-center gap-2 px-3 py-2 bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 rounded-lg text-sm font-medium hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors w-full">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Beranda
          </router-link>
        </div>

        <!-- Navigation (Scrollable) -->
        <nav class="flex-1 overflow-y-auto p-3">
          <ul class="space-y-1">
            <li v-for="item in menuItems" :key="item.title">
              <!-- Menu with submenu -->
              <div v-if="item.children">
                <button
                  @click="toggleSubmenu(item.title)"
                  class="w-full flex items-center justify-between gap-2 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors"
                  :class="isExpanded(item.title) ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'"
                >
                  <div class="flex items-center gap-2">
                    <span class="text-base">{{ item.icon }}</span>
                    <span>{{ item.title }}</span>
                  </div>
                  <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': isExpanded(item.title) }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
                
                <!-- Submenu items -->
                <ul v-show="isExpanded(item.title)" class="mt-1 ml-6 space-y-1 border-l-2 border-gray-200 dark:border-gray-700 pl-3">
                  <li v-for="child in item.children" :key="child.path">
                    <router-link
                      :to="child.path"
                      class="block px-3 py-2 rounded-lg text-sm transition-colors"
                      :class="isActive(child.path) ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700'"
                    >
                      {{ child.title }}
                    </router-link>
                  </li>
                </ul>
              </div>
              
              <!-- Regular menu item -->
              <router-link
                v-else
                :to="item.path"
                class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors"
                :class="isActive(item.path) ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'"
              >
                <span class="text-base">{{ item.icon }}</span>
                <span>{{ item.title }}</span>
                <span v-if="item.badge" class="ml-auto px-2 py-0.5 text-xs bg-red-500 text-white rounded-full">{{ item.badge }}</span>
              </router-link>
            </li>
          </ul>
        </nav>

        <!-- Bottom Section -->
        <div class="flex-shrink-0 p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
          <!-- Impersonating Banner -->
          <div v-if="isImpersonating" class="mb-3 p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg">
            <p class="text-xs text-amber-800 dark:text-amber-300 text-center">⚠️ Sedang login sebagai user lain</p>
            <button @click="backToOriginalAccount" class="w-full mt-2 px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium rounded-lg transition-colors">
              ← Kembali ke Akun Utama
            </button>
          </div>

          <!-- Login As Button -->
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
          <div class="flex items-center justify-between mb-3 pb-3 border-b border-gray-200 dark:border-gray-700">
            <span class="text-sm text-gray-600 dark:text-gray-400">{{ appStore.isDarkMode ? "Mode Gelap" : "Mode Terang" }}</span>
            <button @click="appStore.toggleDarkMode" class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
              <svg v-if="appStore.isDarkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
              </svg>
            </button>
          </div>

          <!-- User Info -->
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center">
                <span class="text-primary-600 dark:text-primary-400 font-medium">{{ user?.name?.charAt(0) || "A" }}</span>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ user?.name || "Admin" }}</p>
                <p class="text-xs text-gray-500">{{ user?.role?.name || "Administrator" }}</p>
              </div>
            </div>
            <button @click="handleLogout" class="flex items-center gap-1 px-3 py-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors text-sm" title="Logout">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
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
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ title }}</h1>
          <p class="text-gray-600 dark:text-gray-400">Selamat datang, {{ user?.name || "Admin" }}!</p>
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
                <span class="text-primary-600 dark:text-primary-400 font-medium">{{ targetUser.name?.charAt(0) || "U" }}</span>
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
          <button @click="showLoginAsModal = false" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            Batal
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
