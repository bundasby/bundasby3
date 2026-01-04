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
  // Social Media
  instagram: "",
  facebook: "",
  youtube: "",
  tiktok: "",
  twitter: "",
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
const activeProfilTab = ref("sambutan");
const profilData = ref({
  sambutan: "",
  visiMisi: "",
  artiLogo: "",
  tugasFungsi: "",
  strukturOrganisasi: "",
  penghargaan: "",
  faq: "",
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
        Kelola konten halaman profil
      </p>

      <!-- Profil Sub-tabs -->
      <div class="flex flex-wrap gap-2 mb-6 overflow-x-auto">
        <button 
          v-for="tab in [
            { key: 'sambutan', label: 'Sambutan', icon: '👋' },
            { key: 'visiMisi', label: 'Visi, Misi, Motto', icon: '🎯' },
            { key: 'artiLogo', label: 'Arti Logo', icon: '🏛️' },
            { key: 'tugasFungsi', label: 'Tugas & Fungsi', icon: '📋' },
            { key: 'strukturOrganisasi', label: 'Struktur Organisasi', icon: '🏢' },
            { key: 'penghargaan', label: 'Penghargaan', icon: '🏆' },
            { key: 'faq', label: 'FAQ', icon: '❓' }
          ]"
          :key="tab.key"
          @click="activeProfilTab = tab.key"
          class="px-4 py-2 rounded-lg text-sm font-medium transition-all whitespace-nowrap"
          :class="activeProfilTab === tab.key 
            ? 'bg-primary-600 text-white shadow' 
            : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'"
        >
          {{ tab.icon }} {{ tab.label }}
        </button>
      </div>

      <div class="max-w-3xl">
        <!-- Sambutan -->
        <div v-if="activeProfilTab === 'sambutan'" class="card p-6">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Sambutan Bunda PAUD</h2>
          <p class="text-sm text-gray-500 mb-4">Konten sambutan yang tampil di halaman utama profil</p>
          <textarea 
            v-model="profilData.sambutan"
            rows="12"
            placeholder="Tulis konten sambutan Bunda PAUD di sini..."
            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
          ></textarea>
        </div>

        <!-- Visi Misi Motto -->
        <div v-if="activeProfilTab === 'visiMisi'" class="card p-6">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Visi, Misi & Motto</h2>
          <p class="text-sm text-gray-500 mb-4">Konten visi, misi, dan motto organisasi</p>
          <textarea 
            v-model="profilData.visiMisi"
            rows="12"
            placeholder="Tulis konten visi, misi, dan motto di sini..."
            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
          ></textarea>
        </div>

        <!-- Arti Logo -->
        <div v-if="activeProfilTab === 'artiLogo'" class="card p-6">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Arti Logo Bunda PAUD</h2>
          <p class="text-sm text-gray-500 mb-4">Penjelasan filosofi dan makna logo Bunda PAUD</p>
          <textarea 
            v-model="profilData.artiLogo"
            rows="12"
            placeholder="Tulis konten arti logo di sini..."
            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
          ></textarea>
        </div>

        <!-- Tugas dan Fungsi -->
        <div v-if="activeProfilTab === 'tugasFungsi'" class="card p-6">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Tugas dan Fungsi</h2>
          <p class="text-sm text-gray-500 mb-4">Deskripsi tugas pokok dan fungsi Bunda PAUD</p>
          <textarea 
            v-model="profilData.tugasFungsi"
            rows="12"
            placeholder="Tulis konten tugas dan fungsi di sini..."
            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
          ></textarea>
        </div>

        <!-- Struktur Organisasi -->
        <div v-if="activeProfilTab === 'strukturOrganisasi'" class="card p-6">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Struktur Organisasi</h2>
          <p class="text-sm text-gray-500 mb-4">Konten struktur organisasi Bunda PAUD</p>
          <textarea 
            v-model="profilData.strukturOrganisasi"
            rows="12"
            placeholder="Tulis konten struktur organisasi di sini..."
            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
          ></textarea>
        </div>

        <!-- Penghargaan -->
        <div v-if="activeProfilTab === 'penghargaan'" class="card p-6">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Penghargaan</h2>
          <p class="text-sm text-gray-500 mb-4">Daftar penghargaan yang diterima</p>
          <textarea 
            v-model="profilData.penghargaan"
            rows="12"
            placeholder="Tulis konten penghargaan di sini..."
            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
          ></textarea>
        </div>

        <!-- FAQ -->
        <div v-if="activeProfilTab === 'faq'" class="card p-6">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">FAQ (Frequently Asked Questions)</h2>
          <p class="text-sm text-gray-500 mb-4">Pertanyaan yang sering diajukan</p>
          <textarea 
            v-model="profilData.faq"
            rows="12"
            placeholder="Tulis konten FAQ di sini..."
            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
          ></textarea>
        </div>

        <!-- Save Button -->
        <div class="mt-6 flex justify-end">
          <button
            @click="saveProfilSettings"
            class="btn-primary flex items-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Simpan Pengaturan Profil
          </button>
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

        <!-- Social Media Settings -->
        <div class="card p-6">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-pink-500" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073z"/>
            </svg>
            Sosial Media
          </h2>
          <div class="grid md:grid-cols-2 gap-4">
            <!-- Instagram -->
            <div>
              <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                <svg class="w-4 h-4 text-pink-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
                Instagram
              </label>
              <input v-model="settings.instagram" type="url" placeholder="https://instagram.com/username" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
            <!-- Facebook -->
            <div>
              <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                Facebook
              </label>
              <input v-model="settings.facebook" type="url" placeholder="https://facebook.com/username" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
            <!-- YouTube -->
            <div>
              <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                </svg>
                YouTube
              </label>
              <input v-model="settings.youtube" type="url" placeholder="https://youtube.com/@channel" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
            <!-- TikTok -->
            <div>
              <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                </svg>
                TikTok
              </label>
              <input v-model="settings.tiktok" type="url" placeholder="https://tiktok.com/@username" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            </div>
            <!-- Twitter/X -->
            <div>
              <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
                Twitter/X
              </label>
              <input v-model="settings.twitter" type="url" placeholder="https://x.com/username" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
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
