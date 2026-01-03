<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $galleries = [
            [
                'title' => 'Pembukaan Konferensi 2025',
                'description' => 'Acara pembukaan konferensi Bunda PAUD Kota Surabaya',
                'image_url' => 'https://placehold.co/600x400/2563eb/ffffff?text=Konferensi+1',
                'category' => 'Konferensi 2025',
                'order' => 1,
            ],
            [
                'title' => 'Sambutan Bunda PAUD',
                'description' => 'Sambutan dari Bunda PAUD Kota Surabaya',
                'image_url' => 'https://placehold.co/600x400/2563eb/ffffff?text=Konferensi+2',
                'category' => 'Konferensi 2025',
                'order' => 2,
            ],
            [
                'title' => 'Workshop Guru PAUD',
                'description' => 'Sesi workshop pelatihan guru PAUD',
                'image_url' => 'https://placehold.co/600x400/16a34a/ffffff?text=Pelatihan+1',
                'category' => 'Pelatihan Guru',
                'order' => 1,
            ],
            [
                'title' => 'Praktik Mengajar',
                'description' => 'Guru PAUD melakukan praktik mengajar',
                'image_url' => 'https://placehold.co/600x400/16a34a/ffffff?text=Pelatihan+2',
                'category' => 'Pelatihan Guru',
                'order' => 2,
            ],
            [
                'title' => 'Pembagian Makanan Bergizi',
                'description' => 'Program MBG di PAUD Surabaya',
                'image_url' => 'https://placehold.co/600x400/f59e0b/ffffff?text=MBG+1',
                'category' => 'Program MBG',
                'order' => 1,
            ],
            [
                'title' => 'Anak-anak Makan Bersama',
                'description' => 'Anak-anak PAUD menikmati makanan bergizi',
                'image_url' => 'https://placehold.co/600x400/f59e0b/ffffff?text=MBG+2',
                'category' => 'Program MBG',
                'order' => 2,
            ],
        ];

        foreach ($galleries as $gallery) {
            Gallery::create([
                ...$gallery,
                'is_active' => true,
            ]);
        }
    }
}
