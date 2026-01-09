# API Documentation - Bunda PAUD Kota Surabaya

**Base URL:** `https://api.bundapaud.surabaya.go.id/api/v1`  
**Version:** 1.0.0  
**Authentication:** Bearer Token (Laravel Sanctum)

---

## Table of Contents

1. [Authentication](#authentication)
2. [Articles](#articles)
3. [Galleries](#galleries)
4. [Documents](#documents)
5. [Complaints](#complaints)
6. [Bunda PAUD](#bunda-paud)
7. [Sliders](#sliders)
8. [Profiles](#profiles)
9. [Settings](#settings)
10. [Publications](#publications)
11. [Services](#services)
12. [Analytics & Export](#analytics--export)
13. [External API Integration](#external-api-integration) _(NEW)_
14. [API Client Management](#api-client-management) _(NEW)_
15. [Webhook Management](#webhook-management) _(NEW)_
16. [Public API (External Access)](#public-api-external-access) _(NEW)_

---

## Authentication

### Login

```http
POST /auth/login
```

**Request Body:**

```json
{
    "email": "admin@bundapaud.surabaya.go.id",
    "password": "password123"
}
```

**Response (200):**

```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "Admin",
            "email": "admin@bundapaud.surabaya.go.id",
            "role": { "code": "super_admin", "name": "Super Admin" }
        },
        "token": "1|abc123xyz"
    }
}
```

### Get Current User

```http
GET /auth/user
Authorization: Bearer {token}
```

### Logout

```http
POST /auth/logout
Authorization: Bearer {token}
```

---

## Articles

### Get All Articles (Public)

```http
GET /articles
```

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `category` | string | Filter by category (kegiatan, edukasi, pengumuman) |
| `limit` | integer | Limit results (default: paginated 10) |

### Get Article by Slug (Public)

```http
GET /articles/{slug}
```

### Create Article (Auth Required)

```http
POST /articles
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**

```json
{
    "title": "Judul Artikel",
    "slug": "judul-artikel",
    "excerpt": "Ringkasan artikel maksimal 500 karakter",
    "content": "Konten lengkap artikel dengan HTML",
    "thumbnail": "/uploads/articles/image.jpg",
    "category": "kegiatan",
    "is_published": true
}
```

### Update Article (Auth Required)

```http
PUT /articles/{id}
Authorization: Bearer {token}
```

### Delete Article (Auth Required)

```http
DELETE /articles/{id}
Authorization: Bearer {token}
```

---

## Galleries

### Get All Galleries (Public)

```http
GET /galleries
```

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `grouped` | boolean | Group by category |

### Create Gallery (Auth Required)

```http
POST /galleries
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "title": "Judul Gambar",
    "description": "Deskripsi opsional",
    "image_url": "/uploads/galleries/image.jpg",
    "category": "kegiatan",
    "order": 1,
    "is_active": true
}
```

### Update Gallery (Auth Required)

```http
PUT /galleries/{id}
Authorization: Bearer {token}
```

### Delete Gallery (Auth Required)

```http
DELETE /galleries/{id}
Authorization: Bearer {token}
```

---

## Documents

### Get All Documents (Public)

```http
GET /documents
```

### Create Document (Auth Required)

```http
POST /documents
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "title": "Nama Dokumen",
    "file_url": "/uploads/documents/file.pdf",
    "category": "peraturan",
    "description": "Deskripsi dokumen"
}
```

### Update/Delete Document

```http
PUT /documents/{id}
DELETE /documents/{id}
Authorization: Bearer {token}
```

---

## Complaints

### Submit Complaint (Public)

```http
POST /complaints
```

**Request Body:**

```json
{
    "name": "Nama Pelapor",
    "email": "email@example.com",
    "phone": "081234567890",
    "subject": "Subjek Pengaduan",
    "message": "Isi pengaduan lengkap",
    "attachment": "/uploads/complaints/file.pdf"
}
```

### Get All Complaints (Auth Required)

```http
GET /complaints
Authorization: Bearer {token}
```

### Update Complaint Status (Auth Required)

```http
PUT /complaints/{id}
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "status": "processed",
    "response": "Tanggapan atas pengaduan"
}
```

---

## Bunda PAUD

### Get All Bunda PAUD (Public)

```http
GET /bunda-paud
```

### Get Kecamatan List (Public)

```http
GET /bunda-paud/kecamatan
```

### CRUD Operations (Auth Required)

```http
POST /bunda-paud
GET /bunda-paud/{id}
PUT /bunda-paud/{id}
DELETE /bunda-paud/{id}
Authorization: Bearer {token}
```

---

## Sliders

### Get Active Sliders (Public)

```http
GET /sliders
```

### Get All Sliders (Public)

```http
GET /sliders/all
```

### Slider CRUD

```http
POST /sliders
PUT /sliders/{id}
DELETE /sliders/{id}
PATCH /sliders/{id}/toggle
```

---

## Profiles

### Get Published Profiles (Public)

```http
GET /profiles
```

### Get Profile by Key (Public)

```http
GET /profiles/{key}
```

**Available Keys:**

-   `visi_misi`
-   `tugas_fungsi`
-   `struktur_organisasi`
-   `arti_logo`
-   `sejarah`

### Update Profile (Auth Required)

```http
PUT /profiles/{key}
Authorization: Bearer {token}
```

---

## Settings

### Get Settings (Public)

```http
GET /settings
```

### Update Settings

```http
PUT /settings
```

---

## Publications

### Infografis

```http
GET /infografis
POST /infografis (Auth)
PUT /infografis/{id} (Auth)
DELETE /infografis/{id} (Auth)
```

### Materi

```http
GET /materi
POST /materi (Auth)
PUT /materi/{id} (Auth)
DELETE /materi/{id} (Auth)
```

### Majalah

```http
GET /majalah
POST /majalah (Auth)
PUT /majalah/{id} (Auth)
DELETE /majalah/{id} (Auth)
```

### Bunda TV

```http
GET /bunda-tv
POST /bunda-tv (Auth)
PUT /bunda-tv/{id} (Auth)
DELETE /bunda-tv/{id} (Auth)
```

### Peraturan

```http
GET /peraturan
POST /peraturan (Auth)
PUT /peraturan/{id} (Auth)
DELETE /peraturan/{id} (Auth)
```

### NPK

```http
GET /npk
POST /npk (Auth)
PUT /npk/{id} (Auth)
DELETE /npk/{id} (Auth)
```

### Laporan

```http
GET /laporan
POST /laporan (Auth)
PUT /laporan/{id} (Auth)
DELETE /laporan/{id} (Auth)
```

---

## Services

### Peminjaman Gedung

```http
POST /peminjaman (Public - submit)
GET /peminjaman/approved (Public)
GET /peminjaman (Auth)
PUT /peminjaman/{id} (Auth)
DELETE /peminjaman/{id} (Auth)
```

### Konsultasi PAUD HI

```http
POST /konsultasi (Public - submit)
GET /konsultasi/approved (Public)
GET /konsultasi (Auth)
PUT /konsultasi/{id} (Auth)
DELETE /konsultasi/{id} (Auth)
```

### Mitra PAUD

```http
POST /mitra-paud (Public - submit)
GET /mitra-paud/approved (Public)
GET /mitra-paud (Auth)
PUT /mitra-paud/{id} (Auth)
DELETE /mitra-paud/{id} (Auth)
```

### Gugus Tugas

```http
GET /gugus-tugas (Public)
POST /gugus-tugas (Auth)
PUT /gugus-tugas/{id} (Auth)
DELETE /gugus-tugas/{id} (Auth)
```

---

## Analytics & Export

### Analytics

```http
GET /analytics/stats
GET /analytics/complaints-trend
GET /analytics/bunda-paud-distribution
GET /analytics/recent-activity
GET /analytics/mitra-by-category
```

### Export (CSV)

```http
GET /export/bunda-paud
GET /export/mitra-paud
GET /export/complaints
GET /export/articles
```

### Export (Excel)

```http
GET /export/bunda-paud/excel
GET /export/mitra-paud/excel
```

---

## Error Responses

### 401 Unauthorized

```json
{
    "message": "Unauthenticated."
}
```

### 422 Validation Error

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

### 404 Not Found

```json
{
    "message": "Resource not found."
}
```

---

## Health Check

```http
GET /health
GET /health/ping
GET /health/ready
GET /health/live
```

---

## File Upload

```http
POST /upload
Content-Type: multipart/form-data
```

**Request Body:**

-   `file`: File to upload
-   `folder`: Target folder (articles, galleries, documents, etc.)

**Response:**

```json
{
    "success": true,
    "url": "/uploads/articles/filename.jpg"
}
```

---

_Last Updated: January 2026_

---

## External API Integration

Manage connections to external government agency APIs (Dinas).

### Get All External API Configs

```http
GET /external-apis
Authorization: Bearer {token}
```

### Create External API Config

```http
POST /external-apis
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "name": "Dinas Pendidikan Kota Surabaya",
    "code": "disdik",
    "base_url": "https://api.disdik.surabaya.go.id",
    "auth_type": "api_key",
    "api_key": "your_api_key",
    "api_secret": "your_api_secret",
    "timeout": 30,
    "description": "API untuk mengambil data sekolah PAUD"
}
```

**Auth Types:** `none`, `api_key`, `bearer`, `basic`

### Test External API Connection

```http
POST /external-apis/{id}/test
Authorization: Bearer {token}
```

### Sync Data from External API

```http
POST /external-apis/{id}/sync
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "endpoint": "/api/v1/schools",
    "params": { "kecamatan": "Gubeng" }
}
```

### Update/Delete External API Config

```http
PUT /external-apis/{id}
DELETE /external-apis/{id}
Authorization: Bearer {token}
```

---

## API Client Management

Manage external organizations (dinas) that access your API.

### Get All API Clients

```http
GET /api-clients
Authorization: Bearer {token}
```

### Create API Client

```http
POST /api-clients
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "name": "Dinas Pendidikan Integration",
    "organization": "Dinas Pendidikan Kota Surabaya",
    "allowed_endpoints": ["data-paud", "statistics"],
    "rate_limit": 100,
    "contact_email": "it@disdik.surabaya.go.id",
    "contact_person": "IT Disdik"
}
```

**Response (201):**

```json
{
    "success": true,
    "message": "API client created successfully",
    "data": {
        "id": 1,
        "api_key": "bp_abc123...",
        "api_secret": "secret_xyz..."
    }
}
```

> ⚠️ **Important:** `api_secret` is only shown once. Save it securely!

### Regenerate API Key

```http
POST /api-clients/{id}/regenerate-key
Authorization: Bearer {token}
```

### Get API Client Request Logs

```http
GET /api-clients/{id}/logs
Authorization: Bearer {token}
```

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `per_page` | integer | Items per page (default: 50) |
| `method` | string | Filter by HTTP method |
| `status` | string | Filter: `success` or `failed` |

### Get Available Endpoints

```http
GET /api-clients/endpoints
Authorization: Bearer {token}
```

---

## Webhook Management

Configure webhooks to send real-time notifications to external systems.

### Get All Webhooks

```http
GET /webhooks
Authorization: Bearer {token}
```

### Create Webhook

```http
POST /webhooks
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "api_client_id": 1,
    "name": "Disdik Notification Webhook",
    "url": "https://api.disdik.surabaya.go.id/webhook",
    "events": ["article.created", "data_paud.updated"],
    "retry_count": 3,
    "timeout": 30
}
```

**Available Events:**

-   `article.created` - Artikel baru dibuat
-   `article.updated` - Artikel diperbarui
-   `data_paud.created` - Data PAUD baru
-   `data_paud.updated` - Data PAUD diperbarui
-   `bunda_paud.updated` - Data Bunda PAUD diperbarui
-   `mitra_paud.registered` - Mitra PAUD baru terdaftar
-   `mitra_paud.approved` - Mitra PAUD disetujui
-   `complaint.new` - Pengaduan baru masuk
-   `complaint.responded` - Pengaduan direspon
-   `statistics.updated` - Statistik diperbarui

### Test Webhook

```http
POST /webhooks/{id}/test
Authorization: Bearer {token}
```

### Get Webhook Logs

```http
GET /webhooks/{id}/logs
Authorization: Bearer {token}
```

### Retry Failed Webhooks

```http
POST /webhooks/retry-failed
Authorization: Bearer {token}
```

### Get Webhook Statistics

```http
GET /webhooks/statistics
Authorization: Bearer {token}
```

### Webhook Payload Format

When a webhook is triggered, the following payload is sent:

```json
{
    "event": "article.created",
    "timestamp": "2026-01-09T10:30:00+07:00",
    "data": {
        "id": 123,
        "title": "Artikel Baru",
        ...
    },
    "source": {
        "name": "Bunda PAUD Kota Surabaya",
        "url": "https://bundapaud.surabaya.go.id"
    }
}
```

**Webhook Headers:**
| Header | Description |
|--------|-------------|
| `X-Webhook-Event` | Event name |
| `X-Webhook-Signature` | HMAC-SHA256 signature |
| `X-Webhook-Timestamp` | Unix timestamp |

---

## Public API (External Access)

**Base URL:** `https://api.bundapaud.surabaya.go.id/api/public/v1`

> 🔐 **Authentication:** API Key + API Secret in headers

### Headers Required

```http
X-API-Key: bp_your_api_key
X-API-Secret: your_api_secret
```

### Rate Limiting

-   Default: 100 requests/minute per API key
-   Headers returned:
    -   `X-RateLimit-Limit`: Maximum requests per minute
    -   `X-RateLimit-Remaining`: Remaining requests
    -   `X-RateLimit-Reset`: Unix timestamp when limit resets

### Health Check

```http
GET /health
```

### Get PAUD Data

```http
GET /data-paud
```

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `kecamatan` | string | Filter by kecamatan |
| `status` | string | Filter by status |
| `per_page` | integer | Items per page |

### Get PAUD by NPSN

```http
GET /data-paud/{npsn}
```

### Get Bunda PAUD Data

```http
GET /bunda-paud
```

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `type` | string | Filter: `kecamatan` or `kelurahan` |
| `kecamatan` | string | Filter by kecamatan name |

### Get Mitra PAUD Data

```http
GET /mitra-paud
```

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `kategori` | string | Filter by category |

### Get Statistics

```http
GET /statistics
```

**Response:**

```json
{
    "success": true,
    "data": {
        "total_paud": 850,
        "total_guru": 3200,
        "total_siswa": 45000,
        "total_kecamatan": 31,
        "total_kelurahan": 154,
        "total_bunda_paud_kecamatan": 31,
        "total_bunda_paud_kelurahan": 154,
        "total_mitra_paud": 25,
        "stunting_rate": "12%",
        "mbg_penerima": 35000
    }
}
```

### Get Articles

```http
GET /articles
```

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `category` | string | Filter by category |
| `limit` | integer | Limit results (default: 10) |

### Get Programs

```http
GET /programs
```

---

## Error Responses (Public API)

### 401 Unauthorized

```json
{
    "success": false,
    "message": "API key and secret are required",
    "status_code": 401
}
```

### 429 Rate Limit Exceeded

```json
{
    "success": false,
    "message": "Rate limit exceeded. Maximum 100 requests per minute.",
    "status_code": 429,
    "rate_limit": 100,
    "remaining": 0,
    "retry_after": 60
}
```

### 403 Forbidden

```json
{
    "success": false,
    "message": "You do not have access to this endpoint",
    "status_code": 403,
    "requested_endpoint": "data-paud"
}
```

---

_Last Updated: January 2026_
