// Bunda PAUD Role Menu Configuration
export const bundaMenus = [
  {
    title: 'Dashboard',
    icon: '📊',
    path: '/admin/dashboard'
  },
  {
    title: 'User',
    icon: '👥',
    path: '/admin/users'
  },
  {
    title: 'Berkas',
    icon: '📁',
    path: '/admin/berkas'
  },
  {
    title: 'Kelompok Kerja',
    icon: '👥',
    path: '/admin/kelompok-kerja'
  },
  {
    title: 'Program Kerja',
    icon: '📋',
    path: '/admin/program-kerja'
  },
  {
    title: 'Kegiatan',
    icon: '📝',
    path: '/admin/kegiatan'
  },
  {
    title: 'Monitoring Ijop',
    icon: '🔍',
    path: '/admin/monitoring-ijop'
  },
  {
    title: 'Kegiatan Pendukung',
    icon: '🎯',
    path: '/admin/kegiatan-pendukung',
    children: [
      { title: 'Pelatihan', path: '/admin/kegiatan-pendukung/pelatihan' },
      { title: 'Konsultasi PAUD HI', path: '/admin/kegiatan-pendukung/konsultasi' },
      { title: 'Permohonan Fasilitasi', path: '/admin/kegiatan-pendukung/fasilitasi' },
      { title: 'Edu City Tour', path: '/admin/kegiatan-pendukung/edu-city-tour' }
    ]
  },
  {
    title: 'Rekap Satuan PAUD',
    icon: '🏫',
    path: '/admin/rekap-satuan-kec',
    subtitle: 'Kecamatan',
    children: [
      { title: 'Rekap Satuan', path: '/admin/rekap-satuan-kec/satuan' },
      { title: 'Rekap Besar', path: '/admin/rekap-satuan-kec/besar' }
    ]
  },
  {
    title: 'Monitoring',
    icon: '📡',
    path: '/admin/monitoring'
  },
  {
    title: 'PAUD HI',
    icon: '🎓',
    path: '/admin/paud-hi'
  },
  {
    title: 'Sekolah',
    icon: '🏫',
    path: '/admin/sekolah'
  },
  {
    title: 'Vaksinasi',
    icon: '💉',
    path: '/admin/vaksinasi'
  },
  {
    title: 'Materi',
    icon: '📚',
    path: '/admin/materi'
  },
  {
    title: 'Lomba Apresiasi',
    icon: '🏆',
    path: '/admin/lomba-apresiasi',
    subtitle: 'Bunda Kelurahan/Kecamatan'
  },
  {
    title: 'Survey Transisi',
    icon: '📝',
    path: '/admin/survey-transisi',
    subtitle: 'PAUD ke SD'
  },
  {
    title: 'Monitor Transisi',
    icon: '🔄',
    path: '/admin/monitor-transisi',
    subtitle: 'PAUD ke SD'
  },
  {
    title: 'Monitoring MPLS',
    icon: '👀',
    path: '/admin/monitoring-mpls',
    children: [
      { title: 'PAUD', path: '/admin/monitoring-mpls/paud' },
      { title: 'SD', path: '/admin/monitoring-mpls/sd' }
    ]
  },
  {
    title: 'Monitoring Anak',
    icon: '👶',
    path: '/admin/monitoring-anak',
    subtitle: 'Anak Belum Sekolah'
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
    title: 'G7KAIH',
    icon: '🎯',
    path: '/admin/g7kaih'
  },
  {
    title: 'Imunisasi',
    icon: '💊',
    path: '/admin/imunisasi'
  }
]

export default bundaMenus
