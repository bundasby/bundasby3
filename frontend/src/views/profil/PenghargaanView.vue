<script setup>
import { ref, onMounted } from "vue";
import { profileService } from "@/services";

const content = ref(null);
const loading = ref(true);

// Sample awards data
const defaultAwards = [
  {
    id: 1,
    title: "Penghargaan Inovasi Pendidikan Anak Usia Dini",
    year: "2024",
    giver: "Kementerian Pendidikan dan Kebudayaan RI",
    description:
      "Penghargaan atas inovasi program PAUD Holistik Integratif di Kota Surabaya",
    image: null,
  },
  {
    id: 2,
    title: "Best Practice PAUD HI Tingkat Nasional",
    year: "2023",
    giver: "Direktorat PAUD Kemendikbud",
    description:
      "Apresiasi atas penerapan PAUD Holistik Integratif yang komprehensif",
    image: null,
  },
  {
    id: 3,
    title: "Penghargaan Kota Layak Anak",
    year: "2023",
    giver: "Kementerian Pemberdayaan Perempuan dan Perlindungan Anak",
    description:
      "Kontribusi dalam mendukung Kota Surabaya sebagai Kota Layak Anak",
    image: null,
  },
  {
    id: 4,
    title: "Penghargaan Satuan PAUD Terbaik",
    year: "2022",
    giver: "Gubernur Jawa Timur",
    description:
      "Penghargaan untuk pembinaan satuan PAUD terbaik tingkat Provinsi Jawa Timur",
    image: null,
  },
];

const awards = ref(defaultAwards);
const localContent = ref('');

onMounted(async () => {
  try {
    // First check localStorage for admin-edited content
    const saved = localStorage.getItem('bp_profil_settings')
    if (saved) {
      const profilData = JSON.parse(saved)
      if (profilData.penghargaan) {
        localContent.value = profilData.penghargaan
      }
    }
    
    // Also try to load from profileService
    const response = await profileService.getActive();
    if (response.success && response.data["penghargaan"]) {
      content.value = response.data["penghargaan"];
    }
  } catch (e) {
    console.error("Failed to load Penghargaan:", e);
  } finally {
    loading.value = false;
  }
});
</script>

<template>
  <div>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
      Penghargaan
    </h2>
    <p class="text-gray-600 dark:text-gray-400 mb-8">
      Daftar penghargaan yang diterima oleh Pokja Bunda PAUD Kota Surabaya
    </p>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center py-12">
      <div
        class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"
      ></div>
    </div>

    <!-- Admin Content from localStorage -->
    <div v-else-if="localContent" class="space-y-6">
      <div class="card p-8">
        <div class="prose dark:prose-invert max-w-none whitespace-pre-wrap">{{ localContent }}</div>
      </div>
    </div>

    <!-- Dynamic Content from API -->
    <div v-else-if="content" class="space-y-6">
      <div class="card p-8">
        <div
          class="prose dark:prose-invert max-w-none"
          v-html="content.content"
        ></div>
      </div>
    </div>

    <!-- Static Fallback Awards List -->
    <div v-else class="space-y-6">
      <!-- Stats Card -->
      <div
        class="card p-6 bg-gradient-to-r from-primary-600 to-primary-700 text-white"
      >
        <div class="flex items-center gap-4">
          <div class="p-3 bg-white/20 rounded-xl">
            <svg
              class="w-8 h-8"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"
              />
            </svg>
          </div>
          <div>
            <p class="text-primary-100 text-sm">Total Penghargaan</p>
            <p class="text-3xl font-bold">{{ awards.length }}</p>
          </div>
        </div>
      </div>

      <!-- Awards Grid -->
      <div class="grid gap-6 md:grid-cols-2">
        <div
          v-for="award in awards"
          :key="award.id"
          class="card p-6 hover:shadow-lg transition-shadow duration-300"
        >
          <div class="flex items-start gap-4">
            <!-- Award Icon -->
            <div
              class="flex-shrink-0 p-3 bg-amber-100 dark:bg-amber-900/30 rounded-xl"
            >
              <svg
                class="w-8 h-8 text-amber-600 dark:text-amber-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"
                />
              </svg>
            </div>

            <!-- Award Content -->
            <div class="flex-1">
              <div class="flex items-center gap-2 mb-2">
                <span
                  class="px-2 py-1 text-xs font-medium bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 rounded-full"
                >
                  {{ award.year }}
                </span>
              </div>
              <h3
                class="text-lg font-semibold text-gray-900 dark:text-white mb-2"
              >
                {{ award.title }}
              </h3>
              <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                <span class="font-medium">Dari:</span> {{ award.giver }}
              </p>
              <p class="text-gray-600 dark:text-gray-300 text-sm">
                {{ award.description }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Note -->
      <div
        class="mt-8 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg"
      >
        <p class="text-sm text-blue-700 dark:text-blue-400">
          <strong>Catatan:</strong> Daftar penghargaan akan terus diperbarui
          sesuai dengan prestasi terbaru.
        </p>
      </div>
    </div>
  </div>
</template>
