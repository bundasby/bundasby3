<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'title' => 'Penguatan Pendidikan Karakter',
                'description' => 'Penanaman karakter 7 kebiasaan anak Indonesia hebat melalui pendidikan nilai dan bimbingan konseling.',
                'icon' => 'heart',
                'image' => 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=600&h=400&fit=crop',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title' => 'Wajib Belajar 13 Tahun',
                'description' => 'Pemerataan kesempatan pendidikan termasuk PAUD, pendidikan jarak jauh, dan fasilitasi relawan mengajar.',
                'icon' => 'school',
                'image' => 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=600&h=400&fit=crop',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'title' => 'Peningkatan Kompetensi Guru',
                'description' => 'Peningkatan kualifikasi pendidikan guru minimal D-IV/S-1 serta pelatihan dan sertifikasi guru PAUD.',
                'icon' => 'users',
                'image' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=600&h=400&fit=crop',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'title' => 'Literasi, Numerasi & Sains',
                'description' => 'Penguatan pendidikan matematika, sains, dan teknologi untuk anak usia dini.',
                'icon' => 'book',
                'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&h=400&fit=crop',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'title' => 'Sarana dan Prasarana',
                'description' => 'Pemenuhan dan perbaikan fasilitas sekolah PAUD di seluruh Kota Surabaya.',
                'icon' => 'building',
                'image' => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=600&h=400&fit=crop',
                'is_active' => true,
                'order' => 5,
            ],
            [
                'title' => 'Bahasa dan Sastra',
                'description' => 'Pemartabatan bahasa negara dan pelindungan bahasa daerah dalam pembelajaran.',
                'icon' => 'language',
                'image' => 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=600&h=400&fit=crop',
                'is_active' => true,
                'order' => 6,
            ],
        ];

        foreach ($programs as $program) {
            DB::table('programs')->insert(array_merge($program, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
