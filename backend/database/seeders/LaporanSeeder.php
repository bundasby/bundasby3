<?php

namespace Database\Seeders;

use App\Models\Laporan;
use Illuminate\Database\Seeder;

class LaporanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'title' => 'Laporan Tahunan Bunda PAUD 2024',
                'description' => 'Laporan kegiatan dan capaian Bunda PAUD tahun 2024',
                'file_url' => '#',
                'file_type' => 'PDF',
                'periode' => 'Tahunan',
                'tahun' => 2024,
                'is_active' => true,
            ],
            [
                'title' => 'Laporan Semester 1 Tahun 2025',
                'description' => 'Laporan kegiatan semester pertama tahun 2025',
                'file_url' => '#',
                'file_type' => 'PDF',
                'periode' => 'Semester 1',
                'tahun' => 2025,
                'is_active' => true,
            ],
        ];

        foreach ($data as $item) {
            Laporan::firstOrCreate(['title' => $item['title']], $item);
        }
    }
}
