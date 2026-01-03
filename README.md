# Website Bunda PAUD Kota Surabaya

Website resmi Bunda PAUD Kota Surabaya untuk informasi, koordinasi, dan layanan terkait Pendidikan Anak Usia Dini.

## 🚀 Tech Stack

- **Frontend:** Vue.js 3 + Vite + TailwindCSS v4
- **Backend:** Laravel 11 + Sanctum API
- **Database:** MySQL / PostgreSQL

## 📋 Features

### Public Website

- ✅ Homepage dengan statistik dan berita terbaru
- ✅ Profil (Visi Misi, Tentang, Struktur Organisasi)
- ✅ Program Kerja
- ✅ Dokumen dengan filter kategori
- ✅ Layanan (Data Stunting, MBG, Form Pengaduan)
- ✅ Berita & Pengumuman
- ✅ Galeri dengan lightbox
- ✅ Kontak dengan form dan Google Maps
- ✅ Dark mode support
- ✅ Responsive design

### Admin Panel

- ✅ Login authentication
- ✅ Dashboard dengan statistik
- ✅ Kelola Berita (CRUD)
- ✅ Kelola Galeri (CRUD)
- ✅ Kelola Dokumen (CRUD)
- ✅ Kelola Pengaduan

### API Endpoints

- `POST /api/v1/auth/login` - Login admin
- `GET /api/v1/articles` - Get articles
- `GET /api/v1/galleries` - Get galleries
- `GET /api/v1/documents` - Get documents
- `POST /api/v1/complaints` - Submit complaint
- `GET /api/v1/statistics` - Get statistics

## 🛠️ Quick Start

### Development

```bash
# Frontend
cd frontend
npm install
npm run dev

# Backend
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

### Demo Admin

- **URL:** http://localhost:5173/admin/login
- **Email:** admin@bundapaud.surabaya.go.id
- **Password:** password123

## 📁 Project Structure

```
├── frontend/           # Vue.js frontend
│   ├── src/
│   │   ├── views/      # Page components
│   │   ├── components/ # Reusable components
│   │   ├── services/   # API services
│   │   ├── stores/     # Pinia stores
│   │   └── composables/# Vue composables
│   └── public/
│
├── backend/            # Laravel backend
│   ├── app/
│   │   ├── Models/
│   │   └── Http/Controllers/Api/
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   └── routes/api.php
│
└── DEPLOYMENT.md       # Deployment guide
```

## 📄 License

© 2026 Bunda PAUD Kota Surabaya. All rights reserved.
