<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import Navbar from "@/components/layout/Navbar.vue";
import Footer from "@/components/layout/Footer.vue";
import { articleService, documentService } from "@/services";

const route = useRoute();
const searchQuery = ref("");
const activeFilter = ref("semua");
const isSearching = ref(false);
const loading = ref(true);

// Data from API
const allData = ref({
  berita: [],
  dokumen: [],
  layanan: [
    {
      id: 1,
      type: "layanan",
      title: "SIBUNDA - Sistem Informasi Bunda PAUD",
      description: "Platform digital untuk pengelolaan data PAUD di Surabaya",
      category: "Layanan Digital",
      url: "/layanan/konsultasi-paud-hi",
    },
    {
      id: 2,
      type: "layanan",
      title: "Pengaduan Masyarakat",
      description: "Layanan pengaduan dan konsultasi terkait PAUD",
      category: "Layanan Publik",
      url: "/pengaduan",
    },
    {
      id: 3,
      type: "layanan",
      title: "Konsultasi PAUD HI",
      description: "Layanan konsultasi PAUD Holistik Integratif",
      category: "Konsultasi",
      url: "/layanan/konsultasi-paud-hi",
    },
    {
      id: 4,
      type: "layanan",
      title: "Peminjaman Gedung Grha Bunda PAUD",
      description: "Layanan peminjaman gedung untuk kegiatan PAUD",
      category: "Fasilitas",
      url: "/layanan/peminjaman-gedung",
    },
    {
      id: 5,
      type: "layanan",
      title: "Mitra PAUD",
      description: "Program kemitraan dengan berbagai lembaga",
      category: "Kemitraan",
      url: "/mitra-paud",
    },
  ],
  profil: [
    {
      id: 1,
      type: "profil",
      title: "Tentang Bunda PAUD",
      description:
        "Informasi sejarah dan latar belakang Bunda PAUD Kota Surabaya",
      category: "Profil",
      url: "/profil/tentang",
    },
    {
      id: 2,
      type: "profil",
      title: "Visi dan Misi",
      description: "Visi misi organisasi Bunda PAUD Kota Surabaya",
      category: "Profil",
      url: "/profil/visi-misi",
    },
    {
      id: 3,
      type: "profil",
      title: "Struktur Organisasi",
      description: "Struktur organisasi dan tim Bunda PAUD",
      category: "Profil",
      url: "/profil/struktur-organisasi",
    },
    {
      id: 4,
      type: "profil",
      title: "Tugas dan Fungsi",
      description: "Tugas pokok dan fungsi Bunda PAUD",
      category: "Profil",
      url: "/profil/tugas-fungsi",
    },
    {
      id: 5,
      type: "profil",
      title: "Arti Logo",
      description: "Makna dan filosofi logo Bunda PAUD",
      category: "Profil",
      url: "/profil/logo",
    },
    {
      id: 6,
      type: "profil",
      title: "Penghargaan",
      description: "Prestasi dan penghargaan Bunda PAUD",
      category: "Profil",
      url: "/profil/penghargaan",
    },
  ],
});

// Load data from API
const loadData = async () => {
  loading.value = true;
  try {
    // Load articles
    const articlesRes = await articleService.getAll();
    if (articlesRes.data) {
      allData.value.berita = articlesRes.data.map((article) => ({
        id: article.id,
        type: "berita",
        title: article.title,
        description:
          article.excerpt || article.content?.substring(0, 150) + "...",
        category: article.category?.name || "Berita",
        date: article.published_at || article.created_at,
        url: `/berita/${article.slug || article.id}`,
      }));
    }

    // Load documents
    const docsRes = await documentService.getAll();
    if (docsRes.data) {
      allData.value.dokumen = docsRes.data.map((doc) => ({
        id: doc.id,
        type: "dokumen",
        title: doc.title,
        description: doc.description || "Dokumen resmi Bunda PAUD",
        category: doc.category || "Dokumen",
        date: doc.created_at,
        url: "/data/dokumen",
      }));
    }
  } catch (error) {
    console.error("Error loading search data:", error);
  } finally {
    loading.value = false;
  }
};

// Get query from URL and load data
onMounted(() => {
  if (route.query.q) {
    searchQuery.value = route.query.q;
  }
  loadData();
});

const filters = [
  { id: "semua", label: "Semua" },
  { id: "berita", label: "Berita" },
  { id: "dokumen", label: "Dokumen" },
  { id: "layanan", label: "Layanan" },
  { id: "profil", label: "Profil" },
];

const searchResults = computed(() => {
  if (!searchQuery.value.trim()) return [];

  const query = searchQuery.value.toLowerCase();
  let results = [];

  // Search in all categories
  Object.keys(allData.value).forEach((category) => {
    const categoryResults = allData.value[category].filter(
      (item) =>
        item.title.toLowerCase().includes(query) ||
        item.description.toLowerCase().includes(query) ||
        (item.category && item.category.toLowerCase().includes(query))
    );
    results = [...results, ...categoryResults];
  });

  // Filter by active filter
  if (activeFilter.value !== "semua") {
    results = results.filter((item) => item.type === activeFilter.value);
  }

  return results;
});

const resultCounts = computed(() => {
  if (!searchQuery.value.trim())
    return { semua: 0, berita: 0, dokumen: 0, layanan: 0, profil: 0 };

  const query = searchQuery.value.toLowerCase();
  const counts = { semua: 0, berita: 0, dokumen: 0, layanan: 0, profil: 0 };

  Object.keys(allData.value).forEach((category) => {
    const count = allData.value[category].filter(
      (item) =>
        item.title.toLowerCase().includes(query) ||
        item.description.toLowerCase().includes(query)
    ).length;
    counts[category] = count;
    counts.semua += count;
  });

  return counts;
});

const getTypeIcon = (type) => {
  const icons = {
    berita:
      "M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z",
    dokumen:
      "M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z",
    layanan:
      "M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z",
    profil:
      "M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4",
  };
  return icons[type] || icons.berita;
};

const getTypeColor = (type) => {
  const colors = {
    berita: "bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400",
    dokumen:
      "bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400",
    layanan:
      "bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400",
    profil:
      "bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400",
  };
  return colors[type] || colors.berita;
};

const getTypeLabel = (type) => {
  const labels = {
    berita: "Berita",
    dokumen: "Dokumen",
    layanan: "Layanan",
    profil: "Profil",
  };
  return labels[type] || type;
};
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <Navbar />

    <main class="pt-20">
      <!-- Search Header -->
      <div
        class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 py-8"
      >
        <div class="section-container">
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
            Pencarian
          </h1>

          <!-- Search Input -->
          <div class="relative max-w-2xl">
            <svg
              class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
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
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari berita, dokumen, layanan, atau informasi lainnya..."
              class="w-full pl-12 pr-4 py-4 text-lg border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
              autofocus
            />
          </div>

          <!-- Filter Tabs -->
          <div class="flex flex-wrap gap-2 mt-6" v-if="searchQuery">
            <button
              v-for="filter in filters"
              :key="filter.id"
              @click="activeFilter = filter.id"
              class="px-4 py-2 rounded-full text-sm font-medium transition-colors"
              :class="
                activeFilter === filter.id
                  ? 'bg-primary-600 text-white'
                  : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
              "
            >
              {{ filter.label }}
              <span
                v-if="resultCounts[filter.id] > 0"
                class="ml-1 text-xs opacity-75"
                >({{ resultCounts[filter.id] }})</span
              >
            </button>
          </div>
        </div>
      </div>

      <!-- Search Results -->
      <div class="section-container py-8">
        <!-- No Query -->
        <div v-if="!searchQuery" class="text-center py-16">
          <svg
            class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4"
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
          <p class="text-gray-500 dark:text-gray-400 text-lg">
            Ketik kata kunci untuk mulai mencari
          </p>
          <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">
            Cari berita, dokumen, layanan, dan informasi lainnya
          </p>
        </div>

        <!-- No Results -->
        <div v-else-if="searchResults.length === 0" class="text-center py-16">
          <svg
            class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
          <p class="text-gray-500 dark:text-gray-400 text-lg">
            Tidak ditemukan hasil untuk "{{ searchQuery }}"
          </p>
          <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">
            Coba kata kunci lain atau filter berbeda
          </p>
        </div>

        <!-- Results List -->
        <div v-else class="space-y-4">
          <p class="text-gray-600 dark:text-gray-400 mb-6">
            Ditemukan
            <strong class="text-gray-900 dark:text-white">{{
              searchResults.length
            }}</strong>
            hasil untuk "{{ searchQuery }}"
          </p>

          <router-link
            v-for="result in searchResults"
            :key="`${result.type}-${result.id}`"
            :to="result.url"
            class="block card p-5 hover:shadow-lg transition-shadow group"
          >
            <div class="flex gap-4">
              <!-- Icon -->
              <div
                class="w-12 h-12 rounded-lg flex items-center justify-center shrink-0"
                :class="getTypeColor(result.type)"
              >
                <svg
                  class="w-6 h-6"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    :d="getTypeIcon(result.type)"
                  />
                </svg>
              </div>

              <!-- Content -->
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                  <span
                    class="text-xs font-medium px-2 py-0.5 rounded-full"
                    :class="getTypeColor(result.type)"
                  >
                    {{ getTypeLabel(result.type) }}
                  </span>
                  <span v-if="result.category" class="text-xs text-gray-500">{{
                    result.category
                  }}</span>
                </div>
                <h3
                  class="font-semibold text-gray-900 dark:text-white group-hover:text-primary-600 transition-colors"
                >
                  {{ result.title }}
                </h3>
                <p
                  class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2"
                >
                  {{ result.description }}
                </p>
                <p v-if="result.date" class="text-xs text-gray-400 mt-2">
                  {{
                    new Date(result.date).toLocaleDateString("id-ID", {
                      day: "numeric",
                      month: "long",
                      year: "numeric",
                    })
                  }}
                </p>
              </div>

              <!-- Arrow -->
              <div class="flex items-center">
                <svg
                  class="w-5 h-5 text-gray-400 group-hover:text-primary-600 transition-colors"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"
                  />
                </svg>
              </div>
            </div>
          </router-link>
        </div>
      </div>
    </main>

    <Footer />
  </div>
</template>
