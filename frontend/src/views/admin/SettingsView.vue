<script setup>
import { ref, computed, onMounted } from "vue";
import AdminLayout from "@/components/layout/AdminLayout.vue";
import { settingsService } from "@/services";
import { useSliderStore } from "@/stores/slider";

// ========== TAB STATE ==========
const activeTab = ref("beranda");
const saving = ref(false);
const loading = ref(true);

// ========== SLIDER DATA (BERANDA) ==========
const sliderStore = useSliderStore();
const showSliderForm = ref(false);
const editingSlide = ref(null);
const sliderFormData = ref({
  image: "",
  title: "",
  caption: "",
});

const openAddSlideForm = () => {
  editingSlide.value = null;
  sliderFormData.value = { image: "", title: "", caption: "" };
  showSliderForm.value = true;
};

const openEditSlideForm = (slide) => {
  editingSlide.value = slide;
  sliderFormData.value = {
    image: slide.image,
    title: slide.title,
    caption: slide.caption,
  };
  showSliderForm.value = true;
};

const saveSlide = async () => {
  if (!sliderFormData.value.image) {
    alert("URL gambar harus diisi!");
    return;
  }

  if (editingSlide.value) {
    await sliderStore.updateSlide(editingSlide.value.id, sliderFormData.value);
  } else {
    await sliderStore.addSlide(sliderFormData.value);
  }

  showSliderForm.value = false;
  sliderFormData.value = { image: "", title: "", caption: "" };
  editingSlide.value = null;
};

const deleteSlide = async (id) => {
  if (confirm("Yakin ingin menghapus slide ini?")) {
    await sliderStore.deleteSlide(id);
  }
};

const handleSliderFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      sliderFormData.value.image = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

// ========== SETTINGS DATA (KONTAK) ==========
const settings = ref({
  siteName: "Bunda PAUD Kota Surabaya",
  email: "",
  phone: "",
  address: "",
  whatsappNumber: "",
  whatsappMessage: "",
  googleMapsUrl: "",
});

const extractedMapSrc = computed(() => {
  const html = settings.value.googleMapsUrl;
  if (!html) return null;
  if (html.startsWith("https://www.google.com/maps/embed")) {
    return html;
  }
  const srcMatch = html.match(/src=["']([^"']+)["']/);
  if (srcMatch && srcMatch[1]) {
    return srcMatch[1];
  }
  return null;
});

const saveSettings = async () => {
  saving.value = true;
  try {
    settingsService.save(settings.value);
    alert("Pengaturan berhasil disimpan!");
  } catch (error) {
    alert("Gagal menyimpan pengaturan");
  } finally {
    saving.value = false;
  }
};

// ========== PROFIL DATA ==========
const profilData = ref({
  visiMisi: "",
  tugasFungsi: "",
  strukturOrganisasi: "",
  artiLogo: "",
});

const saveProfilSettings = () => {
  localStorage.setItem("bp_profil_settings", JSON.stringify(profilData.value));
  alert("Pengaturan profil berhasil disimpan!");
};

const loadProfilSettings = () => {
  const saved = localStorage.getItem("bp_profil_settings");
  if (saved) {
    profilData.value = { ...profilData.value, ...JSON.parse(saved) };
  }
};

// ========== LIFECYCLE ==========
onMounted(async () => {
  // Load slider data
  await sliderStore.fetchAllSlides();

  // Load settings
  const data = settingsService.get();
  settings.value = { ...settings.value, ...data };

  // Load profil settings
  loadProfilSettings();

  loading.value = false;
});
</script>

<template>
  <AdminLayout title="Pengaturan Website">
    <!-- Tabs -->
    <div class="flex flex-wrap gap-2 mb-6">
      <button
        @click="activeTab = 'beranda'"
        class="px-6 py-3 rounded-lg font-medium transition-all"
        :class="
          activeTab === 'beranda'
            ? 'bg-primary-600 text-white shadow-lg'
            : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'
        "
      >
        🏠 Beranda
      </button>
      <button
        @click="activeTab = 'profil'"
        class="px-6 py-3 rounded-lg font-medium transition-all"
        :class="
          activeTab === 'profil'
            ? 'bg-primary-600 text-white shadow-lg'
            : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'
        "
      >
        👤 Profil
      </button>
      <button
        @click="activeTab = 'kontak'"
        class="px-6 py-3 rounded-lg font-medium transition-all"
        :class="
          activeTab === 'kontak'
            ? 'bg-primary-600 text-white shadow-lg'
            : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'
        "
      >
        📞 Kontak
      </button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex justify-center py-12">
      <div
        class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"
      ></div>
    </div>

    <!-- Tab: BERANDA (SLIDER) -->
    <div v-else-if="activeTab === 'beranda'">
      <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600 dark:text-gray-400">
          Kelola slider yang tampil di halaman beranda
        </p>
        <button
          @click="openAddSlideForm"
          class="btn-primary flex items-center gap-2"
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
              d="M12 6v6m0 0v6m0-6h6m-6 0H6"
            />
          </svg>
          Tambah Slide
        </button>
      </div>

      <!-- Info -->
      <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
        <p class="text-sm text-blue-800 dark:text-blue-200">
          <strong>Tips:</strong> Ukuran gambar yang disarankan adalah 1200x450
          pixel.
        </p>
      </div>

      <!-- Slides Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div
          v-for="slide in sliderStore.slides"
          :key="slide.id"
          class="card overflow-hidden"
          :class="{ 'opacity-50': !slide.active }"
        >
          <div class="relative h-48 bg-gray-100 dark:bg-gray-700">
            <img
              :src="slide.image"
              :alt="slide.title"
              class="w-full h-full object-cover"
              @error="
                $event.target.src =
                  'https://placehold.co/600x300/cccccc/666666?text=Gambar+Error'
              "
            />
            <div class="absolute top-2 right-2">
              <span
                class="px-2 py-1 text-xs font-medium rounded"
                :class="
                  slide.active
                    ? 'bg-green-500 text-white'
                    : 'bg-gray-500 text-white'
                "
              >
                {{ slide.active ? "Aktif" : "Nonaktif" }}
              </span>
            </div>
          </div>
          <div class="p-4">
            <h3 class="font-bold text-gray-900 dark:text-white mb-1">
              {{ slide.title || "Tanpa Judul" }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
              {{ slide.caption || "Tidak ada caption" }}
            </p>
            <div class="flex gap-2">
              <button
                @click="openEditSlideForm(slide)"
                class="px-3 py-1 text-sm bg-blue-100 dark:bg-blue-900/30 text-blue-600 rounded hover:bg-blue-200"
              >
                Edit
              </button>
              <button
                @click="sliderStore.toggleActive(slide.id)"
                class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-600 rounded hover:bg-gray-200"
              >
                {{ slide.active ? "Nonaktifkan" : "Aktifkan" }}
              </button>
              <button
                @click="deleteSlide(slide.id)"
                class="px-3 py-1 text-sm bg-red-100 text-red-600 rounded hover:bg-red-200"
              >
                Hapus
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="sliderStore.slides.length === 0" class="text-center py-12">
        <svg
          class="w-16 h-16 mx-auto text-gray-400 mb-4"
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
        <p class="text-gray-600 dark:text-gray-400">
          Belum ada slide. Klik "Tambah Slide" untuk menambahkan.
        </p>
      </div>
    </div>

    <!-- Tab: PROFIL -->
    <div v-else-if="activeTab === 'profil'">
      <p class="text-gray-600 dark:text-gray-400 mb-6">
        Kelola konten halaman profil (dalam pengembangan)
      </p>

      <div class="card p-6 space-y-6">
        <div class="text-center py-12">
          <svg
            class="w-16 h-16 mx-auto text-gray-400 mb-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
            />
          </svg>
          <p class="text-gray-600 dark:text-gray-400">
            Pengaturan profil akan ditambahkan di update selanjutnya
          </p>
        </div>
      </div>
    </div>

    <!-- Tab: KONTAK -->
    <div v-else-if="activeTab === 'kontak'">
      <p class="text-gray-600 dark:text-gray-400 mb-6">
        Kelola informasi kontak dan pengaturan website
      </p>

      <div class="max-w-2xl space-y-6">
        <!-- General Settings -->
        <div class="card p-6">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
            Informasi Umum
          </h2>
          <div class="space-y-4">
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                >Nama Website</label
              >
              <input
                v-model="settings.siteName"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
              />
            </div>
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                >Email</label
              >
              <input
                v-model="settings.email"
                type="email"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
              />
            </div>
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                >Telepon</label
              >
              <input
                v-model="settings.phone"
                type="tel"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
              />
            </div>
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                >Alamat</label
              >
              <textarea
                v-model="settings.address"
                rows="3"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
              ></textarea>
            </div>
          </div>
        </div>

        <!-- WhatsApp Settings -->
        <div class="card p-6">
          <h2
            class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2"
          >
            <svg
              class="w-5 h-5 text-green-500"
              fill="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"
              />
            </svg>
            Hotline WhatsApp
          </h2>
          <div class="space-y-4">
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                >Nomor WhatsApp</label
              >
              <input
                v-model="settings.whatsappNumber"
                type="tel"
                placeholder="6281234567890"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
              />
            </div>
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                >Pesan Default</label
              >
              <textarea
                v-model="settings.whatsappMessage"
                rows="2"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
              ></textarea>
            </div>
          </div>
        </div>

        <!-- Google Maps Settings -->
        <div class="card p-6">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
            Google Maps
          </h2>
          <div class="space-y-4">
            <div>
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                >Kode Embed Google Maps</label
              >
              <textarea
                v-model="settings.googleMapsUrl"
                rows="3"
                placeholder="Paste kode iframe dari Google Maps"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
              ></textarea>
            </div>
            <div v-if="extractedMapSrc" class="rounded-lg overflow-hidden h-48">
              <iframe
                :src="extractedMapSrc"
                width="100%"
                height="100%"
                style="border: 0"
                allowfullscreen=""
                loading="lazy"
              ></iframe>
            </div>
          </div>
        </div>

        <!-- Save Button -->
        <div class="flex justify-end">
          <button
            @click="saveSettings"
            :disabled="saving"
            class="btn-primary flex items-center gap-2"
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
                d="M5 13l4 4L19 7"
              />
            </svg>
            {{ saving ? "Menyimpan..." : "Simpan Pengaturan" }}
          </button>
        </div>
      </div>
    </div>

    <!-- Slider Form Modal -->
    <div
      v-if="showSliderForm"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
    >
      <div
        class="bg-white dark:bg-gray-800 rounded-xl max-w-lg w-full max-h-[90vh] overflow-y-auto"
      >
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white">
            {{ editingSlide ? "Edit Slide" : "Tambah Slide Baru" }}
          </h2>
        </div>
        <div class="p-6 space-y-4">
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >URL Gambar <span class="text-red-500">*</span></label
            >
            <input
              v-model="sliderFormData.image"
              type="text"
              placeholder="https://example.com/gambar.jpg"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
            />
          </div>
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >Atau Upload File</label
            >
            <input
              type="file"
              accept="image/*"
              @change="handleSliderFileUpload"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
            />
          </div>
          <div v-if="sliderFormData.image" class="mt-4">
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >Preview</label
            >
            <img
              :src="sliderFormData.image"
              class="w-full h-32 object-cover rounded-lg"
            />
          </div>
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >Judul (Opsional)</label
            >
            <input
              v-model="sliderFormData.title"
              type="text"
              placeholder="Judul slide"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
            />
          </div>
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
              >Caption (Opsional)</label
            >
            <textarea
              v-model="sliderFormData.caption"
              rows="2"
              placeholder="Deskripsi singkat"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
            ></textarea>
          </div>
        </div>
        <div
          class="p-6 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3"
        >
          <button
            @click="showSliderForm = false"
            class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg"
          >
            Batal
          </button>
          <button @click="saveSlide" class="btn-primary">Simpan</button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
