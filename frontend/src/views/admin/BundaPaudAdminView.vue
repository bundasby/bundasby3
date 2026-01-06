<script setup>
import { ref, onMounted, computed } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'

// Tab state
const activeTab = ref('list')

// Data
const bundaPaudList = ref([])
const pokjaList = ref([])
const programList = ref([])
const laporanList = ref([])
const apresiasiList = ref([])

// Modals
const showModal = ref(false)
const modalType = ref('')
const editingItem = ref(null)

// Forms
const bundaForm = ref({ name: '', area: '', type: 'kecamatan', phone: '', photo_url: '', is_active: true })
const pokjaForm = ref({ nama: '', deskripsi: '', skUrl: '' })
const programForm = ref({ nama: '', deskripsi: '', tanggalMulai: '', tanggalSelesai: '', status: 'active' })
const laporanForm = ref({ judul: '', tanggal: '', deskripsi: '', fileUrl: '' })
const apresiasiForm = ref({ judul: '', tingkat: 'Kota', tahun: new Date().getFullYear(), deskripsi: '', fotoUrl: '' })

// Filters
const searchQuery = ref('')
const filterType = ref('')

// Load data
const loadData = () => {
    // List Bunda PAUD
    const savedList = localStorage.getItem('bp_bunda_paud_list')
    if (savedList) {
        bundaPaudList.value = JSON.parse(savedList)
    } else {
        bundaPaudList.value = [
            { id: 1, name: 'Ibu. Rini Indriyani', area: 'Tegalsari', type: 'kecamatan', phone: '081234567890', photo_url: '', is_active: true },
            { id: 2, name: 'Ibu. Siti Fatimah', area: 'Genteng', type: 'kecamatan', phone: '081234567891', photo_url: '', is_active: true },
            { id: 3, name: 'Ibu. Dewi Sartika', area: 'Kedungdoro', type: 'kelurahan', phone: '081234567892', photo_url: '', is_active: true },
        ]
        localStorage.setItem('bp_bunda_paud_list', JSON.stringify(bundaPaudList.value))
    }

    // Kelompok Kerja (Pokja)
    const savedPokja = localStorage.getItem('bp_bunda_paud_pokja')
    if (savedPokja) {
        pokjaList.value = JSON.parse(savedPokja)
    } else {
        pokjaList.value = [
            { id: 1, nama: 'Pokja Kemitraan', deskripsi: 'Bertanggung jawab menjalin kerjasama dengan mitra luar.', skUrl: '#' },
            { id: 2, nama: 'Pokja Pengembangan Kurikulum', deskripsi: 'Fokus pada peningkatan kualitas pembelajaran PAUD.', skUrl: '#' },
            { id: 3, nama: 'Pokja Kesehatan & Gizi', deskripsi: 'Memantau kesehatan dan gizi anak usia dini.', skUrl: '#' }
        ]
        localStorage.setItem('bp_bunda_paud_pokja', JSON.stringify(pokjaList.value))
    }

    // Program Kerja
    const savedProgram = localStorage.getItem('bp_bunda_paud_program')
    if (savedProgram) {
        programList.value = JSON.parse(savedProgram)
    } else {
        programList.value = [
            { id: 1, nama: 'Gebyar PAUD 2024', deskripsi: 'Lomba kreativitas anak dan guru PAUD se-Surabaya.', tanggalMulai: '2024-05-01', tanggalSelesai: '2024-05-05', status: 'active' },
            { id: 2, nama: 'Workshop Parenting', deskripsi: 'Edukasi orang tua tentang pencegahan stunting.', tanggalMulai: '2024-06-10', tanggalSelesai: '2024-06-10', status: 'active' }
        ]
        localStorage.setItem('bp_bunda_paud_program', JSON.stringify(programList.value))
    }

    // Laporan Kegiatan
    const savedLaporan = localStorage.getItem('bp_bunda_paud_laporan')
    if (savedLaporan) {
        laporanList.value = JSON.parse(savedLaporan)
    } else {
        laporanList.value = [
            { id: 1, judul: 'Laporan Triwulan I', tanggal: '2024-03-31', deskripsi: 'Laporan kegiatan rutin periode Januari-Maret.', fileUrl: '#' }
        ]
        localStorage.setItem('bp_bunda_paud_laporan', JSON.stringify(laporanList.value))
    }

    // Apresiasi
    const savedApresiasi = localStorage.getItem('bp_bunda_paud_apresiasi')
    if (savedApresiasi) {
        apresiasiList.value = JSON.parse(savedApresiasi)
    } else {
        apresiasiList.value = [
            { id: 1, judul: 'Bunda PAUD Teladan 2023', tingkat: 'Nasional', tahun: 2023, deskripsi: 'Penghargaan atas dedikasi dalam pengembangan PAUD HI.', fotoUrl: '' }
        ]
        localStorage.setItem('bp_bunda_paud_apresiasi', JSON.stringify(apresiasiList.value))
    }
}

// Persist data
const persist = (key, data) => localStorage.setItem(key, JSON.stringify(data))

// Computeds
const filteredList = computed(() => {
    return bundaPaudList.value.filter(item => {
        const matchSearch = !searchQuery.value || 
            item.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            item.area.toLowerCase().includes(searchQuery.value.toLowerCase())
        const matchType = !filterType.value || item.type === filterType.value
        return matchSearch && matchType
    })
})

const stats = computed(() => [
    { label: 'Total Bunda PAUD', value: bundaPaudList.value.length, icon: 'users', color: 'bg-purple-100 text-purple-600' },
    { label: 'Kelompok Kerja (Pokja)', value: pokjaList.value.length, icon: 'building', color: 'bg-blue-100 text-blue-600' },
    { label: 'Program Kerja', value: programList.value.length, icon: 'clipboard', color: 'bg-green-100 text-green-600' },
    { label: 'Laporan Kegiatan', value: laporanList.value.length, icon: 'file-text', color: 'bg-orange-100 text-orange-600' }
])

// CRUD Handlers
const openModal = (type, item = null) => {
    modalType.value = type
    editingItem.value = item
    showModal.value = true

    if (type === 'list') {
        bundaForm.value = item ? { ...item } : { name: '', area: '', type: 'kecamatan', phone: '', photo_url: '', is_active: true }
    } else if (type === 'pokja') {
        pokjaForm.value = item ? { ...item } : { nama: '', deskripsi: '', skUrl: '' }
    } else if (type === 'program') {
        programForm.value = item ? { ...item } : { nama: '', deskripsi: '', tanggalMulai: '', tanggalSelesai: '', status: 'active' }
    } else if (type === 'laporan') {
        laporanForm.value = item ? { ...item } : { judul: '', tanggal: '', deskripsi: '', fileUrl: '' }
    } else if (type === 'apresiasi') {
        apresiasiForm.value = item ? { ...item } : { judul: '', tingkat: 'Kota', tahun: new Date().getFullYear(), deskripsi: '', fotoUrl: '' }
    }
}

const saveItem = () => {
    if (modalType.value === 'list') {
        updateList(bundaPaudList, bundaForm.value, 'bp_bunda_paud_list')
    } else if (modalType.value === 'pokja') {
        updateList(pokjaList, pokjaForm.value, 'bp_bunda_paud_pokja')
    } else if (modalType.value === 'program') {
        updateList(programList, programForm.value, 'bp_bunda_paud_program')
    } else if (modalType.value === 'laporan') {
        updateList(laporanList, laporanForm.value, 'bp_bunda_paud_laporan')
    } else if (modalType.value === 'apresiasi') {
        updateList(apresiasiList, apresiasiForm.value, 'bp_bunda_paud_apresiasi')
    }
    showModal.value = false
}

const updateList = (listRef, formData, storageKey) => {
    if (editingItem.value) {
        const index = listRef.value.findIndex(i => i.id === editingItem.value.id)
        listRef.value[index] = { ...formData, id: editingItem.value.id }
    } else {
        listRef.value.push({ ...formData, id: Date.now() })
    }
    persist(storageKey, listRef.value)
}

const deleteItem = (type, item) => {
    if (!confirm('Yakin ingin menghapus data ini?')) return

    if (type === 'list') {
        bundaPaudList.value = bundaPaudList.value.filter(i => i.id !== item.id)
        persist('bp_bunda_paud_list', bundaPaudList.value)
    } else if (type === 'pokja') {
        pokjaList.value = pokjaList.value.filter(i => i.id !== item.id)
        persist('bp_bunda_paud_pokja', pokjaList.value)
    } else if (type === 'program') {
        programList.value = programList.value.filter(i => i.id !== item.id)
        persist('bp_bunda_paud_program', programList.value)
    } else if (type === 'laporan') {
        laporanList.value = laporanList.value.filter(i => i.id !== item.id)
        persist('bp_bunda_paud_laporan', laporanList.value)
    } else if (type === 'apresiasi') {
        apresiasiList.value = apresiasiList.value.filter(i => i.id !== item.id)
        persist('bp_bunda_paud_apresiasi', apresiasiList.value)
    }
}

const formatDate = (dateStr) => {
    if (!dateStr) return '-'
    return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })
}

onMounted(loadData)
</script>

<template>
    <AdminLayout title="Kelola Bunda PAUD">
        <!-- Dashboard Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div v-for="(stat, index) in stats" :key="index" class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex items-center">
                <div :class="['w-12 h-12 rounded-lg flex items-center justify-center mr-4', stat.color]">
                    <!-- Users Icon -->
                    <svg v-if="stat.icon === 'users'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <!-- Building Icon -->
                    <svg v-if="stat.icon === 'building'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    <!-- Clipboard Icon -->
                    <svg v-if="stat.icon === 'clipboard'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    <!-- File Text Icon -->
                    <svg v-if="stat.icon === 'file-text'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ stat.value }}</h3>
                    <p class="text-sm text-gray-500">{{ stat.label }}</p>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex space-x-1 p-2 overflow-x-auto">
                    <button @click="activeTab = 'list'" :class="['px-4 py-2.5 text-sm font-medium rounded-lg transition-colors whitespace-nowrap', activeTab === 'list' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100']">
                        👥 List Bunda PAUD
                    </button>
                    <button @click="activeTab = 'pokja'" :class="['px-4 py-2.5 text-sm font-medium rounded-lg transition-colors whitespace-nowrap', activeTab === 'pokja' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100']">
                        👥 Kelompok Kerja
                    </button>
                    <button @click="activeTab = 'program'" :class="['px-4 py-2.5 text-sm font-medium rounded-lg transition-colors whitespace-nowrap', activeTab === 'program' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100']">
                        📁 Program Kerja
                    </button>
                    <button @click="activeTab = 'laporan'" :class="['px-4 py-2.5 text-sm font-medium rounded-lg transition-colors whitespace-nowrap', activeTab === 'laporan' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100']">
                        📊 Laporan Kegiatan
                    </button>
                    <button @click="activeTab = 'apresiasi'" :class="['px-4 py-2.5 text-sm font-medium rounded-lg transition-colors whitespace-nowrap', activeTab === 'apresiasi' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100']">
                        🏆 Apresiasi
                    </button>
                </nav>
            </div>

            <div class="p-6">
                <!-- Tab: List Bunda PAUD -->
                <div v-if="activeTab === 'list'">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                        <div class="flex flex-col md:flex-row gap-4 flex-1">
                            <input v-model="searchQuery" type="text" placeholder="Cari nama atau wilayah..." class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
                            <select v-model="filterType" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">Semua Tipe</option>
                                <option value="kecamatan">Kecamatan</option>
                                <option value="kelurahan">Kelurahan</option>
                            </select>
                        </div>
                        <button @click="openModal('list')" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm font-medium whitespace-nowrap">
                            + Tambah Data
                        </button>
                    </div>
                
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Wilayah</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="item in filteredList" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-xs">
                                                {{ item.name.charAt(0) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white text-sm">{{ item.name }}</p>
                                                <p class="text-xs text-gray-500">{{ item.phone }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">{{ item.area }}</td>
                                    <td class="px-4 py-4">
                                        <span :class="['px-2 py-1 text-xs rounded-full', item.type === 'kecamatan' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700']">
                                            {{ item.type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span :class="['px-2 py-1 text-xs rounded-full cursor-pointer', item.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700']" @click="item.is_active = !item.is_active; persist('bp_bunda_paud_list', bundaPaudList)">
                                            {{ item.is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex gap-2">
                                            <button @click="openModal('list', item)" class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                                            <button @click="deleteItem('list', item)" class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab: Kelompok Kerja -->
                <div v-else-if="activeTab === 'pokja'">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Daftar Kelompok Kerja (Pokja)</h3>
                        <button @click="openModal('pokja')" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm font-medium">
                            + Tambah Pokja
                        </button>
                    </div>
                    
                    <div class="grid gap-4">
                        <div v-for="pokja in pokjaList" :key="pokja.id" class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">{{ pokja.nama }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ pokja.deskripsi }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <a v-if="pokja.skUrl" :href="pokja.skUrl" target="_blank" class="px-3 py-1.5 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200">Lihat SK</a>
                                <button @click="openModal('pokja', pokja)" class="text-gray-500 hover:text-blue-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
                                <button @click="deleteItem('pokja', pokja)" class="text-gray-500 hover:text-red-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                            </div>
                        </div>
                        <div v-if="pokjaList.length === 0" class="text-center py-12 text-gray-500">
                            Belum ada data Kelompok Kerja
                        </div>
                    </div>
                </div>

                <!-- Tab: Program Kerja -->
                <div v-else-if="activeTab === 'program'">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Program Kerja Bunda PAUD</h3>
                        <button @click="openModal('program')" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm font-medium">
                            + Tambah Program
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Program</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="program in programList" :key="program.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-4">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ program.nama }}</div>
                                        <div class="text-xs text-gray-500 mt-1">{{ program.deskripsi }}</div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ formatDate(program.tanggalMulai) }} - {{ formatDate(program.tanggalSelesai) }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <span :class="['px-2 py-1 text-xs rounded-full', program.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700']">
                                            {{ program.status === 'active' ? 'Aktif' : 'Selesai' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex gap-2">
                                            <button @click="openModal('program', program)" class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                                            <button @click="deleteItem('program', program)" class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab: Laporan Kegiatan -->
                <div v-else-if="activeTab === 'laporan'">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Laporan Kegiatan</h3>
                        <button @click="openModal('laporan')" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm font-medium">
                            + Tambah Laporan
                        </button>
                    </div>

                    <div class="grid gap-4">
                         <div v-for="laporan in laporanList" :key="laporan.id" class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">{{ laporan.judul }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ formatDate(laporan.tanggal) }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ laporan.deskripsi }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <a v-if="laporan.fileUrl" :href="laporan.fileUrl" target="_blank" class="px-3 py-1.5 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200">Lampiran</a>
                                <button @click="openModal('laporan', laporan)" class="text-gray-500 hover:text-blue-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
                                <button @click="deleteItem('laporan', laporan)" class="text-gray-500 hover:text-red-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                            </div>
                        </div>
                        <div v-if="laporanList.length === 0" class="text-center py-12 text-gray-500">
                            Belum ada laporan kegiatan
                        </div>
                    </div>
                </div>

                <!-- Tab: Apresiasi -->
                <div v-else-if="activeTab === 'apresiasi'">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Apresiasi Bunda PAUD</h3>
                        <button @click="openModal('apresiasi')" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm font-medium">
                            + Tambah Apresiasi
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="item in apresiasiList" :key="item.id" class="bg-gray-50 dark:bg-gray-800/50 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                             <div class="h-48 bg-gray-200 dark:bg-gray-700 relative">
                                <img v-if="item.fotoUrl" :src="item.fotoUrl" class="w-full h-full object-cover" />
                                <div v-else class="flex items-center justify-center h-full text-gray-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                                <span class="absolute top-2 right-2 bg-purple-600 text-white text-xs px-2 py-1 rounded-full">
                                    {{ item.tingkat }}
                                </span>
                            </div>
                            <div class="p-4">
                                <h4 class="font-bold text-gray-900 dark:text-white mb-1">{{ item.judul }}</h4>
                                <p class="text-sm text-gray-500 mb-2">{{ item.tahun }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ item.deskripsi }}</p>
                                <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <button @click="openModal('apresiasi', item)" class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                                     <button @click="deleteItem('apresiasi', item)" class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div v-if="apresiasiList.length === 0" class="text-center py-12 text-gray-500">
                        Belum ada data apresiasi
                    </div>
                </div>
            </div>
        </div>

        <!-- Dynamic Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/50" @click="showModal = false"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                    {{ editingItem ? 'Edit' : 'Tambah' }} 
                    {{ modalType === 'list' ? 'Bunda PAUD' : modalType === 'pokja' ? 'Kelompok Kerja' : modalType === 'program' ? 'Program Kerja' : modalType === 'laporan' ? 'Laporan' : 'Apresiasi' }}
                </h3>

                <!-- List Form -->
                <form v-if="modalType === 'list'" @submit.prevent="saveItem" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Nama</label>
                        <input v-model="bundaForm.name" type="text" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">Wilayah</label>
                            <input v-model="bundaForm.area" type="text" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">Tipe</label>
                            <select v-model="bundaForm.type" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="kecamatan">Kecamatan</option>
                                <option value="kelurahan">Kelurahan</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">No. HP</label>
                        <input v-model="bundaForm.phone" type="tel" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Simpan</button>
                    </div>
                </form>

                <!-- Pokja Form -->
                <form v-else-if="modalType === 'pokja'" @submit.prevent="saveItem" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Nama Pokja</label>
                        <input v-model="pokjaForm.nama" type="text" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                    </div>
                     <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Deskripsi</label>
                        <textarea v-model="pokjaForm.deskripsi" rows="3" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Link SK (Opsional)</label>
                        <input v-model="pokjaForm.skUrl" type="url" placeholder="https://..." class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Simpan</button>
                    </div>
                </form>

                 <!-- Program Form -->
                <form v-else-if="modalType === 'program'" @submit.prevent="saveItem" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Nama Program</label>
                        <input v-model="programForm.nama" type="text" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Deskripsi</label>
                        <textarea v-model="programForm.deskripsi" rows="3" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                         <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">Tgl Mulai</label>
                            <input v-model="programForm.tanggalMulai" type="date" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>
                         <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">Tgl Selesai</label>
                            <input v-model="programForm.tanggalSelesai" type="date" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Status</label>
                         <select v-model="programForm.status" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="active">Aktif</option>
                            <option value="completed">Selesai</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Simpan</button>
                    </div>
                </form>

                <!-- Laporan Form -->
                <form v-else-if="modalType === 'laporan'" @submit.prevent="saveItem" class="space-y-4">
                     <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Judul Laporan</label>
                        <input v-model="laporanForm.judul" type="text" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                    </div>
                     <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Tanggal</label>
                        <input v-model="laporanForm.tanggal" type="date" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                    </div>
                     <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Deskripsi Singkat</label>
                        <textarea v-model="laporanForm.deskripsi" rows="3" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>
                     <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Link Lampiran</label>
                        <input v-model="laporanForm.fileUrl" type="url" placeholder="https://..." class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Simpan</button>
                    </div>
                </form>

                <!-- Apresiasi Form -->
                <form v-else-if="modalType === 'apresiasi'" @submit.prevent="saveItem" class="space-y-4">
                     <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Judul Apresiasi</label>
                        <input v-model="apresiasiForm.judul" type="text" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                             <label class="block text-sm font-medium mb-1 dark:text-gray-300">Tingkat</label>
                             <select v-model="apresiasiForm.tingkat" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="Kota">Kota</option>
                                <option value="Provinsi">Provinsi</option>
                                <option value="Nasional">Nasional</option>
                                <option value="Internasional">Internasional</option>
                            </select>
                        </div>
                         <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-300">Tahun</label>
                            <input v-model="apresiasiForm.tahun" type="number" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>
                    </div>
                     <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Deskripsi</label>
                        <textarea v-model="apresiasiForm.deskripsi" rows="3" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>
                     <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Link Foto (URL)</label>
                        <input v-model="apresiasiForm.fotoUrl" type="url" placeholder="https://..." class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
