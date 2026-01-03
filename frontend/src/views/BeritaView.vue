<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { articlesStorage, initializeData } from "@/services/localStorage";

const activeCategory = ref("semua");

const categories = [
  { id: "semua", name: "Semua" },
  { id: "kegiatan", name: "Kegiatan" },
  { id: "edukasi", name: "Edukasi" },
  { id: "pengumuman", name: "Pengumuman" },
  { id: "berita", name: "Berita" },
];

// Load news from localStorage
const news = ref([]);

const loadNews = () => {
  initializeData(); // Ensure data is initialized
  const articles = articlesStorage.getAll({ published: true });
  // Map articles to news format
  news.value = articles.map((article) => ({
    id: article.id,
    slug: article.slug,
    title: article.title,
    excerpt: article.excerpt,
    content: article.content,
    thumbnail:
      article.image || "https://placehold.co/800x400/1e3a5f/ffffff?text=Berita",
    category: article.category,
    author: article.author || "Admin",
    date: article.publishedAt,
  }));
};

onMounted(() => {
  loadNews();
});

const filteredNews = computed(() => {
  if (activeCategory.value === "semua") {
    return news.value;
  }
  return news.value.filter((item) => item.category === activeCategory.value);
});

const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString("id-ID", {
    day: "numeric",
    month: "long",
    year: "numeric",
  });
};

const getCategoryLabel = (category) => {
  const cat = categories.find((c) => c.id === category);
  return cat ? cat.name : category;
};
</script>

<template>
  <div>
    <!-- Hero -->
    <section
      class="bg-gradient-to-br from-primary-600 to-primary-800 text-white py-16"
    >
      <div class="section-container text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Berita & Pengumuman</h1>
        <p class="text-lg text-blue-100 max-w-2xl mx-auto">
          Informasi terkini seputar kegiatan dan program Bunda PAUD Kota
          Surabaya.
        </p>
      </div>
    </section>

    <!-- Filter & News -->
    <section class="py-16 bg-white dark:bg-gray-900">
      <div class="section-container">
        <!-- Category Tabs -->
        <div class="flex flex-wrap gap-2 mb-8">
          <button
            v-for="category in categories"
            :key="category.id"
            @click="activeCategory = category.id"
            class="px-4 py-2 rounded-full text-sm font-medium transition-colors"
            :class="
              activeCategory === category.id
                ? 'bg-primary-600 text-white'
                : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700'
            "
          >
            {{ category.name }}
          </button>
        </div>

        <!-- News Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          <router-link
            v-for="item in filteredNews"
            :key="item.id"
            :to="`/berita/${item.slug}`"
            class="card overflow-hidden card-hover group"
          >
            <!-- Thumbnail -->
            <div class="relative h-48 overflow-hidden">
              <img
                :src="item.thumbnail"
                :alt="item.title"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
              />
              <span class="absolute top-3 left-3 badge badge-primary">
                {{ getCategoryLabel(item.category) }}
              </span>
            </div>

            <!-- Content -->
            <div class="p-5">
              <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                {{ formatDate(item.date) }}
              </p>
              <h3
                class="text-lg font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-primary-600 transition-colors"
              >
                {{ item.title }}
              </h3>
              <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2">
                {{ item.excerpt }}
              </p>
            </div>
          </router-link>
        </div>
      </div>
    </section>
  </div>
</template>
