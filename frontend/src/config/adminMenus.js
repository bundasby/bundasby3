// Admin Role Menu Configuration
export const adminMenus = [
  {
    title: 'Dashboard',
    icon: '📊',
    path: '/admin/dashboard',
    badge: null
  },
  {
    title: 'User',
    icon: '👥',
    path: '/admin/users'
  },
  {
    title: 'Timeline Program',
    icon: '📅',
    path: '/admin/timeline-program'
  },
  {
    title: 'Kegiatan',
    icon: '📝',
    path: '/admin/kegiatan'
  },
  {
    title: 'Penanggung Jawab',
    icon: '👔',
    path: '/admin/penanggung-jawab',
    subtitle: 'Daftar Penanggung Jawab Bunda PAUD'
  },
  {
    title: 'Bunda PAUD',
    icon: '👩‍👧',
    path: '/admin/bunda-paud',
    subtitle: 'Daftar Semua Bunda PAUD'
  },
  {
    title: 'Hasil Penilaian',
    icon: '⭐',
    path: '/admin/hasil-penilaian',
    subtitle: 'Hasil Penilaian Aplikasi'
  },
  {
    title: 'Rekap',
    icon: '📋',
    path: '/admin/rekap',
    children: [
      { title: 'User', path: '/admin/rekap/user' },
      { title: 'Profil', path: '/admin/rekap/profil' },
      { title: 'Program Kerja', path: '/admin/rekap/program-kerja' },
      { title: 'Kegiatan', path: '/admin/rekap/kegiatan' }
    ]
  },
  {
    title: 'Rekap Satuan PAUD',
    icon: '🏫',
    path: '/admin/rekap-satuan',
    children: [
      { title: 'Rekap Satuan', path: '/admin/rekap-satuan/satuan' },
      { title: 'Rekap Besar', path: '/admin/rekap-satuan/besar' }
    ]
  },
  {
    title: 'Kegiatan Pendukung',
    icon: '🎯',
    path: '/admin/kegiatan-pendukung',
    children: [
      { title: 'Pelatihan', path: '/admin/kegiatan-pendukung/pelatihan' },
      { title: 'Konsultasi PAUD HI', path: '/admin/kegiatan-pendukung/konsultasi' },
      { title: 'Permohonan Fasilitasi', path: '/admin/kegiatan-pendukung/fasilitasi' },
      { title: 'Edu City Tour', path: '/admin/kegiatan-pendukung/edu-city-tour' },
      { title: 'Rekap Permohonan Bus', path: '/admin/kegiatan-pendukung/rekap-bus' }
    ]
  },
  {
    title: 'Data PAUD',
    icon: '📚',
    path: '/admin/data-paud',
    children: [
      { title: 'Tempat Wisata', path: '/admin/data-paud/wisata' },
      { title: 'Pelatihan', path: '/admin/data-paud/pelatihan' }
    ]
  },
  {
    title: 'Lomba Apresiasi',
    icon: '🏆',
    path: '/admin/lomba-apresiasi',
    subtitle: 'Bunda Kelurahan/Kecamatan'
  },
  {
    title: 'Talenta Seni',
    icon: '🎨',
    path: '/admin/talenta-seni'
  },
  {
    title: 'Jadwal',
    icon: '🗓️',
    path: '/admin/jadwal'
  },
  {
    title: 'Monitor Transisi',
    icon: '🔄',
    path: '/admin/monitor-transisi',
    subtitle: 'Transisi PAUD ke SD'
  },
  {
    title: 'Diagram',
    icon: '📈',
    path: '/admin/diagram',
    children: [
      { title: 'Anak 5-6 Tahun', path: '/admin/diagram/anak' },
      { title: 'Guru', path: '/admin/diagram/guru' },
      { title: 'Lembaga', path: '/admin/diagram/lembaga' }
    ]
  },
  {
    title: 'Monitor MPLS',
    icon: '👀',
    path: '/admin/monitor-mpls'
  },
  // Existing menus
  {
    title: 'Berita',
    icon: '📰',
    path: '/admin/articles'
  },
  {
    title: 'Galeri',
    icon: '🖼️',
    path: '/admin/galleries'
  },
  {
    title: 'Dokumen',
    icon: '📄',
    path: '/admin/documents'
  },
  {
    title: 'Pengaduan',
    icon: '📬',
    path: '/admin/complaints'
  },
  {
    title: 'Pengaturan',
    icon: '⚙️',
    path: '/admin/settings'
  }
]

export default adminMenus
