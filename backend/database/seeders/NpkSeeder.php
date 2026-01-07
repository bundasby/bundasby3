<?php

namespace Database\Seeders;

use App\Models\Npk;
use Illuminate\Database\Seeder;

class NpkSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'title' => 'NPK Penyelenggaraan PAUD',
                'description' => 'Norma, Prosedur, dan Kriteria penyelenggaraan PAUD',
                'file_url' => '#',
                'file_type' => 'PDF',
                'category' => 'penyelenggaraan',
                'is_active' => true,
            ],
            [
                'title' => 'NPK Akreditasi PAUD',
                'description' => 'Standar akreditasi lembaga PAUD',
                'file_url' => '#',
                'file_type' => 'PDF',
                'category' => 'akreditasi',
                'is_active' => true,
            ],
        ];

        foreach ($data as $item) {
            Npk::firstOrCreate(['title' => $item['title']], $item);
        }
    }
}
