<script setup>
import { ref, onMounted, computed } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import { Bar, Doughnut } from 'vue-chartjs'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  ArcElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js'
import api from '@/services/api'

// Register Chart.js components
ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  ArcElement,
  Title,
  Tooltip,
  Legend
)

// State
const stats = ref({
  articles: 0,
  bunda_paud: 0,
  mitra_paud: 0,
  complaints: 0,
  complaints_pending: 0,
  galleries: 0,
  documents: 0,
  users: 0
})

const complaintsTrend = ref([])
const bundaDistribution = ref([])
const recentActivity = ref([])
const loading = ref(true)

// Chart data for complaints trend (Bar chart)
const complaintsTrendData = computed(() => ({
  labels: complaintsTrend.value.map(item => item.month),
  datasets: [
    {
      label: 'Pengaduan',
      backgroundColor: '#3b82f6',
      borderRadius: 8,
      data: complaintsTrend.value.map(item => item.count)
    }
  ]
}))

const complaintsTrendOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      ticks: {
        stepSize: 1
      }
    }
  }
}

// Chart data for Bunda PAUD distribution (Doughnut chart)
const bundaDistributionData = computed(() => ({
  labels: bundaDistribution.value.map(item => item.type),
  datasets: [
    {
      backgroundColor: ['#8b5cf6', '#10b981'],
      data: bundaDistribution.value.map(item => item.count)
    }
  ]
}))

const bundaDistributionOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom'
    }
  }
}

// Load analytics data
const loadAnalytics = async () => {
  loading.value = true
  try {
    const [statsRes, trendRes, distRes, activityRes] = await Promise.all([
      api.get('/analytics/stats'),
      api.get('/analytics/complaints-trend'),
      api.get('/analytics/bunda-paud-distribution'),
      api.get('/analytics/recent-activity')
    ])
    
    stats.value = statsRes.data
    complaintsTrend.value = trendRes.data
    bundaDistribution.value = distRes.data
    recentActivity.value = activityRes.data
  } catch (error) {
    console.error('Failed to load analytics:', error)
    // Fallback to localStorage data
    loadFromLocalStorage()
  } finally {
    loading.value = false
  }
}

// Fallback to localStorage
const loadFromLocalStorage = () => {
  const articles = JSON.parse(localStorage.getItem('bp_articles') || '[]')
  const galleries = JSON.parse(localStorage.getItem('bp_galleries') || '[]')
  const documents = JSON.parse(localStorage.getItem('bp_documents') || '[]')
  const complaints = JSON.parse(localStorage.getItem('bp_complaints') || '[]')
  const bundaPaud = JSON.parse(localStorage.getItem('bp_bunda_paud_list') || '[]')
  const mitraPaud = JSON.parse(localStorage.getItem('bp_mitra_paud') || '[]')

  stats.value = {
    articles: articles.length,
    bunda_paud: bundaPaud.length,
    mitra_paud: mitraPaud.length,
    complaints: complaints.length,
    complaints_pending: complaints.filter(c => c.status === 'pending').length,
    galleries: galleries.length,
    documents: documents.length,
    users: 5
  }
  
  // Fake trend data
  complaintsTrend.value = [
    { month: 'Aug', count: 2 },
    { month: 'Sep', count: 3 },
    { month: 'Oct', count: 1 },
    { month: 'Nov', count: 4 },
    { month: 'Dec', count: 2 },
    { month: 'Jan', count: complaints.length }
  ]
  
  bundaDistribution.value = [
    { type: 'Kecamatan', count: 31 },
    { type: 'Kelurahan', count: 12 }
  ]
}

// Get activity icon
const getActivityIcon = (type) => {
  const icons = {
    article: '📝',
    complaint: '💬',
    mitra: '🤝',
    user: '👤'
  }
  return icons[type] || '📌'
}

onMounted(() => {
  loadAnalytics()
})
</script>

<template>
  <AdminLayout title="Dashboard">
    <!-- Loading -->
    <div v-if="loading" class="flex items-center justify-center py-12">
      <div class="w-10 h-10 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
    </div>

    <div v-else>
      <!-- Main Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Berita -->
        <div class="card p-6 border-l-4 border-blue-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Total Berita</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.articles }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Bunda PAUD -->
        <div class="card p-6 border-l-4 border-purple-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Bunda PAUD</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.bunda_paud }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Mitra PAUD -->
        <div class="card p-6 border-l-4 border-indigo-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Mitra PAUD</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.mitra_paud }}</p>
            </div>
            <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Pengaduan -->
        <div class="card p-6 border-l-4 border-red-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Pengaduan Baru</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.complaints_pending }}</p>
            </div>
            <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts Row -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Complaints Trend Chart -->
        <div class="card p-6">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">📊 Tren Pengaduan (6 Bulan Terakhir)</h3>
          <div class="h-64">
            <Bar 
              v-if="complaintsTrend.length > 0"
              :data="complaintsTrendData" 
              :options="complaintsTrendOptions" 
            />
            <div v-else class="flex items-center justify-center h-full text-gray-500">
              Tidak ada data
            </div>
          </div>
        </div>

        <!-- Bunda PAUD Distribution Chart -->
        <div class="card p-6">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">🎯 Distribusi Bunda PAUD</h3>
          <div class="h-64">
            <Doughnut 
              v-if="bundaDistribution.length > 0"
              :data="bundaDistributionData" 
              :options="bundaDistributionOptions" 
            />
            <div v-else class="flex items-center justify-center h-full text-gray-500">
              Tidak ada data
            </div>
          </div>
        </div>
      </div>

      <!-- Secondary Stats -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="card p-4 flex items-center gap-4 border border-gray-100 dark:border-gray-700">
          <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center text-green-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          </div>
          <div>
            <p class="text-sm text-gray-500">Total Galeri</p>
            <p class="font-bold text-lg dark:text-white">{{ stats.galleries }}</p>
          </div>
        </div>
        <div class="card p-4 flex items-center gap-4 border border-gray-100 dark:border-gray-700">
          <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center text-amber-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          </div>
          <div>
            <p class="text-sm text-gray-500">Total Dokumen</p>
            <p class="font-bold text-lg dark:text-white">{{ stats.documents }}</p>
          </div>
        </div>
        <div class="card p-4 flex items-center gap-4 border border-gray-100 dark:border-gray-700">
          <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/30 rounded-full flex items-center justify-center text-teal-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          </div>
          <div>
            <p class="text-sm text-gray-500">User Aktif</p>
            <p class="font-bold text-lg dark:text-white">{{ stats.users }}</p>
          </div>
        </div>
        <div class="card p-4 flex items-center gap-4 border border-gray-100 dark:border-gray-700">
          <div class="w-10 h-10 bg-rose-100 dark:bg-rose-900/30 rounded-full flex items-center justify-center text-rose-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
          </div>
          <div>
            <p class="text-sm text-gray-500">Total Pengaduan</p>
            <p class="font-bold text-lg dark:text-white">{{ stats.complaints }}</p>
          </div>
        </div>
      </div>

      <!-- Recent Activity & Quick Actions -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activity -->
        <div class="card p-6">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">🕐 Aktivitas Terbaru</h3>
          <div class="space-y-3">
            <div 
              v-for="(activity, index) in recentActivity" 
              :key="index"
              class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg"
            >
              <span class="text-xl">{{ getActivityIcon(activity.type) }}</span>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ activity.title }}</p>
                <p class="text-xs text-gray-500">{{ activity.date }}</p>
              </div>
            </div>
            <div v-if="recentActivity.length === 0" class="text-center py-8 text-gray-500">
              Belum ada aktivitas
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="card p-6">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">⚡ Akses Cepat</h2>
          <div class="grid grid-cols-2 gap-4">
            <router-link to="/admin/bunda-paud" class="p-4 bg-purple-50 dark:bg-purple-900/10 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/20 transition-colors text-center block border border-transparent hover:border-purple-200">
              <svg class="w-8 h-8 mx-auto mb-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              <span class="text-sm font-medium text-gray-900 dark:text-white">Tambah Bunda PAUD</span>
            </router-link>

            <router-link to="/admin/mitra-paud" class="p-4 bg-indigo-50 dark:bg-indigo-900/10 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/20 transition-colors text-center block border border-transparent hover:border-indigo-200">
              <svg class="w-8 h-8 mx-auto mb-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
              </svg>
              <span class="text-sm font-medium text-gray-900 dark:text-white">Tambah Mitra PAUD</span>
            </router-link>
            
            <router-link to="/admin/articles/new" class="p-4 bg-blue-50 dark:bg-blue-900/10 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/20 transition-colors text-center block border border-transparent hover:border-blue-200">
              <svg class="w-8 h-8 mx-auto mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              <span class="text-sm font-medium text-gray-900 dark:text-white">Tulis Berita</span>
            </router-link>

            <router-link to="/admin/complaints" class="p-4 bg-red-50 dark:bg-red-900/10 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/20 transition-colors text-center block border border-transparent hover:border-red-200">
              <svg class="w-8 h-8 mx-auto mb-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
              <span class="text-sm font-medium text-gray-900 dark:text-white">Cek Pengaduan</span>
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
