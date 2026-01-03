<script setup>
import { useRoute } from 'vue-router'

const route = useRoute()

const categories = [
  { name: 'Ringkasan Data', path: '/data/ringkasan', icon: 'chart', color: 'blue' },
  { name: 'Daftar Sekolah', path: '/data/daftar-sekolah', icon: 'school', color: 'teal' },
  { name: 'Akreditasi', path: '/data/akreditasi-paud', icon: 'badge', color: 'amber' },
  { name: 'Ijin Operasional', path: '/data/ijin-operasional', icon: 'document', color: 'orange' },
  { name: 'Profiling', path: '/data/profiling-paud', icon: 'user', color: 'purple' },
  { name: 'Gerakan 7 KAIH', path: '/data/gerakan-7-kaih', icon: 'heart', color: 'pink' },
  { name: 'Digitalisasi', path: '/data/digitalisasi-paud', icon: 'computer', color: 'cyan' },
  { name: 'Sulingjar', path: '/data/sulingjar', icon: 'clipboard', color: 'indigo' },
  { name: 'Rapor Pendidikan', path: '/data/rapor-pendidikan', icon: 'clipboard', color: 'violet' },
  { name: 'APK/AKS/ATS', path: '/data/apk-aks-ats', icon: 'pie', color: 'rose' },
  { name: 'IKD', path: '/data/ikd', icon: 'database', color: 'sky' },
  { name: 'PAUD HI', path: '/data/paud-hi', icon: 'star', color: 'lime' },
  { name: 'Revitalisasi', path: '/data/revitalisasi-paud', icon: 'refresh', color: 'emerald' },
]

const isActive = (path) => {
  return route.path === path
}
</script>

<template>
  <aside class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-4 sticky top-24">
    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 px-2">Kategori Data</h3>
    <nav class="space-y-1">
      <router-link
        v-for="cat in categories"
        :key="cat.path"
        :to="cat.path"
        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200"
        :class="isActive(cat.path) 
          ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 shadow-sm' 
          : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white'"
      >
        <!-- Icons -->
        <div 
          class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-transform group-hover:scale-110"
          :class="{
            'bg-blue-100 text-blue-600 dark:bg-blue-900/50 dark:text-blue-400': cat.color === 'blue',
            'bg-green-100 text-green-600 dark:bg-green-900/50 dark:text-green-400': cat.color === 'green',
            'bg-teal-100 text-teal-600 dark:bg-teal-900/50 dark:text-teal-400': cat.color === 'teal',
            'bg-amber-100 text-amber-600 dark:bg-amber-900/50 dark:text-amber-400': cat.color === 'amber',
            'bg-orange-100 text-orange-600 dark:bg-orange-900/50 dark:text-orange-400': cat.color === 'orange',
            'bg-purple-100 text-purple-600 dark:bg-purple-900/50 dark:text-purple-400': cat.color === 'purple',
            'bg-pink-100 text-pink-600 dark:bg-pink-900/50 dark:text-pink-400': cat.color === 'pink',
            'bg-cyan-100 text-cyan-600 dark:bg-cyan-900/50 dark:text-cyan-400': cat.color === 'cyan',
            'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-400': cat.color === 'indigo',
            'bg-rose-100 text-rose-600 dark:bg-rose-900/50 dark:text-rose-400': cat.color === 'rose',
            'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/50 dark:text-emerald-400': cat.color === 'emerald',
            'bg-slate-100 text-slate-600 dark:bg-slate-900/50 dark:text-slate-400': cat.color === 'slate',
            'bg-violet-100 text-violet-600 dark:bg-violet-900/50 dark:text-violet-400': cat.color === 'violet',
            'bg-sky-100 text-sky-600 dark:bg-sky-900/50 dark:text-sky-400': cat.color === 'sky',
            'bg-lime-100 text-lime-600 dark:bg-lime-900/50 dark:text-lime-400': cat.color === 'lime'
          }"
        >
          <!-- Chart Icon -->
          <svg v-if="cat.icon === 'chart'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          <!-- Building Icon -->
          <svg v-if="cat.icon === 'building'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
          <!-- School Icon -->
          <svg v-if="cat.icon === 'school'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
          </svg>
          <!-- Badge Icon -->
          <svg v-if="cat.icon === 'badge'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
          </svg>
          <!-- Document Icon -->
          <svg v-if="cat.icon === 'document'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <!-- User Icon -->
          <svg v-if="cat.icon === 'user'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          <!-- Heart Icon -->
          <svg v-if="cat.icon === 'heart'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
          </svg>
          <!-- Computer Icon -->
          <svg v-if="cat.icon === 'computer'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
          </svg>
          <!-- Clipboard Icon -->
          <svg v-if="cat.icon === 'clipboard'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
          </svg>
          <!-- Pie Icon -->
          <svg v-if="cat.icon === 'pie'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
          </svg>
          <!-- Refresh Icon -->
          <svg v-if="cat.icon === 'refresh'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
          <!-- Database Icon -->
          <svg v-if="cat.icon === 'database'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
          </svg>
          <!-- Star Icon -->
          <svg v-if="cat.icon === 'star'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
          </svg>
          <!-- Download Icon -->
          <svg v-if="cat.icon === 'download'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
        <span class="truncate">{{ cat.name }}</span>
        <!-- Active indicator -->
        <div v-if="isActive(cat.path)" class="ml-auto w-1.5 h-1.5 rounded-full bg-primary-500"></div>
      </router-link>
    </nav>
  </aside>
</template>
