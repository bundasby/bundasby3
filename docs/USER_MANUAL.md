# User Manual - Admin Panel Bunda PAUD

## Panduan Penggunaan Panel Administrasi

---

## Daftar Isi

1. [Login & Autentikasi](#1-login--autentikasi)
2. [Dashboard](#2-dashboard)
3. [Kelola Berita](#3-kelola-berita)
4. [Kelola Galeri](#4-kelola-galeri)
5. [Kelola Dokumen](#5-kelola-dokumen)
6. [Pengaduan](#6-pengaduan)
7. [Pengaturan](#7-pengaturan)
8. [Manajemen Pengguna](#8-manajemen-pengguna)

---

## 1. Login & Autentikasi

### Cara Login

1. Buka halaman `/admin/login`
2. Masukkan **Email** dan **Password**
3. Klik tombol **Masuk**

### Kredensial Default

| Role        | Email                             | Password    |
| ----------- | --------------------------------- | ----------- |
| Super Admin | admin@bundapaud.surabaya.go.id    | password123 |
| Operator    | operator@bundapaud.surabaya.go.id | password123 |

### Logout

Klik ikon profil di kanan atas → pilih **Keluar**

---

## 2. Dashboard

Dashboard menampilkan ringkasan:

- **Statistik** - Total berita, galeri, dokumen, pengaduan
- **Grafik Tren** - Pengaduan per bulan
- **Aktivitas Terbaru** - Log aktivitas terakhir
- **Quick Actions** - Tombol aksi cepat

---

## 3. Kelola Berita

### Menambah Berita Baru

1. Klik menu **Berita** di sidebar
2. Klik tombol **+ Tambah Berita**
3. Isi form:
   - **Judul** (wajib)
   - **Slug** (otomatis dari judul)
   - **Kategori** (Kegiatan/Edukasi/Pengumuman)
   - **Ringkasan** (maks 500 karakter)
   - **Konten** (editor WYSIWYG)
   - **Thumbnail** (upload gambar)
4. Toggle **Publikasikan** jika ingin langsung tampil
5. Klik **Simpan**

### Mengedit Berita

1. Klik ikon **Edit** (pensil) pada baris berita
2. Ubah data yang diperlukan
3. Klik **Simpan Perubahan**

### Menghapus Berita

1. Klik ikon **Hapus** (tempat sampah) pada baris berita
2. Konfirmasi penghapusan

---

## 4. Kelola Galeri

### Menambah Foto/Video

1. Klik menu **Galeri** di sidebar
2. Klik **+ Tambah Galeri**
3. Isi form:
   - **Judul** (wajib)
   - **Deskripsi** (opsional)
   - **Kategori**
   - **Upload Gambar**
4. Klik **Simpan**

### Mengatur Urutan

- Drag & drop item untuk mengubah urutan tampilan

---

## 5. Kelola Dokumen

### Upload Dokumen

1. Klik menu **Dokumen** di sidebar
2. Klik **+ Tambah Dokumen**
3. Isi form:
   - **Judul Dokumen**
   - **Kategori** (Peraturan/SK/Panduan/dll)
   - **Deskripsi**
   - **Upload File** (PDF, DOC, XLS - maks 25MB)
4. Klik **Simpan**

### Format yang Diperbolehkan

| Tipe        | Ekstensi          |
| ----------- | ----------------- |
| Dokumen     | .pdf, .doc, .docx |
| Spreadsheet | .xls, .xlsx       |
| Gambar      | .jpg, .png, .gif  |

---

## 6. Pengaduan

### Melihat Daftar Pengaduan

1. Klik menu **Pengaduan** di sidebar
2. Lihat daftar pengaduan dengan status:
   - 🟡 **Pending** - Baru masuk
   - 🔵 **Diproses** - Sedang ditangani
   - 🟢 **Selesai** - Sudah ditanggapi

### Menanggapi Pengaduan

1. Klik pengaduan untuk melihat detail
2. Isi kolom **Tanggapan**
3. Ubah status ke **Diproses** atau **Selesai**
4. Klik **Simpan**

---

## 7. Pengaturan

### Pengaturan Umum

- **Nama Website**
- **Deskripsi**
- **Logo**
- **Kontak**
- **Alamat**
- **Jam Operasional**

### Pengaturan Slider

1. Klik tab **Slider**
2. Tambah/edit slide dengan:
   - Gambar
   - Judul
   - Deskripsi
   - Link
3. Toggle **Aktif** untuk menampilkan

### Pengaturan Profil

1. Klik tab **Profil**
2. Edit konten:
   - Visi & Misi
   - Tugas & Fungsi
   - Struktur Organisasi
   - dll

---

## 8. Manajemen Pengguna

> ⚠️ **Hanya Super Admin** yang dapat mengakses

### Menambah Pengguna

1. Klik menu **Pengguna** di sidebar
2. Klik **+ Tambah Pengguna**
3. Isi form:
   - Nama
   - Email
   - Password
   - Role (Super Admin/Admin/Operator/Viewer)
4. Klik **Simpan**

### Role & Hak Akses

| Role        | Hak Akses                               |
| ----------- | --------------------------------------- |
| Super Admin | Semua akses, termasuk kelola pengguna   |
| Admin       | CRUD berita, galeri, dokumen, pengaduan |
| Operator    | Lihat & edit data tertentu              |
| Viewer      | Hanya melihat data                      |

---

## Tips Penggunaan

### Shortcut Keyboard

| Shortcut   | Aksi               |
| ---------- | ------------------ |
| `Ctrl + S` | Simpan             |
| `Escape`   | Tutup modal        |
| `/`        | Fokus ke pencarian |

### Best Practices

1. **Backup data** secara berkala
2. **Gunakan password kuat** (min. 8 karakter)
3. **Logout** setelah selesai bekerja
4. **Kompres gambar** sebelum upload
5. **Periksa preview** sebelum publikasi

---

## Troubleshooting

### Tidak Bisa Login

- Pastikan email dan password benar
- Hubungi Super Admin jika akun dinonaktifkan

### Upload Gagal

- Periksa ukuran file (maks 25MB)
- Pastikan format file diizinkan
- Coba refresh halaman

### Halaman Error

- Refresh halaman (Ctrl + F5)
- Clear cache browser
- Hubungi tim teknis

---

## Kontak Support

📧 **Email:** support@bundapaud.surabaya.go.id  
📞 **Telepon:** (031) 5xxx-xxxx  
🕐 **Jam Kerja:** Senin-Jumat, 08:00-16:00 WIB

---

_Dokumentasi ini terakhir diperbarui: Januari 2026_
