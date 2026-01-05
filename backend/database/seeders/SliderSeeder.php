<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sliders = [
            [
                'title' => 'Selamat Datang di Bunda PAUD Kota Surabaya',
                'caption' => 'Membangun Generasi Emas Indonesia melalui Pendidikan Anak Usia Dini yang Berkualitas',
                'image' => 'https://picsum.photos/1920/600?random=1',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'PAUD Holistik Integratif',
                'caption' => 'Layanan pendidikan, kesehatan, gizi, pengasuhan, dan perlindungan anak secara menyeluruh',
                'image' => 'https://picsum.photos/1920/600?random=2',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Gerakan 7 KAIH',
                'caption' => '7 Kebaikan untuk Anak Indonesia Hebat - Mari bergabung dalam gerakan nasional untuk anak-anak kita',
                'image' => 'https://picsum.photos/1920/600?random=3',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => '6 Program Prioritas Kemendikdasmen',
                'caption' => 'Mendukung implementasi 6 program prioritas untuk PAUD berkualitas',
                'image' => 'https://picsum.photos/1920/600?random=4',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::create($slider);
        }
    }
}
