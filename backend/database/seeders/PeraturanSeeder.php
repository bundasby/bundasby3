<?php

namespace Database\Seeders;

use App\Models\Peraturan;
use Illuminate\Database\Seeder;

class PeraturanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'title' => 'Peraturan Walikota tentang Penyelenggaraan PAUD',
                'description' => 'Peraturan Walikota Surabaya tentang penyelenggaraan pendidikan anak usia dini',
                'file_url' => '#',
                'file_type' => 'PDF',
                'nomor_peraturan' => 'Perwali No. 12',
                'tahun' => 2024,
                'is_active' => true,
            ],
            [
                'title' => 'Peraturan Daerah tentang Perlindungan Anak',
                'description' => 'Perda Kota Surabaya tentang perlindungan anak',
                'file_url' => '#',
                'file_type' => 'PDF',
                'nomor_peraturan' => 'Perda No. 5',
                'tahun' => 2023,
                'is_active' => true,
            ],
        ];

        foreach ($data as $item) {
            Peraturan::firstOrCreate(['title' => $item['title']], $item);
        }
    }
}
