# Website Bunda PAUD Kota Surabaya

# Panduan Deployment

## 📁 Struktur Proyek

```
Proyek Satu/
├── frontend/       # Vue.js 3 + Vite + TailwindCSS v4
│   ├── src/
│   │   ├── views/      # 10+ halaman publik + admin
│   │   ├── components/ # Navbar, Footer, UI
│   │   ├── services/   # API services
│   │   └── stores/     # Pinia state
│   └── dist/           # Build output
│
└── backend/        # Laravel 11 + Sanctum
    ├── app/
    │   ├── Models/         # Article, Gallery, Document, Complaint
    │   └── Http/Controllers/Api/
    ├── database/
    │   ├── migrations/     # Database schema
    │   └── seeders/        # Sample data
    └── public/             # Document root
```

---

## 🚀 Development

### Frontend

```bash
cd frontend
npm install
npm run dev
# Open http://localhost:5173
```

### Backend

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate

# Configure database in .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=bundapaud_surabaya
# DB_USERNAME=root
# DB_PASSWORD=

php artisan migrate:fresh --seed
php artisan serve
# API at http://localhost:8000/api/v1
```

---

## 🏭 Production Build

### Frontend Build

```bash
cd frontend
npm run build
# Output di folder dist/
```

### Backend Production

```bash
cd backend
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🖥️ Server Deployment Options

### Option 1: Shared Hosting (cPanel)

1. **Upload Backend**
   - Upload folder `backend/` ke `public_html/api/`
   - Set document root ke `public/`
   - Configure `.env` dengan database credentials
2. **Upload Frontend**
   - Build frontend: `npm run build`
   - Upload folder `dist/` ke `public_html/`
3. **Configure .htaccess**
   ```apache
   # public_html/.htaccess
   RewriteEngine On
   RewriteBase /
   RewriteRule ^api/(.*)$ /api/public/$1 [L]
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteRule . /index.html [L]
   ```

### Option 2: VPS/Cloud (Nginx)

```nginx
# /etc/nginx/sites-available/bundapaud

server {
    listen 80;
    server_name bundapaud.surabaya.go.id;
    root /var/www/bundapaud/frontend/dist;
    index index.html;

    # Frontend SPA
    location / {
        try_files $uri $uri/ /index.html;
    }

    # Backend API
    location /api {
        alias /var/www/bundapaud/backend/public;
        try_files $uri $uri/ /api/index.php?$query_string;

        location ~ \.php$ {
            fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
            fastcgi_param SCRIPT_FILENAME $request_filename;
            include fastcgi_params;
        }
    }

    # Storage files
    location /storage {
        alias /var/www/bundapaud/backend/storage/app/public;
    }
}
```

### Option 3: Vercel (Frontend) + Railway/Render (Backend)

**Frontend (Vercel)**

```bash
cd frontend
npm run build
npx vercel --prod
```

**Backend (Railway)**

- Connect GitHub repo
- Set environment variables
- Deploy Laravel app

---

## 🔐 Environment Variables

### Frontend (.env)

```env
VITE_API_URL=https://api.bundapaud.surabaya.go.id/api/v1
VITE_APP_NAME="Bunda PAUD Kota Surabaya"
```

### Backend (.env)

```env
APP_NAME="Bunda PAUD API"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.bundapaud.surabaya.go.id

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=bundapaud
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

SANCTUM_STATEFUL_DOMAINS=bundapaud.surabaya.go.id
SESSION_DOMAIN=.bundapaud.surabaya.go.id

# CORS
CORS_ALLOWED_ORIGINS=https://bundapaud.surabaya.go.id
```

---

## 📋 Deployment Checklist

- [ ] Build frontend untuk production
- [ ] Configure backend .env
- [ ] Run database migrations
- [ ] Run database seeders (optional)
- [ ] Configure web server (Apache/Nginx)
- [ ] Setup SSL certificate
- [ ] Test all routes
- [ ] Setup file storage for uploads
- [ ] Configure backup strategy

---

## 🔒 Security Checklist

- [ ] APP_DEBUG=false di production
- [ ] Generate new APP_KEY
- [ ] HTTPS enabled
- [ ] CORS configured properly
- [ ] Rate limiting enabled
- [ ] Database credentials secure
- [ ] Admin credentials changed from demo
