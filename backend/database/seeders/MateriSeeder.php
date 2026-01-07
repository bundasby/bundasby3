<?php

namespace Database\Seeders;

use App\Models\Materi;
use Illuminate\Database\Seeder;

class MateriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'title' => 'Panduan Pembelajaran PAUD 2025',
                'description' => 'Modul panduan pembelajaran untuk guru PAUD',
                'file_url' => '#',
                'file_type' => 'PDF',
                'file_size' => '2.5 MB',
                'category' => 'panduan',
                'is_active' => true,
            ],
            [
                'title' => 'Materi Pelatihan Guru PAUD',
                'description' => 'Materi pelatihan peningkatan kompetensi guru',
                'file_url' => '#',
                'file_type' => 'PDF',
                'file_size' => '3.2 MB',
                'category' => 'pelatihan',
                'is_active' => true,
            ],
            [
                'title' => 'Kurikulum Merdeka PAUD',
                'description' => 'Implementasi kurikulum merdeka untuk PAUD',
                'file_url' => '#',
                'file_type' => 'PDF',
                'file_size' => '1.8 MB',
                'category' => 'kurikulum',
                'is_active' => true,
            ],
        ];

        foreach ($data as $item) {
            Materi::firstOrCreate(['title' => $item['title']], $item);
        }
    }
}
