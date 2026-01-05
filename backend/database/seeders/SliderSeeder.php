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
                'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=1920&h=600&fit=crop',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'PAUD Holistik Integratif',
                'caption' => 'Layanan pendidikan, kesehatan, gizi, pengasuhan, dan perlindungan anak secara menyeluruh',
                'image' => 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=1920&h=600&fit=crop',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Gerakan 7 KAIH',
                'caption' => '7 Kebaikan untuk Anak Indonesia Hebat - Mari bergabung dalam gerakan nasional untuk anak-anak kita',
                'image' => 'https://images.unsplash.com/photo-1544776193-352d25ca82cd?w=1920&h=600&fit=crop',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => '6 Program Prioritas Kemendikdasmen',
                'caption' => 'Mendukung implementasi 6 program prioritas untuk PAUD berkualitas',
                'image' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=1920&h=600&fit=crop',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::create($slider);
        }
    }
}
