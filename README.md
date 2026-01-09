# Website Bunda PAUD Kota Surabaya

![Vue.js](https://img.shields.io/badge/Vue.js-3.5-4FC08D?style=flat-square&logo=vue.js)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-4-06B6D4?style=flat-square&logo=tailwindcss)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-4169E1?style=flat-square&logo=postgresql)
![License](https://img.shields.io/badge/License-Proprietary-blue?style=flat-square)

Website resmi Bunda PAUD Kota Surabaya untuk informasi, koordinasi, dan layanan terkait Pendidikan Anak Usia Dini.

## 📸 Screenshots

| Homepage                                   | Admin Dashboard                                |
| ------------------------------------------ | ---------------------------------------------- |
| ![Homepage](docs/screenshots/homepage.png) | ![Admin](docs/screenshots/admin-dashboard.png) |

## 🚀 Tech Stack

| Layer          | Technology                       |
| -------------- | -------------------------------- |
| **Frontend**   | Vue.js 3 + Vite + TailwindCSS v4 |
| **Backend**    | Laravel 12 + Sanctum API         |
| **Database**   | PostgreSQL 16                    |
| **Caching**    | Redis (optional)                 |
| **Deployment** | Docker / Vercel + Railway        |

## 📋 Features

### Public Website

- ✅ Homepage dengan statistik dan berita terbaru
- ✅ Profil (Visi Misi, Tentang, Struktur Organisasi)
- ✅ Program Kerja & Layanan
- ✅ Dokumen dengan filter kategori
- ✅ Form Pengaduan & Konsultasi
- ✅ Berita & Pengumuman
- ✅ Galeri dengan lightbox
- ✅ Dark mode & PWA support
- ✅ Responsive design

### Admin Panel

- ✅ Multi-user authentication dengan roles
- ✅ Dashboard dengan analytics & charts
- ✅ CRUD Berita, Galeri, Dokumen
- ✅ Kelola Pengaduan & Konsultasi
- ✅ Export data ke CSV/Excel
- ✅ User Management (Super Admin)

### API

- 26+ REST API endpoints
- Laravel Sanctum authentication
- Rate limiting & CORS
- Full documentation: [API_DOCUMENTATION.md](backend/API_DOCUMENTATION.md)

## 🛠️ Quick Start

### Prerequisites

- Node.js 18+
- PHP 8.2+
- PostgreSQL 16+
- Composer 2.x

### Development

```bash
# Clone repository
git clone https://github.com/bundapaud/website.git
cd website

# Frontend
cd frontend
npm install
npm run dev

# Backend (new terminal)
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

### Demo Credentials

| Role        | Email                             | Password    |
| ----------- | --------------------------------- | ----------- |
| Super Admin | admin@bundapaud.surabaya.go.id    | password123 |
| Admin       | operator@bundapaud.surabaya.go.id | password123 |

## 📁 Project Structure

```
├── frontend/               # Vue.js 3 + Vite
│   ├── src/
│   │   ├── views/          # 50+ page components
│   │   ├── components/     # Reusable UI components
│   │   ├── services/       # API services
│   │   └── stores/         # Pinia state management
│   └── public/             # Static assets + PWA
│
├── backend/                # Laravel 12
│   ├── app/
│   │   ├── Models/         # 21 Eloquent models
│   │   └── Http/Controllers/Api/   # 26 API controllers
│   ├── database/
│   │   ├── migrations/     # 29 database migrations
│   │   └── seeders/        # Initial data seeders
│   └── tests/              # PHPUnit tests
│
├── docker-compose.yml      # Docker configuration
└── DEPLOYMENT.md           # Deployment guide
```

## 🧪 Testing

```bash
# Run backend tests
cd backend
php artisan test

# Run with coverage
php artisan test --coverage
```

## 🚀 Deployment

See [DEPLOYMENT.md](DEPLOYMENT.md) for detailed deployment instructions.

### Quick Deploy

```bash
# Using Docker
docker-compose up -d

# Or deploy separately
# Frontend -> Vercel
# Backend -> Railway
```

## 🔒 Security

- CSRF protection enabled
- XSS prevention via sanitization
- SQL injection prevention (Eloquent ORM)
- Rate limiting on authentication endpoints
- Secure file upload validation

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

© 2026 Bunda PAUD Kota Surabaya. All rights reserved.

---

**Developed with ❤️ for PAUD Kota Surabaya**
