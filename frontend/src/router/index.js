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

// Update document title
router.beforeEach((to, from, next) => {
  document.title = `${to.meta.title} | Bunda PAUD Kota Surabaya`;
  next();
});

export default router;
