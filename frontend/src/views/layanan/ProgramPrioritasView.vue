<script setup>
import { ref, onMounted } from "vue";

const loading = ref(true);

const priorityPrograms = ref([
  {
    id: 1,
    title: "PAUD Holistik Integratif (PAUD HI)",
    icon: "🏫",
    description:
      "Program pengembangan anak usia dini secara menyeluruh mencakup aspek pendidikan, kesehatan, gizi, pengasuhan, dan perlindungan anak.",
    features: [
      "Pendidikan berkualitas untuk anak usia dini",
      "Layanan kesehatan dan gizi terintegrasi",
      "Pengasuhan yang baik dan perlindungan anak",
      "Kolaborasi lintas sektor",
    ],
    color: "from-blue-500 to-blue-600",
  },
  {
    id: 2,
    title: "Gerakan 7 KAIH",
    icon: "🌟",
    description:
      "Gerakan 7 Kebaikan untuk Anak Indonesia Hebat sebagai upaya bersama meningkatkan kualitas pendidikan dan pengasuhan anak.",
    features: [
      "Kampanye kesadaran pentingnya PAUD",
      "Pemberdayaan orang tua dan pengasuh",
      "Stimulasi tumbuh kembang anak",
      "Lingkungan ramah anak",
    ],
    color: "from-green-500 to-green-600",
  },
  {
    id: 3,
    title: "Digitalisasi PAUD",
    icon: "💻",
    description:
      "Transformasi digital dalam penyelenggaraan PAUD untuk meningkatkan akses, kualitas, dan tata kelola pendidikan anak usia dini.",
    features: [
      "Platform pembelajaran digital",
      "Sistem informasi manajemen PAUD",
      "Pelatihan literasi digital guru",
      "Monitoring dan evaluasi berbasis data",
    ],
    color: "from-purple-500 to-purple-600",
  },
  {
    id: 4,
    title: "Revitalisasi PAUD",
    icon: "🔄",
    description:
      "Program peningkatan kualitas sarana prasarana dan penguatan kelembagaan satuan PAUD di Kota Surabaya.",
    features: [
      "Rehabilitasi gedung PAUD",
      "Pengadaan APE (Alat Permainan Edukatif)",
      "Peningkatan standar layanan",
      "Akreditasi satuan PAUD",
    ],
    color: "from-orange-500 to-orange-600",
  },
  {
    id: 5,
    title: "Parenting Education",
    icon: "👨‍👩‍👧",
    description:
      "Program edukasi dan pemberdayaan orang tua dalam mendukung tumbuh kembang optimal anak usia dini.",
    features: [
      "Kelas parenting untuk orang tua",
      "Konsultasi tumbuh kembang anak",
      "Pendampingan keluarga",
      "Materi edukasi parenting",
    ],
    color: "from-pink-500 to-pink-600",
  },
  {
    id: 6,
    title: "Inklusi PAUD",
    icon: "🤝",
    description:
      "Program pendidikan inklusif yang memastikan setiap anak termasuk anak berkebutuhan khusus mendapat akses pendidikan yang setara.",
    features: [
      "Identifikasi dan asesmen ABK",
      "Pelatihan guru pendamping khusus",
      "Modifikasi kurikulum inklusif",
      "Sarana prasarana ramah disabilitas",
    ],
    color: "from-teal-500 to-teal-600",
  },
]);

onMounted(() => {
  setTimeout(() => {
    loading.value = false;
  }, 300);
});
</script>

<template>
  <div class="min-h-screen py-8">
    <div class="section-container">
      <!-- Page Header -->
      <div class="text-center max-w-3xl mx-auto mb-12">
        <h1
          class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4"
        >
          Program Prioritas
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400">
          Program-program unggulan Bunda PAUD Kota Surabaya untuk mewujudkan
          pendidikan anak usia dini yang berkualitas dan holistik
        </p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center py-12">
        <div
          class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"
        ></div>
      </div>

      <!-- Programs Grid -->
      <div v-else class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="program in priorityPrograms"
          :key="program.id"
          class="card overflow-hidden hover:shadow-xl transition-all duration-300 group"
        >
          <!-- Card Header with Gradient -->
          <div :class="`bg-gradient-to-r ${program.color} p-6 text-white`">
            <div class="flex items-center gap-4">
              <span class="text-4xl">{{ program.icon }}</span>
              <h3 class="text-xl font-bold">{{ program.title }}</h3>
            </div>
          </div>

          <!-- Card Body -->
          <div class="p-6">
            <p class="text-gray-600 dark:text-gray-400 mb-4">
              {{ program.description }}
            </p>

            <!-- Features List -->
            <ul class="space-y-2">
              <li
                v-for="(feature, index) in program.features"
                :key="index"
                class="flex items-start gap-2 text-sm text-gray-700 dark:text-gray-300"
              >
                <svg
                  class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5"
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
                {{ feature }}
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Call to Action -->
      <div
        class="mt-12 card p-8 bg-gradient-to-r from-primary-600 to-primary-700 text-white text-center"
      >
        <h3 class="text-2xl font-bold mb-4">Ingin Tahu Lebih Lanjut?</h3>
        <p class="text-primary-100 mb-6 max-w-2xl mx-auto">
          Hubungi kami untuk informasi lebih detail tentang program-program
          prioritas Bunda PAUD Kota Surabaya
        </p>
        <div class="flex flex-wrap justify-center gap-4">
          <router-link
            to="/kontak"
            class="inline-flex items-center gap-2 px-6 py-3 bg-white text-primary-600 font-semibold rounded-lg hover:bg-primary-50 transition-colors"
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
                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
              />
            </svg>
            Hubungi Kami
          </router-link>
          <router-link
            to="/layanan/konsultasi-paud-hi"
            class="inline-flex items-center gap-2 px-6 py-3 bg-primary-500 text-white font-semibold rounded-lg hover:bg-primary-400 transition-colors"
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
                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
              />
            </svg>
            Konsultasi PAUD HI
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>
