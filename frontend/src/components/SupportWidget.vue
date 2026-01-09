<template>
  <div class="support-widget">
    <!-- Floating Toggle Button with Bunda PAUD Logo -->
    <div class="widget-toggle-container">
      <!-- Hotline Label with Animation -->
      <div v-if="!isOpen" class="hotline-label">
        <span class="hotline-text">HOTLINE</span>
        <span class="hotline-subtext">Bunda PAUD</span>
        <div class="hotline-arrow"></div>
      </div>

      <button 
        @click="toggleWidget" 
        class="widget-toggle"
        :class="{ 'is-open': isOpen }"
      >
        <!-- Bunda PAUD Logo when closed -->
        <div v-if="!isOpen" class="logo-container">
          <div class="logo-glow"></div>
          <img 
            src="/logo-bunda-paud.png" 
            alt="Bunda PAUD" 
            class="logo-img"
            onerror="this.src='https://placehold.co/60x60/2563eb/ffffff?text=BP'"
          />
        </div>
        <!-- Close icon when open -->
        <span v-else class="close-icon">✕</span>
      </button>
    </div>

    <!-- Widget Panel -->
    <transition name="widget-panel">
      <div v-if="isOpen" class="widget-container">
        <!-- Header with Logo -->
        <div class="widget-header">
          <div class="header-content">
            <img src="/logo-bunda-paud.png" alt="Logo" class="header-logo" 
                 onerror="this.src='https://placehold.co/40x40/2563eb/ffffff?text=BP'" />
            <div>
              <h4>{{ $t('support.title') }}</h4>
              <span class="status-online">● {{ $t('support.online') }}</span>
            </div>
          </div>
          <button @click="toggleWidget" class="close-btn">✕</button>
        </div>

        <!-- Tab Navigation -->
        <div class="widget-tabs">
          <button 
            @click="activeTab = 'chat'" 
            class="tab-btn"
            :class="{ active: activeTab === 'chat' }"
          >
            💬 {{ $t('support.chat') }}
          </button>
          <button 
            @click="activeTab = 'whatsapp'" 
            class="tab-btn"
            :class="{ active: activeTab === 'whatsapp' }"
          >
            📱 {{ $t('support.whatsapp') }}
          </button>
        </div>

        <!-- Chat Tab with n8n-like Bot -->
        <div v-show="activeTab === 'chat'" class="tab-content chat-tab">
          <div class="messages-container" ref="messagesContainer">
            <div v-for="(msg, idx) in messages" :key="idx" class="message" :class="msg.sender">
              <div class="message-bubble">
                <p v-html="msg.text"></p>
                <span class="message-time">{{ formatTime(msg.time) }}</span>
              </div>
            </div>
            
            <!-- Typing indicator -->
            <div v-if="isTyping" class="message bot">
              <div class="message-bubble typing">
                <span></span><span></span><span></span>
              </div>
            </div>
          </div>

          <!-- Quick Actions -->
          <div v-if="showQuickActions" class="quick-actions">
            <button v-for="action in quickActions" :key="action.id" @click="selectAction(action)">
              {{ action.icon }} {{ action.label }}
            </button>
          </div>

          <!-- Need Admin Help Button -->
          <div v-if="showAdminHelp" class="admin-help">
            <p>💡 Butuh bantuan lebih lanjut?</p>
            <button @click="contactAdmin" class="admin-btn">
              📞 Hubungi Hotline Bunda PAUD
            </button>
          </div>

          <!-- Chat Input -->
          <div class="chat-input">
            <input 
              v-model="inputText" 
              @keyup.enter="sendMessage"
              :placeholder="$t('support.typePlaceholder')"
            />
            <button @click="sendMessage" :disabled="!inputText.trim()">
              <svg viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            </button>
          </div>
        </div>

        <!-- WhatsApp Tab -->
        <div v-show="activeTab === 'whatsapp'" class="tab-content whatsapp-tab">
          <div class="wa-info">
            <div class="wa-icon">
              <img 
                src="/logo-bunda-paud.png" 
                alt="Bunda PAUD" 
                class="wa-logo"
                onerror="this.src='https://placehold.co/50x50/2563eb/ffffff?text=BP'"
              />
            </div>
            <h4>{{ $t('support.waTitle') }}</h4>
            <p>{{ $t('support.waSubtitle') }}</p>
          </div>

          <div class="wa-options">
            <button @click="openWhatsApp('umum')" class="wa-btn">
              📋 {{ $t('support.waGeneral') }}
            </button>
            <button @click="openWhatsApp('pengaduan')" class="wa-btn">
              📝 {{ $t('support.waComplaint') }}
            </button>
            <button @click="openWhatsApp('konsultasi')" class="wa-btn">
              🎓 {{ $t('support.waConsultation') }}
            </button>
            <button @click="openWhatsApp('peminjaman')" class="wa-btn">
              🏛️ {{ $t('support.waRental') }}
            </button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, nextTick, onMounted, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { settingsService } from '@/services'

const { t, locale } = useI18n()

// State
const isOpen = ref(false)
const activeTab = ref('chat')
const inputText = ref('')
const isTyping = ref(false)
const showQuickActions = ref(true)
const showAdminHelp = ref(false)
const messagesContainer = ref(null)
const whatsappNumber = ref('6281234567890')
const messageCount = ref(0)

const messages = ref([])

// n8n-like knowledge base for auto-responses
const knowledgeBase = [
  { 
    keywords: ['konsultasi', 'paud hi', 'konsul', 'tanya paud'],
    response: 'Layanan <b>Konsultasi PAUD HI</b> tersedia setiap hari kerja pukul <b>08:00-16:00 WIB</b>.<br><br>📍 Lokasi: Gedung Grha Bunda PAUD<br>📞 Telp: (031) 5xxx-xxxx<br><br>Atau kunjungi halaman <a href="/layanan/konsultasi-paud-hi" style="color:#0ea5e9;text-decoration:underline;">Konsultasi PAUD HI</a>'
  },
  { 
    keywords: ['peminjaman', 'gedung', 'grha', 'sewa', 'pinjam'],
    response: 'Untuk <b>peminjaman Gedung Grha Bunda PAUD</b>, silakan:<br><br>1️⃣ Isi formulir di halaman <a href="/layanan/peminjaman-gedung" style="color:#0ea5e9;text-decoration:underline;">Peminjaman Gedung</a><br>2️⃣ Siapkan surat permohonan resmi<br>3️⃣ Tunggu konfirmasi dari admin<br><br>📅 Booking minimal H-7 hari kerja'
  },
  { 
    keywords: ['pengaduan', 'aduan', 'lapor', 'keluhan', 'komplain'],
    response: 'Layanan <b>Pengaduan</b> kami siap menerima masukan Anda:<br><br>📝 Form online: <a href="/pengaduan" style="color:#0ea5e9;text-decoration:underline;">Halaman Pengaduan</a><br>📧 Email: pengaduan@bundapaud.surabaya.go.id<br>📞 Hotline: (031) 5xxx-xxxx<br><br>Semua pengaduan akan diproses dalam <b>3x24 jam</b> kerja.'
  },
  { 
    keywords: ['jam', 'buka', 'operasional', 'waktu', 'jadwal'],
    response: '<b>Jam Operasional:</b><br><br>📅 Senin - Jumat: 08:00 - 16:00 WIB<br>🚫 Sabtu & Minggu: Tutup<br>🚫 Hari Libur Nasional: Tutup<br><br>Untuk informasi darurat, hubungi hotline kami.'
  },
  { 
    keywords: ['alamat', 'lokasi', 'dimana', 'tempat', 'kantor'],
    response: '<b>Alamat Kantor:</b><br><br>📍 Gedung Grha Bunda PAUD<br>Jl. Ketintang Madya No. 2<br>Kecamatan Gayungan, Surabaya<br>Jawa Timur 60231<br><br>🗺️ <a href="https://maps.google.com" target="_blank" style="color:#0ea5e9;text-decoration:underline;">Lihat di Google Maps</a>'
  },
  { 
    keywords: ['daftar', 'mitra', 'kerjasama', 'partner', 'mou'],
    response: 'Untuk menjadi <b>Mitra PAUD</b>:<br><br>1️⃣ Kunjungi <a href="/mitra-paud/pendaftaran" style="color:#0ea5e9;text-decoration:underline;">Halaman Pendaftaran Mitra</a><br>2️⃣ Isi formulir dengan lengkap<br>3️⃣ Upload dokumen yang diperlukan<br>4️⃣ Tunggu verifikasi dari tim kami<br><br>Proses verifikasi: <b>7-14 hari kerja</b>'
  },
  { 
    keywords: ['bung karno', 'paud negeri', 'satu atap'],
    response: '<b>PAUD Negeri Bung Karno (Satu Atap)</b><br><br>PAUD binaan unggulan Bunda PAUD Kota Surabaya yang menerapkan kurikulum holistik integratif.<br><br>📍 Info lengkap: <a href="/layanan/paud-bung-karno" style="color:#0ea5e9;text-decoration:underline;">Halaman PAUD Bung Karno</a>'
  },
  { 
    keywords: ['program', 'kegiatan', 'acara', 'agenda'],
    response: '<b>Program Kerja Bunda PAUD 2026:</b><br><br>✅ Penguatan Pendidikan Karakter<br>✅ Wajib Belajar 13 Tahun<br>✅ Peningkatan Kompetensi Guru<br>✅ Literasi, Numerasi & Sains<br>✅ Sarana dan Prasarana<br><br>📄 Detail: <a href="/program" style="color:#0ea5e9;text-decoration:underline;">Halaman Program Kerja</a>'
  }
]

const quickActions = computed(() => [
  { id: 1, icon: '📝', label: t('support.quickActions.complaint'), keyword: 'pengaduan' },
  { id: 2, icon: '📋', label: t('support.quickActions.consultation'), keyword: 'konsultasi' },
  { id: 3, icon: '🏛️', label: t('support.quickActions.rental'), keyword: 'peminjaman' },
  { id: 4, icon: '❓', label: t('support.quickActions.faq'), keyword: 'faq' }
])

// Methods
function toggleWidget() {
  isOpen.value = !isOpen.value
  if (isOpen.value && messages.value.length === 0) {
    // Initial welcome message
    messages.value.push({ 
      sender: 'bot', 
      text: t('support.welcome'), 
      time: new Date() 
    })
  }
}

function findResponse(text) {
  const lowerText = text.toLowerCase()
  
  for (const item of knowledgeBase) {
    for (const keyword of item.keywords) {
      if (lowerText.includes(keyword)) {
        return item.response
      }
    }
  }
  
  // Default response after 2+ messages without match
  if (messageCount.value >= 2) {
    showAdminHelp.value = true
  }
  
  return 'Terima kasih atas pertanyaan Anda. Tim kami akan segera merespons. Untuk respon lebih cepat, silakan hubungi via WhatsApp atau gunakan tombol di bawah untuk menghubungi Hotline.'
}

function sendMessage() {
  if (!inputText.value.trim()) return
  
  messages.value.push({ sender: 'user', text: inputText.value, time: new Date() })
  const userText = inputText.value
  inputText.value = ''
  showQuickActions.value = false
  messageCount.value++
  scrollToBottom()
  
  // Bot typing indicator
  isTyping.value = true
  
  // Simulate n8n-like processing delay
  setTimeout(() => {
    isTyping.value = false
    const response = findResponse(userText)
    messages.value.push({ sender: 'bot', text: response, time: new Date() })
    scrollToBottom()
  }, 1000 + Math.random() * 500)
}

function selectAction(action) {
  messages.value.push({ sender: 'user', text: action.label, time: new Date() })
  showQuickActions.value = false
  messageCount.value++
  
  isTyping.value = true
  setTimeout(() => {
    isTyping.value = false
    let response = findResponse(action.keyword)
    
    if (action.keyword === 'faq') {
      response = 'Berikut beberapa pertanyaan yang sering diajukan:<br><br>❓ <b>Jam operasional?</b> - Senin-Jumat 08:00-16:00<br>❓ <b>Cara daftar mitra?</b> - Kunjungi halaman Pendaftaran Mitra<br>❓ <b>Lokasi kantor?</b> - Gedung Grha Bunda PAUD<br><br>Ketik pertanyaan Anda atau pilih topik di atas.'
    }
    
    messages.value.push({ sender: 'bot', text: response, time: new Date() })
    scrollToBottom()
  }, 800)
}

function contactAdmin() {
  const msg = encodeURIComponent('Halo, saya butuh bantuan dari admin Bunda PAUD. Pertanyaan saya tidak terjawab oleh chatbot.')
  window.open(`https://wa.me/${whatsappNumber.value}?text=${msg}`, '_blank')
}

function openWhatsApp(type) {
  const msgs = {
    umum: 'Halo, saya ingin bertanya tentang program Bunda PAUD Kota Surabaya',
    pengaduan: 'Halo, saya ingin menyampaikan pengaduan terkait layanan PAUD',
    konsultasi: 'Halo, saya ingin konsultasi tentang pendidikan anak usia dini',
    peminjaman: 'Halo, saya ingin mengajukan peminjaman Grha Bunda PAUD'
  }
  const msg = encodeURIComponent(msgs[type] || msgs.umum)
  window.open(`https://wa.me/${whatsappNumber.value}?text=${msg}`, '_blank')
}

function scrollToBottom() {
  nextTick(() => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
    }
  })
}

function formatTime(date) {
  return new Date(date).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
}

onMounted(() => {
  const settings = settingsService.get()
  if (settings.whatsappNumber) whatsappNumber.value = settings.whatsappNumber
})
</script>

<style scoped>
.support-widget {
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: 9999;
  font-family: system-ui, -apple-system, sans-serif;
}

/* Toggle Button Container */
.widget-toggle-container {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* Hotline Label with Animation */
.hotline-label {
  position: absolute;
  bottom: 85px;
  right: 0;
  background: white;
  padding: 8px 16px;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.15);
  text-align: center;
  animation: bounce 2s infinite;
  z-index: 10;
}

.hotline-text {
  display: block;
  font-size: 14px;
  font-weight: 900;
  color: #dc2626;
  letter-spacing: 1px;
}

.hotline-subtext {
  display: block;
  font-size: 10px;
  font-weight: 700;
  color: #0ea5e9;
}

.hotline-arrow {
  position: absolute;
  bottom: -8px;
  right: 25px;
  width: 16px;
  height: 16px;
  background: white;
  transform: rotate(45deg);
  box-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

@keyframes bounce {
  0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
  40% { transform: translateY(-8px); }
  60% { transform: translateY(-4px); }
}

/* Toggle Button with Logo */
.widget-toggle {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  background: white;
  border: 3px solid #0ea5e9;
  cursor: pointer;
  box-shadow: 0 4px 25px rgba(14, 165, 233, 0.4);
  transition: all 0.3s;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.widget-toggle:hover { 
  transform: scale(1.1); 
  box-shadow: 0 6px 30px rgba(14, 165, 233, 0.5);
}

.widget-toggle.is-open { 
  background: #64748b; 
  border-color: #64748b;
}

.logo-container {
  position: relative;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.logo-glow {
  position: absolute;
  inset: 0;
  background: radial-gradient(circle, rgba(14,165,233,0.3) 0%, transparent 70%);
  animation: pulse-glow 2s infinite;
}

@keyframes pulse-glow {
  0%, 100% { opacity: 0.5; transform: scale(0.95); }
  50% { opacity: 1; transform: scale(1.05); }
}

.logo-img {
  width: 50px;
  height: 50px;
  object-fit: contain;
  z-index: 1;
}

.close-icon {
  font-size: 24px;
  color: white;
}

/* Widget Container */
.widget-container {
  position: absolute;
  bottom: 85px;
  right: 0;
  width: 380px;
  height: 520px;
  background: white;
  border-radius: 20px;
  box-shadow: 0 15px 50px rgba(0,0,0,0.2);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.widget-header {
  background: linear-gradient(135deg, #0ea5e9, #0284c7);
  color: white;
  padding: 16px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header-content { display: flex; align-items: center; gap: 12px; }
.header-logo { width: 40px; height: 40px; border-radius: 50%; background: white; padding: 2px; }
.header-content h4 { margin: 0; font-size: 16px; font-weight: 600; }
.status-online { font-size: 12px; color: #86efac; }
.close-btn { background: none; border: none; color: white; font-size: 20px; cursor: pointer; padding: 4px; }

/* Tabs */
.widget-tabs {
  display: flex;
  background: #f1f5f9;
  padding: 4px;
}

.tab-btn {
  flex: 1;
  padding: 10px;
  border: none;
  background: transparent;
  cursor: pointer;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 500;
  color: #64748b;
  transition: all 0.2s;
}

.tab-btn.active {
  background: white;
  color: #0ea5e9;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Chat Tab */
.tab-content { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
.chat-tab { background: #f8fafc; }

.messages-container {
  flex: 1;
  overflow-y: auto;
  padding: 16px;
}

.message { display: flex; margin-bottom: 12px; }
.message.user { justify-content: flex-end; }

.message-bubble {
  max-width: 85%;
  padding: 10px 14px;
  border-radius: 16px;
  font-size: 14px;
  line-height: 1.5;
}

.message.bot .message-bubble {
  background: white;
  border-radius: 16px 16px 16px 4px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}

.message.user .message-bubble {
  background: linear-gradient(135deg, #0ea5e9, #0284c7);
  color: white;
  border-radius: 16px 16px 4px 16px;
}

.message-bubble p { margin: 0; }
.message-bubble a { color: #0ea5e9; }
.message.user .message-bubble a { color: #bae6fd; }
.message-time { font-size: 10px; opacity: 0.6; margin-top: 4px; display: block; }

/* Typing Indicator */
.typing { display: flex; gap: 4px; padding: 14px !important; }
.typing span { width: 8px; height: 8px; background: #94a3b8; border-radius: 50%; animation: typing 1.4s infinite; }
.typing span:nth-child(2) { animation-delay: 0.2s; }
.typing span:nth-child(3) { animation-delay: 0.4s; }
@keyframes typing { 0%, 60%, 100% { transform: translateY(0); } 30% { transform: translateY(-6px); } }

/* Quick Actions */
.quick-actions {
  padding: 10px;
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  background: white;
  border-top: 1px solid #e2e8f0;
}

.quick-actions button {
  padding: 8px 12px;
  background: #f1f5f9;
  border: none;
  border-radius: 18px;
  font-size: 12px;
  cursor: pointer;
  transition: all 0.2s;
}

.quick-actions button:hover { background: #0ea5e9; color: white; }

/* Admin Help */
.admin-help {
  padding: 12px;
  background: #fef3c7;
  border-top: 1px solid #fcd34d;
  text-align: center;
}

.admin-help p { margin: 0 0 8px; font-size: 13px; color: #92400e; }

.admin-btn {
  padding: 10px 16px;
  background: linear-gradient(135deg, #22c55e, #16a34a);
  color: white;
  border: none;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.admin-btn:hover { transform: scale(1.02); box-shadow: 0 4px 12px rgba(34,197,94,0.4); }

/* Chat Input */
.chat-input {
  display: flex;
  padding: 12px;
  gap: 8px;
  background: white;
  border-top: 1px solid #e2e8f0;
}

.chat-input input {
  flex: 1;
  padding: 12px 16px;
  border: 1px solid #e2e8f0;
  border-radius: 24px;
  font-size: 14px;
  outline: none;
}

.chat-input input:focus { border-color: #0ea5e9; }

.chat-input button {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: #0ea5e9;
  border: none;
  color: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.chat-input button:disabled { background: #cbd5e1; }
.chat-input button:not(:disabled):hover { background: #0284c7; }
.chat-input button svg { width: 20px; height: 20px; }

/* WhatsApp Tab */
.whatsapp-tab { padding: 20px; }

.wa-info { text-align: center; margin-bottom: 20px; }

.wa-icon { 
  width: 70px; 
  height: 70px; 
  background: linear-gradient(135deg, #0ea5e9, #0284c7);
  border-radius: 50%; 
  margin: 0 auto 12px; 
  display: flex; 
  align-items: center; 
  justify-content: center;
  box-shadow: 0 4px 15px rgba(14,165,233,0.3);
}

.wa-logo { width: 45px; height: 45px; object-fit: contain; }
.wa-info h4 { margin: 0 0 4px; font-size: 16px; color: #1e293b; }
.wa-info p { margin: 0; font-size: 13px; color: #64748b; }

.wa-options { display: flex; flex-direction: column; gap: 8px; }

.wa-btn {
  padding: 14px 16px;
  background: #f1f5f9;
  border: none;
  border-radius: 12px;
  font-size: 14px;
  text-align: left;
  cursor: pointer;
  transition: all 0.2s;
}

.wa-btn:hover { background: #0ea5e9; color: white; transform: translateX(4px); }

/* Transitions */
.widget-panel-enter-active, .widget-panel-leave-active { transition: all 0.3s ease; }
.widget-panel-enter-from, .widget-panel-leave-to { opacity: 0; transform: translateY(20px) scale(0.95); }

/* Mobile */
@media (max-width: 480px) {
  .widget-container { 
    width: calc(100vw - 32px); 
    right: -8px; 
    height: 70vh;
  }
  .hotline-label { right: -5px; }
}

/* Dark Mode */
@media (prefers-color-scheme: dark) {
  .hotline-label { background: #1e293b; }
  .hotline-arrow { background: #1e293b; }
  .widget-toggle { background: #1e293b; }
  .widget-container { background: #0f172a; }
  .widget-tabs { background: #1e293b; }
  .tab-btn { color: #94a3b8; }
  .tab-btn.active { background: #0f172a; color: #38bdf8; }
  .chat-tab { background: #0f172a; }
  .message.bot .message-bubble { background: #1e293b; color: #e2e8f0; }
  .quick-actions { background: #1e293b; border-color: #334155; }
  .quick-actions button { background: #334155; color: #e2e8f0; }
  .chat-input { background: #1e293b; border-color: #334155; }
  .chat-input input { background: #0f172a; border-color: #334155; color: #e2e8f0; }
  .whatsapp-tab { background: #0f172a; }
  .wa-info h4 { color: #e2e8f0; }
  .wa-btn { background: #1e293b; color: #e2e8f0; }
}
</style>
