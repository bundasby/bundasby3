<?php

namespace Database\Seeders;

use App\Models\BundaTv;
use Illuminate\Database\Seeder;

class BundaTvSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'title' => 'Talkshow PAUD Holistik Integratif Surabaya 2025',
                'description' => 'Diskusi mendalam tentang implementasi PAUD Holistik Integratif di Surabaya bersama pakar pendidikan anak usia dini',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1611162616475-46b635cb6868?w=640&h=360&fit=crop',
                'category' => 'talkshow',
                'is_active' => true,
            ],
            [
                'title' => 'Tutorial Pembelajaran Kreatif PAUD',
                'description' => 'Tips dan trik pembelajaran kreatif untuk anak usia dini. Cocok untuk guru dan orang tua',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=640&h=360&fit=crop',
                'category' => 'tutorial',
                'is_active' => true,
            ],
            [
                'title' => 'Dokumentasi Konferensi Bunda PAUD 2025',
                'description' => 'Rekaman lengkap Konferensi Bunda PAUD Kota Surabaya Tahun 2025',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1544776193-352d25ca82cd?w=640&h=360&fit=crop',
                'category' => 'dokumentasi',
                'is_active' => true,
            ],
            [
                'title' => 'Webinar Pencegahan Stunting pada Anak Usia Dini',
                'description' => 'Webinar bersama Dinas Kesehatan tentang pencegahan stunting dan pemenuhan gizi anak',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=640&h=360&fit=crop',
                'category' => 'talkshow',
                'is_active' => true,
            ],
            [
                'title' => 'Tutorial Stimulasi Motorik Halus Anak',
                'description' => 'Panduan stimulasi motorik halus untuk mengembangkan kreativitas anak',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=640&h=360&fit=crop',
                'category' => 'tutorial',
                'is_active' => true,
            ],
            [
                'title' => 'Launching Program Makan Bergizi Gratis',
                'description' => 'Dokumentasi peluncuran program MBG untuk anak PAUD se-Kota Surabaya',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1567521464027-f127ff144326?w=640&h=360&fit=crop',
                'category' => 'dokumentasi',
                'is_active' => true,
            ],
            [
                'title' => 'Pelatihan Guru PAUD: Metode Montessori',
                'description' => 'Pelatihan metode pembelajaran Montessori untuk guru PAUD',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=640&h=360&fit=crop',
                'category' => 'tutorial',
                'is_active' => true,
            ],
            [
                'title' => 'Kunjungan Menteri Pendidikan ke Surabaya',
                'description' => 'Dokumentasi kunjungan Menteri Pendidikan dan Kebudayaan ke PAUD Unggulan Surabaya',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?w=640&h=360&fit=crop',
                'category' => 'dokumentasi',
                'is_active' => true,
            ],
            [
                'title' => 'Talkshow: Peran Orang Tua dalam PAUD HI',
                'description' => 'Diskusi tentang pentingnya peran orang tua dalam pendidikan anak usia dini',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1476703993599-0035a21b17a9?w=640&h=360&fit=crop',
                'category' => 'talkshow',
                'is_active' => true,
            ],
            [
                'title' => 'Festival Kreativitas Anak PAUD 2025',
                'description' => 'Kompilasi penampilan anak-anak PAUD dalam Festival Kreativitas Surabaya',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1565538810643-b5bdb714032a?w=640&h=360&fit=crop',
                'category' => 'dokumentasi',
                'is_active' => true,
            ],
        ];

        foreach ($data as $item) {
            BundaTv::firstOrCreate(['title' => $item['title']], $item);
        }
    }
}

