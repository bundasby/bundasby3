<script setup>
import { ref, computed, onMounted } from "vue";
import AdminLayout from "@/components/layout/AdminLayout.vue";
import { settingsService, uploadFile } from "@/services";
import { useSliderStore } from "@/stores/slider";
import { faqStorage, initializeData } from "@/services/localStorage";

// ========== TAB STATE ==========
const activeTab = ref("beranda");
const saving = ref(false);
const loading = ref(true);

// ========== FAQ DATA ==========
const faqList = ref([]);
const showFaqModal = ref(false);
const editingFaq = ref(null);
const faqForm = ref({ question: "", answer: "", category: "umum" });

const loadFaq = () => { initializeData(); faqList.value = faqStorage.getAll(); };
const deleteFaqItem = (id) => { if (confirm("Yakin ingin menghapus FAQ ini?")) { faqStorage.delete(id); loadFaq(); } };
const openFaqModal = (item = null) => {
  editingFaq.value = item;
  faqForm.value = item ? { question: item.question, answer: item.answer, category: item.category } : { question: "", answer: "", category: "umum" };
  showFaqModal.value = true;
};
const saveFaq = () => {
  if (editingFaq.value) { faqStorage.update(editingFaq.value.id, { question: faqForm.value.question, answer: faqForm.value.answer, category: faqForm.value.category }); }
  else { faqStorage.create({ question: faqForm.value.question, answer: faqForm.value.answer, category: faqForm.value.category, isActive: true }); }
  loadFaq(); showFaqModal.value = false;
};

// ========== SLIDER DATA (BERANDA) ==========
const sliderStore = useSliderStore();
const showSliderForm = ref(false);
const editingSlide = ref(null);
const sliderFormData = ref({
  image: "",
  title: "",
  caption: "",
});

const openAddSlideForm = () => {
  editingSlide.value = null;
  sliderFormData.value = { image: "", title: "", caption: "" };
  sliderFile.value = null;
  showSliderForm.value = true;
};

const openEditSlideForm = (slide) => {
  editingSlide.value = slide;
  sliderFormData.value = {
    image: slide.image,
    title: slide.title,
    caption: slide.caption,
  };
  showSliderForm.value = true;
};

const sliderFile = ref(null);

const saveSlide = async () => {
  if (!sliderFormData.value.image && !sliderFile.value) {
    alert("Mohon isi URL gambar atau upload foto!");
    return;
  }

  saving.value = true;
  try {
    // If file is uploaded, convert to base64 (like Program Kerja)
    if (sliderFile.value && !sliderFormData.value.image.startsWith('data:')) {
      const reader = new FileReader();
      const base64Promise = new Promise((resolve) => {
        reader.onload = (e) => resolve(e.target.result);
        reader.readAsDataURL(sliderFile.value);
      });
      sliderFormData.value.image = await base64Promise;
    }

    // Save to localStorage (like Program Kerja)
    let slides = JSON.parse(localStorage.getItem('bp_sliders') || '[]');
    
    if (editingSlide.value) {
      // Update existing slide
      const index = slides.findIndex(s => s.id === editingSlide.value.id);
      if (index !== -1) {
        slides[index] = { ...slides[index], ...sliderFormData.value };
      }
    } else {
      // Add new slide
      const newId = slides.length > 0 ? Math.max(...slides.map(s => s.id)) + 1 : 1;
      slides.push({ 
        id: newId, 
        ...sliderFormData.value, 
        active: true,
        createdAt: new Date().toISOString() 
      });
    }
    
    localStorage.setItem('bp_sliders', JSON.stringify(slides));
    
    // Update store
    sliderStore.slides = slides;
    
    showSliderForm.value = false;
    sliderFormData.value = { image: "", title: "", caption: "" };
    sliderFile.value = null;
    editingSlide.value = null;
    alert("Berhasil menyimpan slide!");
    
  } catch (error) {
    console.error("Error saving slide:", error);
    alert("EROR: " + error.message);
  } finally {
    saving.value = false;
  }
};

const deleteSlide = (id) => {
  if (confirm("Yakin ingin menghapus slide ini?")) {
    let slides = JSON.parse(localStorage.getItem('bp_sliders') || '[]');
    slides = slides.filter(s => s.id !== id);
    localStorage.setItem('bp_sliders', JSON.stringify(slides));
    sliderStore.slides = slides;
  }
};

const toggleSlideActive = (id) => {
  let slides = JSON.parse(localStorage.getItem('bp_sliders') || '[]');
  const index = slides.findIndex(s => s.id === id);
  if (index !== -1) {
    slides[index].active = !slides[index].active;
    localStorage.setItem('bp_sliders', JSON.stringify(slides));
    sliderStore.slides = slides;
  }
};

const handleSliderFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    sliderFile.value = file;
    // Preview
    const reader = new FileReader();
    reader.onload = (e) => {
      sliderFormData.value.image = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

// ========== PROGRAM KERJA (BERANDA) ==========
const programs = ref([]);
const showProgramForm = ref(false);
const editingProgram = ref(null);
const programForm = ref({
  title: "",
  description: "",
  image: "",
  icon: "heart" // default icon
});

const loadPrograms = () => {
  const saved = localStorage.getItem('bp_home_programs');
  if (saved) {
    programs.value = JSON.parse(saved);
  } else {
    // Default initial data if empty
    programs.value = [
      { id: 1, title: 'Penguatan Pendidikan Karakter', description: 'Penanaman karakter 7 kebiasaan anak Indonesia hebat.', icon: 'heart', image: 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=600&h=400&fit=crop' },
      { id: 2, title: 'Wajib Belajar 13 Tahun', description: 'Pemerataan kesempatan pendidikan termasuk PAUD.', icon: 'school', image: 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=600&h=400&fit=crop' },
      { id: 3, title: 'Peningkatan Kompetensi Guru', description: 'Peningkatan kualifikasi pendidikan guru minimal D-IV/S-1.', icon: 'users', image: 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=600&h=400&fit=crop' },
      { id: 4, title: 'Literasi, Numerasi & Sains', description: 'Penguatan pendidikan matematika, sains, dan teknologi.', icon: 'book', image: 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&h=400&fit=crop' },
      { id: 5, title: 'Sarana dan Prasarana', description: 'Pemenuhan dan perbaikan fasilitas sekolah.', icon: 'building', image: 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=600&h=400&fit=crop' },
      { id: 6, title: 'Bahasa dan Sastra', description: 'Pemartabatan bahasa negara dan pelindungan bahasa daerah.', icon: 'language', image: 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=600&h=400&fit=crop' }
    ];
    localStorage.setItem('bp_home_programs', JSON.stringify(programs.value));
  }
};

const openProgramForm = (item = null) => {
  editingProgram.value = item;
  programForm.value = item 
    ? { title: item.title, description: item.description, image: item.image, icon: item.icon }
    : { title: "", description: "", image: "", icon: "heart" };
  showProgramForm.value = true;
};

const saveProgram = () => {
  if (editingProgram.value) {
    const index = programs.value.findIndex(p => p.id === editingProgram.value.id);
    if (index !== -1) {
      programs.value[index] = { ...editingProgram.value, ...programForm.value };
    }
  } else {
    const newId = programs.value.length > 0 ? Math.max(...programs.value.map(p => p.id)) + 1 : 1;
    programs.value.push({ id: newId, ...programForm.value });
  }
  localStorage.setItem('bp_home_programs', JSON.stringify(programs.value));
  showProgramForm.value = false;
  editingProgram.value = null;
};

const deleteProgram = (id) => {
  if (confirm("Hapus program ini?")) {
    programs.value = programs.value.filter(p => p.id !== id);
    localStorage.setItem('bp_home_programs', JSON.stringify(programs.value));
  }
};

const handleProgramImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      programForm.value.image = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};


// ========== SETTINGS DATA (KONTAK) ==========
const settings = ref({
  siteName: "Bunda PAUD Kota Surabaya",
  email: "",
  phone: "",
  address: "",
  whatsappNumber: "",
  whatsappMessage: "",
  googleMapsUrl: "",
  instagram: "",
  facebook: "",
  youtube: "",
  tiktok: "",
  twitter: "",
});

const extractedMapSrc = computed(() => {
  const html = settings.value.googleMapsUrl;
  if (!html) return null;
  if (html.startsWith("https://www.google.com/maps/embed")) {
    return html;
  }
  const srcMatch = html.match(/src=["']([^"']+)["']/);
  if (srcMatch && srcMatch[1]) {
    return srcMatch[1];
  }
  return null;
});

const saveSettings = async () => {
  saving.value = true;
  try {
    settingsService.save(settings.value);
    alert("Pengaturan berhasil disimpan!");
  } catch (error) {
    alert("Gagal menyimpan pengaturan");
  } finally {
    saving.value = false;
  }
};

// ========== LIFECYCLE ==========
onMounted(async () => {
  // Load sliders from localStorage (like Program Kerja)
  const savedSliders = localStorage.getItem('bp_sliders');
  if (savedSliders) {
    sliderStore.slides = JSON.parse(savedSliders);
  } else {
    // Initialize with default data if empty
    const defaultSliders = [
      { id: 1, title: 'Selamat Datang', caption: 'Website Bunda PAUD Kota Surabaya', image: 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=1200&h=600&fit=crop', active: true },
      { id: 2, title: 'Program PAUD Berkualitas', caption: 'Membangun generasi emas Indonesia', image: 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=1200&h=600&fit=crop', active: true },
    ];
    localStorage.setItem('bp_sliders', JSON.stringify(defaultSliders));
    sliderStore.slides = defaultSliders;
  }
  
  const data = settingsService.get();
  settings.value = { ...settings.value, ...data };
  
  loadFaq();
  loadPrograms();
  
  loading.value = false;
});
</script>

<template>
  <AdminLayout title="Pengaturan Website">
    <!-- Tabs -->
    <div class="flex flex-wrap gap-2 mb-6">
      <button
        @click="activeTab = 'beranda'"
        class="px-6 py-3 rounded-lg font-medium transition-all"
        :class="activeTab === 'beranda' ? 'bg-primary-600 text-white shadow-lg' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'"
      >
        🏠 Beranda
      </button>
      <button
        @click="activeTab = 'kontak'"
        class="px-6 py-3 rounded-lg font-medium transition-all"
        :class="activeTab === 'kontak' ? 'bg-primary-600 text-white shadow-lg' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'"
      >
        📞 Kontak
      </button>
      <button
        @click="activeTab = 'faq'"
        class="px-6 py-3 rounded-lg font-medium transition-all"
        :class="activeTab === 'faq' ? 'bg-primary-600 text-white shadow-lg' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'"
      >
        ❓ FAQ
      </button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex justify-center py-12">
      <div class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
    </div>

    <!-- Tab: BERANDA (SLIDER & PROGRAM) -->
    <div v-else-if="activeTab === 'beranda'" class="space-y-8">
      
      <!-- SLIDER SECTION -->
      <div>
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white">Slider Utama</h2>
          <button @click="openAddSlideForm" class="btn-primary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
            Tambah Slide
          </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div v-for="slide in sliderStore.slides" :key="slide.id" class="card overflow-hidden" :class="{ 'opacity-50': !slide.active }">
            <div class="relative h-48 bg-gray-100 dark:bg-gray-700">
              <img :src="slide.image" :alt="slide.title" class="w-full h-full object-cover" @error="$event.target.src = 'https://placehold.co/600x300/cccccc/666666?text=Gambar+Error'" />
              <div class="absolute top-2 right-2">
                <span class="px-2 py-1 text-xs font-medium rounded" :class="slide.active ? 'bg-green-500 text-white' : 'bg-gray-500 text-white'">{{ slide.active ? "Aktif" : "Nonaktif" }}</span>
              </div>
            </div>
            <div class="p-4">
              <h3 class="font-bold text-gray-900 dark:text-white mb-1">{{ slide.title || "Tanpa Judul" }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ slide.caption || "Tidak ada caption" }}</p>
              <div class="flex gap-2">
                <button @click="openEditSlideForm(slide)" class="px-3 py-1 text-sm bg-blue-100 dark:bg-blue-900/30 text-blue-600 rounded hover:bg-blue-200">Edit</button>
                <button @click="toggleSlideActive(slide.id)" class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-600 rounded hover:bg-gray-200">{{ slide.active ? "Nonaktifkan" : "Aktifkan" }}</button>
                <button @click="deleteSlide(slide.id)" class="px-3 py-1 text-sm bg-red-100 text-red-600 rounded hover:bg-red-200">Hapus</button>
              </div>
            </div>
          </div>
        </div>
        <div v-if="sliderStore.slides.length === 0" class="text-center py-12 text-gray-500">Belum ada slide.</div>
      </div>

      <!-- PROGRAM KERJA SECTION -->
      <div>
        <div class="flex items-center justify-between mb-6 pt-8 border-t border-gray-200 dark:border-gray-700">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white">Program Kerja Prioritas (Beranda)</h2>
          <button @click="openProgramForm()" class="btn-primary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
            Tambah Program
          </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="program in programs" :key="program.id" class="card overflow-hidden">
            <div class="h-40 bg-gray-100 relative">
              <img :src="program.image" class="w-full h-full object-cover" @error="$event.target.src='https://placehold.co/600x400?text=No+Image'"/>
              <div class="absolute top-2 right-2 bg-white/80 p-1 rounded-full shadow">
                 <!-- Icon Preview -->
                 <span v-if="program.icon === 'heart'">❤️</span>
                 <span v-else-if="program.icon === 'school'">🏫</span>
                 <span v-else-if="program.icon === 'users'">👥</span>
                 <span v-else-if="program.icon === 'book'">📚</span>
                 <span v-else-if="program.icon === 'building'">🏢</span>
                 <span v-else-if="program.icon === 'language'">🗣️</span>
                 <span v-else>📍</span>
              </div>
            </div>
            <div class="p-4">
              <h3 class="font-bold text-gray-900 dark:text-white mb-2 line-clamp-1">{{ program.title }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">{{ program.description }}</p>
              <div class="flex gap-2 justify-end">
                <button @click="openProgramForm(program)" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</button>
                <button @click="deleteProgram(program.id)" class="text-red-600 hover:text-red-800 text-sm font-medium">Hapus</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tab: KONTAK -->
    <div v-else-if="activeTab === 'kontak'">
      <div class="max-w-2xl space-y-6">
        <!-- General Settings -->
        <div class="card p-6">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Informasi Umum</h2>
          <div class="space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Website</label><input v-model="settings.siteName" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label><input v-model="settings.email" type="email" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telepon</label><input v-model="settings.phone" type="tel" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label><textarea v-model="settings.address" rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"></textarea></div>
          </div>
        </div>
        <!-- WhatsApp Settings -->
        <div class="card p-6">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Hotline WhatsApp</h2>
          <div class="space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor WhatsApp</label><input v-model="settings.whatsappNumber" type="tel" placeholder="6281234567890" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"/></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pesan Default</label><textarea v-model="settings.whatsappMessage" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"></textarea></div>
          </div>
        </div>
        <!-- Google Maps Settings -->
        <div class="card p-6">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Google Maps</h2>
          <div class="space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode Embed Google Maps</label><textarea v-model="settings.googleMapsUrl" rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"></textarea></div>
            <div v-if="extractedMapSrc" class="rounded-lg overflow-hidden h-48"><iframe :src="extractedMapSrc" width="100%" height="100%" style="border: 0" allowfullscreen="" loading="lazy"></iframe></div>
          </div>
        </div>
        <!-- Social Media Settings -->
        <div class="card p-6">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Sosial Media</h2>
          <div class="grid md:grid-cols-2 gap-4">
             <div><label class="block text-sm font-medium mb-1">Instagram</label><input v-model="settings.instagram" type="url" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-800 dark:border-gray-600"/></div>
             <div><label class="block text-sm font-medium mb-1">Facebook</label><input v-model="settings.facebook" type="url" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-800 dark:border-gray-600"/></div>
             <div><label class="block text-sm font-medium mb-1">YouTube</label><input v-model="settings.youtube" type="url" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-800 dark:border-gray-600"/></div>
             <div><label class="block text-sm font-medium mb-1">TikTok</label><input v-model="settings.tiktok" type="url" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-800 dark:border-gray-600"/></div>
             <div><label class="block text-sm font-medium mb-1">Twitter/X</label><input v-model="settings.twitter" type="url" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-800 dark:border-gray-600"/></div>
          </div>
        </div>
        <!-- Save Button -->
        <div class="flex justify-end"><button @click="saveSettings" :disabled="saving" class="btn-primary flex items-center gap-2">{{ saving ? "Menyimpan..." : "Simpan Pengaturan" }}</button></div>
      </div>
    </div>

    <!-- Tab: FAQ -->
    <div v-else-if="activeTab === 'faq'">
      <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600 dark:text-gray-400">Kelola pertanyaan yang sering diajukan</p>
        <button @click="openFaqModal()" class="btn-primary flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>Tambah FAQ</button>
      </div>
      <div class="space-y-4 max-w-3xl">
        <div v-for="item in faqList" :key="item.id" class="card p-4 hover:shadow-md transition-shadow">
          <div class="flex items-start gap-4">
            <div class="flex-1">
              <h3 class="font-medium text-gray-900 dark:text-white mb-1">{{ item.question }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ item.answer }}</p>
              <span class="text-xs text-gray-500 mt-2 inline-block bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ item.category }}</span>
            </div>
            <div class="flex gap-2 flex-shrink-0">
              <button @click="openFaqModal(item)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">Edit</button>
              <button @click="deleteFaqItem(item.id)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">Hapus</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Slider Form Modal -->
    <div v-if="showSliderForm" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" @click.self="showSliderForm = false">
      <div class="bg-white dark:bg-gray-800 rounded-xl max-w-lg w-full max-h-[90vh] overflow-y-auto p-6">
        <h2 class="text-xl font-bold mb-4">{{ editingSlide ? "Edit Slide" : "Tambah Slide" }}</h2>
        <div class="space-y-4">
           <div><label class="block mb-2">URL Gambar</label><input v-model="sliderFormData.image" type="text" class="w-full px-4 py-2 border rounded-lg"/><input type="file" @change="handleSliderFileUpload" class="mt-2 block w-full text-sm"/></div>
           <div v-if="sliderFormData.image"><img :src="sliderFormData.image" class="h-32 object-cover rounded"/></div>
           <div><label class="block mb-2">Judul</label><input v-model="sliderFormData.title" type="text" class="w-full px-4 py-2 border rounded-lg"/></div>
           <div><label class="block mb-2">Caption</label><input v-model="sliderFormData.caption" type="text" class="w-full px-4 py-2 border rounded-lg"/></div>
           <button @click="saveSlide" class="btn-primary w-full">Simpan</button>
        </div>
      </div>
    </div>

    <!-- FAQ Modal -->
    <div v-if="showFaqModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.self="showFaqModal = false">
      <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-bold mb-4">{{ editingFaq ? 'Edit FAQ' : 'Tambah FAQ' }}</h3>
        <form @submit.prevent="saveFaq" class="space-y-4">
          <div><label class="block mb-1">Pertanyaan</label><input v-model="faqForm.question" type="text" required class="w-full px-4 py-2 border rounded-lg"/></div>
          <div><label class="block mb-1">Jawaban</label><textarea v-model="faqForm.answer" rows="4" required class="w-full px-4 py-2 border rounded-lg"></textarea></div>
          <div><label class="block mb-1">Kategori</label><input v-model="faqForm.category" type="text" class="w-full px-4 py-2 border rounded-lg"/></div>
          <div class="flex gap-3 pt-4"><button type="submit" class="flex-1 btn-primary">Simpan</button></div>
        </form>
      </div>
    </div>

    <!-- Program Form Modal -->
    <div v-if="showProgramForm" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" @click.self="showProgramForm = false">
      <div class="bg-white dark:bg-gray-800 rounded-xl max-w-lg w-full max-h-[90vh] overflow-y-auto p-6">
        <h2 class="text-xl font-bold mb-4">{{ editingProgram ? "Edit Program" : "Tambah Program" }}</h2>
        <div class="space-y-4">
           <div><label class="block mb-2">Judul</label><input v-model="programForm.title" type="text" class="w-full px-4 py-2 border rounded-lg"/></div>
           <div><label class="block mb-2">Deskripsi</label><textarea v-model="programForm.description" rows="3" class="w-full px-4 py-2 border rounded-lg"></textarea></div>
           
           <div>
              <label class="block mb-2">Icon</label>
              <select v-model="programForm.icon" class="w-full px-4 py-2 border rounded-lg">
                 <option value="heart">❤️ Heart (Karater)</option>
                 <option value="school">🏫 School (Wajib Belajar)</option>
                 <option value="users">👥 Users (Guru)</option>
                 <option value="book">📚 Book (Literasi)</option>
                 <option value="building">🏢 Building (Sarana)</option>
                 <option value="language">🗣️ Language (Bahasa)</option>
              </select>
           </div>

           <div><label class="block mb-2">URL Gambar</label><input v-model="programForm.image" type="text" class="w-full px-4 py-2 border rounded-lg"/><input type="file" @change="handleProgramImageUpload" class="mt-2 block w-full text-sm"/></div>
           <div v-if="programForm.image"><img :src="programForm.image" class="h-32 object-cover rounded"/></div>
           
           <button @click="saveProgram" class="btn-primary w-full">Simpan</button>
        </div>
      </div>
    </div>

  </AdminLayout>
</template>
