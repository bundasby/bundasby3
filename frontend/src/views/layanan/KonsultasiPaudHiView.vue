<script setup>
import { ref, computed, onMounted } from 'vue'
import { settingsStorage, konsultasiStorage } from '@/services/localStorage'

const formData = ref({
  nama: '',
  instansi: '',
  email: '',
  telepon: '',
  topik: '',
  topikLainnya: '',
  tanggal: '',
  jam: '',
  narasumber: '',
  metode: 'online', // online or offline
  pesan: ''
})

const settings = ref({
  phone: '',
  email: ''
})

const statusCheckData = ref({
  email: '',
  telepon: ''
})

const statusResults = ref([])
const bookedSlots = ref([])
const currentMonth = ref(new Date())
const activeSection = ref('konsultasi') // 'konsultasi' or 'forum'

// Forum Chat data with replies
const forumPosts = ref([
  { 
    id: 1, 
    author: 'Ibu Sari', 
    instansi: 'TK Melati Indah',
    avatar: 'https://i.pravatar.cc/40?img=1', 
    topic: 'Kurikulum Merdeka', 
    question: 'Bagaimana cara mengimplementasikan kurikulum merdeka di PAUD dengan keterbatasan fasilitas?', 
    attachmentUrl: null,
    attachmentFile: null,
    date: '2025-01-02', 
    likes: 12,
    replies: [
      { id: 1, author: 'Admin Tim PAUD HI', instansi: 'Bunda PAUD Kota Surabaya', content: 'Untuk implementasi kurikulum merdeka, bisa dimulai dengan pendekatan bermain berbasis proyek sederhana yang memanfaatkan bahan-bahan lokal.', date: '2025-01-02', likes: 5, attachmentUrl: null, attachmentFile: null }
    ]
  },
  { 
    id: 2, 
    author: 'Ibu Dewi', 
    instansi: 'PAUD Harapan Bunda',
    avatar: 'https://i.pravatar.cc/40?img=5', 
    topic: 'PAUD HI', 
    question: 'Apa saja indikator keberhasilan PAUD Holistik Integratif?', 
    attachmentUrl: 'https://example.com/dokumen-paud-hi.pdf',
    attachmentFile: null,
    date: '2025-01-01', 
    likes: 8,
    replies: []
  },
  { 
    id: 3, 
    author: 'Bunda Rina', 
    instansi: 'KB Ceria',
    avatar: 'https://i.pravatar.cc/40?img=9', 
    topic: 'Pengasuhan', 
    question: 'Tips mengatasi anak yang sulit berkonsentrasi saat belajar di rumah', 
    attachmentUrl: null,
    attachmentFile: null,
    date: '2025-12-28', 
    likes: 24,
    replies: [
      { id: 1, author: 'Psikolog Anak', instansi: 'Tim Konsultan', content: 'Coba gunakan metode bermain sambil belajar dengan durasi 15-20 menit per sesi.', date: '2025-12-28', likes: 8, attachmentUrl: null, attachmentFile: null },
      { id: 2, author: 'Ibu Maya', instansi: 'TK Tunas Bangsa', content: 'Saya juga mengalami hal sama, ternyata musik latar membantu anak fokus.', date: '2025-12-29', likes: 3, attachmentUrl: null, attachmentFile: null }
    ]
  },
])

const forumQuestion = ref({
  name: '',
  instansi: '',
  telepon: '',
  topic: '',
  topikLainnya: '',
  question: '',
  attachmentUrl: '',
  attachmentFile: null
})

const replyData = ref({
  postId: null,
  name: '',
  instansi: '',
  content: '',
  attachmentUrl: '',
  attachmentFile: null
})

const isSubmittingForum = ref(false)
const showForumSuccess = ref(false)
const showReplyForm = ref(null)

// File upload handlers
const handleQuestionFileUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    forumQuestion.value.attachmentFile = file
  }
}

const handleReplyFileUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    replyData.value.attachmentFile = file
  }
}

const topikOptions = [
  'Pengembangan Kurikulum PAUD',
  'Pelatihan Guru PAUD',
  'Sarana Prasarana PAUD',
  'Akreditasi PAUD',
  'Program PAUD Holistik Integratif',
  'Penerapan Merdeka Belajar',
  'Manajemen Lembaga PAUD',
  'Lainnya'
]

const narasumberOptions = [
  { id: 'ns1', nama: 'Tim Ahli Kurikulum', bidang: 'Kurikulum & Pembelajaran' },
  { id: 'ns2', nama: 'Tim Akreditasi', bidang: 'Akreditasi & Penjaminan Mutu' },
  { id: 'ns3', nama: 'Tim PAUD HI', bidang: 'PAUD Holistik Integratif' },
  { id: 'ns4', nama: 'Tim Pendampingan', bidang: 'Manajemen & Administrasi' }
]

const jamOptions = [
  '08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00'
]

const isSubmitting = ref(false)
const showSuccess = ref(false)

onMounted(() => {
  loadSettings()
  loadBookedSlots()
})

const loadSettings = () => {
  const data = settingsStorage.get()
  if (data) {
    settings.value.phone = data.phone || '(031) 555-5555'
    settings.value.email = data.email || 'konsultasi@bundapaud.surabaya.go.id'
  }
}

const loadBookedSlots = () => {
  const approved = konsultasiStorage.getApproved()
  bookedSlots.value = approved.map(k => ({
    tanggal: k.tanggal,
    jam: k.jam,
    narasumber: k.narasumber,
    nama: k.nama
  }))
}

// Calendar helpers
const calendarDays = computed(() => {
  const year = currentMonth.value.getFullYear()
  const month = currentMonth.value.getMonth()
  const firstDay = new Date(year, month, 1)
  const lastDay = new Date(year, month + 1, 0)
  const days = []
  
  for (let i = 0; i < firstDay.getDay(); i++) {
    days.push({ date: null })
  }
  
  for (let i = 1; i <= lastDay.getDate(); i++) {
    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`
    const bookingsOnDate = bookedSlots.value.filter(b => b.tanggal === dateStr)
    days.push({
      date: i,
      dateStr,
      hasBooking: bookingsOnDate.length > 0,
      bookingCount: bookingsOnDate.length,
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

const checkSlotAvailable = (tanggal, jam, narasumber) => {
  return !bookedSlots.value.some(b => 
    b.tanggal === tanggal && b.jam === jam && b.narasumber === narasumber
  )
}

const submitForm = async () => {
  // Validate slot
  if (!checkSlotAvailable(formData.value.tanggal, formData.value.jam, formData.value.narasumber)) {
    alert('Maaf, slot waktu ini sudah terisi. Silakan pilih waktu lain.')
    return
  }
  
  isSubmitting.value = true
  
  const topikFinal = formData.value.topik === 'Lainnya' 
    ? formData.value.topikLainnya 
    : formData.value.topik
  
  konsultasiStorage.create({
    nama: formData.value.nama,
    instansi: formData.value.instansi,
    email: formData.value.email,
    telepon: formData.value.telepon,
    topik: topikFinal,
    tanggal: formData.value.tanggal,
    jam: formData.value.jam,
    narasumber: formData.value.narasumber,
    metode: formData.value.metode,
    pesan: formData.value.pesan
  })
  
  await new Promise(resolve => setTimeout(resolve, 1000))
  isSubmitting.value = false
  showSuccess.value = true
  
  // Reset form
  formData.value = {
    nama: '', instansi: '', email: '', telepon: '',
    topik: '', topikLainnya: '', tanggal: '', jam: '',
    narasumber: '', metode: 'online', pesan: ''
  }
  
  loadBookedSlots()
}

const checkStatus = () => {
  if (!statusCheckData.value.email && !statusCheckData.value.telepon) {
    alert('Masukkan email atau nomor telepon')
    return
  }
  statusResults.value = konsultasiStorage.findByContact(
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

const getNarasumberLabel = (id) => {
  const ns = narasumberOptions.find(n => n.id === id)
  return ns ? ns.nama : id
}

const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
}

const submitForumQuestion = async () => {
  if (!forumQuestion.value.name || !forumQuestion.value.telepon || !forumQuestion.value.question) {
    alert('Mohon lengkapi nama, no HP, dan pertanyaan')
    return
  }
  
  const topikFinal = forumQuestion.value.topic === 'Lainnya' 
    ? forumQuestion.value.topikLainnya 
    : forumQuestion.value.topic
    
  if (!topikFinal) {
    alert('Mohon pilih topik')
    return
  }
  
  isSubmittingForum.value = true
  await new Promise(resolve => setTimeout(resolve, 1000))
  
  // Get file name if uploaded
  const fileName = forumQuestion.value.attachmentFile ? forumQuestion.value.attachmentFile.name : null
  
  forumPosts.value.unshift({
    id: Date.now(),
    author: forumQuestion.value.name,
    instansi: forumQuestion.value.instansi || '-',
    avatar: `https://i.pravatar.cc/40?u=${forumQuestion.value.telepon}`,
    topic: topikFinal,
    question: forumQuestion.value.question,
    attachmentUrl: forumQuestion.value.attachmentUrl || null,
    attachmentFile: fileName,
    date: new Date().toISOString().split('T')[0],
    likes: 0,
    replies: []
  })
  
  isSubmittingForum.value = false
  showForumSuccess.value = true
  forumQuestion.value = { name: '', instansi: '', telepon: '', topic: '', topikLainnya: '', question: '', attachmentUrl: '', attachmentFile: null }
  
  setTimeout(() => showForumSuccess.value = false, 3000)
}

const likePost = (postId) => {
  const post = forumPosts.value.find(p => p.id === postId)
  if (post) post.likes++
}

const likeReply = (postId, replyId) => {
  const post = forumPosts.value.find(p => p.id === postId)
  if (post) {
    const reply = post.replies.find(r => r.id === replyId)
    if (reply) reply.likes++
  }
}

const toggleReplyForm = (postId) => {
  showReplyForm.value = showReplyForm.value === postId ? null : postId
  replyData.value = { postId: postId, name: '', instansi: '', content: '', attachmentUrl: '', attachmentFile: null }
}

const submitReply = async (postId) => {
  if (!replyData.value.name || !replyData.value.content) {
    alert('Mohon isi nama dan balasan')
    return
  }
  
  const fileName = replyData.value.attachmentFile ? replyData.value.attachmentFile.name : null
  
  const post = forumPosts.value.find(p => p.id === postId)
  if (post) {
    post.replies.push({
      id: Date.now(),
      author: replyData.value.name,
      instansi: replyData.value.instansi || '-',
      content: replyData.value.content,
      attachmentUrl: replyData.value.attachmentUrl || null,
      attachmentFile: fileName,
      date: new Date().toISOString().split('T')[0],
      likes: 0
    })
  }
  
  showReplyForm.value = null
  replyData.value = { postId: null, name: '', instansi: '', content: '', attachmentUrl: '', attachmentFile: null }
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Hero -->
    <section class="bg-gradient-to-br from-primary-600 to-primary-800 text-white py-16">
      <div class="section-container text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Konsultasi PAUD HI</h1>
        <p class="text-primary-100 max-w-2xl mx-auto">
          Layanan konsultasi gratis seputar Pendidikan Anak Usia Dini Holistik Integratif
        </p>
      </div>
    </section>

    <!-- Tab Navigation -->
    <section class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 sticky top-16 z-10">
      <div class="section-container">
        <div class="flex gap-1">
          <button 
            @click="activeSection = 'konsultasi'" 
            :class="['px-6 py-4 font-medium text-sm transition-all border-b-2', 
              activeSection === 'konsultasi' 
                ? 'border-primary-500 text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20' 
                : 'border-transparent text-gray-600 dark:text-gray-400 hover:text-primary-600 hover:bg-gray-50 dark:hover:bg-gray-700/50']"
          >
            📋 Booking Konsultasi
          </button>
          <button 
            @click="activeSection = 'forum'" 
            :class="['px-6 py-4 font-medium text-sm transition-all border-b-2', 
              activeSection === 'forum' 
                ? 'border-primary-500 text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20' 
                : 'border-transparent text-gray-600 dark:text-gray-400 hover:text-primary-600 hover:bg-gray-50 dark:hover:bg-gray-700/50']"
          >
            💬 Forum Tanya Jawab
          </button>
        </div>
      </div>
    </section>

    <!-- Konsultasi Section -->
    <section v-if="activeSection === 'konsultasi'" class="py-12">
      <div class="section-container">
        <div class="grid lg:grid-cols-3 gap-8">
          <!-- Info -->
          <div class="lg:col-span-1 space-y-6">
            <div class="card p-6">
              <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Tentang Layanan</h3>
              <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                Konsultasi PAUD HI adalah layanan konsultasi gratis yang disediakan oleh Tim Bunda PAUD Kota Surabaya 
                untuk membantu lembaga PAUD, guru, dan masyarakat dalam pengembangan pendidikan anak usia dini 
                yang holistik dan integratif.
              </p>
            </div>

            <div class="card p-6">
              <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Jam Layanan
              </h3>
              <div class="space-y-4 text-sm">
                <div>
                  <p class="font-medium text-gray-900 dark:text-white mb-1">Senin - Kamis</p>
                  <p class="text-gray-600 dark:text-gray-400">07:30 - 16:00 WIB</p>
                  <p class="text-xs text-gray-500">Istirahat: 12:00 - 13:00 WIB</p>
                </div>
                <div>
                  <p class="font-medium text-gray-900 dark:text-white mb-1">Jumat</p>
                  <p class="text-gray-600 dark:text-gray-400">07:00 - 16:30 WIB</p>
                  <p class="text-xs text-gray-500">Istirahat: 11:30 - 13:00 WIB</p>
                </div>
              </div>
            </div>

            <div class="card p-6">
              <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Kontak Langsung</h3>
              <div class="space-y-3 text-sm">
                <a :href="'tel:' + settings.phone" class="flex items-center gap-3 text-gray-600 dark:text-gray-400 hover:text-primary-600">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                  {{ settings.phone }}
                </a>
                <a :href="'mailto:' + settings.email" class="flex items-center gap-3 text-gray-600 dark:text-gray-400 hover:text-primary-600 break-all">
                  <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                  {{ settings.email }}
                </a>
              </div>
            </div>

            <div class="card p-6 bg-primary-50 dark:bg-primary-900/20 border-primary-200 dark:border-primary-800">
              <h3 class="font-semibold text-primary-800 dark:text-primary-400 mb-2">Gratis!</h3>
              <p class="text-sm text-primary-700 dark:text-primary-500">Layanan konsultasi tidak dipungut biaya apapun.</p>
            </div>
          </div>

          <!-- Form -->
          <div class="lg:col-span-2">
            <div class="card p-8">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Form Booking Konsultasi</h2>
              
              <div v-if="showSuccess" class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <p class="font-medium text-green-800 dark:text-green-400">✅ Booking Konsultasi Terkirim!</p>
                <p class="text-sm text-green-700 dark:text-green-500">Gunakan form "Cek Status" di bawah untuk memantau status booking Anda.</p>
              </div>

              <form @submit.prevent="submitForm" class="space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap *</label>
                    <input v-model="formData.nama" type="text" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white" placeholder="Masukkan nama lengkap"/>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Instansi/Lembaga</label>
                    <input v-model="formData.instansi" type="text" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white" placeholder="Nama lembaga PAUD (opsional)"/>
                  </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                    <input v-model="formData.email" type="email" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white" placeholder="email@contoh.com"/>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nomor Telepon *</label>
                    <input v-model="formData.telepon" type="tel" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white" placeholder="08xxxxxxxxxx"/>
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Topik Konsultasi *</label>
                  <select v-model="formData.topik" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    <option value="">Pilih topik konsultasi</option>
                    <option v-for="topik in topikOptions" :key="topik" :value="topik">{{ topik }}</option>
                  </select>
                </div>

                <div v-if="formData.topik === 'Lainnya'">
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sebutkan Topik *</label>
                  <input v-model="formData.topikLainnya" type="text" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white" placeholder="Tuliskan topik konsultasi Anda"/>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Konsultasi *</label>
                    <input v-model="formData.tanggal" type="date" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jam Konsultasi *</label>
                    <select v-model="formData.jam" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                      <option value="">Pilih jam</option>
                      <option v-for="jam in jamOptions" :key="jam" :value="jam">{{ jam }} WIB</option>
                    </select>
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Narasumber *</label>
                  <div class="grid md:grid-cols-2 gap-3">
                    <label v-for="ns in narasumberOptions" :key="ns.id"
                      class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all"
                      :class="formData.narasumber === ns.id ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-primary-300'">
                      <input type="radio" :value="ns.id" v-model="formData.narasumber" class="sr-only"/>
                      <div>
                        <p class="font-medium text-gray-900 dark:text-white text-sm">{{ ns.nama }}</p>
                        <p class="text-xs text-gray-500">{{ ns.bidang }}</p>
                      </div>
                    </label>
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Metode Konsultasi *</label>
                  <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                      <input type="radio" value="online" v-model="formData.metode" class="w-4 h-4 text-primary-600"/>
                      <span class="text-gray-900 dark:text-white">🌐 Online (Video Call)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                      <input type="radio" value="offline" v-model="formData.metode" class="w-4 h-4 text-primary-600"/>
                      <span class="text-gray-900 dark:text-white">🏢 Offline (Tatap Muka)</span>
                    </label>
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pesan/Pertanyaan *</label>
                  <textarea v-model="formData.pesan" required rows="4" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white" placeholder="Tuliskan pertanyaan atau hal yang ingin dikonsultasikan..."></textarea>
                </div>

                <button type="submit" :disabled="isSubmitting" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-3 rounded-lg transition-colors">
                  {{ isSubmitting ? 'Mengirim...' : 'Booking Konsultasi' }}
                </button>
              </form>
            </div>
          </div>
        </div>

        <!-- Calendar & Status Check -->
        <div class="mt-12 grid lg:grid-cols-2 gap-8">
          <!-- Calendar -->
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
              
              <div class="grid grid-cols-7 gap-1 mb-2">
                <div v-for="day in ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab']" :key="day" class="text-center text-xs font-medium text-gray-500 py-2">{{ day }}</div>
              </div>
              
              <div class="grid grid-cols-7 gap-1">
                <div v-for="(day, idx) in calendarDays" :key="idx" 
                  :class="['aspect-square flex items-center justify-center rounded-lg text-sm relative',
                    !day.date ? '' : 
                    day.hasBooking ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' :
                    day.isToday ? 'bg-primary-500 text-white font-bold' : 
                    'bg-gray-50 dark:bg-gray-700/50']">
                  {{ day.date }}
                  <span v-if="day.hasBooking" class="absolute bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-500 rounded-full"></span>
                </div>
              </div>
              
              <div class="flex flex-wrap items-center justify-center gap-4 mt-4 text-xs">
                <div class="flex items-center gap-1">
                  <div class="w-3 h-3 bg-primary-500 rounded"></div>
                  <span>Hari Ini</span>
                </div>
                <div class="flex items-center gap-1">
                  <div class="w-3 h-3 bg-blue-100 border border-blue-300 rounded"></div>
                  <span>Ada Booking</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Status Check -->
          <div>
            <div class="card p-6">
              <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">🔍 Cek Status Booking</h2>
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
                <button @click="checkStatus" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 rounded-lg text-sm">
                  Cari Booking
                </button>
              </div>
            </div>

            <div v-if="statusResults.length > 0" class="mt-4 space-y-3">
              <h3 class="font-bold text-gray-900 dark:text-white text-sm">Hasil ({{ statusResults.length }})</h3>
              <div v-for="item in statusResults" :key="item.id" class="card p-4">
                <div class="flex items-start justify-between mb-2">
                  <div>
                    <p class="font-medium text-gray-900 dark:text-white text-sm">{{ item.topik }}</p>
                    <p class="text-xs text-gray-500">{{ getNarasumberLabel(item.narasumber) }}</p>
                  </div>
                  <span :class="['px-2 py-0.5 rounded-full text-xs font-medium', getStatusBadge(item.status).class]">
                    {{ getStatusBadge(item.status).label }}
                  </span>
                </div>
                <div class="text-xs text-gray-600 dark:text-gray-400">
                  <p>📅 {{ formatDate(item.tanggal) }} • {{ item.jam }} WIB</p>
                  <p>{{ item.metode === 'online' ? '🌐 Online' : '🏢 Offline' }}</p>
                </div>
                <div v-if="item.catatan" class="mt-2 p-2 bg-gray-50 dark:bg-gray-700/50 rounded text-xs">
                  <p class="text-gray-500">Catatan:</p>
                  <p class="text-gray-700 dark:text-gray-300">{{ item.catatan }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- Forum Section -->
    <section v-if="activeSection === 'forum'" class="py-12">
      <div class="section-container">
        <div class="grid lg:grid-cols-3 gap-8">
          <!-- Sidebar Forum -->
          <div class="lg:col-span-1 space-y-6">
            <div class="card p-6">
              <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                💬 Tentang Forum
              </h3>
              <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                Forum Tanya Jawab PAUD HI adalah wadah diskusi terbuka untuk Bunda PAUD, guru, dan masyarakat. 
                Ajukan pertanyaan dan berbagi pengalaman seputar pendidikan anak usia dini.
              </p>
            </div>

            <div class="card p-6">
              <h3 class="font-semibold text-gray-900 dark:text-white mb-4">📊 Statistik Forum</h3>
              <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Total Diskusi</span>
                  <span class="font-medium text-primary-600">{{ forumPosts.length }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Total Jawaban</span>
                  <span class="font-medium text-green-600">{{ forumPosts.reduce((a, b) => a + b.replies.length, 0) }}</span>
                </div>
              </div>
            </div>

            <div class="card p-6 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800">
              <h3 class="font-semibold text-amber-800 dark:text-amber-400 mb-3">📝 Ketentuan Forum</h3>
              <ul class="text-xs text-amber-700 dark:text-amber-500 space-y-2">
                <li>1. Fitur tanya jawab berikut hanya berupa pemberian informasi umum dan tidak menggantikan konsultasi secara tatap muka.</li>
                <li>2. Mohon dapat menuliskan pertanyaan secara jelas.</li>
                <li>3. Mohon cantumkan no HP aktif untuk memperoleh notifikasi jawaban pertanyaan Anda.</li>
                <li>4. Kami berhak menghapus pertanyaan yang tidak sesuai ketentuan dan/atau melanggar peraturan yang berlaku.</li>
                <li>5. Anda dapat mengajukan pertanyaan kapan saja.</li>
                <li>6. Konsultan menjawab pada hari kerja Senin-Jumat, 07.30–16.00 WIB, maks. 1x24 jam.</li>
              </ul>
            </div>
          </div>

          <!-- Main Forum Content -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Ask Question Form -->
            <div class="card p-6">
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ajukan Pertanyaan Baru</h2>
              
              <div v-if="showForumSuccess" class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <p class="text-green-700 dark:text-green-400 text-sm">✅ Pertanyaan Anda berhasil dikirim!</p>
              </div>
              
              <form @submit.prevent="submitForumQuestion" class="space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama *</label>
                    <input v-model="forumQuestion.name" type="text" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm" placeholder="Nama Anda"/>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Asal Instansi/Lembaga</label>
                    <input v-model="forumQuestion.instansi" type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm" placeholder="Nama lembaga PAUD"/>
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No HP *</label>
                  <input v-model="forumQuestion.telepon" type="tel" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm" placeholder="08xxxxxxxxxx"/>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Topik *</label>
                  <select v-model="forumQuestion.topic" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm">
                    <option value="">Pilih topik</option>
                    <option v-for="topik in topikOptions" :key="topik" :value="topik">{{ topik }}</option>
                  </select>
                </div>
                <div v-if="forumQuestion.topic === 'Lainnya'">
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sebutkan Topik *</label>
                  <input v-model="forumQuestion.topikLainnya" type="text" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm" placeholder="Tuliskan topik Anda"/>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pertanyaan *</label>
                  <textarea v-model="forumQuestion.question" required rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm" placeholder="Tuliskan pertanyaan Anda..."></textarea>
                </div>
                <div class="space-y-3">
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lampiran</label>
                  <div class="flex gap-2">
                    <label class="flex-1 flex items-center justify-center gap-2 px-3 py-2 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-sm cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                      {{ forumQuestion.attachmentFile ? forumQuestion.attachmentFile.name : 'Upload Gambar' }}
                      <input type="file" accept="image/*" class="hidden" @change="handleQuestionFileUpload"/>
                    </label>
                  </div>
                  <input v-model="forumQuestion.attachmentUrl" type="url" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm" placeholder="Atau masukkan link (https://...)"/>
                </div>
                <button type="submit" :disabled="isSubmittingForum" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 rounded-lg text-sm">
                  {{ isSubmittingForum ? 'Mengirim...' : 'Kirim Pertanyaan' }}
                </button>
              </form>
            </div>

            <!-- Forum Posts List -->
            <div class="card p-6">
              <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ forumPosts.length }} Diskusi</h2>
              </div>
              
              <div class="space-y-6">
                <div v-for="post in forumPosts" :key="post.id" class="border dark:border-gray-700 rounded-xl overflow-hidden">
                  <!-- Main Post -->
                  <div class="p-4 bg-gray-50 dark:bg-gray-800/50">
                    <div class="flex items-start gap-3">
                      <img :src="post.avatar" :alt="post.author" class="w-12 h-12 rounded-full border-2 border-white shadow"/>
                      <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                          <span class="font-semibold text-primary-600 dark:text-primary-400">{{ post.author }}</span>
                          <span class="text-xs text-gray-500">{{ post.instansi }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                          <span>📅 {{ formatDate(post.date) }}</span>
                          <span class="px-2 py-0.5 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 rounded">{{ post.topic }}</span>
                        </div>
                        <p class="text-gray-800 dark:text-gray-200 text-sm">{{ post.question }}</p>
                        <a v-if="post.attachmentUrl" :href="post.attachmentUrl" target="_blank" class="inline-flex items-center gap-1 mt-2 text-xs text-blue-600 hover:underline">� {{ post.attachmentUrl }}</a>
                        <span v-if="post.attachmentFile" class="inline-flex items-center gap-1 mt-2 text-xs text-green-600">📎 {{ post.attachmentFile }}</span>
                      </div>
                    </div>
                    <div class="flex items-center gap-4 mt-3 pt-3 border-t dark:border-gray-700">
                      <button @click="likePost(post.id)" class="flex items-center gap-1 text-sm text-gray-600 hover:text-primary-600">👍 {{ post.likes }}</button>
                      <button @click="toggleReplyForm(post.id)" class="flex items-center gap-1 text-sm text-gray-600 hover:text-primary-600">💬 Reply ({{ post.replies.length }})</button>
                    </div>
                  </div>
                  
                  <!-- Replies -->
                  <div v-if="post.replies.length > 0" class="bg-white dark:bg-gray-900 border-t dark:border-gray-700">
                    <div v-for="reply in post.replies" :key="reply.id" class="p-4 border-b dark:border-gray-700 last:border-b-0">
                      <div class="flex items-start gap-3 ml-6">
                        <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 text-sm font-bold">{{ reply.author.charAt(0) }}</div>
                        <div class="flex-1">
                          <div class="flex items-center gap-2 mb-1">
                            <span class="font-medium text-gray-900 dark:text-white text-sm">{{ reply.author }}</span>
                            <span class="text-xs text-gray-500">{{ reply.instansi }}</span>
                            <span class="text-xs text-gray-400">• {{ formatDate(reply.date) }}</span>
                          </div>
                          <p class="text-gray-700 dark:text-gray-300 text-sm">{{ reply.content }}</p>
                          <a v-if="reply.attachmentUrl" :href="reply.attachmentUrl" target="_blank" class="inline-flex items-center gap-1 mt-1 text-xs text-blue-600 hover:underline">� {{ reply.attachmentUrl }}</a>
                          <span v-if="reply.attachmentFile" class="inline-flex items-center gap-1 mt-1 text-xs text-green-600">📎 {{ reply.attachmentFile }}</span>
                          <button @click="likeReply(post.id, reply.id)" class="flex items-center gap-1 mt-2 text-xs text-gray-500 hover:text-primary-600">👍 {{ reply.likes }}</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Reply Form -->
                  <div v-if="showReplyForm === post.id" class="p-4 bg-gray-100 dark:bg-gray-800 border-t dark:border-gray-700">
                    <h4 class="font-medium text-gray-900 dark:text-white text-sm mb-3">Balas Diskusi</h4>
                    <div class="space-y-3">
                      <div class="grid grid-cols-2 gap-3">
                        <input v-model="replyData.name" type="text" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-sm" placeholder="Nama *"/>
                        <input v-model="replyData.instansi" type="text" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-sm" placeholder="Asal Lembaga"/>
                      </div>
                      <textarea v-model="replyData.content" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-sm" placeholder="Tulis balasan..."></textarea>
                      <div class="flex gap-2">
                        <label class="flex items-center gap-2 px-3 py-2 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-600 text-xs cursor-pointer hover:bg-gray-100">
                          📷 {{ replyData.attachmentFile ? replyData.attachmentFile.name : 'Upload' }}
                          <input type="file" accept="image/*" class="hidden" @change="handleReplyFileUpload"/>
                        </label>
                        <input v-model="replyData.attachmentUrl" type="url" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-sm" placeholder="Atau link..."/>
                      </div>
                      <div class="flex gap-2">
                        <button @click="submitReply(post.id)" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm rounded-lg">Kirim</button>
                        <button @click="showReplyForm = null" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-white text-sm rounded-lg">Batal</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div v-if="forumPosts.length === 0" class="text-center py-8 text-gray-500">
                Belum ada diskusi. Jadilah yang pertama bertanya!
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
