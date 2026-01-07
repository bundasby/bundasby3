<?php

namespace Database\Seeders;

use App\Models\Infografis;
use Illuminate\Database\Seeder;

class InfografisSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'title' => 'Infografis PAUD Holistik Integratif',
                'description' => 'Penjelasan lengkap tentang program PAUD Holistik Integratif dan 5 layanan esensial',
                'image_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&h=800&fit=crop',
                'category' => 'program',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title' => 'Pencegahan Stunting pada Anak',
                'description' => 'Informasi lengkap pencegahan stunting dan 1000 hari pertama kehidupan',
                'image_url' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=600&h=800&fit=crop',
                'category' => 'kesehatan',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'title' => 'Gerakan 7 KAIH',
                'description' => '7 Kebaikan untuk Anak Indonesia Hebat - Program unggulan Bunda PAUD',
                'image_url' => 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=600&h=800&fit=crop',
                'category' => 'gerakan',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'title' => 'Tahapan Perkembangan Anak 0-6 Tahun',
                'description' => 'Panduan tahapan perkembangan anak dari lahir hingga usia 6 tahun',
                'image_url' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&h=800&fit=crop',
                'category' => 'edukasi',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'title' => 'Imunisasi Lengkap untuk Anak',
                'description' => 'Jadwal imunisasi lengkap sesuai rekomendasi Kementerian Kesehatan',
                'image_url' => 'https://images.unsplash.com/photo-1559825481-12a05cc00344?w=600&h=800&fit=crop',
                'category' => 'kesehatan',
                'is_active' => true,
                'order' => 5,
            ],
            [
                'title' => 'Program Makan Bergizi Gratis',
                'description' => 'Informasi tentang program MBG untuk anak PAUD di Surabaya',
                'image_url' => 'https://images.unsplash.com/photo-1567521464027-f127ff144326?w=600&h=800&fit=crop',
                'category' => 'program',
                'is_active' => true,
                'order' => 6,
            ],
            [
                'title' => 'Deteksi Dini Tumbuh Kembang Anak',
                'description' => 'Panduan deteksi dini gangguan tumbuh kembang anak untuk orang tua',
                'image_url' => 'https://images.unsplash.com/photo-1544776193-352d25ca82cd?w=600&h=800&fit=crop',
                'category' => 'kesehatan',
                'is_active' => true,
                'order' => 7,
            ],
            [
                'title' => 'Stimulasi Anak Usia Dini',
                'description' => 'Cara menstimulasi perkembangan kognitif, motorik, dan sosial anak',
                'image_url' => 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=600&h=800&fit=crop',
                'category' => 'edukasi',
                'is_active' => true,
                'order' => 8,
            ],
            [
                'title' => 'Struktur Organisasi Bunda PAUD Surabaya',
                'description' => 'Struktur organisasi Gerakan PAUD Berkualitas Kota Surabaya',
                'image_url' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=600&h=800&fit=crop',
                'category' => 'organisasi',
                'is_active' => true,
                'order' => 9,
            ],
            [
                'title' => 'Capaian PAUD Surabaya 2025',
                'description' => 'Data dan statistik capaian PAUD Kota Surabaya tahun 2025',
                'image_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&h=800&fit=crop',
                'category' => 'statistik',
                'is_active' => true,
                'order' => 10,
            ],
        ];

        foreach ($data as $item) {
            Infografis::firstOrCreate(['title' => $item['title']], $item);
        }
    }
}

