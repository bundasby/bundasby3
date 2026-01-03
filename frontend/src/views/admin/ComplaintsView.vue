<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const loading = ref(true)

const complaints = ref([
  {
    id: 1,
    name: 'Budi Santoso',
    email: 'budi@email.com',
    subject: 'Pertanyaan tentang pendaftaran PAUD',
    message: 'Bagaimana cara mendaftar PAUD di kelurahan saya?',
    status: 'pending',
    created_at: '2026-01-01'
  },
  {
    id: 2,
    name: 'Siti Aminah',
    email: 'siti@email.com',
    subject: 'Laporan Kondisi Sarana PAUD',
    message: 'Sarana bermain di PAUD Melati sudah rusak dan perlu diperbaiki.',
    status: 'in_progress',
    created_at: '2025-12-28'
  },
  {
    id: 3,
    name: 'Ahmad Wijaya',
    email: 'ahmad@email.com',
    subject: 'Informasi Program MBG',
    message: 'Kapan program MBG akan dimulai di PAUD kami?',
    status: 'resolved',
    created_at: '2025-12-20'
  }
])

onMounted(() => {
  setTimeout(() => loading.value = false, 500)
})

const getStatusColor = (status) => {
  const colors = {
    pending: 'bg-yellow-100 text-yellow-700',
    in_progress: 'bg-blue-100 text-blue-700',
    resolved: 'bg-green-100 text-green-700'
  }
  return colors[status] || 'bg-gray-100 text-gray-700'
}

const getStatusLabel = (status) => {
  const labels = { pending: 'Pending', in_progress: 'Diproses', resolved: 'Selesai' }
  return labels[status] || status
}

const updateStatus = (complaint, newStatus) => {
  complaint.status = newStatus
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
}
</script>

<template>
  <AdminLayout title="Kelola Pengaduan">
    <p class="text-gray-600 dark:text-gray-400 -mt-6 mb-6">Lihat dan tangani pengaduan masyarakat</p>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 mb-6">
      <div class="card p-4 text-center">
        <p class="text-2xl font-bold text-yellow-600">{{ complaints.filter(c => c.status === 'pending').length }}</p>
        <p class="text-sm text-gray-600 dark:text-gray-400">Pending</p>
      </div>
      <div class="card p-4 text-center">
        <p class="text-2xl font-bold text-blue-600">{{ complaints.filter(c => c.status === 'in_progress').length }}</p>
        <p class="text-sm text-gray-600 dark:text-gray-400">Diproses</p>
      </div>
      <div class="card p-4 text-center">
        <p class="text-2xl font-bold text-green-600">{{ complaints.filter(c => c.status === 'resolved').length }}</p>
        <p class="text-sm text-gray-600 dark:text-gray-400">Selesai</p>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex justify-center py-12">
      <div class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
    </div>

    <!-- Complaints List -->
    <div v-else class="space-y-4">
      <div v-for="complaint in complaints" :key="complaint.id" class="card p-6">
        <div class="flex items-start justify-between mb-4">
          <div>
            <h3 class="font-semibold text-gray-900 dark:text-white">{{ complaint.subject }}</h3>
            <p class="text-sm text-gray-500">{{ complaint.name }} • {{ complaint.email }} • {{ formatDate(complaint.created_at) }}</p>
          </div>
          <span class="px-3 py-1 text-xs font-medium rounded-full" :class="getStatusColor(complaint.status)">
            {{ getStatusLabel(complaint.status) }}
          </span>
        </div>
        <p class="text-gray-700 dark:text-gray-300 mb-4">{{ complaint.message }}</p>
        <div class="flex gap-2">
          <button 
            v-if="complaint.status === 'pending'"
            @click="updateStatus(complaint, 'in_progress')"
            class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200"
          >
            Proses
          </button>
          <button 
            v-if="complaint.status === 'in_progress'"
            @click="updateStatus(complaint, 'resolved')"
            class="px-3 py-1 text-sm bg-green-100 text-green-700 rounded-lg hover:bg-green-200"
          >
            Selesai
          </button>
          <a 
            :href="`mailto:${complaint.email}?subject=Re: ${complaint.subject}`"
            class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 inline-block"
          >
            Balas Email
          </a>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
