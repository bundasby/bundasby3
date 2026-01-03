<script setup>
import { ref, computed, onMounted } from 'vue'
import { settingsStorage, peminjamanStorage } from '@/services/localStorage'

const activeTab = ref('form') // 'form', 'jadwal', 'status'

const formData = ref({
  namaPemohon: '',
  instansi: '',
  email: '',
  telepon: '',
  tanggalMulai: '',
  tanggalSelesai: '',
  waktuMulai: '',
  waktuSelesai: '',
  pilihanRuang: '',
  jenisKegiatan: '',
  jenisKegiatanLainnya: '',
  jumlahPeserta: '',
  keterangan: '',
  suratPermohonan: null
})

const settings = ref({
  address: '',
  mapEmbed: ''
})

const statusCheckData = ref({
  email: '',
  telepon: ''
})

const statusResults = ref([])
const bookedDates = ref([])

const jenisKegiatanOptions = [
  'Seminar/Workshop PAUD',
  'Pelatihan Guru',
  'Rapat Koordinasi',
  'Gathering/Pertemuan',
  'Acara Kesenian Anak',
  'Lomba/Kompetisi',
  'Lainnya'
]

const ruangOptions = [
  { value: 'aula-lt2', label: 'Aula Lantai 2', kapasitas: '100 orang' },
  { value: 'ruang-pertemuan-lt2', label: 'Ruang Pertemuan Lantai 2', kapasitas: '20 orang' }
]

const fasilitasGedung = [
  { icon: '🏛️', name: 'Aula Utama', kapasitas: '100 orang' },
  { icon: '🪑', name: 'Ruang Meeting', kapasitas: '20 orang' },
  { icon: '💺', name: 'Kursi', kapasitas: 'Tersedia' },
  { icon: '🔊', name: 'Sound System', kapasitas: 'Tersedia' },
  { icon: '📽️', name: 'Proyektor & Layar', kapasitas: 'Tersedia' },
  { icon: '❄️', name: 'AC Central', kapasitas: 'Tersedia' },
  { icon: '🚗', name: 'Parkir', kapasitas: 'Tersedia' },
  { icon: '🕌', name: 'Mushola', kapasitas: 'Tersedia' }
]

const isSubmitting = ref(false)
const showSuccess = ref(false)
const fileName = ref('')
const currentMonth = ref(new Date())

onMounted(() => {
  loadSettings()
  loadBookedDates()
})

const loadSettings = () => {
  const data = settingsStorage.get()
  if (data) {
    settings.value.address = data.address || ''
    const mapHtml = data.googleMapsUrl || ''
    if (mapHtml.startsWith('https://www.google.com/maps/embed')) {
      settings.value.mapEmbed = mapHtml
    } else {
      const srcMatch = mapHtml.match(/src=["']([^"']+)["']/)
      settings.value.mapEmbed = srcMatch ? srcMatch[1] : ''
    }
  }
}

const loadBookedDates = () => {
  const approved = peminjamanStorage.getApproved()
  bookedDates.value = approved.map(p => ({
    start: p.tanggalMulai,
    end: p.tanggalSelesai,
    waktuMulai: p.waktuMulai || '07:30',
    waktuSelesai: p.waktuSelesai || '16:00',
    ruang: p.pilihanRuang,
    instansi: p.instansi,
    slot: getTimeSlot(p.waktuMulai || '07:30', p.waktuSelesai || '16:00')
  }))
}

// Determine time slot: 'pagi', 'sore', or 'fullday'
const getTimeSlot = (waktuMulai, waktuSelesai) => {
  const mulai = parseInt(waktuMulai.replace(':', ''))
  const selesai = parseInt(waktuSelesai.replace(':', ''))
  
  // Morning session: ends before or at 12:00
  if (selesai <= 1200) return 'pagi'
  
  // Afternoon session: starts at or after 13:00
  if (mulai >= 1300) return 'sore'
  
  // Crosses lunch break = full day
  return 'fullday'
}

// Check if date is available for given time slot
const checkAvailability = (dateStr, ruang, waktuMulai, waktuSelesai) => {
  const requestedSlot = getTimeSlot(waktuMulai, waktuSelesai)
  
  const conflictingBookings = bookedDates.value.filter(b => {
    // Check date range overlap
    if (dateStr < b.start || dateStr > b.end) return false
    
    // Check same room
    if (b.ruang !== ruang) return false
    
    // Check slot conflict
    if (b.slot === 'fullday') return true // Full day blocks all
    if (requestedSlot === 'fullday') return true // Requesting full day blocked by any
    if (b.slot === requestedSlot) return true // Same slot blocked
    
    return false
  })
  
  return conflictingBookings.length === 0
}

// Get availability status for calendar
const getDateAvailability = (dateStr) => {
  const bookingsOnDate = bookedDates.value.filter(b => dateStr >= b.start && dateStr <= b.end)
  
  if (bookingsOnDate.length === 0) return { status: 'available', label: 'Tersedia' }
  
  // Check each room
  const rooms = ['aula-lt2', 'ruang-pertemuan-lt2']
  const availability = {}
  
  rooms.forEach(room => {
    const roomBookings = bookingsOnDate.filter(b => b.ruang === room)
    if (roomBookings.length === 0) {
      availability[room] = 'available'
    } else if (roomBookings.some(b => b.slot === 'fullday')) {
      availability[room] = 'full'
    } else {
      const hasAM = roomBookings.some(b => b.slot === 'pagi')
      const hasPM = roomBookings.some(b => b.slot === 'sore')
      if (hasAM && hasPM) {
        availability[room] = 'full'
      } else if (hasAM) {
        availability[room] = 'sore-only'
      } else {
        availability[room] = 'pagi-only'
      }
    }
  })
  
  return { status: 'partial', availability, bookings: bookingsOnDate }
}

// Calendar helpers
const calendarDays = computed(() => {
  const year = currentMonth.value.getFullYear()
  const month = currentMonth.value.getMonth()
  const firstDay = new Date(year, month, 1)
  const lastDay = new Date(year, month + 1, 0)
  const days = []
  
  // Add empty days for padding
  for (let i = 0; i < firstDay.getDay(); i++) {
    days.push({ date: null, isBooked: false })
  }
  
  // Add actual days
  for (let i = 1; i <= lastDay.getDate(); i++) {
    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`
    const availability = getDateAvailability(dateStr)
    const hasBooking = bookedDates.value.some(b => dateStr >= b.start && dateStr <= b.end)
    
    days.push({
      date: i,
      dateStr,
      isBooked: hasBooking,
      availability,
      isToday: dateStr === new Date().toISOString().split('T')[0]
    })
  }
  
  return days
})

const currentMonthName = computed(() => {
  return currentMonth.value.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' })
})

const prevMonth = () => {
  currentMonth.value = new Date(currentMonth.value.getFullYear(), currentMonth.value.getMonth() - 1, 1)
}

const nextMonth = () => {
  currentMonth.value = new Date(currentMonth.value.getFullYear(), currentMonth.value.getMonth() + 1, 1)
}

const handleFileUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    if (file.type !== 'application/pdf') {
      alert('Hanya file PDF yang diperbolehkan')
      event.target.value = ''
      return
    }
    if (file.size > 5 * 1024 * 1024) {
      alert('Ukuran file maksimal 5MB')
      event.target.value = ''
      return
    }
    formData.value.suratPermohonan = file
    fileName.value = file.name
  }
}

const submitForm = async () => {
  if (!formData.value.suratPermohonan) {
    alert('Silakan upload surat permohonan terlebih dahulu')
    return
  }
  
  if (!formData.value.pilihanRuang) {
    alert('Silakan pilih ruang yang diinginkan')
    return
  }
  
  // Validate time slot availability
  const startDate = formData.value.tanggalMulai
  const endDate = formData.value.tanggalSelesai
  const ruang = formData.value.pilihanRuang
  const waktuMulai = formData.value.waktuMulai
  const waktuSelesai = formData.value.waktuSelesai
  
  // Check each date in range
  let currentDate = new Date(startDate)
  const lastDate = new Date(endDate)
  
  while (currentDate <= lastDate) {
    const dateStr = currentDate.toISOString().split('T')[0]
    const isAvailable = checkAvailability(dateStr, ruang, waktuMulai, waktuSelesai)
    
    if (!isAvailable) {
      const slot = getTimeSlot(waktuMulai, waktuSelesai)
      const slotLabel = slot === 'pagi' ? 'pagi (07:30-12:00)' : 
                        slot === 'sore' ? 'sore (13:00-16:00)' : 'seharian'
      alert(`Maaf, ruang tidak tersedia untuk tanggal ${dateStr} pada sesi ${slotLabel}. Silakan pilih waktu lain.`)
      return
    }
    
    currentDate.setDate(currentDate.getDate() + 1)
  }
  
  isSubmitting.value = true
  
  // Save to localStorage
  const jenisKegiatanFinal = formData.value.jenisKegiatan === 'Lainnya' 
    ? formData.value.jenisKegiatanLainnya 
    : formData.value.jenisKegiatan
    
  peminjamanStorage.create({
    namaPemohon: formData.value.namaPemohon,
    instansi: formData.value.instansi,
    email: formData.value.email,
    telepon: formData.value.telepon,
    tanggalMulai: formData.value.tanggalMulai,
    tanggalSelesai: formData.value.tanggalSelesai,
    waktuMulai: formData.value.waktuMulai,
    waktuSelesai: formData.value.waktuSelesai,
    pilihanRuang: formData.value.pilihanRuang,
    jenisKegiatan: jenisKegiatanFinal,
    jumlahPeserta: formData.value.jumlahPeserta,
    keterangan: formData.value.keterangan,
    fileName: fileName.value
  })
  
  await new Promise(resolve => setTimeout(resolve, 1000))
  isSubmitting.value = false
  showSuccess.value = true
  
  // Reset form
  formData.value = {
    namaPemohon: '',
    instansi: '',
    email: '',
    telepon: '',
    tanggalMulai: '',
    tanggalSelesai: '',
    waktuMulai: '',
    waktuSelesai: '',
    pilihanRuang: '',
    jenisKegiatan: '',
    jenisKegiatanLainnya: '',
    jumlahPeserta: '',
    keterangan: '',
    suratPermohonan: null
  }
  fileName.value = ''
}

const checkStatus = () => {
  if (!statusCheckData.value.email && !statusCheckData.value.telepon) {
    alert('Masukkan email atau nomor telepon')
    return
  }
  statusResults.value = peminjamanStorage.findByContact(
    statusCheckData.value.email,
    statusCheckData.value.telepon
  )
}

const getStatusBadge = (status) => {
  const badges = {
    pending: { class: 'bg-yellow-100 text-yellow-800', label: 'Menunggu' },
    approved: { class: 'bg-green-100 text-green-800', label: 'Disetujui' },
    rejected: { class: 'bg-red-100 text-red-800', label: 'Ditolak' }
  }
  return badges[status] || badges.pending
}

const getRuangLabel = (value) => {
  const ruang = ruangOptions.find(r => r.value === value)
  return ruang ? ruang.label : value
}

const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Hero -->
    <section class="bg-gradient-to-br from-amber-500 to-orange-600 text-white py-16">
      <div class="section-container text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Peminjaman Gedung<br>Grha Bunda PAUD</h1>
        <p class="text-amber-100 max-w-2xl mx-auto">
          Fasilitas gedung serbaguna untuk kegiatan pendidikan dan kemasyarakatan
        </p>
      </div>
    </section>

    <!-- Content -->
    <section class="py-12">
      <div class="section-container">
        
        <!-- Fasilitas -->
          <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center mb-8">Fasilitas Gedung</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div v-for="fasilitas in fasilitasGedung" :key="fasilitas.name" class="card p-4 text-center">
                <div class="text-3xl mb-2">{{ fasilitas.icon }}</div>
                <h3 class="font-semibold text-gray-900 dark:text-white text-sm">{{ fasilitas.name }}</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ fasilitas.kapasitas }}</p>
              </div>
            </div>
          </div>

          <div class="grid lg:grid-cols-3 gap-8">
            <!-- Info -->
            <div class="lg:col-span-1 space-y-6">
              <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Lokasi Gedung</h3>
                <p v-if="settings.address" class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-4 whitespace-pre-line">{{ settings.address }}</p>
                <div v-if="settings.mapEmbed" class="aspect-video bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                  <iframe :src="settings.mapEmbed" class="w-full h-full" style="border:0;" allowfullscreen loading="lazy"></iframe>
                </div>
              </div>

              <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Syarat Peminjaman</h3>
                <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                  <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Kegiatan bernuansa pendidikan/sosial
                  </li>
                  <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Mengajukan minimal 7 hari sebelumnya
                  </li>
                  <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Melampirkan surat permohonan resmi (PDF)
                  </li>
                </ul>
              </div>

              <!-- Jam Layanan -->
              <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                  <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  Jam Layanan
                </h3>
                <div class="space-y-3 text-sm">
                  <div>
                    <p class="font-medium text-gray-800 dark:text-gray-200">Senin - Kamis</p>
                    <p class="text-gray-600 dark:text-gray-400">07:30 - 16:00 WIB</p>
                    <p class="text-xs text-gray-500">Istirahat: 12:00 - 13:00 WIB</p>
                  </div>
                  <div>
                    <p class="font-medium text-gray-800 dark:text-gray-200">Jumat</p>
                    <p class="text-gray-600 dark:text-gray-400">07:00 - 16:30 WIB</p>
                    <p class="text-xs text-gray-500">Istirahat: 11:30 - 13:00 WIB</p>
                  </div>
                </div>
              </div>

              <div class="card p-6 bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800">
                <h3 class="font-semibold text-amber-800 dark:text-amber-400 mb-2">Gratis!</h3>
                <p class="text-sm text-amber-700 dark:text-amber-500">Peminjaman tidak dipungut biaya untuk kegiatan pendidikan dan sosial.</p>
              </div>
            </div>

            <!-- Form -->
            <div class="lg:col-span-2">
              <div class="card p-8">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Form Pengajuan Peminjaman</h2>
                
                <div v-if="showSuccess" class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                  <p class="font-medium text-green-800 dark:text-green-400">✅ Pengajuan Terkirim!</p>
                  <p class="text-sm text-green-700 dark:text-green-500">Gunakan tab "Cek Status" untuk melihat status pengajuan Anda.</p>
                </div>

                <form @submit.prevent="submitForm" class="space-y-6">
                  <div class="grid md:grid-cols-2 gap-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Pemohon *</label>
                      <input v-model="formData.namaPemohon" type="text" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white" placeholder="Nama lengkap"/>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Instansi/Lembaga *</label>
                      <input v-model="formData.instansi" type="text" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
                    </div>
                  </div>

                  <div class="grid md:grid-cols-2 gap-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                      <input v-model="formData.email" type="email" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Telepon *</label>
                      <input v-model="formData.telepon" type="tel" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
                    </div>
                  </div>

                  <div class="grid md:grid-cols-2 gap-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Mulai *</label>
                      <input v-model="formData.tanggalMulai" type="date" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Selesai *</label>
                      <input v-model="formData.tanggalSelesai" type="date" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
                    </div>
                  </div>

                  <div class="grid md:grid-cols-2 gap-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Waktu Mulai *</label>
                      <input v-model="formData.waktuMulai" type="time" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Waktu Selesai *</label>
                      <input v-model="formData.waktuSelesai" type="time" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
                    </div>
                  </div>

                  <!-- Pilihan Ruang -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilihan Ruang *</label>
                    <div class="grid md:grid-cols-2 gap-4">
                      <label v-for="ruang in ruangOptions" :key="ruang.value"
                        class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all"
                        :class="formData.pilihanRuang === ruang.value ? 'border-amber-500 bg-amber-50 dark:bg-amber-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-amber-300'">
                        <input type="radio" :value="ruang.value" v-model="formData.pilihanRuang" class="sr-only"/>
                        <div class="flex items-center gap-3">
                          <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                            <span class="text-xl">🏛️</span>
                          </div>
                          <div>
                            <p class="font-medium text-gray-900 dark:text-white text-sm">{{ ruang.label }}</p>
                            <p class="text-xs text-gray-500">Kapasitas: {{ ruang.kapasitas }}</p>
                          </div>
                        </div>
                      </label>
                    </div>
                  </div>

                  <div class="grid md:grid-cols-2 gap-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Kegiatan *</label>
                      <select v-model="formData.jenisKegiatan" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        <option value="">Pilih jenis kegiatan</option>
                        <option v-for="jenis in jenisKegiatanOptions" :key="jenis" :value="jenis">{{ jenis }}</option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jumlah Peserta *</label>
                      <input v-model="formData.jumlahPeserta" type="number" required min="1" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white" placeholder="Estimasi jumlah"/>
                    </div>
                  </div>

                  <!-- Jenis Kegiatan Lainnya -->
                  <div v-if="formData.jenisKegiatan === 'Lainnya'">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sebutkan Jenis Kegiatan *</label>
                    <input v-model="formData.jenisKegiatanLainnya" type="text" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white" placeholder="Tuliskan jenis kegiatan Anda"/>
                  </div>

                  <!-- Upload -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Surat Permohonan (PDF) *</label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center">
                      <input type="file" accept=".pdf" @change="handleFileUpload" class="hidden" id="suratPermohonan"/>
                      <label for="suratPermohonan" class="cursor-pointer">
                        <div v-if="!fileName" class="space-y-2">
                          <svg class="w-10 h-10 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                          <p class="text-sm text-gray-600">Klik untuk upload surat permohonan</p>
                          <p class="text-xs text-gray-500">Format PDF, maksimal 5MB</p>
                        </div>
                        <div v-else class="flex items-center justify-center gap-3">
                          <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/></svg>
                          <div class="text-left">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ fileName }}</p>
                            <p class="text-xs text-gray-500">Klik untuk mengganti</p>
                          </div>
                        </div>
                      </label>
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Keterangan Tambahan</label>
                    <textarea v-model="formData.keterangan" rows="3" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white" placeholder="Informasi tambahan..."></textarea>
                  </div>

                  <button type="submit" :disabled="isSubmitting" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-medium py-3 rounded-lg transition-colors">
                    {{ isSubmitting ? 'Mengirim...' : 'Ajukan Peminjaman' }}
                  </button>
                </form>
              </div>
            </div>
          </div>

        <!-- Jadwal & Cek Status Side by Side -->
        <div class="mt-12 grid lg:grid-cols-2 gap-8">
          <!-- Kalender Jadwal -->
          <div>
            <div class="card p-6">
              <div class="flex items-center justify-between mb-6">
                <button @click="prevMonth" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">📅 {{ currentMonthName }}</h2>
                <button @click="nextMonth" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
              </div>
              
              <!-- Days header -->
              <div class="grid grid-cols-7 gap-1 mb-2">
                <div v-for="day in ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab']" :key="day" class="text-center text-xs font-medium text-gray-500 py-2">{{ day }}</div>
              </div>
              
              <!-- Calendar grid -->
              <div class="grid grid-cols-7 gap-1">
                <div v-for="(day, idx) in calendarDays" :key="idx" 
                  :class="['aspect-square flex items-center justify-center rounded-lg text-sm relative',
                    !day.date ? '' : 
                    day.isBooked ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' :
                    day.isToday ? 'bg-amber-500 text-white font-bold' : 
                    'bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700']">
                  {{ day.date }}
                  <span v-if="day.isBooked" class="absolute bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 bg-red-500 rounded-full"></span>
                </div>
              </div>
              
              <!-- Legend -->
              <div class="flex flex-wrap items-center justify-center gap-4 mt-4 text-xs">
                <div class="flex items-center gap-1">
                  <div class="w-3 h-3 bg-amber-500 rounded"></div>
                  <span>Hari Ini</span>
                </div>
                <div class="flex items-center gap-1">
                  <div class="w-3 h-3 bg-red-100 border border-red-300 rounded"></div>
                  <span>Dipesan</span>
                </div>
                <div class="flex items-center gap-1">
                  <div class="w-3 h-3 bg-gray-50 border border-gray-200 rounded"></div>
                  <span>Tersedia</span>
                </div>
              </div>
            </div>
            
            <!-- Upcoming Bookings -->
            <div class="card p-6 mt-4" v-if="bookedDates.length > 0">
              <h3 class="font-bold text-gray-900 dark:text-white mb-4">📋 Jadwal Terisi</h3>
              <div class="space-y-2 max-h-48 overflow-y-auto">
                <div v-for="(booking, idx) in bookedDates" :key="idx" class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-sm">
                  <div>
                    <p class="font-medium text-gray-900 dark:text-white">{{ booking.instansi }}</p>
                    <p class="text-xs text-gray-500">{{ getRuangLabel(booking.ruang) }} • {{ booking.waktuMulai }}-{{ booking.waktuSelesai }}</p>
                  </div>
                  <div class="text-right text-xs">
                    <p class="text-gray-700 dark:text-gray-300">{{ formatDate(booking.start) }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Cek Status -->
          <div>
            <div class="card p-6">
              <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">🔍 Cek Status Pengajuan</h2>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Masukkan email atau telepon untuk cek status.</p>
              
              <div class="space-y-3">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                  <input v-model="statusCheckData.email" type="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm" placeholder="email@domain.com"/>
                </div>
                <div class="text-center text-gray-400 text-xs">atau</div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telepon</label>
                  <input v-model="statusCheckData.telepon" type="tel" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm" placeholder="08xxx"/>
                </div>
                <button @click="checkStatus" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-medium py-2 rounded-lg text-sm">
                  Cari Pengajuan
                </button>
              </div>
            </div>

            <!-- Results -->
            <div v-if="statusResults.length > 0" class="mt-4 space-y-3">
              <h3 class="font-bold text-gray-900 dark:text-white text-sm">Hasil ({{ statusResults.length }})</h3>
              <div v-for="item in statusResults" :key="item.id" class="card p-4">
                <div class="flex items-start justify-between mb-2">
                  <div>
                    <p class="font-medium text-gray-900 dark:text-white text-sm">{{ item.instansi }}</p>
                    <p class="text-xs text-gray-500">{{ getRuangLabel(item.pilihanRuang) }}</p>
                  </div>
                  <span :class="['px-2 py-0.5 rounded-full text-xs font-medium', getStatusBadge(item.status).class]">
                    {{ getStatusBadge(item.status).label }}
                  </span>
                </div>
                <div class="text-xs text-gray-600 dark:text-gray-400">
                  <p>{{ formatDate(item.tanggalMulai) }} - {{ formatDate(item.tanggalSelesai) }}</p>
                  <p>{{ item.jenisKegiatan }}</p>
                </div>
                <div v-if="item.catatan" class="mt-2 p-2 bg-gray-50 dark:bg-gray-700/50 rounded text-xs">
                  <p class="text-gray-500">Catatan:</p>
                  <p class="text-gray-700 dark:text-gray-300">{{ item.catatan }}</p>
                </div>
              </div>
            </div>
            <div v-else-if="statusCheckData.email || statusCheckData.telepon" class="mt-4 text-center text-gray-500 text-sm">
              <p>Tidak ditemukan pengajuan.</p>
            </div>
          </div>
        </div>

      </div>
    </section>
  </div>
</template>
