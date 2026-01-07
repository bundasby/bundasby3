<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import AdminLayout from "@/components/layout/AdminLayout.vue";
import { userService, roleService, authService } from "@/services";

const router = useRouter();

// State
const users = ref([]);
const roles = ref([]);
const loading = ref(true);
const showModal = ref(false);
const editingUser = ref(null);
const searchQuery = ref("");
const filterRole = ref("");
const filterStatus = ref("");

// Form data
const form = ref({
  name: "",
  email: "",
  password: "",
  phone: "",
  role_id: "",
  kecamatan_id: "",
  kelurahan_id: "",
  organization: "",
  is_active: true,
});

const formError = ref("");
const formLoading = ref(false);

// Check if current user is super admin
const currentUser = authService.getUser();
const isSuperAdmin = computed(() => currentUser?.role?.code === "super_admin");

// Filtered users
const filteredUsers = computed(() => {
  let result = users.value;

  if (searchQuery.value) {
    const search = searchQuery.value.toLowerCase();
    result = result.filter(
      (u) =>
        u.name.toLowerCase().includes(search) ||
        u.email.toLowerCase().includes(search)
    );
  }

  if (filterRole.value) {
    result = result.filter((u) => u.role_id === parseInt(filterRole.value));
  }

  if (filterStatus.value) {
    result = result.filter((u) =>
      filterStatus.value === "active" ? u.is_active : !u.is_active
    );
  }

  return result;
});

// Load data
const loadData = async () => {
  loading.value = true;
  try {
    const [usersRes, rolesRes] = await Promise.all([
      userService.getAll(),
      roleService.getAll(),
    ]);
    users.value = usersRes.data;
    roles.value = rolesRes.data;
  } catch (error) {
    console.error("Failed to load data:", error);
  } finally {
    loading.value = false;
  }
};

// Open modal for create/edit
const openModal = (user = null) => {
  editingUser.value = user;
  formError.value = "";

  if (user) {
    form.value = {
      name: user.name,
      email: user.email,
      password: "",
      phone: user.phone || "",
      role_id: user.role_id,
      kecamatan_id: user.kecamatan_id || "",
      kelurahan_id: user.kelurahan_id || "",
      organization: user.organization || "",
      is_active: user.is_active,
    };
  } else {
    form.value = {
      name: "",
      email: "",
      password: "",
      phone: "",
      role_id: "",
      kecamatan_id: "",
      kelurahan_id: "",
      organization: "",
      is_active: true,
    };
  }

  showModal.value = true;
};

// Close modal
const closeModal = () => {
  showModal.value = false;
  editingUser.value = null;
  formError.value = "";
};

// Submit form
const submitForm = async () => {
  formError.value = "";
  formLoading.value = true;

  try {
    // Validation
    if (!form.value.name || !form.value.email || !form.value.role_id) {
      throw new Error("Nama, email, dan role wajib diisi");
    }

    if (!editingUser.value && !form.value.password) {
      throw new Error("Password wajib diisi untuk user baru");
    }

    if (editingUser.value) {
      await userService.update(editingUser.value.id, form.value);
    } else {
      await userService.create(form.value);
    }

    closeModal();
    loadData();
  } catch (error) {
    formError.value = error.message;
  } finally {
    formLoading.value = false;
  }
};

// Toggle user active status
const toggleActive = async (user) => {
  if (user.role?.code === "super_admin") {
    alert("Tidak dapat menonaktifkan Super Admin");
    return;
  }

  try {
    await userService.toggleActive(user.id);
    loadData();
  } catch (error) {
    console.error("Failed to toggle active:", error);
  }
};

// Delete user
const deleteUser = async (user) => {
  if (user.role?.code === "super_admin") {
    alert("Tidak dapat menghapus Super Admin");
    return;
  }

  if (!confirm(`Apakah Anda yakin ingin menghapus user "${user.name}"?`)) {
    return;
  }

  try {
    await userService.delete(user.id);
    loadData();
  } catch (error) {
    console.error("Failed to delete user:", error);
  }
};

// Get role badge color
const getRoleBadgeColor = (roleCode) => {
  const colors = {
    super_admin: "bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400",
    admin:
      "bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400",
    bunda_kota:
      "bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400",
    bunda_kecamatan:
      "bg-teal-100 text-teal-800 dark:bg-teal-900/30 dark:text-teal-400",
    bunda_kelurahan:
      "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400",
    gugus_tugas:
      "bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400",
    mitra_paud:
      "bg-pink-100 text-pink-800 dark:bg-pink-900/30 dark:text-pink-400",
    forum_komunikasi:
      "bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400",
    kelompok_kerja:
      "bg-cyan-100 text-cyan-800 dark:bg-cyan-900/30 dark:text-cyan-400",
    juri_apresiasi:
      "bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400",
    kemendikdasmen:
      "bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400",
  };
  return colors[roleCode] || colors.kemendikdasmen;
};

// Navigate back
const goBack = () => {
  router.push("/admin/dashboard");
};

onMounted(() => {
  if (!isSuperAdmin.value) {
    router.push("/admin/dashboard");
    return;
  }
  loadData();
});
</script>

<template>
  <AdminLayout title="Manajemen Pengguna">
    <!-- Header Actions -->
    <div class="flex items-center justify-between mb-6">
      <p class="text-gray-500 dark:text-gray-400">
        Kelola user dan hak akses sistem
      </p>
      <button @click="openModal()" class="btn-primary flex items-center gap-2">
        <span>+</span>
        Tambah User
      </button>
    </div>
    <main>
      <!-- Filters -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <!-- Search -->
          <div class="md:col-span-2">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari nama atau email..."
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
            />
          </div>

          <!-- Role filter -->
          <div>
            <select
              v-model="filterRole"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
            >
              <option value="">Semua Role</option>
              <option v-for="role in roles" :key="role.id" :value="role.id">
                {{ role.name }}
              </option>
            </select>
          </div>

          <!-- Status filter -->
          <div>
            <select
              v-model="filterStatus"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
            >
              <option value="">Semua Status</option>
              <option value="active">Aktif</option>
              <option value="inactive">Nonaktif</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="text-center py-12">
        <div
          class="animate-spin w-8 h-8 border-4 border-primary-500 border-t-transparent rounded-full mx-auto"
        ></div>
        <p class="mt-4 text-gray-500 dark:text-gray-400">Memuat data...</p>
      </div>

      <!-- Users Table -->
      <div
        v-else
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden"
      >
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                >
                  User
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                >
                  Role
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                >
                  Wilayah
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                >
                  Status
                </th>
                <th
                  class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                >
                  Aksi
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr
                v-for="user in filteredUsers"
                :key="user.id"
                class="hover:bg-gray-50 dark:hover:bg-gray-700/50"
              >
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white font-bold"
                    >
                      {{ user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div>
                      <div class="font-medium text-gray-900 dark:text-white">
                        {{ user.name }}
                      </div>
                      <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ user.email }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span
                    :class="[
                      'px-2 py-1 text-xs font-medium rounded-full',
                      getRoleBadgeColor(user.role?.code),
                    ]"
                  >
                    {{ user.role?.name || "No Role" }}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                  <span v-if="user.kecamatan_id || user.kelurahan_id">
                    {{ user.kelurahan_id ? `Kel. ${user.kelurahan_id}` : "" }}
                    {{ user.kecamatan_id ? `Kec. ${user.kecamatan_id}` : "" }}
                  </span>
                  <span v-else class="text-gray-400">-</span>
                </td>
                <td class="px-6 py-4">
                  <span
                    :class="[
                      'px-2 py-1 text-xs font-medium rounded-full',
                      user.is_active
                        ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                        : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                    ]"
                  >
                    {{ user.is_active ? "Aktif" : "Nonaktif" }}
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <button
                      @click="openModal(user)"
                      class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg"
                      title="Edit"
                    >
                      ✏️
                    </button>
                    <button
                      @click="toggleActive(user)"
                      :class="[
                        'p-2 rounded-lg',
                        user.is_active
                          ? 'text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20'
                          : 'text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20',
                      ]"
                      :title="user.is_active ? 'Nonaktifkan' : 'Aktifkan'"
                      :disabled="user.role?.code === 'super_admin'"
                    >
                      {{ user.is_active ? "🔒" : "🔓" }}
                    </button>
                    <button
                      @click="deleteUser(user)"
                      class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg"
                      title="Hapus"
                      :disabled="user.role?.code === 'super_admin'"
                    >
                      🗑️
                    </button>
                  </div>
                </td>
              </tr>

              <tr v-if="filteredUsers.length === 0">
                <td
                  colspan="5"
                  class="px-6 py-12 text-center text-gray-500 dark:text-gray-400"
                >
                  Tidak ada user ditemukan
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Stats -->
      <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
          <div class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ users.length }}
          </div>
          <div class="text-sm text-gray-500 dark:text-gray-400">Total User</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
          <div class="text-2xl font-bold text-green-600">
            {{ users.filter((u) => u.is_active).length }}
          </div>
          <div class="text-sm text-gray-500 dark:text-gray-400">User Aktif</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
          <div class="text-2xl font-bold text-red-600">
            {{ users.filter((u) => !u.is_active).length }}
          </div>
          <div class="text-sm text-gray-500 dark:text-gray-400">
            User Nonaktif
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
          <div class="text-2xl font-bold text-purple-600">
            {{ roles.length }}
          </div>
          <div class="text-sm text-gray-500 dark:text-gray-400">Total Role</div>
        </div>
      </div>
    </main>

    <!-- Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 z-50 flex items-center justify-center"
    >
      <div class="absolute inset-0 bg-black/50" @click="closeModal"></div>
      <div
        class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto"
      >
        <div class="p-6">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
            {{ editingUser ? "Edit User" : "Tambah User Baru" }}
          </h2>

          <!-- Error -->
          <div
            v-if="formError"
            class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg"
          >
            <p class="text-sm text-red-600 dark:text-red-400">
              {{ formError }}
            </p>
          </div>

          <form @submit.prevent="submitForm" class="space-y-4">
            <!-- Name -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >
                Nama <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.name"
                type="text"
                required
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
              />
            </div>

            <!-- Email -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >
                Email <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.email"
                type="email"
                required
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
              />
            </div>

            <!-- Password -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >
                Password
                {{ editingUser ? "(kosongkan jika tidak diubah)" : "" }}
                <span v-if="!editingUser" class="text-red-500">*</span>
              </label>
              <input
                v-model="form.password"
                type="password"
                :required="!editingUser"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
              />
            </div>

            <!-- Phone -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >
                Telepon
              </label>
              <input
                v-model="form.phone"
                type="tel"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
              />
            </div>

            <!-- Role -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >
                Role <span class="text-red-500">*</span>
              </label>
              <select
                v-model="form.role_id"
                required
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
              >
                <option value="">Pilih Role</option>
                <option v-for="role in roles" :key="role.id" :value="role.id">
                  {{ role.name }}
                </option>
              </select>
            </div>

            <!-- Kecamatan -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >
                Kecamatan (untuk Bunda PAUD Kecamatan/Kelurahan)
              </label>
              <input
                v-model="form.kecamatan_id"
                type="text"
                placeholder="contoh: tegalsari"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
              />
            </div>

            <!-- Kelurahan -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >
                Kelurahan (untuk Bunda PAUD Kelurahan)
              </label>
              <input
                v-model="form.kelurahan_id"
                type="text"
                placeholder="contoh: wonorejo"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
              />
            </div>

            <!-- Organization -->
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >
                Organisasi/Instansi (untuk Mitra, Forum, dll)
              </label>
              <input
                v-model="form.organization"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
              />
            </div>

            <!-- Active Status -->
            <div class="flex items-center gap-2">
              <input
                v-model="form.is_active"
                type="checkbox"
                id="is_active"
                class="w-4 h-4 text-primary-600 rounded"
              />
              <label
                for="is_active"
                class="text-sm text-gray-700 dark:text-gray-300"
              >
                User Aktif
              </label>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-4">
              <button
                type="button"
                @click="closeModal"
                class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
              >
                Batal
              </button>
              <button
                type="submit"
                :disabled="formLoading"
                class="flex-1 btn-primary disabled:opacity-50"
              >
                {{
                  formLoading
                    ? "Menyimpan..."
                    : editingUser
                    ? "Update"
                    : "Simpan"
                }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
