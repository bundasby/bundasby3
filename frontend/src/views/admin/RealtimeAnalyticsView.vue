<template>
  <div class="realtime-dashboard">
    <!-- Header with Live Stats -->
    <div class="dashboard-header">
      <h1 class="title">📊 Real-time Analytics</h1>
      <div class="live-indicator">
        <span class="pulse"></span>
        <span>Live</span>
      </div>
    </div>

    <!-- Live Stats Cards -->
    <div class="stats-grid">
      <div class="stat-card visitors">
        <div class="stat-icon">👥</div>
        <div class="stat-content">
          <div class="stat-value">{{ liveStats.activeVisitors }}</div>
          <div class="stat-label">Pengunjung Aktif</div>
        </div>
        <div class="stat-trend up">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 14l5-5 5 5H7z"/></svg>
          +{{ liveStats.visitorTrend }}%
        </div>
      </div>

      <div class="stat-card pageviews">
        <div class="stat-icon">📄</div>
        <div class="stat-content">
          <div class="stat-value">{{ formatNumber(liveStats.todayPageviews) }}</div>
          <div class="stat-label">Pageviews Hari Ini</div>
        </div>
      </div>

      <div class="stat-card sessions">
        <div class="stat-icon">⏱️</div>
        <div class="stat-content">
          <div class="stat-value">{{ liveStats.avgSessionDuration }}</div>
          <div class="stat-label">Rata-rata Durasi</div>
        </div>
      </div>

      <div class="stat-card bounce">
        <div class="stat-icon">↩️</div>
        <div class="stat-content">
          <div class="stat-value">{{ liveStats.bounceRate }}%</div>
          <div class="stat-label">Bounce Rate</div>
        </div>
      </div>
    </div>

    <!-- Real-time Charts -->
    <div class="charts-section">
      <!-- Visitors Timeline -->
      <div class="chart-card large">
        <div class="chart-header">
          <h3>Pengunjung Real-time (24 Jam)</h3>
          <div class="chart-controls">
            <button 
              v-for="range in timeRanges" 
              :key="range.value"
              :class="{ active: selectedRange === range.value }"
              @click="selectedRange = range.value"
            >
              {{ range.label }}
            </button>
          </div>
        </div>
        <div class="chart-container">
          <canvas ref="visitorsChart"></canvas>
        </div>
      </div>

      <!-- Traffic Sources -->
      <div class="chart-card">
        <div class="chart-header">
          <h3>Sumber Traffic</h3>
        </div>
        <div class="chart-container">
          <canvas ref="sourcesChart"></canvas>
        </div>
      </div>

      <!-- Device Distribution -->
      <div class="chart-card">
        <div class="chart-header">
          <h3>Perangkat</h3>
        </div>
        <div class="chart-container">
          <canvas ref="devicesChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Live Activity Feed -->
    <div class="activity-section">
      <div class="activity-card">
        <div class="activity-header">
          <h3>🔴 Aktivitas Live</h3>
          <span class="activity-count">{{ liveActivities.length }} baru</span>
        </div>
        <div class="activity-feed">
          <TransitionGroup name="feed">
            <div 
              v-for="activity in liveActivities" 
              :key="activity.id" 
              class="activity-item"
              :class="activity.type"
            >
              <span class="activity-icon">{{ getActivityIcon(activity.type) }}</span>
              <div class="activity-content">
                <span class="activity-text">{{ activity.message }}</span>
                <span class="activity-time">{{ activity.time }}</span>
              </div>
              <span class="activity-location">{{ activity.location }}</span>
            </div>
          </TransitionGroup>
        </div>
      </div>

      <!-- Top Pages -->
      <div class="top-pages-card">
        <div class="activity-header">
          <h3>📈 Halaman Populer</h3>
        </div>
        <div class="top-pages-list">
          <div 
            v-for="(page, index) in topPages" 
            :key="page.url" 
            class="page-item"
          >
            <span class="page-rank">{{ index + 1 }}</span>
            <span class="page-url">{{ page.title }}</span>
            <div class="page-stats">
              <span class="page-visitors">{{ page.visitors }} aktif</span>
              <div class="page-bar">
                <div class="page-bar-fill" :style="{ width: page.percentage + '%' }"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Geographic Map -->
    <div class="geo-section">
      <div class="geo-card">
        <div class="geo-header">
          <h3>🗺️ Distribusi Geografis</h3>
        </div>
        <div class="geo-list">
          <div v-for="loc in topLocations" :key="loc.city" class="geo-item">
            <span class="geo-city">{{ loc.city }}</span>
            <span class="geo-region">{{ loc.region }}</span>
            <span class="geo-visitors">{{ loc.visitors }}</span>
            <div class="geo-bar">
              <div class="geo-bar-fill" :style="{ width: loc.percentage + '%' }"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

// Refs
const visitorsChart = ref(null)
const sourcesChart = ref(null)
const devicesChart = ref(null)

// State
const selectedRange = ref('1h')
const timeRanges = [
  { label: '1 Jam', value: '1h' },
  { label: '6 Jam', value: '6h' },
  { label: '24 Jam', value: '24h' },
  { label: '7 Hari', value: '7d' }
]

// Live Stats
const liveStats = ref({
  activeVisitors: 47,
  visitorTrend: 12,
  todayPageviews: 3842,
  avgSessionDuration: '4:32',
  bounceRate: 32
})

// Live Activities
const liveActivities = ref([
  { id: 1, type: 'pageview', message: 'Melihat halaman Berita', time: 'baru saja', location: 'Surabaya' },
  { id: 2, type: 'form', message: 'Mengisi form pengaduan', time: '1 menit lalu', location: 'Gresik' },
  { id: 3, type: 'download', message: 'Mengunduh dokumen peraturan', time: '2 menit lalu', location: 'Sidoarjo' },
  { id: 4, type: 'pageview', message: 'Melihat halaman Bunda PAUD', time: '3 menit lalu', location: 'Surabaya' },
  { id: 5, type: 'login', message: 'Admin login ke panel', time: '5 menit lalu', location: 'Surabaya' }
])

// Top Pages
const topPages = ref([
  { title: 'Beranda', url: '/', visitors: 28, percentage: 100 },
  { title: 'Berita', url: '/berita', visitors: 15, percentage: 54 },
  { title: 'Bunda PAUD', url: '/bunda-paud', visitors: 12, percentage: 43 },
  { title: 'Dokumen', url: '/dokumen', visitors: 8, percentage: 29 },
  { title: 'Galeri', url: '/galeri', visitors: 6, percentage: 21 }
])

// Top Locations
const topLocations = ref([
  { city: 'Surabaya', region: 'Jawa Timur', visitors: 32, percentage: 100 },
  { city: 'Sidoarjo', region: 'Jawa Timur', visitors: 8, percentage: 25 },
  { city: 'Gresik', region: 'Jawa Timur', visitors: 5, percentage: 16 },
  { city: 'Malang', region: 'Jawa Timur', visitors: 3, percentage: 9 },
  { city: 'Jakarta', region: 'DKI Jakarta', visitors: 2, percentage: 6 }
])

let charts = {}
let refreshInterval = null

// Helpers
function formatNumber(num) {
  return num.toLocaleString('id-ID')
}

function getActivityIcon(type) {
  const icons = {
    pageview: '👁️',
    form: '📝',
    download: '📥',
    login: '🔐',
    signup: '👤'
  }
  return icons[type] || '📌'
}

// Initialize Charts
function initCharts() {
  // Visitors Timeline Chart
  if (visitorsChart.value) {
    const ctx = visitorsChart.value.getContext('2d')
    charts.visitors = new Chart(ctx, {
      type: 'line',
      data: {
        labels: generateTimeLabels(24),
        datasets: [{
          label: 'Pengunjung',
          data: generateRandomData(24, 10, 60),
          borderColor: '#0ea5e9',
          backgroundColor: 'rgba(14, 165, 233, 0.1)',
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true }
        }
      }
    })
  }

  // Sources Chart
  if (sourcesChart.value) {
    const ctx = sourcesChart.value.getContext('2d')
    charts.sources = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Direct', 'Google', 'Social', 'Referral'],
        datasets: [{
          data: [40, 30, 20, 10],
          backgroundColor: ['#0ea5e9', '#22c55e', '#f59e0b', '#8b5cf6']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false
      }
    })
  }

  // Devices Chart
  if (devicesChart.value) {
    const ctx = devicesChart.value.getContext('2d')
    charts.devices = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Mobile', 'Desktop', 'Tablet'],
        datasets: [{
          data: [55, 40, 5],
          backgroundColor: ['#f43f5e', '#3b82f6', '#10b981']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false
      }
    })
  }
}

function generateTimeLabels(count) {
  const labels = []
  for (let i = count - 1; i >= 0; i--) {
    labels.push(`${i}h ago`)
  }
  return labels
}

function generateRandomData(count, min, max) {
  return Array.from({ length: count }, () => 
    Math.floor(Math.random() * (max - min + 1)) + min
  )
}

// Simulate real-time updates
function startRealTimeUpdates() {
  refreshInterval = setInterval(() => {
    // Update active visitors
    liveStats.value.activeVisitors += Math.floor(Math.random() * 5) - 2
    if (liveStats.value.activeVisitors < 10) liveStats.value.activeVisitors = 10

    // Add new activity
    const activities = [
      'Melihat halaman Beranda',
      'Melihat halaman Berita',
      'Mengunduh dokumen',
      'Mengisi form konsultasi',
      'Melihat galeri'
    ]
    const locations = ['Surabaya', 'Sidoarjo', 'Gresik', 'Malang']
    
    liveActivities.value.unshift({
      id: Date.now(),
      type: 'pageview',
      message: activities[Math.floor(Math.random() * activities.length)],
      time: 'baru saja',
      location: locations[Math.floor(Math.random() * locations.length)]
    })

    // Keep only last 10 activities
    if (liveActivities.value.length > 10) {
      liveActivities.value.pop()
    }
  }, 5000)
}

onMounted(() => {
  initCharts()
  startRealTimeUpdates()
})

onUnmounted(() => {
  if (refreshInterval) clearInterval(refreshInterval)
  Object.values(charts).forEach(chart => chart?.destroy())
})
</script>

<style scoped>
.realtime-dashboard {
  padding: 24px;
  background: #f8fafc;
  min-height: 100vh;
}

.dashboard-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.title {
  font-size: 28px;
  font-weight: 700;
  color: #1e293b;
}

.live-indicator {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  background: #fef2f2;
  color: #dc2626;
  border-radius: 20px;
  font-weight: 600;
}

.pulse {
  width: 10px;
  height: 10px;
  background: #dc2626;
  border-radius: 50%;
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50% { opacity: 0.5; transform: scale(1.2); }
}

/* Stats Grid */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin-bottom: 24px;
}

.stat-card {
  background: white;
  border-radius: 16px;
  padding: 24px;
  display: flex;
  align-items: center;
  gap: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

.stat-icon {
  font-size: 32px;
}

.stat-value {
  font-size: 32px;
  font-weight: 700;
  color: #1e293b;
}

.stat-label {
  font-size: 14px;
  color: #64748b;
}

.stat-trend {
  display: flex;
  align-items: center;
  gap: 4px;
  font-size: 14px;
  font-weight: 600;
  margin-left: auto;
}

.stat-trend.up {
  color: #22c55e;
}

.stat-trend svg {
  width: 16px;
  height: 16px;
}

/* Charts */
.charts-section {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr;
  gap: 20px;
  margin-bottom: 24px;
}

.chart-card {
  background: white;
  border-radius: 16px;
  padding: 24px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

.chart-card.large {
  grid-column: span 1;
}

.chart-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.chart-header h3 {
  font-size: 16px;
  font-weight: 600;
  color: #1e293b;
}

.chart-controls button {
  padding: 6px 12px;
  border: none;
  background: #f1f5f9;
  color: #64748b;
  border-radius: 6px;
  font-size: 12px;
  cursor: pointer;
  margin-left: 8px;
}

.chart-controls button.active {
  background: #0ea5e9;
  color: white;
}

.chart-container {
  height: 200px;
}

/* Activity Section */
.activity-section {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-bottom: 24px;
}

.activity-card, .top-pages-card {
  background: white;
  border-radius: 16px;
  padding: 24px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

.activity-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}

.activity-header h3 {
  font-size: 16px;
  font-weight: 600;
}

.activity-count {
  background: #fef2f2;
  color: #dc2626;
  padding: 4px 10px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
}

.activity-feed {
  max-height: 300px;
  overflow-y: auto;
}

.activity-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 8px;
  background: #f8fafc;
}

.activity-icon {
  font-size: 20px;
}

.activity-content {
  flex: 1;
}

.activity-text {
  display: block;
  font-size: 14px;
  color: #1e293b;
}

.activity-time {
  font-size: 12px;
  color: #94a3b8;
}

.activity-location {
  font-size: 12px;
  color: #64748b;
  background: #e2e8f0;
  padding: 4px 8px;
  border-radius: 4px;
}

/* Top Pages */
.page-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 0;
  border-bottom: 1px solid #f1f5f9;
}

.page-rank {
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f1f5f9;
  border-radius: 50%;
  font-size: 12px;
  font-weight: 600;
  color: #64748b;
}

.page-url {
  flex: 1;
  font-size: 14px;
  color: #1e293b;
}

.page-stats {
  text-align: right;
}

.page-visitors {
  font-size: 12px;
  color: #64748b;
}

.page-bar {
  width: 60px;
  height: 4px;
  background: #e2e8f0;
  border-radius: 2px;
  margin-top: 4px;
}

.page-bar-fill {
  height: 100%;
  background: #0ea5e9;
  border-radius: 2px;
}

/* Geo Section */
.geo-section {
  margin-bottom: 24px;
}

.geo-card {
  background: white;
  border-radius: 16px;
  padding: 24px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

.geo-header h3 {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 16px;
}

.geo-list {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 16px;
}

.geo-item {
  text-align: center;
}

.geo-city {
  display: block;
  font-weight: 600;
  color: #1e293b;
}

.geo-region {
  display: block;
  font-size: 12px;
  color: #94a3b8;
}

.geo-visitors {
  display: block;
  font-size: 20px;
  font-weight: 700;
  color: #0ea5e9;
  margin: 8px 0;
}

.geo-bar {
  height: 4px;
  background: #e2e8f0;
  border-radius: 2px;
}

.geo-bar-fill {
  height: 100%;
  background: #0ea5e9;
  border-radius: 2px;
}

/* Feed Animation */
.feed-enter-active {
  animation: slideIn 0.3s ease;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsive */
@media (max-width: 1200px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  .charts-section {
    grid-template-columns: 1fr;
  }
  .activity-section {
    grid-template-columns: 1fr;
  }
  .geo-list {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 768px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }
  .geo-list {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>
