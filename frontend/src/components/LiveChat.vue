<template>
  <div class="live-chat-widget">
    <!-- Chat Toggle Button -->
    <button 
      @click="toggleChat" 
      class="chat-toggle"
      :class="{ 'has-unread': unreadCount > 0 }"
    >
      <span v-if="!isOpen" class="chat-icon">💬</span>
      <span v-else class="close-icon">✕</span>
      <span v-if="unreadCount > 0" class="unread-badge">{{ unreadCount }}</span>
    </button>

    <!-- Chat Window -->
    <transition name="chat-window">
      <div v-if="isOpen" class="chat-container">
        <!-- Header -->
        <div class="chat-header">
          <div class="header-info">
            <div class="avatar">
              <img src="/logo-bunda-paud.png" alt="Bunda PAUD" />
              <span class="status-indicator" :class="{ online: isOnline }"></span>
            </div>
            <div class="header-text">
              <h4>Bunda PAUD Support</h4>
              <span class="status-text">{{ isOnline ? 'Online' : 'Offline' }}</span>
            </div>
          </div>
          <button @click="toggleChat" class="close-btn">✕</button>
        </div>

        <!-- Messages -->
        <div class="chat-messages" ref="messagesContainer">
          <template v-for="(message, index) in messages" :key="index">
            <!-- Date Separator -->
            <div v-if="showDateSeparator(message, index)" class="date-separator">
              {{ formatDate(message.timestamp) }}
            </div>

            <!-- Message -->
            <div class="message" :class="message.sender">
              <div class="message-content">
                <p>{{ message.text }}</p>
                <span class="message-time">{{ formatTime(message.timestamp) }}</span>
              </div>
            </div>
          </template>

          <!-- Typing Indicator -->
          <div v-if="isTyping" class="message bot">
            <div class="message-content typing">
              <span></span>
              <span></span>
              <span></span>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div v-if="showQuickActions" class="quick-actions">
          <button 
            v-for="action in quickActions" 
            :key="action.id"
            @click="selectQuickAction(action)"
            class="quick-action-btn"
          >
            {{ action.icon }} {{ action.label }}
          </button>
        </div>

        <!-- Input -->
        <div class="chat-input">
          <input 
            v-model="inputMessage"
            @keyup.enter="sendMessage"
            @focus="showQuickActions = false"
            type="text"
            placeholder="Ketik pesan..."
            :disabled="!isOnline"
          />
          <button 
            @click="sendMessage" 
            :disabled="!inputMessage.trim() || !isOnline"
            class="send-btn"
          >
            <svg viewBox="0 0 24 24" fill="currentColor">
              <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
            </svg>
          </button>
        </div>

        <!-- Powered By -->
        <div class="chat-footer">
          <span>Powered by Bunda PAUD Kota Surabaya</span>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, nextTick, onMounted, watch } from 'vue'

// State
const isOpen = ref(false)
const isOnline = ref(true)
const isTyping = ref(false)
const inputMessage = ref('')
const unreadCount = ref(0)
const showQuickActions = ref(true)
const messagesContainer = ref(null)

const messages = ref([
  {
    id: 1,
    sender: 'bot',
    text: 'Halo! 👋 Selamat datang di layanan Live Chat Bunda PAUD Kota Surabaya. Ada yang bisa kami bantu?',
    timestamp: new Date()
  }
])

const quickActions = [
  { id: 1, icon: '📝', label: 'Pengaduan', response: 'Untuk pengaduan, silakan kunjungi halaman /pengaduan atau sampaikan keluhan Anda di sini.' },
  { id: 2, icon: '📋', label: 'Konsultasi PAUD', response: 'Untuk konsultasi PAUD HI, silakan isi form di halaman /layanan/konsultasi atau hubungi kami di (031) 5xxx-xxxx.' },
  { id: 3, icon: '🏛️', label: 'Peminjaman Gedung', response: 'Untuk peminjaman Grha Bunda PAUD, silakan ajukan permohonan di halaman /layanan/peminjaman-gedung.' },
  { id: 4, icon: '❓', label: 'FAQ', response: 'Anda dapat melihat pertanyaan yang sering diajukan di halaman /faq.' }
]

// Methods
function toggleChat() {
  isOpen.value = !isOpen.value
  if (isOpen.value) {
    unreadCount.value = 0
    showQuickActions.value = messages.value.length <= 1
    nextTick(() => scrollToBottom())
  }
}

function sendMessage() {
  if (!inputMessage.value.trim()) return

  const userMessage = {
    id: Date.now(),
    sender: 'user',
    text: inputMessage.value,
    timestamp: new Date()
  }

  messages.value.push(userMessage)
  inputMessage.value = ''
  showQuickActions.value = false

  // Simulate bot response
  simulateBotResponse(userMessage.text)
  scrollToBottom()
}

function selectQuickAction(action) {
  const userMessage = {
    id: Date.now(),
    sender: 'user',
    text: action.label,
    timestamp: new Date()
  }

  messages.value.push(userMessage)
  showQuickActions.value = false

  // Bot response
  setTimeout(() => {
    messages.value.push({
      id: Date.now(),
      sender: 'bot',
      text: action.response,
      timestamp: new Date()
    })
    scrollToBottom()
  }, 500)
}

function simulateBotResponse(userText) {
  isTyping.value = true
  scrollToBottom()

  setTimeout(() => {
    isTyping.value = false

    let response = 'Terima kasih atas pertanyaan Anda. Tim kami akan segera merespons. Untuk informasi lebih lanjut, silakan hubungi (031) 5xxx-xxxx atau email ke info@bundapaud.surabaya.go.id'

    // Simple keyword matching
    const text = userText.toLowerCase()
    if (text.includes('pengaduan') || text.includes('keluhan') || text.includes('lapor')) {
      response = 'Untuk mengajukan pengaduan, silakan:\n1. Kunjungi halaman /pengaduan\n2. Isi form dengan lengkap\n3. Tim kami akan merespons dalam 1x24 jam.\n\nAtau sampaikan detail keluhan Anda di sini.'
    } else if (text.includes('konsultasi') || text.includes('paud')) {
      response = 'Layanan konsultasi PAUD HI tersedia setiap hari kerja pukul 08:00-16:00 WIB. Silakan:\n1. Isi form di /layanan/konsultasi\n2. Atau hubungi langsung (031) 5xxx-xxxx'
    } else if (text.includes('gedung') || text.includes('peminjaman') || text.includes('grha')) {
      response = 'Untuk peminjaman Grha Bunda PAUD:\n1. Isi form di /layanan/peminjaman-gedung\n2. Sertakan surat permohonan resmi\n3. Konfirmasi akan diberikan dalam 3 hari kerja'
    } else if (text.includes('jam') || text.includes('buka') || text.includes('operasional')) {
      response = 'Jam operasional Bunda PAUD:\n📅 Senin - Jumat: 08:00 - 16:00 WIB\n📅 Sabtu - Minggu: Libur\n📍 Jl. Contoh No. 123, Surabaya'
    } else if (text.includes('terima kasih') || text.includes('makasih')) {
      response = 'Sama-sama! 😊 Jika ada pertanyaan lain, jangan ragu untuk bertanya. Kami siap membantu!'
    }

    messages.value.push({
      id: Date.now(),
      sender: 'bot',
      text: response,
      timestamp: new Date()
    })

    if (!isOpen.value) {
      unreadCount.value++
    }

    scrollToBottom()
  }, 1500)
}

function scrollToBottom() {
  nextTick(() => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
    }
  })
}

function showDateSeparator(message, index) {
  if (index === 0) return true
  const prevDate = new Date(messages.value[index - 1].timestamp).toDateString()
  const currDate = new Date(message.timestamp).toDateString()
  return prevDate !== currDate
}

function formatDate(date) {
  const d = new Date(date)
  const today = new Date()
  const yesterday = new Date(today)
  yesterday.setDate(yesterday.getDate() - 1)

  if (d.toDateString() === today.toDateString()) return 'Hari ini'
  if (d.toDateString() === yesterday.toDateString()) return 'Kemarin'
  return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })
}

function formatTime(date) {
  return new Date(date).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
}

// Check online status
onMounted(() => {
  // Simulate online/offline status
  setInterval(() => {
    isOnline.value = Math.random() > 0.1 // 90% online
  }, 30000)
})
</script>

<style scoped>
.live-chat-widget {
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: 9999;
  font-family: 'Segoe UI', system-ui, sans-serif;
}

/* Toggle Button */
.chat-toggle {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: linear-gradient(135deg, #0ea5e9, #0284c7);
  border: none;
  color: white;
  font-size: 24px;
  cursor: pointer;
  box-shadow: 0 4px 20px rgba(14, 165, 233, 0.4);
  transition: all 0.3s ease;
  position: relative;
}

.chat-toggle:hover {
  transform: scale(1.1);
  box-shadow: 0 6px 30px rgba(14, 165, 233, 0.5);
}

.chat-toggle.has-unread {
  animation: pulse 2s infinite;
}

.unread-badge {
  position: absolute;
  top: -5px;
  right: -5px;
  width: 22px;
  height: 22px;
  background: #ef4444;
  border-radius: 50%;
  font-size: 12px;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
}

@keyframes pulse {
  0%, 100% { box-shadow: 0 4px 20px rgba(14, 165, 233, 0.4); }
  50% { box-shadow: 0 4px 30px rgba(14, 165, 233, 0.7); }
}

/* Chat Container */
.chat-container {
  position: absolute;
  bottom: 70px;
  right: 0;
  width: 380px;
  height: 520px;
  background: white;
  border-radius: 16px;
  box-shadow: 0 10px 50px rgba(0, 0, 0, 0.15);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

/* Header */
.chat-header {
  background: linear-gradient(135deg, #0ea5e9, #0284c7);
  color: white;
  padding: 16px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.header-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.avatar {
  position: relative;
  width: 44px;
  height: 44px;
}

.avatar img {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  object-fit: cover;
  background: white;
  padding: 4px;
}

.status-indicator {
  position: absolute;
  bottom: 2px;
  right: 2px;
  width: 12px;
  height: 12px;
  background: #94a3b8;
  border-radius: 50%;
  border: 2px solid white;
}

.status-indicator.online {
  background: #22c55e;
}

.header-text h4 {
  margin: 0;
  font-size: 16px;
  font-weight: 600;
}

.status-text {
  font-size: 12px;
  opacity: 0.9;
}

.close-btn {
  background: rgba(255, 255, 255, 0.2);
  border: none;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  color: white;
  font-size: 16px;
  cursor: pointer;
  transition: background 0.2s;
}

.close-btn:hover {
  background: rgba(255, 255, 255, 0.3);
}

/* Messages */
.chat-messages {
  flex: 1;
  overflow-y: auto;
  padding: 16px;
  background: #f8fafc;
}

.date-separator {
  text-align: center;
  font-size: 12px;
  color: #64748b;
  margin: 16px 0;
}

.message {
  display: flex;
  margin-bottom: 12px;
}

.message.user {
  justify-content: flex-end;
}

.message-content {
  max-width: 80%;
  padding: 12px 16px;
  border-radius: 16px;
  position: relative;
}

.message.bot .message-content {
  background: white;
  border-radius: 16px 16px 16px 4px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.message.user .message-content {
  background: linear-gradient(135deg, #0ea5e9, #0284c7);
  color: white;
  border-radius: 16px 16px 4px 16px;
}

.message-content p {
  margin: 0;
  font-size: 14px;
  line-height: 1.5;
  white-space: pre-line;
}

.message-time {
  font-size: 10px;
  opacity: 0.7;
  margin-top: 4px;
  display: block;
}

/* Typing Indicator */
.typing {
  display: flex;
  gap: 4px;
  padding: 16px !important;
}

.typing span {
  width: 8px;
  height: 8px;
  background: #94a3b8;
  border-radius: 50%;
  animation: typing 1.4s infinite;
}

.typing span:nth-child(2) { animation-delay: 0.2s; }
.typing span:nth-child(3) { animation-delay: 0.4s; }

@keyframes typing {
  0%, 60%, 100% { transform: translateY(0); }
  30% { transform: translateY(-6px); }
}

/* Quick Actions */
.quick-actions {
  padding: 12px 16px;
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  background: white;
  border-top: 1px solid #e2e8f0;
}

.quick-action-btn {
  padding: 8px 12px;
  background: #f1f5f9;
  border: none;
  border-radius: 20px;
  font-size: 13px;
  color: #475569;
  cursor: pointer;
  transition: all 0.2s;
}

.quick-action-btn:hover {
  background: #0ea5e9;
  color: white;
}

/* Input */
.chat-input {
  display: flex;
  padding: 12px 16px;
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
  transition: border-color 0.2s;
}

.chat-input input:focus {
  border-color: #0ea5e9;
}

.send-btn {
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

.send-btn:hover:not(:disabled) {
  background: #0284c7;
}

.send-btn:disabled {
  background: #cbd5e1;
  cursor: not-allowed;
}

.send-btn svg {
  width: 20px;
  height: 20px;
}

/* Footer */
.chat-footer {
  padding: 8px;
  text-align: center;
  font-size: 10px;
  color: #94a3b8;
  background: #f8fafc;
}

/* Animation */
.chat-window-enter-active,
.chat-window-leave-active {
  transition: all 0.3s ease;
}

.chat-window-enter-from,
.chat-window-leave-to {
  opacity: 0;
  transform: translateY(20px) scale(0.95);
}

/* Responsive */
@media (max-width: 480px) {
  .chat-container {
    width: calc(100vw - 32px);
    height: 70vh;
    right: -8px;
  }
}
</style>
