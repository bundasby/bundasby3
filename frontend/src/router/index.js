import { createRouter, createWebHistory } from "vue-router";

const routes = [
  {
    path: "/",
    name: "home",
    component: () => import("../views/HomeView.vue"),
    meta: { title: "Beranda" },
  },
  {
    path: "/profil",
    name: "profil",
    component: () => import("../views/ProfilView.vue"),
    meta: { title: "Profil" },
    children: [
      {
        path: "visi-misi",
        name: "visi-misi",
        component: () => import("../views/profil/VisiMisiView.vue"),
        meta: { title: "Visi, Misi, Motto" },
      },
      {
        path: "tentang",
        name: "tentang",
        component: () => import("../views/profil/TentangView.vue"),
        meta: { title: "Sambutan Bunda PAUD" },
      },
      {
        path: "logo",
        name: "logo",
        component: () => import("../views/profil/LogoView.vue"),
        meta: { title: "Arti Logo Bunda PAUD" },
      },
      {
        path: "tugas-fungsi",
        name: "tugas-fungsi",
        component: () => import("../views/profil/TugasFungsiView.vue"),
        meta: { title: "Tugas dan Fungsi" },
      },
      {
        path: "struktur-organisasi",
        name: "struktur-organisasi",
        component: () => import("../views/profil/StrukturView.vue"),
        meta: { title: "Struktur Organisasi" },
      },
      {
        path: "penghargaan",
        name: "penghargaan",
        component: () => import("../views/profil/PenghargaanView.vue"),
        meta: { title: "Penghargaan" },
      },
    ],
  },
  {
    path: "/program",
    name: "program",
    component: () => import("../views/ProgramView.vue"),
    meta: { title: "Program Kerja" },
  },
  {
    path: "/dokumen",
    name: "dokumen",
    component: () => import("../views/DokumenView.vue"),
    meta: { title: "Dokumen" },
  },
  {
    path: "/layanan",
    name: "layanan",
    component: () => import("../views/LayananView.vue"),
    meta: { title: "Layanan" },
  },
  {
    path: "/layanan/konsultasi-paud-hi",
    name: "konsultasi-paud-hi",
    component: () => import("../views/layanan/KonsultasiPaudHiView.vue"),
    meta: { title: "Konsultasi PAUD HI" },
  },
  {
    path: "/layanan/peminjaman-gedung",
    name: "peminjaman-gedung",
    component: () => import("../views/layanan/PeminjamanGedungView.vue"),
    meta: { title: "Peminjaman Gedung Grha Bunda PAUD" },
  },
  {
    path: "/layanan/paud-bung-karno",
    name: "paud-bung-karno",
    component: () => import("../views/layanan/PaudBungKarnoView.vue"),
    meta: { title: "PAUD Negeri Bung Karno" },
  },
  {
    path: "/layanan/program-prioritas",
    name: "program-prioritas",
    component: () => import("../views/layanan/ProgramPrioritasView.vue"),
    meta: { title: "Program Prioritas" },
  },
  {
    path: "/berita",
    name: "berita",
    component: () => import("../views/BeritaView.vue"),
    meta: { title: "Berita & Pengumuman" },
  },
  {
    path: "/berita/:slug",
    name: "berita-detail",
    component: () => import("../views/BeritaDetailView.vue"),
    meta: { title: "Detail Berita" },
  },
  {
    path: "/galeri",
    name: "galeri",
    component: () => import("../views/GaleriView.vue"),
    meta: { title: "Galeri" },
  },
  {
    path: "/galeri-bunda/infografis",
    name: "infografis",
    component: () => import("../views/galeri-bunda/InfografisView.vue"),
    meta: { title: "Infografis" },
  },
  {
    path: "/galeri-bunda/majalah-buku",
    name: "majalah-buku",
    component: () => import("../views/galeri-bunda/MajalahBukuView.vue"),
    meta: { title: "Majalah & Buku" },
  },
  {
    path: "/galeri-bunda/materi",
    name: "materi",
    component: () => import("../views/galeri-bunda/MateriView.vue"),
    meta: { title: "Materi Pembelajaran" },
  },
  {
    path: "/galeri-bunda/permainan",
    name: "permainan",
    component: () => import("../views/galeri-bunda/PermainanView.vue"),
    meta: { title: "Permainan Edukatif" },
  },
  {
    path: "/galeri-bunda/bunda-tv",
    name: "bunda-tv",
    component: () => import("../views/galeri-bunda/BundaTvView.vue"),
    meta: { title: "Bunda TV" },
  },
  {
    path: "/data/ringkasan",
    name: "ringkasan-data",
    component: () => import("../views/data/RingkasanDataView.vue"),
    meta: { title: "Ringkasan Data PAUD" },
  },
  {
    path: "/data/lembaga-paud",
    name: "lembaga-paud",
    component: () => import("../views/data/LembagaPaudView.vue"),
    meta: { title: "Daftar Lembaga PAUD" },
  },
  {
    path: "/data/akreditasi-paud",
    name: "akreditasi-paud",
    component: () => import("../views/data/AkreditasiPaudView.vue"),
    meta: { title: "Daftar Akreditasi PAUD" },
  },
  {
    path: "/data/profiling-paud",
    name: "profiling-paud",
    component: () => import("../views/data/ProfilingPaudView.vue"),
    meta: { title: "Profiling PAUD" },
  },
  {
    path: "/data/gerakan-7-kaih",
    name: "gerakan-7-kaih",
    component: () => import("../views/data/Gerakan7KaihView.vue"),
    meta: { title: "Implementasi Gerakan 7 KAIH" },
  },
  {
    path: "/data/digitalisasi-paud",
    name: "digitalisasi-paud",
    component: () => import("../views/data/DigitalisasiPaudView.vue"),
    meta: { title: "Pelaporan Digitalisasi PAUD" },
  },
  {
    path: "/data/rapor-pendidikan",
    name: "rapor-pendidikan",
    component: () => import("../views/data/RaporPendidikanView.vue"),
    meta: { title: "Rapor Pendidikan PAUD" },
  },
  {
    path: "/data/apk-aks-ats",
    name: "apk-aks-ats",
    component: () => import("../views/data/ApkAksAtsView.vue"),
    meta: { title: "APK, AKS, ATS PAUD" },
  },
  {
    path: "/data/revitalisasi-paud",
    name: "revitalisasi-paud",
    component: () => import("../views/data/RevitalisasiPaudView.vue"),
    meta: { title: "Revitalisasi PAUD" },
  },
  {
    path: "/data/peraturan",
    name: "peraturan",
    component: () => import("../views/data/PeraturanView.vue"),
    meta: { title: "Peraturan" },
  },
  {
    path: "/data/npk",
    name: "npk",
    component: () => import("../views/data/NpkView.vue"),
    meta: { title: "Norma, Prosedur, Kriteria (NPK)" },
  },
  {
    path: "/data/laporan",
    name: "laporan",
    component: () => import("../views/data/LaporanView.vue"),
    meta: { title: "Laporan" },
  },
  {
    path: "/data/paud",
    name: "data-paud",
    component: () => import("../views/data/DataPaudView.vue"),
    meta: { title: "Data PAUD" },
  },
  {
    path: "/data/ijin-operasional",
    name: "ijin-operasional",
    component: () => import("../views/data/IjinOperasionalView.vue"),
    meta: { title: "Ijin Operasional" },
  },
  {
    path: "/data/daftar-sekolah",
    name: "daftar-sekolah",
    component: () => import("../views/data/DaftarSekolahPaudView.vue"),
    meta: { title: "Daftar Sekolah PAUD" },
  },
  {
    path: "/data/sulingjar",
    name: "sulingjar",
    component: () => import("../views/data/SulingjarView.vue"),
    meta: { title: "Survei Lingkungan Belajar (Sulingjar)" },
  },
  {
    path: "/data/ikd",
    name: "ikd",
    component: () => import("../views/data/IkdView.vue"),
    meta: { title: "Indeks Kualitas Dapodik (IKD)" },
  },
  {
    path: "/data/paud-hi",
    name: "paud-hi",
    component: () => import("../views/data/PaudHiView.vue"),
    meta: { title: "PAUD Holistik Integratif" },
  },
  {
    path: "/kontak",
    name: "kontak",
    component: () => import("../views/KontakView.vue"),
    meta: { title: "Kontak Kami" },
  },
  {
    path: "/bunda-paud",
    name: "bunda-paud",
    component: () => import("../views/BundaPaudView.vue"),
    meta: { title: "Bunda PAUD Kecamatan & Kelurahan" },
  },
  {
    path: "/mitra-paud",
    name: "mitra-paud",
    component: () => import("../views/mitra-paud/MitraPaudView.vue"),
    meta: { title: "Mitra PAUD" },
  },
  {
    path: "/mitra-paud/pendaftaran",
    name: "pendaftaran-mitra",
    redirect: { name: "mitra-paud", query: { tab: "pendaftaran" } },
    meta: { title: "Pendaftaran Mitra PAUD" },
  },
  {
    path: "/mitra-paud/gugus-tugas",
    name: "gugus-tugas",
    component: () => import("../views/mitra-paud/GugasTugasView.vue"),
    meta: { title: "Gugus Tugas PAUD HI" },
  },
  {
    path: "/pengaduan",
    name: "pengaduan",
    component: () => import("../views/PengaduanView.vue"),
    meta: { title: "Pengaduan Masyarakat" },
  },
  {
    path: "/faq",
    name: "faq",
    component: () => import("../views/FaqView.vue"),
    meta: { title: "FAQ - Pertanyaan yang Sering Diajukan" },
  },
  {
    path: "/cari",
    name: "search",
    component: () => import("../views/SearchView.vue"),
    meta: { title: "Pencarian" },
  },
  // Admin Routes

  {
    path: "/admin/login",
    name: "admin-login",
    component: () => import("../views/admin/LoginView.vue"),
    meta: { title: "Admin Login", hideNavbar: true, hideFooter: true },
  },
  {
    path: "/admin/dashboard",
    name: "admin-dashboard",
    component: () => import("../views/admin/DashboardView.vue"),
    meta: {
      title: "Dashboard Admin",
      hideNavbar: true,
      hideFooter: true,
      requiresAuth: true,
    },
  },
  {
    path: "/admin/users",
    name: "admin-users",
    component: () => import("../views/admin/UserManagementView.vue"),
    meta: {
      title: "Manajemen Pengguna",
      hideNavbar: true,
      hideFooter: true,
      requiresAuth: true,
      roles: ["super_admin"],
    },
  },
  {
    path: "/admin/sliders",
    name: "admin-sliders",
    component: () => import("../views/admin/SliderView.vue"),
    meta: {
      title: "Kelola Slider",
      hideNavbar: true,
      hideFooter: true,
      requiresAuth: true,
    },
  },
  {
    path: "/admin/profiles",
    name: "admin-profiles",
    component: () => import("../views/admin/ProfileView.vue"),
    meta: {
      title: "Kelola Profil",
      hideNavbar: true,
      hideFooter: true,
      requiresAuth: true,
    },
  },
  {
    path: "/admin/publikasi",
    name: "admin-publikasi",
    component: () => import("../views/admin/PublikasiView.vue"),
    meta: {
      title: "Publikasi & Informasi",
      hideNavbar: true,
      hideFooter: true,
      requiresAuth: true,
    },
  },
  {
    path: "/admin/articles",
    name: "admin-articles",
    component: () => import("../views/admin/ArticlesView.vue"),
    meta: {
      title: "Kelola Berita",
      hideNavbar: true,
      hideFooter: true,
      requiresAuth: true,
    },
  },
  {
    path: "/admin/articles/:id",
    name: "admin-article-form",
    component: () => import("../views/admin/ArticleFormView.vue"),
    meta: {
      title: "Form Berita",
      hideNavbar: true,
      hideFooter: true,
      requiresAuth: true,
    },
  },
  {
    path: "/admin/categories",
    name: "admin-categories",
    component: () => import("../views/admin/CategoriesView.vue"),
    meta: {
      title: "Kelola Jenis Berita",
      hideNavbar: true,
      hideFooter: true,
      requiresAuth: true,
    },
  },
  {
    path: "/admin/galleries",
    name: "admin-galleries",
    component: () => import("../views/admin/GalleriesView.vue"),
    meta: {
      title: "Kelola Galeri",
      hideNavbar: true,
      hideFooter: true,
      requiresAuth: true,
    },
  },
  {
    path: "/admin/documents",
    name: "admin-documents",
    component: () => import("../views/admin/DocumentsView.vue"),
    meta: {
      title: "Kelola Dokumen",
      hideNavbar: true,
      hideFooter: true,
      requiresAuth: true,
    },
  },
  {
    path: "/admin/peminjaman",
    name: "admin-peminjaman",
    component: () => import("../views/admin/PeminjamanAdminView.vue"),
    meta: {
      title: "Kelola Peminjaman Gedung",
      hideNavbar: true,
      hideFooter: true,
      requiresAuth: true,
    },
  },
  {
    path: "/admin/konsultasi",
    name: "admin-konsultasi",
    component: () => import("../views/admin/KonsultasiAdminView.vue"),
    meta: {
      title: "Kelola Konsultasi PAUD",
      hideNavbar: true,
      hideFooter: true,
      requiresAuth: true,
    },
  },
  {
    path: "/admin/complaints",
    name: "admin-complaints",
    component: () => import("../views/admin/ComplaintsView.vue"),
    meta: {
      title: "Kelola Pengaduan",
      hideNavbar: true,
      hideFooter: true,
      requiresAuth: true,
    },
  },
  {
    path: "/admin/bunda-paud",
    name: "admin-bunda-paud",
    component: () => import("../views/admin/BundaPaudAdminView.vue"),
    meta: {
      title: "Kelola Bunda PAUD",
      hideNavbar: true,
      hideFooter: true,
      requiresAuth: true,
    },
  },
  {
    path: "/admin/mitra-paud",
    name: "admin-mitra-paud",
    component: () => import("../views/admin/MitraPaudAdminView.vue"),
    meta: {
      title: "Kelola Mitra PAUD",
      hideNavbar: true,
      hideFooter: true,
      requiresAuth: true,
    },
  },
  {
    path: "/admin/gugus-tugas",
    name: "admin-gugus-tugas",
    component: () => import("../views/admin/GugasTugasAdminView.vue"),
    meta: {
      title: "Kelola Gugus Tugas",
      hideNavbar: true,
      hideFooter: true,
      requiresAuth: true,
    },
  },
  {
    path: "/admin/data-paud",
    name: "admin-data-paud",
    component: () => import("../views/admin/DataPaudAdminView.vue"),
    meta: {
      title: "Data PAUD",
      hideNavbar: true,
      hideFooter: true,
      requiresAuth: true,
    },
  },
  {
    path: "/admin/settings",
    name: "admin-settings",
    component: () => import("../views/admin/SettingsView.vue"),
    meta: {
      title: "Pengaturan",
      hideNavbar: true,
      hideFooter: true,
      requiresAuth: true,
    },
  },
  {
    path: "/admin/analytics",
    name: "admin-analytics",
    component: () => import("../views/admin/RealtimeAnalyticsView.vue"),
    meta: {
      title: "Analitik Real-time",
      hideNavbar: true,
      hideFooter: true,
      requiresAuth: true,
    },
  },
  // New Phase 2 Routes
  {
    path: "/admin/timeline-program",
    name: "admin-timeline-program",
    component: () => import("../views/admin/TimelineProgramView.vue"),
    meta: { title: "Timeline Program", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/kegiatan",
    name: "admin-kegiatan",
    component: () => import("../views/admin/KegiatanAdminView.vue"),
    meta: { title: "Kelola Kegiatan", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/rekap",
    name: "admin-rekap",
    component: () => import("../views/admin/RekapView.vue"),
    meta: { title: "Rekap Data", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/rekap/:tab",
    name: "admin-rekap-tab",
    component: () => import("../views/admin/RekapView.vue"),
    meta: { title: "Rekap Data", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/diagram",
    name: "admin-diagram",
    component: () => import("../views/admin/DiagramView.vue"),
    meta: { title: "Diagram Data", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/diagram/:type",
    name: "admin-diagram-type",
    component: () => import("../views/admin/DiagramView.vue"),
    meta: { title: "Diagram Data", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/monitor-transisi",
    name: "admin-monitor-transisi",
    component: () => import("../views/admin/MonitorTransisiView.vue"),
    meta: { title: "Monitor Transisi PAUD ke SD", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/lomba-apresiasi",
    name: "admin-lomba-apresiasi",
    component: () => import("../views/admin/LombaApresiasiView.vue"),
    meta: { title: "Lomba Apresiasi Bunda PAUD", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  // Phase 3: Bunda PAUD Routes
  {
    path: "/admin/berkas",
    name: "admin-berkas",
    component: () => import("../views/admin/BerkasView.vue"),
    meta: { title: "Berkas", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/kelompok-kerja",
    name: "admin-kelompok-kerja",
    component: () => import("../views/admin/KelompokKerjaView.vue"),
    meta: { title: "Kelompok Kerja", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/program-kerja",
    name: "admin-program-kerja",
    component: () => import("../views/admin/ProgramKerjaBundaView.vue"),
    meta: { title: "Program Kerja", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/sekolah",
    name: "admin-sekolah",
    component: () => import("../views/admin/SekolahView.vue"),
    meta: { title: "Data Sekolah PAUD", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/vaksinasi",
    name: "admin-vaksinasi",
    component: () => import("../views/admin/VaksinasiView.vue"),
    meta: { title: "Data Vaksinasi", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/monitoring-anak",
    name: "admin-monitoring-anak",
    component: () => import("../views/admin/MonitorAnakView.vue"),
    meta: { title: "Monitoring Anak Belum Sekolah", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  // Missing routes from bundaMenus - Using PlaceholderView for pages under development
  {
    path: "/admin/monitoring-ijop",
    name: "admin-monitoring-ijop",
    component: () => import("../views/admin/PlaceholderView.vue"),
    meta: { title: "Monitoring Ijin Operasional", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/paud-hi",
    name: "admin-paud-hi",
    component: () => import("../views/admin/PlaceholderView.vue"),
    meta: { title: "PAUD Holistik Integratif", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/survey-transisi",
    name: "admin-survey-transisi",
    component: () => import("../views/admin/PlaceholderView.vue"),
    meta: { title: "Survey Transisi PAUD ke SD", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/monitoring-mpls",
    name: "admin-monitoring-mpls",
    component: () => import("../views/admin/PlaceholderView.vue"),
    meta: { title: "Monitoring MPLS", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/monitoring-mpls/paud",
    name: "admin-monitoring-mpls-paud",
    component: () => import("../views/admin/PlaceholderView.vue"),
    meta: { title: "Monitoring MPLS PAUD", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/monitoring-mpls/sd",
    name: "admin-monitoring-mpls-sd",
    component: () => import("../views/admin/PlaceholderView.vue"),
    meta: { title: "Monitoring MPLS SD", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/g7kaih",
    name: "admin-g7kaih",
    component: () => import("../views/admin/PlaceholderView.vue"),
    meta: { title: "Gerakan 7 KAIH", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/imunisasi",
    name: "admin-imunisasi",
    component: () => import("../views/admin/PlaceholderView.vue"),
    meta: { title: "Data Imunisasi", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/materi",
    name: "admin-materi",
    component: () => import("../views/admin/PlaceholderView.vue"),
    meta: { title: "Materi Pembelajaran", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/monitoring",
    name: "admin-monitoring",
    component: () => import("../views/admin/PlaceholderView.vue"),
    meta: { title: "Monitoring", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/kegiatan-pendukung",
    name: "admin-kegiatan-pendukung",
    component: () => import("../views/admin/PlaceholderView.vue"),
    meta: { title: "Kegiatan Pendukung", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/kegiatan-pendukung/:type",
    name: "admin-kegiatan-pendukung-type",
    component: () => import("../views/admin/PlaceholderView.vue"),
    meta: { title: "Kegiatan Pendukung", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/rekap-satuan-kec",
    name: "admin-rekap-satuan-kec",
    component: () => import("../views/admin/PlaceholderView.vue"),
    meta: { title: "Rekap Satuan Kecamatan", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/rekap-satuan-kec/:type",
    name: "admin-rekap-satuan-kec-type",
    component: () => import("../views/admin/PlaceholderView.vue"),
    meta: { title: "Rekap Satuan Kecamatan", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  {
    path: "/admin/diagram/:type",
    name: "admin-diagram-type",
    component: () => import("../views/admin/PlaceholderView.vue"),
    meta: { title: "Diagram Data", hideNavbar: true, hideFooter: true, requiresAuth: true },
  },
  // Phase 4: Juri Role Routes
  {
    path: "/admin/juri/dashboard",
    name: "juri-dashboard",
    component: () => import("../views/admin/juri/JuriDashboardView.vue"),
    meta: { title: "Dashboard Juri", hideNavbar: true, hideFooter: true, requiresAuth: true, roles: ["juri_apresiasi", "juri", "super_admin", "admin"] },
  },
  {
    path: "/admin/juri/penilaian",
    name: "juri-penilaian",
    component: () => import("../views/admin/juri/PenilaianJuriView.vue"),
    meta: { title: "Penilaian Juri", hideNavbar: true, hideFooter: true, requiresAuth: true, roles: ["juri_apresiasi", "juri", "super_admin", "admin"] },
  },
  // Phase 5: Dinas Role Routes
  {
    path: "/admin/dinas/pelatihan",
    name: "dinas-pelatihan",
    component: () => import("../views/admin/dinas/PelatihanDinasView.vue"),
    meta: { title: "Pelatihan PAUD", hideNavbar: true, hideFooter: true, requiresAuth: true, roles: ["dinas", "super_admin", "admin"] },
  },
  {
    path: "/admin/dinas/edu-city-tour",
    name: "dinas-edu-city-tour",
    component: () => import("../views/admin/dinas/EduCityTourView.vue"),
    meta: { title: "Edu City Tour", hideNavbar: true, hideFooter: true, requiresAuth: true, roles: ["dinas", "super_admin", "admin"] },
  },
  {
    path: "/admin/dinas/fasilitasi",
    name: "dinas-fasilitasi",
    component: () => import("../views/admin/dinas/FasilitasiView.vue"),
    meta: { title: "Fasilitasi Program", hideNavbar: true, hideFooter: true, requiresAuth: true, roles: ["dinas", "super_admin", "admin"] },
  },
  {
    path: "/admin/dinas/vaksinasi",
    name: "dinas-vaksinasi",
    component: () => import("../views/admin/dinas/DataVaksinasiView.vue"),
    meta: { title: "Data Vaksinasi", hideNavbar: true, hideFooter: true, requiresAuth: true, roles: ["dinas", "super_admin", "admin"] },
  },
  // Phase 6: Mitra PAUD Role Routes
  {
    path: "/admin/mitra/anggota",
    name: "mitra-anggota",
    component: () => import("../views/admin/mitra/DaftarAnggotaView.vue"),
    meta: { title: "Daftar Anggota Mitra", hideNavbar: true, hideFooter: true, requiresAuth: true, roles: ["mitra_paud", "mitra", "forum_komunikasi", "kelompok_kerja", "gugus_tugas", "super_admin", "admin"] },
  },
  {
    path: "/admin/mitra/kegiatan",
    name: "mitra-kegiatan",
    component: () => import("../views/admin/mitra/KegiatanMitraView.vue"),
    meta: { title: "Kegiatan Mitra", hideNavbar: true, hideFooter: true, requiresAuth: true, roles: ["mitra_paud", "mitra", "forum_komunikasi", "kelompok_kerja", "gugus_tugas", "super_admin", "admin"] },
  },
  {
    path: "/admin/mitra/laporan",
    name: "mitra-laporan",
    component: () => import("../views/admin/mitra/LaporanMitraView.vue"),
    meta: { title: "Laporan Mitra", hideNavbar: true, hideFooter: true, requiresAuth: true, roles: ["mitra_paud", "mitra", "forum_komunikasi", "kelompok_kerja", "gugus_tugas", "super_admin", "admin"] },
  },
  {
    path: "/:pathMatch(.*)*",
    name: "not-found",
    component: () => import("../views/NotFoundView.vue"),
    meta: { title: "404 - Tidak Ditemukan" },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    } else {
      return { top: 0 };
    }
  },
});

// Import auth service for route guards
import { authService } from '@/services';

// Update document title and check authentication
router.beforeEach((to, from, next) => {
  document.title = `${to.meta.title} | Bunda PAUD Kota Surabaya`;
  
  // Check if route requires authentication
  if (to.meta.requiresAuth) {
    if (!authService.isLoggedIn()) {
      return next('/admin/login');
    }
    
    // Check role-based access
    if (to.meta.roles && to.meta.roles.length > 0) {
      const userRole = authService.getRoleCode();
      if (!to.meta.roles.includes(userRole)) {
        // Redirect to dashboard if user doesn't have required role
        return next('/admin/dashboard');
      }
    }
  }
  
  // If user is logged in and trying to access login page, redirect to dashboard
  if (to.path === '/admin/login' && authService.isLoggedIn()) {
    return next('/admin/dashboard');
  }
  
  next();
});

export default router;

