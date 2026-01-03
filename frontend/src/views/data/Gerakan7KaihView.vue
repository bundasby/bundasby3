<script setup>
import { ref } from 'vue'
import DataLayout from '@/components/layout/DataLayout.vue'

// Data Gerakan 7 KAIH
const kaihItems = ref([
  { 
    id: 1, 
    title: 'Kelompok Kerja Ayah Ibu Hebat (KAIH)', 
    description: 'Pembentukan kelompok kerja untuk mendukung pengasuhan anak usia dini',
    target: 100,
    achieved: 87,
    icon: '👨‍👩‍👧‍👦'
  },
  { 
    id: 2, 
    title: 'Kelas Parenting', 
    description: 'Penyelenggaraan kelas parenting untuk orang tua peserta didik PAUD',
    target: 500,
    achieved: 423,
    icon: '📚'
  },
  { 
    id: 3, 
    title: 'Pojok Baca Keluarga', 
    description: 'Pengadaan pojok baca di rumah untuk stimulasi literasi anak',
    target: 1000,
    achieved: 856,
    icon: '📖'
  },
  { 
    id: 4, 
    title: 'Cooking Class Bersama', 
    description: 'Kegiatan memasak bersama untuk edukasi gizi seimbang',
    target: 200,
    achieved: 178,
    icon: '🍳'
  },
  { 
    id: 5, 
    title: 'Bermain Aktif Outdoor', 
    description: 'Program aktivitas fisik di luar ruangan untuk tumbuh kembang optimal',
    target: 300,
    achieved: 265,
    icon: '🏃'
  },
  { 
    id: 6, 
    title: 'Digital Parenting', 
    description: 'Edukasi penggunaan gadget yang bijak untuk anak usia dini',
    target: 400,
    achieved: 312,
    icon: '📱'
  },
  { 
    id: 7, 
    title: 'Stimulasi Perkembangan Anak', 
    description: 'Program stimulasi tumbuh kembang sesuai usia anak',
    target: 600,
    achieved: 534,
    icon: '🧩'
  }
])

const overallStats = ref({
  totalProgram: 7,
  totalLembaga: 524,
  totalPeserta: 15620,
  persentaseCapaian: 82.5
})
</script>

<template>
  <DataLayout 
    title="Gerakan 7 KAIH" 
    subtitle="Program untuk meningkatkan peran orang tua dalam pendidikan anak usia dini"
    icon="💕"
  >
    <div class="space-y-6">
      <!-- Overall Statistics -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-primary-600 mb-1">{{ overallStats.totalProgram }}</div>
          <div class="text-xs text-gray-500">Program KAIH</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-green-600 mb-1">{{ overallStats.totalLembaga }}</div>
          <div class="text-xs text-gray-500">Lembaga PAUD</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-blue-600 mb-1">{{ overallStats.totalPeserta.toLocaleString('id-ID') }}</div>
          <div class="text-xs text-gray-500">Peserta</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="text-2xl font-bold text-yellow-600 mb-1">{{ overallStats.persentaseCapaian }}%</div>
          <div class="text-xs text-gray-500">Capaian Target</div>
        </div>
      </div>

      <!-- 7 KAIH Programs -->
      <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">📊 Capaian 7 Program KAIH</h3>
        <div class="grid md:grid-cols-2 gap-4">
          <div 
            v-for="item in kaihItems" 
            :key="item.id"
            class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4"
          >
            <div class="flex items-start gap-3 mb-3">
              <div class="text-3xl">{{ item.icon }}</div>
              <div class="flex-1 min-w-0">
                <h4 class="font-medium text-gray-900 dark:text-white text-sm truncate">{{ item.title }}</h4>
                <p class="text-xs text-gray-500 line-clamp-2">{{ item.description }}</p>
              </div>
            </div>
            
            <div class="space-y-1">
              <div class="flex justify-between text-xs">
                <span class="text-gray-500">Capaian</span>
                <span class="font-medium text-gray-900 dark:text-white">
                  {{ item.achieved }}/{{ item.target }} ({{ Math.round(item.achieved / item.target * 100) }}%)
                </span>
              </div>
              <div class="h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                <div 
                  class="h-full rounded-full transition-all duration-1000"
                  :class="{
                    'bg-green-500': (item.achieved / item.target) >= 0.8,
                    'bg-yellow-500': (item.achieved / item.target) >= 0.5 && (item.achieved / item.target) < 0.8,
                    'bg-red-500': (item.achieved / item.target) < 0.5
                  }"
                  :style="{ width: Math.min(100, Math.round(item.achieved / item.target * 100)) + '%' }"
                ></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DataLayout>
</template>
