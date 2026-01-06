<script setup>
import { ref, onMounted, computed } from "vue";
import { useRouter } from "vue-router";
import AdminLayout from "@/components/layout/AdminLayout.vue";
import {
  articlesStorage,
  galleriesStorage,
  documentsStorage,
  infografisStorage,
  materiStorage,
  majalahStorage,
  bundaTvStorage,
  peraturanStorage,
  npkStorage,
  laporanStorage,
  initializeData,
} from "@/services/localStorage";

const router = useRouter();
const loading = ref(true);
const activeTab = ref("berita");

// ========== BERITA DATA ==========
const articles = ref([]);
const searchQuery = ref("");

const loadArticles = () => {
  initializeData();
  const allArticles = articlesStorage.getAll();
  articles.value = allArticles.map((article) => ({
    id: article.id,
    title: article.title,
    category: article.category,
    is_published: article.isPublished,
    published_at: article.publishedAt,
    author: article.author || "Admin",
  }));
};

const filteredArticles = computed(() => {
  if (!searchQuery.value) return articles.value;
  return articles.value.filter((a) =>
    a.title.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

const getCategoryLabel = (cat) => {
  const labels = {
    kegiatan: "Kegiatan",
    edukasi: "Edukasi",
    pengumuman: "Pengumuman",
    berita: "Berita",
  };
  return labels[cat] || cat;
};

const getCategoryColor = (cat) => {
  const colors = {
    kegiatan: "bg-blue-100 text-blue-700",
    edukasi: "bg-green-100 text-green-700",
    pengumuman: "bg-amber-100 text-amber-700",
    berita: "bg-teal-100 text-teal-700",
  };
  return colors[cat] || "bg-gray-100 text-gray-700";
};

const formatDate = (date) => {
  if (!date) return "-";
  return new Date(date).toLocaleDateString("id-ID", {
    day: "numeric",
    month: "short",
    year: "numeric",
  });
};

const deleteArticle = (id) => {
  if (confirm("Yakin ingin menghapus artikel ini?")) {
    articlesStorage.delete(id);
    loadArticles();
  }
};

const togglePublish = (article) => {
  const newStatus = !article.is_published;
  articlesStorage.update(article.id, {
    isPublished: newStatus,
    publishedAt: newStatus
      ? new Date().toISOString().split("T")[0]
      : article.published_at,
  });
  loadArticles();
};

// ========== GALERI DATA ==========
const galleries = ref([]);
const showGalleryModal = ref(false);
const editingGallery = ref(null);
const galleryForm = ref({ title: "", category: "", image_url: "" });

const loadGalleries = () => {
  initializeData();
  galleries.value = galleriesStorage.getAll();
};

const toggleGalleryActive = (gallery) => {
  galleriesStorage.update(gallery.id, { isActive: !gallery.isActive });
  loadGalleries();
};

const deleteGallery = (id) => {
  if (confirm("Yakin ingin menghapus gambar ini?")) {
    galleriesStorage.delete(id);
    loadGalleries();
  }
};

const openGalleryModal = (gallery = null) => {
  editingGallery.value = gallery;
  galleryForm.value = gallery
    ? {
        title: gallery.title,
        category: gallery.category,
        image_url: gallery.imageUrl,
      }
    : { title: "", category: "", image_url: "" };
  showGalleryModal.value = true;
};

const convertGoogleDriveUrl = (url) => {
  const match = url.match(/\/d\/([a-zA-Z0-9_-]+)/);
  if (match) {
    return `https://drive.google.com/uc?export=view&id=${match[1]}`;
  }
  return url;
};

const saveGallery = () => {
  const imageUrl = convertGoogleDriveUrl(galleryForm.value.image_url);
  if (editingGallery.value) {
    galleriesStorage.update(editingGallery.value.id, {
      title: galleryForm.value.title,
      category: galleryForm.value.category,
      imageUrl: imageUrl,
    });
  } else {
    galleriesStorage.create({
      title: galleryForm.value.title,
      category: galleryForm.value.category,
      imageUrl: imageUrl,
      isActive: true,
    });
  }
  loadGalleries();
  showGalleryModal.value = false;
};

// ========== DOKUMEN DATA ==========
const documents = ref([]);
const showDocModal = ref(false);
const editingDoc = ref(null);
const docForm = ref({
  title: "",
  category: "regulasi",
  file_url: "",
  file_type: "PDF",
});

const loadDocuments = () => {
  initializeData();
  documents.value = documentsStorage.getAll();
};

const formatSize = (bytes) => {
  if (!bytes) return "-";
  if (bytes < 1024) return bytes + " B";
  if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + " KB";
  return (bytes / (1024 * 1024)).toFixed(1) + " MB";
};

const getDocCategoryLabel = (cat) => {
  const labels = {
    kegiatan: "Kegiatan",
    regulasi: "Regulasi",
    anggaran: "Anggaran",
    monitoring: "Monitoring",
  };
  return labels[cat] || cat;
};

const getFileIcon = (type) => {
  if (type === "PDF") return "text-red-500";
  if (type === "XLSX") return "text-green-600";
  if (type === "DOCX") return "text-blue-600";
  return "text-gray-500";
};

const toggleDocActive = (doc) => {
  documentsStorage.update(doc.id, { isActive: !doc.isActive });
  loadDocuments();
};

const deleteDocument = (id) => {
  if (confirm("Yakin ingin menghapus dokumen ini?")) {
    documentsStorage.delete(id);
    loadDocuments();
  }
};

const openDocModal = (doc = null) => {
  editingDoc.value = doc;
  docForm.value = doc
    ? {
        title: doc.title,
        category: doc.category,
        file_url: doc.fileUrl || "",
        file_type: doc.fileType,
      }
    : { title: "", category: "regulasi", file_url: "", file_type: "PDF" };
  showDocModal.value = true;
};

const saveDocument = () => {
  const fileUrl = convertGoogleDriveUrl(docForm.value.file_url);
  if (editingDoc.value) {
    documentsStorage.update(editingDoc.value.id, {
      title: docForm.value.title,
      category: docForm.value.category,
      fileType: docForm.value.file_type,
      fileUrl: fileUrl,
    });
  } else {
    documentsStorage.create({
      title: docForm.value.title,
      category: docForm.value.category,
      fileType: docForm.value.file_type,
      fileUrl: fileUrl,
      fileSize: 0,
      isActive: true,
    });
  }
  loadDocuments();
  showDocModal.value = false;
};

// ========== INFOGRAFIS DATA ==========
const infografis = ref([]);
const showInfoModal = ref(false);
const editingInfo = ref(null);
const infoForm = ref({ title: "", description: "", image_url: "", category: "" });

const loadInfografis = () => {
  initializeData();
  infografis.value = infografisStorage.getAll();
};

const toggleInfoActive = (item) => {
  infografisStorage.update(item.id, { isActive: !item.isActive });
  loadInfografis();
};

const deleteInfoItem = (id) => {
  if (confirm("Yakin ingin menghapus infografis ini?")) {
    infografisStorage.delete(id);
    loadInfografis();
  }
};

const openInfoModal = (item = null) => {
  editingInfo.value = item;
  infoForm.value = item
    ? { title: item.title, description: item.description, image_url: item.imageUrl, category: item.category }
    : { title: "", description: "", image_url: "", category: "" };
  showInfoModal.value = true;
};

const saveInfografis = () => {
  const imageUrl = convertGoogleDriveUrl(infoForm.value.image_url);
  if (editingInfo.value) {
    infografisStorage.update(editingInfo.value.id, {
      title: infoForm.value.title,
      description: infoForm.value.description,
      imageUrl: imageUrl,
      category: infoForm.value.category,
    });
  } else {
    infografisStorage.create({
      title: infoForm.value.title,
      description: infoForm.value.description,
      imageUrl: imageUrl,
      category: infoForm.value.category,
      isActive: true,
    });
  }
  loadInfografis();
  showInfoModal.value = false;
};

// ========== MATERI DATA ==========
const materi = ref([]);
const showMateriModal = ref(false);
const editingMateri = ref(null);
const materiForm = ref({ title: "", description: "", file_url: "", file_type: "PDF", category: "" });

const loadMateri = () => {
  initializeData();
  materi.value = materiStorage.getAll();
};

const toggleMateriActive = (item) => {
  materiStorage.update(item.id, { isActive: !item.isActive });
  loadMateri();
};

const deleteMateriItem = (id) => {
  if (confirm("Yakin ingin menghapus materi ini?")) {
    materiStorage.delete(id);
    loadMateri();
  }
};

const openMateriModal = (item = null) => {
  editingMateri.value = item;
  materiForm.value = item
    ? { title: item.title, description: item.description, file_url: item.fileUrl || "", file_type: item.fileType, category: item.category }
    : { title: "", description: "", file_url: "", file_type: "PDF", category: "" };
  showMateriModal.value = true;
};

const saveMateri = () => {
  const fileUrl = convertGoogleDriveUrl(materiForm.value.file_url);
  if (editingMateri.value) {
    materiStorage.update(editingMateri.value.id, {
      title: materiForm.value.title,
      description: materiForm.value.description,
      fileUrl: fileUrl,
      fileType: materiForm.value.file_type,
      category: materiForm.value.category,
    });
  } else {
    materiStorage.create({
      title: materiForm.value.title,
      description: materiForm.value.description,
      fileUrl: fileUrl,
      fileType: materiForm.value.file_type,
      category: materiForm.value.category,
      isActive: true,
    });
  }
  loadMateri();
  showMateriModal.value = false;
};

// ========== MAJALAH/BUKU DATA ==========
const majalahList = ref([]);
const showMajalahModal = ref(false);
const editingMajalah = ref(null);
const majalahForm = ref({ title: "", description: "", cover_url: "", file_url: "", category: "majalah" });

const loadMajalah = () => {
  initializeData();
  majalahList.value = majalahStorage.getAll();
};

const toggleMajalahActive = (item) => {
  majalahStorage.update(item.id, { isActive: !item.isActive });
  loadMajalah();
};

const deleteMajalahItem = (id) => {
  if (confirm("Yakin ingin menghapus item ini?")) {
    majalahStorage.delete(id);
    loadMajalah();
  }
};

const openMajalahModal = (item = null) => {
  editingMajalah.value = item;
  majalahForm.value = item
    ? { title: item.title, description: item.description, cover_url: item.coverUrl, file_url: item.fileUrl, category: item.category }
    : { title: "", description: "", cover_url: "", file_url: "", category: "majalah" };
  showMajalahModal.value = true;
};

const saveMajalah = () => {
  const coverUrl = convertGoogleDriveUrl(majalahForm.value.cover_url);
  if (editingMajalah.value) {
    majalahStorage.update(editingMajalah.value.id, {
      title: majalahForm.value.title,
      description: majalahForm.value.description,
      coverUrl: coverUrl,
      fileUrl: majalahForm.value.file_url,
      category: majalahForm.value.category,
    });
  } else {
    majalahStorage.create({
      title: majalahForm.value.title,
      description: majalahForm.value.description,
      coverUrl: coverUrl,
      fileUrl: majalahForm.value.file_url,
      category: majalahForm.value.category,
      isActive: true,
    });
  }
  loadMajalah();
  showMajalahModal.value = false;
};

// ========== BUNDA TV DATA ==========
const bundaTvList = ref([]);
const showBundaTvModal = ref(false);
const editingBundaTv = ref(null);
const bundaTvForm = ref({ title: "", description: "", thumbnail_url: "", video_url: "", category: "" });

const loadBundaTv = () => {
  initializeData();
  bundaTvList.value = bundaTvStorage.getAll();
};

const toggleBundaTvActive = (item) => {
  bundaTvStorage.update(item.id, { isActive: !item.isActive });
  loadBundaTv();
};

const deleteBundaTvItem = (id) => {
  if (confirm("Yakin ingin menghapus video ini?")) {
    bundaTvStorage.delete(id);
    loadBundaTv();
  }
};

const openBundaTvModal = (item = null) => {
  editingBundaTv.value = item;
  bundaTvForm.value = item
    ? { title: item.title, description: item.description, thumbnail_url: item.thumbnailUrl, video_url: item.videoUrl, category: item.category }
    : { title: "", description: "", thumbnail_url: "", video_url: "", category: "" };
  showBundaTvModal.value = true;
};

const saveBundaTv = () => {
  if (editingBundaTv.value) {
    bundaTvStorage.update(editingBundaTv.value.id, {
      title: bundaTvForm.value.title,
      description: bundaTvForm.value.description,
      thumbnailUrl: bundaTvForm.value.thumbnail_url,
      videoUrl: bundaTvForm.value.video_url,
      category: bundaTvForm.value.category,
    });
  } else {
    bundaTvStorage.create({
      title: bundaTvForm.value.title,
      description: bundaTvForm.value.description,
      thumbnailUrl: bundaTvForm.value.thumbnail_url,
      videoUrl: bundaTvForm.value.video_url,
      category: bundaTvForm.value.category,
      isActive: true,
    });
  }
  loadBundaTv();
  showBundaTvModal.value = false;
};

// ========== PERATURAN DATA ==========
const peraturanList = ref([]);
const showPeraturanModal = ref(false);
const editingPeraturan = ref(null);
const peraturanForm = ref({ title: "", description: "", file_url: "", category: "perwal" });

const loadPeraturan = () => { initializeData(); peraturanList.value = peraturanStorage.getAll(); };
const togglePeraturanActive = (item) => { peraturanStorage.update(item.id, { isActive: !item.isActive }); loadPeraturan(); };
const deletePeraturanItem = (id) => { if (confirm("Yakin ingin menghapus?")) { peraturanStorage.delete(id); loadPeraturan(); } };
const openPeraturanModal = (item = null) => {
  editingPeraturan.value = item;
  peraturanForm.value = item ? { title: item.title, description: item.description, file_url: item.fileUrl, category: item.category } : { title: "", description: "", file_url: "", category: "perwal" };
  showPeraturanModal.value = true;
};
const savePeraturan = () => {
  if (editingPeraturan.value) { peraturanStorage.update(editingPeraturan.value.id, { title: peraturanForm.value.title, description: peraturanForm.value.description, fileUrl: peraturanForm.value.file_url, category: peraturanForm.value.category }); }
  else { peraturanStorage.create({ title: peraturanForm.value.title, description: peraturanForm.value.description, fileUrl: peraturanForm.value.file_url, category: peraturanForm.value.category, isActive: true }); }
  loadPeraturan(); showPeraturanModal.value = false;
};

// ========== NPK DATA ==========
const npkList = ref([]);
const showNpkModal = ref(false);
const editingNpk = ref(null);
const npkForm = ref({ title: "", description: "", file_url: "", category: "npk" });

const loadNpk = () => { initializeData(); npkList.value = npkStorage.getAll(); };
const toggleNpkActive = (item) => { npkStorage.update(item.id, { isActive: !item.isActive }); loadNpk(); };
const deleteNpkItem = (id) => { if (confirm("Yakin ingin menghapus?")) { npkStorage.delete(id); loadNpk(); } };
const openNpkModal = (item = null) => {
  editingNpk.value = item;
  npkForm.value = item ? { title: item.title, description: item.description, file_url: item.fileUrl, category: item.category } : { title: "", description: "", file_url: "", category: "npk" };
  showNpkModal.value = true;
};
const saveNpk = () => {
  if (editingNpk.value) { npkStorage.update(editingNpk.value.id, { title: npkForm.value.title, description: npkForm.value.description, fileUrl: npkForm.value.file_url, category: npkForm.value.category }); }
  else { npkStorage.create({ title: npkForm.value.title, description: npkForm.value.description, fileUrl: npkForm.value.file_url, category: npkForm.value.category, isActive: true }); }
  loadNpk(); showNpkModal.value = false;
};

// ========== LAPORAN DATA ==========
const laporanList = ref([]);
const showLaporanModal = ref(false);
const editingLaporan = ref(null);
const laporanForm = ref({ title: "", description: "", file_url: "", category: "tahunan" });

const loadLaporan = () => { initializeData(); laporanList.value = laporanStorage.getAll(); };
const toggleLaporanActive = (item) => { laporanStorage.update(item.id, { isActive: !item.isActive }); loadLaporan(); };
const deleteLaporanItem = (id) => { if (confirm("Yakin ingin menghapus?")) { laporanStorage.delete(id); loadLaporan(); } };
const openLaporanModal = (item = null) => {
  editingLaporan.value = item;
  laporanForm.value = item ? { title: item.title, description: item.description, file_url: item.fileUrl, category: item.category } : { title: "", description: "", file_url: "", category: "tahunan" };
  showLaporanModal.value = true;
};
const saveLaporan = () => {
  if (editingLaporan.value) { laporanStorage.update(editingLaporan.value.id, { title: laporanForm.value.title, description: laporanForm.value.description, fileUrl: laporanForm.value.file_url, category: laporanForm.value.category }); }
  else { laporanStorage.create({ title: laporanForm.value.title, description: laporanForm.value.description, fileUrl: laporanForm.value.file_url, category: laporanForm.value.category, isActive: true }); }
  loadLaporan(); showLaporanModal.value = false;
};

// ========== LIFECYCLE ==========
onMounted(() => {
  loadArticles();
  loadGalleries();
  loadDocuments();
  loadInfografis();
  loadMateri();
  loadMajalah();
  loadBundaTv();
  loadPeraturan();
  loadNpk();
  loadLaporan();
  setTimeout(() => (loading.value = false), 300);
});
</script>

<template>
  <AdminLayout title="Publikasi & Informasi">
    <!-- Tabs -->
    <div class="flex gap-2 mb-6 flex-wrap">
      <button
        @click="activeTab = 'berita'"
        class="px-6 py-3 rounded-lg font-medium transition-all flex items-center gap-2"
        :class="
          activeTab === 'berita'
            ? 'bg-primary-600 text-white shadow-lg'
            : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'
        "
      >
        <svg
          class="w-5 h-5"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
          />
        </svg>
        Berita
      </button>
      <button
        @click="activeTab = 'galeri'"
        class="px-6 py-3 rounded-lg font-medium transition-all flex items-center gap-2"
        :class="
          activeTab === 'galeri'
            ? 'bg-primary-600 text-white shadow-lg'
            : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'
        "
      >
        <svg
          class="w-5 h-5"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
          />
        </svg>
        Galeri
      </button>
      <button
        @click="activeTab = 'dokumen'"
        class="px-6 py-3 rounded-lg font-medium transition-all flex items-center gap-2"
        :class="
          activeTab === 'dokumen'
            ? 'bg-primary-600 text-white shadow-lg'
            : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'
        "
      >
        <svg
          class="w-5 h-5"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"
          />
        </svg>
        Dokumen
      </button>
      <button
        @click="activeTab = 'infografis'"
        class="px-6 py-3 rounded-lg font-medium transition-all flex items-center gap-2"
        :class="
          activeTab === 'infografis'
            ? 'bg-primary-600 text-white shadow-lg'
            : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'
        "
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
        Infografis
      </button>
      <button
        @click="activeTab = 'materi'"
        class="px-6 py-3 rounded-lg font-medium transition-all flex items-center gap-2"
        :class="
          activeTab === 'materi'
            ? 'bg-primary-600 text-white shadow-lg'
            : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'
        "
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        Materi
      </button>
      <button
        @click="activeTab = 'majalah'"
        class="px-6 py-3 rounded-lg font-medium transition-all flex items-center gap-2"
        :class="activeTab === 'majalah' ? 'bg-primary-600 text-white shadow-lg' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
        Majalah/Buku
      </button>
      <button
        @click="activeTab = 'bundatv'"
        class="px-6 py-3 rounded-lg font-medium transition-all flex items-center gap-2"
        :class="activeTab === 'bundatv' ? 'bg-primary-600 text-white shadow-lg' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
        Bunda TV
      </button>
      <button @click="activeTab = 'peraturan'" class="px-6 py-3 rounded-lg font-medium transition-all flex items-center gap-2" :class="activeTab === 'peraturan' ? 'bg-primary-600 text-white shadow-lg' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
        Peraturan
      </button>
      <button @click="activeTab = 'npk'" class="px-6 py-3 rounded-lg font-medium transition-all flex items-center gap-2" :class="activeTab === 'npk' ? 'bg-primary-600 text-white shadow-lg' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
        NPK
      </button>
      <button @click="activeTab = 'laporan'" class="px-6 py-3 rounded-lg font-medium transition-all flex items-center gap-2" :class="activeTab === 'laporan' ? 'bg-primary-600 text-white shadow-lg' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
        Laporan
      </button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex justify-center py-12">
      <div
        class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"
      ></div>
    </div>

    <!-- ========== TAB BERITA ========== -->
    <div v-else-if="activeTab === 'berita'">
      <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600 dark:text-gray-400">
          Manage artikel dan pengumuman
        </p>
        <router-link
          to="/admin/articles/new"
          class="btn-primary flex items-center gap-2"
        >
          <svg
            class="w-5 h-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 6v6m0 0v6m0-6h6m-6 0H6"
            />
          </svg>
          Tambah Berita
        </router-link>
      </div>

      <div class="card p-4 mb-6">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Cari berita..."
          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
        />
      </div>

      <div class="card overflow-hidden">
        <table class="w-full">
          <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase"
              >
                Judul
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase"
              >
                Kategori
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase"
              >
                Status
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase"
              >
                Tanggal
              </th>
              <th
                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase"
              >
                Aksi
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <tr
              v-for="article in filteredArticles"
              :key="article.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-800"
            >
              <td class="px-6 py-4">
                <p class="font-medium text-gray-900 dark:text-white">
                  {{ article.title }}
                </p>
                <p class="text-sm text-gray-500">By {{ article.author }}</p>
              </td>
              <td class="px-6 py-4">
                <span
                  class="px-2 py-1 text-xs font-medium rounded-full"
                  :class="getCategoryColor(article.category)"
                  >{{ getCategoryLabel(article.category) }}</span
                >
              </td>
              <td class="px-6 py-4">
                <button
                  @click="togglePublish(article)"
                  class="px-2 py-1 text-xs font-medium rounded-full"
                  :class="
                    article.is_published
                      ? 'bg-green-100 text-green-700'
                      : 'bg-gray-100 text-gray-600'
                  "
                >
                  {{ article.is_published ? "Published" : "Draft" }}
                </button>
              </td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                {{ formatDate(article.published_at) }}
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <router-link
                    :to="`/admin/articles/${article.id}`"
                    class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg"
                    title="Edit"
                  >
                    <svg
                      class="w-4 h-4"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                      />
                    </svg>
                  </router-link>
                  <button
                    @click="deleteArticle(article.id)"
                    class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg"
                    title="Hapus"
                  >
                    <svg
                      class="w-4 h-4"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                      />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="filteredArticles.length === 0">
              <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                Tidak ada berita ditemukan
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ========== TAB GALERI ========== -->
    <div v-else-if="activeTab === 'galeri'">
      <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600 dark:text-gray-400">
          Manage foto dan gambar kegiatan
        </p>
        <button
          @click="openGalleryModal()"
          class="btn-primary flex items-center gap-2"
        >
          <svg
            class="w-5 h-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 6v6m0 0v6m0-6h6m-6 0H6"
            />
          </svg>
          Upload Gambar
        </button>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <div
          v-for="gallery in galleries"
          :key="gallery.id"
          class="card overflow-hidden group"
        >
          <div class="relative">
            <img
              :src="gallery.imageUrl"
              :alt="gallery.title"
              class="w-full h-40 object-cover"
              onerror="this.src='https://placehold.co/300x200/gray/white?text=No+Image'"
            />
            <div
              class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2"
            >
              <button
                @click="openGalleryModal(gallery)"
                class="p-2 bg-white rounded-lg text-blue-600 hover:bg-blue-50"
              >
                <svg
                  class="w-4 h-4"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                  />
                </svg>
              </button>
              <button
                @click="deleteGallery(gallery.id)"
                class="p-2 bg-white rounded-lg text-red-600 hover:bg-red-50"
              >
                <svg
                  class="w-4 h-4"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                  />
                </svg>
              </button>
            </div>
          </div>
          <div class="p-3">
            <p
              class="font-medium text-gray-900 dark:text-white text-sm truncate"
            >
              {{ gallery.title }}
            </p>
            <div class="flex items-center justify-between mt-2">
              <span class="text-xs text-gray-500">{{ gallery.category }}</span>
              <button
                @click="toggleGalleryActive(gallery)"
                class="text-xs px-2 py-1 rounded"
                :class="
                  gallery.isActive
                    ? 'bg-green-100 text-green-700'
                    : 'bg-gray-100 text-gray-600'
                "
              >
                {{ gallery.isActive ? "Aktif" : "Hidden" }}
              </button>
            </div>
          </div>
        </div>
        <div
          v-if="galleries.length === 0"
          class="col-span-full py-12 text-center text-gray-500"
        >
          Belum ada galeri. Klik "Upload Gambar" untuk menambahkan.
        </div>
      </div>
    </div>

    <!-- ========== TAB DOKUMEN ========== -->
    <div v-else-if="activeTab === 'dokumen'">
      <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600 dark:text-gray-400">
          Upload dan manage dokumen
        </p>
        <button
          @click="openDocModal()"
          class="btn-primary flex items-center gap-2"
        >
          <svg
            class="w-5 h-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
            />
          </svg>
          Upload Dokumen
        </button>
      </div>

      <div class="space-y-3">
        <div
          v-for="doc in documents"
          :key="doc.id"
          class="card p-4 flex items-center gap-4 hover:shadow-md transition-shadow"
        >
          <div
            class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center"
          >
            <svg
              class="w-6 h-6"
              :class="getFileIcon(doc.fileType)"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
              />
            </svg>
          </div>
          <div class="flex-1">
            <p class="font-medium text-gray-900 dark:text-white">
              {{ doc.title }}
            </p>
            <p class="text-sm text-gray-500">
              {{ doc.fileType }} • {{ formatSize(doc.fileSize) }} •
              {{ getDocCategoryLabel(doc.category) }}
            </p>
          </div>
          <button
            @click="toggleDocActive(doc)"
            class="px-3 py-1 text-xs font-medium rounded-full"
            :class="
              doc.isActive
                ? 'bg-green-100 text-green-700'
                : 'bg-gray-100 text-gray-600'
            "
          >
            {{ doc.isActive ? "Aktif" : "Hidden" }}
          </button>
          <div class="flex gap-2">
            <button
              @click="openDocModal(doc)"
              class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg"
            >
              <svg
                class="w-4 h-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                />
              </svg>
            </button>
            <button
              @click="deleteDocument(doc.id)"
              class="p-2 text-red-600 hover:bg-red-50 rounded-lg"
            >
              <svg
                class="w-4 h-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                />
              </svg>
            </button>
          </div>
        </div>
        <div
          v-if="documents.length === 0"
          class="py-12 text-center text-gray-500"
        >
          Belum ada dokumen. Klik "Upload Dokumen" untuk menambahkan.
        </div>
      </div>
    </div>

    <!-- ========== TAB INFOGRAFIS ========== -->
    <div v-else-if="activeTab === 'infografis'">
      <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600 dark:text-gray-400">Kelola infografis dan visual</p>
        <button @click="openInfoModal()" class="btn-primary flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          Tambah Infografis
        </button>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <div v-for="item in infografis" :key="item.id" class="card overflow-hidden group">
          <div class="relative">
            <img :src="item.imageUrl" :alt="item.title" class="w-full h-48 object-cover" onerror="this.src='https://placehold.co/300x400/gray/white?text=No+Image'"/>
            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
              <button @click="openInfoModal(item)" class="p-2 bg-white rounded-lg text-blue-600 hover:bg-blue-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
              </button>
              <button @click="deleteInfoItem(item.id)" class="p-2 bg-white rounded-lg text-red-600 hover:bg-red-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
              </button>
            </div>
          </div>
          <div class="p-3">
            <p class="font-medium text-gray-900 dark:text-white text-sm truncate">{{ item.title }}</p>
            <div class="flex items-center justify-between mt-2">
              <span class="text-xs text-gray-500">{{ item.category }}</span>
              <button @click="toggleInfoActive(item)" class="text-xs px-2 py-1 rounded" :class="item.isActive ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'">
                {{ item.isActive ? 'Aktif' : 'Hidden' }}
              </button>
            </div>
          </div>
        </div>
        <div v-if="infografis.length === 0" class="col-span-full py-12 text-center text-gray-500">
          Belum ada infografis. Klik "Tambah Infografis" untuk menambahkan.
        </div>
      </div>
    </div>

    <!-- ========== TAB MATERI ========== -->
    <div v-else-if="activeTab === 'materi'">
      <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600 dark:text-gray-400">Kelola materi pembelajaran</p>
        <button @click="openMateriModal()" class="btn-primary flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          Tambah Materi
        </button>
      </div>

      <div class="space-y-3">
        <div v-for="item in materi" :key="item.id" class="card p-4 flex items-center gap-4 hover:shadow-md transition-shadow">
          <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <div class="flex-1">
            <p class="font-medium text-gray-900 dark:text-white">{{ item.title }}</p>
            <p class="text-sm text-gray-500">{{ item.fileType }} • {{ item.category }} • {{ item.description }}</p>
          </div>
          <button @click="toggleMateriActive(item)" class="px-3 py-1 text-xs font-medium rounded-full" :class="item.isActive ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'">
            {{ item.isActive ? 'Aktif' : 'Hidden' }}
          </button>
          <div class="flex gap-2">
            <button @click="openMateriModal(item)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
            </button>
            <button @click="deleteMateriItem(item.id)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
            </button>
          </div>
        </div>
        <div v-if="materi.length === 0" class="py-12 text-center text-gray-500">
          Belum ada materi. Klik "Tambah Materi" untuk menambahkan.
        </div>
      </div>
    </div>

    <!-- ========== TAB MAJALAH/BUKU ========== -->
    <div v-else-if="activeTab === 'majalah'">
      <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600 dark:text-gray-400">Kelola majalah dan buku</p>
        <button @click="openMajalahModal()" class="btn-primary flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
          Tambah Majalah/Buku
        </button>
      </div>
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <div v-for="item in majalahList" :key="item.id" class="card overflow-hidden group">
          <div class="relative">
            <img :src="item.coverUrl" :alt="item.title" class="w-full h-56 object-cover" onerror="this.src='https://placehold.co/300x450/gray/white?text=No+Cover'"/>
            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
              <button @click="openMajalahModal(item)" class="p-2 bg-white rounded-lg text-blue-600 hover:bg-blue-50"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
              <button @click="deleteMajalahItem(item.id)" class="p-2 bg-white rounded-lg text-red-600 hover:bg-red-50"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
            </div>
          </div>
          <div class="p-3">
            <p class="font-medium text-gray-900 dark:text-white text-sm truncate">{{ item.title }}</p>
            <div class="flex items-center justify-between mt-2">
              <span class="text-xs text-gray-500 capitalize">{{ item.category }}</span>
              <button @click="toggleMajalahActive(item)" class="text-xs px-2 py-1 rounded" :class="item.isActive ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'">{{ item.isActive ? 'Aktif' : 'Hidden' }}</button>
            </div>
          </div>
        </div>
        <div v-if="majalahList.length === 0" class="col-span-full py-12 text-center text-gray-500">Belum ada majalah/buku. Klik "Tambah Majalah/Buku" untuk menambahkan.</div>
      </div>
    </div>

    <!-- ========== TAB BUNDA TV ========== -->
    <div v-else-if="activeTab === 'bundatv'">
      <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600 dark:text-gray-400">Kelola video Bunda TV</p>
        <button @click="openBundaTvModal()" class="btn-primary flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
          Tambah Video
        </button>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div v-for="item in bundaTvList" :key="item.id" class="card overflow-hidden group">
          <div class="relative">
            <img :src="item.thumbnailUrl" :alt="item.title" class="w-full h-40 object-cover" onerror="this.src='https://placehold.co/400x225/gray/white?text=No+Thumbnail'"/>
            <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
              <div class="w-12 h-12 bg-white/90 rounded-full flex items-center justify-center"><svg class="w-6 h-6 text-red-600 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></div>
            </div>
            <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
              <button @click="openBundaTvModal(item)" class="p-1.5 bg-white rounded text-blue-600 hover:bg-blue-50"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
              <button @click="deleteBundaTvItem(item.id)" class="p-1.5 bg-white rounded text-red-600 hover:bg-red-50"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
            </div>
          </div>
          <div class="p-3">
            <p class="font-medium text-gray-900 dark:text-white text-sm line-clamp-2">{{ item.title }}</p>
            <div class="flex items-center justify-between mt-2">
              <span class="text-xs text-gray-500">{{ item.category }}</span>
              <button @click="toggleBundaTvActive(item)" class="text-xs px-2 py-1 rounded" :class="item.isActive ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'">{{ item.isActive ? 'Aktif' : 'Hidden' }}</button>
            </div>
          </div>
        </div>
        <div v-if="bundaTvList.length === 0" class="col-span-full py-12 text-center text-gray-500">Belum ada video. Klik "Tambah Video" untuk menambahkan.</div>
      </div>
    </div>

    <!-- ========== TAB PERATURAN ========== -->
    <div v-else-if="activeTab === 'peraturan'">
      <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600 dark:text-gray-400">Kelola peraturan dan kebijakan</p>
        <button @click="openPeraturanModal()" class="btn-primary flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>Tambah Peraturan
        </button>
      </div>
      <div class="space-y-3">
        <div v-for="item in peraturanList" :key="item.id" class="card p-4 flex items-center gap-4 hover:shadow-md transition-shadow">
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center"><svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg></div>
          <div class="flex-1"><p class="font-medium text-gray-900 dark:text-white">{{ item.title }}</p><p class="text-sm text-gray-500">{{ item.description }} • {{ item.category }}</p></div>
          <button @click="togglePeraturanActive(item)" class="px-3 py-1 text-xs font-medium rounded-full" :class="item.isActive ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'">{{ item.isActive ? 'Aktif' : 'Hidden' }}</button>
          <div class="flex gap-2">
            <button @click="openPeraturanModal(item)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
            <button @click="deletePeraturanItem(item.id)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
          </div>
        </div>
        <div v-if="peraturanList.length === 0" class="py-12 text-center text-gray-500">Belum ada peraturan. Klik "Tambah Peraturan" untuk menambahkan.</div>
      </div>
    </div>

    <!-- ========== TAB NPK ========== -->
    <div v-else-if="activeTab === 'npk'">
      <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600 dark:text-gray-400">Kelola Norma, Prosedur, Kriteria</p>
        <button @click="openNpkModal()" class="btn-primary flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>Tambah NPK
        </button>
      </div>
      <div class="space-y-3">
        <div v-for="item in npkList" :key="item.id" class="card p-4 flex items-center gap-4 hover:shadow-md transition-shadow">
          <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center"><svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg></div>
          <div class="flex-1"><p class="font-medium text-gray-900 dark:text-white">{{ item.title }}</p><p class="text-sm text-gray-500">{{ item.description }} • {{ item.category }}</p></div>
          <button @click="toggleNpkActive(item)" class="px-3 py-1 text-xs font-medium rounded-full" :class="item.isActive ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'">{{ item.isActive ? 'Aktif' : 'Hidden' }}</button>
          <div class="flex gap-2">
            <button @click="openNpkModal(item)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
            <button @click="deleteNpkItem(item.id)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
          </div>
        </div>
        <div v-if="npkList.length === 0" class="py-12 text-center text-gray-500">Belum ada NPK. Klik "Tambah NPK" untuk menambahkan.</div>
      </div>
    </div>

    <!-- ========== TAB LAPORAN ========== -->
    <div v-else-if="activeTab === 'laporan'">
      <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600 dark:text-gray-400">Kelola laporan kegiatan</p>
        <button @click="openLaporanModal()" class="btn-primary flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>Tambah Laporan
        </button>
      </div>
      <div class="space-y-3">
        <div v-for="item in laporanList" :key="item.id" class="card p-4 flex items-center gap-4 hover:shadow-md transition-shadow">
          <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center"><svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg></div>
          <div class="flex-1"><p class="font-medium text-gray-900 dark:text-white">{{ item.title }}</p><p class="text-sm text-gray-500">{{ item.description }} • {{ item.category }}</p></div>
          <button @click="toggleLaporanActive(item)" class="px-3 py-1 text-xs font-medium rounded-full" :class="item.isActive ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'">{{ item.isActive ? 'Aktif' : 'Hidden' }}</button>
          <div class="flex gap-2">
            <button @click="openLaporanModal(item)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
            <button @click="deleteLaporanItem(item.id)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
          </div>
        </div>
        <div v-if="laporanList.length === 0" class="py-12 text-center text-gray-500">Belum ada laporan. Klik "Tambah Laporan" untuk menambahkan.</div>
      </div>
    </div>

    <!-- ========== MODAL GALERI ========== -->
    <div
      v-if="showGalleryModal"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
      @click.self="showGalleryModal = false"
    >
      <div
        class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4"
      >
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
          {{ editingGallery ? "Edit Galeri" : "Upload Gambar" }}
        </h3>
        <form @submit.prevent="saveGallery" class="space-y-4">
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >Judul</label
            >
            <input
              v-model="galleryForm.title"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
            />
          </div>
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >Kategori</label
            >
            <input
              v-model="galleryForm.category"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
            />
          </div>
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >Link Gambar</label
            >
            <input
              v-model="galleryForm.image_url"
              type="url"
              required
              placeholder="https://..."
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
            />
          </div>
          <div class="flex gap-3 pt-4">
            <button
              type="button"
              @click="showGalleryModal = false"
              class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
            >
              Batal
            </button>
            <button type="submit" class="flex-1 btn-primary">
              {{ editingGallery ? "Update" : "Upload" }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ========== MODAL DOKUMEN ========== -->
    <div
      v-if="showDocModal"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
      @click.self="showDocModal = false"
    >
      <div
        class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4"
      >
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
          {{ editingDoc ? "Edit Dokumen" : "Upload Dokumen" }}
        </h3>
        <form @submit.prevent="saveDocument" class="space-y-4">
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >Judul</label
            >
            <input
              v-model="docForm.title"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
            />
          </div>
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >Kategori</label
            >
            <select
              v-model="docForm.category"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
            >
              <option value="regulasi">Regulasi</option>
              <option value="kegiatan">Kegiatan</option>
              <option value="anggaran">Anggaran</option>
              <option value="monitoring">Monitoring</option>
            </select>
          </div>
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >Tipe File</label
            >
            <select
              v-model="docForm.file_type"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
            >
              <option value="PDF">PDF</option>
              <option value="DOCX">DOCX</option>
              <option value="XLSX">XLSX</option>
            </select>
          </div>
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >Link File</label
            >
            <input
              v-model="docForm.file_url"
              type="url"
              required
              placeholder="https://..."
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
            />
          </div>
          <div class="flex gap-3 pt-4">
            <button
              type="button"
              @click="showDocModal = false"
              class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
            >
              Batal
            </button>
            <button type="submit" class="flex-1 btn-primary">
              {{ editingDoc ? "Update" : "Upload" }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ========== MODAL INFOGRAFIS ========== -->
    <div v-if="showInfoModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.self="showInfoModal = false">
      <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ editingInfo ? 'Edit Infografis' : 'Tambah Infografis' }}</h3>
        <form @submit.prevent="saveInfografis" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul</label>
            <input v-model="infoForm.title" type="text" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
            <textarea v-model="infoForm.description" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
            <input v-model="infoForm.category" type="text" placeholder="program, gerakan, dll" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link Gambar</label>
            <input v-model="infoForm.image_url" type="url" required placeholder="https://..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div class="flex gap-3 pt-4">
            <button type="button" @click="showInfoModal = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
            <button type="submit" class="flex-1 btn-primary">{{ editingInfo ? 'Update' : 'Tambah' }}</button>
          </div>
        </form>
      </div>
    </div>

    <!-- ========== MODAL MATERI ========== -->
    <div v-if="showMateriModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.self="showMateriModal = false">
      <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ editingMateri ? 'Edit Materi' : 'Tambah Materi' }}</h3>
        <form @submit.prevent="saveMateri" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul</label>
            <input v-model="materiForm.title" type="text" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
            <textarea v-model="materiForm.description" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
            <input v-model="materiForm.category" type="text" placeholder="modul, panduan, dll" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe File</label>
            <select v-model="materiForm.file_type" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
              <option value="PDF">PDF</option>
              <option value="DOCX">DOCX</option>
              <option value="PPTX">PPTX</option>
              <option value="VIDEO">VIDEO</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link File</label>
            <input v-model="materiForm.file_url" type="url" required placeholder="https://..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div class="flex gap-3 pt-4">
            <button type="button" @click="showMateriModal = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
            <button type="submit" class="flex-1 btn-primary">{{ editingMateri ? 'Update' : 'Tambah' }}</button>
          </div>
        </form>
      </div>
    </div>

    <!-- ========== MODAL MAJALAH ========== -->
    <div v-if="showMajalahModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.self="showMajalahModal = false">
      <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ editingMajalah ? 'Edit Majalah/Buku' : 'Tambah Majalah/Buku' }}</h3>
        <form @submit.prevent="saveMajalah" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul</label>
            <input v-model="majalahForm.title" type="text" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
            <textarea v-model="majalahForm.description" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
            <select v-model="majalahForm.category" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
              <option value="majalah">Majalah</option>
              <option value="buku">Buku</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link Cover</label>
            <input v-model="majalahForm.cover_url" type="url" required placeholder="https://..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link File (PDF)</label>
            <input v-model="majalahForm.file_url" type="url" placeholder="https://..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div class="flex gap-3 pt-4">
            <button type="button" @click="showMajalahModal = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
            <button type="submit" class="flex-1 btn-primary">{{ editingMajalah ? 'Update' : 'Tambah' }}</button>
          </div>
        </form>
      </div>
    </div>

    <!-- ========== MODAL BUNDA TV ========== -->
    <div v-if="showBundaTvModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.self="showBundaTvModal = false">
      <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ editingBundaTv ? 'Edit Video' : 'Tambah Video' }}</h3>
        <form @submit.prevent="saveBundaTv" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul</label>
            <input v-model="bundaTvForm.title" type="text" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
            <textarea v-model="bundaTvForm.description" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
            <input v-model="bundaTvForm.category" type="text" placeholder="workshop, lagu, dll" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link Thumbnail</label>
            <input v-model="bundaTvForm.thumbnail_url" type="url" required placeholder="https://..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link Video (YouTube Embed)</label>
            <input v-model="bundaTvForm.video_url" type="url" required placeholder="https://www.youtube.com/embed/..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/>
          </div>
          <div class="flex gap-3 pt-4">
            <button type="button" @click="showBundaTvModal = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
            <button type="submit" class="flex-1 btn-primary">{{ editingBundaTv ? 'Update' : 'Tambah' }}</button>
          </div>
        </form>
      </div>
    </div>

    <!-- ========== MODAL PERATURAN ========== -->
    <div v-if="showPeraturanModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.self="showPeraturanModal = false">
      <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ editingPeraturan ? 'Edit Peraturan' : 'Tambah Peraturan' }}</h3>
        <form @submit.prevent="savePeraturan" class="space-y-4">
          <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul</label><input v-model="peraturanForm.title" type="text" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/></div>
          <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label><textarea v-model="peraturanForm.description" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"></textarea></div>
          <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label><select v-model="peraturanForm.category" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"><option value="perwal">Perwal</option><option value="permendikbud">Permendikbud</option><option value="lainnya">Lainnya</option></select></div>
          <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link File</label><input v-model="peraturanForm.file_url" type="url" placeholder="https://..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/></div>
          <div class="flex gap-3 pt-4"><button type="button" @click="showPeraturanModal = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button><button type="submit" class="flex-1 btn-primary">{{ editingPeraturan ? 'Update' : 'Tambah' }}</button></div>
        </form>
      </div>
    </div>

    <!-- ========== MODAL NPK ========== -->
    <div v-if="showNpkModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.self="showNpkModal = false">
      <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ editingNpk ? 'Edit NPK' : 'Tambah NPK' }}</h3>
        <form @submit.prevent="saveNpk" class="space-y-4">
          <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul</label><input v-model="npkForm.title" type="text" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/></div>
          <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label><textarea v-model="npkForm.description" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"></textarea></div>
          <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label><select v-model="npkForm.category" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"><option value="npk">NPK</option><option value="sop">SOP</option></select></div>
          <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link File</label><input v-model="npkForm.file_url" type="url" placeholder="https://..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/></div>
          <div class="flex gap-3 pt-4"><button type="button" @click="showNpkModal = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button><button type="submit" class="flex-1 btn-primary">{{ editingNpk ? 'Update' : 'Tambah' }}</button></div>
        </form>
      </div>
    </div>

    <!-- ========== MODAL LAPORAN ========== -->
    <div v-if="showLaporanModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.self="showLaporanModal = false">
      <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ editingLaporan ? 'Edit Laporan' : 'Tambah Laporan' }}</h3>
        <form @submit.prevent="saveLaporan" class="space-y-4">
          <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul</label><input v-model="laporanForm.title" type="text" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/></div>
          <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label><textarea v-model="laporanForm.description" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"></textarea></div>
          <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label><select v-model="laporanForm.category" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"><option value="tahunan">Tahunan</option><option value="semester">Semester</option><option value="bulanan">Bulanan</option></select></div>
          <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link File</label><input v-model="laporanForm.file_url" type="url" placeholder="https://..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/></div>
          <div class="flex gap-3 pt-4"><button type="button" @click="showLaporanModal = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button><button type="submit" class="flex-1 btn-primary">{{ editingLaporan ? 'Update' : 'Tambah' }}</button></div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
