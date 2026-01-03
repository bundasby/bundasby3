<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();

        $articles = [
            [
                'title' => 'Konferensi Bunda PAUD Kota Surabaya 2026',
                'excerpt' => 'Acara tahunan yang mempertemukan seluruh Bunda PAUD se-Kota Surabaya untuk berbagi pengalaman dan best practices.',
                'content' => '<p>Konferensi Bunda PAUD Kota Surabaya 2026 telah sukses diselenggarakan pada tanggal 1 Januari 2026 di Gedung Siola Surabaya.</p><h3>Tema Konferensi</h3><p>Konferensi tahun ini mengangkat tema "Mewujudkan Generasi Emas 2045 Melalui Pendidikan Anak Usia Dini yang Berkualitas".</p>',
                'thumbnail' => 'https://placehold.co/1200x600/2563eb/ffffff?text=Konferensi+2026',
                'category' => 'kegiatan',
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'Pelatihan Digitalisasi PAUD Batch 2',
                'excerpt' => 'Program pelatihan untuk meningkatkan kompetensi digital guru PAUD dalam pengelolaan pembelajaran.',
                'content' => '<p>Bunda PAUD Kota Surabaya bekerja sama dengan Dinas Pendidikan menyelenggarakan Pelatihan Digitalisasi PAUD Batch 2.</p><h3>Materi Pelatihan</h3><ul><li>Penggunaan aplikasi pembelajaran interaktif</li><li>Pembuatan media pembelajaran digital</li></ul>',
                'thumbnail' => 'https://placehold.co/1200x600/16a34a/ffffff?text=Pelatihan+Digital',
                'category' => 'edukasi',
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Launching SIBUNDA 2.0',
                'excerpt' => 'Pembaruan sistem informasi Bunda PAUD dengan fitur-fitur terbaru untuk kemudahan pelaporan.',
                'content' => '<p>SIBUNDA (Sistem Informasi Bunda PAUD) versi 2.0 resmi diluncurkan pada tanggal 25 Desember 2025.</p><h3>Fitur Baru</h3><ul><li>Dashboard Real-time</li><li>Modul Pelaporan</li><li>Mobile App</li></ul>',
                'thumbnail' => 'https://placehold.co/1200x600/f59e0b/ffffff?text=SIBUNDA+2.0',
                'category' => 'pengumuman',
                'is_published' => true,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Webinar Pencegahan Stunting untuk Guru PAUD',
                'excerpt' => 'Webinar interaktif tentang peran guru PAUD dalam pencegahan stunting.',
                'content' => '<p>Webinar ini menghadirkan narasumber ahli gizi dan kesehatan anak untuk membahas strategi pencegahan stunting.</p>',
                'thumbnail' => 'https://placehold.co/1200x600/dc2626/ffffff?text=Webinar+Stunting',
                'category' => 'edukasi',
                'is_published' => true,
                'published_at' => now()->subDays(12),
            ],
            [
                'title' => 'Pendaftaran Program MBG 2026 Dibuka',
                'excerpt' => 'Lembaga PAUD dapat mendaftarkan diri untuk mengikuti Program Makanan Bergizi Gratis tahun 2026.',
                'content' => '<p>Program Makanan Bergizi Gratis (MBG) 2026 kembali dibuka untuk pendaftaran.</p><p>Lembaga PAUD yang berminat dapat mendaftar melalui SIBUNDA.</p>',
                'thumbnail' => 'https://placehold.co/1200x600/8b5cf6/ffffff?text=Pendaftaran+MBG',
                'category' => 'pengumuman',
                'is_published' => true,
                'published_at' => now()->subDays(17),
            ],
        ];

        foreach ($articles as $article) {
            Article::create([
                ...$article,
                'slug' => Str::slug($article['title']),
                'author_id' => $admin->id,
            ]);
        }
    }
}
